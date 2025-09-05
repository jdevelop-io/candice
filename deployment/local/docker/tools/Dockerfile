ARG PHP_VERSION=8.4
ARG COMPOSER_VERSION=2.8

FROM composer:${COMPOSER_VERSION} AS composer

FROM php:${PHP_VERSION}-cli AS base

# Set working directory
ENV CANDICE_HOME /var/www/candice
WORKDIR $CANDICE_HOME

# Turn off Xdebug by default
# Enable it by setting the XDEBUG_MODE env variable to "develop" or "debug"
ENV XDEBUG_MODE off

# Install system dependencies
RUN apt-get update -y && apt-get install -y --no-install-recommends \
    git \
    libzip-dev \
    unzip && \
    pecl install xdebug && \
    docker-php-ext-install zip && \
    docker-php-ext-enable xdebug && \
    apt-get clean

# Install Composer
# Copy from the official Composer image to leverage its caching mechanism
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Set the default entrypoint script
COPY ./tools/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
ENTRYPOINT ["docker-entrypoint"]

FROM base AS shell

# Override the entrypoint
ENTRYPOINT ["docker-entrypoint", "/bin/bash"]

FROM base AS phpunit

# Override the entrypoint
ENTRYPOINT ["docker-entrypoint", "vendor/bin/phpunit"]
