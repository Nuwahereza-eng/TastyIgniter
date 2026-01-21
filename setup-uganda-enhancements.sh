#!/bin/bash

# Uganda TastyIgniter Visual Enhancements Setup Script

echo "🇺🇬 Setting up Uganda Visual Enhancements for TastyIgniter..."
echo ""

# Check if running from project root
if [ ! -f "artisan" ]; then
    echo "❌ Error: Please run this script from the TastyIgniter root directory"
    exit 1
fi

# Clear caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Make sure files exist
echo "✅ Checking enhancement files..."
if [ ! -f "public/themes/uganda-custom.css" ]; then
    echo "❌ uganda-custom.css not found"
    exit 1
fi

if [ ! -f "public/themes/uganda-enhancements.js" ]; then
    echo "❌ uganda-enhancements.js not found"
    exit 1
fi

echo "✅ All enhancement files found"

# Update APP_NAME in .env if not already updated
if grep -q 'APP_NAME="TastyIgniter"' .env; then
    echo "📝 Updating app name to 'TastyIgniter Uganda'..."
    sed -i 's/APP_NAME="TastyIgniter"/APP_NAME="TastyIgniter Uganda"/' .env
fi

# Create symlink for public storage if needed
if [ ! -L "public/storage" ]; then
    echo "🔗 Creating storage symlink..."
    php artisan storage:link
fi

echo ""
echo "✅ Uganda Visual Enhancements Setup Complete!"
echo ""
echo "📝 Next Steps:"
echo "1. Restart your server: php artisan serve --host=0.0.0.0 --port=8001"
echo "2. Open: http://localhost:8001"
echo "3. Go to Admin Panel > Design > Themes"
echo "4. Add these files to your active theme:"
echo "   - /themes/uganda-custom.css"
echo "   - /themes/uganda-enhancements.js"
echo ""
echo "📖 Full documentation: UGANDA_VISUAL_ENHANCEMENTS.md"
echo ""
echo "🎉 Enjoy your Uganda-themed TastyIgniter!"
