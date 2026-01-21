// Uganda TastyIgniter Enhancements
(function() {
    'use strict';

    // Format currency to UGX
    function formatUGX(amount) {
        return 'USh ' + parseFloat(amount).toLocaleString('en-UG', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        
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
