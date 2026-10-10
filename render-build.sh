#!/usr/bin/env bash
# Exit immediately if a command exits with a non-zero status
set -o errexit

echo "--- Installing Composer dependencies ---"
composer install --no-dev --optimize-autoloader

echo "--- Ensuring SQLite database file exists ---"
touch database/database.sqlite

echo "--- Running Database Migrations & Seeders ---"
php artisan migrate --force --seed

echo "--- Linking storage ---"
php artisan storage:link || true

echo "--- Caching Laravel Configurations ---"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "--- Build completed successfully! ---"
