#!/bin/sh
set -e
cd /var/www

mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

if [ "$1" = "php-fpm" ]; then
  php artisan package:discover --ansi
  php artisan config:cache
  php artisan view:cache
  php artisan migrate --force
fi

chown -R www-data:www-data storage bootstrap/cache
exec "$@"
