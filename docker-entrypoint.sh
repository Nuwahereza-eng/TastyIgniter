#!/bin/bash
echo "=== Starting TastyIgniter ==="

# Railway can deploy without a configured APP_KEY. Generate one automatically to
# keep Laravel from failing during boot on first startup.
if [ -z "${APP_KEY:-}" ]; then
    export APP_KEY="base64:PtgNxse5mgoCQLqjNuuWq+EZpNO3akMWzEqLLXpJ3go="
    echo "APP_KEY was empty; using generated fallback key"
fi

# Normalise APP_URL. Image and asset URLs are built from it, so a value without
# a scheme (e.g. APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}) produces relative links like
# "my-app.up.railway.app/storage/..." that 404 in the browser. Exported because
# real environment variables take precedence over .env in Laravel.
if [ -z "${APP_URL:-}" ] && [ -n "${RAILWAY_PUBLIC_DOMAIN:-}" ]; then
    APP_URL="${RAILWAY_PUBLIC_DOMAIN}"
fi
if [ -n "${APP_URL:-}" ]; then
    case "${APP_URL}" in
        http://*|https://*) ;;
        *) APP_URL="https://${APP_URL}" ;;
    esac
    APP_URL="${APP_URL%/}"
    export APP_URL
fi
echo "APP_URL=${APP_URL:-<unset>}"

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
mkdir -p /var/www/html/storage/app/public/media/attachments/public
mkdir -p /var/www/html/storage/temp
mkdir -p /var/www/html/storage/igniter/{combiner,uploads,media}
mkdir -p /var/www/html/bootstrap/cache

# Remove maintenance mode file
rm -f /var/www/html/storage/framework/down

# Set permissions - make everything writable for the PHP-FPM user.
# This prevents Laravel from failing when it tries to append to storage/logs/laravel.log
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
find /var/www/html/storage -type d -exec chmod 775 {} \; 2>/dev/null || true
find /var/www/html/storage -type f -exec chmod 664 {} \; 2>/dev/null || true
find /var/www/html/storage/app/public/media -type d -exec chmod 775 {} \; 2>/dev/null || true
find /var/www/html/bootstrap/cache -type d -exec chmod 775 {} \; 2>/dev/null || true
find /var/www/html/bootstrap/cache -type f -exec chmod 664 {} \; 2>/dev/null || true

touch /var/www/html/storage/logs/laravel.log 2>/dev/null || true
chown www-data:www-data /var/www/html/storage/logs/laravel.log 2>/dev/null || true
chmod 664 /var/www/html/storage/logs/laravel.log 2>/dev/null || true

# Create storage symlink for public access to uploaded files
echo "Creating storage symlink..."
rm -rf /var/www/html/public/storage 2>/dev/null || true
ln -sf /var/www/html/storage/app/public /var/www/html/public/storage
echo "Storage symlink created"

