# FROM php:8.1-fpm-alpine3.15
FROM php:8.1.18-fpm-alpine3.17

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


RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install \
  pcntl


WORKDIR /entrypoint
COPY entrypoint.sh /entrypoint
RUN chmod +x /entrypoint/entrypoint.sh

# Downloads Composer
WORKDIR /app
RUN EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')" \
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")" \
  if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ] \
  then \
  >&2 echo 'ERROR: Invalid installer checksum' \
  rm composer-setup.php \
  exit 1 \
  fi

RUN php composer-setup.php --quiet
RUN rm composer-setup.php
RUN mv composer.phar /usr/bin/composer
RUN apk add --update --no-cache less


WORKDIR /app/laravel/src
ENTRYPOINT ["/entrypoint/entrypoint.sh"]

