#!/usr/bin/env bash

# Install dependencies if vendor directory does not exist or is empty
if [ ! -d "/app/vendor" ] || [ -z "$(ls -A /app/vendor)" ]; then
    composer install --no-interaction --no-progress --no-suggest
fi

# Run the command passed to the docker run command
exec "$@"
