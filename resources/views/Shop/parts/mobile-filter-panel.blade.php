{{-- Mobile Filter Sliding Panel --}}
<div class="mobile-filter-panel d-lg-none" id="mobileFilterPanel">
    <!-- Backdrop Overlay -->
    <div class="filter-backdrop" id="filterBackdrop"></div>
    
    <!-- Sliding Panel -->
    <div class="filter-panel">
        <!-- Panel Header -->
        <div class="filter-panel-header">
            <div class="panel-header-content">
                <h3 class="panel-title">
                    <i class="fas fa-sliders-h me-2"></i>
                    Filters
                </h3>
                <div class="panel-actions">
                    <button type="button" 
                            class="clear-all-btn" 
                            id="clearAllFilters"
                            style="display: none;">
                        <i class="fas fa-times me-1"></i>
                        Clear All
                    </button>
                    <button type="button" 
                            class="close-panel-btn" 
                            id="closePanelBtn"
                            aria-label="Close Filters">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Panel Body (Scrollable) -->
        <div class="filter-panel-body">
            <form id="mobileFilterForm" method="GET" action="{{ route('shop') }}">
                
                <!-- Active Filters Chips -->
                <div class="active-filters" id="activeFilters" style="display: none;">
                    <h4 class="active-filters-title">Active Filters</h4>
                    <div class="filter-chips" id="filterChips"></div>
                </div>

                <!-- Search Filter -->
                <div class="mobile-filter-group">
                    <h4 class="mobile-filter-title">
                        <i class="fas fa-search me-2"></i>
                        Search Products
                    </h4>
                    <div class="mobile-search-container">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search products..." 
                               class="mobile-search-input"
                               id="mobileSearchInput">
                        <div class="search-input-icon">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>

                <!-- Price Range Filter -->
                <div class="mobile-filter-group">
                    <h4 class="mobile-filter-title">
                        <i class="fas fa-dollar-sign me-2"></i>
                        Price Range
                    </h4>
                    <div class="mobile-price-container">
                        <div class="price-inputs-row">
                            <div class="price-input-wrapper">
                                <label>Min Price</label>
                                <input type="number" 
                                       name="min_price" 
                                       value="{{ request('min_price') }}" 
                                       placeholder="0"
                                       min="0" 
                                       max="1000"
                                       class="mobile-price-input">
                            </div>
                            <div class="price-separator">
                                <span>to</span>
                            </div>
                            <div class="price-input-wrapper">
                                <label>Max Price</label>
                                <input type="number" 
                                       name="max_price" 
                                       value="{{ request('max_price') }}" 
                                       placeholder="500"
                                       min="0" 
                                       max="1000"
                                       class="mobile-price-input">
                            </div>
                        </div>
                        <div class="price-range-slider">
                            <div class="price-range-track"></div>
                            <div class="price-range-fill"></div>
                        </div>
                        <div class="price-range-labels">
                            <span>$0</span>
                            <span>$500+</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Categories -->
                <div class="mobile-filter-group">
                    <h4 class="mobile-filter-title">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Filters
                    </h4>
                    <div class="quick-filter-options">
                        <button type="button" 
                                class="quick-filter-btn {{ !request()->hasAny(['search', 'min_price', 'max_price']) ? 'active' : '' }}"
                                data-filter="all">
                            <i class="fas fa-th"></i>
                            All Products
                        </button>
                        <button type="button" 
                                class="quick-filter-btn {{ request('max_price') == 100 ? 'active' : '' }}"
                                data-filter="under-100"
                                data-max-price="100">
                            <i class="fas fa-tag"></i>
                            Under $100
                        </button>
                        <button type="button" 
                                class="quick-filter-btn {{ request('min_price') == 200 ? 'active' : '' }}"
                                data-filter="premium"
                                data-min-price="200">
                            <i class="fas fa-gem"></i>
                            Premium ($200+)
                        </button>
                    </div>
                </div>

                <!-- Hidden inputs for other parameters -->
                <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">
            </form>
        </div>

        <!-- Panel Footer -->
        <div class="filter-panel-footer">
            <div class="footer-actions">
                <button type="button" 
                        class="btn-secondary" 
                        id="resetFiltersBtn">
                    <i class="fas fa-undo me-2"></i>
                    Reset
                </button>
                <button type="submit" 
                        class="btn-primary" 
                        form="mobileFilterForm">
                    <i class="fas fa-check me-2"></i>
                    Apply Filters
                    <span class="results-count" id="resultsCount"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Mobile Filter Panel Styles */
