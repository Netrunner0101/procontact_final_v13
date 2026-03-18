#!/bin/sh
set -e

echo "==> Running Laravel optimizations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "==> Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

echo "==> Running database migrations..."
php artisan migrate --force

echo "==> Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
