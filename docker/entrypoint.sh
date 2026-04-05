#!/bin/sh

set -e

echo "Running Entrypoint Script..."

cd /app

# Run migrations and seeders
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force
    echo "Running seeders..."
    php artisan db:seed --force
fi

# Production optimizations
if [ "$APP_ENV" = "production" ]; then
    echo "Optimizing for production..."
    php artisan config:cache
    php artisan view:cache
fi

echo "Entrypoint Script Completed!"
