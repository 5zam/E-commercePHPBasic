// Shop Enhancements & Performance Optimizations
class ShopEnhancements {
    constructor() {
        this.init();
    }
    
    init() {
        this.enhanceProductCards();
        this.addLazyLoading();
        this.enhanceViewToggle();
        this.addSearchEnhancements();
        this.addScrollEnhancements();
        this.addKeyboardNavigation();
        this.addTouchGestures();
        this.optimizePerformance();
        this.initializeSortFunctionality();
    }
    
    // Sort functionality - moved here from other files
    initializeSortFunctionality() {
        // Sort functionality
        window.updateSort = function(sortValue) {
            console.log('Sorting by:', sortValue);
            
            const url = new URL(window.location);
            url.searchParams.set('sort', sortValue);
            
            // Show loading feedback
            const sortSelect = document.querySelector('.shop-sort');
            if (sortSelect) {
                sortSelect.disabled = true;
                sortSelect.style.opacity = '0.7';
                
                // Add loading indicator
                const originalText = sortSelect.options[sortSelect.selectedIndex].text;
                sortSelect.options[sortSelect.selectedIndex].text = 'Loading...';
            }
            
            // Show notification
            if (typeof showEnhancedNotification === 'function') {
                showEnhancedNotification('Sorting products...', 'info');
            }
            
            // Navigate to new URL
            window.location.href = url.toString();
        };
        
        // Category sort functionality
        window.updateCategorySort = function(sortValue) {
            console.log('Categories sorted by:', sortValue);
            // Add actual sorting logic here
        };
        
        // Alternative: Direct event listener on sort dropdown
        const shopSort = document.querySelector('.shop-sort');
        if (shopSort) {
            shopSort.addEventListener('change', function() {
                console.log('Sort changed to:', this.value);
                updateSort(this.value);
            });
        }
    }
    
