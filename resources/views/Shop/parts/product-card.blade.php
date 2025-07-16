{{-- resources/views/shop/partials/product-card.blade.php --}}
<div class="product-card">
    <div class="product-image-container">
        <img src="{{ asset($product->image ?: 'images/default-product.png') }}" 
             alt="{{ $product->title }}" 
             class="product-image"
             loading="lazy">
        
        @if(isset($product->sale_price) && $product->sale_price)
            <div class="product-badge sale-badge">
                SALE
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
        </div>

        <div class="product-actions-bottom">
            <a href="{{ route('product.show', $product->id) }}" class="btn btn-outline">
                <i class="fas fa-eye me-2"></i>View Details
            </a>
            <button class="btn btn-primary add-to-cart-btn" 
                    onclick="addToCart({{ $product->id }})">
                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
            </button>
        </div>
    </div>
</div>