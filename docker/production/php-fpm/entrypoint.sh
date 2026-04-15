#!/bin/sh
set -e

echo "Starting Laravel setup..."

DB_HOST="${DB_HOST:-mysql}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-app}"
DB_USERNAME="${DB_USERNAME:-laravel}"
DB_PASSWORD="${DB_PASSWORD:-secret}"

echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT}..."
until php -r 'try { new PDO("mysql:host=" . getenv("DB_HOST") . ";port=" . getenv("DB_PORT") . ";dbname=" . getenv("DB_DATABASE"), getenv("DB_USERNAME"), getenv("DB_PASSWORD")); exit(0); } catch (Throwable $e) { fwrite(STDERR, $e->getMessage() . PHP_EOL); exit(1); }'; do
  echo "MySQL not ready yet. Retrying..."
  sleep 2
done

# Laravel setup
echo "Clearing config and cache..."
php artisan config:clear
php artisan cache:clear

# Generate APP_KEY if not provided 
if [ -z "$APP_KEY" ]; then
export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
echo "APP_KEY not provided; generated runtime key for this container start."
fi

echo "Running database migrations..."
php artisan migrate --force

echo "Caching config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Laravel setup completed!!!!!!!!!!!!!!!!!!!!!!!!!!!!"

# Start PHP-FPM
exec php-fpm