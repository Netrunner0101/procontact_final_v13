#!/bin/bash
# =============================================================================
# ProContact - Manual Deploy Script
# Usage: ./scripts/deploy.sh
# =============================================================================
set -euo pipefail

APP_DIR="/var/www/procontact"
REPO_URL="${REPO_URL:-git@github.com:YOUR_USERNAME/procontact_final_v13.git}"
BRANCH="${BRANCH:-main}"

echo "========================================="
echo " ProContact Deployment"
echo "========================================="

cd "$APP_DIR"

# Pull latest code
echo "[1/7] Pulling latest code from $BRANCH..."
git fetch origin "$BRANCH"
git reset --hard "origin/$BRANCH"

# Build and restart containers
echo "[2/7] Building Docker images..."
docker compose build --no-cache app

echo "[3/7] Starting containers..."
docker compose up -d

# Wait for containers to be healthy
echo "[4/7] Waiting for database to be ready..."
sleep 5

# Run Laravel setup commands inside the app container
echo "[5/7] Running migrations..."
docker compose exec app php artisan migrate --force

echo "[6/7] Optimizing for production..."
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose exec app php artisan event:cache

echo "[7/7] Restarting queue workers..."
docker compose exec app php artisan queue:restart

echo ""
echo "========================================="
echo " Deployment complete!"
echo "========================================="
docker compose ps
