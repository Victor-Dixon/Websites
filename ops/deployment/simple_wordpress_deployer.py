#!/usr/bin/env python3
"""
Simple WordPress Deployer
==========================

A lightweight WordPress deployment tool that uses site_configs.json
for SFTP credentials. Works without WordPressManager dependency.

Author: Agent-7 (Web Development Specialist)
Date: 2025-12-21
"""

import json
import sys
from pathlib import Path
from typing import Dict, Optional

try:
    import paramiko
    PARAMIKO_AVAILABLE = True
except ImportError:
    PARAMIKO_AVAILABLE = False


def load_hostinger_env_credentials():
    """Load Hostinger credentials from environment variables or .env file."""
    import os
    from dotenv import load_dotenv
    
    # Try to load .env from main repository
    env_path = Path("D:/Agent_Cellphone_V2_Repository/.env")
    if env_path.exists():
        load_dotenv(env_path)
    
    host = os.getenv("HOSTINGER_HOST")
    username = os.getenv("HOSTINGER_USER")
    password = os.getenv("HOSTINGER_PASS")
    port = int(os.getenv("HOSTINGER_PORT", "65002"))
    
    if all([host, username, password]):
        return {
            "host": host,
            "username": username,
            "password": password,
            "port": port
        }
    return None


def load_site_configs():
    """Load site configurations from multiple sources in priority order."""
    # Priority 1: Hostinger environment variables (.env)
    hostinger_creds = load_hostinger_env_credentials()
    if hostinger_creds:
        # Create a default config structure with Hostinger credentials
        # This will be used as fallback if site-specific config not found
        default_config = {
            "default": {
                "host": hostinger_creds["host"],
                "username": hostinger_creds["username"],
                "password": hostinger_creds["password"],
                "port": hostinger_creds["port"]
            }
        }
    
    # Priority 2: .deploy_credentials/sites.json (WordPressManager format)
    sites_json_path = Path("D:/Agent_Cellphone_V2_Repository/.deploy_credentials/sites.json")
    if sites_json_path.exists():
        try:
            with open(sites_json_path, 'r') as f:
                configs = json.load(f)
                # Merge with Hostinger defaults if available
                if hostinger_creds:
                    for site_key, site_config in configs.items():
                        if not site_config.get('host'):
                            site_config['host'] = hostinger_creds['host']
                            site_config['username'] = hostinger_creds['username']
                            site_config['password'] = hostinger_creds['password']
                            site_config['port'] = hostinger_creds['port']
                return configs
        except Exception as e:
            print(f"⚠️  Could not load sites.json: {e}")
    
    # Priority 3: site_configs.json
    config_path = Path("D:/websites/configs/site_configs.json")
    if not config_path.exists():
        config_path = Path(__file__).parent.parent.parent / "configs" / "site_configs.json"
    
    if config_path.exists():
        try:
            with open(config_path, 'r') as f:
                configs = json.load(f)
                # Merge with Hostinger defaults if available
                if hostinger_creds:
                    for site_key, site_config in configs.items():
                        sftp_config = site_config.get('sftp', {})
                        if not sftp_config.get('host'):
                            sftp_config['host'] = hostinger_creds['host']
                            sftp_config['username'] = hostinger_creds['username']
                            sftp_config['password'] = hostinger_creds['password']
                            sftp_config['port'] = hostinger_creds['port']
                return configs
        except Exception as e:
            print(f"❌ Could not load site_configs.json: {e}")
    
    # Priority 4: Return Hostinger defaults if available
    if hostinger_creds:
        return default_config
    
    return {}


