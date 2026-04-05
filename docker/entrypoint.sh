#!/bin/sh

# Exit on error
set -e

echo "Running Entrypoint Script..."

# ---------------------------------------------------------------
# Railway assigns a dynamic PORT env var. The serversideup/php
# image supports NGINX_HTTP_PORT to control which port Nginx binds.
# We must export it BEFORE the main process starts.
# Default to 8080 if PORT is not set (local dev).
# ---------------------------------------------------------------
export NGINX_HTTP_PORT="${PORT:-8080}"
echo "Nginx will listen on port: $NGINX_HTTP_PORT"

# Ensure we are in the right directory
cd /var/www/html

# Run migrations if the database is ready
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations..."
    if [ "$(id -u)" = "0" ]; then
        su www-data -s /bin/sh -c "php artisan migrate --force"
    else
        php artisan migrate --force
    fi
fi

# Optimization for production
if [ "$APP_ENV" = "production" ]; then
    echo "Optimizing for production..."
    if [ "$(id -u)" = "0" ]; then
        su www-data -s /bin/sh -c "php artisan config:cache"
        su www-data -s /bin/sh -c "php artisan view:cache"
    else
        php artisan config:cache
        php artisan view:cache
    fi
fi

echo "Entrypoint Script Completed!"
