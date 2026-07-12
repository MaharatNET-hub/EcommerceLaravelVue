#!/bin/bash
set -e

cd /var/www/html

php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --class=ProductionEssentialsSeeder --force

exec /usr/bin/supervisord -c /etc/supervisord.conf
