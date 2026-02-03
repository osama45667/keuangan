#!/bin/sh
set -e

PORT_VALUE="${PORT:-80}"
# Replace __PORT__ (preferred) and any leftover ${PORT} to avoid nginx errors.
sed "s/__PORT__/${PORT_VALUE}/g; s/\\${PORT}/${PORT_VALUE}/g" /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

php-fpm -D
nginx -g 'daemon off;'
