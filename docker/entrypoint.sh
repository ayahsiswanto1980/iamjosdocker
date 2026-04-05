#!/bin/sh

# Exit on error
set -e

echo "Running Entrypoint Script..."

# Ensure we are in the right directory
cd /var/www/html

# Run migrations if the database is ready
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

# Optimization for production
if [ "$APP_ENV" = "production" ]; then
    echo "Optimizing for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

echo "Entrypoint Script Completed!"
