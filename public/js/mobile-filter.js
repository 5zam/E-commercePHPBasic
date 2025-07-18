// Mobile Filter Panel JavaScript
class MobileFilter {
    constructor() {
        this.panel = document.getElementById('mobileFilterPanel');
        this.toggleBtn = document.getElementById('mobileFilterToggle');
        this.closeBtn = document.getElementById('closePanelBtn');
        this.backdrop = document.getElementById('filterBackdrop');
        this.form = document.getElementById('mobileFilterForm');
        this.clearAllBtn = document.getElementById('clearAllFilters');
        this.resetBtn = document.getElementById('resetFiltersBtn');
        this.filterBadge = document.getElementById('filterBadge');
        this.filterCount = document.getElementById('filterCount');
        this.activeFilters = document.getElementById('activeFilters');
        this.filterChips = document.getElementById('filterChips');
        this.resultsCount = document.getElementById('resultsCount');
        
        this.isOpen = false;
        this.activeFiltersList = [];
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.updateFilterState();
        this.initQuickFilters();
        this.preventBodyScroll();
    }
    
    bindEvents() {
        // Toggle panel
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', () => this.openPanel());
        }
        
        // Close panel
        if (this.closeBtn) {
            this.closeBtn.addEventListener('click', () => this.closePanel());
        }
        
        if (this.backdrop) {
            this.backdrop.addEventListener('click', () => this.closePanel());
        }
        
        // Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closePanel();
            }
        });
        
        // Clear all filters
        if (this.clearAllBtn) {
            this.clearAllBtn.addEventListener('click', () => this.clearAllFilters());
        }
        
        // Reset filters
        if (this.resetBtn) {
            this.resetBtn.addEventListener('click', () => this.resetFilters());
        }
        
        // Form submission
        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }
        
        // Monitor input changes
        this.monitorInputChanges();
        
        // Quick filter chips removal
        if (this.filterChips) {
            this.filterChips.addEventListener('click', (e) => {
                if (e.target.classList.contains('filter-chip-remove')) {
                    this.removeFilterChip(e.target);
                }
            });
        }
    }
    
    openPanel() {
        if (this.panel) {
            this.panel.classList.add('active');
            this.toggleBtn.classList.add('active');
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
            
            // Focus management
            setTimeout(() => {
                const firstInput = this.panel.querySelector('input, button');
                if (firstInput) firstInput.focus();
            }, 300);
            
            // Add open animation
            this.animateOpen();
        }
    }
    
    closePanel() {
        if (this.panel) {
            this.panel.classList.remove('active');
            this.toggleBtn.classList.remove('active');
            this.isOpen = false;
            document.body.style.overflow = '';
            
            // Add close animation
            this.animateClose();
        }
    }
    
    animateOpen() {
        // Animate filter groups
        const filterGroups = this.panel.querySelectorAll('.mobile-filter-group');
        filterGroups.forEach((group, index) => {
            setTimeout(() => {
                group.style.animation = `slideInUp 0.3s ease forwards`;
            }, index * 100);
        });
    }
    
    animateClose() {
        // Reset animations
        const filterGroups = this.panel.querySelectorAll('.mobile-filter-group');
        filterGroups.forEach(group => {
            group.style.animation = '';
        });
    }
    
    clearAllFilters() {
        // Clear form inputs
        const inputs = this.form.querySelectorAll('input[type="text"], input[type="number"]');
        inputs.forEach(input => {
            input.value = '';
        });
        
        // Reset quick filters
        const quickBtns = this.form.querySelectorAll('.quick-filter-btn');
        quickBtns.forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Activate "All Products"
        const allProductsBtn = this.form.querySelector('[data-filter="all"]');
        if (allProductsBtn) {
            allProductsBtn.classList.add('active');
        }
        
        // Update state
        this.updateFilterState();
        
        // Show notification
        this.showNotification('All filters cleared', 'info');
    }
    
    resetFilters() {
        this.clearAllFilters();
        
        // Submit form to reset URL
        this.form.submit();
    }
    
    handleFormSubmit(e) {
        // Add loading state to submit button
        const submitBtn = this.form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Applying...';
            submitBtn.disabled = true;
        }
        
        // Show notification
        this.showNotification('Applying filters...', 'info');
        
        // Close panel after short delay
        setTimeout(() => {
            this.closePanel();
        }, 500);
    }
    
    updateFilterState() {
        this.activeFiltersList = this.getActiveFilters();
        this.updateFilterBadge();
        this.updateFilterChips();
        this.updateClearAllButton();
        this.updateResultsCount();
    }
    
    getActiveFilters() {
        const filters = [];
        
        // Search filter
        const searchInput = this.form.querySelector('input[name="search"]');
        if (searchInput && searchInput.value.trim()) {
            filters.push({
                type: 'search',
                label: `Search: "${searchInput.value.trim()}"`,
                value: searchInput.value.trim()
            });
        }
        
        // Price filters
        const minPrice = this.form.querySelector('input[name="min_price"]');
        const maxPrice = this.form.querySelector('input[name="max_price"]');
        
        if (minPrice && minPrice.value) {
            filters.push({
                type: 'min_price',
                label: `Min: $${minPrice.value}`,
                value: minPrice.value
            });
        }
        
        if (maxPrice && maxPrice.value) {
            filters.push({
                type: 'max_price',
                label: `Max: $${maxPrice.value}`,
                value: maxPrice.value
            });
        }
        
        // Quick filters
        const activeQuickFilter = this.form.querySelector('.quick-filter-btn.active:not([data-filter="all"])');
        if (activeQuickFilter) {
            filters.push({
                type: 'quick',
                label: activeQuickFilter.textContent.trim(),
                element: activeQuickFilter
            });
        }
        
        return filters;
    }
    
    updateFilterBadge() {
        const count = this.activeFiltersList.length;
        
        if (count > 0) {
            this.filterCount.textContent = count;
            this.filterBadge.style.display = 'flex';
        } else {
            this.filterBadge.style.display = 'none';
        }
    }
    
    updateFilterChips() {
        if (!this.filterChips) return;
        
        // Clear existing chips
        this.filterChips.innerHTML = '';
        
        if (this.activeFiltersList.length > 0) {
            this.activeFilters.style.display = 'block';
            
            this.activeFiltersList.forEach(filter => {
                const chip = this.createFilterChip(filter);
                this.filterChips.appendChild(chip);
            });
        } else {
            this.activeFilters.style.display = 'none';
        }
    }
    
    createFilterChip(filter) {
        const chip = document.createElement('div');
        chip.className = 'filter-chip';
        chip.innerHTML = `
            <span>${filter.label}</span>
            <button type="button" class="filter-chip-remove" data-filter-type="${filter.type}">
                <i class="fas fa-times"></i>
            </button>
        `;
        return chip;
    }
    
    removeFilterChip(removeBtn) {
        const filterType = removeBtn.getAttribute('data-filter-type');
        
        switch (filterType) {
            case 'search':
                this.form.querySelector('input[name="search"]').value = '';
                break;
            case 'min_price':
                this.form.querySelector('input[name="min_price"]').value = '';
                break;
            case 'max_price':
                this.form.querySelector('input[name="max_price"]').value = '';
                break;
            case 'quick':
                const activeQuickBtn = this.form.querySelector('.quick-filter-btn.active:not([data-filter="all"])');
                if (activeQuickBtn) {
                    activeQuickBtn.classList.remove('active');
                    this.form.querySelector('[data-filter="all"]').classList.add('active');
                }
                break;
        }
        
        this.updateFilterState();
    }
    
    updateClearAllButton() {
        if (this.activeFiltersList.length > 0) {
            this.clearAllBtn.style.display = 'block';
        } else {
            this.clearAllBtn.style.display = 'none';
        }
    }
    
    updateResultsCount() {
        // This would typically come from an API call
        // For now, we'll simulate it
        const productCount = document.querySelectorAll('.product-card').length;
        if (this.resultsCount && productCount > 0) {
            this.resultsCount.textContent = `(${productCount})`;
        }
    }
    
    initQuickFilters() {
        const quickFilterBtns = this.form.querySelectorAll('.quick-filter-btn');
        
        quickFilterBtns.forEach(btn => {
            btn.addEventListener('click', () => this.handleQuickFilter(btn));
        });
    }
    
    handleQuickFilter(btn) {
        // Remove active from all buttons
        const allBtns = this.form.querySelectorAll('.quick-filter-btn');
        allBtns.forEach(b => b.classList.remove('active'));
        
        // Add active to clicked button
        btn.classList.add('active');
        
        // Handle filter logic
        const filterType = btn.getAttribute('data-filter');
        
        switch (filterType) {
            case 'all':
                this.form.querySelector('input[name="min_price"]').value = '';
                this.form.querySelector('input[name="max_price"]').value = '';
                break;
            case 'under-100':
                this.form.querySelector('input[name="min_price"]').value = '';
                this.form.querySelector('input[name="max_price"]').value = '100';
                break;
            case 'premium':
                this.form.querySelector('input[name="min_price"]').value = '200';
                this.form.querySelector('input[name="max_price"]').value = '';
                break;
        }
        
        this.updateFilterState();
        
        // Add ripple effect
        this.addRippleEffect(btn);
    }
    
    addRippleEffect(element) {
        const ripple = document.createElement('div');
        ripple.style.cssText = `
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
        `;
        
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = '50%';
        ripple.style.top = '50%';
        ripple.style.marginLeft = -(size / 2) + 'px';
        ripple.style.marginTop = -(size / 2) + 'px';
        
        element.style.position = 'relative';
        element.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
    
    monitorInputChanges() {
        const inputs = this.form.querySelectorAll('input[type="text"], input[type="number"]');
        
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                // Debounce the update
                clearTimeout(this.updateTimeout);
                this.updateTimeout = setTimeout(() => {
                    this.updateFilterState();
                }, 300);
            });
        });
    }
    
    preventBodyScroll() {
        // Prevent body scroll when panel is open
        const originalStyle = window.getComputedStyle(document.body).overflow;
        
        const observer = new MutationObserver(() => {
            if (this.isOpen) {
                document.body.style.overflow = 'hidden';
            }
        });
        
        observer.observe(document.body, {
            attributes: true,
            attributeFilter: ['style']
        });
    }
    
    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existing = document.querySelectorAll('.mobile-filter-notification');
        existing.forEach(notif => notif.remove());
        
        // Create notification
        const notification = document.createElement('div');
        notification.className = `mobile-filter-notification notification-${type}`;
        notification.innerHTML = `
            <i class="fas fa-${this.getNotificationIcon(type)} me-2"></i>
            ${message}
        `;
        
        // Style notification
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: ${this.getNotificationColor(type)};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
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
        }, 3000);
    }
    
    getNotificationIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
    
    getNotificationColor(type) {
        const colors = {
            success: 'linear-gradient(135deg, #10b981, #059669)',
            error: 'linear-gradient(135deg, #ef4444, #dc2626)',
            warning: 'linear-gradient(135deg, #f59e0b, #d97706)',
            info: 'linear-gradient(135deg, #3b82f6, #2563eb)'
        };
        return colors[type] || colors.info;
    }
}

// CSS for animations (add to existing CSS)
const filterAnimationCSS = `
@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.mobile-filter-notification {
    animation: slideInRight 0.3s ease;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
    }
    to {
        transform: translateX(0);
    }
}
`;

// Inject CSS
const style = document.createElement('style');
style.textContent = filterAnimationCSS;
document.head.appendChild(style);

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize on mobile/tablet
    if (window.innerWidth < 992) {
        window.mobileFilter = new MobileFilter();
    }
    
    // Re-initialize on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth < 992 && !window.mobileFilter) {
            window.mobileFilter = new MobileFilter();
        } else if (window.innerWidth >= 992 && window.mobileFilter) {
            // Close panel if open
            if (window.mobileFilter.isOpen) {
                window.mobileFilter.closePanel();
            }
        }
    });
});

// Export for external use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MobileFilter;
}