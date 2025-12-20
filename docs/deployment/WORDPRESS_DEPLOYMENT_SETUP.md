# 🔐 WordPress Deployment Setup Guide

**Date**: 2025-11-30  
**Agent**: Agent-7 (Web Development Specialist)  
**Purpose**: Configure WordPress deployment manager for direct hosting connection

---

## 🎯 **OVERVIEW**

We have a WordPress deployment manager (`wordpress_manager.py`) that connects directly to hosting via SFTP/SSH. This allows automatic deployment of website fixes without manual file uploads.

---

## 📋 **REQUIRED SETUP**

### **Step 1: Configure Credentials**

Edit the credentials file:
```
D:\Agent_Cellphone_V2_Repository\.deploy_credentials\sites.json
```

Add credentials for each site:

```json
{
  "freerideinvestor": {
    "host": "your-hostinger-host.com",
    "username": "your-ftp-username",
    "password": "your-ftp-password",
    "port": 65002,
    "remote_path": "/public_html/wp-content/themes/freerideinvestor"
  },
  "prismblossom": {
    "host": "your-hostinger-host.com",
    "username": "your-ftp-username",
    "password": "your-ftp-password",
    "port": 65002,
    "remote_path": "/public_html/wp-content/themes/prismblossom"
  },
  "southwestsecret": {
    "host": "your-hostinger-host.com",
    "username": "your-ftp-username",
    "password": "your-ftp-password",
    "port": 65002,
    "remote_path": "/public_html/wp-content/themes/southwestsecret"
  }
}
```

### **Step 2: Get Hostinger Credentials**

1. **Log into Hostinger**: https://hpanel.hostinger.com
2. **Go to FTP Accounts**:
   - Navigate to **"FTP Accounts"** in your hosting panel
   - Find or create FTP account for your site
   - Note the credentials:
     - **Host**: Usually `ftp.yourdomain.com` or provided by Hostinger
     - **Username**: Your FTP username
     - **Password**: Your FTP password
     - **Port**: Usually `65002` for Hostinger SFTP (or `21` for FTP)

3. **Alternative - SSH Access**:
   - If you have SSH access, you can use SSH credentials instead
   - Port is usually `65002` for Hostinger

---

## 🚀 **USING THE DEPLOYMENT MANAGER**

### **Deploy All Websites**:

```bash
cd D:\websites
python tools/deploy_all_websites.py
```

### **Deploy Single Site**:

```bash
cd D:\Agent_Cellphone_V2_Repository
python tools/wordpress_manager.py --site prismblossom --deploy
```

### **Deploy Specific Files**:

```python
from wordpress_manager import WordPressManager
from pathlib import Path

manager = WordPressManager("prismblossom")
manager.connect()
manager.deploy_file(Path("D:/websites/prismblossom.online/wordpress-theme/prismblossom/functions.php"))
manager.disconnect()
```

---

## ✅ **VERIFICATION**

After setting up credentials, test the connection:

```bash
cd D:\Agent_Cellphone_V2_Repository
python tools/wordpress_manager.py --site prismblossom --verify
```

---

## 🔧 **TROUBLESHOOTING**

### **Connection Failed**:
1. Check credentials are correct in `sites.json`
2. Verify FTP/SSH is enabled on Hostinger
3. Check firewall isn't blocking port 65002
4. Try port 21 (FTP) if 65002 doesn't work

### **File Upload Failed**:
1. Check file permissions on server
2. Verify remote path is correct
3. Check disk space on server
4. Verify file exists locally

### **Site Not Found**:
1. Check site key matches `SITE_CONFIGS` in `wordpress_manager.py`
2. Verify site is added to `SITE_CONFIGS`
3. Check credentials file has matching site key

---

## 📝 **CURRENT STATUS**

**WordPress Manager**: ✅ Ready  
**FreeRideInvestor**: ⚠️ Needs credentials  
**prismblossom.online**: ⚠️ Needs credentials  
**southwestsecret.com**: ⚠️ Needs credentials  

**Next Step**: Configure credentials in `sites.json`

---

🐝 **WE. ARE. SWARM.** ⚡🔥

**Agent-7 (Web Development Specialist)**  
**Status: ✅ DEPLOYMENT MANAGER READY - AWAITING CREDENTIALS**

