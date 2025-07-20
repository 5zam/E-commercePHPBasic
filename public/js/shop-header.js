// // Simple Shop Header JavaScript - Final Version

// document.addEventListener('DOMContentLoaded', function() {
//     const filterBtn = document.getElementById('filterToggle');
    
//     if (filterBtn) {
//         filterBtn.addEventListener('click', function() {
//             console.log('Filter button clicked!');
            
//             if (window.innerWidth >= 992) {
//                 // Desktop: Toggle right sidebar
//                 console.log('Desktop mode');
//                 const sidebar = document.querySelector('.right-filter-sidebar');
//                 const content = document.querySelector('.shop-content');
//                 const header = document.querySelector('.shop-header');
                
//                 if (sidebar) {
//                     const isActive = sidebar.classList.contains('active');
                    
//                     if (isActive) {
//                         sidebar.classList.remove('active');
//                         content?.classList.remove('right-sidebar-active');
//                         header?.classList.remove('right-sidebar-active');
//                         filterBtn.classList.remove('active');
//                         console.log('Sidebar closed');
//                     } else {
//                         sidebar.classList.add('active');
//                         content?.classList.add('right-sidebar-active');
//                         header?.classList.add('right-sidebar-active');
//                         filterBtn.classList.add('active');
//                         console.log('Sidebar opened');
//                     }
//                 } else {
//                     console.log('Right sidebar not found');
//                 }
//             } else {
//                 // Mobile: Toggle mobile panel
//                 console.log('Mobile mode');
//                 const panel = document.getElementById('mobileFilterPanel');
                
//                 if (panel) {
//                     const isActive = panel.classList.contains('active');
                    
//                     if (isActive) {
//                         panel.classList.remove('active');
//                         filterBtn.classList.remove('active');
//                         document.body.style.overflow = '';
//                         console.log('Mobile panel closed');
//                     } else {
//                         panel.classList.add('active');
//                         filterBtn.classList.add('active');
//                         document.body.style.overflow = 'hidden';
//                         console.log('Mobile panel opened');
//                     }
//                 } else {
//                     console.log('Mobile panel not found');
//                 }
//             }
//         });
//     } else {
//         console.log('Filter button not found');
//     }
    
//     // Sort functionality
//     window.updateSort = function(sortValue) {
//         const url = new URL(window.location);
//         url.searchParams.set('sort', sortValue);
//         window.location.href = url.toString();
//     };
    
//     // Category sort functionality
//     window.updateCategorySort = function(sortValue) {
//         console.log('Categories sorted by:', sortValue);
//         // Add actual sorting logic here
//     };
    
//     // Category view toggle for buttons
//     const categoryViewToggles = document.querySelectorAll('.view-toggle[data-view]');
//     categoryViewToggles.forEach(toggle => {
//         if (toggle.tagName === 'BUTTON') {
//             toggle.addEventListener('click', function() {
//                 const view = this.dataset.view;
//                 const grid = document.getElementById('categoriesGrid');
                
//                 if (grid) {
//                     // Update active state
//                     categoryViewToggles.forEach(t => t.classList.remove('active'));
//                     this.classList.add('active');
                    
//                     if (view === 'list') {
//                         grid.classList.add('categories-list-view');
//                     } else {
//                         grid.classList.remove('categories-list-view');
//                     }
                    
//                     console.log('Category view changed to:', view);
//                 }
//             });
//         }
//     });
// });

// // Simple notification function
// function showNotification(message, type = 'info') {
//     console.log(`${type.toUpperCase()}: ${message}`);
    
//     const notification = document.createElement('div');
//     notification.style.cssText = `
//         position: fixed;
//         top: 100px;
//         right: 20px;
//         background: #4f46e5;
//         color: white;
//         padding: 1rem;
//         border-radius: 8px;
//         z-index: 10000;
//         font-weight: 600;
//         transform: translateX(100%);
//         transition: transform 0.3s ease;
//     `;
//     notification.textContent = message;
    
//     document.body.appendChild(notification);
    
//     setTimeout(() => {
//         notification.style.transform = 'translateX(0)';
//     }, 100);
    
//     setTimeout(() => {
//         notification.style.transform = 'translateX(100%)';
//         setTimeout(() => notification.remove(), 300);
//     }, 3000);
// }

// // Global functions
// window.showEnhancedNotification = showNotification;
// window.clearAllFilters = function() {
//     window.location.href = window.location.pathname;
// };