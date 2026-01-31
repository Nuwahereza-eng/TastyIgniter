#!/bin/bash
set -e

echo "=== Starting TastyIgniter ==="

# Create storage directories if they don't exist
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Remove maintenance mode file if it exists
rm -f /var/www/html/storage/framework/down
echo "Maintenance mode file removed"

# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Default port to 80 if not set
PORT=${PORT:-80}
echo "PORT is: $PORT"

# Update Nginx to use Railway's PORT (conf.d location)
sed -i "s/listen 80/listen $PORT/g" /etc/nginx/conf.d/default.conf
sed -i "s/listen  80/listen $PORT/g" /etc/nginx/conf.d/default.conf
echo "Nginx configured to listen on port $PORT"

# Verify nginx config
echo "Testing nginx configuration..."
nginx -t 2>&1 || echo "Nginx config test warning"

# Show environment variables for debugging
echo "=== Environment Variables ==="
echo "DB_HOST: ${DB_HOST:-not set}"
echo "DB_PORT: ${DB_PORT:-not set}"
echo "DB_DATABASE: ${DB_DATABASE:-not set}"
echo "DB_USERNAME: ${DB_USERNAME:-not set}"
echo "APP_KEY is set: $([ -n "$APP_KEY" ] && echo 'yes' || echo 'no')"
echo "APP_URL: ${APP_URL:-not set}"

# Test database connection and disable maintenance mode
echo "=== Testing Database Connection ==="
php -r "
try {
    \$host = \$_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'localhost';
    \$port = \$_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '3306';
    \$db = \$_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?: 'tastyigniter';
    \$user = \$_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?: 'root';
    \$pass = \$_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: '';
    
    echo \"Connecting to: \$host:\$port/\$db as \$user\n\";
    
    \$pdo = new PDO(
        \"mysql:host=\$host;port=\$port;dbname=\$db\",
        \$user,
        \$pass
    );
    echo \"Database connection OK\n\";
    
    \$pdo->exec(\"UPDATE ti_settings SET value = '0' WHERE item = 'maintenance_mode'\");
    echo \"Maintenance mode disabled in database\n\";
} catch(Exception \$e) {
    echo 'Database error: '.\$e->getMessage().\"\n\";
}
" || true

# Clear Laravel caches
echo "=== Clearing Laravel caches ==="
cd /var/www/html
php artisan config:clear 2>&1 || echo "config:clear failed"
php artisan cache:clear 2>&1 || echo "cache:clear failed"  
php artisan view:clear 2>&1 || echo "view:clear failed"
php artisan route:clear 2>&1 || echo "route:clear failed"

# Ensure app is up (not in maintenance mode)
php artisan up 2>&1 || echo "artisan up failed"

# Generate config cache for production
echo "=== Optimizing for production ==="
php artisan config:cache 2>&1 || echo "config:cache failed (this is ok)"

echo "=== Starting Supervisor ==="
# Start Supervisor (which starts nginx and php-fpm)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
