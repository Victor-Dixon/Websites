#!/usr/bin/env python3
"""
Deploy Beautiful Blog Template to digitaldreamscape.site
========================================================

⚠️  DEPRECATED: Use generic_deploy_template.py instead:
    python tools/generic_deploy_template.py --site digitaldreamscape.site --template page-blog-beautiful.php --local-path <path> --css <css_path>

This tool is kept for backward compatibility but will be removed in a future version.

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
    print("DEPLOY BEAUTIFUL BLOG TEMPLATE")
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
    
    # Create page-templates directory if needed
    print("📁 Creating page-templates directory...")
    try:
        deployer.sftp.mkdir(f"{theme_path}/page-templates")
        print("✅ Created page-templates directory")
    except:
        print("ℹ️  Directory already exists")
    
    # Create assets/css directory if needed
    print("📁 Creating assets/css directory...")
    try:
        deployer.sftp.mkdir(f"{theme_path}/assets")
        deployer.sftp.mkdir(f"{theme_path}/assets/css")
        print("✅ Created assets/css directory")
    except:
        print("ℹ️  Directory already exists")
    
    print()
    
    # Deploy template file
    template_file = Path(__file__).parent.parent / "websites" / "digitaldreamscape.site" / "wp" / "wp-content" / "themes" / "digitaldreamscape" / "page-templates" / "page-blog-beautiful.php"
    if template_file.exists():
        print("📝 Deploying page-blog-beautiful.php...")
        with open(template_file, 'r', encoding='utf-8') as f:
            content = f.read()
        
        remote_template = f"{theme_path}/page-templates/page-blog-beautiful.php"
        with deployer.sftp.open(remote_template, 'w') as f:
            f.write(content.encode('utf-8'))
        print("✅ Template deployed")
    else:
        print(f"❌ Template file not found: {template_file}")
    
    print()
    
    # Deploy CSS file
    css_file = Path(__file__).parent.parent / "websites" / "digitaldreamscape.site" / "wp" / "wp-content" / "themes" / "digitaldreamscape" / "assets" / "css" / "beautiful-blog.css"
    if css_file.exists():
        print("🎨 Deploying beautiful-blog.css...")
        with open(css_file, 'r', encoding='utf-8') as f:
            content = f.read()
        
        remote_css = f"{theme_path}/assets/css/beautiful-blog.css"
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
        
        if 'beautiful-blog.css' not in functions_content:
            # Add enqueue function
            enqueue_code = """
// Enqueue Beautiful Blog CSS
function enqueue_beautiful_blog_styles() {
    if (is_page_template('page-templates/page-blog-beautiful.php')) {
        wp_enqueue_style(
            'beautiful-blog',
            get_template_directory_uri() . '/assets/css/beautiful-blog.css',
            array(),
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_beautiful_blog_styles');
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
    print("=" * 70)
    print("✅ DEPLOYMENT COMPLETE")
    print("=" * 70)
    print()
    print("Next steps:")
    print("1. Go to WordPress admin → Pages → Blog")
    print("2. Edit the Blog page")
    print("3. Set Page Template to 'Beautiful Blog'")
    print("4. Update the page")
    print()
    print("Or use WP-CLI:")
    print(f"   wp post update <blog_page_id> --page_template=page-templates/page-blog-beautiful.php")

if __name__ == "__main__":
    main()

