#!/usr/bin/env bash
set -euo pipefail

# Ensure storage directories exist
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Create SQLite database file if it doesn't exist
touch database/database.sqlite

# Generate app key if not set
php artisan key:generate --no-interaction --force

# Run migrations
php artisan migrate --force --no-interaction

# Create storage symlink
php artisan storage:link --force 2>/dev/null || true

# Cache config/routes/views for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP's built-in server on the PORT provided by Railway
PORT="${PORT:-8000}"
echo "Starting Laravel on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port="$PORT"
