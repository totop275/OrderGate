#!/bin/sh

composer install
php artisan migrate

exec "$@"