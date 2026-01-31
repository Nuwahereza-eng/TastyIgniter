#!/bin/bash
# Don't use set -e - we want to continue even if some commands fail

echo "=== Starting TastyIgniter ==="

# Create storage directories
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

# Remove maintenance mode file
rm -f /var/www/html/storage/framework/down

# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Configure Nginx port
PORT=${PORT:-80}
echo "PORT is: $PORT"
sed -i "s/listen 80/listen $PORT/g" /etc/nginx/conf.d/default.conf 2>/dev/null || true

# Clear ALL cached files and regenerate at runtime with correct env
echo "=== Clearing and regenerating Laravel cache ==="
cd /var/www/html
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/packages.php

# Regenerate package manifest with runtime environment
php artisan package:discover --ansi 2>&1 || echo "package:discover warning"

# Clear caches
php artisan config:clear 2>&1 || echo "config:clear warning"
php artisan cache:clear 2>&1 || echo "cache:clear warning"
php artisan view:clear 2>&1 || echo "view:clear warning"

echo "=== Starting Supervisor ==="
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
