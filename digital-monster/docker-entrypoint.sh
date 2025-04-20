#!/bin/bash

# Exit immediately on error
set -e

if ! grep -q "APP_KEY" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

echo "🏗️ Running migrations..."
php artisan migrate --force

echo "🔗 Linking storage directory..."
php artisan storage:link

echo "📦 Installing npm dependencies..."
npm install

echo "🛠️ Building frontend assets..."
npm run build

echo "🌱 Checking if seeding is needed..."
SEED_EXISTS=$(php artisan tinker --execute="echo App\Models\Equipment::where('type', 'DigiGarden')->exists();" | tr -d '\n')

if [ "$SEED_EXISTS" != "1" ]; then
    echo "🌾 Seeding initial data..."
    php artisan db:seed --force
else
    echo "✅ Seed already ran. Skipping..."
fi

echo "🚀 Starting Laravel server..."