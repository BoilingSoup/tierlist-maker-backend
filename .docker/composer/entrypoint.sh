#!/bin/sh
cd /app/laravel/src
COMPOSER_ALLOW_SUPERUSER=1 /usr/bin/composer $@
# chown -R 1000:1001 /app/laravel/src
