<footer class="footer">
    <div class="footer-background">
        <div class="footer-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
    </div>
    
    <div class="container">
        <!-- Main Footer Content -->
        <div class="row footer-main">
            <!-- Brand Section -->
            <div class="col-12 text-center mb-1">
                <div class="footer-brand">
                    <a href="{{ url('/') }}" class="brand-container">
                        <img src="{{ asset('images/logo.png') }}" alt="TekSouq Logo" class="footer-logo">
                    </a>
                </div>
            </div> 
        </div>
        
        <!-- Footer Bottom -->
        <div class="row footer-bottom">
            <div class="col-lg-6">
                <p class="copyright">
                    &copy; {{ date('Y') }} TekSouq. All rights reserved. 
                </p>
            </div>
            <div class="col-lg-6">
                <div class="footer-bottom-links">
                    <a href="{{ route('policy') ?? '#' }}">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" title="Back to Top">
        <i class="fas fa-chevron-up"></i>
    </button>
</footer>