#!/bin/bash
set -e

# Ensure SQLite database exists and is writable
touch database/database.sqlite
chmod 666 database/database.sqlite

# Ensure storage symlink exists
php artisan storage:link || true

# Run database migrations and seed default CrackNCode data
php artisan migrate --force --seed

# Clear and optimize Laravel cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Determine port from Render environment ($PORT), default to 8000
SERVER_PORT="${PORT:-8000}"
echo "Serving CrackNCode on port $SERVER_PORT..."

exec php artisan serve --host=0.0.0.0 --port="$SERVER_PORT"
