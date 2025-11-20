# Auto-Deployment System

## Overview

The auto-deployment system automatically deploys changed files to live WordPress websites when you commit changes to the GitHub repository.

## How It Works

1. **Pre-commit Hook**: When you run `git commit`, the pre-commit hook automatically triggers
2. **File Detection**: The hook detects all staged files that have changed
3. **Site Mapping**: Files are mapped to appropriate WordPress sites based on their path:
   - `FreeRideInvestor/` → freerideinvestor.com
   - `southwestsecret.com/` → southwestsecret.com
   - `Swarm_website/` → weareswarm.site
   - `TradingRobotPlugWeb/` → tradingrobotplug.com
4. **Deployment**: Changed files are automatically deployed to the correct site via SFTP
5. **Commit Proceeds**: After deployment, the commit completes normally

## Usage

### Normal Workflow

```bash
# 1. Make changes to website files
# 2. Stage changes
git add southwestsecret.com/wordpress-theme/southwestsecret/page-carmyn.php

# 3. Commit (auto-deployment happens automatically)
git commit -m "Update Carmyn page"

# 4. Push to GitHub (backup)
git push origin master
```

### Dry Run (Test Without Deploying)

```bash
python tools/auto_deploy_hook.py --dry-run
```

This shows which files would be deployed to which sites without actually deploying.

### Manual Deployment

```bash
python tools/auto_deploy_hook.py --auto-deploy
```

## File Type Detection

The system automatically detects:

- **Theme Files**: PHP, CSS, JS files in theme directories
- **Plugin Files**: Files in plugin directories
- **Root Theme Files**: Files directly in site root (e.g., `FreeRideInvestor/style.css`)

## Path Mapping Examples

| Local Path | Site | Remote Path |
|------------|------|-------------|
| `FreeRideInvestor/style.css` | freerideinvestor | `themes/FreeRideInvestor/style.css` |
| `southwestsecret.com/wordpress-theme/southwestsecret/page-carmyn.php` | southwestsecret | `themes/southwestsecret-theme/page-carmyn.php` |
| `Swarm_website/wp-content/themes/swarm-theme/functions.php` | weareswarm | `themes/swarm-theme/functions.php` |
| `Swarm_website/wp-content/themes/swarm-theme/js/main.js` | weareswarm | `themes/swarm-theme/js/main.js` |

## Requirements

1. **Credentials**: `.deploy_credentials/sites.json` must exist in the main repository
2. **WordPressDeploymentManager**: Must be available at `D:/Agent_Cellphone_V2_Repository/tools/wordpress_deployment_manager.py`
3. **Git**: Must have staged changes for deployment to trigger

## Error Handling

- If a file fails to deploy, the error is logged but the commit still proceeds
- Check the deployment summary at the end of the commit output
- Failed deployments can be retried manually

## Troubleshooting

### Hook Not Running

Check if the hook exists:
```bash
ls -la .git/hooks/pre-commit
```

### Files Not Deploying

1. Check if files are staged: `git status`
2. Verify file path matches site mapping
3. Check credentials file exists
4. Run dry-run to see what would be deployed

### Deployment Errors

1. Check SFTP connection to Hostinger server
2. Verify credentials in `.deploy_credentials/sites.json`
3. Check file permissions on remote server
4. Review error messages in commit output

## Configuration

### Site Mapping

Edit `tools/auto_deploy_hook.py` to modify site mappings:

```python
SITE_MAPPING = {
    "FreeRideInvestor": "freerideinvestor",
    "southwestsecret.com": "southwestsecret",
    "Swarm_website": "weareswarm",
    "TradingRobotPlugWeb": "tradingrobotplug",
}
```

### Disable Auto-Deployment

To skip deployment for a specific commit:

```bash
git commit --no-verify -m "Skip deployment for this commit"
```

## Benefits

✅ **Automatic**: No manual deployment steps needed  
✅ **Fast**: Only changed files are deployed  
✅ **Safe**: Files are backed up on GitHub before deployment  
✅ **Reliable**: Atomic file operations prevent corruption  
✅ **Transparent**: Detailed logging shows what's being deployed  

## Support

For issues or questions, check:
- Deployment logs in commit output
- `tools/auto_deploy_hook.py` source code
- `tools/wordpress_deployment_manager.py` documentation

