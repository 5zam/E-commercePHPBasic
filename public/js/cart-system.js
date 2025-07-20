// TekSouq Complete Cart System - FIXED VERSION
class CartSystem {
    constructor() {
        this.items = this.getCartFromStorage();
        this.cartBadge = null;
        this.cartIcon = null;
        this.cartDropdown = null;
        this.init();
    }
    
    init() {
        this.findCartElements();
        this.bindAddToCartForms();
        this.updateCartBadge();
        this.createCartDropdown();
        this.bindCartIcon();
        
        console.log('Cart elements found:', {
            cartBadge: !!this.cartBadge,
            cartIcon: !!this.cartIcon,
            totalItems: this.getTotalItems()
        });
    }
    
    // Find cart elements in DOM
    findCartElements() {
        // Try multiple selectors for cart badge
        this.cartBadge = document.querySelector('.cart-badge') || 
                        document.querySelector('[class*="badge"]') ||
                        document.querySelector('.navbar .badge');
        
        // Try multiple selectors for cart icon
        this.cartIcon = document.querySelector('a[href*="cart"]') ||
                       document.querySelector('.nav-icon') ||
                       document.querySelector('[title*="cart" i]') ||
                       document.querySelector('.fa-shopping-cart')?.closest('a');
        
        // If cart badge doesn't exist, create it
        if (!this.cartBadge && this.cartIcon) {
            this.createCartBadge();
        }
        
        console.log('Cart icon found:', this.cartIcon?.outerHTML);
        console.log('Cart badge found:', this.cartBadge?.outerHTML);
    }
    
    // Create cart badge if it doesn't exist
    createCartBadge() {
        if (!this.cartIcon) return;
        
        this.cartBadge = document.createElement('span');
        this.cartBadge.className = 'cart-badge';
        this.cartBadge.style.cssText = `
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #0a0a1a;
            z-index: 10;
        `;
        
        // Make cart icon container relative
        this.cartIcon.style.position = 'relative';
        this.cartIcon.appendChild(this.cartBadge);
        
        console.log('Cart badge created and added to:', this.cartIcon);
    }
    
    // Get cart items from localStorage
    getCartFromStorage() {
        try {
            return JSON.parse(localStorage.getItem('tekSouqCart')) || [];
        } catch (e) {
            return [];
        }
    }
    
    // Save cart to localStorage
    saveCartToStorage() {
        localStorage.setItem('tekSouqCart', JSON.stringify(this.items));
    }
    
