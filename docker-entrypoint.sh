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

# IMPORTANT: Clear ALL cache data FIRST before any Laravel commands
# This prevents stale cache entries from referencing non-existent temp files
cd /var/www/html
rm -rf /var/www/html/storage/framework/cache/data/* 2>/dev/null || true
rm -rf /var/www/html/storage/framework/views/*.php 2>/dev/null || true
rm -rf /var/www/html/storage/temp/* 2>/dev/null || true
rm -rf /var/www/html/storage/igniter/combiner/* 2>/dev/null || true
rm -rf /var/www/html/storage/igniter/cache/* 2>/dev/null || true
echo "Cleared storage caches"

# Clear Laravel caches to use fresh .env values
php artisan config:clear 2>&1 || echo "config:clear done"
php artisan cache:clear 2>&1 || echo "cache:clear done"
php artisan view:clear 2>&1 || echo "view:clear done"
php artisan route:clear 2>&1 || echo "route:clear done"

# Run any pending migrations
php artisan migrate --force 2>&1 || echo "migrate done"

# Sync all themes from filesystem to database (required for demo theme to be recognized)
php artisan tinker --execute="\Igniter\Main\Models\Theme::syncAll();" 2>&1 || echo "theme sync done"

# Set the theme to demo (correct syntax with --theme option)
php artisan igniter:util set theme --theme=demo 2>&1 || echo "theme set done"

# CRITICAL FIX: Set site_logo to 'no_photo.png' to prevent media_thumb() errors
# This avoids the "File does not exist" error from Glide thumbnail generation
php artisan tinker --execute="\Igniter\System\Models\Settings::set('site_logo', 'no_photo.png');" 2>&1 || echo "site_logo fix done"

# ULTRA NUCLEAR: Clear ALL cached media references from database
# This clears the pagic_pages cache and any other cached image paths
php artisan tinker --execute="
try {
    \DB::table('cache')->truncate();
} catch (\Exception \$e) {}
try {
    \DB::table('sessions')->truncate();
} catch (\Exception \$e) {}
try {
    // Clear any cached template data that might reference old temp files
    \Illuminate\Support\Facades\Cache::flush();
} catch (\Exception \$e) {}
echo 'DB caches cleared';
" 2>&1 || echo "db cache clear done"

# Pre-compile assets to avoid combiner issues
php artisan vendor:publish --tag=igniter-orange-assets --force 2>&1 || echo "publish assets done"

echo "Startup complete"

# Configure Nginx port
PORT=${PORT:-80}
echo "PORT is: $PORT"
sed -i "s/listen 80/listen $PORT/g" /etc/nginx/conf.d/default.conf 2>/dev/null || true

echo "=== Starting Supervisor ==="
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
