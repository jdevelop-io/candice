ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-cli AS base

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependencies
RUN apt-get update -y && apt-get install -y --no-install-recommends \
    bash \
    git \
    libzip-dev \
    zip \
    unzip && \
    rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY ./docker/tools/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY ./docker/tools/php.ini /usr/local/etc/php/conf.d/php.ini

ENTRYPOINT ["entrypoint.sh"]

FROM base AS console

ENTRYPOINT ["entrypoint.sh", "php", "bin/console"]

FROM base AS phpcs

ENTRYPOINT ["entrypoint.sh", "php", "vendor/bin/phpcs"]

FROM base AS phpmd

ENTRYPOINT ["entrypoint.sh", "php", "vendor/bin/phpmd"]
CMD ["src,tests,bin,config,public", "text", "phpmd.xml"]

FROM base AS phpstan

ENTRYPOINT ["entrypoint.sh", "php", "vendor/bin/phpstan"]

FROM base AS phpunit

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY ./docker/tools/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

ENTRYPOINT ["entrypoint.sh", "php", "vendor/bin/phpunit"]

FROM phpunit AS coverage

ENV XDEBUG_MODE=coverage

CMD ["--coverage-text", "--coverage-html=var/coverage", "--coverage-clover=var/coverage.xml"]

FROM base AS deptrac

ENTRYPOINT ["entrypoint.sh", "php", "vendor/bin/deptrac"]
CMD ["analyze", "--fail-on-uncovered", "--report-uncovered", "--cache-file=var/cache/deptrac.cache"]
