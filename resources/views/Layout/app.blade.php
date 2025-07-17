<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'TekSouq - Tech at Hand')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    @include('layout.navbar')
    
    <main class="main-content">
        @yield('content')
    </main>
    
    @include('layout.footer')
    
    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
 <!-- Custom JS -->
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (navbar) {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }
        });

        // Back to top button
        const backToTopBtn = document.getElementById('backToTop');
        if (backToTopBtn) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    backToTopBtn.classList.add('show');
                } else {
                    backToTopBtn.classList.remove('show');
                }
            });

            backToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Form validation enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.needs-validation');
            forms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            });
        });

        // Enhanced user experience for dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('shown.bs.dropdown', function() {
                    this.setAttribute('aria-expanded', 'true');
                });
                dropdown.addEventListener('hidden.bs.dropdown', function() {
                    this.setAttribute('aria-expanded', 'false');
                });
            });
        });

        // ========== MOBILE MENU FIX ==========
        document.addEventListener('DOMContentLoaded', function() {
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            const navLinks = document.querySelectorAll('.mobile-nav-content .nav-link');

            // إغلاق القائمة عند الضغط على أي رابط
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                        navbarCollapse.classList.remove('show');
                        if (navbarToggler) {
                            navbarToggler.setAttribute('aria-expanded', 'false');
                            navbarToggler.classList.add('collapsed');
                        }
                    }
                });
            });

            // إغلاق القائمة عند الضغط خارجها
            document.addEventListener('click', function(event) {
                const isToggler = navbarToggler && navbarToggler.contains(event.target);
                const isMenu = navbarCollapse && navbarCollapse.contains(event.target);
                
                if (!isToggler && !isMenu && navbarCollapse && navbarCollapse.classList.contains('show')) {
                    navbarCollapse.classList.remove('show');
                    if (navbarToggler) {
                        navbarToggler.setAttribute('aria-expanded', 'false');
                        navbarToggler.classList.add('collapsed');
                    }
                }
            });

            // تأكد من إغلاق القائمة عند تغيير حجم الشاشة للديسكتوب
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992 && navbarCollapse && navbarCollapse.classList.contains('show')) {
                    navbarCollapse.classList.remove('show');
                    if (navbarToggler) {
                        navbarToggler.setAttribute('aria-expanded', 'false');
                        navbarToggler.classList.add('collapsed');
                    }
                }
            });

            // إضافة animation للقائمة
            if (navbarToggler && navbarCollapse) {
                navbarToggler.addEventListener('click', function() {
                    setTimeout(function() {
                        if (navbarCollapse.classList.contains('show')) {
                            navbarCollapse.style.animation = 'slideDown 0.3s ease';
                        }
                    }, 10);
                });
            }
        });

        // ========== CART FUNCTIONALITY ==========
        
        // Update cart count dynamically
        function updateCartCount() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    const cartBadge = document.querySelector('.cart-badge');
                    if (data.count > 0) {
                        if (cartBadge) {
                            cartBadge.textContent = data.count;
                        } else {
                            // Create badge if it doesn't exist
                            const cartLink = document.querySelector('.nav-link[title="Shopping Cart"]');
                            if (cartLink) {
                                const badge = document.createElement('span');
                                badge.className = 'cart-badge';
                                badge.textContent = data.count;
                                cartLink.appendChild(badge);
                            }
                        }
                    } else {
                        if (cartBadge) {
                            cartBadge.remove();
                        }
                    }
                })
                .catch(error => console.error('Error updating cart count:', error));
        }

        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', updateCartCount);

        // Update cart count after form submissions
        document.addEventListener('submit', function(e) {
            if (e.target.classList.contains('add-to-cart-form') || 
                e.target.classList.contains('quantity-form') ||
                e.target.classList.contains('remove-form')) {
                setTimeout(updateCartCount, 500);
            }
        });

        // Quantity controls for add to cart
        document.addEventListener('DOMContentLoaded', function() {
            // Handle quantity decrease/increase buttons
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('qty-decrease') || e.target.closest('.qty-decrease')) {
                    e.preventDefault();
                    const button = e.target.closest('.qty-decrease');
                    const input = button.parentElement.querySelector('.qty-input');
                    let value = parseInt(input.value);
                    if (value > 1) {
                        input.value = value - 1;
                    }
                }
                
                if (e.target.classList.contains('qty-increase') || e.target.closest('.qty-increase')) {
                    e.preventDefault();
                    const button = e.target.closest('.qty-increase');
                    const input = button.parentElement.querySelector('.qty-input');
                    let value = parseInt(input.value);
                    let max = parseInt(input.getAttribute('max'));
                    if (value < max) {
                        input.value = value + 1;
                    }
                }
            });

            // Handle cart page quantity controls
            const qtyBtns = document.querySelectorAll('.qty-btn');
            qtyBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const action = this.dataset.action;
                    const input = this.closest('.quantity-controls').querySelector('.qty-input');
                    let value = parseInt(input.value);
                    const max = parseInt(input.getAttribute('max'));
                    
                    if (action === 'increase' && value < max) {
                        input.value = value + 1;
                    } else if (action === 'decrease' && value > 1) {
                        input.value = value - 1;
                    }
                });
            });
            
            // Auto-show update button when quantity changes
            const qtyInputs = document.querySelectorAll('.qty-input');
            qtyInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const form = this.closest('.quantity-form');
                    if (form) {
                        const updateBtn = form.querySelector('.update-btn');
                        if (updateBtn) {
                            updateBtn.style.display = 'inline-block';
                        }
                    }
                });
            });
        });

        // Show success message after adding to cart
        document.addEventListener('submit', function(e) {
            if (e.target.classList.contains('add-to-cart-form')) {
                e.target.querySelector('.add-to-cart-btn').innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
                e.target.querySelector('.add-to-cart-btn').disabled = true;
            }
        });
        
    </script>
    
    @stack('scripts')
</body>
</html>