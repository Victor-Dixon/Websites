# Deployment Tool Usage Guide

**Last Updated:** 2025-12-23  
**Quick Reference:** Which tool to use for what

## 🎯 Quick Decision Tree

```
Need to deploy files to a website?
├─ Yes → Use: unified_deployer.py
│   ├─ Single site: --site <domain>
│   └─ All sites: --all
│
Need to deploy AND activate a theme?
├─ Yes → Use: deploy_and_activate_themes.py
│
Need to publish content?
├─ Yes → Use: publish_blog_post.py or publish_with_autoblogger.py
│
Need to verify a deployment?
├─ Yes → Use: verify_website_fixes.py or verify_theme_files.py
│
Need to check WordPress?
├─ Yes → Use: check_wordpress_updates.py or check_wordpress_versions.py
```

## 📚 Tool Reference

### File Deployment (Primary Tool)

#### `unified_deployer.py` ⭐ **USE THIS**
**Purpose:** Deploy any files to any website

**Usage:**
```bash
# Deploy single site
python ops/deployment/unified_deployer.py --site prismblossom.online

# Deploy all sites
python ops/deployment/unified_deployer.py --all

# Test first (dry run)
python ops/deployment/unified_deployer.py --site prismblossom.online --dry-run

# Deploy specific files
python ops/deployment/unified_deployer.py --site prismblossom.online --files file1.php file2.css
```

**When to use:**
- ✅ Deploying theme files
- ✅ Deploying any PHP/CSS/JS files
- ✅ Quick deployments
- ✅ Testing deployments

---

### Theme Management

#### `deploy_and_activate_themes.py`
**Purpose:** Deploy themes AND activate them

**Usage:**
```bash
python ops/deployment/deploy_and_activate_themes.py --site houstonsipqueen.com
python ops/deployment/deploy_and_activate_themes.py --all
```

**When to use:**
- ✅ Need to activate theme after deployment
- ✅ Complete theme deployment workflow

#### `activate_themes.py`
**Purpose:** Activate themes (already uploaded)

**Usage:**
```bash
python ops/deployment/activate_themes.py --site houstonsipqueen.com
```

**When to use:**
- ✅ Theme already uploaded, just need to activate

---

### Content Publishing

#### `publish_blog_post.py`
**Purpose:** Publish blog posts via REST API

**When to use:**
- ✅ Publishing blog content
- ✅ Using WordPress REST API

#### `publish_with_autoblogger.py`
**Purpose:** Auto-blogger publishing workflow

**When to use:**
- ✅ Automated blog publishing
- ✅ Using autoblogger system

---

### Verification

#### `verify_website_fixes.py`
**Purpose:** Verify that deployments worked

**When to use:**
- ✅ After deploying files
- ✅ Confirm changes are live

#### `test_all_deployers.py`
**Purpose:** Test connectivity for all sites

**Usage:**
```bash
python ops/deployment/test_all_deployers.py
```

**When to use:**
- ✅ Testing deployment setup
- ✅ Verifying credentials

---

### WordPress Management

#### `check_wordpress_updates.py`
**Purpose:** Check for WordPress core updates

**When to use:**
- ✅ Checking update status
- ✅ WordPress maintenance

---

## ⚠️ Deprecated Tools (Don't Use)

| Tool | Use Instead |
|------|-------------|
| `deploy_website_fixes.py` | `unified_deployer.py` |
| `deploy_all_websites.py` | `unified_deployer.py --all` |

## 💡 Best Practices

1. **Always test first:**
   ```bash
   python ops/deployment/unified_deployer.py --site <domain> --dry-run
   ```

2. **Use unified_deployer for file deployments:**
   - It auto-detects files
   - Works with all sites
   - Most flexible option

3. **Use specialized tools for their purpose:**
   - Theme activation → `deploy_and_activate_themes.py`
   - Content publishing → `publish_*.py`
   - Verification → `verify_*.py`

4. **Test connectivity regularly:**
   ```bash
   python ops/deployment/test_all_deployers.py
   ```