    // Enhanced Product Cards with Micro-interactions
    enhanceProductCards() {
        const productCards = document.querySelectorAll('.product-card');
        
        productCards.forEach((card, index) => {
            // Staggered animation on load
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('card-fade-in');
            
            // Enhanced hover effects
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-8px) scale(1.02)';
                card.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.15)';
                
                // Animate product image
                const img = card.querySelector('.product-image');
                if (img) {
                    img.style.transform = 'scale(1.1)';
                }
                
                // Show overlay with smooth transition
                const overlay = card.querySelector('.product-overlay');
                if (overlay) {
                    overlay.style.opacity = '1';
                    overlay.style.visibility = 'visible';
                }
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
                card.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
                
                const img = card.querySelector('.product-image');
                if (img) {
                    img.style.transform = 'scale(1)';
                }
                
                const overlay = card.querySelector('.product-overlay');
                if (overlay) {
                    overlay.style.opacity = '0';
                    overlay.style.visibility = 'hidden';
                }
            });
            
            // Add to cart with enhanced feedback
            const addToCartBtn = card.querySelector('.add-to-cart-btn');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', (e) => {
                    this.enhancedAddToCart(e, card);
                });
            }
        });
    }
    
    // Enhanced Add to Cart with Animation
    enhancedAddToCart(e, card) {
        e.preventDefault();
        
        const btn = e.target.closest('.add-to-cart-btn');
        const originalText = btn.innerHTML;
        
        // Button loading state
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
        btn.disabled = true;
        btn.style.background = 'linear-gradient(135deg, #3730a3, #6d28d9)';
        
        // Create floating animation
        this.createFloatingCartIcon(btn);
        
        // Simulate API call
        setTimeout(() => {
            // Success state
            btn.innerHTML = '<i class="fas fa-check me-2"></i>Added!';
            btn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
            
            // Update cart badge with animation
            this.updateCartBadgeAnimated();
            
            // Show success notification
            this.showEnhancedNotification('Product added to cart!', 'success');
            
            // Reset button after delay
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.style.background = 'linear-gradient(135deg, #4f46e5, #7c3aed)';
            }, 2000);
        }, 1000);
    }
    
    // Floating Cart Icon Animation
    createFloatingCartIcon(btn) {
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
        cartIcon.style.left = btnRect.left + 'px';
        cartIcon.style.top = btnRect.top + 'px';
        
        document.body.appendChild(cartIcon);
        
        // Remove after animation
        setTimeout(() => cartIcon.remove(), 1000);
    }
    
    // Animated Cart Badge Update
    updateCartBadgeAnimated() {
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
    
    // Enhanced View Toggle with Smooth Transitions - FIXED FOR BOTH LINKS AND BUTTONS
    enhanceViewToggle() {
        const viewToggles = document.querySelectorAll('.view-toggle');
        const productsGrid = document.getElementById('productsGrid');
        
        viewToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                const isButton = this.tagName === 'BUTTON';
                const isLink = this.tagName === 'A';
                
                // Only prevent default for buttons (categories), let links work normally
                if (isButton) {
                    e.preventDefault();
                    
                    // Prevent multiple rapid clicks
                    if (this.classList.contains('processing')) return;
                    this.classList.add('processing');
                    
                    // Update active state for buttons only
                    viewToggles.forEach(t => {
                        if (t.tagName === 'BUTTON') {
                            t.classList.remove('active');
                        }
                    });
                    this.classList.add('active');
                    
                    const view = this.dataset.view;
                    
                    if (productsGrid) {
                        // Add smooth transition to prevent flicker
                        productsGrid.style.transition = 'opacity 0.2s ease';
                        productsGrid.style.opacity = '0.8';
                        
                        setTimeout(() => {
                            if (view === 'list') {
                                productsGrid.classList.add('list-view');
                            } else {
                                productsGrid.classList.remove('list-view');
                            }
                            
                            // Restore opacity
                            productsGrid.style.opacity = '1';
                            
                            // Show notification
                            this.showEnhancedNotification(`Switched to ${view} view`, 'info');
                            
                            // Remove processing state
                            setTimeout(() => {
                                this.classList.remove('processing');
                            }, 100);
                        }, 150);
                        
                        // Add ripple effect for buttons
                        this.createRipple(this);
                    }
                } else if (isLink) {
                    // For links (products), just add smooth transition effect
                    if (productsGrid) {
                        const view = this.dataset.view;
                        this.showEnhancedNotification(`Switching to ${view} view...`, 'info');
                        
                        // Add smooth loading effect
                        productsGrid.style.transition = 'opacity 0.2s ease';
                        productsGrid.style.opacity = '0.8';
                    }
                    // Let the link navigate normally (don't prevent default)
                }
            }.bind(this));
        });
    }
    
    // Create Ripple Effect
    createRipple(element) {
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
    
    // Enhanced Search with Real-time Suggestions
    addSearchEnhancements() {
        const searchInputs = document.querySelectorAll('.search-input, .mobile-search-input');
        
        searchInputs.forEach(input => {
            let searchTimeout;
            
            input.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                
                searchTimeout = setTimeout(() => {
                    this.showSearchSuggestions(e.target.value, input);
                }, 300);
            });
            
            input.addEventListener('focus', () => {
                input.parentNode.classList.add('search-focused');
            });
            
            input.addEventListener('blur', () => {
                setTimeout(() => {
                    input.parentNode.classList.remove('search-focused');
                    this.hideSearchSuggestions();
                }, 200);
            });
        });
    }
    
    // Show Search Suggestions
    showSearchSuggestions(query, input) {
        if (query.length < 2) {
            this.hideSearchSuggestions();
            return;
        }
        
        // Mock suggestions (in real app, this would be an API call)
        const suggestions = [
            'Smartwatch',
            'Smart Ring',
            'Wireless Earbuds',
            'Fitness Tracker',
            'Gaming Mouse'
        ].filter(item => item.toLowerCase().includes(query.toLowerCase()));
        
        if (suggestions.length > 0) {
            this.createSuggestionsDropdown(suggestions, input);
        }
    }
    
    // Create Suggestions Dropdown
    createSuggestionsDropdown(suggestions, input) {
        this.hideSearchSuggestions();
        
        const dropdown = document.createElement('div');
        dropdown.className = 'search-suggestions';
        dropdown.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
            animation: dropdownSlide 0.2s ease;
        `;
        
        suggestions.forEach((suggestion, index) => {
            const item = document.createElement('div');
            item.className = 'suggestion-item';
            item.textContent = suggestion;
            item.style.cssText = `
                padding: 0.75rem 1rem;
                cursor: pointer;
                transition: background 0.2s ease;
                border-bottom: 1px solid #f1f5f9;
            `;
            
            item.addEventListener('mouseenter', () => {
                item.style.background = '#f8fafc';
            });
            
            item.addEventListener('mouseleave', () => {
                item.style.background = 'white';
            });
            
            item.addEventListener('click', () => {
                input.value = suggestion;
                this.hideSearchSuggestions();
            });
            
            dropdown.appendChild(item);
        });
        
        input.parentNode.style.position = 'relative';
        input.parentNode.appendChild(dropdown);
    }
    
    // Hide Search Suggestions
    hideSearchSuggestions() {
        const existing = document.querySelectorAll('.search-suggestions');
        existing.forEach(dropdown => dropdown.remove());
    }
    
    // Scroll Enhancements
    addScrollEnhancements() {
        let isScrolling = false;
        
        window.addEventListener('scroll', () => {
            if (!isScrolling) {
                window.requestAnimationFrame(() => {
                    this.handleScroll();
                    isScrolling = false;
                });
                isScrolling = true;
            }
        });
    }
    
    handleScroll() {
        const scrollY = window.scrollY;
        
        // Parallax effect for hero section
        const hero = document.querySelector('.shop-hero');
        if (hero) {
            hero.style.transform = `translateY(${scrollY * 0.5}px)`;
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
    
    // Keyboard Navigation
    addKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            // Focus search on Ctrl+K or Cmd+K
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('.search-input, .mobile-search-input');
                if (searchInput) {
                    searchInput.focus();
                    this.showEnhancedNotification('Search focused - Start typing!', 'info');
                }
            }
            
            // Clear filters on Ctrl+Shift+C
            if (e.ctrlKey && e.shiftKey && e.key === 'C') {
                e.preventDefault();
                if (window.mobileFilter) {
                    window.mobileFilter.clearAllFilters();
                }
                this.showEnhancedNotification('All filters cleared!', 'info');
            }
        });
    }
    
    // Touch Gestures for Mobile
    addTouchGestures() {
        if ('ontouchstart' in window) {
            const productsGrid = document.getElementById('productsGrid');
            let startX, startY;
            
            productsGrid.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            });
            
            productsGrid.addEventListener('touchmove', (e) => {
                if (!startX || !startY) return;
                
                const currentX = e.touches[0].clientX;
                const currentY = e.touches[0].clientY;
                
                const diffX = startX - currentX;
                const diffY = startY - currentY;
                
                // Horizontal swipe to toggle view
                if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                    const viewToggles = document.querySelectorAll('.view-toggle');
                    const activeToggle = document.querySelector('.view-toggle.active');
                    
                    if (diffX > 0) { // Swipe left -> List view
                        const listToggle = document.querySelector('[data-view="list"]');
                        if (listToggle && !listToggle.classList.contains('active')) {
                            listToggle.click();
                        }
                    } else { // Swipe right -> Grid view
                        const gridToggle = document.querySelector('[data-view="grid"]');
                        if (gridToggle && !gridToggle.classList.contains('active')) {
                            gridToggle.click();
                        }
                    }
                    
                    startX = null;
                    startY = null;
                }
            });
        }
    }
    
    // Lazy Loading for Images
    addLazyLoading() {
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
        } else {
            // Fallback for older browsers
            images.forEach(img => {
                img.src = img.dataset.src;
            });
        }
    }
    
    // Performance Optimizations
    optimizePerformance() {
        // Preload critical resources
        this.preloadCriticalResources();
        
        // Debounce resize events
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.handleResize();
            }, 250);
        });
        
        // Optimize scroll performance
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => {
                this.optimizeScrollPerformance();
            });
        }
    }
    
    preloadCriticalResources() {
        const criticalImages = [
            '/images/background.png',
            '/images/logo.png'
        ];
        
        criticalImages.forEach(src => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = src;
            document.head.appendChild(link);
        });
    }
    
    optimizeScrollPerformance() {
        // Add CSS containment for better performance
        const cards = document.querySelectorAll('.product-card');
        cards.forEach(card => {
            card.style.contain = 'layout style paint';
        });
    }
    
    handleResize() {
        // Re-initialize mobile filter if needed
        if (window.innerWidth < 992 && !window.mobileFilter) {
            window.mobileFilter = new MobileFilter();
        } else if (window.innerWidth >= 992 && window.mobileFilter && window.mobileFilter.isOpen) {
            window.mobileFilter.closePanel();
        }
    }
    
    // Enhanced Notifications - MAIN NOTIFICATION FUNCTION FOR SHOP
    showEnhancedNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.enhanced-notification');
        existingNotifications.forEach(notification => notification.remove());
        
        const notification = document.createElement('div');
        notification.className = `enhanced-notification notification-${type}`;
        
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        
        const colors = {
            success: 'linear-gradient(135deg, #10b981, #059669)',
            error: 'linear-gradient(135deg, #ef4444, #dc2626)',
            warning: 'linear-gradient(135deg, #f59e0b, #d97706)',
            info: 'linear-gradient(135deg, #3b82f6, #2563eb)'
        };
        
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${icons[type]} notification-icon"></i>
                <span class="notification-message">${message}</span>
            </div>
            <div class="notification-progress"></div>
        `;
        
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: ${colors[type]};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            min-width: 300px;
            max-width: 400px;
            backdrop-filter: blur(10px);
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Progress bar animation
        const progress = notification.querySelector('.notification-progress');
        progress.style.cssText = `
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            width: 100%;
            border-radius: 0 0 12px 12px;
            animation: notificationProgress 4s linear forwards;
        `;
        
        // Remove after delay
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }
}

