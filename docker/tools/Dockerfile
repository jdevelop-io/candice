ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-cli AS base

WORKDIR /var/www/html

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./docker/tools/entrypoint.sh /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

FROM base AS shell

CMD ["bash"]
