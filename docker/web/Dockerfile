ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-alpine AS development

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependencies
RUN apk add --no-cache \
    bash \
    git \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install \
    zip \
    pdo \
    pdo_pgsql \
    pgsql

WORKDIR /var/www/html

EXPOSE 80

COPY ./docker/web/entrypoint.sh /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html/public"]
