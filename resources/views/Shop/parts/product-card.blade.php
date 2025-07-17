{{-- resources/views/shop/partials/product-card.blade.php --}}
<div class="product-card">
    <div class="product-image-container">
        <img src="{{ $product->image_url }}" 
             alt="{{ $product->title }}" 
             class="product-image"
             loading="lazy">
        
        @if(isset($product->sale_price) && $product->sale_price)
            <div class="product-badge sale-badge">
                SALE
            </div>
        @endif

        @if($product->stock <= 5 && $product->stock > 0)
            <div class="product-badge stock-badge">
                Low Stock
            </div>
        @elseif($product->stock <= 0)
            <div class="product-badge stock-badge" style="background: #e53e3e;">
                Out of Stock
            </div>
        @endif

        <div class="product-overlay">
            <div class="product-actions">
                <button class="action-btn quick-view-btn" 
                        onclick="quickView({{ $product->id }})" 
                        title="Quick View">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn wishlist-btn" 
                        onclick="addToWishlist({{ $product->id }})" 
                        title="Add to Wishlist">
                    <i class="far fa-heart"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="product-info">
        <h3 class="product-title">
            <a href="{{ route('product.show', $product->id) }}">{{ $product->title }}</a>
        </h3>
        
        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>

        <div class="product-price">
            <span class="price-current">${{ number_format($product->price, 2) }}</span>
            @if($product->stock > 0)
                <small class="stock-info text-success">{{ $product->stock }} in stock</small>
            @endif
        </div>

        <div class="product-actions-bottom">
            <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline">
                <i class="fas fa-eye me-2"></i>View Details
            </a>
            
            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form d-inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-primary add-to-cart-btn">
                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                    </button>
                </form>
            @else
                <button class="btn btn-primary" disabled>
                    <i class="fas fa-times-circle me-2"></i>Out of Stock
                </button>
            @endif
        </div>
    </div>
</div>

<style>
/* إضافة بعض الستايلات للتحسين */
.stock-info {
    display: block;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.add-to-cart-form {
    flex: 2;
}

.add-to-cart-btn:disabled {
    background: #a0aec0 !important;
    cursor: not-allowed;
}

.product-badge.stock-badge {
    left: 1rem;
    top: 3.5rem;
    background: #f59e0b;
    color: white;
}

/* loading effect*/
.add-to-cart-btn.loading {
    position: relative;
    color: transparent !important;
}

.add-to-cart-btn.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}
</style>