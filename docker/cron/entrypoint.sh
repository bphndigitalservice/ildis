#!/bin/sh -e
# Wait for database before starting crond

MAX_RETRIES=30
RETRY_COUNT=0

echo "[cron-entrypoint] Waiting for database..."

while [ $RETRY_COUNT -lt $MAX_RETRIES ]; do
    if php /var/www/yii migrate/history 1 --interactive=0 >/dev/null 2>&1; then
        echo "[cron-entrypoint] Database ready."
        break
    fi
    RETRY_COUNT=$((RETRY_COUNT + 1))
    echo "[cron-entrypoint] Database not ready, retrying... ($RETRY_COUNT/$MAX_RETRIES)"
    sleep 2
done

if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
    echo "[cron-entrypoint] WARNING: Database not ready. Starting cron anyway - jobs may fail until DB is available."
fi

exec crond -f -s