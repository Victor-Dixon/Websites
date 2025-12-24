#!/usr/bin/env python3
"""
Fix digitaldreamscape.site Post to Match YAML Vocal Patterns (V2 - Generic)
============================================================================

DEPRECATED: Use generic_update_post_content.py instead:
    python tools/generic_update_post_content.py --site digitaldreamscape.site --post-slug digital-dreamscape-site-update --content-file rewritten_content.html

This is a wrapper that uses the generic tool for backward compatibility.

Agent-7: Web Development Specialist
"""

import sys
import subprocess
from pathlib import Path

# Rewritten content matching YAML vocal patterns
REWRITTEN_CONTENT = """ok so i spent a serious block of time on digitaldreamscape.site this sprint and the goal was simple: get the theme to a place where it represents my web design capabilities in a clean, modern, build-in-public way, and where its maintainable enough to evolve without turning into a fragile mess.

i started with the header... the old header was functional but it felt disconnected from the rest of the site. so i rebuilt it from scratch, focusing on making it feel cohesive with the overall design language. the new header has better spacing, clearer hierarchy, and it actually feels like part of the site instead of something bolted on.

then i moved to the blog template... the default wordpress blog listing was... fine? but it didnt feel special. so i built a new card-based grid layout with hover effects, better typography, and a more modern aesthetic. each post card has a gradient overlay, smooth transitions, and it just feels more polished.

the streaming page got the same treatment... matching the blog template style but adapted for streaming content. status cards, platform links, schedule info... all wrapped in the same design language.

and throughout all of this, i kept asking myself: does this feel like something i can be proud of? does it represent the quality of work i want to be known for? because the way our websites look is a reflection on the swarm, and i want that reflection to be good.

the result? a site that feels cohesive, modern, and maintainable. something that can evolve without breaking, and something that actually represents what we're capable of.

next up: community page, about page, and then we'll see where the momentum takes us.
"""


def main():
    """Main execution - uses generic tool."""
    print("=" * 70)
    print("FIX DIGITALDREAMSCAPE.SITE POST VOCAL PATTERNS (V2 - Generic)")
    print("=" * 70)
    print()
    print("⚠️  DEPRECATED: This tool is deprecated.")
    print("   Use generic_update_post_content.py instead:")
    print()
    print("   python tools/generic_update_post_content.py \\")
    print("     --site digitaldreamscape.site \\")
    print("     --post-slug digital-dreamscape-site-update \\")
    print("     --content \"<p>Rewritten content here</p>\"")
    print()
    print("Continuing with backward compatibility wrapper...")
    print()
    
    # Create temporary content file
    content_file = Path(__file__).parent / "temp_post_content.html"
    try:
        # Convert plain text to HTML paragraphs
        html_content = "<p>" + REWRITTEN_CONTENT.replace("\n\n", "</p><p>").replace("\n", " ") + "</p>"
        
        with open(content_file, 'w', encoding='utf-8') as f:
            f.write(html_content)
        
        # Call generic tool
        generic_tool = Path(__file__).parent / "generic_update_post_content.py"
        cmd = [
            sys.executable,
            str(generic_tool),
            "--site", "digitaldreamscape.site",
            "--post-slug", "digital-dreamscape-site-update",
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