    // Bind all Add to Cart forms
    bindAddToCartForms() {
        // Remove existing listeners first
        const existingForms = document.querySelectorAll('.add-to-cart-form');
        console.log('Found add to cart forms:', existingForms.length);
        
        existingForms.forEach(form => {
            const newForm = form.cloneNode(true);
            form.parentNode.replaceChild(newForm, form);
        });
        
        // Add new listeners
        const forms = document.querySelectorAll('.add-to-cart-form');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => this.handleAddToCart(e));
        });
        
        console.log('Bound add to cart forms:', forms.length);
    }
    
    // Handle Add to Cart form submission
    handleAddToCart(e) {
        e.preventDefault();
        console.log('Add to cart triggered');
        
        const form = e.target;
        const button = form.querySelector('.add-to-cart-btn');
        const productId = form.querySelector('input[name="product_id"]').value;
        const quantity = parseInt(form.querySelector('input[name="quantity"]').value) || 1;
        
        // Get product info from the card
        const productCard = form.closest('.product-card');
        const productInfo = this.extractProductInfo(productCard, productId);
        
        console.log('Product info extracted:', productInfo);
        
        if (!productInfo) {
            this.showNotification('Product information not found', 'error');
            return;
        }
        
        // Add loading state
        this.setButtonLoading(button, true);
        
        // Add to cart after short delay (simulate API call)
        setTimeout(() => {
            this.addItem(productInfo, quantity);
            this.setButtonLoading(button, false);
            this.showAddedAnimation(button);
        }, 800);
    }
    
    // Extract product information from product card
    extractProductInfo(productCard, productId) {
        if (!productCard) {
            console.error('Product card not found');
            return null;
        }
        
        const titleElement = productCard.querySelector('.product-title a, .product-title, h3 a, h3');
        const priceElement = productCard.querySelector('.price-current, .product-price, [class*="price"]');
        const imageElement = productCard.querySelector('.product-image, img');
        
        console.log('Elements found:', {
            title: titleElement?.textContent,
            price: priceElement?.textContent,
            image: imageElement?.src
        });
        
        if (!titleElement || !priceElement) {
            console.error('Required elements not found');
            return null;
        }
        
        const title = titleElement.textContent.trim();
        const priceText = priceElement.textContent.replace(/[^0-9.]/g, '');
        const price = parseFloat(priceText) || 0;
        const image = imageElement ? imageElement.src : '/images/default-product.png';
        
        return {
            id: productId,
            title: title,
            price: price,
            image: image
        };
    }
    
    // Add item to cart
    addItem(product, quantity = 1) {
        console.log('Adding item to cart:', product);
        
        const existingIndex = this.items.findIndex(item => item.id === product.id);
        
        if (existingIndex > -1) {
            this.items[existingIndex].quantity += quantity;
            console.log('Updated existing item quantity');
        } else {
            this.items.push({
                ...product,
                quantity: quantity,
                addedAt: Date.now()
            });
            console.log('Added new item to cart');
        }
        
        this.saveCartToStorage();
        this.updateCartBadge();
        this.updateCartDropdown();
        this.showNotification(`${product.title} added to cart!`, 'success');
        
        console.log('Cart updated. Total items:', this.getTotalItems());
    }
    
    // Remove item from cart
    removeItem(productId) {
        const itemIndex = this.items.findIndex(item => item.id === productId);
        if (itemIndex > -1) {
            const removedItem = this.items[itemIndex];
            this.items.splice(itemIndex, 1);
            this.saveCartToStorage();
            this.updateCartBadge();
            this.updateCartDropdown();
            this.showNotification(`${removedItem.title} removed from cart`, 'info');
        }
    }
    
    // Update item quantity
    updateQuantity(productId, quantity) {
        const item = this.items.find(item => item.id === productId);
        if (item) {
            if (quantity <= 0) {
                this.removeItem(productId);
            } else {
                item.quantity = quantity;
                this.saveCartToStorage();
                this.updateCartBadge();
                this.updateCartDropdown();
            }
        }
    }
    
    // Get total items count
    getTotalItems() {
        return this.items.reduce((total, item) => total + item.quantity, 0);
    }
    
    // Get total price
    getTotalPrice() {
        return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
    }
    
    // Update cart badge
    updateCartBadge() {
        if (!this.cartBadge) {
            console.log('Cart badge not found, trying to find/create it');
            this.findCartElements();
        }
        
        if (this.cartBadge) {
            const count = this.getTotalItems();
            this.cartBadge.textContent = count;
            
            console.log('Updating cart badge. Count:', count);
            
            if (count > 0) {
                this.cartBadge.style.display = 'flex';
                this.cartBadge.classList.add('animate-bounce');
                setTimeout(() => {
                    this.cartBadge.classList.remove('animate-bounce');
                }, 600);
            } else {
                this.cartBadge.style.display = 'none';
            }
        } else {
            console.error('Cart badge still not found after search');
        }
    }
    
    // Create cart dropdown
    createCartDropdown() {
        if (!this.cartIcon) {
            console.log('Cart icon not found, cannot create dropdown');
            return;
        }
        
        // Create dropdown container
        const dropdown = document.createElement('div');
        dropdown.className = 'cart-dropdown';
        dropdown.innerHTML = `
            <div class="cart-dropdown-content">
                <div class="cart-header">
                    <h5><i class="fas fa-shopping-cart me-2"></i>Shopping Cart</h5>
                    <span class="cart-count">${this.getTotalItems()} items</span>
                </div>
                <div class="cart-items" id="cartDropdownItems">
                    <!-- Items will be inserted here -->
                </div>
                <div class="cart-footer">
                    <div class="cart-total">
                        <strong>Total: $<span id="cartTotal">${this.getTotalPrice().toFixed(2)}</span></strong>
                    </div>
                    <div class="cart-actions">
                        <a href="/cart" class="btn btn-outline-primary">View Cart</a>
                        <a href="/checkout" class="btn btn-primary">Checkout</a>
                    </div>
                </div>
            </div>
        `;
        
        // Style the dropdown
        dropdown.style.cssText = `
            position: absolute;
            top: 100%;
            right: 0;
            width: 320px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        `;
        
        // Make cart icon container relative
        this.cartIcon.parentNode.style.position = 'relative';
        this.cartIcon.parentNode.appendChild(dropdown);
        
        this.cartDropdown = dropdown;
        this.updateCartDropdown();
        
        console.log('Cart dropdown created');
    }
    
    // Update cart dropdown content
    updateCartDropdown() {
        if (!this.cartDropdown) return;
        
        const itemsContainer = this.cartDropdown.querySelector('#cartDropdownItems');
        const totalElement = this.cartDropdown.querySelector('#cartTotal');
        const countElement = this.cartDropdown.querySelector('.cart-count');
        
        if (this.items.length === 0) {
            itemsContainer.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart text-muted"></i>
                    <p>Your cart is empty</p>
                    <a href="/shop" class="btn btn-sm btn-primary">Start Shopping</a>
                </div>
            `;
        } else {
            itemsContainer.innerHTML = this.items.map(item => `
                <div class="cart-item" data-id="${item.id}">
                    <img src="${item.image}" alt="${item.title}" class="cart-item-image">
                    <div class="cart-item-info">
                        <h6 class="cart-item-title">${item.title}</h6>
                        <div class="cart-item-details">
                            <span class="cart-item-price">${item.price.toFixed(2)}</span>
                            <div class="quantity-controls">
                                <button class="qty-btn minus" onclick="window.cartSystem.updateQuantity('${item.id}', ${item.quantity - 1})">-</button>
                                <span class="quantity">${item.quantity}</span>
                                <button class="qty-btn plus" onclick="window.cartSystem.updateQuantity('${item.id}', ${item.quantity + 1})">+</button>
                            </div>
                        </div>
                    </div>
                    <button class="remove-item" onclick="window.cartSystem.removeItem('${item.id}')" title="Remove item">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `).join('');
        }
        
        totalElement.textContent = this.getTotalPrice().toFixed(2);
        countElement.textContent = `${this.getTotalItems()} items`;
    }
    
    // Bind cart icon click
    bindCartIcon() {
        if (!this.cartIcon) {
            console.log('Cart icon not found, cannot bind click event');
            return;
        }
        
        this.cartIcon.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggleCartDropdown();
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (this.cartIcon && !this.cartIcon.parentNode.contains(e.target)) {
                this.hideCartDropdown();
            }
        });
        
        console.log('Cart icon click event bound');
    }
    
    // Toggle cart dropdown
    toggleCartDropdown() {
        if (!this.cartDropdown) return;
        
        const isVisible = this.cartDropdown.style.opacity === '1';
        
        if (isVisible) {
            this.hideCartDropdown();
        } else {
            this.showCartDropdown();
        }
    }
    
    // Show cart dropdown
    showCartDropdown() {
        if (!this.cartDropdown) return;
        
        this.updateCartDropdown();
        this.cartDropdown.style.opacity = '1';
        this.cartDropdown.style.visibility = 'visible';
        this.cartDropdown.style.transform = 'translateY(0)';
    }
    
    // Hide cart dropdown
    hideCartDropdown() {
        if (!this.cartDropdown) return;
        
        this.cartDropdown.style.opacity = '0';
        this.cartDropdown.style.visibility = 'hidden';
        this.cartDropdown.style.transform = 'translateY(-10px)';
    }
    
    // Set button loading state
    setButtonLoading(button, loading) {
        if (loading) {
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
            button.disabled = true;
            button.style.background = 'linear-gradient(135deg, #6366f1, #8b5cf6)';
        } else {
            button.innerHTML = button.dataset.originalText;
            button.disabled = false;
            button.style.background = '';
        }
    }
    
    // Show added animation
    showAddedAnimation(button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check me-2"></i>Added!';
        button.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.style.background = '';
        }, 2000);
    }
    
    // Show notification
    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existing = document.querySelectorAll('.cart-notification');
        existing.forEach(notif => notif.remove());
        
        const notification = document.createElement('div');
        notification.className = `cart-notification notification-${type}`;
        
        const colors = {
            success: '#10b981',
            error: '#ef4444',
            info: '#3b82f6'
        };
        
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: ${colors[type] || colors.info};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 300px;
        `;
        
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // Clear cart
    clearCart() {
        this.items = [];
        this.saveCartToStorage();
        this.updateCartBadge();
        this.updateCartDropdown();
        this.showNotification('Cart cleared', 'info');
    }
    
        
        this.cartDropdown.style.opacity = '0';
        this.cartDropdown.style.visibility = 'hidden';
        this.cartDropdown.style.transform = 'translateY(-10px)';
    }
    
    // Set button loading state
    setButtonLoading(button, loading) {
        if (loading) {
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
            button.disabled = true;
            button.style.background = 'linear-gradient(135deg, #6366f1, #8b5cf6)';
        } else {
            button.innerHTML = button.dataset.originalText;
            button.disabled = false;
            button.style.background = '';
        }
    }
    
    // Show added animation
    showAddedAnimation(button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check me-2"></i>Added!';
        button.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.style.background = '';
        }, 2000);
    }
    
    // Show notification
    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existing = document.querySelectorAll('.cart-notification');
        existing.forEach(notif => notif.remove());
        
        const notification = document.createElement('div');
        notification.className = `cart-notification notification-${type}`;
        
        const colors = {
            success: '#10b981',
            error: '#ef4444',
            info: '#3b82f6'
        };
        
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: ${colors[type] || colors.info};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 300px;
        `;
        
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // Clear cart
    clearCart() {
        this.items = [];
        this.saveCartToStorage();
        this.updateCartBadge();
        this.updateCartDropdown();
        this.showNotification('Cart cleared', 'info');
    }
    
    // Manual trigger for testing
    testAddItem() {
        const testProduct = {
            id: 'test-' + Date.now(),
            title: 'Test Product',
            price: 29.99,
            image: '/images/default-product.png'
        };
        
        this.addItem(testProduct, 1);
        console.log('Test item added to cart');
    }
}

// Cart Dropdown CSS
const cartDropdownCSS = `
.cart-dropdown-content {
    padding: 1rem;
}

