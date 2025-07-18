@extends('layout.app')

@section('title', 'Shop - TekSouq')

@push('styles')
<link href="{{ asset('css/shop.css') }}" rel="stylesheet">
<link href="{{ asset('css/right-sidebar-filter.css') }}" rel="stylesheet">
@endpush

@section('body-class', 'shop-page')

@section('content')
<!-- Shop Hero Section -->
<section class="shop-hero">
    <div class="shop-hero-background"></div>
    <div class="shop-hero-overlay"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="shop-hero-content">
                    <h1 class="shop-hero-title">
                        Discover Amazing <span class="gradient-text">Tech Products</span>
                    </h1>
                    <p class="shop-hero-subtitle">
                        From smartwatches to accessories, find everything you need for your digital lifestyle
                    </p>
                    <div class="shop-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ $products->count() }}</span>
                            <span class="stat-label">Products</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">3</span>
                            <span class="stat-label">Categories</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">1</span>
                            <span class="stat-label">Brands</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shop Content -->
<section class="shop-content" id="shopContent">
    <div class="container">
        <div class="row">
            <!-- Desktop: Filters will be in sidebar, Mobile: Filters in regular sidebar -->
            <div class="col-lg-3 d-lg-block d-none">
                @include('shop.parts.filters')
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9 col-12">
                
                <!-- Enhanced Shop Header -->
                @include('shop.parts.enhanced-shop-header')

                <!-- Products Grid -->
                <div class="products-grid" id="productsGrid">
                    @if($products && $products->count() > 0)
                        @foreach($products as $product)
                            @include('shop.parts.product-card', ['product' => $product])
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="no-products">
                                <div class="no-products-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h3>No products found</h3>
                                <p>Try adjusting your filters or search terms to find what you're looking for</p>
                                <div class="no-products-actions">
                                    <a href="{{ route('shop') }}" class="btn btn-primary">
                                        <i class="fas fa-th me-2"></i>View All Products
                                    </a>
                                    @if(request()->hasAny(['search', 'min_price', 'max_price']))
                                        <button onclick="clearAllFilters()" class="btn btn-outline">
                                            <i class="fas fa-times me-2"></i>Clear Filters
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Enhanced Pagination -->
                @if($products && $products->hasPages())
                <div class="shop-pagination">
                    <div class="pagination-container">
                        {{ $products->links('shop.parts.pagination') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Mobile Filter Button (Mobile Only) -->
@include('shop.parts.mobile-filter-button')

<!-- Mobile Filter Panel (Mobile Only) -->
@include('shop.parts.mobile-filter-panel')

@endsection

@push('scripts')
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/mobile-filter.js') }}"></script>
<script src="{{ asset('js/shop-enhancements.js') }}"></script>
<script src="{{ asset('js/right-sidebar-filter.js') }}"></script>
<script src="{{ asset('js/sort-functionality-fix.js') }}"></script>

<script>
// Enhanced Shop Functions
document.addEventListener('DOMContentLoaded', function() {
    
    // Sort functionality with enhanced feedback
    window.updateSort = function(sortValue) {
        const url = new URL(window.location);
        url.searchParams.set('sort', sortValue);
        
        // Show loading feedback
        const sortSelect = document.querySelector('.shop-sort');
        if (sortSelect) {
            sortSelect.disabled = true;
            sortSelect.style.opacity = '0.7';
        }
        
        // Add loading notification
        showEnhancedNotification('Sorting products...', 'info');
        
        // Navigate to new URL
        window.location.href = url.toString();
    };
    
    // Enhanced view toggle with smooth transitions
    document.querySelectorAll('.view-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            // Update active state
            document.querySelectorAll('.view-toggle').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const view = this.dataset.view;
            const grid = document.getElementById('productsGrid');
            
            // Add transition class
            grid.style.transition = 'all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            
            if (view === 'list') {
                grid.classList.add('list-view');
                showEnhancedNotification('Switched to list view', 'info');
            } else {
                grid.classList.remove('list-view');
                showEnhancedNotification('Switched to grid view', 'info');
            }
            
            // Animate cards
            const cards = grid.querySelectorAll('.product-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.animation = view === 'list' ? 
                        'morphToList 0.3s ease forwards' : 
                        'morphToGrid 0.3s ease forwards';
                }, index * 30);
            });
            
            // Add ripple effect
            createRippleEffect(this);
        });
    });
    
    // Filter form functionality with better UX
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        const inputs = filterForm.querySelectorAll('input, select');
        let filterTimeout;
        
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                clearTimeout(filterTimeout);
                
                // Add loading state
                this.disabled = true;
                this.style.opacity = '0.7';
                
                // Show loading notification
                showEnhancedNotification('Applying filters...', 'info');
                
                // Debounce form submission
                filterTimeout = setTimeout(() => {
                    filterForm.submit();
                }, 300);
            });
        });
    }
    
    // Enhanced product interactions
    initializeProductInteractions();
    
    // Initialize lazy loading
    initializeLazyLoading();
});

