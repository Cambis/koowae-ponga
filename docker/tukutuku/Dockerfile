ARG COMPOSER_VERSION=2-bin
ARG NODE_VERSION=lts
ARG PHP_VERSION=8.3

FROM composer/composer:${COMPOSER_VERSION} AS composer-source
FROM node:${NODE_VERSION} AS node-source
FROM php:${PHP_VERSION}-cli AS base

# Install apt dependencies
RUN apt-get update -qq; \
  DEBIAN_FRONTEND=noninteractive apt-get install -qqy \
    git \
    netcat-traditional \
    unzip \
    zip \
  --no-install-recommends; \
  rm -rf /var/lib/apt/lists*

# Copy over extension installer
COPY --from=ghcr.io/mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install php extensions
 RUN install-php-extensions \
  bcmath \
  exif \
  gd \
  gmp \
  intl \
  ldap \
  mysqli \
  opcache \
  pdo \
  pdo_mysql \
  soap \
  tidy \
  xsl \
  zip

# Install composer from source
COPY --from=composer-source /composer /usr/local/bin/

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install node from source
COPY --from=node-source /usr/local/bin/node /usr/local/bin/
COPY --from=node-source /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node-source /opt/ /opt/

RUN ln -fs /usr/local/bin/node /usr/local/bin/nodejs && \
  ln -fs ../lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm && \
  ln -fs ../lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx && \
  ln -fs /opt/yarn*/bin/yarn /usr/local/bin/yarn && \
  ln -fs /opt/yarn*/bin/yarnpkg /usr/local/bin/yarnpkg && \
  node --version && \
  npm --version && \
  yarn --version

# Copy ini settings
COPY --link ./docker/tukutuku/conf.d/app.ini ${PHP_INI_DIR}/conf.d/

# Add vendor directory to path
ENV PATH="./vendor/bin:$PATH"

WORKDIR /app

# Add a named non-root user
RUN groupadd --gid 1000 silverstripe; \
  useradd --uid 1000 --gid silverstripe --shell /bin/bash --create-home silverstripe

# Add the entrypoint
COPY --link --chmod=755 ./docker/tukutuku/docker-entrypoint.sh /usr/bin/docker-entrypoint.sh
ENTRYPOINT [ "/usr/bin/docker-entrypoint.sh" ]

USER 1000

CMD [ "php", "--version" ]
