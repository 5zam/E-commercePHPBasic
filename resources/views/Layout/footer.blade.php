{{-- <footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Tek Souq. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer> --}}

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
            <div class="col-lg-4 col-md-6 mb-1">
                <div class="footer-brand">
                    <div class="brand-container">
                        <div class="brand-icon">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <span class="brand-text">TEKSOUQ</span>
                    </div>
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