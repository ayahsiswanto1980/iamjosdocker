# Stage 1: Build frontend assets
FROM node:20 AS node-build
WORKDIR /app
COPY package*.json ./
RUN rm -f package-lock.json && \
    npm cache clean --force && \
    npm install && \
    npm install -D @rollup/rollup-linux-x64-gnu
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

# Stage 2: FrankenPHP Production Image
# FrankenPHP = PHP 8.4 + Caddy (replaces Nginx+FPM). No AIO issues.
# Officially recommended by Laravel for production containers.
FROM dunglas/frankenphp:1-php8.4

# Copy Composer from official image (not included in FrankenPHP base)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install system dependencies
RUN apt-get update && apt-get install -y \
    ffmpeg \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    libicu-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions
RUN install-php-extensions \
    gd \
    zip \
    intl \
    bcmath \
    pcntl \
    pdo_pgsql \
    opcache

# Set working directory
WORKDIR /app

# Copy application code
COPY --chown=www-data:www-data . .

# Copy compiled frontend assets from node-build stage
COPY --from=node-build --chown=www-data:www-data /app/public/build ./public/build

# Copy entrypoint script
COPY --chown=root:root docker/entrypoint.sh /docker-entrypoint.d/entrypoint.sh
RUN chmod +x /docker-entrypoint.d/entrypoint.sh

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Production environment
ENV PHP_OPCACHE_ENABLE=1
ENV APP_ENV=production
ENV SERVER_NAME=":${PORT:-8080}"

EXPOSE 8080