.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.cart-header h5 {
    margin: 0;
    font-size: 1rem;
    color: #2d3748;
}

.cart-count {
    font-size: 0.8rem;
    color: #718096;
}

.cart-items {
    max-height: 300px;
    overflow-y: auto;
    margin-bottom: 1rem;
}

.cart-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
}

.cart-item-info {
    flex: 1;
}

.cart-item-title {
    font-size: 0.85rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
    color: #2d3748;
    line-height: 1.2;
}

.cart-item-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cart-item-price {
    font-size: 0.8rem;
    font-weight: 600;
    color: #4f46e5;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.qty-btn {
    width: 20px;
    height: 20px;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 3px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.7rem;
    transition: all 0.2s ease;
}

.qty-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e0;
}

.quantity {
    font-size: 0.8rem;
    font-weight: 600;
    min-width: 20px;
    text-align: center;
}

.remove-item {
    background: #fed7d7;
    border: none;
    color: #e53e3e;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.7rem;
    transition: all 0.2s ease;
}

.remove-item:hover {
    background: #e53e3e;
    color: white;
}

.empty-cart {
    text-align: center;
    padding: 2rem 1rem;
    color: #718096;
}

.empty-cart i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

.empty-cart p {
    margin: 0.5rem 0;
}

.cart-footer {
    border-top: 1px solid #e2e8f0;
    padding-top: 1rem;
}

