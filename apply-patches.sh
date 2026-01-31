#!/bin/bash
# Apply patches after composer update
# Run this script after running 'composer update' to reapply custom fixes

echo "Applying TastyIgniter patches..."

# Fix orderType bug in SearchesNearby trait
SEARCHES_NEARBY="/home/petercodes/TastyIgniter/vendor/tastyigniter/ti-theme-orange/src/Livewire/Concerns/SearchesNearby.php"

if [ -f "$SEARCHES_NEARBY" ]; then
    # Check if the fix is already applied
    if grep -q "property_exists.*orderType" "$SEARCHES_NEARBY"; then
        echo "✓ orderType fix already applied"
    else
        # Apply the fix
        sed -i 's/if (\$this->orderType && \$this->orderType === LocationModel::DELIVERY && \$this->searchAutocompleteEnabled)/if (property_exists(\$this, '\''orderType'\'') \&\& \$this->orderType \&\& \$this->orderType === LocationModel::DELIVERY \&\& \$this->searchAutocompleteEnabled)/' "$SEARCHES_NEARBY"
        echo "✓ Applied orderType fix to SearchesNearby.php"
    fi
else
    echo "✗ SearchesNearby.php not found - theme may have changed"
fi

# Fix Uganda address validation (street number not required)
ORDER_MANAGER="/home/petercodes/TastyIgniter/vendor/tastyigniter/ti-ext-cart/src/Classes/OrderManager.php"

if [ -f "$ORDER_MANAGER" ]; then
    # Check if the fix is already applied
    if grep -q "Uganda addresses often don't have street numbers" "$ORDER_MANAGER"; then
        echo "✓ Uganda address validation fix already applied"
    else
        # Apply the fix using sed to make address validation more lenient
        sed -i 's/if (!$userLocation->getStreetNumber() || !$userLocation->getStreetName()) {/\/\/ Uganda addresses often don'\''t have street numbers - be more lenient\n        $hasStreetInfo = $userLocation->getStreetName() || $userLocation->getStreetNumber();\n        $hasValidCoordinates = $userLocation->getCoordinates() \&\& $userLocation->getCoordinates()->getLatitude() != 0;\n        if (!$hasStreetInfo \&\& !$hasValidCoordinates) {/' "$ORDER_MANAGER"
        echo "✓ Applied Uganda address validation fix to OrderManager.php"
    fi
else
    echo "✗ OrderManager.php not found"
fi

# Copy custom feature pages to theme
THEME_PAGES="/home/petercodes/TastyIgniter/vendor/tastyigniter/ti-theme-orange/resources/views/_pages"
PATCHES_DIR="/home/petercodes/TastyIgniter/patches"

# Check if features page exists in patches
if [ -f "$PATCHES_DIR/features.blade.php" ]; then
    mkdir -p "$THEME_PAGES/account"
    cp "$PATCHES_DIR/features.blade.php" "$THEME_PAGES/account/features.blade.php"
    echo "✓ Copied features page (Group Orders, Scheduled Orders, Subscriptions, Order Tracking)"
fi

if [ -f "$PATCHES_DIR/track-order.blade.php" ]; then
    cp "$PATCHES_DIR/track-order.blade.php" "$THEME_PAGES/track-order.blade.php"
    echo "✓ Copied public order tracking page"
fi

# Copy features CSS
if [ -f "$PATCHES_DIR/features.css" ]; then
    mkdir -p "/home/petercodes/TastyIgniter/public/themes/demo/assets/css"
    cp "$PATCHES_DIR/features.css" "/home/petercodes/TastyIgniter/public/themes/demo/assets/css/features.css"
    echo "✓ Copied features CSS"
fi

# Clear caches after patching
echo ""
echo "Clearing caches..."
cd /home/petercodes/TastyIgniter
php artisan view:clear
php artisan cache:clear

echo ""
echo "Done! All patches applied."
