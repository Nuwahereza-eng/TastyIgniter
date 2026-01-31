#!/bin/bash
set -e

echo "=== Starting TastyIgniter ==="

# Create storage directories if they don't exist
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Default port to 80 if not set
PORT=${PORT:-80}
echo "PORT is: $PORT"

# Update Nginx to use Railway's PORT
sed -i "s/listen 80/listen $PORT/g" /etc/nginx/sites-available/default
sed -i "s/listen  80/listen $PORT/g" /etc/nginx/sites-available/default
echo "Nginx configured to listen on port $PORT"

# Verify nginx config
echo "Testing nginx configuration..."
nginx -t || echo "Nginx config test warning"

# Test database connection
echo "Testing database connection..."
echo "DB_HOST: ${DB_HOST:-not set}"
echo "DB_PORT: ${DB_PORT:-not set}"
echo "DB_DATABASE: ${DB_DATABASE:-not set}"
php -r "try { new PDO('mysql:host='.\$_ENV['DB_HOST'].';port='.\$_ENV['DB_PORT'].';dbname='.\$_ENV['DB_DATABASE'], \$_ENV['DB_USERNAME'], \$_ENV['DB_PASSWORD']); echo 'Database connection OK\n'; } catch(Exception \$e) { echo 'Database error: '.\$e->getMessage().'\n'; }" || true

# Clear Laravel caches
echo "Clearing Laravel caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

echo "Starting Supervisor..."
# Start Supervisor (which starts nginx and php-fpm)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
