# Website Deployment Guide

**Last Updated:** 2025-12-23  
**Status:** All deployment scripts verified and working

## 📋 Overview

All website deployments use a unified system based on `SimpleWordPressDeployer` which supports:
- SFTP deployment via Hostinger credentials (from `.env`)
- Site-specific SFTP credentials (from `site_configs.json`)
- WordPress REST API deployment (requires app passwords)

## 🛠️ Available Deployment Scripts

### 1. Unified Deployer (Recommended)
**File:** `unified_deployer.py`

Works for all websites automatically. Detects files and deploys them.

```bash
# Deploy single site
python ops/deployment/unified_deployer.py --site prismblossom.online

# Deploy all sites
python ops/deployment/unified_deployer.py --all

# Dry run (test without deploying)
python ops/deployment/unified_deployer.py --site prismblossom.online --dry-run
```

### 2. PrismBlossom Specific Deployer
**File:** `deploy_prismblossom.py`

Specialized deployer for prismblossom.online theme files.

```bash
python ops/deployment/deploy_prismblossom.py
```

### 3. Deploy All Websites
**File:** `deploy_all_websites.py`

Deploys pre-configured files for multiple sites.

```bash
python ops/deployment/deploy_all_websites.py
```

### 4. Theme Deployment
**File:** `deploy_and_activate_themes.py`

Deploys and activates WordPress themes.

```bash
python ops/deployment/deploy_and_activate_themes.py --site houstonsipqueen.com
python ops/deployment/deploy_and_activate_themes.py --all
```

### 5. Test All Deployers
**File:** `test_all_deployers.py`

Tests connectivity and configuration for all sites.

```bash
python ops/deployment/test_all_deployers.py
```

## 🔐 Credential Configuration

### Method 1: Hostinger Environment Variables (Recommended)
Set in `D:/Agent_Cellphone_V2_Repository/.env`:
```
HOSTINGER_HOST=your-host.com
HOSTINGER_USER=your-username
HOSTINGER_PASS=your-password
HOSTINGER_PORT=65002
```

### Method 2: Site-Specific SFTP (site_configs.json)
```json
{
  "prismblossom.online": {
    "sftp": {
      "host": "your-host.com",
      "username": "your-username",
      "password": "your-password",
      "remote_path": "domains/prismblossom.online/public_html"
    }
  }
}
```

### Method 3: WordPress REST API
```json
{
  "prismblossom.online": {
    "rest_api": {
      "username": "wordpress-username",
      "app_password": "xxxx xxxx xxxx xxxx xxxx xxxx",
      "site_url": "https://prismblossom.online"
    }
  }
}
```

## 📊 Site Configuration Status

| Site | Method | Status | Notes |
|------|--------|--------|-------|
| prismblossom.online | SFTP (Hostinger) | ✅ Working | Uses env vars |
| houstonsipqueen.com | SFTP (Hostinger) | ✅ Working | Uses env vars |
| freerideinvestor.com | REST API | ⚠️ Needs Credentials | App password required |
| crosbyultimateevents.com | REST API | ⚠️ Needs Credentials | App password required |
| tradingrobotplug.com | REST API | ⚠️ Needs Credentials | App password required |
| weareswarm.online | REST API | ⚠️ Needs Credentials | App password required |
| weareswarm.site | REST API | ⚠️ Needs Credentials | App password required |
| digitaldreamscape.site | REST API | ⚠️ Needs Credentials | App password required |
| southwestsecret.com | REST API | ⚠️ Needs Credentials | App password required |
| ariajet.site | REST API | ⚠️ Needs Credentials | App password required |

## 🔧 How It Works

1. **Credential Loading Priority:**
   - Hostinger environment variables (`.env`)
   - `.deploy_credentials/sites.json` (WordPressManager format)
   - `configs/site_configs.json` (SFTP credentials)
   - Hostinger defaults fallback

2. **Connection:**
   - Attempts SFTP connection using loaded credentials
   - Falls back to REST API if SFTP fails

3. **File Deployment:**
   - Automatically detects WordPress theme files
   - Calculates remote paths based on local structure
   - Uploads files via SFTP

## ✅ Verification

Test all deployers:
```bash
python ops/deployment/test_all_deployers.py
```

This will:
- Check connectivity for each site
- Verify credentials are configured
- Test SFTP connections
- Report deployment readiness

## 🚀 Quick Start

1. **Test connectivity:**
   ```bash
   python ops/deployment/test_all_deployers.py
   ```

2. **Deploy single site:**
   ```bash
   python ops/deployment/unified_deployer.py --site prismblossom.online --dry-run
   ```

3. **Deploy when ready:**
   ```bash
   python ops/deployment/unified_deployer.py --site prismblossom.online
   ```

## 📝 Notes

- All sites can use SFTP via Hostinger credentials (no additional setup needed)
- REST API requires WordPress Application Passwords to be configured
- The unified deployer automatically detects which files to deploy
- Dry run mode (`--dry-run`) allows testing without actual deployment

