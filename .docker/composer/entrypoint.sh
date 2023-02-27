#!/bin/sh
cd /app/laravel/src
/usr/bin/composer $@
chown -R 1000:1001 /app/laravel/src
