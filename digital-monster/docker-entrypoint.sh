#!/bin/bash

set -e

# Generate app key if not already set
if ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Run database migrations
echo "🏗️ Running migrations..."
php artisan migrate --force

# Link storage
echo "🔗 Linking storage directory..."
php artisan storage:link || true

# Seed only if needed
echo "🌱 Checking if seeding is needed..."
SEED_EXISTS=$(php artisan tinker --execute="echo App\Models\Equipment::where('type', 'DigiGarden')->exists();" | tr -d '\n')

if [ "$SEED_EXISTS" != "1" ]; then
    echo "🌾 Seeding initial data..."
    php artisan db:seed --force
else
    echo "✅ Seed data already exists. Skipping seeder..."
fi

# Cache config, routes, and views
echo "🧠 Caching Laravel configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Hand off to Apache (keeps container alive)
echo "🚀 Starting Apache..."
exec apache2-foreground