// Global Shop Functions
window.quickView = function(productId) {
    if (window.shopEnhancements) {
        window.shopEnhancements.showEnhancedNotification('Opening quick view...', 'info');
    }
    
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
        if (window.shopEnhancements) {
            window.shopEnhancements.showEnhancedNotification('Removed from wishlist', 'info');
        }
    } else {
        icon.classList.remove('far');
        icon.classList.add('fas');
        btn.style.color = '#e53e3e';
        if (window.shopEnhancements) {
            window.shopEnhancements.showEnhancedNotification('Added to wishlist!', 'success');
        }
        
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

// Clear all filters function
window.clearAllFilters = function() {
    if (window.shopEnhancements) {
        window.shopEnhancements.showEnhancedNotification('Clearing all filters...', 'info');
    }
    setTimeout(() => {
        window.location.href = window.location.pathname;
    }, 500);
};

// CSS for animations and enhancements
const enhancementCSS = `
@keyframes floatToCart {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.2) translateY(-20px);
    }
    100% {
        transform: scale(0.5) translateY(-50px) translateX(200px);
        opacity: 0;
    }
}

@keyframes floatHeart {
    0% { transform: scale(1) translateY(0); opacity: 1; }
    100% { transform: scale(2) translateY(-50px); opacity: 0; }
}

@keyframes cartBounce {
    0%, 20%, 53%, 80%, 100% {
        transform: scale(1);
    }
    40%, 43% {
        transform: scale(1.3);
    }
    70% {
        transform: scale(1.1);
    }
    90% {
        transform: scale(1.05);
    }
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

@keyframes dropdownSlide {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes revealCard {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes card-fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes notificationProgress {
    from { width: 100%; }
    to { width: 0%; }
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

.search-focused {
    transform: scale(1.02);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
}

.enhanced-notification {
    backdrop-filter: blur(10px);
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.notification-icon {
    font-size: 1.1rem;
}

/* Performance optimizations */
.product-card {
    will-change: transform;
    backface-visibility: hidden;
    transform: translateZ(0);
}

.product-image {
    will-change: transform;
    backface-visibility: hidden;
}

/* Processing state to prevent double clicks */
.view-toggle.processing {
    pointer-events: none !important;
    opacity: 0.7 !important;
}

.products-grid {
    transition: opacity 0.2s ease !important;
}

/* Quick view styles */
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

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
`;

// Inject enhancement CSS
const enhancementStyle = document.createElement('style');
enhancementStyle.textContent = enhancementCSS;
document.head.appendChild(enhancementStyle);

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.shopEnhancements = new ShopEnhancements();
    
    // Make showEnhancedNotification globally available
    window.showEnhancedNotification = function(message, type) {
        if (window.shopEnhancements) {
            window.shopEnhancements.showEnhancedNotification(message, type);
        }
    };
});

// Export for external use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ShopEnhancements;
}