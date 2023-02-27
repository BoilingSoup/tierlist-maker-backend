FROM composer:2.2.5 AS composer

FROM php:8.1-fpm-alpine3.15
COPY --from=composer /usr/bin/composer /usr/bin/composer 

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


RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install \
  pcntl

RUN apk add --update libpq-dev \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql

WORKDIR /entrypoint
COPY entrypoint.sh /entrypoint
RUN chmod +x /entrypoint/entrypoint.sh

WORKDIR /app/laravel/src
ENTRYPOINT ["/entrypoint/entrypoint.sh"]
