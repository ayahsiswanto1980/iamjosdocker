# Use the correct serversideup/php image for PHP 8.4 with NGINX
# This image runs as non-root (www-data) on port 8080 by default.
FROM serversideup/php:8.4-fpm-nginx

# Switch to root to install system dependencies
USER root

# Install system dependencies including FFmpeg for laravel-ffmpeg
# The base image is Debian-based
RUN apt-get update && apt-get install -y \
    ffmpeg \
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY --chown=www-data:www-data . .

# Copy and setup entrypoint script for auto-run on startup
# The image runs scripts in /etc/entrypoint.d/ as root before switching to www-data
COPY --chown=root:root docker/entrypoint.sh /etc/entrypoint.d/entrypoint.sh
RUN chmod +x /etc/entrypoint.d/entrypoint.sh

# Switch back to the non-root user for Composer and NPM
USER www-data

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
# Laravel 12 + Vite 7 + Tailwind 4
RUN npm install && npm run build

# Ensure storage and bootstrap/cache permissions are correct
# (Already handled by COPY --chown but good for safety)
RUN chmod -R 775 storage bootstrap/cache

# Environment variables for production
ENV PHP_OPCACHE_ENABLE=1
ENV AUTORUN_ENABLED=1

# Railway automatically detects the exposed port or uses PORT variable.
# Serversideup listens on 8080 by default.
EXPOSE 8080
