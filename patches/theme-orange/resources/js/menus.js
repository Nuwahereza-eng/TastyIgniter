// Menu list functionality - handles menu item interactions
+function ($) {
    "use strict";

    // Initialize menu item click handlers
    $(document).ready(function() {
        // Handle click on menu items with orange-modal data toggle
        $(document).on('click', '[data-toggle="orange-modal"]', function(event) {
            var $el = $(this);
            
            // Prevent click on disabled items
            if ($el.hasClass('disabled') || $el.hasClass('opacity-50')) {
                event.preventDefault();
                event.stopPropagation();
                return false;
            }
        });
    });

    // Livewire hook to reinitialize after page updates
    ['livewire:initialized', 'livewire:navigated'].forEach(function (eventName) {
        document.addEventListener(eventName, function() {
            // Reinitialize any menu-specific handlers after Livewire updates
        }, {once: false});
    });

}(window.jQuery);