.mobile-filter-panel {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    visibility: hidden;
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.mobile-filter-panel.active {
    visibility: visible;
    opacity: 1;
}

/* Backdrop */
.filter-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    transition: all 0.3s ease;
}

/* Panel Container */
.filter-panel {
    position: absolute;
    top: 0;
    left: 0;
    width: 85%;
    max-width: 380px;
    height: 100%;
    background: white;
    display: flex;
    flex-direction: column;
    transform: translateX(-100%);
    transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    box-shadow: 20px 0 40px rgba(0, 0, 0, 0.15);
}

.mobile-filter-panel.active .filter-panel {
    transform: translateX(0);
}

/* Panel Header */
.filter-panel-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    flex-shrink: 0;
}

.panel-header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.panel-title {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
}

.panel-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.clear-all-btn {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.clear-all-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.05);
}

.close-panel-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.close-panel-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

/* Panel Body */
.filter-panel-body {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem;
    scroll-behavior: smooth;
}

.filter-panel-body::-webkit-scrollbar {
    width: 4px;
}

.filter-panel-body::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.filter-panel-body::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 2px;
}

/* Active Filters Chips */
.active-filters {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.active-filters-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 1rem;
}

.filter-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.filter-chip {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    animation: slideInUp 0.3s ease;
}

.filter-chip-remove {
    background: rgba(255, 255, 255, 0.3);
    border: none;
    color: white;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-chip-remove:hover {
    background: rgba(255, 255, 255, 0.5);
    transform: scale(1.1);
}

/* Filter Groups */
.mobile-filter-group {
    margin-bottom: 2rem;
}

.mobile-filter-title {
    font-size: 1rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

/* Search Input */
.mobile-search-container {
    position: relative;
}

.mobile-search-input {
    width: 100%;
    padding: 0.8rem 1rem;
    padding-right: 3rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.mobile-search-input:focus {
    outline: none;
    border-color: #4f46e5;
    background: white;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.search-input-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    pointer-events: none;
}

/* Price Range */
.mobile-price-container {
    space-y: 1rem;
}

.price-inputs-row {
    display: flex;
    align-items: end;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.price-input-wrapper {
    flex: 1;
}

.price-input-wrapper label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 0.5rem;
}

.mobile-price-input {
    width: 100%;
    padding: 0.7rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.mobile-price-input:focus {
    outline: none;
    border-color: #4f46e5;
    background: white;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.price-separator {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem;
    color: #718096;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.price-range-slider {
    position: relative;
    height: 4px;
    margin: 1rem 0;
}

.price-range-track {
    width: 100%;
    height: 100%;
    background: #e2e8f0;
    border-radius: 2px;
}

.price-range-fill {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border-radius: 2px;
    width: 60%;
}

.price-range-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.8rem;
    color: #718096;
    margin-top: 0.5rem;
}

/* Quick Filter Options */
.quick-filter-options {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.quick-filter-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    color: #4a5568;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    cursor: pointer;
    text-align: left;
}

.quick-filter-btn:hover {
    background: #f1f5f9;
    border-color: #cbd5e0;
    transform: translateX(3px);
}

.quick-filter-btn.active {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border-color: #4f46e5;
    color: white;
    transform: translateX(3px);
}

.quick-filter-btn i {
    font-size: 1rem;
    width: 20px;
    text-align: center;
}

/* Panel Footer */
.filter-panel-footer {
    padding: 1.5rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    flex-shrink: 0;
}

.footer-actions {
    display: flex;
    gap: 1rem;
}

.btn-secondary {
    flex: 1;
    padding: 0.8rem 1rem;
    background: white;
    border: 2px solid #e2e8f0;
    color: #4a5568;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-secondary:hover {
    background: #f8fafc;
    border-color: #cbd5e0;
    transform: translateY(-1px);
}

.btn-primary {
    flex: 2;
    padding: 0.8rem 1rem;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border: none;
    color: white;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #3730a3, #6d28d9);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
}

.results-count {
    margin-left: 0.5rem;
    background: rgba(255, 255, 255, 0.3);
    padding: 0.2rem 0.5rem;
    border-radius: 10px;
    font-size: 0.8rem;
}

/* Animations */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 576px) {
    .filter-panel {
        width: 90%;
        max-width: none;
    }
    
    .filter-panel-header,
    .filter-panel-body,
    .filter-panel-footer {
        padding: 1rem;
    }
    
    .price-inputs-row {
        flex-direction: column;
        gap: 1rem;
    }
    
    .price-separator {
        margin: 0;
        padding: 0.25rem;
    }
}

/* Hide on desktop */
@media (min-width: 992px) {
    .mobile-filter-panel {
        display: none !important;
    }
}
</style>