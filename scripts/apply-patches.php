<?php
/**
 * Apply patches to vendor files at runtime
 * This is more reliable than shell patches
 */

$projectRoot = dirname(__DIR__);

echo "=== Applying PHP Patches ===\n";

// 1. Patch saved-address-picker.blade.php
$savedAddressPickerPath = $projectRoot . '/vendor/tastyigniter/ti-theme-orange/resources/views/includes/local/saved-address-picker.blade.php';

if (file_exists($savedAddressPickerPath)) {
    $content = file_get_contents($savedAddressPickerPath);
    
    // Check if already patched
    if (strpos($content, 'Use Current Location') === false) {
        $newContent = <<<'BLADE'
<div class="mt-4">
    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
        {{-- Use Current Location Button --}}
        <button
            type="button"
            data-control="user-position"
            class="btn btn-lg btn-outline-primary d-flex align-items-center justify-content-center gap-2 px-4 py-3"
            wire:loading.class="disabled"
            id="use-current-location-btn"
        >
            <i class="fa fa-crosshairs fs-5"></i>
            <span>Use Current Location</span>
        </button>

        @auth('igniter-customer')
            @if($this->savedAddresses->count() > 0)
                {{-- Use Saved Address Button with Dropdown --}}
                <div class="dropdown">
                    <button
                        type="button"
                        class="btn btn-lg btn-outline-primary d-flex align-items-center justify-content-center gap-2 px-4 py-3 dropdown-toggle"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        id="saved-address-btn"
                    >
                        <i class="fa fa-bookmark fs-5"></i>
                        <span>Use Saved Address</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end w-100" style="min-width: 300px;">
                        @foreach ($this->savedAddresses as $address)
                            <li>
                                <button
                                    type="button"
                                    wire:click="onSelectAddress({{ $address->address_id }})"
                                    class="dropdown-item py-2 d-flex align-items-start gap-2"
                                >
                                    <i class="fa fa-location-dot text-primary mt-1"></i>
                                    <span class="flex-grow-1 text-wrap">{{ $address->formatted_address }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @else
            {{-- Login to use saved addresses --}}
            <a
                href="{{ page_url('account.login') }}"
                class="btn btn-lg btn-outline-primary d-flex align-items-center justify-content-center gap-2 px-4 py-3"
                id="login-for-address-btn"
            >
                <i class="fa fa-user fs-5"></i>
                <span>Log In for Saved Addresses</span>
            </a>
        @endauth
    </div>
    
    <x-igniter-orange::forms.error field="savedAddress" id="savedAddressFeedback" class="p-2 text-danger text-center" />
</div>
BLADE;
        
        file_put_contents($savedAddressPickerPath, $newContent);
        echo "✓ Patched saved-address-picker.blade.php\n";
    } else {
        echo "✓ saved-address-picker.blade.php already patched\n";
    }
} else {
    echo "✗ saved-address-picker.blade.php not found\n";
}

// 2. Patch local-search.blade.php - add position-relative class
$localSearchPath = $projectRoot . '/vendor/tastyigniter/ti-theme-orange/resources/views/livewire/local-search.blade.php';

if (file_exists($localSearchPath)) {
    $content = file_get_contents($localSearchPath);
    
    // Check if position-relative is already there
    if (strpos($content, 'position-relative') === false) {
        $content = str_replace(
            'id="local-search-form">',
            'id="local-search-form" class="position-relative">',
            $content
        );
        
        // Also update placeholder text
        $content = str_replace(
            "placeholder=\"@lang('igniter.local::default.label_search_query')\"",
            'placeholder="Enter your delivery address..."',
            $content
        );
        
        file_put_contents($localSearchPath, $content);
        echo "✓ Patched local-search.blade.php\n";
    } else {
        echo "✓ local-search.blade.php already patched\n";
    }
} else {
    echo "✗ local-search.blade.php not found\n";
}

echo "=== Patches Applied ===\n";
