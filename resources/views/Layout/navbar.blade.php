<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="toggler-line"></span>
            <span class="toggler-line"></span>
            <span class="toggler-line"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left Side Navigation -->
            <ul class="navbar-nav me-auto">
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
            
            <!-- Center Logo -->
             <!-- CENTER LOGO - ADD THIS -->
            <a class="navbar-brand mx-auto" href="{{ url('/') }}">
                <img src="{{ asset('images/logo2.png') }}" alt="TekSouq Logo" class="navbar-logo">
            </a>

            <!-- Right Side Navigation -->
            <ul class="navbar-nav">
                <!-- Search Icon -->
                {{-- <li class="nav-item">
                    <a class="nav-link nav-icon" href="#" data-bs-toggle="modal" data-bs-target="#searchModal" title="Search">
                        <i class="fas fa-search"></i>
                        <span class="icon-glow"></span>
                    </a>
                </li> --}}
                
              
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
                    <span class="icon-glow"></span>
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
                            <span class="user-name">Hi, {{ explode(' ', Auth::user()->name)[0] }}</span>
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
    </div>
</nav>

<!-- Search Modal -->
{{-- <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content search-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="searchModalLabel">Search Products</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="search-form">
                    <div class="search-input-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control search-input" placeholder="Search for smartwatches, accessories..." autocomplete="off">
                        <button type="submit" class="btn btn-primary-nav search-btn">Search</button>
                    </div>
                </form>
                <div class="search-suggestions">
                    <h6>Popular Searches</h6>
                    <div class="suggestion-tags">
                        <span class="tag">Apple Watch</span>
                        <span class="tag">Samsung Galaxy</span>
                        <span class="tag">Fitness Tracker</span>
                        <span class="tag">Sport Band</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}