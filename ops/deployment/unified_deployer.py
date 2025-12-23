#!/usr/bin/env python3
"""
Unified Website Deployer
=========================

A unified deployment script that works for all websites.
Uses SimpleWordPressDeployer with automatic credential loading.

Author: Agent-7 (Web Development Specialist)
Date: 2025-12-23
"""

import sys
import json
from pathlib import Path

# Add deployment tools to path
sys.path.insert(0, str(Path(__file__).parent))

try:
    from simple_wordpress_deployer import SimpleWordPressDeployer, load_site_configs
    SIMPLE_DEPLOYER_AVAILABLE = True
except ImportError:
    SIMPLE_DEPLOYER_AVAILABLE = False
    print("❌ ERROR: SimpleWordPressDeployer not found!")

# Load .env file for credentials
try:
    from dotenv import load_dotenv, dotenv_values
    env_vars = dotenv_values("D:/Agent_Cellphone_V2_Repository/.env")
    import os
    for key, value in env_vars.items():
        if value and key not in os.environ:
            os.environ[key] = value
    load_dotenv("D:/Agent_Cellphone_V2_Repository/.env")
except ImportError:
    pass
except Exception:
    pass


def get_site_files(site_domain: str) -> list:
    """Get list of files to deploy for a site based on its structure."""
    base_path = Path("D:/websites/websites") / site_domain
    if not base_path.exists():
        base_path = Path("D:/websites") / site_domain
    
    files_to_deploy = []
    
    # Check for WordPress theme files
    theme_path = base_path / "wp" / "wp-content" / "themes"
    if theme_path.exists():
        for theme_dir in theme_path.iterdir():
            if theme_dir.is_dir():
                # Add PHP files from theme
                for php_file in theme_dir.glob("*.php"):
                    relative_path = php_file.relative_to(base_path)
                    files_to_deploy.append(str(relative_path))
                # Add style.css
                style_css = theme_dir / "style.css"
                if style_css.exists():
                    relative_path = style_css.relative_to(base_path)
                    files_to_deploy.append(str(relative_path))
    
    # Check for wordpress-theme directory
    wp_theme_path = base_path / "wordpress-theme"
    if wp_theme_path.exists():
        for theme_dir in wp_theme_path.iterdir():
            if theme_dir.is_dir():
                for file in theme_dir.rglob("*"):
                    if file.is_file() and file.suffix in ['.php', '.css', '.js']:
                        relative_path = file.relative_to(base_path)
                        files_to_deploy.append(str(relative_path))
    
    return files_to_deploy


