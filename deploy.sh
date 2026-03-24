#!/usr/bin/env bash
set -euo pipefail

# ── Configuration ──────────────────────────────────
REGISTRY="ghcr.io"
IMAGE_NAME="netrunner0101/procontact_final_v13"
DEPLOY_DIR="/opt/procontact"

# ── Parse arguments ────────────────────────────────
ENV="${1:-}"
if [[ "$ENV" != "staging" && "$ENV" != "production" ]]; then
  echo "Usage: ./deploy.sh <staging|production>"
  exit 1
fi

# Required env vars
: "${VPS_HOST:?Set VPS_HOST to your server IP/hostname}"
VPS_USER="${VPS_USER:-root}"

IMAGE_TAG="${ENV}-$(git rev-parse --short HEAD)"

echo "==> Deploying ${ENV} (${IMAGE_TAG})"

# ── Step 1: Build Docker image ────────────────────
echo "==> Building image: ${REGISTRY}/${IMAGE_NAME}:${IMAGE_TAG}"
docker build -f docker/production/Dockerfile \
  -t "${REGISTRY}/${IMAGE_NAME}:${IMAGE_TAG}" \
  -t "${REGISTRY}/${IMAGE_NAME}:${ENV}-latest" \
  .

# ── Step 2: Push to GHCR ─────────────────────────
echo "==> Pushing to GHCR..."
docker push "${REGISTRY}/${IMAGE_NAME}:${IMAGE_TAG}"
docker push "${REGISTRY}/${IMAGE_NAME}:${ENV}-latest"

# ── Step 3: Copy compose file to VPS ──────────────
echo "==> Copying docker-compose.yml to VPS..."
ssh "${VPS_USER}@${VPS_HOST}" "mkdir -p ${DEPLOY_DIR}/${ENV}"
scp docker/production/docker-compose.yml "${VPS_USER}@${VPS_HOST}:${DEPLOY_DIR}/${ENV}/docker-compose.yml"

# ── Step 4: Deploy on VPS ─────────────────────────
echo "==> Deploying on VPS..."
ssh "${VPS_USER}@${VPS_HOST}" bash -s <<REMOTE
  set -e
  cd ${DEPLOY_DIR}/${ENV}

  # Export env vars for compose
  export IMAGE_TAG=${IMAGE_TAG}
  export ENV=${ENV}

  # Pull new images
  docker compose pull app queue scheduler

  # Restart containers
  docker compose up -d --no-deps --remove-orphans app queue scheduler

  # Wait for app to be ready (max 30s)
  timeout 30 sh -c 'until docker compose exec -T app php artisan --version 2>/dev/null; do sleep 2; done'

  # Run migrations
  docker compose exec -T app php artisan migrate --force

  # Cleanup old images
  docker image prune -f

  echo "==> ${ENV} deploy complete (${IMAGE_TAG})"
REMOTE
