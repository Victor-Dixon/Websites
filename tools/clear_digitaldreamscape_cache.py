#!/usr/bin/env python3
"""
Clear DigitalDreamscape Cache
=============================

Clears all types of WordPress cache for digitaldreamscape.site

Author: Agent-7 (Web Development Specialist)
Date: 2025-12-24
"""

import sys
from pathlib import Path

sys.path.insert(0, str(Path(__file__).parent.parent / "ops" / "deployment"))

from simple_wordpress_deployer import SimpleWordPressDeployer, load_site_configs


def clear_cache():
    """Clear all WordPress cache for digitaldreamscape.site."""
    site_name = "digitaldreamscape.site"
    
    print("=" * 70)
    print(f"🧹 CLEARING ALL CACHE: {site_name}")
    print("=" * 70)
    print()
    
    site_configs = load_site_configs()
    
    try:
        deployer = SimpleWordPressDeployer(site_name, site_configs)
    except Exception as e:
        print(f"❌ Failed to initialize deployer: {e}")
        return False
    
    if not deployer.connect():
        print("❌ Failed to connect to server")
        return False
    
    try:
        remote_path = getattr(deployer, 'remote_path', '') or f"domains/{site_name}/public_html"
        print(f"📁 Remote path: {remote_path}")
        print()
        
        # Clear WordPress cache
        print("1️⃣ Clearing WordPress cache...")
        cache_result = deployer.execute_command(f"cd {remote_path} && wp cache flush --allow-root 2>&1")
        if cache_result:
            print(f"   {cache_result[:150]}")
        
        # Clear object cache
        print("2️⃣ Clearing object cache...")
        obj_cache = deployer.execute_command(f"cd {remote_path} && wp cache delete --allow-root 2>&1")
        if obj_cache:
            print(f"   {obj_cache[:150]}")
        
        # Clear rewrite rules (forces template refresh)
        print("3️⃣ Flushing rewrite rules...")
        rewrite = deployer.execute_command(f"cd {remote_path} && wp rewrite flush --allow-root 2>&1")
        if rewrite:
            print(f"   {rewrite[:150]}")
        
        # Clear transients
        print("4️⃣ Clearing transients...")
        transients = deployer.execute_command(f"cd {remote_path} && wp transient delete --all --allow-root 2>&1")
        if transients:
            print(f"   {transients[:150]}")
        
        # Try LiteSpeed cache if available
        print("5️⃣ Trying LiteSpeed cache purge...")
        litespeed = deployer.execute_command(f"cd {remote_path} && wp litespeed-purge all --allow-root 2>&1")
        if litespeed:
            print(f"   {litespeed[:150]}")
        
        print()
        print("=" * 70)
        print("✅ CACHE CLEAR COMPLETE")
        print("=" * 70)
        print()
        print("💡 Refresh the site with Ctrl+Shift+R (hard refresh)")
        print("   Or visit: https://digitaldreamscape.site/community/")
        
        return True
        
    except Exception as e:
        print(f"❌ Error: {e}")
        import traceback
        traceback.print_exc()
        return False
    finally:
        deployer.disconnect()


def main():
    """Main execution."""
    success = clear_cache()
    return 0 if success else 1


if __name__ == "__main__":
    sys.exit(main())

