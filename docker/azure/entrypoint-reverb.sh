#!/bin/bash
set -e

echo "Starting Reverb sidecar container..."

# Create required directories
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views

# Set permissions
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Run package discovery
echo "Running package discovery..."
php /var/www/html/artisan package:discover --ansi

# Cache configuration (same as main app)
echo "Caching configuration..."
php /var/www/html/artisan config:cache

echo "Starting Laravel Reverb WebSocket server..."
exec "$@"
