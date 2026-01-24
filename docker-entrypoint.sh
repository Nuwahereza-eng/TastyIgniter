#!/bin/bash
set -e

# Create storage directories if they don't exist
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Update Nginx to use Railway's PORT
if [ ! -z "$PORT" ]; then
    sed -i "s/listen 80/listen $PORT/g" /etc/nginx/sites-available/default
fi

# Clear Laravel caches
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Start Supervisor (which starts nginx and php-fpm)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
