# PrismBlossom.online Deployment Audit & Instructions

**Date:** 2025-12-23  
**Status:** ⚠️ Deployment Ready - Requires Credentials Setup  
**Merged PR:** Commit `8045d7f` - Add Carmyn page profile fields and update page-carmyn.php

## 📋 Audit Summary

### Files Changed (from merged PR)
1. `websites/prismblossom.online/wp/wp-content/themes/prismblossom/functions.php`
   - Added 104 lines (profile fields functionality)
   
2. `websites/prismblossom.online/wp/wp-content/themes/prismblossom/page-carmyn.php`
   - Modified 60 lines (updated Carmyn page template)

### Site Configuration Status

**Current Configuration** (`configs/site_configs.json`):
- **Site URL:** https://prismblossom.online
- **Deployment Method:** REST API
- **REST API Username:** `carmyn`
- **REST API App Password:** ❌ NOT SET (`REPLACE_WITH_APPLICATION_PASSWORD`)
- **SFTP Credentials:** ❌ NOT CONFIGURED (all null)

**Site Registry** (`configs/sites_registry.json`):
- **Mode:** MAINTENANCE
- **Owner:** Carmyn
- **Purpose:** Personal / family site
- **Allowed Tasks:** bugfix only

### Deployment Options

#### Option 1: SFTP Deployment (Recommended)
- ✅ Works with SimpleWordPressDeployer
- ✅ Supports direct file uploads
- ❌ Requires SFTP credentials setup

**Required Credentials:**
```json
{
  "prismblossom.online": {
    "sftp": {
      "host": "YOUR_SFTP_HOST",
      "username": "YOUR_SFTP_USERNAME",
      "password": "YOUR_SFTP_PASSWORD",
      "remote_path": "domains/prismblossom.online/public_html"
    }
  }
}
```

#### Option 2: REST API Deployment
- ⚠️ WordPress REST API doesn't support file uploads directly
- ⚠️ Would require custom plugin or manual upload
- ❌ App password not configured

**Required Setup:**
1. Generate WordPress Application Password in WordPress admin
2. Update `site_configs.json` with the app password
3. Use REST API only for activation (files must be uploaded via SFTP first)

#### Option 3: Manual Deployment (Fallback)
- ✅ No credentials needed
- ⚠️ Manual process via WordPress admin or cPanel
- ✅ Works immediately

## 🚀 Deployment Instructions

### Using Automated Script (Once Credentials Configured)

```bash
# Deploy updated theme files
python ops/deployment/deploy_prismblossom.py
```

The script will:
1. Connect to server via SFTP
2. Upload `functions.php` to `wp-content/themes/prismblossom/`
3. Upload `page-carmyn.php` to `wp-content/themes/prismblossom/`
4. Verify deployment success

### Manual Deployment Steps

If credentials are not available, deploy manually:

1. **Access Server Files:**
   - Via cPanel File Manager, or
   - Via SFTP client (FileZilla, WinSCP, etc.)

2. **Navigate to Theme Directory:**
   ```
   /domains/prismblossom.online/public_html/wp-content/themes/prismblossom/
   ```

3. **Backup Current Files:**
   - Download existing `functions.php` and `page-carmyn.php` as backup

4. **Upload New Files:**
   - Upload from: `D:\websites\websites\prismblossom.online\wp\wp-content\themes\prismblossom\functions.php`
   - Upload from: `D:\websites\websites\prismblossom.online\wp\wp-content\themes\prismblossom\page-carmyn.php`
   - Overwrite existing files

5. **Verify Changes:**
   - Visit https://prismblossom.online
   - Check the Carmyn page specifically
   - Clear browser cache if needed

## 📝 Next Steps

### Immediate Actions Required:
1. ✅ **Files Ready:** Updated files are in repository
2. ❌ **Credentials Needed:** SFTP credentials or WordPress App Password
3. ⏳ **Deploy:** Run deployment once credentials are configured
4. ⏳ **Verify:** Test live site after deployment

### To Set Up Credentials:

#### For SFTP (Recommended):
1. Obtain SFTP credentials from hosting provider
2. Update `configs/site_configs.json`:
   ```json
   "prismblossom.online": {
     "sftp": {
       "host": "your-sftp-host.com",
       "username": "your-username",
       "password": "your-password",
       "remote_path": "domains/prismblossom.online/public_html"
     }
   }
   ```

#### For REST API:
1. Login to WordPress admin: https://prismblossom.online/wp-admin
2. Go to Users → Profile → Application Passwords
3. Create new application password
4. Update `configs/site_configs.json`:
   ```json
   "prismblossom.online": {
     "rest_api": {
       "username": "carmyn",
       "app_password": "xxxx xxxx xxxx xxxx xxxx xxxx"
     }
   }
   ```

## 🔍 Files to Deploy

### Local Paths:
```
D:\websites\websites\prismblossom.online\wp\wp-content\themes\prismblossom\functions.php
D:\websites\websites\prismblossom.online\wp\wp-content\themes\prismblossom\page-carmyn.php
```

### Remote Paths:
```
domains/prismblossom.online/public_html/wp-content/themes/prismblossom/functions.php
domains/prismblossom.online/public_html/wp-content/themes/prismblossom/page-carmyn.php
```

## ✅ Verification Checklist

After deployment:
- [ ] Functions.php uploaded successfully
- [ ] Page-carmyn.php uploaded successfully
- [ ] Site loads without errors
- [ ] Carmyn page displays correctly
- [ ] Profile fields functionality works
- [ ] No PHP errors in error logs
- [ ] Cache cleared (WordPress + browser)

## 📞 Support

If deployment fails:
1. Check SFTP credentials are correct
2. Verify file permissions on server
3. Check WordPress error logs
4. Test with manual file upload first
5. Review deployment script output for specific errors

