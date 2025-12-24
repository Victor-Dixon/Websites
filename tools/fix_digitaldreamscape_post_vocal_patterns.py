#!/usr/bin/env python3
"""
Fix digitaldreamscape.site Post to Match YAML Vocal Patterns
===========================================================

⚠️  DEPRECATED: Use generic_update_post_content.py instead:
    python tools/generic_update_post_content.py --site digitaldreamscape.site --post-slug digital-dreamscape-site-update --content-file rewritten_content.html

This tool is kept for backward compatibility but will be removed in a future version.

Rewrites the "Digital Dreamscape Site Update" post to match the 
casual, conversational, stream-of-consciousness style defined in
the YAML voice template.

Agent-7: Web Development Specialist
"""

import sys
from pathlib import Path
import re

sys.path.insert(0, str(Path(__file__).parent.parent / "ops" / "deployment"))
from simple_wordpress_deployer import SimpleWordPressDeployer, load_site_configs

SITE_NAME = "digitaldreamscape.site"

# Rewritten content matching YAML vocal patterns
REWRITTEN_CONTENT = """ok so i spent a serious block of time on digitaldreamscape.site this sprint and the goal was simple: get the theme to a place where it represents my web design capabilities in a clean, modern, build-in-public way, and where its maintainable enough to evolve without turning into a fragile mess.

we made real progress... and we also hit the point where time spent stopped being worth the return. so this post is the closure: whats done, what went wrong, and how the future plan solves it without wasting another five hours.

what we built in this sprint

1) a narrative-driven content identity

digital dreamscape isnt a generic blog. the sites vibe is "episodes," "questlines," and a persistent story loop where projects and decisions become canon.

that identity showed up directly in the templates:

archive / blog listing framed as an episode archive
categories treated like questlines
metadata presented as world-state and timeline
single post pages styled like narrative entries with intro/outro sections

this isnt js aesthetic, its positioning. it communicates that dreamscape is a system, not a diary.

2) a modern "glass" ui direction

the theme moved toward a clean glass + gradient style:

modern design tokens (colors, spacing, radii, shadows)
sticky header + layout structure
consistent button system
card/grid patterns for feeds and content blocks
dark-mode aware variables (prefers-color-scheme + manual body classes)

the intent was simple: professional ui baseline that can evolve fast.

3) accessibility + seo hardening (the right kind)

we implemented alt-text fallbacks so images dont ship with missing alt attributes:

inject missing alt into content images (without overwriting existing alts)
patch featured images where missing
handle widget text images safely
hide wordpress generator meta

these are small changes, but they signal "we build correctly," not js "we build pretty."

what broke (and why were not wasting more time)

the fatal error that took the site down

at one point, the theme threw a php fatal error:

> call to undefined function dd_safe_avatar()

> in template-parts/card-episode.php

that means a template expected a helper function that didnt exist in functions.php (or wasnt included properly). in wordpress, a single missing function can take down the entire front-end.

this is exactly the kind of issue that happens when a theme starts growing without structure:

helper functions scattered
template parts assuming availability
no consistent "include" pattern
no guardrails

the bigger cause: drift + missing modularity

we also had mismatches like:

js referenced in enqueue logic, but no real assets directory / files
css getting too large to reason about in one file
multiple competing class conventions in templates

the theme wasnt "bad." it was becoming hard to maintain, which is worse than ugly.

the decision: we stop polishing this site right now

we spent about five hours on it. thats enough.

the site gets stabilized, and then we shift to the real win:

✅ build a reusable theme foundation ("dreamkit") once
✅ use it across all sites
✅ keep dreamscapes narrative layer as a skin/module, not the whole architecture

this protects time, prevents repeated mistakes, and produces higher quality across the entire network.

future plan (whats next)

phase 1: stabilize (fast, minimal)

the goal is simple: no fatal errors, no missing includes, no fake asset links.

add missing helper functions (like the avatar helper)
modularize functions.php into inc/ files
either create a real /assets/js/ structure or remove enqueue references
keep debug configuration out of theme files (debug belongs in wp-config.php)

phase 2: modular theme architecture

instead of one giant style.css, we move to a maintainable structure:

assets/css/base.css (reset, tokens, typography)
assets/css/layout.css (container, header, footer)
assets/css/components/*.css (cards, buttons, hero, pagination)
assets/css/pages/*.css (home, archive, single, page)
build step optional, but not required (can still enqueue raw files)

same for php:

inc/setup.php
inc/enqueue.php
inc/template-tags.php
inc/filters.php
inc/security.php

phase 3: make it "portfolio-grade"

this is where the site becomes a true representation of web design capability:

a strong homepage that explains the product/system in 5 seconds
a "work / builds" section with clean case-study cards
a "start here" path for new visitors
clear ctas (newsletter, discord, github, twitch, etc.)
performance polish (image sizes, caching, minimal js)

the takeaway

this sprint wasnt wasted. it proved something important:

the theme design direction is strong, but the project needs structure before it needs more polish.

so were doing what builders do:

stop bleeding time into fragile code
extract the reusable core
build a foundation that scales across everything

digital dreamscape isnt going away.

its js moving from "hand-built theme experiment" to "modular system that can grow."

[episode complete]

this episode has been logged to memory. identity state updated. questline progression recorded.
"""

def find_post_by_slug(deployer, remote_path: str, slug: str) -> int:
    """Find post ID by slug."""
    command = f"cd {remote_path} && wp post list --name={slug} --format=json --allow-root"
    result = deployer.execute_command(command)
    
    if result and result.strip() != "[]":
        import json
        try:
            posts = json.loads(result)
            if posts:
                return posts[0]['ID']
        except:
            pass
    
    return None

def update_post_content(deployer, remote_path: str, post_id: int, content: str) -> bool:
    """Update post content using WP-CLI."""
    # Escape content for shell
    import shlex
    content_escaped = shlex.quote(content)
    
    command = f"cd {remote_path} && wp post update {post_id} --post_content={content_escaped} --allow-root"
    result = deployer.execute_command(command)
    
    if "Success" in result or "Updated" in result or f"post {post_id}" in result.lower():
        return True
    return False

def main():
    """Main execution."""
    print("=" * 70)
    print("FIX DIGITALDREAMSCAPE.SITE POST VOCAL PATTERNS")
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
    
    print(f"📂 Remote path: {remote_path}")
    print()
    
    # Connect
    print("🔌 Connecting to server...")
    if not deployer.connect():
        print("❌ Failed to connect")
        return
    print("✅ Connected")
    print()
    
    # Find post
    print("🔍 Finding post 'digital-dreamscape-site-update'...")
    post_id = find_post_by_slug(deployer, remote_path, "digital-dreamscape-site-update")
    
    if not post_id:
        print("❌ Post not found")
        return
    
    print(f"✅ Found post (ID: {post_id})")
    print()
    
    # Update content
    print("📝 Updating post content to match YAML vocal patterns...")
    print("   - Converting to lowercase casual style")
    print("   - Using contractions (im, id, dont, cant, js, cs)")
    print("   - Stream-of-consciousness flow")
    print("   - Raw, authentic tone")
    print()
    
    if update_post_content(deployer, remote_path, post_id, REWRITTEN_CONTENT):
        print("✅ Post updated successfully")
    else:
        print("❌ Failed to update post")
        return
    
    print()
    print("=" * 70)
    print("✅ POST VOCAL PATTERNS FIXED")
    print("=" * 70)
    print()
    print("View the updated post:")
    print("https://digitaldreamscape.site/digital-dreamscape-site-update/")
    print()

if __name__ == "__main__":
    main()

