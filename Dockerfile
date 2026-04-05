# Stage 1: Build frontend assets
FROM node:20 AS node-build
WORKDIR /app
COPY package*.json ./
# Use 'npm ci' for reproducible, deterministic builds (uses package-lock.json)
# Then force-install the correct Linux/x64 Rollup and LightningCSS binaries
RUN npm ci && \
    npm install -D @rollup/rollup-linux-x64-gnu lightningcss-linux-x64-gnu --no-save
COPY . .
RUN npm run build

# Stage 2: FrankenPHP Production Image
FROM dunglas/frankenphp:1-php8.4

# Copy Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install system dependencies (MINIMAL VERSION)
# Adding --no-install-recommends is CRITICAL here to avoid 800MB+ of extra files
RUN apt-get update && apt-get install -y --no-install-recommends \
    ffmpeg \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    libicu-dev \
    unzip \
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

WORKDIR /app

# Copy dependency files first to leverage Docker cache
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-autoloader

# Copy the rest of the application code
COPY --chown=www-data:www-data . .

# Copy compiled frontend assets from node-build stage
COPY --from=node-build --chown=www-data:www-data /app/public/build ./public/build

# Finish composer install (autoloader + scripts)
RUN composer install --no-dev --optimize-autoloader

# Copy entrypoint script
COPY --chown=root:root docker/entrypoint.sh /docker-entrypoint.d/entrypoint.sh
RUN chmod +x /docker-entrypoint.d/entrypoint.sh

# Set permissions for Laravel
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Production environment
ENV PHP_OPCACHE_ENABLE=1
ENV APP_ENV=production

EXPOSE 8080
