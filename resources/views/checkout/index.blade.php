@extends('layout.app')

@section('title', 'Checkout - TekSouq')

@push('styles')
<link href="{{ asset('css/checkout.css') }}" rel="stylesheet">
@endpush

@section('content')
{{-- Checkout Header - نفس تدرج صفحة Shop --}}
<div class="checkout-hero">
    <div class="checkout-hero-background"></div>
    <div class="checkout-hero-overlay"></div>
    <div class="container">
        <div class="checkout-hero-content">
            <h1 class="checkout-hero-title">
                <i class="fas fa-credit-card me-3"></i>
                Checkout
            </h1>
            <p class="checkout-hero-subtitle">Complete your order in just a few steps</p>
        </div>
    </div>
</div>

{{-- Main Checkout Content --}}
<div class="checkout-content">
    <div class="container">
        {{-- Progress Steps --}}
        <div class="checkout-progress">
            <div class="progress-step active">
                <div class="step-number">1</div>
                <div class="step-label">Shipping Info</div>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="step-number">2</div>
                <div class="step-label">Review Order</div>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="step-number">3</div>
                <div class="step-label">Complete</div>
            </div>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="row">
                {{-- Left Side - Shipping Form --}}
                <div class="col-lg-8">
                    <div class="checkout-card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-shipping-fast me-2"></i>
                                Shipping Information
                            </h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">First Name *</label>
                                        <input type="text" 
                                               id="first_name" 
                                               name="first_name" 
                                               class="form-control" 
                                               placeholder="Enter your first name"
                                               required>
                                        <div class="error-message"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Last Name *</label>
                                        <input type="text" 
                                               id="last_name" 
                                               name="last_name" 
                                               class="form-control" 
                                               placeholder="Enter your last name"
                                               required>
                                        <div class="error-message"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               class="form-control" 
                                               placeholder="your@email.com"
                                               required>
                                        <div class="error-message"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Phone Number *</label>
                                        <input type="tel" 
                                               id="phone" 
                                               name="phone" 
                                               class="form-control" 
                                               placeholder="+966 5xx xxx xxx"
                                               required>
                                        <div class="error-message"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address" class="form-label">Street Address *</label>
                                <input type="text" 
                                       id="address" 
                                       name="address" 
                                       class="form-control" 
                                       placeholder="Enter your full address"
                                       required>
                                <div class="error-message"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city" class="form-label">City *</label>
                                        <select id="city" name="city" class="form-control" required>
                                            <option value="">Select City</option>
                                            <option value="riyadh">Riyadh</option>
                                            <option value="jeddah">Jeddah</option>
                                            <option value="dammam">Dammam</option>
                                            <option value="mecca">Mecca</option>
                                            <option value="medina">Medina</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <div class="error-message"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="postal_code" class="form-label">Postal Code</label>
                                        <input type="text" 
                                               id="postal_code" 
                                               name="postal_code" 
                                               class="form-control" 
                                               placeholder="12345">
                                        <div class="error-message"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Shipping Method --}}
                            <div class="shipping-methods">
                                <h3 class="section-title">
                                    <i class="fas fa-truck me-2"></i>
                                    Shipping Method
                                </h3>
                                <div class="shipping-options">
                                    <div class="shipping-option">
                                        <input type="radio" id="standard" name="shipping_method" value="standard" checked>
                                        <label for="standard" class="shipping-label">
                                            <div class="shipping-info">
                                                <span class="shipping-name">Standard Delivery</span>
                                                <span class="shipping-time">5-7 business days</span>
                                            </div>
                                            <span class="shipping-price">FREE</span>
                                        </label>
                                    </div>
                                    <div class="shipping-option">
                                        <input type="radio" id="express" name="shipping_method" value="express">
                                        <label for="express" class="shipping-label">
                                            <div class="shipping-info">
                                                <span class="shipping-name">Express Delivery</span>
                                                <span class="shipping-time">2-3 business days</span>
                                            </div>
                                            <span class="shipping-price">$15.00</span>
                                        </label>
                                    </div>
                                    <div class="shipping-option">
                                        <input type="radio" id="overnight" name="shipping_method" value="overnight">
                                        <label for="overnight" class="shipping-label">
                                            <div class="shipping-info">
                                                <span class="shipping-name">Overnight Delivery</span>
                                                <span class="shipping-time">Next business day</span>
                                            </div>
                                            <span class="shipping-price">$35.00</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Special Instructions --}}
                            <div class="form-group">
                                <label for="notes" class="form-label">Special Instructions (Optional)</label>
                                <textarea id="notes" 
                                          name="notes" 
                                          class="form-control" 
                                          rows="3" 
                                          placeholder="Any special delivery instructions..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Side - Order Summary --}}
                <div class="col-lg-4">
                    <div class="order-summary">
                        <div class="summary-header">
                            <h2 class="summary-title">
                                <i class="fas fa-receipt me-2"></i>
                                Order Summary
                            </h2>
                        </div>
                        
                        {{-- Order Items --}}
                        <div class="order-items">
                            @if(session('cart'))
                                @foreach(session('cart') as $item)
                                    <div class="order-item">
                                        <div class="item-image">
                                            <img src="{{ $item['product']->image_url ?? '/images/placeholder.jpg' }}" 
                                                 alt="{{ $item['product']->title }}">
                                        </div>
                                        <div class="item-details">
                                            <h4 class="item-name">{{ $item['product']->title }}</h4>
                                            <div class="item-meta">
                                                <span class="item-qty">Qty: {{ $item['quantity'] }}</span>
                                                <span class="item-price">${{ number_format($item['price'], 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="item-total">
                                            ${{ number_format($item['subtotal'], 2) }}
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- Price Breakdown --}}
                        <div class="price-breakdown">
                            <div class="price-row">
                                <span class="price-label">Subtotal:</span>
                                <span class="price-value" id="subtotal">
                                    ${{ number_format(array_sum(array_column(session('cart', []), 'subtotal')), 2) }}
                                </span>
                            </div>
                            <div class="price-row">
                                <span class="price-label">Shipping:</span>
                                <span class="price-value" id="shipping-cost">FREE</span>
                            </div>
                            <div class="price-row">
                                <span class="price-label">Tax (10%):</span>
                                <span class="price-value" id="tax">
                                    ${{ number_format(array_sum(array_column(session('cart', []), 'subtotal')) * 0.1, 2) }}
                                </span>
                            </div>
                            <div class="price-row total-row">
                                <span class="price-label">Total:</span>
                                <span class="price-value" id="total">
                                    ${{ number_format(array_sum(array_column(session('cart', []), 'subtotal')) * 1.1, 2) }}
                                </span>
                            </div>
                        </div>

                        {{-- Place Order Button --}}
                        <div class="checkout-actions">
                            <button type="submit" class="place-order-btn" id="placeOrderBtn">
                                <i class="fas fa-lock me-2"></i>
                                Place Order
                            </button>
                            <a href="{{ route('cart.index') }}" class="back-to-cart">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Cart
                            </a>
                        </div>

                        {{-- Security Notice --}}
                        <div class="security-notice">
                            <i class="fas fa-shield-alt me-2"></i>
                            <span>Your information is secure and encrypted</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    const shippingMethods = document.querySelectorAll('input[name="shipping_method"]');
    
    // Shipping method change handler
    shippingMethods.forEach(method => {
        method.addEventListener('change', function() {
            updateShippingCost();
        });
    });
    
    // Form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (validateForm()) {
            submitOrder();
        }
    });
    
    function updateShippingCost() {
        const selectedMethod = document.querySelector('input[name="shipping_method"]:checked');
        const shippingCostEl = document.getElementById('shipping-cost');
        const totalEl = document.getElementById('total');
        
        let shippingCost = 0;
        switch(selectedMethod.value) {
            case 'express': shippingCost = 15; break;
            case 'overnight': shippingCost = 35; break;
            default: shippingCost = 0;
        }
        
        shippingCostEl.textContent = shippingCost === 0 ? 'FREE' : `$${shippingCost.toFixed(2)}`;
        
        // Recalculate total
        const subtotal = parseFloat(document.getElementById('subtotal').textContent.replace('$', ''));
        const tax = parseFloat(document.getElementById('tax').textContent.replace('$', ''));
        const newTotal = subtotal + shippingCost + tax;
        totalEl.textContent = `$${newTotal.toFixed(2)}`;
    }
    
    function validateForm() {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            const errorEl = field.parentElement.querySelector('.error-message');
            if (!field.value.trim()) {
                field.classList.add('error');
                errorEl.textContent = 'This field is required';
                isValid = false;
            } else {
                field.classList.remove('error');
                errorEl.textContent = '';
            }
        });
        
        // Email validation
        const email = document.getElementById('email');
        if (email.value && !isValidEmail(email.value)) {
            email.classList.add('error');
            email.parentElement.querySelector('.error-message').textContent = 'Please enter a valid email';
            isValid = false;
        }
        
        return isValid;
    }
    
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    function submitOrder() {
        // Show loading state
        placeOrderBtn.disabled = true;
        placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing Order...';
        
        // Simulate processing time
        setTimeout(() => {
            form.submit();
        }, 2000);
    }
});
</script>
@endpush