#!/bin/sh

echo "🚀 Starting Laravel setup..."

# Wait for DB (important for staging/prod)
echo "⏳ Waiting for database..."
sleep 10

# Laravel setup
php artisan config:clear
php artisan cache:clear

php artisan key:generate --force

php artisan migrate --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Laravel setup completed!"

# Start PHP-FPM
exec php-fpm