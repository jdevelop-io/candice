ARG PHP_VERSION

FROM shinsenter/php:${PHP_VERSION}-cli AS base

WORKDIR /var/www/html

# Install xdebug
RUN phpaddmod xdebug

FROM base AS test

ENTRYPOINT ["php", "bin/phpunit"]

FROM base AS coverage

ENV COVERAGE_DIR=/var/www/html/var/coverage
ENV XDEBUG_MODE=coverage

ENTRYPOINT ["sh", "-c", "php bin/phpunit --coverage-html ${COVERAGE_DIR}"]
