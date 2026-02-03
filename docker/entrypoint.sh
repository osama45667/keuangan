#!/bin/sh
set -e

PORT_VALUE="${PORT:-80}"
# Ensure nginx listens on the Railway-provided port.
if [ -f /etc/nginx/nginx.conf.template ]; then
  sed "s/__PORT__/${PORT_VALUE}/g; s/\\${PORT}/${PORT_VALUE}/g" /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf
else
  sed -i "s/__PORT__/${PORT_VALUE}/g; s/\\${PORT}/${PORT_VALUE}/g" /etc/nginx/nginx.conf
fi
echo "Using PORT=$PORT_VALUE"
grep -n "listen" /etc/nginx/nginx.conf || true

php-fpm -D
nginx -g 'daemon off;'
