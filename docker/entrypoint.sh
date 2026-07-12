#!/bin/bash
set -e

cd /var/www/html

# Render's env var generateValue produces a plain random string, not
# Laravel's required "base64:<32 bytes>" format, so we generate a real one
# here instead. Note: without a persistent disk to cache it on, this means
# a fresh key (and invalidated sessions/cookies) on every container
# restart — acceptable for the free/ephemeral setup, but worth pinning to
# a stable value via a mounted disk before relying on this for a real store.
if [ -z "$APP_KEY" ] || [[ "$APP_KEY" != base64:* ]]; then
    export APP_KEY=$(php artisan key:generate --show)
fi

if [ "$DB_CONNECTION" = "sqlite" ]; then
    mkdir -p database
    touch database/database.sqlite
    chown -R www-data:www-data database
fi

php artisan storage:link || true
php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --class=ProductionEssentialsSeeder --force

exec /usr/bin/supervisord -c /etc/supervisord.conf
