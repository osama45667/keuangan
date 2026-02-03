#!/bin/sh
set -e

PORT_VALUE="${PORT:-80}"
# Replace only the __PORT__ placeholder to keep nginx variables intact.
sed "s/__PORT__/${PORT_VALUE}/g" /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

php-fpm -D
nginx -g 'daemon off;'
