# Use serversideup/php for a modern, production-ready Laravel image
FROM serversideup/php:8.4-fpm-nginx-v3

# Switch to root to install system dependencies
USER root

# Install system dependencies including FFmpeg for laravel-ffmpeg
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
COPY --chown=www-data:www-data docker/entrypoint.sh /etc/entrypoint.d/entrypoint.sh
RUN chmod +x /etc/entrypoint.d/entrypoint.sh

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
# Laravel 12 + Vite 7 + Tailwind 4
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Environment variables for production
ENV PHP_OPCACHE_ENABLE=1
ENV AUTORUN_ENABLED=1

# Expose port (Nginx default for serversideup is 8080)
EXPOSE 8080