// Enhanced product card interactions
function initializeProductInteractions() {
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach((card, index) => {
        // Staggered entrance animation
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('card-fade-in');
        
        // Enhanced hover effects
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
            this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.15)';
            
            // Animate product image
            const img = this.querySelector('.product-image');
            if (img) {
                img.style.transform = 'scale(1.05)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
            
            const img = this.querySelector('.product-image');
            if (img) {
                img.style.transform = 'scale(1)';
            }
        });
    });
}

// Initialize lazy loading for better performance
function initializeLazyLoading() {
    const images = document.querySelectorAll('.product-image[data-src]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    img.classList.add('loaded');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => {
            img.classList.add('lazy');
            imageObserver.observe(img);
        });
    }
}

// Quick View functionality
window.quickView = function(productId) {
    showEnhancedNotification('Opening quick view...', 'info');
    
    // Create modal backdrop
    const backdrop = document.createElement('div');
    backdrop.className = 'quick-view-backdrop';
    backdrop.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(8px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;
    
    // Create modal content
    const modal = document.createElement('div');
    modal.className = 'quick-view-modal';
    modal.style.cssText = `
        background: white;
        border-radius: 15px;
        padding: 2rem;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        transform: scale(0.8);
        transition: transform 0.3s ease;
    `;
    
    modal.innerHTML = `
        <div class="quick-view-header">
            <h3>Quick View - Product ${productId}</h3>
            <button onclick="closeQuickView()" class="close-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="quick-view-content">
            <p>Quick view functionality would load product details here...</p>
            <div class="quick-view-actions">
                <button class="btn btn-primary" onclick="addToCart(${productId})">
                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                </button>
                <a href="/product/${productId}" class="btn btn-outline">
                    <i class="fas fa-eye me-2"></i>View Full Details
                </a>
            </div>
        </div>
    `;
    
    backdrop.appendChild(modal);
    document.body.appendChild(backdrop);
    
    // Animate in
    setTimeout(() => {
        backdrop.style.opacity = '1';
        modal.style.transform = 'scale(1)';
    }, 50);
    
    // Close on backdrop click
    backdrop.addEventListener('click', function(e) {
        if (e.target === backdrop) {
            closeQuickView();
        }
    });
    
    // Store reference for closing
    window.currentQuickView = backdrop;
};

// Close quick view
window.closeQuickView = function() {
    const backdrop = window.currentQuickView;
    if (backdrop) {
        const modal = backdrop.querySelector('.quick-view-modal');
        backdrop.style.opacity = '0';
        modal.style.transform = 'scale(0.8)';
        
        setTimeout(() => {
            backdrop.remove();
            window.currentQuickView = null;
        }, 300);
    }
};

// Enhanced wishlist functionality
window.addToWishlist = function(productId) {
    const btn = event.target.closest('.wishlist-btn');
    const icon = btn.querySelector('i');
    
    // Toggle wishlist state
    const isInWishlist = icon.classList.contains('fas');
    
    if (isInWishlist) {
        icon.classList.remove('fas');
        icon.classList.add('far');
        btn.style.color = '#64748b';
        showEnhancedNotification('Removed from wishlist', 'info');
    } else {
        icon.classList.remove('far');
        icon.classList.add('fas');
        btn.style.color = '#e53e3e';
        showEnhancedNotification('Added to wishlist!', 'success');
        
        // Add heart animation
        createHeartAnimation(btn);
    }
};

// Create heart animation effect
function createHeartAnimation(element) {
    const heart = document.createElement('div');
    heart.innerHTML = '<i class="fas fa-heart"></i>';
    heart.style.cssText = `
        position: absolute;
        color: #e53e3e;
        font-size: 1.5rem;
        pointer-events: none;
        animation: floatHeart 1s ease-out forwards;
        z-index: 1000;
    `;
    
    const rect = element.getBoundingClientRect();
    heart.style.left = rect.left + rect.width / 2 + 'px';
    heart.style.top = rect.top + 'px';
    
    document.body.appendChild(heart);
    
    setTimeout(() => heart.remove(), 1000);
}

// Enhanced add to cart with better feedback
document.addEventListener('submit', function(e) {
    if (e.target.classList.contains('add-to-cart-form')) {
        e.preventDefault();
        
        const form = e.target;
        const button = form.querySelector('.add-to-cart-btn');
        const originalText = button.innerHTML;
        const productId = form.querySelector('input[name="product_id"]').value;
        
        // Add loading state
        button.classList.add('loading');
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
        
        // Create floating cart animation
        createFloatingCartIcon(button);
        
        // Simulate API call
        setTimeout(() => {
            // Success state
            button.innerHTML = '<i class="fas fa-check me-2"></i>Added!';
            button.style.background = 'linear-gradient(135deg, #10b981, #059669)';
            
            // Update cart badge
            updateCartBadgeAnimated();
            
            // Show success notification
            showEnhancedNotification('Product added to cart!', 'success');
            
            // Reset button after delay
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                button.style.background = 'linear-gradient(135deg, #4f46e5, #7c3aed)';
                button.classList.remove('loading');
                
                // Actually submit the form
                form.submit();
            }, 2000);
        }, 1000);
    }
});

