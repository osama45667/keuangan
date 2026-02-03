#!/bin/sh
set -e

PORT_VALUE="${PORT:-80}"
env PORT="$PORT_VALUE" envsubst < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

php-fpm -D
nginx -g 'daemon off;'
