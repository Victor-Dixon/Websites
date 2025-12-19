# Websites Repository

Unified repository for all WordPress themes, plugins, and static websites.

## Sites

- **FreeRideInvestor** - Trading education platform
- **Southwest Secret** - Music/DJ showcase site
- **WE ARE SWARM** - Swarm intelligence platform
- **TradingRobotPlug** - Trading automation platform

## Structure

```
websites/
├── themes/
│   ├── freerideinvestor/
│   ├── southwestsecret/
│   ├── swarm/
│   └── tradingrobotplug/
├── plugins/
│   └── (shared plugins)
├── tools/
│   └── (deployment tools)
└── docs/
    └── (documentation)
```

## Auto-Deployment

This repository is configured with pre-commit hooks that automatically deploy changes to live websites when code is pushed to GitHub.

### Deployment Process

1. Make changes to theme/plugin files
2. Commit changes: `git commit -m "Update theme"`
3. Pre-commit hook triggers deployment
4. Push to GitHub: `git push origin main`
5. Changes are live on website

## Setup

1. Clone repository: `git clone <repo-url>`
2. Install dependencies: `pip install -r requirements.txt`
3. Configure deployment: Update `tools/wordpress_deployment_manager.py` with credentials
4. Start developing!

## Deployment Tools

- `wordpress_deployment_manager.py` - Main deployment tool
- `wordpress_deployer.py` - Theme deployment
- `deploy_static_html.py` - Static HTML deployment

## Autoblogger (dadudekc.com) — calendar + backlog → daily drafts

Autoblogger runs a **daily pipeline** that selects a post from a rolling calendar/backlog, generates a draft in the correct site's voice, validates it, and saves it to `content/drafts/<site_id>/`.

### SSOT file contract

```
content/
  voices/
  brands/
  backlogs/
  calendars/
  drafts/
sites/
  dadudekc.yaml
  corey.yaml
  kiki.yaml
runtime/
  autoblogger_state__<site_id>.json
```

### Run (queue-only by default)

```bash
python3 -m autoblogger.run_daily --site dadudekc
```

### Dry-run (writes prompt to a draft, no LLM, no publish)

```bash
python3 -m autoblogger.run_daily --site dadudekc --dry-run --date 2025-12-20
```

### Enable generation (OpenAI-compatible)

Set env vars:

- `AUTOBLOGGER_OPENAI_API_KEY` (or `OPENAI_API_KEY`)
- `AUTOBLOGGER_OPENAI_MODEL` (default: `gpt-4o-mini`)
- `AUTOBLOGGER_OPENAI_BASE_URL` (default: `https://api.openai.com/v1`)

### Optional: publish to WordPress

Uses per-site WordPress env vars configured in `sites/<site>.yaml` (see `publish.wp_*_env` keys).

```bash
python3 -m autoblogger.run_daily --site dadudekc --auto-publish --wp-status draft
```

### Cron example (06:00 America/Chicago)

```cron
0 6 * * * TZ=America/Chicago cd /path/to/repo && python3 -m autoblogger.run_daily --site dadudekc >> runtime/autoblogger_cron.log 2>&1
```

### Run all sites (single runner)

```bash
python3 -m autoblogger.run_all_sites --dry-run
```

## Notes

- All sites deploy to Hostinger server (157.173.214.121:65002)
- Credentials stored in environment variables
- Pre-commit hooks ensure automatic deployment