// Create floating cart icon animation
function createFloatingCartIcon(btn) {
    const cartIcon = document.createElement('div');
    cartIcon.innerHTML = '<i class="fas fa-shopping-cart"></i>';
    cartIcon.style.cssText = `
        position: fixed;
        z-index: 9999;
        color: #4f46e5;
        font-size: 1.5rem;
        pointer-events: none;
        animation: floatToCart 1s ease-out forwards;
    `;
    
    const btnRect = btn.getBoundingClientRect();
    cartIcon.style.left = btnRect.left + btnRect.width / 2 + 'px';
    cartIcon.style.top = btnRect.top + 'px';
    
    document.body.appendChild(cartIcon);
    
    setTimeout(() => cartIcon.remove(), 1000);
}

// Update cart badge with animation
function updateCartBadgeAnimated() {
    const cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        cartBadge.style.animation = 'cartBounce 0.6s ease';
        
        const currentCount = parseInt(cartBadge.textContent) || 0;
        cartBadge.textContent = currentCount + 1;
        
        setTimeout(() => {
            cartBadge.style.animation = '';
        }, 600);
    }
}

// Create ripple effect for buttons
function createRippleEffect(element) {
    const ripple = document.createElement('div');
    const rect = element.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    
    ripple.style.cssText = `
        position: absolute;
        border-radius: 50%;
        background: rgba(79, 70, 229, 0.3);
        transform: scale(0);
        animation: ripple 0.6s ease-out;
        pointer-events: none;
        width: ${size}px;
        height: ${size}px;
        left: 50%;
        top: 50%;
        margin-left: -${size/2}px;
        margin-top: -${size/2}px;
    `;
    
    element.style.position = 'relative';
    element.appendChild(ripple);
    
    setTimeout(() => ripple.remove(), 600);
}

