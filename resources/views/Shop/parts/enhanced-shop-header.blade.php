{{-- Enhanced Shop Header - Fixed Version --}}
<div class="shop-header" id="shopHeader">
    <div class="shop-header-left">
        <h2 class="shop-title">
            <i class="fas fa-store me-2"></i>
            @if(request('view') == 'categories')
                Browse Categories
            @else
                All Products
            @endif
        </h2>
        @if(request('view') != 'categories' && isset($products) && $products->count() > 0)
            <p class="shop-count">
                Showing <span class="count-highlight">{{ $products->count() }}</span> products
                @if(request()->hasAny(['search', 'min_price', 'max_price', 'category']))
                    <span class="filtered-indicator">
                        <i class="fas fa-filter me-1"></i>
                        Filtered Results
                    </span>
                @endif
            </p>
        @elseif(request('view') == 'categories' && isset($categories))
            <p class="shop-count">
                Browse <span class="count-highlight">{{ $categories->count() }}</span> categories
            </p>
        @endif
        
        {{-- Active Filters Display (Only for products) --}}
        @if(request('view') != 'categories' && request()->hasAny(['search', 'min_price', 'max_price']))
            <div class="active-filters-display">
                <div class="active-filters-chips">
                    @if(request('search'))
                        <span class="filter-chip-display">
                            <i class="fas fa-search me-1"></i>
                            "{{ request('search') }}"
                            <a href="{{ route('shop', array_merge(request()->except('search'), request()->only(['min_price', 'max_price', 'sort', 'category']))) }}" 
                               class="chip-remove" title="Remove search filter">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    
                    @if(request('min_price'))
                        <span class="filter-chip-display">
                            <i class="fas fa-dollar-sign me-1"></i>
                            Min: ${{ request('min_price') }}
                            <a href="{{ route('shop', array_merge(request()->except('min_price'), request()->only(['search', 'max_price', 'sort', 'category']))) }}" 
                               class="chip-remove" title="Remove minimum price filter">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    
                    @if(request('max_price'))
                        <span class="filter-chip-display">
                            <i class="fas fa-dollar-sign me-1"></i>
                            Max: ${{ request('max_price') }}
                            <a href="{{ route('shop', array_merge(request()->except('max_price'), request()->only(['search', 'min_price', 'sort', 'category']))) }}" 
                               class="chip-remove" title="Remove maximum price filter">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    
                    <a href="{{ route('shop', request()->only('category')) }}" class="clear-all-filters-btn" title="Clear all filters except category">
                        <i class="fas fa-times-circle me-1"></i>
                        Clear All
                    </a>
                </div>
            </div>
        @endif
    </div>
    
    <div class="shop-header-right">
        <div class="view-sort-controls">
            {{-- Browse Type Dropdown - FIXED COLORS --}}
            <div class="browse-selector">
                <div class="dropdown">
                    <button class="browse-dropdown-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="browse-btn-content">
                            <i class="fas fa-{{ request('view') == 'categories' ? 'th' : 'box' }} browse-icon"></i>
                            <div class="browse-text">
                                <span class="browse-label">Browse</span>
                                <span class="browse-type">{{ request('view') == 'categories' ? 'Categories' : 'Products' }}</span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down browse-arrow"></i>
                    </button>
                    <ul class="dropdown-menu browse-dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request('view') != 'categories' ? 'active' : '' }}" 
                               href="{{ route('shop', request()->except('view')) }}">
                                <i class="fas fa-box dropdown-icon"></i>
                                <div class="dropdown-item-content">
                                    <strong>Products</strong>
                                    <small>Browse all products</small>
                                </div>
                                @if(request('view') != 'categories')
                                    <i class="fas fa-check active-check"></i>
                                @endif
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item {{ request('view') == 'categories' ? 'active' : '' }}" 
                               href="{{ route('shop', ['view' => 'categories']) }}">
                                <i class="fas fa-th dropdown-icon"></i>
                                <div class="dropdown-item-content">
                                    <strong>Categories</strong>
                                    <small>Browse by category</small>
                                </div>
                                @if(request('view') == 'categories')
                                    <i class="fas fa-check active-check"></i>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            {{-- Separator (Desktop only) --}}
            <div class="controls-separator d-none d-lg-block"></div>
            
            {{-- View Controls - BACK TO LINKS (Working Version) --}}
            <div class="view-toggle-group">
                @if(request('view') == 'categories')
                    {{-- Category View Controls --}}
                    <button class="view-toggle active" title="Grid View" data-view="grid" type="button">
                        <i class="fas fa-th-large"></i>
                        <span class="view-label d-none d-xl-inline">Grid</span>
                    </button>
                    <button class="view-toggle" title="List View" data-view="list" type="button">
                        <i class="fas fa-list"></i>
                        <span class="view-label d-none d-xl-inline">List</span>
                    </button>
                @else
                    {{-- Product View Controls - BACK TO WORKING LINKS --}}
                    <a href="{{ route('shop', array_merge(request()->except('view'), request()->only(['search', 'min_price', 'max_price', 'sort']))) }}" 
                       class="view-toggle {{ !request('view') || request('view') == 'grid' ? 'active' : '' }}" 
                       title="Grid View"
                       data-view="grid">
                        <i class="fas fa-th-large"></i>
                        <span class="view-label d-none d-xl-inline">Grid</span>
                    </a>
                    <a href="{{ route('shop', array_merge(request()->all(), ['view' => 'list'])) }}" 
                       class="view-toggle {{ request('view') == 'list' ? 'active' : '' }}" 
                       title="List View"
                       data-view="list">
                        <i class="fas fa-list"></i>
                        <span class="view-label d-none d-xl-inline">List</span>
                    </a>
                @endif
            </div>
            
            {{-- Sort Dropdown (Context-aware) --}}
            <div class="sort-container">
                @if(request('view') == 'categories')
                    <select class="shop-sort" onchange="updateCategorySort(this.value)" title="Sort categories">
                        <option value="name">📝 Name A-Z</option>
                        <option value="products_count">📊 Most Products</option>
                        <option value="newest">🕒 Newest First</option>
                    </select>
                @else
                    <select class="shop-sort" onchange="updateSort(this.value)" title="Sort products">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>🕒 Newest First</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>💰 Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>💎 Price: High to Low</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>🔤 Name A-Z</option>
                    </select>
                @endif
                <div class="sort-icon">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </div>
        
        {{-- FILTER BUTTON CONTAINER --}}
        @if(request('view') != 'categories')
        <div class="filters-section">
            <button class="filter-toggle-btn" id="filterToggleBtn" type="button">
                <i class="fas fa-sliders-h"></i>
                <span>Filters</span>
            </button>
        </div>
        @endif
    </div>
