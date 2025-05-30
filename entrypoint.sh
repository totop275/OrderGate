#!/bin/sh

composer install
npm install
npm run build
php artisan migrate

exec "$@"