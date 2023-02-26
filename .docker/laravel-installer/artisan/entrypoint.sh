#!/bin/sh
cd /app/laravel/src
php artisan $@
chown -R 1000:1001 /app/laravel/src
