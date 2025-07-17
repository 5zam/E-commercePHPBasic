@extends('layout.app')

@section('title', 'Shopping Cart - TekSouq')

@push('styles')
<link href="{{ asset('css/cart.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="cart-container">
    <div class="container">
        <div class="cart-card">
            <div class="cart-header">
                <h1 class="cart-title">
                    <i class="fas fa-shopping-cart me-3"></i>
                    Shopping Cart
                </h1>
                @if(count($cart) > 0)
                    <p class="cart-subtitle">{{ count($cart) }} amazing item(s) ready for checkout</p>
                @else
                    <p class="cart-subtitle">Your cart is waiting for some great products</p>
                @endif
            </div>

            <div class="cart-content">
                {{-- Success/Error Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(count($cart) > 0)
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="cart-items">
                                @foreach($cart as $index => $item)
                                    <div class="cart-item" style="animation-delay: {{ $index * 0.1 }}s">
                                        <div class="item-image">
                                            <img src="{{ $item['product']->image_url }}" 
                                                 alt="{{ $item['product']->title }}" 
                                                 loading="lazy">
                                        </div>
                                        
                                        <div class="item-details">
                                            <h3 class="item-title">{{ $item['product']->title }}</h3>
                                            <p class="item-description">{{ Str::limit($item['product']->description ?? 'Premium quality product', 100) }}</p>
                                            <div class="item-price">
                                                <span class="price-amount">${{ number_format($item['price'], 2) }}</span>
                                                <span class="price-label">per item</span>
                                            </div>
                                        </div>
                                        
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
                                        
                                        <div class="item-total">
                                            <div class="total-label">Subtotal</div>
                                            <div class="total-amount">${{ number_format($item['subtotal'], 2) }}</div>
                                        </div>
                                        
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
                        
                        <div class="col-lg-4">
                            <div class="cart-summary">
                                <h2 class="summary-title">
                                    <i class="fas fa-receipt me-2"></i>
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
                                
                                <div class="checkout-actions">
                                    <button class="checkout-btn">
                                        <i class="fas fa-credit-card me-2"></i>
                                        Proceed to Checkout
                                    </button>
                                    
                                    <a href="{{ route('shop') }}" class="continue-shopping">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Continue Shopping
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="cart-actions">
                        <form action="{{ route('cart.clear') }}" 
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="clear-cart"
                                    onclick="return confirm('Are you sure you want to clear your entire cart?')">
                                <i class="fas fa-trash me-2"></i>
                                Clear Cart
                            </button>
                        </form>
                    </div>
                @else
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
<script src="{{ asset('js/cart.js') }}"></script>
@endpush