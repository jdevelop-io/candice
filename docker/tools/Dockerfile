ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-cli AS base

WORKDIR /var/www/html

# Install dependencies
RUN apt-get update -y && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the PHP configuration
COPY ./docker/tools/php.ini /usr/local/etc/php/conf.d/php.ini

COPY ./docker/tools/entrypoint.sh /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

FROM base AS shell

ENTRYPOINT ["entrypoint.sh", "bash"]

FROM base AS phpunit

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Set the Xdebug configuration
COPY ./docker/tools/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

ENTRYPOINT ["entrypoint.sh", "php", "bin/phpunit"]
