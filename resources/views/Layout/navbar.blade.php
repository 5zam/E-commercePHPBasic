<nav class="bg-white shadow-lg border-b-2 border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-primary">
                    World Store
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary transition-colors">
                    Home
                </a>
                <a href="{{ route('products') }}" class="text-gray-700 hover:text-primary transition-colors">
                    Products
                </a>
                <a href="{{ route('policy') }}" class="text-gray-700 hover:text-primary transition-colors">
                    Policy
                </a>
                <a href="{{ route('cart') }}" class="text-gray-700 hover:text-primary transition-colors relative">
                    Cart
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        0
                    </span>
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition-colors">
                    Register
                </a>
            </div>

            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-gray-700 hover:text-primary">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="md:hidden hidden bg-white border-t">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:bg-amber-50 hover:text-primary">
                Home
            </a>
            <a href="{{ route('products') }}" class="block px-3 py-2 text-gray-700 hover:bg-amber-50 hover:text-primary">
                Products
            </a>
            <a href="{{ route('cart') }}" class="block px-3 py-2 text-gray-700 hover:bg-amber-50 hover:text-primary">
                Cart
            </a>
            <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary">
                Login
            </a>
            <a href="{{ route('register') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary">
                Register
            </a>
        </div>
    </div>
</nav>

<script>
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>