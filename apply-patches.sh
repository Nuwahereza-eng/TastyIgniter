#!/bin/bash
# Apply patches after composer install
# Works both locally and in Docker

# Detect the project root directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="${SCRIPT_DIR}"

# If running in Docker, use /var/www/html
if [ -d "/var/www/html/vendor" ]; then
    PROJECT_ROOT="/var/www/html"
fi

echo "Applying TastyIgniter patches..."
echo "Project root: $PROJECT_ROOT"

# Fix orderType bug in SearchesNearby trait
SEARCHES_NEARBY="$PROJECT_ROOT/vendor/tastyigniter/ti-theme-orange/src/Livewire/Concerns/SearchesNearby.php"

if [ -f "$SEARCHES_NEARBY" ]; then
    if grep -q "property_exists.*orderType" "$SEARCHES_NEARBY"; then
        echo "✓ orderType fix already applied"
    else
        sed -i 's/if (\$this->orderType && \$this->orderType === LocationModel::DELIVERY && \$this->searchAutocompleteEnabled)/if (property_exists(\$this, '\''orderType'\'') \&\& \$this->orderType \&\& \$this->orderType === LocationModel::DELIVERY \&\& \$this->searchAutocompleteEnabled)/' "$SEARCHES_NEARBY"
        echo "✓ Applied orderType fix to SearchesNearby.php"
    fi
else
    echo "✗ SearchesNearby.php not found"
fi

# Fix Uganda address validation
ORDER_MANAGER="$PROJECT_ROOT/vendor/tastyigniter/ti-ext-cart/src/Classes/OrderManager.php"

if [ -f "$ORDER_MANAGER" ]; then
    if grep -q "Uganda addresses often don't have street numbers" "$ORDER_MANAGER"; then
        echo "✓ Uganda address validation fix already applied"
    else
        sed -i 's/if (!$userLocation->getStreetNumber() || !$userLocation->getStreetName()) {/\/\/ Uganda addresses often don'\''t have street numbers - be more lenient\n        $hasStreetInfo = $userLocation->getStreetName() || $userLocation->getStreetNumber();\n        $hasValidCoordinates = $userLocation->getCoordinates() \&\& $userLocation->getCoordinates()->getLatitude() != 0;\n        if (!$hasStreetInfo \&\& !$hasValidCoordinates) {/' "$ORDER_MANAGER"
        echo "✓ Applied Uganda address validation fix to OrderManager.php"
    fi
else
    echo "✗ OrderManager.php not found"
fi

# Apply search experience patch
SEARCH_PATCH="$PROJECT_ROOT/patches/fix-search-experience.patch"
LOCAL_SEARCH="$PROJECT_ROOT/vendor/tastyigniter/ti-theme-orange/resources/views/livewire/local-search.blade.php"

if [ -f "$SEARCH_PATCH" ] && [ -f "$LOCAL_SEARCH" ]; then
    if grep -q 'position-relative' "$LOCAL_SEARCH"; then
        echo "✓ Search experience patch already applied"
    else
        cd "$PROJECT_ROOT"
        patch -p1 < "$SEARCH_PATCH" 2>/dev/null && echo "✓ Applied search experience patch" || echo "✗ Search experience patch failed"
    fi
fi

# Apply saved-address-picker patch (Use Current Location and Use Saved Address buttons)
SAVED_ADDRESS_PICKER="$PROJECT_ROOT/vendor/tastyigniter/ti-theme-orange/resources/views/includes/local/saved-address-picker.blade.php"
if [ -f "$SAVED_ADDRESS_PICKER" ]; then
    if grep -q 'Use Current Location' "$SAVED_ADDRESS_PICKER"; then
        echo "✓ Saved address picker patch already applied"
    else
        PICKER_PATCH="$PROJECT_ROOT/patches/fix-saved-address-picker.patch"
        if [ -f "$PICKER_PATCH" ]; then
            cd "$PROJECT_ROOT"
            patch -p1 < "$PICKER_PATCH" 2>/dev/null && echo "✓ Applied saved-address-picker patch" || echo "✗ Saved-address-picker patch failed"
        fi
    fi
fi

# Apply other patches
for patch_file in "$PROJECT_ROOT/patches"/*.patch; do
    if [ -f "$patch_file" ] && [ "$patch_file" != "$SEARCH_PATCH" ]; then
        patch_name=$(basename "$patch_file")
        cd "$PROJECT_ROOT"
        patch -p1 --forward < "$patch_file" 2>/dev/null && echo "✓ Applied $patch_name" || echo "- $patch_name (already applied or N/A)"
    fi
done

# Copy custom feature pages
THEME_PAGES="$PROJECT_ROOT/vendor/tastyigniter/ti-theme-orange/resources/views/_pages"
PATCHES_DIR="$PROJECT_ROOT/patches"

if [ -f "$PATCHES_DIR/features.blade.php" ]; then
    mkdir -p "$THEME_PAGES/account"
    cp "$PATCHES_DIR/features.blade.php" "$THEME_PAGES/account/features.blade.php"
    echo "✓ Copied features page"
fi

if [ -f "$PATCHES_DIR/track-order.blade.php" ]; then
    cp "$PATCHES_DIR/track-order.blade.php" "$THEME_PAGES/track-order.blade.php"
    echo "✓ Copied track-order page"
fi

if [ -f "$PATCHES_DIR/menus.blade.php" ]; then
    mkdir -p "$THEME_PAGES/local"
    cp "$PATCHES_DIR/menus.blade.php" "$THEME_PAGES/local/menus.blade.php"
    echo "✓ Copied menus page"
fi

# Copy features CSS to public
if [ -f "$PATCHES_DIR/features.css" ]; then
    mkdir -p "$PROJECT_ROOT/public/themes/demo/assets/css"
    cp "$PATCHES_DIR/features.css" "$PROJECT_ROOT/public/themes/demo/assets/css/features.css"
    echo "✓ Copied features CSS"
fi

echo ""
echo "Done! All patches applied."