def deploy_site_files(site_domain: str, files: list = None, dry_run: bool = False) -> bool:
    """Deploy files for a specific site."""
    print(f"\n{'='*60}")
    print(f"🌐 DEPLOYING: {site_domain}")
    print(f"{'='*60}\n")
    
    if not SIMPLE_DEPLOYER_AVAILABLE:
        print("❌ SimpleWordPressDeployer not available!")
        return False
    
    try:
        # Load site configs
        site_configs = load_site_configs()
        if not site_configs:
            print("❌ No site configurations found!")
            return False
        
        # Get site key (domain without .com/.site/.online)
        site_key = site_domain.replace('.com', '').replace('.site', '').replace('.online', '').replace('.', '')
        
        # Initialize deployer
        try:
            deployer = SimpleWordPressDeployer(site_domain, site_configs)
        except ValueError:
            # Try with site_key instead
            try:
                deployer = SimpleWordPressDeployer(site_key, site_configs)
            except ValueError as e:
                print(f"❌ Site '{site_domain}' not found in configuration: {e}")
                return False
        
        # Connect
        print(f"📡 Connecting to {site_domain}...")
        if not deployer.connect():
            print(f"❌ Failed to connect")
            return False
        print("✅ Connected!\n")
        
        # Get files to deploy
        if files is None:
            files = get_site_files(site_domain)
        
        if not files:
            print("⚠️  No files found to deploy")
            deployer.disconnect()
            return True
        
        # Deploy files
        base_path = Path("D:/websites/websites") / site_domain
        if not base_path.exists():
            base_path = Path("D:/websites") / site_domain
        
        success_count = 0
        fail_count = 0
        
        for file_path in files:
            local_path = base_path / file_path
            if not local_path.exists():
                print(f"⚠️  File not found: {local_path}")
                continue
            
            # Determine remote path
            if 'wp/wp-content/themes' in file_path or 'wordpress-theme' in file_path:
                # Extract theme name and file path
                parts = Path(file_path).parts
                if 'wp-content' in parts:
                    wp_index = parts.index('wp-content')
                    remote_path = '/'.join(parts[wp_index:])
                elif 'wordpress-theme' in parts:
                    theme_index = parts.index('wordpress-theme')
                    theme_name = parts[theme_index + 1]
                    filename = parts[-1]
                    subdir = '/'.join(parts[theme_index + 2:-1]) if len(parts) > theme_index + 2 else ''
                    if subdir:
                        remote_path = f"wp-content/themes/{theme_name}/{subdir}/{filename}"
                    else:
                        remote_path = f"wp-content/themes/{theme_name}/{filename}"
                else:
                    remote_path = f"wp-content/themes/{site_key}/{Path(file_path).name}"
            else:
                remote_path = f"wp-content/themes/{site_key}/{Path(file_path).name}"
            
            print(f"📤 Deploying: {file_path} -> {remote_path}")
            
            if not dry_run:
                if deployer.deploy_file(local_path, remote_path):
                    print(f"✅ Deployed")
                    success_count += 1
                else:
                    print(f"❌ Failed")
                    fail_count += 1
            else:
                print(f"   [DRY RUN - Would deploy]")
                success_count += 1
        
        deployer.disconnect()
        
        print(f"\n📊 Summary:")
        print(f"   ✅ Succeeded: {success_count}")
        print(f"   ❌ Failed: {fail_count}")
        
        return fail_count == 0
        
    except Exception as e:
        print(f"❌ Error: {e}")
        import traceback
        traceback.print_exc()
        return False


def main():
    """Main execution."""
    import argparse
    
    parser = argparse.ArgumentParser(description='Unified website deployer')
    parser.add_argument('--site', type=str, help='Site domain to deploy')
    parser.add_argument('--all', action='store_true', help='Deploy all sites')
    parser.add_argument('--dry-run', action='store_true', help='Dry run (no actual deployment)')
    parser.add_argument('--files', nargs='+', help='Specific files to deploy')
    
    args = parser.parse_args()
    
    print("\n" + "="*60)
    print("🚀 UNIFIED WEBSITE DEPLOYER")
    print("="*60)
    
    if args.all:
        # Load site registry
        registry_path = Path("D:/websites/configs/sites_registry.json")
        sites = []
        if registry_path.exists():
            with open(registry_path, 'r') as f:
                registry = json.load(f)
                sites = list(registry.keys())
        else:
            # Fallback to site_configs
            config_path = Path("D:/websites/configs/site_configs.json")
            if config_path.exists():
                with open(config_path, 'r') as f:
                    configs = json.load(f)
                    sites = list(configs.keys())
        
        results = {}
        for site in sites:
            results[site] = deploy_site_files(site, dry_run=args.dry_run)
        
        # Summary
        print("\n" + "="*60)
        print("📊 DEPLOYMENT SUMMARY")
        print("="*60)
        for site, success in results.items():
            status = "✅" if success else "❌"
            print(f"{status} {site}")
        
    elif args.site:
        success = deploy_site_files(args.site, files=args.files, dry_run=args.dry_run)
        return 0 if success else 1
    else:
        parser.print_help()
        return 1
    
    return 0


if __name__ == '__main__':
    exit(main())

