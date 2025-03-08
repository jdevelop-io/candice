ARG PHP_VERSION

FROM shinsenter/php:${PHP_VERSION}-cli AS base

WORKDIR /var/www/html

# Install xdebug
RUN phpaddmod xdebug

FROM base AS test

ENTRYPOINT ["php", "bin/phpunit"]

FROM base AS coverage

ENV XDEBUG_MODE=coverage

ENTRYPOINT ["php", "bin/phpunit"]
