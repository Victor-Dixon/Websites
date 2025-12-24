#!/usr/bin/env python3
"""
Update freerideinvestor.com About Page Content
==============================================

⚠️  DEPRECATED: Use generic_update_page_content.py instead:
    python tools/generic_update_page_content.py --site freerideinvestor.com --page about --content "<h1>About</h1><p>Content here</p>"

This tool is kept for backward compatibility but will be removed in a future version.

Creates comprehensive About page content that matches the site's philosophy.

Agent-7: Web Development Specialist
"""

import sys
from pathlib import Path

sys.path.insert(0, str(Path(__file__).parent.parent / "ops" / "deployment"))
from simple_wordpress_deployer import SimpleWordPressDeployer, load_site_configs

SITE_NAME = "freerideinvestor.com"

# Comprehensive About page content matching site philosophy
ABOUT_PAGE_CONTENT = """<h1>About FreeRide Investor</h1>

<p>Trading is a journey we take together. Let's explore strategies, share insights, and grow as traders and investors.</p>

<h2>Our Mission</h2>

<p>We're here to help you become a better trader through education, community, and data-driven strategies. No hype. No get-rich-quick promises. Just real trading education built around independence, discipline, and asymmetric advantage in the market.</p>

<h2>What We Stand For</h2>

<h3>Risk Management First</h3>
<p>Every trade starts with defined risk. Position protected early. Upside allowed to run. Losses cut without ego. We believe that protecting capital is more important than maximizing gains. Without capital, there are no trades.</p>

<h3>Small, Repeatable Edge</h3>
<p>Build capital with small, consistent wins. Execution over prediction. Systems over luck. We focus on finding and exploiting small edges that compound over time, rather than chasing big wins that never materialize.</p>

<h3>Journaling & Review</h3>
<p>Learn from every trade. Post-trade reviews. Emotional awareness as a metric, not a weakness. We believe that systematic review and honest self-assessment are the keys to long-term improvement.</p>

<h3>Automation That's Earned</h3>
<p>Strategy libraries and tools that help you execute. Automation comes after discipline is proven. We don't believe in automated trading until you've proven you can trade manually with discipline and consistency.</p>

<h3>Real Lessons, No Hype</h3>
<p>Blogs breaking down real trades. Market psychology in plain language. Playbooks instead of promises. We share actual trading experiences, not theoretical concepts or marketing fluff.</p>

<h3>Freedom Over Flexing</h3>
<p>No lambos & lifestyle bait. No fake certainty. Just experienced, calm, accountable trading education. We focus on building real financial freedom, not showing off or pretending we have all the answers.</p>

<h2>Our Philosophy</h2>

<p>Removing downside pressure so clarity can exist. Building financial freedom without worshiping hustle culture. Teaching how to ride momentum without being enslaved by it.</p>

<p>It's not "get rich quick." It's get free on your terms.</p>

<p>We believe in:</p>
<ul>
<li><strong>Independence:</strong> Make your own decisions based on your own analysis</li>
<li><strong>Discipline:</strong> Follow your system, even when it's hard</li>
<li><strong>Asymmetric Advantage:</strong> Find edges where the risk/reward is in your favor</li>
<li><strong>Accountability:</strong> Take responsibility for your trades and your results</li>
<li><strong>Continuous Learning:</strong> The market changes, and so should your approach</li>
</ul>

<h2>Join the Community</h2>

<p>Connect with us on <a href="https://twitter.com/freerideinvestor">Twitter</a>, join our <a href="https://discord.gg/freerideinvestor">Discord</a> community, or subscribe to our <a href="https://youtube.com/freerideinvestor">YouTube</a> channel for regular trading insights and educational content.</p>

<p>Ready to start your trading journey? <a href="/">Explore our content</a> and begin building your trading system today.</p>
"""

