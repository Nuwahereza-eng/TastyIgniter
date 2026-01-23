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

# Clear caches after patching
echo ""
echo "Clearing caches..."
cd /home/petercodes/TastyIgniter
php artisan view:clear
php artisan cache:clear

echo ""
echo "Done! All patches applied."