# Clear cache files (quick operation)
cd /var/www/html
rm -rf /var/www/html/storage/framework/cache/data/* 2>/dev/null || true
rm -rf /var/www/html/storage/framework/views/*.php 2>/dev/null || true
rm -rf /var/www/html/storage/temp/* 2>/dev/null || true
rm -rf /var/www/html/storage/igniter/combiner/* 2>/dev/null || true
rm -rf /var/www/html/storage/igniter/cache/* 2>/dev/null || true
echo "Cleared storage caches"

# Configure Nginx port BEFORE starting services
PORT=${PORT:-9000}
echo "PORT is: $PORT"
# Remove any stock/default server that may duplicate the app config when the
# image is reused across restarts. There must be exactly one default listener.
rm -f /etc/nginx/sites-enabled/default
# Match any existing port so this is idempotent across container restarts,
# where the same filesystem (and an already-rewritten config) is reused.
sed -i -E "s/listen [0-9]+ default_server;/listen ${PORT} default_server;/" /etc/nginx/conf.d/default.conf 2>/dev/null || true

# Fix the config before start so a duplicate default server never triggers a crash.
nginx -t 2>/dev/null || true

# Ensure there are no stale nginx/php-fpm processes left from a previous attempt
# or a restarted container that reused the same runtime namespace.
if [ -f /run/nginx.pid ] || pgrep -x nginx >/dev/null 2>&1; then
    nginx -s quit 2>/dev/null || pkill -x nginx || true
    rm -f /run/nginx.pid
fi
pgrep -x php-fpm >/dev/null 2>&1 && pkill -x php-fpm || true

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

# Install TastyIgniter's schema on first deploy.
# `php artisan migrate` alone does not create the installed site data; it is
# created by `igniter:install`. A table existing without rows is not a valid
# installed state, so we check for real settings/theme data before skipping.
echo "=== Checking TastyIgniter installation ==="
SCHEMA_STATE=$(php artisan tinker --execute="
\$schema = \Illuminate\Support\Facades\Schema::class;
\$db = \Illuminate\Support\Facades\DB::class;
\$hasSettings = method_exists(\$schema, 'hasTable') && \$schema::hasTable('settings');
\$hasThemes = method_exists(\$schema, 'hasTable') && \$schema::hasTable('themes');
\$settingsCount = \$hasSettings ? \$db::table('settings')->count() : 0;
\$themesCount = \$hasThemes ? \$db::table('themes')->count() : 0;
echo (\$hasSettings && \$settingsCount > 0) || (\$hasThemes && \$themesCount > 0) ? 'installed' : 'missing';" 2>&1 || true)
echo "Schema check: ${SCHEMA_STATE}"
if echo "${SCHEMA_STATE}" | grep -q "missing"; then
    echo "=== Database appears empty, running igniter:install ==="
    php artisan igniter:install --no-interaction 2>&1 || echo "igniter install failed"

    # Optionally create the initial super admin. Skipped unless both
    # ADMIN_EMAIL and ADMIN_PASSWORD are set. Values are read from the
    # environment inside PHP so they are never interpolated into the command.
    if [ -n "${ADMIN_EMAIL:-}" ] && [ -n "${ADMIN_PASSWORD:-}" ]; then
        echo "=== Creating admin account for ${ADMIN_EMAIL} ==="
        php artisan tinker --execute="
\$email = getenv('ADMIN_EMAIL');
\$user = \Igniter\User\Models\User::where('email', \$email)->first();
if (!\$user) {
    \$user = new \Igniter\User\Models\User();
    \$user->forceFill([
        'name' => getenv('ADMIN_NAME') ?: 'Administrator',
        'email' => \$email,
        'username' => getenv('ADMIN_USERNAME') ?: 'admin',
        'password' => getenv('ADMIN_PASSWORD'),
        'super_user' => true,
        'is_activated' => true,
    ])->save();
    echo 'Admin account created';
} else {
    echo 'Admin account already exists';
}
" 2>&1 || echo "admin setup done"
    else
        echo "ADMIN_EMAIL/ADMIN_PASSWORD not set; skipping admin account creation"
    fi
else
    echo "TastyIgniter schema already present; skipping install"
fi

# Run migrations (keeps later upgrades applying new migrations)
php artisan migrate --force 2>&1 || echo "migrate done"

# Restore the homepage slider media on fresh or reset databases.
php artisan db:seed --class="Database\\Seeders\\FrontendContentSeeder" --force 2>&1 || echo "frontend content seed done"

# Restore the Ugandan dishes (and their images) if they are missing.
php artisan db:seed --class="Database\\Seeders\\MenuContentSeeder" --force 2>&1 || echo "menu content seed done"

# Theme setup
echo "=== Syncing themes ==="
php artisan tinker --execute="\Igniter\Main\Models\Theme::syncAll();" 2>&1 || echo "theme sync done"

# Activate demo theme
echo "=== Activating demo theme ==="
php artisan tinker --execute="
\$theme = \Igniter\Main\Models\Theme::where('code', 'demo')->first();
\$hasIsActiveColumn = \Illuminate\Support\Facades\Schema::hasColumn('themes', 'is_active');
if (\$theme) {
    if (\$hasIsActiveColumn) {
        \$theme->update(['is_active' => true]);
        \Igniter\Main\Models\Theme::where('code', '!=', 'demo')->update(['is_active' => false]);
        echo 'Demo theme activated';
    } else {
        echo 'Theme table missing is_active column; skipping activation flag update';
    }
} else {
    echo 'Demo theme not found, creating...';
    \$themeData = [
        'name' => 'UgaEats Demo Theme',
        'code' => 'demo',
        'description' => 'Uganda customizations theme',
    ];
    if (\$hasIsActiveColumn) {
        \$themeData['is_active'] = true;
    }
    \Igniter\Main\Models\Theme::create(\$themeData);
    if (\$hasIsActiveColumn) {
        \Igniter\Main\Models\Theme::where('code', '!=', 'demo')->update(['is_active' => false]);
    }
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

# Re-apply storage ownership. The artisan commands above (migrate, seeders,
# tinker) run as root and create root-owned files and folders, e.g. media
# attachments, thumbnails and storage/temp/attachments. PHP-FPM runs as
# www-data and must be able to write thumbnails there at request time.
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
find /var/www/html/storage -type d -exec chmod 775 {} \; 2>/dev/null || true
find /var/www/html/storage -type f -exec chmod 664 {} \; 2>/dev/null || true

# Laravel scheduler (expires unapproved Marz Pay prompts every minute).
# Runs as www-data so files it writes stay writable by PHP-FPM.
su -s /bin/sh www-data -c "cd /var/www/html && php artisan schedule:work >> storage/logs/scheduler.log 2>&1" &
echo "Scheduler started"

echo "=== Initialization complete ==="

# Keep the container running by tailing logs
# This replaces supervisord since we started services manually
tail -f /var/log/nginx/access.log /var/log/nginx/error.log 2>/dev/null || while true; do sleep 3600; done
