# Claude Code – Laravel Staging Docker Deployment Plan

Use this file when you want to **re‑deploy from scratch** on your VPS.

---

## 1. VPS basics (Ubuntu 24)

Run on VPS:

```bash
# Update
sudo apt update
sudo apt install -y docker.io docker-compose

# Add your user to docker group
sudo usermod -aG docker $USER
newgrp docker
```

## 2. Project root and repo

```bash
mkdir -p ~/laravel-staging
cd ~/laravel-staging
git clone https://github.com/netrunner0101/procontact_final_v13.git .
```

## 3. Create the external Docker network (if it doesn't exist)

```bash
docker network create services-network || true
```

## 4. Existing database

The staging app connects to the existing `procontact-db` container (PostgreSQL 16-alpine) which already hosts:

| Database | Purpose |
|----------|---------|
| `procontact_staging` | Staging app uses this (seeded with test data) |
| `procontact_production` | Migrated, empty, ready for production |
| `procontact` | Old DB (previous data still here) |

Make sure `procontact-db` is running and connected to `services-network`:

```bash
docker network connect services-network procontact-db || true
```

## 5. Docker files

All staging Docker files are located in `docker/staging/`:

| File | Purpose |
|------|---------|
| `docker/staging/Dockerfile` | PHP 8.3-FPM with PostgreSQL extensions |
| `docker/staging/nginx.conf` | Nginx config pointing to PHP-FPM |
| `docker/staging/docker-compose.yml` | Two services: app + nginx (uses existing procontact-db) |

## 6. Environment file

Copy and configure the staging environment:

```bash
cp .env.staging .env
php artisan key:generate
```

Or inside the container:

```bash
docker compose -f docker/staging/docker-compose.yml run --rm laravel-app php artisan key:generate
```

## 7. Firewall + first deploy

```bash
sudo ufw allow 8083/tcp

cd ~/laravel-staging

# Create the external network
docker network create services-network || true

# Install dependencies
docker compose -f docker/staging/docker-compose.yml run --rm laravel-app composer install --no-dev

# Generate app key (writes to .env)
docker compose -f docker/staging/docker-compose.yml run --rm laravel-app php artisan key:generate

# Run migrations
docker compose -f docker/staging/docker-compose.yml run --rm laravel-app php artisan migrate --force

# Start all services
docker compose -f docker/staging/docker-compose.yml up -d --build
```

The staging app will be available at: **http://72.60.23.156:8083**

## 8. Update after GitHub changes

```bash
cd ~/laravel-staging
git pull origin main
docker compose -f docker/staging/docker-compose.yml up -d --build laravel-app
```

## 9. Useful commands

```bash
# View logs
docker compose -f docker/staging/docker-compose.yml logs -f

# Run artisan commands
docker compose -f docker/staging/docker-compose.yml exec laravel-app php artisan tinker

# Restart everything
docker compose -f docker/staging/docker-compose.yml restart

# Tear down (preserves DB volume)
docker compose -f docker/staging/docker-compose.yml down

# Tear down + delete DB data
docker compose -f docker/staging/docker-compose.yml down -v
```

---

## Notes

- The staging app (`laravel-staging` + `nginx-staging`) connects to the existing `procontact-db` container using the `procontact_staging` database.
- No duplicate postgres container is created — all databases live in `procontact-db`.
- Port **8083** is used to avoid conflicts with the main app on port 80.
- The `services-network` external network allows containers to communicate across compose files.
