# Stage 1: Build frontend assets
FROM node:20 AS node-build
WORKDIR /app

# Copy package configurations
COPY package*.json ./

# Remove lock-file, clean cache, install dependencies, and force native rollup binary
RUN rm -f package-lock.json node_modules && \
    npm cache clean --force && \
    npm install && \
    npm install -D @rollup/rollup-linux-x64-gnu

# Copy source files and build
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

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

# Setup entrypoint and Nginx optimization
# The image includes /etc/nginx/conf.d/*.conf into the main http block
COPY --chown=root:root docker/nginx/disable-aio.conf /etc/nginx/conf.d/disable-aio.conf
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
ENV NGINX_WORKER_PROCESSES=1
# Default port — entrypoint.sh will override NGINX_HTTP_PORT using Railway's $PORT
ENV PORT=8080

# Railway reads EXPOSE to know what port the app listens on
EXPOSE 8080