// Clear all filters function
window.clearAllFilters = function() {
    showEnhancedNotification('Clearing all filters...', 'info');
    setTimeout(() => {
        window.location.href = '{{ route("shop") }}';
    }, 500);
};

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K for search focus
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.focus();
            showEnhancedNotification('Search focused', 'info');
        }
    }
    
    // Ctrl + Shift + F for filter toggle
    if (e.ctrlKey && e.shiftKey && e.key === 'F') {
        e.preventDefault();
        if (window.desktopFilterSidebar) {
            window.desktopFilterSidebar.toggle();
        }
    }
    
    // ESC to close any open modals
    if (e.key === 'Escape') {
        if (window.currentQuickView) {
            closeQuickView();
        }
    }
});

// Performance optimization: Debounced scroll handler
let isScrolling = false;
window.addEventListener('scroll', function() {
    if (!isScrolling) {
        window.requestAnimationFrame(function() {
            handleScroll();
            isScrolling = false;
        });
        isScrolling = true;
    }
});

function handleScroll() {
    const scrollY = window.scrollY;
    
    // Parallax effect for hero
    const hero = document.querySelector('.shop-hero');
    if (hero) {
        hero.style.transform = `translateY(${scrollY * 0.3}px)`;
    }
    
    // Reveal cards on scroll
    const cards = document.querySelectorAll('.product-card:not(.revealed)');
    cards.forEach(card => {
        const rect = card.getBoundingClientRect();
        if (rect.top < window.innerHeight - 100) {
            card.classList.add('revealed');
            card.style.animation = 'revealCard 0.6s ease forwards';
        }
    });
}

// Enhanced CSS animations
const enhancedCSS = `
@keyframes floatToCart {
    0% { transform: scale(1) translateY(0); opacity: 1; }
    50% { transform: scale(1.2) translateY(-20px); }
    100% { transform: scale(0.5) translateY(-60px) translateX(200px); opacity: 0; }
}

@keyframes floatHeart {
    0% { transform: scale(1) translateY(0); opacity: 1; }
    100% { transform: scale(2) translateY(-50px); opacity: 0; }
}

@keyframes cartBounce {
    0%, 20%, 53%, 80%, 100% { transform: scale(1); }
    40%, 43% { transform: scale(1.3); }
    70% { transform: scale(1.1); }
    90% { transform: scale(1.05); }
}

@keyframes ripple {
    to { transform: scale(4); opacity: 0; }
}

@keyframes morphToList {
    from { transform: scale(1); }
    to { transform: scale(1.02); }
}

@keyframes morphToGrid {
    from { transform: scale(1.02); }
    to { transform: scale(1); }
}

@keyframes revealCard {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes card-fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.card-fade-in {
    animation: card-fade-in 0.6s ease forwards;
}

.product-image.lazy {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-image.loaded {
    opacity: 1;
}

.no-products {
    text-align: center;
    padding: 4rem 2rem;
    color: #718096;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.no-products-icon {
    font-size: 4rem;
    margin-bottom: 2rem;
    color: #cbd5e0;
    animation: pulse 2s infinite;
}

.no-products h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #4a5568;
    font-weight: 700;
}

.no-products p {
    margin-bottom: 2rem;
    font-size: 1rem;
    line-height: 1.6;
}

.no-products-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.quick-view-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.quick-view-header h3 {
    margin: 0;
    color: #2d3748;
    font-weight: 700;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #a0aec0;
    cursor: pointer;
    transition: color 0.3s ease;
}

.close-btn:hover {
    color: #e53e3e;
}

.quick-view-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    justify-content: center;
}

.dense-grid {
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) !important;
    gap: 1rem !important;
}

.dense-grid .product-card {
    transform: scale(0.95);
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
`;

// Inject enhanced CSS
const style = document.createElement('style');
style.textContent = enhancedCSS;
document.head.appendChild(style);

</script>
@endpush