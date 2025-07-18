{{-- Enhanced Shop Header with Desktop Sidebar Integration --}}
<div class="shop-header" id="shopHeader">
    <div class="shop-header-left">
        <h2 class="shop-title">
            <i class="fas fa-store me-2"></i>
            All Products
        </h2>
        @if($products->count() > 0)
            <p class="shop-count">
                Showing <span class="count-highlight">{{ $products->count() }}</span> products
                @if(request()->hasAny(['search', 'min_price', 'max_price']))
                    <span class="filtered-indicator">
                        <i class="fas fa-filter me-1"></i>
                        Filtered Results
                    </span>
                @endif
            </p>
        @endif
        
        {{-- Active Filters Display (Mobile & Desktop) --}}
        @if(request()->hasAny(['search', 'min_price', 'max_price']))
            <div class="active-filters-display">
                <div class="active-filters-chips">
                    @if(request('search'))
                        <span class="filter-chip-display">
                            <i class="fas fa-search me-1"></i>
                            "{{ request('search') }}"
                            <a href="{{ route('shop', array_merge(request()->except('search'), request()->only(['min_price', 'max_price', 'sort']))) }}" 
                               class="chip-remove" title="Remove search filter">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    
                    @if(request('min_price'))
                        <span class="filter-chip-display">
                            <i class="fas fa-dollar-sign me-1"></i>
                            Min: ${{ request('min_price') }}
                            <a href="{{ route('shop', array_merge(request()->except('min_price'), request()->only(['search', 'max_price', 'sort']))) }}" 
                               class="chip-remove" title="Remove minimum price filter">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    
                    @if(request('max_price'))
                        <span class="filter-chip-display">
                            <i class="fas fa-dollar-sign me-1"></i>
                            Max: ${{ request('max_price') }}
                            <a href="{{ route('shop', array_merge(request()->except('max_price'), request()->only(['search', 'min_price', 'sort']))) }}" 
                               class="chip-remove" title="Remove maximum price filter">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    
                    <a href="{{ route('shop') }}" class="clear-all-filters-btn" title="Clear all filters">
                        <i class="fas fa-times-circle me-1"></i>
                        Clear All
                    </a>
                </div>
            </div>
        @endif
    </div>
    
    <div class="shop-header-right">
        {{-- Desktop Filter Toggle Button (Will be injected by JS) --}}
        {{-- The desktop-filter-sidebar.js will insert the toggle button here --}}
        
        <div class="view-sort-controls">
            {{-- View Toggle --}}
            <div class="shop-view-toggle">
                <button class="view-toggle active" data-view="grid" title="Grid View">
                    <i class="fas fa-th-large"></i>
                    <span class="view-label d-none d-xl-inline">Grid</span>
                </button>
                <button class="view-toggle" data-view="list" title="List View">
                    <i class="fas fa-list"></i>
                    <span class="view-label d-none d-xl-inline">List</span>
                </button>
            </div>
            
            {{-- Sort Dropdown --}}
            <div class="sort-container">
                <select class="shop-sort" onchange="updateSort(this.value)" title="Sort products">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                        🕒 Newest First
                    </option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                        💰 Price: Low to High
                    </option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                        💎 Price: High to Low
                    </option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>
                        🔤 Name A-Z
                    </option>
                </select>
                <div class="sort-icon">
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </div>
        
        {{-- Quick Actions (Desktop Only) --}}
        <div class="quick-actions d-none d-lg-flex">
            <button class="quick-action-btn" onclick="toggleGridDensity()" title="Toggle grid density">
                <i class="fas fa-expand-arrows-alt"></i>
            </button>
            <button class="quick-action-btn" onclick="refreshProducts()" title="Refresh products">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>
</div>

{{-- Enhanced Shop Header Styles --}}
<style>
/* Enhanced Shop Header */
.shop-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
    transition: margin-left 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.shop-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(135deg, #4f46e5, #06b6d4, #8b5cf6);
    background-size: 200% 200%;
    animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

/* Shop Header Left */
.shop-header-left {
    flex: 1;
    max-width: 60%;
}

.shop-title {
    font-size: 2rem;
    font-weight: 800;
    color: #2d3748;
    margin: 0 0 0.75rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    line-height: 1.2;
}

.shop-title i {
    color: #4f46e5;
    font-size: 1.5rem;
}

.shop-count {
    color: #718096;
    margin: 0 0 1rem 0;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
    font-weight: 500;
}

.count-highlight {
    font-weight: 800;
    color: #4f46e5;
    font-size: 1.1rem;
}

.filtered-indicator {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 0.375rem 0.875rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    animation: slideInRight 0.4s ease;
}

/* Active Filters Display */
.active-filters-display {
    margin-top: 0.75rem;
    animation: slideInUp 0.4s ease;
}

.active-filters-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
}

.filter-chip-display {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    animation: slideInUp 0.3s ease;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
    transition: all 0.3s ease;
}

.filter-chip-display:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
}

