#!/bin/sh

# Set error handling
set -e

echo "----------------------------------------------------------------"
echo "🚀 IAMJOS SYSTEM STARTUP SEQUENCE"
echo "----------------------------------------------------------------"

cd /app

# 1. Sync File Permissions
echo "📦 Syncing storage and cache permissions..."
chown -R www-data:www-data /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

# 2. Database Operations
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "🔍 Checking Database Connection..."
    # Attempt to show db status to verify connectivity
    if php artisan db:show > /dev/null 2>&1; then
        echo "✅ Database connection successful."
    else
        echo "❌ Database connection failed! Please check your DB_URL."
        exit 1
    fi

    echo "⚙️  Running Database Migrations..."
    php artisan migrate --force

    echo "🌱 Starting Database Seeding..."
    # We run seeders individually for better control and logging
    echo "   - Seeding Roles & Permissions..."
    php artisan db:seed --class=RolesAndPermissionsSeeder --force
    
    echo "   - Seeding Site Content..."
    php artisan db:seed --class=SiteContentSeeder --force
    
    echo "   - Seeding Homepage Content Blocks..."
    php artisan db:seed --class=SiteContentBlockSeeder --force
    
    echo "   - Seeding Default Navigation..."
    php artisan db:seed --class=DefaultNavigationSeeder --force
    
    echo "   - Seeding Email & Notification Templates..."
    php artisan db:seed --class=EmailTemplateSeeder --force
    php artisan db:seed --class=NotificationTemplateSeeder --force
    
    echo "   - Seeding Sample Journals & Initial Data..."
    php artisan db:seed --class=JournalSeeder --force
    php artisan db:seed --class=InitialDataSeeder --force
    
    echo "✅ Database operations completed."
else
    echo "⚠️  Skipping migrations (RUN_MIGRATIONS is not true)."
fi

# 3. Application Optimization
if [ "$APP_ENV" = "production" ]; then
    echo "⚡ Optimizing for Production..."
    php artisan optimize:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    echo "✅ Optimization complete."
fi

echo "----------------------------------------------------------------"
echo "🐘 IAMJOS READY - STARTING FRANKENPHP"
echo "----------------------------------------------------------------"
