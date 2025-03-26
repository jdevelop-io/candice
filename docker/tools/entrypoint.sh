#!/usr/bin/env bash

## Install dependencies if vendor directory does not exist
#if [ ! -d "/var/www/html/vendor" ]; then
#    composer install
#fi

echo "Running entrypoint.sh"
echo "Executing command: $@"
exec "$@"
