#!/usr/bin/env bash

# Install dependencies if vendor directory does not exist or is empty
if [ ! -d "$CANDICE_HOME/vendor" ] || [ -z "$(ls -A $CANDICE_HOME/vendor)" ]; then
    echo "Directory $CANDICE_HOME/vendor does not exist or is empty."
    echo "Installing dependencies..."
    composer install --no-interaction --no-progress

    if [ $? -ne 0 ]; then
        echo "Failed to install dependencies."
        exit 1
    fi

    echo "Dependencies installed successfully."
fi

# Run the command passed to the docker run command
echo "Running command: $@"
exec "$@"

if [ $? -ne 0 ]; then
    echo "Command failed."
    exit 1
fi

echo "Command executed successfully."
