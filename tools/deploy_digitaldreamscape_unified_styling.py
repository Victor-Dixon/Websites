#!/usr/bin/env python3
"""
Deploy Unified Subheader & Styling System to digitaldreamscape.site
====================================================================

Adds consistent subheader strip and unified visual system styling.

Author: Agent-5 (Business Intelligence Specialist)
Date: 2025-12-22
"""

import sys
from pathlib import Path

# Add parent directory to path for imports
sys.path.insert(0, str(Path(__file__).parent.parent))

import json
from ops.deployment.simple_wordpress_deployer import SimpleWordPressDeployer

def load_site_configs():
    """Load site configurations from config file."""
    config_path = Path(__file__).parent.parent / "configs" / "sites_registry.json"
    if config_path.exists():
        with open(config_path, 'r', encoding='utf-8') as f:
            return json.load(f)
    return {}

def main():
    print("=" * 70)
    print("DEPLOYING UNIFIED SUBHEADER & STYLING SYSTEM")
    print("to digitaldreamscape.site")
    print("=" * 70)
    print()
    
    # Load site configs and initialize deployer
    site_configs = load_site_configs()
    site_key = "digitaldreamscape.site"
    deployer = SimpleWordPressDeployer(site_key=site_key, site_configs=site_configs)
    
    # Site configuration
    site_domain = "digitaldreamscape.site"
    
    # Read the unified styling fix
    fix_file = Path(__file__).parent.parent / "docs" / "digitaldreamscape" / "UNIFIED_SUBHEADER_FIX.php"
    
    if not fix_file.exists():
        print(f"❌ ERROR: Fix file not found: {fix_file}")
        return
    
    print(f"📄 Reading unified styling fix...")
    with open(fix_file, 'r', encoding='utf-8') as f:
        fix_content = f.read()
    
    print(f"   ✅ Fix content loaded ({len(fix_content)} bytes)")
    print()
    
    # Deploy to functions.php
    print(f"🔧 Deploying unified styling system to functions.php...")
    try:
        remote_base = deployer.remote_path or f"/home/u996867598/domains/{site_key}/public_html"
        if not remote_base.startswith('/'):
            remote_base = f"/home/u996867598/{remote_base}"
        
        remote_functions = f"{remote_base}/wp-content/themes/digitaldreamscape/functions.php"
        
        # Read current functions.php
        print(f"   📄 Reading current functions.php...")
        current_content = deployer.sftp_client.open(remote_functions, 'r').read().decode('utf-8')
        
        # Check if already added
        if 'digitaldreamscape_unified_subheader' in current_content:
            print(f"   ⚠️  Unified styling system already exists - skipping")
        else:
            # Append the fix
            print(f"   ➕ Appending unified styling system...")
            new_content = current_content + "\n\n" + fix_content
            deployer.sftp_client.putfo(__import__('io').StringIO(new_content), remote_functions)
            print(f"   ✅ Unified styling system deployed successfully")
    except Exception as e:
        print(f"   ❌ Deployment failed: {e}")
        import traceback
        traceback.print_exc()
        return
    
    print()
    
    # Clear WordPress cache
    print(f"💬 Clearing WordPress cache...")
    try:
        deployer.clear_wordpress_cache(site_domain)
        print(f"   ✅ Cache clear attempted")
    except Exception as e:
        print(f"   ⚠️  Cache clear warning: {e}")
    
    print()
    print("=" * 70)
    print("✅ DEPLOYMENT COMPLETE!")
    print("=" * 70)
    print()
    print("📋 Next Steps:")
    print("   1. Visit https://digitaldreamscape.site/")
    print("   2. Visit https://digitaldreamscape.site/blog/")
    print("   3. Verify subheader strip appears on both pages")
    print("   4. Verify card styling is consistent")
    print("   5. Clear browser cache (Ctrl+F5) if needed")
    print()

if __name__ == "__main__":
    main()

