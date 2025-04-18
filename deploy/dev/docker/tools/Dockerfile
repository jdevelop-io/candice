ARG PHP_VERSION=8.4
ARG COMPOSER_VERSION=lts

FROM composer:${COMPOSER_VERSION} AS composer

FROM php:${PHP_VERSION}-cli AS base

# Set the working directory
ENV WORKDIR /var/www/html
WORKDIR ${WORKDIR}

# Turn off Xdebug by default.
# Enable it by setting the XDEBUG_MODE environment variable to "debug" or "develop".
ENV XDEBUG_MODE off

# Install dependencies
RUN apt-get update -y && apt-get install -y --no-install-recommends \
    git \
    libzip-dev \
    unzip && \
    pecl install xdebug && \
    docker-php-ext-install zip && \
    docker-php-ext-enable xdebug && \
    apt-get clean

# Install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Set entrypoint
COPY ./tools/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
ENTRYPOINT ["docker-entrypoint"]

FROM base AS phpunit

# Override the entrypoint
ENTRYPOINT ["docker-entrypoint", "vendor/bin/phpunit"]

FROM phpunit AS phpunit-coverage

ENV XDEBUG_MODE coverage

# Override the command
CMD ["--coverage-text"]
