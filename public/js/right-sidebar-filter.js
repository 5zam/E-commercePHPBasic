// Right Sidebar Filter System
class RightSidebarFilter {
    constructor() {
        this.sidebar = null;
        this.toggleBtn = null;
        this.backdrop = null;
        this.shopContent = null;
        this.shopHeader = null;
        this.isOpen = false;
        this.isDesktop = window.innerWidth >= 992;
        
        this.init();
    }
    
    init() {
        // Only work on desktop
        if (!this.isDesktop) return;
        
        this.createToggleButton();
        this.createSidebar();
        this.bindEvents();
        this.setupResizeHandler();
    }
    
    createToggleButton() {
        // Find the quick actions container and replace it with filter button
        const quickActions = document.querySelector('.quick-actions');
        if (quickActions) {
            // Replace quick actions with filter button
            quickActions.innerHTML = `
                <button class="filter-toggle-btn" id="rightFilterToggle">
                    <i class="fas fa-sliders-h filter-icon"></i>
                    <span>Filters</span>
                </button>
            `;
            quickActions.style.display = 'flex';
            
            this.toggleBtn = document.getElementById('rightFilterToggle');
        } else {
            // If quick actions not found, create in shop header right
            const shopHeaderRight = document.querySelector('.shop-header-right');
            if (shopHeaderRight) {
                const filterBtn = document.createElement('button');
                filterBtn.className = 'filter-toggle-btn';
                filterBtn.id = 'rightFilterToggle';
                filterBtn.innerHTML = `
                    <i class="fas fa-sliders-h filter-icon"></i>
                    <span>Filters</span>
                `;
                
                shopHeaderRight.appendChild(filterBtn);
                this.toggleBtn = filterBtn;
            }
        }
    }
    
