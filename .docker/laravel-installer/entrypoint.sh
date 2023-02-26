#!/bin/sh
php composer.phar create-project laravel/laravel /app/laravel/src
cd /app/laravel/src

# Update .env file with postgres DB
rm .env.example
rm .env
mv /app/.env.example /app/laravel/src
cp /app/laravel/src/.env.example /app/laravel/src/.env

# Update database.php and phpunit.xml to include test DB
rm phpunit.xml
rm ./config/database.php 
mv /app/phpunit.xml /app/laravel/src/phpunit.xml
mv /app/database.php /app/laravel/src/config/database.php

# Generate key for Laravel project
php /app/laravel/src/artisan key:generate

# Symlink storage to public folder
php /app/composer.phar require symfony/filesystem  
php /app/laravel/src/artisan storage:link --relative


# Gets laravel-ide-helper for autocompletion
php /app/composer.phar require --dev barryvdh/laravel-ide-helper


chown -R 1000:1001 /app/laravel/src/

# Allow nginx to read/write cache & static files
chmod -R ug+w /app/laravel/src/storage
chmod -R ug+w /app/laravel/src/vendor
chmod -R ug+w /app/laravel/src/bootstrap/cache

