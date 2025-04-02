#!/usr/bin/env bash

WORKDIR="/var/www/html"

# Install dependencies if vendor directory does not exist or is empty
if [ ! -d "$WORKDIR/vendor" ] || [ -z "$(ls -A $WORKDIR/vendor)" ]; then
    composer install --no-interaction --no-progress
fi

# Run the command passed to the docker run command
exec "$@"
