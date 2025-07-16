@extends('layout.app')

@section('title', 'Shop - TekSouq')

@push('styles')
<link href="{{ asset('css/shop.css') }}" rel="stylesheet">
@endpush

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
<section class="shop-content">
    <div class="container">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3">
                @include('shop.parts.filters')
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Shop Header -->
                <div class="shop-header">
                    <div class="shop-header-left">
                        <h2 class="shop-title">All Products</h2>
                        @if($products->count() > 0)
                            <p class="shop-count">Showing {{ $products->count() }} products</p>
                        @endif
                    </div>
                    <div class="shop-header-right">
                        <div class="shop-view-toggle">
                            <button class="view-toggle active" data-view="grid">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button class="view-toggle" data-view="list">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                        <select class="shop-sort" onchange="updateSort(this.value)">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="products-grid" id="productsGrid">
                    @if($products && $products->count() > 0)
                        @foreach($products as $product)
                            @include('shop.partials.product-card', ['product' => $product])
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="no-products">
                                <i class="fas fa-search"></i>
                                <h3>No products found</h3>
                                <p>Try adjusting your filters or search terms</p>
                                <a href="{{ route('shop') }}" class="btn btn-primary">View All Products</a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Simple Pagination -->
                @if($products && $products->hasPages())
                <div class="shop-pagination">
                    {{ $products->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Sort functionality
function updateSort(sortValue) {
    const url = new URL(window.location);
    url.searchParams.set('sort', sortValue);
    window.location.href = url.toString();
}

// View toggle functionality
document.querySelectorAll('.view-toggle').forEach(toggle => {
    toggle.addEventListener('click', function() {
        document.querySelectorAll('.view-toggle').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        
        const view = this.dataset.view;
        const grid = document.getElementById('productsGrid');
        
        if (view === 'list') {
            grid.classList.add('list-view');
        } else {
            grid.classList.remove('list-view');
        }
    });
});

// Filter form functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        const inputs = filterForm.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                filterForm.submit();
            });
        });
    }
});

// JavaScript functions for buttons
function quickView(productId) {
    alert('Quick view for product ' + productId);
}

function addToWishlist(productId) {
    alert('Added product ' + productId + ' to wishlist');
}

function addToCart(productId) {
    alert('Added product ' + productId + ' to cart');
}
</script>
@endpush