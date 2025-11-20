# Websites Repository

Unified repository for all WordPress themes, plugins, and static websites.

## Sites

- **FreeRideInvestor** - Trading education platform
- **Southwest Secret** - Music/DJ showcase site
- **WE ARE SWARM** - Swarm intelligence platform
- **TradingRobotPlug** - Trading automation platform

## Structure

```
websites/
├── themes/
│   ├── freerideinvestor/
│   ├── southwestsecret/
│   ├── swarm/
│   └── tradingrobotplug/
├── plugins/
│   └── (shared plugins)
├── tools/
│   └── (deployment tools)
└── docs/
    └── (documentation)
```

## Auto-Deployment

This repository is configured with pre-commit hooks that automatically deploy changes to live websites when code is pushed to GitHub.

### Deployment Process

1. Make changes to theme/plugin files
2. Commit changes: `git commit -m "Update theme"`
3. Pre-commit hook triggers deployment
4. Push to GitHub: `git push origin main`
5. Changes are live on website

## Setup

1. Clone repository: `git clone <repo-url>`
2. Install dependencies: `pip install -r requirements.txt`
3. Configure deployment: Update `tools/wordpress_deployment_manager.py` with credentials
4. Start developing!

## Deployment Tools

- `wordpress_deployment_manager.py` - Main deployment tool
- `wordpress_deployer.py` - Theme deployment
- `deploy_static_html.py` - Static HTML deployment

## Notes

- All sites deploy to Hostinger server (157.173.214.121:65002)
- Credentials stored in environment variables
- Pre-commit hooks ensure automatic deployment
