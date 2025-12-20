# Websites Repository

Unified repository for all WordPress themes, plugins, and static websites.

## Sites (8 Active + 3 Pending)

### Active Websites

1. **FreeRideInvestor** (`FreeRideInvestor/`)
   - Trading education platform
   - URL: https://freerideinvestor.com
   - Type: WordPress theme

2. **Southwest Secret** (`southwestsecret.com/`)
   - Music/DJ showcase site
   - URL: https://southwestsecret.com
   - Type: Static HTML + WordPress theme

3. **WE ARE SWARM** (`Swarm_website/`)
   - Swarm intelligence platform
   - URL: https://weareswarm.online
   - Type: WordPress theme with CI/CD

4. **AriaJet** (`ariajet.site/`)
   - Personal website with games
   - URL: https://ariajet.site
   - Type: Static HTML + WordPress theme

5. **Prism Blossom** (`prismblossom.online/`)
   - Personal/Portfolio site
   - URL: https://prismblossom.online
   - Type: WordPress theme

6. **DaduDekC** (`dadudekc.com/`)
   - Blog/Personal website
   - URL: https://dadudekc.com
   - Type: Blog/Website

7. **Journal App** (`journal-app/`)
   - Personal journaling application
   - Type: Static HTML/JS app

8. **TradingRobotPlug** (`TradingRobotPlugWeb/`)
   - Trading automation platform
   - Type: WordPress theme (in development)

### Additional Directories

- **FreeRideInvestor_V2** - Backup/archive version
- **side-projects** - Games and experimental projects
- **tools** - Deployment and maintenance tools
- **wordpress-plugins** - Shared WordPress plugins
- **docs** - Documentation and guides

## Structure

```
websites/
├── [WEBSITE_DIRECTORIES]/
│   ├── ariajet.site/
│   ├── dadudekc.com/
│   ├── FreeRideInvestor/
│   ├── journal-app/
│   ├── prismblossom.online/
│   ├── southwestsecret.com/
│   ├── Swarm_website/
│   └── TradingRobotPlugWeb/
├── docs/
│   ├── deployment/        # Deployment guides
│   ├── sites/            # Site-specific documentation
│   └── consolidation/    # Consolidation notes
├── side-projects/        # Games and experiments
├── tools/                # Deployment tools
└── wordpress-plugins/    # Shared plugins
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
