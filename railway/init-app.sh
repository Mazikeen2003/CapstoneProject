#!/bin/bash
php artisan config:clear
php artisan cache:clear
# npm run build          # ← builds Vite frontend
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link