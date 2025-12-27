#!/bin/bash
set -e

echo "Starting Azure App Service entrypoint..."

# Create required directories
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/log/supervisor

# Set permissions
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Create storage symlink
php /var/www/html/artisan storage:link --force 2>/dev/null || true

# Run composer scripts that were skipped during build
echo "Running package discovery..."
php /var/www/html/artisan package:discover --ansi

# Cache configuration
echo "Caching configuration..."
php /var/www/html/artisan config:cache
php /var/www/html/artisan route:cache
php /var/www/html/artisan view:cache

# Run migrations
echo "Running migrations..."
php /var/www/html/artisan migrate --force

echo "Starting supervisor..."
exec "$@"
