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

COPY ./docker/web/entrypoint.sh /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

FROM base AS console

CMD ["php", "bin/console"]

FROM base AS phpcs

CMD ["php", "vendor/bin/phpcs"]

FROM base AS phpmd

CMD ["php", "vendor/bin/phpmd", "src,bin,config,public", "text", "phpmd.xml"]

FROM base AS phpstan

CMD ["php", "vendor/bin/phpstan", "analyse"]
