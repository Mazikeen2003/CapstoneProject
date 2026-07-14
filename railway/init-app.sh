#!/bin/bash
php artisan config:clear
php artisan cache:clear
npm run build
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link