FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    nginx \
    supervisor \
    nodejs \
    npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql gd bcmath zip mbstring xml pcntl

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies (skip scripts to avoid env var issues during build)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Clear bootstrap cache (removes dev dependency providers)
RUN rm -f bootstrap/cache/*.php

# Install Node dependencies and build assets
RUN npm ci && npm run build

# Copy Nginx config
COPY docker/nginx/default.conf /etc/nginx/sites-available/default

# Copy Supervisor config
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy and prepare entrypoint
COPY scripts/fly-entrypoint.sh /usr/local/bin/fly-entrypoint
RUN chmod +x /usr/local/bin/fly-entrypoint

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create required directories
RUN mkdir -p /var/log/supervisor \
    && mkdir -p /var/www/html/storage/framework/cache/data \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views

# Expose port 8080
EXPOSE 8080

# Start via entrypoint
ENTRYPOINT ["/usr/local/bin/fly-entrypoint"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
