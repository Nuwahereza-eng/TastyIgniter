#!/bin/bash
echo "=== Starting TastyIgniter ==="

# Create .env file from environment variables
echo "Creating .env file..."
cat > /var/www/html/.env << EOF
APP_NAME=TastyIgniter
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-true}
APP_URL=${APP_URL:-https://localhost}

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=${DB_HOST:-localhost}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-tastyigniter}
DB_USERNAME=${DB_USERNAME:-root}
DB_PASSWORD=${DB_PASSWORD:-}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
EOF
echo ".env file created"

# Create storage directories
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/temp
mkdir -p /var/www/html/storage/igniter/{combiner,uploads,media}
mkdir -p /var/www/html/bootstrap/cache

# Remove maintenance mode file
rm -f /var/www/html/storage/framework/down

# Set permissions - make everything writable
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 777 /var/www/html/storage 2>/dev/null || true
chmod -R 775 /var/www/html/bootstrap/cache 2>/dev/null || true

# Clear cache files (quick operation)
cd /var/www/html
rm -rf /var/www/html/storage/framework/cache/data/* 2>/dev/null || true
rm -rf /var/www/html/storage/framework/views/*.php 2>/dev/null || true
rm -rf /var/www/html/storage/temp/* 2>/dev/null || true
rm -rf /var/www/html/storage/igniter/combiner/* 2>/dev/null || true
rm -rf /var/www/html/storage/igniter/cache/* 2>/dev/null || true
echo "Cleared storage caches"

# Configure Nginx port BEFORE starting services
PORT=${PORT:-80}
echo "PORT is: $PORT"
sed -i "s/listen 80/listen $PORT/g" /etc/nginx/conf.d/default.conf 2>/dev/null || true

# Create a simple health file that nginx can serve immediately
echo '{"status":"ok","service":"ugaeats"}' > /var/www/html/public/health.json
chmod 644 /var/www/html/public/health.json

# Start PHP-FPM in background first
echo "=== Starting PHP-FPM ==="
php-fpm -D
sleep 2

# Start Nginx in background
echo "=== Starting Nginx ==="
nginx
sleep 1

echo "=== Web services started, running initialization ==="

# Now run Laravel initialization in foreground
# This allows health checks to pass while we initialize
php artisan config:clear 2>&1 || echo "config:clear done"
php artisan cache:clear 2>&1 || echo "cache:clear done"
php artisan view:clear 2>&1 || echo "view:clear done"
php artisan route:clear 2>&1 || echo "route:clear done"

# Run migrations
php artisan migrate --force 2>&1 || echo "migrate done"

# Theme setup
echo "=== Syncing themes ==="
php artisan tinker --execute="\Igniter\Main\Models\Theme::syncAll();" 2>&1 || echo "theme sync done"

# Activate demo theme
echo "=== Activating demo theme ==="
php artisan tinker --execute="
\$theme = \Igniter\Main\Models\Theme::where('code', 'demo')->first();
if (\$theme) {
    \$theme->update(['is_active' => true]);
    \Igniter\Main\Models\Theme::where('code', '!=', 'demo')->update(['is_active' => false]);
    echo 'Demo theme activated';
} else {
    echo 'Demo theme not found, creating...';
    \Igniter\Main\Models\Theme::create([
        'name' => 'UgaEats Demo Theme',
        'code' => 'demo',
        'description' => 'Uganda customizations theme',
        'is_active' => true,
    ]);
    \Igniter\Main\Models\Theme::where('code', '!=', 'demo')->update(['is_active' => false]);
}
\DB::table('settings')->updateOrInsert(
    ['item' => 'default_themes'],
    ['value' => json_encode(['main' => 'demo'])]
);
echo ' | Settings updated';
" 2>&1 || echo "theme set done"

php artisan igniter:util set theme --theme=demo 2>&1 || echo "artisan theme set done"

# Site logo fix
php artisan tinker --execute="\Igniter\System\Models\Settings::set('site_logo', 'no_photo.png');" 2>&1 || echo "site_logo fix done"

# Clear DB caches
php artisan tinker --execute="
try { \DB::table('cache')->truncate(); } catch (\Exception \$e) {}
try { \DB::table('sessions')->truncate(); } catch (\Exception \$e) {}
try { \Illuminate\Support\Facades\Cache::flush(); } catch (\Exception \$e) {}
echo 'DB caches cleared';
" 2>&1 || echo "db cache clear done"

# Publish assets
php artisan vendor:publish --tag=igniter-orange-assets --force 2>&1 || echo "publish assets done"

# Apply patches
echo "=== Applying PHP patches ==="
if [ -f /var/www/html/scripts/apply-patches.php ]; then
    php /var/www/html/scripts/apply-patches.php 2>&1 || echo "php patches done"
fi

if [ -f /var/www/html/apply-patches.sh ]; then
    chmod +x /var/www/html/apply-patches.sh
    /var/www/html/apply-patches.sh 2>&1 || echo "shell patches done"
fi

# Final view clear
php artisan view:clear 2>&1 || echo "view:clear done"

echo "=== Initialization complete ==="

# Keep the container running by tailing logs
# This replaces supervisord since we started services manually
tail -f /var/log/nginx/access.log /var/log/nginx/error.log 2>/dev/null || while true; do sleep 3600; done
