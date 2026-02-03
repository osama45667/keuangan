#!/bin/sh
set -e

PORT_VALUE="${PORT:-8080}"

# Ensure writable Laravel directories exist in container
mkdir -p /var/www/html/storage/logs \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/bootstrap/cache
touch /var/www/html/storage/logs/laravel.log
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting PHP server on 0.0.0.0:${PORT_VALUE}"

exec php -S 0.0.0.0:${PORT_VALUE} -t public
