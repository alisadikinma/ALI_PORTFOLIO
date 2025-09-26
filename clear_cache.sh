#!/bin/bash

echo "🔧 CLEARING LARAVEL CACHE..."

# Navigate to project directory
cd "C:/xampp/htdocs/ALI_PORTFOLIO"

# Clear various caches
echo "1. Clearing application cache..."
php artisan cache:clear

echo "2. Clearing config cache..."
php artisan config:clear

echo "3. Clearing route cache..."
php artisan route:clear

echo "4. Clearing view cache..."
php artisan view:clear

echo "5. Optimizing for production..."
php artisan optimize

echo "✅ Cache clearing completed!"
