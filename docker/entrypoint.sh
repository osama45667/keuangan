#!/bin/sh
set -e

PORT_VALUE="${PORT:-8080}"
# Generate nginx config from template and replace port placeholders.
sed "s/__PORT__/${PORT_VALUE}/g; s/\\${PORT}/${PORT_VALUE}/g; s/\\$PORT/${PORT_VALUE}/g" \
  /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf
echo "Using PORT=${PORT_VALUE}"
grep -n "listen" /etc/nginx/nginx.conf || true

php-fpm -D
nginx -g 'daemon off;'
