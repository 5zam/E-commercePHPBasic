@extends('layout.app')

@section('title', 'TekSouq - Tech At Hand | Premium Smartwatches')

@section('content')<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-background-image"></div>
    <div class="hero-overlay"></div>
    
    <div class="container">
        <div class="row align-items-center hero-row">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">
                        <span class="hero-text-main">TECH AT HAND</span>
                    </h1>
                    <p class="hero-subtitle">
                        Carefully selected electronic products that combine high performance with smart design — because technology is now part of your lifestyle.
                    </p>
                    <div class="hero-buttons">
                        <a href="{{ route('shop') }}" class="btn btn-primary-glow btn-lg me-3">
                            <i class="fas fa-shopping-bag me-2"></i>Shop Now
                        </a>
                        @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-glow btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Join Today
                        </a>
                        @endguest
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- Right side empty for background devices -->
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productName = this.getAttribute('data-product');
            
            // Add loading state
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-check"></i>';
                this.classList.add('success');
                
                // Show notification
                showNotification(`${productName} added to cart!`);
                
                // Reset after 2 seconds
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-cart-plus"></i>';
                    this.classList.remove('success');
                    this.disabled = false;
                }, 2000);
                
                // Update cart counter
                updateCartCounter();
            }, 1000);
        });
    });
    
    // Quick view functionality
    document.querySelectorAll('.quick-view').forEach(button => {
        button.addEventListener('click', function() {
            const productName = this.getAttribute('data-product');
            showNotification(`Quick view for ${productName} coming soon!`);
        });
    });
    
    function updateCartCounter() {
        const cartBadge = document.querySelector('.navbar .badge');
        if (cartBadge) {
            let currentCount = parseInt(cartBadge.textContent) || 0;
            cartBadge.textContent = currentCount + 1;
        }
    }
    
    function showNotification(message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            ${message}
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => notification.classList.add('show'), 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // Smooth scrolling for shop now button
    const shopNowBtn = document.querySelector('.hero-buttons a[href*="shop"]');
    if (shopNowBtn) {
        shopNowBtn.addEventListener('click', function(e) {
            // Let the normal navigation work
        });
    }
});
</script>
@endpush