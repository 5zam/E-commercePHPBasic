{{-- Mobile Filter Toggle Button --}}
<div class="mobile-filter-toggle d-lg-none">
    <button type="button" 
            class="filter-toggle-btn" 
            id="mobileFilterToggle"
            aria-label="Open Filters">
        <div class="filter-btn-content">
            <i class="fas fa-sliders-h filter-icon"></i>
            <span class="filter-text">Filters</span>
            <div class="filter-badge" id="filterBadge" style="display: none;">
                <span id="filterCount">0</span>
            </div>
        </div>
        <div class="filter-btn-glow"></div>
    </button>
</div>

<style>
/* Mobile Filter Toggle Button */
.mobile-filter-toggle {
    position: fixed;
    bottom: 20px;
    left: 20px;
    z-index: 1000;
}

.filter-toggle-btn {
    position: relative;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border: none;
    border-radius: 50px;
    padding: 14px 20px;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    overflow: hidden;
    min-width: 110px;
}

.filter-toggle-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(79, 70, 229, 0.5);
}

.filter-toggle-btn:active {
    transform: translateY(-1px);
}

.filter-btn-content {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.filter-icon {
    font-size: 1rem;
    transition: transform 0.3s ease;
}

.filter-toggle-btn:hover .filter-icon {
    transform: rotate(10deg);
}

.filter-text {
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.filter-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
    border: 2px solid rgba(255, 255, 255, 0.9);
    animation: pulse-badge 2s infinite;
}

.filter-btn-glow {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
    border-radius: 50px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.filter-toggle-btn:hover .filter-btn-glow {
    opacity: 1;
}

@keyframes pulse-badge {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* Active state when panel is open */
.filter-toggle-btn.active {
    background: linear-gradient(135deg, #3730a3, #6d28d9);
    transform: translateY(-2px);
}

.filter-toggle-btn.active .filter-icon {
    transform: rotate(90deg);
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .mobile-filter-toggle {
        bottom: 15px;
        left: 15px;
    }
    
    .filter-toggle-btn {
        padding: 12px 18px;
        min-width: 100px;
    }
    
    .filter-text {
        font-size: 0.8rem;
    }
    
    .filter-badge {
        width: 20px;
        height: 20px;
        font-size: 0.65rem;
    }
}

/* Hide on desktop */
@media (min-width: 992px) {
    .mobile-filter-toggle {
        display: none !important;
    }
}
</style>