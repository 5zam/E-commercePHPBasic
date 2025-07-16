// TekSouq Global JavaScript Functions

document.addEventListener('DOMContentLoaded', function() {
    
    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    const backToTopBtn = document.getElementById('backToTop');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
            if (backToTopBtn) {
                backToTopBtn.classList.add('show');
            }
        } else {
            navbar.classList.remove('scrolled');
            if (backToTopBtn) {
                backToTopBtn.classList.remove('show');
            }
        }
    });
    
    // Back to top functionality
    if (backToTopBtn) {
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Newsletter form handling
    const newsletterForms = document.querySelectorAll('.newsletter-form, .footer-newsletter');
    newsletterForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            const button = this.querySelector('button[type="submit"]');
            
            if (email && isValidEmail(email)) {
                // Add loading state
                button.classList.add('loading');
                button.disabled = true;
                
                // Simulate API call
                setTimeout(() => {
                    showNotification('Thank you for subscribing to TekSouq updates!', 'success');
                    this.reset();
                    button.classList.remove('loading');
                    button.disabled = false;
                }, 1500);
            } else {
                showNotification('Please enter a valid email address', 'error');
            }
        });
    });
    
    // Search functionality
    const searchForm = document.querySelector('.search-form');
    const searchModal = document.getElementById('searchModal');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchTerm = this.querySelector('.search-input').value.trim();
            
            if (searchTerm) {
                // Close modal
                if (searchModal) {
                    bootstrap.Modal.getInstance(searchModal).hide();
                }
                
                // Simulate search
                showNotification(`Searching for "${searchTerm}"...`, 'info');
                
                // Here you would typically redirect to search results
                // window.location.href = `/search?q=${encodeURIComponent(searchTerm)}`;
            }
        });
    }
    
    // Search suggestion tags
    const suggestionTags = document.querySelectorAll('.tag');
    suggestionTags.forEach(tag => {
        tag.addEventListener('click', function() {
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.value = this.textContent;
                searchInput.focus();
            }
        });
    });
    
    // Reveal animations on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
            }
        });
    }, observerOptions);
    
    // Observe elements with reveal class
    document.querySelectorAll('.reveal').forEach(el => {
        observer.observe(el);
    });
    
    // Add reveal class to sections for animation
    const sections = document.querySelectorAll('.features-section, .products-section, .newsletter-section');
    sections.forEach(section => {
        section.classList.add('reveal');
    });
    
    // Product card hover effects
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Feature card animations
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('animate-fade-in-up');
    });
    
    // Mobile menu handling
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function() {
            // Add animation class
            this.classList.toggle('active');
        });
        
        // Close mobile menu when clicking on links
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    navbarCollapse.classList.remove('show');
                    navbarToggler.classList.remove('active');
                }
            });
        });
    }
    
    // Cart functionality
    const cartBadge = document.querySelector('.cart-badge');
    let cartCount = parseInt(localStorage.getItem('cartCount')) || 0;
    
    if (cartBadge) {
        cartBadge.textContent = cartCount;
    }
    
    // Update cart count
    window.updateCartCount = function(increment = 1) {
        cartCount += increment;
        localStorage.setItem('cartCount', cartCount);
        if (cartBadge) {
            cartBadge.textContent = cartCount;
            cartBadge.style.animation = 'pulse 0.5s ease';
            setTimeout(() => {
                cartBadge.style.animation = '';
            }, 500);
        }
    };
    
    // Floating elements animation
    const floatingElements = document.querySelectorAll('.floating-circle, .watch-container');
    floatingElements.forEach((element, index) => {
        element.style.animationDelay = `${index * 0.5}s`;
    });
    
    // Parallax effect for hero section
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.5;
        
        const heroBackground = document.querySelector('.hero-background');
        if (heroBackground) {
            heroBackground.style.transform = `translateY(${rate}px)`;
        }
    });
    
    // Initialize tooltips (if Bootstrap tooltips are needed)
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers (if Bootstrap popovers are needed)
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// Utility Functions
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    // Set icon based on type
    let icon = 'fas fa-info-circle';
    if (type === 'success') icon = 'fas fa-check-circle';
    if (type === 'error') icon = 'fas fa-exclamation-circle';
    if (type === 'warning') icon = 'fas fa-exclamation-triangle';
    
    notification.innerHTML = `
        <i class="${icon} me-2"></i>
        ${message}
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Remove after 4 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Loading state management
function setLoadingState(element, loading = true) {
    if (loading) {
        element.classList.add('loading');
        element.disabled = true;
    } else {
        element.classList.remove('loading');
        element.disabled = false;
    }
}

// Smooth scroll to element
function scrollToElement(elementId, offset = 80) {
    const element = document.getElementById(elementId.replace('#', ''));
    if (element) {
        const elementPosition = element.offsetTop - offset;
        window.scrollTo({
            top: elementPosition,
            behavior: 'smooth'
        });
    }
}

// Format currency
function formatCurrency(amount, currency = 'USD') {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency
    }).format(amount);
}

// Local storage helpers
const Storage = {
    set: (key, value) => {
        try {
            localStorage.setItem(key, JSON.stringify(value));
        } catch (e) {
            console.warn('localStorage not available');
        }
    },
    
    get: (key, defaultValue = null) => {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : defaultValue;
        } catch (e) {
            console.warn('localStorage not available');
            return defaultValue;
        }
    },
    
    remove: (key) => {
        try {
            localStorage.removeItem(key);
        } catch (e) {
            console.warn('localStorage not available');
        }
    }
};

// API helpers (for future use)
const API = {
    baseURL: '/api',
    
    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const config = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            ...options
        };
        
        try {
            const response = await fetch(url, config);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('API request failed:', error);
            throw error;
        }
    },
    
    get(endpoint) {
        return this.request(endpoint);
    },
    
    post(endpoint, data) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    },
    
    put(endpoint, data) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    },
    
    delete(endpoint) {
        return this.request(endpoint, {
            method: 'DELETE'
        });
    }
};

// Cart management
const Cart = {
    items: Storage.get('cartItems', []),
    
    add(product) {
        const existingItem = this.items.find(item => item.id === product.id);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            this.items.push({
                ...product,
                quantity: 1
            });
        }
        
        this.save();
        this.updateUI();
        showNotification(`${product.name} added to cart!`, 'success');
    },
    
    remove(productId) {
        this.items = this.items.filter(item => item.id !== productId);
        this.save();
        this.updateUI();
    },
    
    updateQuantity(productId, quantity) {
        const item = this.items.find(item => item.id === productId);
        if (item) {
            item.quantity = Math.max(0, quantity);
            if (item.quantity === 0) {
                this.remove(productId);
            } else {
                this.save();
                this.updateUI();
            }
        }
    },
    
    clear() {
        this.items = [];
        this.save();
        this.updateUI();
    },
    
    getTotal() {
        return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
    },
    
    getCount() {
        return this.items.reduce((count, item) => count + item.quantity, 0);
    },
    
    save() {
        Storage.set('cartItems', this.items);
    },
    
    updateUI() {
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            const count = this.getCount();
            cartBadge.textContent = count;
            cartBadge.style.display = count > 0 ? 'flex' : 'none';
        }
    }
};

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', function() {
    Cart.updateUI();
});

// Wishlist management
const Wishlist = {
    items: Storage.get('wishlistItems', []),
    
    toggle(product) {
        const index = this.items.findIndex(item => item.id === product.id);
        
        if (index > -1) {
            this.items.splice(index, 1);
            showNotification(`${product.name} removed from wishlist`, 'info');
        } else {
            this.items.push(product);
            showNotification(`${product.name} added to wishlist!`, 'success');
        }
        
        this.save();
        this.updateUI();
    },
    
    isInWishlist(productId) {
        return this.items.some(item => item.id === productId);
    },
    
    save() {
        Storage.set('wishlistItems', this.items);
    },
    
    updateUI() {
        // Update wishlist icons throughout the page
        const wishlistButtons = document.querySelectorAll('[data-wishlist-id]');
        wishlistButtons.forEach(button => {
            const productId = button.getAttribute('data-wishlist-id');
            const isInWishlist = this.isInWishlist(productId);
            
            if (isInWishlist) {
                button.classList.add('active');
                button.innerHTML = '<i class="fas fa-heart"></i>';
            } else {
                button.classList.remove('active');
                button.innerHTML = '<i class="far fa-heart"></i>';
            }
        });
    }
};

// Search functionality
const Search = {
    debounceTime: 300,
    
    init() {
        const searchInputs = document.querySelectorAll('.search-input');
        searchInputs.forEach(input => {
            input.addEventListener('input', debounce((e) => {
                this.handleSearch(e.target.value);
            }, this.debounceTime));
        });
    },
    
    handleSearch(query) {
        if (query.length < 2) return;
        
        // Here you would typically make an API call to search
        console.log('Searching for:', query);
        
        // For demo purposes, show some results
        this.showSuggestions(query);
    },
    
    showSuggestions(query) {
        // This would typically show search suggestions
        console.log('Showing suggestions for:', query);
    }
};

// Form validation
const FormValidator = {
    rules: {
        required: (value) => value.trim() !== '',
        email: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
        minLength: (length) => (value) => value.length >= length,
        maxLength: (length) => (value) => value.length <= length,
        phone: (value) => /^[\+]?[\d\s\-\(\)]+$/.test(value)
    },
    
    validate(formElement) {
        const inputs = formElement.querySelectorAll('[data-validate]');
        let isValid = true;
        
        inputs.forEach(input => {
            const rules = input.getAttribute('data-validate').split('|');
            const value = input.value;
            let inputValid = true;
            
            rules.forEach(rule => {
                const [ruleName, ruleParam] = rule.split(':');
                
                if (this.rules[ruleName]) {
                    const validator = ruleParam ? 
                        this.rules[ruleName](ruleParam) : 
                        this.rules[ruleName];
                    
                    if (!validator(value)) {
                        inputValid = false;
                        this.showError(input, ruleName, ruleParam);
                    }
                }
            });
            
            if (inputValid) {
                this.clearError(input);
            } else {
                isValid = false;
            }
        });
        
        return isValid;
    },
    
    showError(input, rule, param) {
        input.classList.add('is-invalid');
        
        let errorMessage = 'Invalid input';
        const errorMessages = {
            required: 'This field is required',
            email: 'Please enter a valid email address',
            minLength: `Minimum ${param} characters required`,
            maxLength: `Maximum ${param} characters allowed`,
            phone: 'Please enter a valid phone number'
        };
        
        if (errorMessages[rule]) {
            errorMessage = errorMessages[rule];
        }
        
        let errorElement = input.parentNode.querySelector('.invalid-feedback');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'invalid-feedback';
            input.parentNode.appendChild(errorElement);
        }
        
        errorElement.textContent = errorMessage;
    },
    
    clearError(input) {
        input.classList.remove('is-invalid');
        const errorElement = input.parentNode.querySelector('.invalid-feedback');
        if (errorElement) {
            errorElement.remove();
        }
    }
};

// Performance optimization
const Performance = {
    // Lazy load images
    lazyLoadImages() {
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    },
    
    // Preload critical resources
    preloadResources() {
        const criticalImages = [
            '/images/hero-smartwatch.png',
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
};

// Initialize performance optimizations
document.addEventListener('DOMContentLoaded', function() {
    Performance.lazyLoadImages();
    Performance.preloadResources();
    Search.init();
    Wishlist.updateUI();
});