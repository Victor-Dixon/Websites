import json
import os
from pathlib import Path
from datetime import datetime

REPO_ROOT = Path(__file__).resolve().parents[1]
SITES_REGISTRY = REPO_ROOT / "configs" / "sites_registry.json"
AUDIT_FILE = REPO_ROOT / "global_plugin_audit_results.json"

def load_domains():
    if not SITES_REGISTRY.exists():
        return []
    with open(SITES_REGISTRY, 'r') as f:
        data = json.load(f)
    return sorted(data.keys())

def find_site_locations(domain):
    locations = []
    # Common patterns in this repo
    candidates = [
        REPO_ROOT / domain,
        REPO_ROOT / "sites" / domain,
        REPO_ROOT / "websites" / domain, # Added this based on observation
    ]
    for p in candidates:
        if p.exists() and p.is_dir():
            locations.append(p)
    return locations

def find_plugins(site_root):
    plugins_dir = site_root / "wp-content" / "plugins"
    # Also check for wp/wp-content/plugins (some sites structure)
    if not plugins_dir.exists():
        plugins_dir = site_root / "wp" / "wp-content" / "plugins"
    
    if not plugins_dir.exists():
        return []

    plugins = []
    for item in plugins_dir.iterdir():
        if item.is_dir():
            # Check if it has a PHP file
            has_php = any(item.glob("*.php"))
            if has_php:
                plugins.append(item.name)
    return sorted(plugins)

def main():
    domains = load_domains()
    results = {
        "audit_date": datetime.now().isoformat(),
        "websites": {},
        "summary": {}
    }

    total_websites = 0
    websites_audited = 0
    total_plugins = 0

    for domain in domains:
        total_websites += 1
        locs = find_site_locations(domain)
        
        site_info = {
            "status": "skipped",
            "reason": "Path not found",
            "plugins": [],
            "plugin_count": 0
        }

        if locs:
            # Use the first location found
            site_root = locs[0]
            site_info["site_path"] = str(site_root.relative_to(REPO_ROOT))
            plugins = find_plugins(site_root)
            
            site_info["status"] = "audited"
            site_info["reason"] = "Scanned local directory"
            site_info["plugins"] = plugins
            site_info["plugin_count"] = len(plugins)
            
            websites_audited += 1
            total_plugins += len(plugins)
        
        results["websites"][domain] = site_info

    results["summary"] = {
        "total_websites": total_websites,
        "websites_audited": websites_audited,
        "total_plugins": total_plugins
    }

    with open(AUDIT_FILE, 'w') as f:
        json.dump(results, f, indent=2)
    
    print(f"Audit complete. Results saved to {AUDIT_FILE}")
    print(json.dumps(results["summary"], indent=2))

if __name__ == "__main__":
    main()
