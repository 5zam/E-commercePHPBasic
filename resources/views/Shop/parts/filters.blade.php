{{-- resources/views/shop/partials/filters.blade.php --}}
<div class="shop-filters">
    <div class="filters-header">
        <h3>Filters</h3>
        @if(request()->hasAny(['search', 'min_price', 'max_price']))
            <a href="{{ route('shop') }}" class="clear-filters">
                <i class="fas fa-times"></i> Clear All
            </a>
        @endif
    </div>

    <form id="filterForm" method="GET" action="{{ route('shop') }}">
        <!-- Search Filter -->
        <div class="filter-group">
            <h4 class="filter-title">Search</h4>
            <div class="search-input-container">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search products..." 
                       class="form-control search-input">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- Price Range Filter -->
        <div class="filter-group">
            <h4 class="filter-title">Price Range</h4>
            <div class="price-range-container">
                <div class="price-inputs">
                    <div class="price-input-group">
                        <label>Min</label>
                        <input type="number" 
                               name="min_price" 
                               value="{{ request('min_price') }}" 
                               placeholder="0"
                               min="0" 
                               max="1000"
                               class="form-control price-input">
                    </div>
                    <div class="price-separator">-</div>
                    <div class="price-input-group">
                        <label>Max</label>
                        <input type="number" 
                               name="max_price" 
                               value="{{ request('max_price') }}" 
                               placeholder="500"
                               min="0" 
                               max="1000"
                               class="form-control price-input">
                    </div>
                </div>
                <div class="price-range-info">
                    <span>$0</span>
                    <span>$500</span>
                </div>
            </div>
        </div>

        <!-- Hidden inputs to preserve other parameters -->
        <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">

        <!-- Apply Filters Button -->
        <div class="filter-actions">
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-filter me-2"></i>Apply Filters
            </button>
        </div>
    </form>

    <!-- Quick Access -->
    <div class="quick-categories">
        <h4 class="filter-title">Quick Access</h4>
        <div class="quick-category-links">
            <a href="{{ route('shop') }}" 
               class="quick-category {{ !request()->hasAny(['search', 'min_price', 'max_price']) ? 'active' : '' }}">
                <i class="fas fa-th"></i>
                All Products
            </a>
            <a href="{{ route('shop', ['max_price' => 100]) }}" 
               class="quick-category {{ request('max_price') == 100 ? 'active' : '' }}">
                <i class="fas fa-tag"></i>
                Under $100
            </a>
            <a href="{{ route('shop', ['min_price' => 200]) }}" 
               class="quick-category {{ request('min_price') == 200 ? 'active' : '' }}">
                <i class="fas fa-gem"></i>
                Premium ($200+)
            </a>
        </div>
    </div>
</div>