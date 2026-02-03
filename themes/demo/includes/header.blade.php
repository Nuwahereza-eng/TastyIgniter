<nav class="navbar navbar-light navbar-top navbar-expand-md py-sm-2 py-md-0">
    {{-- Loyalty Points Badge - Extreme Top Right Corner --}}
    <div class="loyalty-corner-badge" id="loyaltyCornerBadge" title="My Rewards">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
        </svg>
        <span id="header-points-display">0</span> pts
    </div>
    
    <div class="container">
        <a class="navbar-brand" href="{{ page_url('home') }}">
            @php
                $isHomePage = request()->is('/') || request()->is('home');
            @endphp
            @if(!$isHomePage)
                <img
                    class="img-logo white-logo"
                    alt="{{ setting('site_name') }}"
                    src="{{ asset('themes/demo/assets/images/tastyigniter-white-logo.svg') }}"
                />
            @elseif($theme->logo_image ?? false)
                <img
                    class="img-logo"
                    alt="{{ setting('site_name') }}"
                    src="{{ media_url($theme->logo_image) }}"
                />
            @elseif($theme->logo_text ?? false)
                <span class="text-logo">{{ $theme->logo_text }}</span>
            @else
                <img
                    class="img-logo"
                    alt="{{ $site_name ?? 'UgaEats' }}"
                    src="{{ asset('vendor/igniter-orange/images/favicon.ico') }}"
                />
            @endif
        </a>
        <button
            class="navbar-toggler border-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMainHeader"
            aria-controls="navbarMainHeader"
            aria-expanded="false"
            aria-label="Toggle navigation"
        ><span class="navbar-toggler-icon"></span></button>

        <div class="justify-content-end collapse navbar-collapse" id="navbarMainHeader">
            <x-igniter-orange::nav code="main-menu"/>
            
            <!-- Premium Features Dropdown -->
            <ul class="navbar-nav premium-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="premiumFeaturesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-crown me-1"></i> Premium
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="premiumFeaturesDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ url('/account/features#group-orders') }}">
                                <i class="fa fa-users text-primary me-2"></i>Group Orders
                                <small class="d-block text-muted">Order together with friends</small>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ url('/account/features#scheduled-orders') }}">
                                <i class="fa fa-clock text-warning me-2"></i>Schedule Order
                                <small class="d-block text-muted">Plan your meals ahead</small>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ url('/account/features#subscriptions') }}">
                                <i class="fa fa-calendar-check text-success me-2"></i>Meal Plans
                                <small class="d-block text-muted">Save with subscriptions</small>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ url('/track-order') }}">
                                <i class="fa fa-motorcycle text-info me-2"></i>Track Order
                                <small class="d-block text-muted">Real-time delivery tracking</small>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <livewire:igniter-orange::cart-box />
        </div>
    </div>
</nav>

<!-- Loyalty Rewards Modal -->
<div class="loyalty-modal" id="loyaltyModal">
    <div class="loyalty-card">
        <h3>🏆 Your Rewards</h3>
        <p style="color: #666;">Earn points with every order!</p>
        
        <div class="loyalty-points-display">
            <span id="loyaltyPoints">0</span>
            <span>points</span>
        </div>
        
        <div class="loyalty-progress">
            <div class="loyalty-progress-bar" id="loyaltyProgress" style="width: 0%"></div>
        </div>
        <p style="font-size: 14px; color: #888;">
            <span id="pointsToNext">100</span> points until next reward
        </p>
        
        <div class="loyalty-rewards">
            <div class="loyalty-reward-item" data-points="100">
                <span>🥤 Free Drink</span>
                <span>100 pts</span>
            </div>
            <div class="loyalty-reward-item" data-points="250">
                <span>🍟 Free Side</span>
                <span>250 pts</span>
            </div>
            <div class="loyalty-reward-item" data-points="500">
                <span>🍔 Free Meal</span>
                <span>500 pts</span>
            </div>
            <div class="loyalty-reward-item" data-points="1000">
                <span>🎉 50% Off Order</span>
                <span>1000 pts</span>
            </div>
        </div>
        
        <button class="loyalty-close" onclick="closeLoyaltyModal()">Close</button>
    </div>
</div>

<script>
function getLoyaltyPoints() {
    return parseInt(localStorage.getItem('ugaeats_loyalty_points') || '0');
}

function updateLoyaltyDisplay() {
    const points = getLoyaltyPoints();
    const loyaltyPointsEl = document.getElementById('loyaltyPoints');
    const headerPointsEl = document.getElementById('header-points-display');
    
    if (loyaltyPointsEl) loyaltyPointsEl.textContent = points;
    if (headerPointsEl) headerPointsEl.textContent = points;
    
    const progress = (points % 100);
    const progressBar = document.getElementById('loyaltyProgress');
    const pointsToNext = document.getElementById('pointsToNext');
    
    if (progressBar) progressBar.style.width = progress + '%';
    if (pointsToNext) pointsToNext.textContent = 100 - progress;
    
    document.querySelectorAll('.loyalty-reward-item').forEach(item => {
        const requiredPoints = parseInt(item.dataset.points);
        if (points >= requiredPoints) {
            item.classList.add('unlocked');
        } else {
            item.classList.remove('unlocked');
        }
    });
}

function openLoyaltyModal() {
    document.getElementById('loyaltyModal').classList.add('active');
    updateLoyaltyDisplay();
}

function closeLoyaltyModal() {
    document.getElementById('loyaltyModal').classList.remove('active');
}

document.addEventListener('DOMContentLoaded', function() {
    updateLoyaltyDisplay();
    
    const loyaltyBadge = document.getElementById('loyaltyCornerBadge');
    if (loyaltyBadge) {
        loyaltyBadge.addEventListener('click', function() {
            openLoyaltyModal();
        });
    }
    
    const modal = document.getElementById('loyaltyModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoyaltyModal();
            }
        });
    }
});
</script>
