#!/usr/bin/env python3
"""
Update blog template mapping in functions.php

⚠️  DEPRECATED: Use generic_update_template_mapping.py instead:
    python tools/generic_update_template_mapping.py --site digitaldreamscape.site --page blog --template page-templates/page-blog-beautiful.php --clear-cache

This tool is kept for backward compatibility but will be removed in a future version.
"""

import sys
from pathlib import Path

sys.path.insert(0, str(Path(__file__).parent.parent / "ops" / "deployment"))
from simple_wordpress_deployer import SimpleWordPressDeployer, load_site_configs

SITE_NAME = "digitaldreamscape.site"

# Show deprecation warning
print("⚠️  DEPRECATED: This tool is deprecated.")
print("   Use generic_update_template_mapping.py instead:")
print("   python tools/generic_update_template_mapping.py --site digitaldreamscape.site --page blog --template page-templates/page-blog-beautiful.php --clear-cache")
print()

def main():
    site_configs = load_site_configs()
    deployer = SimpleWordPressDeployer(SITE_NAME, site_configs)
    remote_path = site_configs[SITE_NAME].get('sftp', {}).get('remote_path', 
        'domains/digitaldreamscape.site/public_html')
    theme_path = f"{remote_path}/wp-content/themes/digitaldreamscape"
    
    deployer.connect()
    
    # Read local functions.php
    local_file = Path(__file__).parent.parent / "websites" / "digitaldreamscape.site" / "wp" / "wp-content" / "themes" / "digitaldreamscape" / "functions.php"
    content = local_file.read_text(encoding='utf-8')
    
    # Deploy
    with deployer.sftp.open(f"{theme_path}/functions.php", 'w') as f:
        f.write(content.encode('utf-8'))
    
    print("✅ Updated functions.php")
    
    # Clear cache
    deployer.execute_command(f"cd {remote_path} && wp cache flush --allow-root")
    print("✅ Cache cleared")

if __name__ == "__main__":
    main()

