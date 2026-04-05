# Use serversideup/php for a modern, production-ready Laravel image
FROM serversideup/php:8.4-fpm-nginx

# Switch to root to install system dependencies
USER root

# Install system dependencies including FFmpeg and PHP extensions helper
RUN apt-get update && apt-get install -y \
    ffmpeg \
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Use install-php-extensions to handle all PHP extensions correctly
# especially GD (with JPEG/WebP support), Zip, Intl, etc.
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions gd zip intl bcmath pcntl pdo_pgsql opcache

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY --chown=www-data:www-data . .

# Copy and setup entrypoint script for auto-run on startup
COPY --chown=root:root docker/entrypoint.sh /etc/entrypoint.d/entrypoint.sh
RUN chmod +x /etc/entrypoint.d/entrypoint.sh

# Switch back to the non-root user for Composer and NPM
USER www-data

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install && npm run build

# Ensure storage and bootstrap/cache permissions are correct
RUN chmod -R 775 storage bootstrap/cache

# Environment variables for production
ENV PHP_OPCACHE_ENABLE=1
ENV AUTORUN_ENABLED=1

# Railway default port
EXPOSE 8080
