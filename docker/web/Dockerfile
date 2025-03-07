ARG PHP_VERSION

FROM shinsenter/symfony:php${PHP_VERSION} AS base

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs

WORKDIR /var/www/html

FROM base AS development

# Install xdebug
RUN phpaddmod xdebug
