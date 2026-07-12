#!/bin/bash
set -e

cd /var/www/html

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
