#!/usr/bin/env python3
"""
Update freerideinvestor.com About Page Content (V2 - Generic)
=============================================================

DEPRECATED: Use generic_update_page_content.py instead:
    python tools/generic_update_page_content.py --site freerideinvestor.com --page about --content-file content.html

This is a wrapper that uses the generic tool for backward compatibility.

Agent-7: Web Development Specialist
"""

import sys
import subprocess
from pathlib import Path

# About page content
ABOUT_CONTENT = """<h1>About FreeRide Investor</h1>

<h2>Our Mission</h2>
<p>FreeRide Investor is dedicated to empowering traders with the knowledge, tools, and community support needed to navigate the financial markets successfully. We believe that informed trading decisions lead to better outcomes, and we're committed to providing transparent, actionable insights for traders at all levels.</p>

<h2>Our Core Values</h2>
<ul>
    <li><strong>Transparency:</strong> We believe in honest, straightforward communication about trading strategies, risks, and opportunities.</li>
    <li><strong>Education:</strong> We're committed to helping traders understand not just what to do, but why it matters.</li>
    <li><strong>Community:</strong> Trading can be isolating. We foster a supportive community where traders can learn, share, and grow together.</li>
    <li><strong>Innovation:</strong> We continuously explore new tools, strategies, and technologies to stay ahead in an evolving market.</li>
</ul>

<h2>Our Philosophy</h2>
<p>We believe that successful trading isn't about finding a magic formula or following someone else's signals blindly. It's about developing your own understanding of the markets, managing risk effectively, and making informed decisions based on solid analysis and proven strategies.</p>

<p>Our approach combines technical analysis, fundamental research, and risk management principles to help traders build sustainable, long-term trading practices.</p>

<h2>Join Our Community</h2>
<p>Connect with us on <a href="https://twitter.com/freerideinvestor">Twitter</a>, join our <a href="https://discord.gg/freerideinvestor">Discord</a> community, or subscribe to our <a href="https://youtube.com/freerideinvestor">YouTube</a> channel for regular trading insights and educational content.</p>
"""


def main():
    """Main execution - uses generic tool."""
    print("=" * 70)
    print("UPDATE FREERIDEINVESTOR.COM ABOUT PAGE (V2 - Generic)")
    print("=" * 70)
    print()
    print("⚠️  DEPRECATED: This tool is deprecated.")
    print("   Use generic_update_page_content.py instead:")
    print()
    print("   python tools/generic_update_page_content.py \\")
    print("     --site freerideinvestor.com \\")
    print("     --page about \\")
    print("     --content \"<h1>About</h1><p>Content here</p>\"")
    print()
    print("Continuing with backward compatibility wrapper...")
    print()
    
    # Create temporary content file
    content_file = Path(__file__).parent / "temp_about_content.html"
    try:
        with open(content_file, 'w', encoding='utf-8') as f:
            f.write(ABOUT_CONTENT)
        
        # Call generic tool
        generic_tool = Path(__file__).parent / "generic_update_page_content.py"
        cmd = [
            sys.executable,
            str(generic_tool),
            "--site", "freerideinvestor.com",
            "--page", "about",
            "--content-file", str(content_file)
        ]
        
        result = subprocess.run(cmd, capture_output=False)
        return result.returncode
    finally:
        # Clean up temp file
        if content_file.exists():
            content_file.unlink()


if __name__ == "__main__":
    sys.exit(main())