</div>

{{-- Categories Grid (When view=categories) --}}
@if(request('view') == 'categories' && isset($categories))
    <div class="categories-grid" id="categoriesGrid">
        @forelse($categories as $category)
            <div class="category-card">
                <div class="category-image-container">
                    @if($category->hasImage())
                        <img src="{{ $category->image_url }}" 
                             alt="{{ $category->name }}" 
                             class="category-image"
                             loading="lazy">
                    @else
                        <div class="category-image-placeholder">
                            <i class="fas fa-{{ $category->icon }}"></i>
                        </div>
                    @endif
                    
                    <div class="category-overlay">
                        <div class="category-overlay-content">
                            <span class="products-count">{{ $category->products_count }} Products</span>
                        </div>
                    </div>
                </div>
                
                <div class="category-info">
                    <h3 class="category-title">
                        <a href="{{ route('shop', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                    </h3>
                    <p class="category-description">
                        {{ $category->description ?? 'Discover amazing ' . strtolower($category->name) . ' products' }}
                    </p>
                    <div class="category-stats">
                        <span class="category-count">
                            <i class="fas fa-box me-1"></i>
                            {{ $category->products_count }} Products
                        </span>
                        <span class="category-price-range">
                            @php
                                $priceRange = \App\Models\Product::where('category_id', $category->id)
                                    ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
                                    ->first();
                            @endphp
                            @if($priceRange && $priceRange->min_price)
                                <i class="fas fa-dollar-sign me-1"></i>
                                ${{ number_format($priceRange->min_price, 0) }} - ${{ number_format($priceRange->max_price, 0) }}
                            @endif
                        </span>
                    </div>
                    <div class="category-actions">
                        <a href="{{ route('shop', ['category' => $category->slug]) }}" class="btn btn-primary">
                            <i class="fas fa-arrow-right me-2"></i>
                            Browse {{ $category->name }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="no-categories">
                    <div class="no-categories-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h3>No categories found</h3>
                    <p>Categories will appear here once they are created</p>
                    <div class="no-categories-actions">
                        <a href="{{ route('shop') }}" class="btn btn-primary">
                            <i class="fas fa-box me-2"></i>Browse Products
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endif

<style>
/* FIX DROPDOWN COLORS */
.browse-dropdown-menu {
    background: white !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    padding: 0.5rem 0 !important;
    min-width: 280px !important;
}

.browse-dropdown-menu .dropdown-item {
    color: #4a5568 !important;
    padding: 0.75rem 1rem !important;
    display: flex !important;
    align-items: center !important;
    gap: 1rem !important;
    transition: all 0.2s ease !important;
    border: none !important;
    background: transparent !important;
}

.browse-dropdown-menu .dropdown-item:hover {
    background: #f8fafc !important;
    color: #2d3748 !important;
}

.browse-dropdown-menu .dropdown-item.active {
    background: rgba(79, 70, 229, 0.1) !important;
    color: #4f46e5 !important;
}

.dropdown-icon {
    font-size: 1.1rem !important;
    color: #718096 !important;
    width: 20px !important;
    text-align: center !important;
}

.dropdown-item-content {
    flex: 1 !important;
}

.dropdown-item-content strong {
    display: block !important;
    font-size: 0.95rem !important;
    font-weight: 600 !important;
    margin-bottom: 0.25rem !important;
}

.dropdown-item-content small {
    color: #718096 !important;
    font-size: 0.8rem !important;
}

.active-check {
    color: #10b981 !important;
    font-size: 1rem !important;
}

.dropdown-divider {
    margin: 0.5rem 0 !important;
    border-color: #e2e8f0 !important;
}

/* MAKE SURE VIEW TOGGLE BUTTONS WORK */
.view-toggle {
    border: none !important;
    cursor: pointer !important;
}
</style>