class SimpleWordPressDeployer:
    """Simple WordPress deployer using SFTP from site_configs.json"""
    
    def __init__(self, site_key: str, site_configs: dict):
        """Initialize deployer with site configuration."""
        self.site_key = site_key
        self.site_config = None
        self.sftp = None
        self.transport = None
        
        # Try to find by site_key directly first (sites.json format)
        if site_key in site_configs:
            self.site_config = site_configs[site_key]
        else:
            # Find site config by domain or site_key (site_configs.json format)
            for domain, config in site_configs.items():
                if site_key in domain or domain.endswith(site_key):
                    self.site_config = config
                    break
        
        if not self.site_config:
            raise ValueError(f"Site '{site_key}' not found in configuration files")
    
    def connect(self) -> bool:
        """Connect to server via SFTP."""
        if not PARAMIKO_AVAILABLE:
            print("❌ paramiko library not installed. Install with: pip install paramiko")
            return False
        
        # Try to get credentials from Hostinger environment variables first
        import os
        from dotenv import load_dotenv
        
        env_path = Path("D:/Agent_Cellphone_V2_Repository/.env")
        if env_path.exists():
            load_dotenv(env_path)
        
        # Check environment variables first (Hostinger tool credentials)
        host = os.getenv("HOSTINGER_HOST")
        username = os.getenv("HOSTINGER_USER")
        password = os.getenv("HOSTINGER_PASS")
        port = int(os.getenv("HOSTINGER_PORT", "65002"))
        
        # If env vars not available, try site config
        if not all([host, username, password]):
            # Handle both sites.json and site_configs.json formats
            # sites.json format: direct keys like "host", "username", "password"
            # site_configs.json format: nested under "sftp" key
            if 'sftp' in self.site_config:
                sftp_config = self.site_config.get('sftp', {})
                host = sftp_config.get('host') or host
                username = sftp_config.get('username') or username
                password = sftp_config.get('password') or password
                port = sftp_config.get('port', port or 22)
                remote_path = sftp_config.get('remote_path', '')
            else:
                # sites.json format (direct keys)
                host = self.site_config.get('host') or host
                username = self.site_config.get('username') or username
                password = self.site_config.get('password') or password
                port = self.site_config.get('port', port or 22)
                remote_path = self.site_config.get('remote_path', '')
        else:
            # Use remote_path from site config if available
            if 'sftp' in self.site_config:
                remote_path = self.site_config.get('sftp', {}).get('remote_path', '')
            else:
                remote_path = self.site_config.get('remote_path', '')
        
        if not all([host, username, password]):
            print(f"❌ Incomplete SFTP credentials for {self.site_key}")
            print("   Tried: HOSTINGER_* env vars and site config")
            return False
        
        self.remote_path = remote_path
        
        try:
            self.transport = paramiko.Transport((host, port))
            self.transport.connect(username=username, password=password)
            self.sftp = paramiko.SFTPClient.from_transport(self.transport)
            return True
        except Exception as e:
            print(f"❌ Connection error: {e}")
            return False
    
    def deploy_file(self, local_path: Path, remote_path: str = None) -> bool:
        """Deploy a single file to the server."""
        if not self.sftp:
            print("❌ Not connected. Call connect() first.")
            return False
        
        try:
            # Build remote path
            if remote_path:
                # Use provided remote path (already includes base path)
                full_remote_path = remote_path
            else:
                # Build from base remote_path + local filename
                base_path = getattr(self, 'remote_path', '')
                if base_path:
                    full_remote_path = f"{base_path}/{local_path.name}"
                else:
                    full_remote_path = local_path.name
            
            # Normalize path separators
            full_remote_path = full_remote_path.replace('\\', '/')
            
            # Ensure we're using absolute path from home directory
            # Hostinger structure: /home/username/domains/domain.com/public_html/...
            if not full_remote_path.startswith('/'):
                # If relative, make it absolute from home
                username = self.site_config.get('username') if 'username' in self.site_config else self.site_config.get('sftp', {}).get('username', '')
                if username and not full_remote_path.startswith(f'/home/{username}'):
                    # Prepend home directory if not already there
                    if full_remote_path.startswith('domains/'):
                        full_remote_path = f"/home/{username}/{full_remote_path}"
                    elif full_remote_path.startswith('wp-content/'):
                        # This is wrong - should be in domains/domain.com/public_html/wp-content/
                        base_path = getattr(self, 'remote_path', '')
                        if base_path:
                            full_remote_path = f"/home/{username}/{base_path}/{full_remote_path}"
            
            # Ensure remote directory exists
            remote_dir = str(Path(full_remote_path).parent)
            
            # Create directory recursively using absolute paths
            parts = remote_dir.strip('/').split('/')
            current = ''
            for part in parts:
                if part:
                    current = f"{current}/{part}" if current else f"/{part}"
                    try:
                        self.sftp.stat(current)
                    except FileNotFoundError:
                        try:
                            self.sftp.mkdir(current)
                        except Exception as e:
                            # Directory might already exist or permission issue
                            pass
            
            # Upload file (use absolute path)
            self.sftp.put(str(local_path), full_remote_path)
            return True
        except Exception as e:
            print(f"❌ Upload error: {e}")
            import traceback
            traceback.print_exc()
            return False
    
    def execute_command(self, command: str) -> str:
        """Execute a command via SSH using the same credential loading as connect()."""
        if not PARAMIKO_AVAILABLE:
            return ""
        
        try:
            # Use same credential loading logic as connect() method
            import os
            from dotenv import load_dotenv
            
            env_path = Path("D:/Agent_Cellphone_V2_Repository/.env")
            if env_path.exists():
                load_dotenv(env_path)
            
            # Check environment variables first (Hostinger tool credentials)
            host = os.getenv("HOSTINGER_HOST")
            username = os.getenv("HOSTINGER_USER")
            password = os.getenv("HOSTINGER_PASS")
            port = int(os.getenv("HOSTINGER_PORT", "65002"))  # Hostinger uses 65002
            
            # If env vars not available, try site config
            if not all([host, username, password]):
                if 'sftp' in self.site_config:
                    sftp_config = self.site_config.get('sftp', {})
                    host = sftp_config.get('host') or host
                    username = sftp_config.get('username') or username
                    password = sftp_config.get('password') or password
                    port = sftp_config.get('port', port or 65002)  # Default to Hostinger port
                else:
                    host = self.site_config.get('host') or host
                    username = self.site_config.get('username') or username
                    password = self.site_config.get('password') or password
                    port = self.site_config.get('port', port or 65002)  # Default to Hostinger port
            
            if not all([host, username, password]):
                print(f"⚠️  Incomplete SSH credentials for {self.site_key}")
                return ""
            
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            ssh.connect(host, port=port, username=username, password=password, timeout=10)
            stdin, stdout, stderr = ssh.exec_command(command, timeout=30)
            
            output = stdout.read().decode('utf-8')
            error = stderr.read().decode('utf-8')
            
            ssh.close()
            
            if error and "error" in error.lower():
                print(f"⚠️  Command warning: {error[:200]}")
            
            return output if output else error
        except Exception as e:
            print(f"❌ SSH command error: {e}")
            return ""
    
    def disconnect(self):
        """Disconnect from server."""
        if self.sftp:
            self.sftp.close()
        if self.transport:
            self.transport.close()

