from __future__ import annotations

from dataclasses import dataclass
from typing import Any

from .models import BacklogItem


@dataclass(frozen=True)
class Prompt:
    system: str
    user: str


def build_prompt(*, voice_profile_md: str, brand_profile_yaml: str, item: BacklogItem) -> Prompt:
    system = "You are writing a blog post for dadudekc.com in Victor's voice. Follow VOICE_PROFILE exactly."

    user = (
        "INPUTS:\n"
        "VOICE_PROFILE:\n"
        f"<<<{voice_profile_md}>>>\n\n"
        "BRAND_PROFILE:\n"
        f"<<<{brand_profile_yaml}>>>\n\n"
        "POST_BRIEF:\n"
        f"- title: {item.title}\n"
        f"- audience: {item.audience}\n"
        f"- pillar: {item.pillar}\n"
        f"- angle: {item.angle}\n"
        f"- keywords: {item.keywords}\n"
        f"- CTA: {item.cta}\n\n"
        "REQUIREMENTS:\n"
        "- 900–1400 words\n"
        "- Use H2/H3 headings\n"
        "- Include 1 short real-world example relevant to the audience\n"
        "- End with a clear CTA matching the CTA type\n"
        "- Output markdown only\n"
        "\nWRITE:\n"
    )

    return Prompt(system=system, user=user)