.cart-total {
    text-align: center;
    margin-bottom: 1rem;
    font-size: 1.1rem;
    color: #2d3748;
}

.cart-actions {
    display: flex;
    gap: 0.5rem;
}

.cart-actions .btn {
    flex: 1;
    padding: 0.5rem;
    font-size: 0.8rem;
    text-align: center;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn-outline-primary {
    border: 1px solid #4f46e5;
    color: #4f46e5;
    background: white;
}

.btn-outline-primary:hover {
    background: #4f46e5;
    color: white;
}

.btn-primary {
    background: #4f46e5;
    color: white;
    border: 1px solid #4f46e5;
}

.btn-primary:hover {
    background: #3730a3;
    border-color: #3730a3;
}

.animate-bounce {
    animation: cartBounce 0.6s ease;
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

/* Scrollbar for cart items */
.cart-items::-webkit-scrollbar {
    width: 4px;
}

.cart-items::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.cart-items::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 2px;
}
`;

// Inject CSS
const style = document.createElement('style');
style.textContent = cartDropdownCSS;
document.head.appendChild(style);

// Initialize cart system when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.cartSystem = new CartSystem();
    console.log('TekSouq Cart System initialized');
    
    // Add test function to window for debugging
    window.testCart = function() {
        if (window.cartSystem) {
            window.cartSystem.testAddItem();
        }
    };
});

// Export for external use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CartSystem;
}
};
        
        this.cartDropdown.style.opacity = '0';
        this.cartDropdown.style.visibility = 'hidden';
        this.cartDropdown.style.transform = 'translateY(-10px)';
    }
    
    // Set button loading state
    setButtonLoading(button, loading) {
        if (loading) {
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
            button.disabled = true;
            button.style.background = 'linear-gradient(135deg, #6366f1, #8b5cf6)';
        } else {
            button.innerHTML = button.dataset.originalText;
            button.disabled = false;
            button.style.background = '';
        }
    }
    
    // Show added animation
    showAddedAnimation(button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check me-2"></i>Added!';
        button.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.style.background = '';
        }, 2000);
    }
    
    // Show notification
    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existing = document.querySelectorAll('.cart-notification');
        existing.forEach(notif => notif.remove());
        
        const notification = document.createElement('div');
        notification.className = `cart-notification notification-${type}`;
        
        const colors = {
            success: '#10b981',
            error: '#ef4444',
            info: '#3b82f6'
        };
        
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: ${colors[type] || colors.info};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 300px;
        `;
        
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // Clear cart
    clearCart() {
        this.items = [];
        this.saveCartToStorage();
        this.updateCartBadge();
        this.updateCartDropdown();
        this.showNotification('Cart cleared', 'info');
    }
}

