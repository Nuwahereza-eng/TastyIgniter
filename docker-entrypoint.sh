#!/bin/bash
set -e

# Create storage directories if they don't exist
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Update Apache to use Railway's PORT
if [ ! -z "$PORT" ]; then
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/000-default.conf
    sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/000-default.conf
fi

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force || true
fi

# Clear and cache config
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# Start Apache
exec apache2-foreground
