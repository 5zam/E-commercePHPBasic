// // Sort Functionality Fix
// document.addEventListener('DOMContentLoaded', function() {
    
//     // Sort functionality
//     window.updateSort = function(sortValue) {
//         console.log('Sorting by:', sortValue); // للتأكد إن الفنكشن شغالة
        
//         const url = new URL(window.location);
//         url.searchParams.set('sort', sortValue);
        
//         // Show loading feedback
//         const sortSelect = document.querySelector('.shop-sort');
//         if (sortSelect) {
//             sortSelect.disabled = true;
//             sortSelect.style.opacity = '0.7';
            
//             // Add loading indicator
//             const originalText = sortSelect.options[sortSelect.selectedIndex].text;
//             sortSelect.options[sortSelect.selectedIndex].text = 'Loading...';
//         }
        
//         // Show notification if function exists
//         if (typeof showEnhancedNotification === 'function') {
//             showEnhancedNotification('Sorting products...', 'info');
//         }
        
//         // Navigate to new URL
//         window.location.href = url.toString();
//     };
    
//     // Alternative: Direct event listener on sort dropdown
//     const shopSort = document.querySelector('.shop-sort');
//     if (shopSort) {
//         shopSort.addEventListener('change', function() {
//             console.log('Sort changed to:', this.value);
//             updateSort(this.value);
//         });
//     }
    
//     // View toggle functionality
//     document.querySelectorAll('.view-toggle').forEach(toggle => {
//         toggle.addEventListener('click', function() {
//             console.log('View toggle clicked:', this.dataset.view);
            
//             // Update active state
//             document.querySelectorAll('.view-toggle').forEach(t => t.classList.remove('active'));
//             this.classList.add('active');
            
//             const view = this.dataset.view;
//             const grid = document.getElementById('productsGrid');
            
//             if (grid) {
//                 if (view === 'list') {
//                     grid.classList.add('list-view');
//                 } else {
//                     grid.classList.remove('list-view');
//                 }
                
//                 // Show notification if function exists
//                 if (typeof showEnhancedNotification === 'function') {
//                     showEnhancedNotification(`Switched to ${view} view`, 'info');
//                 }
//             }
//         });
//     });
    
//     // Filter form functionality
//     const filterForm = document.getElementById('filterForm');
//     if (filterForm) {
//         const inputs = filterForm.querySelectorAll('input, select');
        
//         inputs.forEach(input => {
//             input.addEventListener('change', function() {
//                 console.log('Filter changed:', this.name, this.value);
                
//                 // Add small delay to prevent too many submissions
//                 setTimeout(() => {
//                     filterForm.submit();
//                 }, 100);
//             });
//         });
//     }
    
//     // Enhanced notification function (fallback)
//     if (typeof window.showEnhancedNotification === 'undefined') {
//         window.showEnhancedNotification = function(message, type = 'info') {
//             console.log(`${type.toUpperCase()}: ${message}`);
            
//             // Simple notification
//             const notification = document.createElement('div');
//             notification.style.cssText = `
//                 position: fixed;
//                 top: 100px;
//                 right: 20px;
//                 background: #4f46e5;
//                 color: white;
//                 padding: 1rem;
//                 border-radius: 8px;
//                 z-index: 10000;
//                 font-weight: 600;
//                 transform: translateX(100%);
//                 transition: transform 0.3s ease;
//             `;
//             notification.textContent = message;
            
//             document.body.appendChild(notification);
            
//             setTimeout(() => {
//                 notification.style.transform = 'translateX(0)';
//             }, 100);
            
//             setTimeout(() => {
//                 notification.style.transform = 'translateX(100%)';
//                 setTimeout(() => notification.remove(), 300);
//             }, 3000);
//         };
//     }
    
//     console.log('Sort functionality initialized');
// });