// Cart Dropdown CSS
const cartDropdownCSS = `
.cart-dropdown-content {
    padding: 1rem;
}

.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.cart-header h5 {
    margin: 0;
    font-size: 1rem;
    color: #2d3748;
}

.cart-count {
    font-size: 0.8rem;
    color: #718096;
}

.cart-items {
    max-height: 300px;
    overflow-y: auto;
    margin-bottom: 1rem;
}

.cart-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
}

.cart-item-info {
    flex: 1;
}

.cart-item-title {
    font-size: 0.85rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
    color: #2d3748;
    line-height: 1.2;
}

.cart-item-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cart-item-price {
    font-size: 0.8rem;
    font-weight: 600;
    color: #4f46e5;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.qty-btn {
    width: 20px;
    height: 20px;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 3px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.7rem;
    transition: all 0.2s ease;
}

.qty-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e0;
}

.quantity {
    font-size: 0.8rem;
    font-weight: 600;
    min-width: 20px;
    text-align: center;
}

.remove-item {
    background: #fed7d7;
    border: none;
    color: #e53e3e;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.7rem;
    transition: all 0.2s ease;
}

.remove-item:hover {
    background: #e53e3e;
    color: white;
}

.empty-cart {
    text-align: center;
    padding: 2rem 1rem;
    color: #718096;
}

.empty-cart i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

.empty-cart p {
    margin: 0.5rem 0;
}

.cart-footer {
    border-top: 1px solid #e2e8f0;
    padding-top: 1rem;
}

.cart-total {
    text-align: center;
    margin-bottom: 1rem;
    font-size: 1.1rem;
    color: #2d3748;
}

.cart-actions {
    display: flex;
    gap: 0.5rem;
}

.cart-actions .btn {
    flex: 1;
    padding: 0.5rem;
    font-size: 0.8rem;
    text-align: center;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn-outline-primary {
    border: 1px solid #4f46e5;
    color: #4f46e5;
    background: white;
}

.btn-outline-primary:hover {
    background: #4f46e5;
    color: white;
}

.btn-primary {
    background: #4f46e5;
    color: white;
    border: 1px solid #4f46e5;
}

.btn-primary:hover {
    background: #3730a3;
    border-color: #3730a3;
}

.animate-bounce {
    animation: cartBounce 0.6s ease;
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

/* Scrollbar for cart items */
.cart-items::-webkit-scrollbar {
    width: 4px;
}

.cart-items::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.cart-items::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 2px;
}
`;

// Inject CSS
const style = document.createElement('style');
style.textContent = cartDropdownCSS;
document.head.appendChild(style);

// Initialize cart system when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.cartSystem = new CartSystem();
    console.log('TekSouq Cart System initialized');
});

// Export for external use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CartSystem;
}