#!/bin/sh
cd /app/laravel/src
# clears any permission issues from cached files
php artisan cache:clear

php ./vendor/bin/phpunit
