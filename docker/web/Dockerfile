ARG PHP_VERSION

FROM shinsenter/symfony:php${PHP_VERSION}

WORKDIR /var/www/html

FROM base AS development

# Install xdebug
RUN phpaddmod xdebug
