# Websites Monorepo

This repository contains the source (themes/templates/content) for multiple small websites and WordPress-based sites, plus internal tooling for packaging and verifying changes.

## What’s in this repo

- **WordPress themes** stored alongside each site/domain folder
- **Static HTML/CSS/JS** for lightweight pages and side projects
- **Custom utilities** under `tools/` (verification, packaging, maintenance scripts)
- **Custom WordPress plugins** under `wordpress-plugins/`

## Repository layout (high level)

| Path | What it contains | Notes |
|------|------------------|-------|
| `FreeRideInvestor/` | WordPress theme code + a large snapshot of plugins/assets | Legacy/monolithic; includes `docker-compose.yml` and many third-party plugin files. |
| `FreeRideInvestor_V2/` | A cleaner standalone WordPress theme | Theme files at the folder root (e.g., `functions.php`, `style.css`). |
| `prismblossom.online/wordpress-theme/prismblossom/` | WordPress theme for `prismblossom.online` | Theme-only folder. |
| `ariajet.site/` | Static pages + WordPress theme | Static `index.html` + games; theme in `wordpress-theme/ariajet/`. |
| `Swarm_website/wp-content/themes/swarm-theme/` | WordPress theme for the Swarm site | Includes theme templates and JS/CSS. |
| `dadudekc.com/blog-posts/` | Blog drafts/content | Markdown + HTML drafts. |
| `side-projects/` | Small experimental pages | Standalone HTML content. |
| `wordpress-plugins/` | Custom plugins | Each plugin has its own folder and readme. |
| `docs/` | Internal maintenance documentation | Operational notes and guides. |
| `tools/` | Helper scripts | Packaging, verification, and maintenance automation. |

## Working with WordPress themes

- **Theme locations vary** by site. Look for either:
  - `*/wordpress-theme/<theme-name>/` (theme-only folder), or
  - `*/wp-content/themes/<theme-name>/` (WordPress-style tree), or
  - a theme stored at the folder root (e.g., `FreeRideInvestor_V2/`).
- **To install a theme**: copy the theme folder into your WordPress install at `wp-content/themes/`, then activate it in **Appearance → Themes**.

## Deployment (high level)

This repository **does not store production credentials**. Deployment is expected to be done via one of:

- **Manual upload** (SFTP / hosting file manager / WordPress Theme Editor) of the changed theme files
- **Packaging + verification helpers**:
  - `python tools/deploy_website_fixes.py` (creates zip packages and prints file-by-file instructions)
  - `python tools/verify_website_fixes.py` (sanity-checks a few live endpoints)

## Security & secrets

- **Do not commit secrets** (hosting credentials, API keys, application passwords).
- Keep any credentials in **local-only** files (ignored by git) or injected via environment variables.
- This repo contains **third-party code** (notably under `FreeRideInvestor/plugins/`). Treat updates and security reviews as part of routine maintenance.

## Contributing guidelines

- Keep changes **scoped to one site** when possible (makes review and deployment safer).
- Prefer small, well-described commits (what changed + why).
- For WordPress PHP changes, validate syntax before deployment if you have PHP available (`php -l <file>`).
