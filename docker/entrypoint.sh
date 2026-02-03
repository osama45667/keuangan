#!/bin/sh
set -e

PORT_VALUE="${PORT:-80}"
# Only substitute PORT to avoid wiping nginx variables like $document_root
env PORT="$PORT_VALUE" envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

php-fpm -D
nginx -g 'daemon off;'
