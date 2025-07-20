@extends('layout.app')

@section('title', 'Shopping Cart - TekSouq')

@push('styles')
<link href="{{ asset('css/cart.css') }}?v={{ time() }}" rel="stylesheet">
@endpush

@section('content')
<div class="cart-container">
    <div class="container">
        <div class="cart-card">
            {{-- Header Section --}}
            <div class="cart-header">
                <h1 class="cart-title">
                    <i class="fas fa-shopping-cart"></i>
                    Shopping Cart
                </h1>
                @if(count($cart) > 0)
                    <p class="cart-subtitle">{{ count($cart) }} amazing item(s) ready for checkout</p>
                @else
                    <p class="cart-subtitle">Your cart is waiting for some great products</p>
                @endif
            </div>

            {{-- Main Content --}}
            <div class="cart-content">
                {{-- Success/Error Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(count($cart) > 0)
                    <div class="row">
                        {{-- Cart Items --}}
                        <div class="col-lg-8">
                            <div class="cart-items">
                                @foreach($cart as $index => $item)
                                    <div class="cart-item" style="animation-delay: {{ $index * 0.1 }}s">
                                        {{-- Product Image --}}
                                        <div class="item-image">
                                            <img src="{{ $item['product']->image_url ?? '/images/placeholder.jpg' }}" 
                                                 alt="{{ $item['product']->title }}" 
                                                 loading="lazy">
                                        </div>
                                        
                                        {{-- Product Details --}}
                                        <div class="item-details">
                                            <h3 class="item-title">{{ $item['product']->title }}</h3>
                                            <p class="item-description">
                                                {{ Str::limit($item['product']->description ?? 'Premium quality product', 100) }}
                                            </p>
                                            <div class="item-price">
                                                <span class="price-amount">${{ number_format($item['price'], 2) }}</span>
                                                <span class="price-label">per item</span>
                                            </div>
                                        </div>
                                        
                                        {{-- Quantity Controls --}}
                                        <div class="item-quantity">
                                            <span class="quantity-label">Quantity</span>
                                            <form action="{{ route('cart.update', $item['product']->id) }}" 
                                                  method="POST" 
                                                  class="quantity-form">
                                                @csrf
                                                @method('PUT')
                                                <div class="quantity-controls">
                                                    <button type="button" class="qty-btn minus" data-action="decrease">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" 
                                                           name="quantity" 
                                                           value="{{ $item['quantity'] }}" 
                                                           min="1" 
                                                           max="{{ $item['product']->stock ?? 99 }}"
                                                           class="qty-input">
                                                    <button type="button" class="qty-btn plus" data-action="increase">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <button type="submit" class="update-btn">
                                                    <i class="fas fa-sync-alt me-1"></i>
                                                    Update
                                                </button>
                                            </form>
                                        </div>
                                        
                                        {{-- Item Total --}}
                                        <div class="item-total">
                                            <div class="total-label">Subtotal</div>
                                            <div class="total-amount">${{ number_format($item['subtotal'], 2) }}</div>
                                        </div>
                                        
                                        {{-- Remove Button --}}
                                        <div class="item-actions">
                                            <form action="{{ route('cart.remove', $item['product']->id) }}" 
                                                  method="POST" 
                                                  class="remove-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="remove-btn"
                                                        onclick="return confirm('Remove this item from cart?')">
                                                    <i class="fas fa-trash"></i>
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        {{-- Order Summary --}}
                        <div class="col-lg-4">
                            <div class="cart-summary">
                                <h2 class="summary-title">
                                    <i class="fas fa-receipt"></i>
                                    Order Summary
                                </h2>
                                
                                <div class="summary-item">
                                    <span class="summary-label">Items ({{ count($cart) }})</span>
                                    <span class="summary-value">${{ number_format($total, 2) }}</span>
                                </div>
                                
                                <div class="summary-item">
                                    <span class="summary-label">Shipping</span>
                                    <span class="summary-value">FREE</span>
                                </div>
                                
                                <div class="summary-item">
                                    <span class="summary-label">Tax (10%)</span>
                                    <span class="summary-value">${{ number_format($total * 0.1, 2) }}</span>
                                </div>
                                
                                <div class="summary-total">
                                    <span class="total-label">Total</span>
                                    <span class="total-value">${{ number_format($total * 1.1, 2) }}</span>
                                </div>
                                
                               <a href="{{ route('checkout') }}" class="checkout-btn">
                                <i class="fas fa-credit-card"></i>
                                Proceed to Checkout
                                </a>
                                    
                                    <a href="{{ route('shop') }}" class="continue-shopping">
                                        <i class="fas fa-arrow-left"></i>
                                        Continue Shopping
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Clear Cart Button --}}
                    <div class="cart-actions">
                        <form action="{{ route('cart.clear') }}" 
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="clear-cart"
                                    onclick="return confirm('Are you sure you want to clear your entire cart?')">
                                <i class="fas fa-trash"></i>
                                Clear Cart
                            </button>
                        </form>
                    </div>
                @else
                    {{-- Empty Cart State --}}
                    <div class="empty-cart">
                        <div class="empty-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h2 class="empty-title">Your cart is empty</h2>
                        <p class="empty-text">
                            Discover amazing products and add them to your cart.<br>
                            Start shopping now and find something you love!
                        </p>
                        <a href="{{ route('shop') }}" class="start-shopping">
                            <i class="fas fa-store"></i>
                            Start Shopping
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity Controls
    document.querySelectorAll('.qty-btn').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.qty-input');
            const action = this.dataset.action;
            let value = parseInt(input.value);
            const max = parseInt(input.getAttribute('max'));
            const min = parseInt(input.getAttribute('min'));

            if (action === 'increase' && value < max) {
                input.value = value + 1;
            } else if (action === 'decrease' && value > min) {
                input.value = value - 1;
            }
        });
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Add loading state to forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            }
        });
    });
});

// Checkout functionality
function proceedToCheckout() {
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    
    // Simulate checkout process (replace with your actual checkout logic)
    setTimeout(() => {
        // For now, show an alert - replace with actual checkout redirect
        alert('Checkout functionality will be implemented here!');
        
        // Reset button
        button.disabled = false;
        button.innerHTML = originalText;
    }, 2000);
    
    // Example of actual checkout redirect:
    // window.location.href = '/checkout';
}

// Add smooth animations
function addCartItemAnimation() {
    const items = document.querySelectorAll('.cart-item');
    items.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.6s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Call animation on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', addCartItemAnimation);
} else {
    addCartItemAnimation();
}
</script>
@endpush