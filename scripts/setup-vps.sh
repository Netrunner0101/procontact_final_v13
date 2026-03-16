#!/bin/bash
# =============================================================================
# ProContact - VPS Initial Setup Script (Ubuntu 22.04/24.04)
# Run this ONCE on a fresh VPS: curl -sSL <url> | bash
# =============================================================================
set -euo pipefail

APP_DIR="/var/www/procontact"

echo "========================================="
echo " ProContact VPS Setup"
echo "========================================="

# Update system
echo "[1/6] Updating system packages..."
sudo apt update && sudo apt upgrade -y

# Install Docker
echo "[2/6] Installing Docker..."
if ! command -v docker &>/dev/null; then
    curl -fsSL https://get.docker.com | sudo sh
    sudo usermod -aG docker "$USER"
    echo "  -> Docker installed. You may need to log out/in for group changes."
else
    echo "  -> Docker already installed."
fi

# Install Docker Compose plugin (if not bundled)
echo "[3/6] Ensuring Docker Compose is available..."
if ! docker compose version &>/dev/null; then
    sudo apt install -y docker-compose-plugin
fi

# Install useful tools
echo "[4/6] Installing utilities..."
sudo apt install -y git curl ufw fail2ban

# Configure firewall
echo "[5/6] Configuring firewall..."
sudo ufw allow OpenSSH
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw --force enable

# Clone repository
echo "[6/6] Setting up application directory..."
if [ ! -d "$APP_DIR" ]; then
    sudo mkdir -p "$APP_DIR"
    sudo chown "$USER":"$USER" "$APP_DIR"
    echo ""
    echo "  Next steps:"
    echo "  1. Clone your repo:  git clone <your-repo-url> $APP_DIR"
    echo "  2. cd $APP_DIR"
    echo "  3. cp .env.production .env"
    echo "  4. Edit .env with your actual values (DB password, APP_KEY, domain, etc.)"
    echo "  5. Generate APP_KEY:  docker compose run --rm app php artisan key:generate --show"
    echo "  6. Start everything: docker compose up -d"
    echo "  7. Run migrations:   docker compose exec app php artisan migrate --force"
else
    echo "  -> $APP_DIR already exists."
fi

echo ""
echo "========================================="
echo " VPS setup complete!"
echo "========================================="
echo ""
echo " Optional: Install SSL with Certbot"
echo "   sudo apt install certbot"
echo "   sudo certbot certonly --standalone -d yourdomain.com"
echo "   Then update docker/nginx/default.conf with SSL config"
echo ""
