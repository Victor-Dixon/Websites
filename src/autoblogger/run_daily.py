from __future__ import annotations

import argparse
import json
import re
from dataclasses import dataclass
from datetime import datetime
from pathlib import Path
from zoneinfo import ZoneInfo

import yaml

from .llm_client import generate_markdown, load_llm_config
from .models import BrandProfile
from .paths import content_dir, drafts_dir, runtime_dir
from .prompt_builder import build_prompt
from .selector import (
    get_backlog_item,
    load_backlog,
    load_state,
    mark_backlog_used,
    pick_post_id,
)
from .validator import validate_markdown


def _slugify(s: str) -> str:
    s = s.lower().strip()
    s = re.sub(r"[^a-z0-9\s-]", "", s)
    s = re.sub(r"\s+", "-", s)
    s = re.sub(r"-+", "-", s)
    return s[:80].strip("-") or "post"


def _read_text(path: Path) -> str:
    return path.read_text(encoding="utf-8")


def _load_yaml(path: Path) -> dict:
    if not path.exists():
        return {}
    return yaml.safe_load(_read_text(path)) or {}


def _ensure_dirs() -> None:
    drafts_dir().mkdir(parents=True, exist_ok=True)
    runtime_dir().mkdir(parents=True, exist_ok=True)


def _state_path() -> Path:
    return runtime_dir() / "autoblogger_state.json"


def _write_state(state: dict) -> None:
    _state_path().write_text(json.dumps(state, indent=2, ensure_ascii=False), encoding="utf-8")


def _extract_frontmatter_title(md: str) -> str | None:
    # Optional: if the model emits frontmatter, parse title.
    if not md.startswith("---"):
        return None
    parts = md.split("---", 2)
    if len(parts) < 3:
        return None
    fm = yaml.safe_load(parts[1]) or {}
    title = fm.get("title")
    return str(title).strip() if title else None


def main() -> int:
    parser = argparse.ArgumentParser(description="Autoblogger daily runner")
    parser.add_argument("--date", help="Override date (YYYY-MM-DD). Default: today in America/Chicago")
    parser.add_argument("--timezone", default="America/Chicago", help="Timezone for 'today'")
    parser.add_argument("--auto-publish", action="store_true", help="Publish to WordPress (default: queue only)")
    parser.add_argument("--wp-status", default="draft", choices=["draft", "publish"], help="WP post status")
    parser.add_argument("--dry-run", action="store_true", help="Do everything except LLM + publish")
    parser.add_argument("--site-id", default="dadudekc.com", help="WordPress site id in .deploy_credentials/blogging_api.json")

    args = parser.parse_args()

    tz = ZoneInfo(args.timezone)
    now = datetime.now(tz)
    today = now.date() if not args.date else datetime.fromisoformat(args.date).date()

    _ensure_dirs()

    voice_path = content_dir() / "voice_profile.md"
    brand_path = content_dir() / "brand_profile.yaml"
    backlog_path = content_dir() / "backlog.yaml"
    calendar_path = content_dir() / "calendar.yaml"

    voice_md = _read_text(voice_path)
    brand_yaml = _read_text(brand_path)

    brand = BrandProfile.from_dict(_load_yaml(brand_path))

    backlog = load_backlog(backlog_path)
    state = load_state(_state_path())

    post_id = pick_post_id(today, calendar_path, backlog, state)
    item = get_backlog_item(backlog, post_id)

    prompt = build_prompt(voice_profile_md=voice_md, brand_profile_yaml=brand_yaml, item=item)

    # Draft path
    draft_name = f"{today.isoformat()}--{_slugify(item.title)}.md"
    draft_path = drafts_dir() / draft_name

    history_entry = {
        "ts": now.isoformat(),
        "date": today.isoformat(),
        "post_id": post_id,
        "title": item.title,
        "draft_path": str(draft_path),
        "mode": "publish" if args.auto_publish else "queue",
    }

    try:
        if args.dry_run:
            # Save prompt only (so you can hand it to an agent)
            draft_path.write_text(
                "---\n"
                f"title: {item.title!r}\n"
                f"date: {today.isoformat()}\n"
                f"pillar: {item.pillar}\n"
                f"audience: {item.audience}\n"
                f"cta: {item.cta}\n"
                "---\n\n"
                "# DRAFT NOT GENERATED (dry-run)\n\n"
                "Below is the exact prompt that would be sent.\n\n"
                "```\n"
                + prompt.system
                + "\n\n"
                + prompt.user
                + "\n```\n",
                encoding="utf-8",
            )
            history_entry["status"] = "queued_prompt_only"
        else:
            cfg = load_llm_config()
            md = generate_markdown(prompt, cfg=cfg)

            # Enforce frontmatter (even if model didn't include it)
            existing_title = _extract_frontmatter_title(md)
            if not md.startswith("---"):
                md = (
                    "---\n"
                    f"title: {item.title!r}\n"
                    f"date: {today.isoformat()}\n"
                    f"pillar: {item.pillar}\n"
                    f"audience: {item.audience}\n"
                    f"cta: {item.cta}\n"
                    "---\n\n"
                    + md
                )

            result = validate_markdown(md, word_count_min=brand.word_count_min, word_count_max=brand.word_count_max, cta_type=item.cta)
            if not result.ok:
                raise RuntimeError("Validation failed: " + "; ".join(result.errors))

            draft_path.write_text(md, encoding="utf-8")
            history_entry["status"] = "draft_saved"
            history_entry["word_count"] = result.word_count

            if args.auto_publish:
                # Publish via existing tooling (WP REST) if configured.
                from tools.blog.unified_blogging_automation import UnifiedBloggingAutomation

                excerpt = f"{item.angle}"
                automation = UnifiedBloggingAutomation()
                publish_result = automation.publish_to_site(
                    site_id=args.site_id,
                    title=item.title,
                    content=md,
                    site_purpose=None,
                    excerpt=excerpt,
                    status=args.wp_status,
                )
                if not publish_result.get("success"):
                    raise RuntimeError(f"WP publish failed: {publish_result.get('error')}")

                history_entry["wp"] = {k: publish_result.get(k) for k in ("post_id", "link", "edit_link", "site_id")}
                history_entry["status"] = "published" if args.wp_status == "publish" else "wp_draft_created"

        # Update state (and optionally consume backlog)
        if not args.dry_run:
            mark_backlog_used(backlog_path, post_id)
            used_ids = list(dict.fromkeys((state.get("used_ids") or []) + [post_id]))
            state["used_ids"] = used_ids

        state["history"] = (state.get("history") or []) + [history_entry]
        _write_state(state)
        return 0

    except Exception as e:
        history_entry["status"] = "failed"
        history_entry["error"] = str(e)
        state["failures"] = (state.get("failures") or []) + [history_entry]
        state["history"] = (state.get("history") or []) + [history_entry]
        _write_state(state)
        raise


if __name__ == "__main__":
    raise SystemExit(main())