.chip-remove {
    background: rgba(255, 255, 255, 0.25);
    color: white;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.chip-remove:hover {
    background: rgba(255, 255, 255, 0.4);
    color: white;
    transform: scale(1.1) rotate(90deg);
    text-decoration: none;
}

.clear-all-filters-btn {
    background: transparent;
    color: #e53e3e;
    padding: 0.5rem 1rem;
    border: 2px solid #e53e3e;
    border-radius: 25px;
    font-size: 0.85rem;
    font-weight: 700;
    text-decoration: none;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    gap: 0.25rem;
}

.clear-all-filters-btn:hover {
    background: #e53e3e;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(229, 62, 62, 0.4);
    text-decoration: none;
}

/* Shop Header Right */
.shop-header-right {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    flex-shrink: 0;
}

.view-sort-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* Enhanced View Toggle */
.shop-view-toggle {
    display: flex;
    background: #f8fafc;
    border-radius: 12px;
    padding: 4px;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    border: 2px solid #e2e8f0;
}

.view-toggle {
    padding: 0.75rem 1rem;
    border: none;
    background: transparent;
    color: #64748b;
    border-radius: 8px;
    transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    cursor: pointer;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
}

.view-toggle.active {
    background: white;
    color: #4f46e5;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
    transform: translateY(-1px);
}

.view-toggle:hover:not(.active) {
    color: #4a5568;
    background: rgba(255, 255, 255, 0.7);
    transform: translateY(-1px);
}

.view-label {
    font-size: 0.8rem;
    letter-spacing: 0.5px;
}

/* Enhanced Sort Container */
.sort-container {
    position: relative;
    display: flex;
    align-items: center;
}

.shop-sort {
    appearance: none;
    padding: 0.75rem 3rem 0.75rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    color: #4a5568;
    font-weight: 600;
    min-width: 200px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.shop-sort:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    transform: translateY(-1px);
}

.shop-sort:hover {
    border-color: #cbd5e0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.sort-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    pointer-events: none;
    transition: transform 0.3s ease;
}

.sort-container:hover .sort-icon {
    transform: translateY(-50%) rotate(180deg);
}

/* Quick Actions */
.quick-actions {
    display: flex;
    gap: 0.5rem;
}

.quick-action-btn {
    width: 44px;
    height: 44px;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.quick-action-btn:hover {
    background: #4f46e5;
    border-color: #4f46e5;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

/* Sidebar Displacement */
.shop-header.sidebar-active {
    margin-left: 320px;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .shop-header {
        padding: 1.5rem;
    }
    
    .shop-title {
        font-size: 1.75rem;
    }
    
    .view-label {
        display: none !important;
    }
    
    .shop-sort {
        min-width: 160px;
    }
}

@media (max-width: 992px) {
    .shop-header {
        flex-direction: column;
        gap: 1.5rem;
        align-items: stretch;
        padding: 1.5rem;
        border-radius: 12px;
    }
    
    .shop-header-left {
        max-width: 100%;
    }
    
    .shop-header-right {
        justify-content: center;
        flex-direction: column;
        gap: 1rem;
    }
    
    .view-sort-controls {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .shop-title {
        font-size: 1.5rem;
        justify-content: center;
    }
    
    .shop-count {
        justify-content: center;
        text-align: center;
    }
    
    .active-filters-chips {
        justify-content: center;
    }
    
    .shop-sort {
        min-width: auto;
        width: 100%;
    }
    
    .quick-actions {
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .shop-header {
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .shop-title {
        font-size: 1.3rem;
    }
    
    .active-filters-chips {
        gap: 0.25rem;
    }
    
    .filter-chip-display {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }
    
    .clear-all-filters-btn {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }
    
    .view-sort-controls {
        flex-direction: column;
        width: 100%;
    }
    
    .shop-view-toggle {
        order: 2;
        margin-top: 0.5rem;
        justify-content: center;
    }
    
    .sort-container {
        order: 1;
        width: 100%;
    }
    
    .shop-sort {
        width: 100%;
    }
}

/* Animations */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Focus indicators for accessibility */
.view-toggle:focus,
.quick-action-btn:focus,
.chip-remove:focus,
.clear-all-filters-btn:focus {
    outline: 2px solid #4f46e5;
    outline-offset: 2px;
}
</style>

<script>
// Enhanced Shop Header JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Grid density toggle
    window.toggleGridDensity = function() {
        const grid = document.getElementById('productsGrid');
        if (grid) {
            grid.classList.toggle('dense-grid');
            
            // Show notification
            const isDense = grid.classList.contains('dense-grid');
            showEnhancedNotification(
                isDense ? 'Dense grid enabled' : 'Normal grid restored',
                'info'
            );
        }
    };
    
    // Refresh products
    window.refreshProducts = function() {
        const btn = event.target.closest('.quick-action-btn');
        const icon = btn.querySelector('i');
        
        // Add spinning animation
        icon.style.animation = 'spin 1s linear infinite';
        
        // Simulate refresh
        setTimeout(() => {
            icon.style.animation = '';
            showEnhancedNotification('Products refreshed', 'success');
        }, 1000);
    };
    
    // Enhanced notification function
    window.showEnhancedNotification = function(message, type = 'info') {
        // Remove existing notifications
        const existing = document.querySelectorAll('.enhanced-notification');
        existing.forEach(notif => notif.remove());
        
        const notification = document.createElement('div');
        notification.className = `enhanced-notification notification-${type}`;
        
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        
        const colors = {
            success: 'linear-gradient(135deg, #10b981, #059669)',
            error: 'linear-gradient(135deg, #ef4444, #dc2626)',
            warning: 'linear-gradient(135deg, #f59e0b, #d97706)',
            info: 'linear-gradient(135deg, #3b82f6, #2563eb)'
        };
        
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${icons[type]} notification-icon"></i>
                <span class="notification-message">${message}</span>
            </div>
        `;
        
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: ${colors[type]};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            min-width: 280px;
            backdrop-filter: blur(10px);
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after delay
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 3500);
    };
});

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</script>