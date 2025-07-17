document.addEventListener('DOMContentLoaded', function() {
    // Quantity controls
    const qtyBtns = document.querySelectorAll('.qty-btn');
    
    qtyBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.dataset.action;
            const input = this.closest('.quantity-controls').querySelector('.qty-input');
            let value = parseInt(input.value);
            const max = parseInt(input.getAttribute('max'));
            
            if (action === 'increase' && value < max) {
                input.value = value + 1;
            } else if (action === 'decrease' && value > 1) {
                input.value = value - 1;
            }
        });
    });
    
    // Auto-submit on quantity change
    const qtyInputs = document.querySelectorAll('.qty-input');
    qtyInputs.forEach(input => {
        input.addEventListener('change', function() {
            const form = this.closest('.quantity-form');
            const updateBtn = form.querySelector('.update-btn');
            updateBtn.style.display = 'block';
            updateBtn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Update';
        });
    });

    // Loading state for buttons
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('quantity-form')) {
            const btn = e.target.querySelector('.update-btn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';
            btn.disabled = true;
        }
        
        if (e.target.classList.contains('remove-form')) {
            const btn = e.target.querySelector('.remove-btn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Removing...';
            btn.disabled = true;
        }
    });

    // Update cart count in navbar
    function updateCartCount() {
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => {
                const cartBadge = document.querySelector('.cart-badge');
                if (data.count > 0) {
                    if (cartBadge) {
                        cartBadge.textContent = data.count;
                    } else {
                        const cartLink = document.querySelector('.nav-link[title="Shopping Cart"]');
                        if (cartLink) {
                            const badge = document.createElement('span');
                            badge.className = 'cart-badge';
                            badge.textContent = data.count;
                            cartLink.appendChild(badge);
                        }
                    }
                } else {
                    if (cartBadge) {
                        cartBadge.remove();
                    }
                }
            })
            .catch(error => console.log('Cart count update failed:', error));
    }

    // Update cart count after operations
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('quantity-form') || 
            e.target.classList.contains('remove-form')) {
            setTimeout(updateCartCount, 1000);
        }
    });

    // Smooth scroll to updated item
    function scrollToItem(itemElement) {
        itemElement.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }

    // Add visual feedback for successful updates
    function showUpdateSuccess(element) {
        element.style.backgroundColor = '#d4edda';
        element.style.border = '2px solid #10b981';
        
        setTimeout(() => {
            element.style.backgroundColor = '';
            element.style.border = '';
        }, 2000);
    }

    // Enhanced form submission with feedback
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('quantity-form')) {
            const cartItem = e.target.closest('.cart-item');
            
            setTimeout(() => {
                showUpdateSuccess(cartItem);
                scrollToItem(cartItem);
            }, 500);
        }
    });
});