#!/bin/sh

# Exit on error
set -e

echo "Running Entrypoint Script..."

# Ensure we are in the right directory
cd /var/www/html

# Run migrations if the database is ready
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations..."
    # If running as root, we should run this as www-data to avoid permission issues
    if [ "$(id -u)" = "0" ]; then
        su www-data -s /bin/sh -c "php artisan migrate --force"
    else
        php artisan migrate --force
    fi
fi

# Optimization for production
if [ "$APP_ENV" = "production" ]; then
    echo "Optimizing for production..."
    # Always try to run these as www-data if we are root
    if [ "$(id -u)" = "0" ]; then
        su www-data -s /bin/sh -c "php artisan config:cache"
        su www-data -s /bin/sh -c "php artisan view:cache"
    else
        php artisan config:cache
        php artisan view:cache
    fi
fi

echo "Entrypoint Script Completed!"
