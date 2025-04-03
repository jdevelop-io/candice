ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-alpine AS development

WORKDIR /var/www/html

# Install dependencies
RUN apk add --no-cache \
    bash \
    git \
    libzip-dev \
    zip \
    unzip && \
    docker-php-ext-install zip

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./docker/web/entrypoint.sh /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html/public"]
