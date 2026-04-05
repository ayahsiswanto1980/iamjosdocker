# Stage 1: Build frontend assets (Debian-based for stable binary support)
FROM node:20 AS node-build
WORKDIR /app
COPY package*.json vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm install && npm run build

# Stage 2: PHP Production Image
FROM serversideup/php:8.4-fpm-nginx

# Switch to root for system dependencies & extensions
USER root

# Install system dependencies
RUN apt-get update && apt-get install -y \
    ffmpeg \
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install required PHP extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions gd zip intl bcmath pcntl pdo_pgsql opcache

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY --chown=www-data:www-data . .

# Copy compiled assets from node-build stage
COPY --from=node-build --chown=www-data:www-data /app/public/build ./public/build

# Setup entrypoint script
COPY --chown=root:root docker/entrypoint.sh /etc/entrypoint.d/entrypoint.sh
RUN chmod +x /etc/entrypoint.d/entrypoint.sh

# Switch to non-root user for Composer
USER www-data

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Final permission check
RUN chmod -R 775 storage bootstrap/cache

# Production environment
ENV PHP_OPCACHE_ENABLE=1
ENV AUTORUN_ENABLED=1

EXPOSE 8080
