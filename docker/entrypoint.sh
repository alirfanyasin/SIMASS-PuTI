#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ ! -d vendor ]; then
    composer install --no-interaction --prefer-dist
fi

if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    php artisan key:generate --force
fi

php artisan migrate --force
php artisan storage:link --force || true

exec php-fpm
