// Uganda TastyIgniter Enhancements
(function() {
    'use strict';

    // Format currency to UGX
    function formatUGX(amount) {
        return 'UGX ' + parseFloat(amount).toLocaleString('en-UG', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        
        // =====================================================
        // NAVBAR SCROLL EFFECT - Changes style when scrolling
        // =====================================================
        const navbar = document.querySelector('.navbar');
        
        function handleNavbarScroll() {
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
        
        if (navbar) {
            window.addEventListener('scroll', handleNavbarScroll);
            handleNavbarScroll(); // Check initial state
        }
        
        // =====================================================
        // HERO SLIDER AUTO-PLAY ENHANCEMENT (5 seconds)
        // =====================================================
        // Find carousel by multiple selectors including the home-slider
        const heroCarousel = document.querySelector('[id^="slider-"], .carousel, #heroCarousel, [data-bs-ride="carousel"], .hero-slider');
        
        if (heroCarousel) {
            // Set the interval attribute directly on the element
            heroCarousel.setAttribute('data-bs-interval', '5000');
            
            if (typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
                // Dispose any existing instance first
                const existingCarousel = bootstrap.Carousel.getInstance(heroCarousel);
                if (existingCarousel) {
                    existingCarousel.dispose();
                }
                
                // Create new carousel with 5 second interval
                const carousel = new bootstrap.Carousel(heroCarousel, {
                    interval: 5000, // 5 seconds
                    wrap: true,
                    pause: 'hover',
                    ride: 'carousel'
                });
                
                // Force auto-play
                carousel.cycle();
                
                console.log('Hero carousel initialized with 5 second interval:', heroCarousel.id);
            } else {
                // Fallback: Manual auto-slide if Bootstrap is not available
                const slides = heroCarousel.querySelectorAll('.carousel-item');
                let currentSlide = 0;
                
                function nextSlide() {
                    slides[currentSlide].classList.remove('active');
                    currentSlide = (currentSlide + 1) % slides.length;
                    slides[currentSlide].classList.add('active');
                }
                
                if (slides.length > 1) {
                    setInterval(nextSlide, 5000); // 5 seconds
                    console.log('Manual carousel initialized with 5 second interval');
                }
            }
        }
        
        // Enhance menu item cards to ensure images display
        function enhanceMenuCards() {
            // Find all menu cards
            const menuCards = document.querySelectorAll(
                '.menu-card, .menu-item-card, [class*="menu"][class*="card"], .card'
            );
            
            menuCards.forEach(card => {
                // Ensure images have proper styling
                const img = card.querySelector('img');
                if (img) {
                    img.style.width = '100%';
                    img.style.height = '220px';
                    img.style.objectFit = 'cover';
                    img.style.display = 'block';
                    
                    // Add error handler for broken images
                    img.onerror = function() {
                        this.style.display = 'flex';
                        this.style.alignItems = 'center';
                        this.style.justifyContent = 'center';
                        this.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                        this.alt = '🍽️ Menu Item';
                    };
                }
                
                // Add hover effect class
                card.classList.add('menu-enhanced');
            });
        }
        
        // Run enhancement on page load
        enhanceMenuCards();
        
        // Re-run when Livewire updates (for dynamic content)
        if (window.Livewire) {
            window.Livewire.hook('message.processed', (message, component) => {
                setTimeout(enhanceMenuCards, 100);
            });
        }
        
        // Also watch for DOM changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    enhanceMenuCards();
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
        
        // Add Uganda flag to phone inputs
        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.placeholder = '+256 XXX XXX XXX';
            input.classList.add('phone-number');
        });

        // Format all prices to UGX
        const priceElements = document.querySelectorAll('.price, .currency, .amount');
        priceElements.forEach(el => {
            const amount = el.textContent.replace(/[^0-9.]/g, '');
            if (amount) {
                el.textContent = formatUGX(amount);
            }
        });

        // Add smooth scroll to all anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });

        // Add animation to cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        entry.target.style.transition = 'all 0.6s ease';
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, 100);
                    
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card, .menu-item').forEach(card => {
            observer.observe(card);
        });

        // Add loading animation to buttons
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    submitBtn.innerHTML = '<span class="loading-spinner"></span> Processing...';
                    submitBtn.disabled = true;
                }
            });
        });

        // Show current time in Kampala
        function updateKampalaTime() {
            const options = {
                timeZone: 'Africa/Kampala',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            const kampalaTime = new Intl.DateTimeFormat('en-US', options).format(new Date());
            
            const timeDisplay = document.querySelector('.kampala-time');
            if (timeDisplay) {
                timeDisplay.textContent = kampalaTime + ' (EAT)';
            }
        }

        // Update time every second
        setInterval(updateKampalaTime, 1000);
        updateKampalaTime();

        // Add delivery time estimator
        function estimateDeliveryTime(distance) {
            // Kampala traffic estimation
            let time = 30; // Base time in minutes
            
            if (distance > 5) time += (distance - 5) * 5; // 5 min per km after 5km
            if (distance > 15) time += (distance - 15) * 3; // Slower outside city
            
            return Math.round(time);
        }

        // Payment method icons
        const paymentMethods = {
            'mtn': {
                name: 'MTN Mobile Money',
                icon: '📱',
                color: '#FFCC00'
            },
            'airtel': {
                name: 'Airtel Money',
                icon: '📱',
                color: '#ED1C24'
            },
            'flutterwave': {
                name: 'Flutterwave',
                icon: '💳',
                color: '#F5A623'
            },
            'cash': {
                name: 'Cash on Delivery',
                icon: '💵',
                color: '#06D6A0'
            }
        };

        // Enhance payment method display
        document.querySelectorAll('.payment-option').forEach(option => {
            const method = option.dataset.method;
            if (paymentMethods[method]) {
                const icon = document.createElement('span');
                icon.textContent = paymentMethods[method].icon;
                icon.style.marginRight = '10px';
                icon.style.fontSize = '1.5rem';
                option.prepend(icon);
            }
        });

        // Add Uganda-specific validation for phone numbers
        const phoneValidator = function(input) {
            const value = input.value.trim();
            const ugandaRegex = /^(\+256|0)[0-9]{9}$/;
            
            if (value && !ugandaRegex.test(value)) {
                input.setCustomValidity('Please enter a valid Uganda phone number (+256XXXXXXXXX or 0XXXXXXXXX)');
            } else {
                input.setCustomValidity('');
            }
        };

        phoneInputs.forEach(input => {
            input.addEventListener('blur', function() {
                phoneValidator(this);
            });
            input.addEventListener('input', function() {
                phoneValidator(this);
            });
        });

        // Add "Call to Order" floating button for mobile
        if (window.innerWidth < 768) {
            const floatingBtn = document.createElement('a');
            floatingBtn.href = 'tel:+256700000000'; // Update with actual number
            floatingBtn.className = 'floating-call-btn';
            floatingBtn.innerHTML = '📞 Call to Order';
            floatingBtn.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: linear-gradient(135deg, #FF6B35, #004E89);
                color: white;
                padding: 15px 25px;
                border-radius: 50px;
                text-decoration: none;
                font-weight: 600;
                box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                z-index: 1000;
                animation: pulse 2s infinite;
            `;
            document.body.appendChild(floatingBtn);
        }

        // Add notification for order updates
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#06D6A0' : '#F5576C'};
                color: white;
                padding: 1rem 2rem;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                z-index: 9999;
                animation: slideIn 0.5s ease;
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.5s ease';
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        }

        // Expose functions globally
        window.UgandaTastyIgniter = {
            formatUGX,
            estimateDeliveryTime,
            showNotification
        };
    });

    // =====================================================
    // USE CURRENT LOCATION BUTTON ENHANCEMENT
    // Detect location AND trigger search to go to locations page
    // =====================================================
    document.addEventListener('click', function(event) {
        const button = event.target.closest('#use-current-location-btn');
        if (!button) return;
        
        event.preventDefault();
        
        // Disable button and show loading state
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fa fa-spinner fa-spin fs-5"></i> <span>Detecting location...</span>';
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    // Update button to show success
                    button.innerHTML = '<i class="fa fa-check fs-5"></i> <span>Location found!</span>';
                    
                    // Dispatch the Livewire event to update position
                    if (window.Livewire) {
                        Livewire.dispatch('userPositionUpdated', {
                            position: [lat, lng],
                            updateMap: true
                        });
                        
                        // Wait a moment then trigger the search
                        setTimeout(function() {
                            // Find the search form and submit it
                            const searchForm = document.querySelector('#location-search');
                            if (searchForm) {
                                // Trigger Livewire form submission
                                const submitEvent = new Event('submit', { bubbles: true, cancelable: true });
                                searchForm.dispatchEvent(submitEvent);
                            } else {
                                // Fallback: redirect to locations page directly
                                window.location.href = '/locations';
                            }
                        }, 500);
                    } else {
                        // Fallback if Livewire not available
                        window.location.href = '/locations';
                    }
                },
                function(error) {
                    // Error getting location
                    button.disabled = false;
                    button.innerHTML = originalText;
                    
                    let errorMessage = 'Unable to get your location.';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = 'Location access denied. Please enable location permissions.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = 'Location information is unavailable.';
                            break;
                        case error.TIMEOUT:
                            errorMessage = 'Location request timed out. Please try again.';
                            break;
                    }
                    
                    alert(errorMessage);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 30000
                }
            );
        } else {
            button.disabled = false;
            button.innerHTML = originalText;
            alert('Geolocation is not supported by your browser.');
        }
    });

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

})();
