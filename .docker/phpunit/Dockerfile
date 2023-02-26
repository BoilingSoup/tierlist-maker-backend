FROM php:8.1-fpm-alpine3.15

RUN apk add --update libpq-dev \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql


# Install PHP Extensions (igbinary & memcached)
RUN apk add --no-cache --update libmemcached-libs zlib
RUN set -xe && \
  cd /tmp/ && \
  apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS && \
  apk add --no-cache --update --virtual .memcached-deps zlib-dev libmemcached-dev cyrus-sasl-dev && \
  # Install igbinary (memcached's deps)
  pecl install igbinary && \
  # Install memcached
  ( \
  pecl install --nobuild memcached && \
  cd "$(pecl config-get temp_dir)/memcached" && \
  phpize && \
  ./configure --enable-memcached-igbinary && \
  make -j$(nproc) && \
  make install && \
  cd /tmp/ \
  ) && \
  # Enable PHP extensions
  docker-php-ext-enable igbinary memcached && \
  rm -rf /tmp/* && \
  apk del .memcached-deps .phpize-deps


RUN addgroup -g 1001 php
RUN adduser -u 1000 -G php -D php

WORKDIR /entrypoint
COPY entrypoint.sh /entrypoint

RUN chown -R php:php /entrypoint
RUN chmod +x /entrypoint/entrypoint.sh

# Set php-fpm process to run with same uid:gid as project files to run phpunit
COPY www.conf /usr/local/etc/php-fpm.d/www.conf

USER php

ENTRYPOINT ["/entrypoint/entrypoint.sh"]
