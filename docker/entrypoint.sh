#!/bin/sh
set -e

PORT_VALUE="${PORT:-8080}"
echo "Starting PHP server on 0.0.0.0:${PORT_VALUE}"

exec php -S 0.0.0.0:${PORT_VALUE} -t public
