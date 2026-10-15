#!/bin/bash
# Copy the local TastyIgniter database to the Railway MySQL service.
#
# Usage:
#   scripts/push-db-to-railway.sh 'mysql://root:PASSWORD@HOST.proxy.rlwy.net:PORT/railway'
#
# Take the URL from Railway -> MySQL service -> Variables -> MYSQL_PUBLIC_URL.
# The production database is backed up to backups/ before it is replaced.
# Sessions and cache rows are not copied.
set -euo pipefail

URL="${1:-${MYSQL_PUBLIC_URL:-}}"
if [ -z "$URL" ]; then
    echo "Pass the Railway MYSQL_PUBLIC_URL as the first argument." >&2
    exit 1
fi

# Parse mysql://user:pass@host:port/db
rest="${URL#mysql://}"
creds="${rest%@*}"
hostpart="${rest##*@}"
R_USER="${creds%%:*}"
R_PASS="${creds#*:}"
R_HOST="${hostpart%%:*}"
portdb="${hostpart#*:}"
R_PORT="${portdb%%/*}"
R_DB="${portdb#*/}"

cd "$(dirname "$0")/.."
set -a
# shellcheck disable=SC1090
. <(grep -E '^DB_(HOST|PORT|DATABASE|USERNAME|PASSWORD|PREFIX)=' .env)
set +a
PREFIX="${DB_PREFIX:-ti_}"

mkdir -p backups
STAMP=$(date +%Y%m%d-%H%M%S)
LOCAL_DUMP="backups/local-$STAMP.sql"
PROD_BACKUP="backups/railway-before-import-$STAMP.sql"

echo "Checking connection to Railway ($R_HOST:$R_PORT/$R_DB)..."
MYSQL_PWD="$R_PASS" mysql -h "$R_HOST" -P "$R_PORT" -u "$R_USER" "$R_DB" -e "SELECT 1" >/dev/null

echo "Backing up production to $PROD_BACKUP..."
MYSQL_PWD="$R_PASS" mysqldump -h "$R_HOST" -P "$R_PORT" -u "$R_USER" \
    --single-transaction --no-tablespaces --default-character-set=utf8mb4 \
    "$R_DB" > "$PROD_BACKUP"

echo "Dumping local database $DB_DATABASE to $LOCAL_DUMP..."
MYSQL_PWD="$DB_PASSWORD" mysqldump -h "$DB_HOST" -P "${DB_PORT:-3306}" -u "$DB_USERNAME" \
    --single-transaction --no-tablespaces --add-drop-table --default-character-set=utf8mb4 \
    --ignore-table="$DB_DATABASE.${PREFIX}sessions" \
    --ignore-table="$DB_DATABASE.${PREFIX}cache" \
    --ignore-table="$DB_DATABASE.${PREFIX}cache_locks" \
    "$DB_DATABASE" > "$LOCAL_DUMP"

echo "Importing into Railway..."
MYSQL_PWD="$R_PASS" mysql -h "$R_HOST" -P "$R_PORT" -u "$R_USER" \
    --default-character-set=utf8mb4 "$R_DB" < "$LOCAL_DUMP"

echo "Row counts on Railway after import:"
for t in customers admin_users menus locations orders media_attachments; do
    n=$(MYSQL_PWD="$R_PASS" mysql -N -h "$R_HOST" -P "$R_PORT" -u "$R_USER" "$R_DB" \
        -e "SELECT COUNT(*) FROM ${PREFIX}${t}" 2>/dev/null || echo "?")
    printf '  %-18s %s\n' "$t" "$n"
done

echo "Done. Redeploy the Railway service so the slider seeder restores banner files."
echo "To roll back: mysql ... $R_DB < $PROD_BACKUP"
