#!/usr/bin/env python3
"""
Deploy Beautiful Streaming Template to digitaldreamscape.site
=============================================================

Deploys the new beautiful streaming template and CSS.

Agent-7: Web Development Specialist
"""

import sys
from pathlib import Path

sys.path.insert(0, str(Path(__file__).parent.parent / "ops" / "deployment"))
from simple_wordpress_deployer import SimpleWordPressDeployer, load_site_configs

SITE_NAME = "digitaldreamscape.site"

def main():
    """Main execution."""
    print("=" * 70)
    print("DEPLOY BEAUTIFUL STREAMING TEMPLATE")
    print("=" * 70)
    print()
    
    # Load site configs
    site_configs = load_site_configs()
    if SITE_NAME not in site_configs:
        print(f"❌ Site {SITE_NAME} not found in configs")
        return
    
    deployer = SimpleWordPressDeployer(SITE_NAME, site_configs)
    remote_path = site_configs[SITE_NAME].get('sftp', {}).get('remote_path', 
        'domains/digitaldreamscape.site/public_html')
    
    theme_path = f"{remote_path}/wp-content/themes/digitaldreamscape"
    
    print(f"📂 Remote path: {remote_path}")
    print(f"📂 Theme path: {theme_path}")
    print()
    
    # Connect
    print("🔌 Connecting to server...")
    if not deployer.connect():
        print("❌ Failed to connect")
        return
    print("✅ Connected")
    print()
    
    # Deploy template file
    template_file = Path(__file__).parent.parent / "websites" / "digitaldreamscape.site" / "wp" / "wp-content" / "themes" / "digitaldreamscape" / "page-templates" / "page-streaming-beautiful.php"
    if template_file.exists():
        print("📝 Deploying page-streaming-beautiful.php...")
        with open(template_file, 'r', encoding='utf-8') as f:
            content = f.read()
        
        remote_template = f"{theme_path}/page-templates/page-streaming-beautiful.php"
        with deployer.sftp.open(remote_template, 'w') as f:
            f.write(content.encode('utf-8'))
        print("✅ Template deployed")
    else:
        print(f"❌ Template file not found: {template_file}")
    
    print()
    
    # Deploy CSS file
    css_file = Path(__file__).parent.parent / "websites" / "digitaldreamscape.site" / "wp" / "wp-content" / "themes" / "digitaldreamscape" / "assets" / "css" / "beautiful-streaming.css"
    if css_file.exists():
        print("🎨 Deploying beautiful-streaming.css...")
        with open(css_file, 'r', encoding='utf-8') as f:
            content = f.read()
        
        remote_css = f"{theme_path}/assets/css/beautiful-streaming.css"
        with deployer.sftp.open(remote_css, 'w') as f:
            f.write(content.encode('utf-8'))
        print("✅ CSS deployed")
    else:
        print(f"❌ CSS file not found: {css_file}")
    
    print()
    
    # Update functions.php to enqueue CSS
    print("📝 Checking functions.php for CSS enqueue...")
    functions_path = f"{theme_path}/functions.php"
    try:
        with deployer.sftp.open(functions_path, 'r') as f:
            functions_content = f.read().decode('utf-8')
        
        if 'beautiful-streaming.css' not in functions_content:
            # Add enqueue function
            enqueue_code = """
// Enqueue Beautiful Streaming CSS
function enqueue_beautiful_streaming_styles() {
    if (is_page_template('page-templates/page-streaming-beautiful.php')) {
        wp_enqueue_style(
            'beautiful-streaming',
            get_template_directory_uri() . '/assets/css/beautiful-streaming.css',
            array(),
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_beautiful_streaming_styles');
"""
            # Append to functions.php
            new_content = functions_content.rstrip() + "\n" + enqueue_code
            with deployer.sftp.open(functions_path, 'w') as f:
                f.write(new_content.encode('utf-8'))
            print("✅ Added CSS enqueue to functions.php")
        else:
            print("ℹ️  CSS enqueue already exists")
    except Exception as e:
        print(f"⚠️  Could not update functions.php: {e}")
    
    print()
    
    # Update template mapping
    print("📝 Updating template mapping...")
    try:
        with deployer.sftp.open(functions_path, 'r') as f:
            functions_content = f.read().decode('utf-8')
        
        # Update streaming template mapping
        if "'streaming' => 'page-streaming.php'" in functions_content:
            functions_content = functions_content.replace(
                "'streaming' => 'page-streaming.php'",
                "'streaming' => 'page-templates/page-streaming-beautiful.php'"
            )
            with deployer.sftp.open(functions_path, 'w') as f:
                f.write(functions_content.encode('utf-8'))
            print("✅ Updated template mapping")
        elif "'streaming' => 'page-templates/page-streaming-beautiful.php'" in functions_content:
            print("ℹ️  Template mapping already updated")
        else:
            print("⚠️  Could not find streaming template mapping")
    except Exception as e:
        print(f"⚠️  Could not update template mapping: {e}")
    
    print()
    print("=" * 70)
    print("✅ DEPLOYMENT COMPLETE")
    print("=" * 70)
    print()
    print("Next steps:")
    print("1. Go to WordPress admin → Pages → Streaming")
    print("2. Edit the Streaming page")
    print("3. Set Page Template to 'Beautiful Streaming'")
    print("4. Update the page")
    print()
    print("Or use WP-CLI:")
    print(f"   wp post update <streaming_page_id> --page_template=page-templates/page-streaming-beautiful.php")

if __name__ == "__main__":
    main()

