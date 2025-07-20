@extends('layout.app')

@section('title', 'Shop - TekSouq')

@push('styles')
<link href="{{ asset('css/shop.css') }}" rel="stylesheet">
<link href="{{ asset('css/right-sidebar-filter.css') }}" rel="stylesheet">
<link href="{{ asset('css/enhanced-shop-header.css') }}" rel="stylesheet">
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
@if(request('view') != 'categories' && isset($products) && method_exists($products, 'hasPages') && $products->hasPages())
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
{{-- @include('shop.parts.mobile-filter-button') --}}

<!-- Mobile Filter Panel (Mobile Only) -->
@include('shop.parts.mobile-filter-panel')

@endsection

// استبدل الكود في @push('scripts') بهذا:

@push('scripts')
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/mobile-filter.js') }}"></script>
<script src="{{ asset('js/unified-filter-controller.js') }}"></script>
<script src="{{ asset('js/shop-enhancements.js') }}"></script>

<script>
console.log('🛒 Final Cart System Loading...');

document.addEventListener('DOMContentLoaded', function() {
    
    // Find cart icon
    const cartIcon = document.querySelector('a[href*="cart"]');
    let cartBadge = null;
    
    if (cartIcon) {
        cartBadge = cartIcon.querySelector('.cart-badge');
        
        if (!cartBadge) {
            cartBadge = document.createElement('span');
            cartBadge.className = 'cart-badge';
            cartBadge.style.cssText = `
                position: absolute !important;
                top: -8px !important;
                right: -8px !important;
                background: #ef4444 !important;
                color: white !important;
                border-radius: 50% !important;
                width: 20px !important;
                height: 20px !important;
                font-size: 0.75rem !important;
                font-weight: 700 !important;
                display: none !important;
                align-items: center !important;
                justify-content: center !important;
                z-index: 1000 !important;
                transition: all 0.3s ease !important;
            `;
            cartIcon.style.position = 'relative';
            cartIcon.appendChild(cartBadge);
            console.log('✅ Badge created');
        }
    }
    
    // Enhanced update badge function with retry mechanism
    async function updateBadge(maxRetries = 3) {
        if (!cartBadge) return;
        
        for (let attempt = 1; attempt <= maxRetries; attempt++) {
            try {
                console.log(`🔄 Updating badge (attempt ${attempt}/${maxRetries})...`);
                
                const response = await fetch('/cart/count?' + Date.now(), {
                    headers: {
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                const data = await response.json();
                const count = data.total_quantity || 0;
                
                console.log(`📊 Cart count: ${count} (attempt ${attempt})`);
                
                // Update badge with animation
                const oldCount = parseInt(cartBadge.textContent) || 0;
                cartBadge.textContent = count;
                
                if (count > 0) {
                    cartBadge.style.display = 'flex';
                    
                    // Add bounce animation if count increased
                    if (count > oldCount) {
                        cartBadge.style.transform = 'scale(1.3)';
                        setTimeout(() => {
                            cartBadge.style.transform = 'scale(1)';
                        }, 200);
                    }
                } else {
                    cartBadge.style.display = 'none';
                }
                
                console.log(`✅ Badge updated successfully: ${count}`);
                return; // Success, exit retry loop
                
            } catch (error) {
                console.warn(`❌ Badge update attempt ${attempt} failed:`, error);
                
                if (attempt < maxRetries) {
                    // Wait before retry
                    await new Promise(resolve => setTimeout(resolve, 500 * attempt));
                } else {
                    console.error('❌ All badge update attempts failed');
                }
            }
        }
    }
    
    // Initial update
    updateBadge();
    
    // Find and bind forms
    function findAndBindForms() {
        let forms = document.querySelectorAll('.add-to-cart-form');
        console.log('Found forms with class:', forms.length);
        
        if (forms.length === 0) {
            const productCards = document.querySelectorAll('.product-card');
            console.log('Found product cards:', productCards.length);
            
            productCards.forEach(card => {
                const productId = extractProductIdFromCard(card);
                if (productId) {
                    createCartFormForCard(card, productId);
                }
            });
            
            forms = document.querySelectorAll('.add-to-cart-form');
            console.log('Forms after creation:', forms.length);
        }
        
        // Remove existing listeners
        forms.forEach(form => {
            const newForm = form.cloneNode(true);
            form.parentNode.replaceChild(newForm, form);
        });
        
        // Re-select and bind
        const finalForms = document.querySelectorAll('.add-to-cart-form');
        
        finalForms.forEach((form, index) => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                console.log(`🛒 Form ${index + 1} submitted`);
                
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                
                // Loading state
                button.innerHTML = '⏳ Adding...';
                button.disabled = true;
                button.style.opacity = '0.7';
                
                try {
                    const formData = new FormData(this);
                    console.log('📤 Product ID:', formData.get('product_id'));
                    
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        console.log('✅ Product added successfully');
                        
                        // Success state
                        button.innerHTML = '✅ Added!';
                        button.style.background = '#10b981';
                        button.style.opacity = '1';
                        
                        // Update badge with multiple attempts and delays
                        setTimeout(() => updateBadge(3), 200);
                        setTimeout(() => updateBadge(2), 800);
                        setTimeout(() => updateBadge(1), 1500);
                        
                        // Show success notification
                        showSuccessNotification('Product added to cart!');
                        
                        // Reset button
                        setTimeout(() => {
                            button.innerHTML = originalText;
                            button.style.background = '';
                            button.style.opacity = '1';
                            button.disabled = false;
                        }, 2000);
                        
                    } else {
                        throw new Error(`HTTP ${response.status}`);
                    }
                } catch (error) {
                    console.error('❌ Failed to add product:', error);
                    
                    button.innerHTML = '❌ Error';
                    button.style.background = '#ef4444';
                    
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.style.background = '';
                        button.style.opacity = '1';
                        button.disabled = false;
                    }, 2000);
                }
            });
        });
        
        console.log(`✅ Bound ${finalForms.length} forms`);
    }
    
    // Helper functions
    function extractProductIdFromCard(card) {
        const link = card.querySelector('a[href*="/product/"]');
        if (link) {
            const match = link.href.match(/\/product\/(\d+)/);
            if (match) return match[1];
        }
        
        const titleLink = card.querySelector('.product-title a');
        if (titleLink) {
            const match = titleLink.href.match(/\/product\/(\d+)/);
            if (match) return match[1];
        }
        
        return null;
    }
    
    function createCartFormForCard(card, productId) {
        const existingForm = card.querySelector('.add-to-cart-form');
        if (existingForm) return;
        
        const button = card.querySelector('button:contains("Add to Cart"), .add-to-cart-btn') ||
                      card.querySelector('button[class*="add"]');
        
        if (button && !button.closest('form')) {
            const form = document.createElement('form');
            form.action = '/cart/add';
            form.method = 'POST';
            form.className = 'add-to-cart-form';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const productInput = document.createElement('input');
            productInput.type = 'hidden';
            productInput.name = 'product_id';
            productInput.value = productId;
            
            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = 'quantity';
            quantityInput.value = '1';
            
            button.parentNode.insertBefore(form, button);
            form.appendChild(csrfInput);
            form.appendChild(productInput);
            form.appendChild(quantityInput);
            form.appendChild(button);
            
            button.type = 'submit';
            
            console.log(`Created form for product ${productId}`);
        }
    }
    
    function showSuccessNotification(message) {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed !important;
            top: 100px !important;
            right: 20px !important;
            background: #10b981 !important;
            color: white !important;
            padding: 1rem 1.5rem !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            z-index: 10000 !important;
            transform: translateX(100%) !important;
            transition: transform 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3) !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
        `;
        
        notification.innerHTML = `
            <i class="fas fa-check-circle"></i>
            ${message}
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // Manual test function
    window.testAddToCart = function() {
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: 'product_id=16&quantity=1'
        })
        .then(response => response.json())
        .then(data => {
            console.log('🧪 Manual test result:', data);
            updateBadge(3);
        })
        .catch(error => console.error('🧪 Manual test failed:', error));
    };
    
    // Listen for page visibility changes
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            setTimeout(() => updateBadge(), 500);
        }
    });
    
    // Periodic update (every 30 seconds)
    setInterval(() => updateBadge(), 30000);
    
    // Initialize after short delay
    setTimeout(findAndBindForms, 1000);
    
    console.log('✅ Final cart system loaded');
    console.log('🧪 Type testAddToCart() to test manually');
});

// Helper for jQuery :contains selector if needed
if (typeof jQuery !== 'undefined') {
    jQuery.expr[':'].contains = function(a, i, m) {
        return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };
}
</script>
@endpush