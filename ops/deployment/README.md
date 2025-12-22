# WordPress Deployment Tools

**Location:** `ops/deployment/`  
**Purpose:** WordPress site deployment and management automation

## Tools Overview

### Core Deployment

- **auto_deploy_hook.py** - Auto-deployment hook for git commits
- **deploy_all_websites.py** - Deploy to all registered websites
- **deploy_website_fixes.py** - Deploy fixes and updates
- **deploy_and_activate_themes.py** - Deploy and activate WordPress themes automatically

### WordPress Management

- **check_wordpress_updates.py** - Check for WordPress core updates
- **check_wordpress_versions.py** - Check versions across sites
- **wordpress_version_checker.py** - Version checking utility
- **activate_themes.py** - Activate themes on WordPress sites (updated with WordPressManager support)

### Verification

- **verify_website_fixes.py** - Verify deployed fixes

## Usage

### Auto-Deployment Hook

```bash
# Automatically triggered on git commit
# Or run manually:
python ops/deployment/auto_deploy_hook.py --auto-deploy
```

### Deploy All Websites

```bash
python ops/deployment/deploy_all_websites.py
```

### Deploy and Activate Themes

```bash
# Deploy and activate theme for specific site
python ops/deployment/deploy_and_activate_themes.py --site houstonsipqueen.com

# Deploy themes for all configured sites
python ops/deployment/deploy_and_activate_themes.py --all

# Upload only, don't activate
python ops/deployment/deploy_and_activate_themes.py --all --upload-only
```

### Activate Themes (Upload Already Complete)

```bash
# Activate theme for specific site
python ops/deployment/activate_themes.py --site houstonsipqueen.com

# Activate themes for all sites
python ops/deployment/activate_themes.py --all
```

### Check WordPress Updates

```bash
python ops/deployment/check_wordpress_updates.py
```

## Integration

These tools work with:
- **Site Registry:** `configs/sites_registry.json`
- **Canonical Locations:** `websites/<domain>/wp/wp-content/`
- **Legacy Paths:** Maintained for backward compatibility during transition

## Dependencies

- WordPressManager from main repository (`D:/Agent_Cellphone_V2_Repository/tools/wordpress_manager.py`)
- Site configurations in `configs/site_configs.json`
- Deployment credentials (stored securely, not in repo)

## Migration Status

✅ **Migrated to ops/deployment/** (2025-12-20)
- All WordPress deployment tools now in canonical location
- Tools in `tools/` maintained for backward compatibility

