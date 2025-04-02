ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-cli AS base

WORKDIR /var/www/html

# Install dependencies
RUN apt-get update -y && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the PHP configuration
COPY ./docker/tools/php.ini /usr/local/etc/php/conf.d/php.ini

COPY ./docker/tools/entrypoint.sh /usr/local/bin/entrypoint.sh

# Turn off Xdebug by default
# Override this if you want to enable Xdebug
ENV XDEBUG_MODE=off

ENTRYPOINT ["entrypoint.sh"]

FROM base AS shell

ENTRYPOINT ["entrypoint.sh", "bash"]

FROM base AS phpunit

ENTRYPOINT ["entrypoint.sh", "php", "bin/phpunit"]

FROM phpunit AS phpunit-coverage

ENV XDEBUG_MODE=coverage

ENTRYPOINT ["entrypoint.sh", "php", "bin/phpunit"]
CMD ["--coverage-text"]

FROM base AS phpcs

ENTRYPOINT ["entrypoint.sh", "php", "vendor/bin/phpcs"]

FROM base AS phpcbf

ENTRYPOINT ["entrypoint.sh", "php", "vendor/bin/phpcbf"]

FROM base AS phpmd

ENTRYPOINT ["entrypoint.sh", "php", "vendor/bin/phpmd"]
CMD ["src,tests,bin,config,public", "text", "phpmd.xml.dist"]

FROM base AS phpstan

ENTRYPOINT ["entrypoint.sh", "php", "vendor/bin/phpstan"]
