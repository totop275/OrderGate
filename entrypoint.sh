#!/bin/sh

composer install
npm install
php artisan migrate

exec "$@"