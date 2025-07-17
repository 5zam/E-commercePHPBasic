<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <!-- Left Side Navigation - Desktop Only -->
        <div class="navbar-nav-left d-none d-lg-flex">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                        <span class="nav-text">Home</span>
                        <div class="nav-indicator"></div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('shop*') ? 'active' : '' }}" href="{{ route('shop') }}">
                        <span class="nav-text">Shop</span>
                        <div class="nav-indicator"></div>
                    </a>
                </li>
                @if(Route::has('policy'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('policy') ? 'active' : '' }}" href="{{ route('policy') }}">
                        <span class="nav-text">Policy</span>
                        <div class="nav-indicator"></div>
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <!-- Center Logo -->
        <a class="navbar-brand-center" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="TekSouq Logo" class="navbar-logo">
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Right Side Navigation - Desktop Only -->
        <div class="navbar-nav-right d-none d-lg-flex">
            <ul class="navbar-nav">
                <!-- Shopping Cart -->
                <li class="nav-item">
                    <a class="nav-link nav-icon position-relative" href="{{ route('cart.index') }}" title="Shopping Cart">
                        <i class="fas fa-shopping-bag"></i>
                        @php
                            try {
                                $cartService = app(\App\Services\CartService::class);
                                $cartCount = $cartService->count();
                            } catch (\Exception $e) {
                                $cartCount = 0;
                            }
                        @endphp
                        @if($cartCount > 0)
                            <span class="cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>
                
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link nav-auth" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="btn btn-primary-nav" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle user-dropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="user-name">{{ explode(' ', Auth::user()->name)[0] }}</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end user-menu" aria-labelledby="navbarDropdown">
                            <div class="dropdown-header">
                                <div class="user-info">
                                    <div class="user-avatar-large">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="user-details">
                                        <h6>{{ Auth::user()->name }}</h6>
                                        <small>{{ Auth::user()->email }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="dropdown-divider"></div>
                            
                            <a class="dropdown-item" href="#profile">
                                <i class="fas fa-user-circle me-2"></i>My Profile
                            </a>
                            <a class="dropdown-item" href="#orders">
                                <i class="fas fa-shopping-bag me-2"></i>My Orders
                            </a>
                            <a class="dropdown-item" href="#wishlist">
                                <i class="fas fa-heart me-2"></i>Wishlist
                            </a>
                            <a class="dropdown-item" href="#settings">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                            
                            <div class="dropdown-divider"></div>
                            
                            <a class="dropdown-item logout-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>

        <!-- Mobile Menu (Collapsed) - Mobile Only -->
        <div class="collapse navbar-collapse d-lg-none" id="navbarNav">
            <div class="mobile-nav-content">
                <!-- Mobile Navigation -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">
                            <i class="fas fa-home me-2"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop') }}">
                            <i class="fas fa-store me-2"></i>Shop
                        </a>
                    </li>
                    @if(Route::has('policy'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('policy') }}">
                            <i class="fas fa-shield-alt me-2"></i>Policy
                        </a>
                    </li>
                    @endif
                    
                    <div class="mobile-divider"></div>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-bag me-2"></i>Cart 
                            @if($cartCount > 0)
                                <span class="badge bg-primary ms-2">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="#profile">
                                <i class="fas fa-user-circle me-2"></i>Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#orders">
                                <i class="fas fa-shopping-bag me-2"></i>My Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</nav>