    createSidebar() {
        // Create sidebar container
        this.sidebar = document.createElement('div');
        this.sidebar.className = 'right-filter-sidebar';
        this.sidebar.id = 'rightFilterSidebar';
        
        // Clone existing filters
        const existingFilters = document.querySelector('.shop-filters');
        if (existingFilters) {
            const filtersClone = existingFilters.cloneNode(true);
            
            // Create sidebar content structure
            const sidebarContent = document.createElement('div');
            sidebarContent.className = 'right-sidebar-content';
            
            // Create header
            const header = document.createElement('div');
            header.className = 'right-sidebar-header';
            header.innerHTML = `
                <h3>
                    <i class="fas fa-sliders-h"></i>
                    Filters
                </h3>
                <button class="close-sidebar-btn" id="closeSidebarBtn">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Update form ID to avoid conflicts
            const form = filtersClone.querySelector('#filterForm');
            if (form) {
                form.id = 'rightFilterForm';
            }
            
            // Remove original classes and add new ones
            filtersClone.className = 'right-sidebar-filters';
            
            // Assemble sidebar
            sidebarContent.appendChild(header);
            sidebarContent.appendChild(filtersClone);
            this.sidebar.appendChild(sidebarContent);
        }
        
        // Create backdrop
        this.backdrop = document.createElement('div');
        this.backdrop.className = 'right-sidebar-backdrop';
        this.backdrop.id = 'rightSidebarBackdrop';
        
        // Add to DOM
        document.body.appendChild(this.sidebar);
        document.body.appendChild(this.backdrop);
        
        // Get references
        this.shopContent = document.querySelector('.shop-content');
        this.shopHeader = document.querySelector('.shop-header');
    }
    
    bindEvents() {
        // Toggle button
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', () => this.toggle());
        }
        
        // Close button
        const closeBtn = document.getElementById('closeSidebarBtn');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.close());
        }
        
        // Backdrop click
        if (this.backdrop) {
            this.backdrop.addEventListener('click', () => this.close());
        }
        
        // ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.close();
            }
        });
        
        // Form auto-submit
        const form = this.sidebar?.querySelector('#rightFilterForm');
        if (form) {
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('change', () => {
                    setTimeout(() => form.submit(), 100);
                });
            });
        }
    }
    
    setupResizeHandler() {
        window.addEventListener('resize', () => {
            const wasDesktop = this.isDesktop;
            this.isDesktop = window.innerWidth >= 992;
            
            // If no longer desktop, cleanup
            if (wasDesktop && !this.isDesktop) {
                this.destroy();
            }
            // If became desktop, reinit
            else if (!wasDesktop && this.isDesktop) {
                this.init();
            }
        });
    }
    
    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }
    
    open() {
        if (!this.sidebar || this.isOpen) return;
        
        this.isOpen = true;
        
        // Add active classes
        this.sidebar.classList.add('active');
        this.toggleBtn?.classList.add('active');
        this.backdrop?.classList.add('active');
        
        // Push content left
        if (this.shopContent) {
            this.shopContent.classList.add('right-sidebar-active');
        }
        
        if (this.shopHeader) {
            this.shopHeader.classList.add('right-sidebar-active');
        }
        
        // Update button text
        const span = this.toggleBtn?.querySelector('span');
        if (span) span.textContent = 'Hide';
        
        // Focus first input
        setTimeout(() => {
            const firstInput = this.sidebar.querySelector('input');
            if (firstInput) firstInput.focus();
        }, 300);
        
        // Show notification
        this.showNotification('Filters panel opened', 'info');
    }
    
    close() {
        if (!this.sidebar || !this.isOpen) return;
        
        this.isOpen = false;
        
        // Remove active classes
        this.sidebar.classList.remove('active');
        this.toggleBtn?.classList.remove('active');
        this.backdrop?.classList.remove('active');
        
        // Reset content position
        if (this.shopContent) {
            this.shopContent.classList.remove('right-sidebar-active');
        }
        
        if (this.shopHeader) {
            this.shopHeader.classList.remove('right-sidebar-active');
        }
        
        // Update button text
        const span = this.toggleBtn?.querySelector('span');
        if (span) span.textContent = 'Filters';
        
        // Show notification
        this.showNotification('Filters panel closed', 'info');
    }
    
    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existing = document.querySelectorAll('.right-filter-notification');
        existing.forEach(notif => notif.remove());
        
        // Create notification
        const notification = document.createElement('div');
        notification.className = `right-filter-notification notification-${type}`;
        
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
            <i class="fas fa-${icons[type]} me-2"></i>
            ${message}
        `;
        
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: ${colors[type]};
            color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            min-width: 200px;
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
        }, 2500);
    }
    
    destroy() {
        // Close if open
        if (this.isOpen) {
            this.close();
        }
        
        // Remove elements
        if (this.sidebar) {
            this.sidebar.remove();
            this.sidebar = null;
        }
        
        if (this.backdrop) {
            this.backdrop.remove();
            this.backdrop = null;
        }
        
        // Reset quick actions if it was replaced
        const quickActions = document.querySelector('.quick-actions');
        if (quickActions && this.toggleBtn) {
            quickActions.innerHTML = `
                <button class="quick-action-btn" onclick="toggleGridDensity()" title="Toggle grid density">
                    <i class="fas fa-expand-arrows-alt"></i>
                </button>
                <button class="quick-action-btn" onclick="refreshProducts()" title="Refresh products">
                    <i class="fas fa-sync-alt"></i>
                </button>
            `;
        }
        
        // Clear references
        this.toggleBtn = null;
        this.shopContent = null;
        this.shopHeader = null;
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Only on desktop
    if (window.innerWidth >= 992) {
        window.rightSidebarFilter = new RightSidebarFilter();
    }
});

// Handle resize
window.addEventListener('resize', function() {
    const isDesktop = window.innerWidth >= 992;
    
    if (isDesktop && !window.rightSidebarFilter) {
        // Create when switching to desktop
        window.rightSidebarFilter = new RightSidebarFilter();
    } else if (!isDesktop && window.rightSidebarFilter) {
        // Destroy when switching to mobile
        window.rightSidebarFilter.destroy();
        window.rightSidebarFilter = null;
    }
});