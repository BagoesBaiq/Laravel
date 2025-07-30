#!/bin/bash

composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