def find_about_page_id(deployer, remote_path: str) -> int:
    """Find the About page ID using WP-CLI."""
    command = f"cd {remote_path} && wp post list --post_type=page --name=about --format=json --allow-root"
    result = deployer.execute_command(command)
    
    if not result or result.strip() == "[]":
        # Try by slug
        command = f"cd {remote_path} && wp post list --post_type=page --post_name=about --format=json --allow-root"
        result = deployer.execute_command(command)
    
    if result and result.strip() != "[]":
        import json
        try:
            pages = json.loads(result)
            if pages:
                return pages[0]['ID']
        except:
            pass
    
    # Try to find by title
    command = f"cd {remote_path} && wp post list --post_type=page --s='About' --format=json --allow-root"
    result = deployer.execute_command(command)
    
    if result and result.strip() != "[]":
        import json
        try:
            pages = json.loads(result)
            if pages:
                # Find the one with "About" in title
                for page in pages:
                    if 'about' in page.get('post_title', '').lower():
                        return page['ID']
        except:
            pass
    
    return None

def update_about_page(deployer, remote_path: str, page_id: int) -> bool:
    """Update the About page content using WP-CLI."""
    # Escape the content for shell
    import shlex
    content_escaped = shlex.quote(ABOUT_PAGE_CONTENT)
    
    command = f"cd {remote_path} && wp post update {page_id} --post_content={content_escaped} --allow-root"
    result = deployer.execute_command(command)
    
    if "Success" in result or "Updated" in result or f"post {page_id}" in result.lower():
        return True
    return False

def main():
    """Main execution."""
    print("=" * 70)
    print("UPDATE FREERIDEINVESTOR.COM ABOUT PAGE")
    print("=" * 70)
    print()
    print("⚠️  DEPRECATED: This tool is deprecated.")
    print("   Use generic_update_page_content.py instead:")
    print("   python tools/generic_update_page_content.py --site freerideinvestor.com --page about --content \"<h1>About</h1><p>Content here</p>\"")
    print()
    
    # Load site configs
    site_configs = load_site_configs()
    if SITE_NAME not in site_configs:
        print(f"❌ Site {SITE_NAME} not found in configs")
        return
    
    deployer = SimpleWordPressDeployer(SITE_NAME, site_configs)
    remote_path = site_configs[SITE_NAME].get('sftp', {}).get('remote_path', 
        'domains/freerideinvestor.com/public_html')
    
    print(f"📂 Remote path: {remote_path}")
    print()
    
    # Connect
    print("🔌 Connecting to server...")
    if not deployer.connect():
        print("❌ Failed to connect")
        return
    print("✅ Connected")
    print()
    
    # Find About page
    print("🔍 Finding About page...")
    page_id = find_about_page_id(deployer, remote_path)
    
    if not page_id:
        print("❌ About page not found")
        print("   Creating new About page...")
        # Create new page
        command = f"cd {remote_path} && wp post create --post_type=page --post_title='About' --post_name='about' --post_status=publish --post_content='{ABOUT_PAGE_CONTENT.replace(chr(39), chr(39)+chr(39))}' --allow-root"
        result = deployer.execute_command(command)
        if "Success" in result or "Created" in result:
            print("✅ About page created")
            # Extract page ID from result
            import re
            match = re.search(r'post_id[:\s]+(\d+)', result)
            if match:
                page_id = int(match.group(1))
                print(f"   Page ID: {page_id}")
        else:
            print(f"❌ Failed to create page: {result}")
            return
    else:
        print(f"✅ Found About page (ID: {page_id})")
    
    print()
    
    # Update page content
    print("📝 Updating About page content...")
    if update_about_page(deployer, remote_path, page_id):
        print("✅ About page updated successfully")
    else:
        print("❌ Failed to update About page")
        return
    
    print()
    print("=" * 70)
    print("✅ ABOUT PAGE UPDATE COMPLETE")
    print("=" * 70)
    print()
    print("View the updated page:")
    print("https://freerideinvestor.com/about/")
    print()

if __name__ == "__main__":
    main()

