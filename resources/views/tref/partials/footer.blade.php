{{-- Tref Footer Component --}}
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            {{-- Brand Column --}}
            <div class="footer-brand">
                <img src="{{ asset('images/tref-logo.png') }}" alt="Tref Stays" class="footer-logo">
                <p class="footer-desc">
                    Your trusted platform for finding the perfect vacation rental. 
                    Discover unique stays in the most desirable locations.
                </p>
                <div class="footer-social">
                    <a href="#" class="footer-social-link" aria-label="Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                        </svg>
                    </a>
                    <a href="#" class="footer-social-link" aria-label="Instagram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                        </svg>
                    </a>
                    <a href="#" class="footer-social-link" aria-label="Twitter">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
                        </svg>
                    </a>
                    <a href="#" class="footer-social-link" aria-label="LinkedIn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
                            <rect x="2" y="9" width="4" height="12"/>
                            <circle cx="4" cy="4" r="2"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            {{-- Properties Column --}}
            <div class="footer-column">
                <h4>Properties</h4>
                <ul class="footer-links">
                    <li><a href="#">Houses</a></li>
                    <li><a href="#">Apartments</a></li>
                    <li><a href="#">Villas</a></li>
                    <li><a href="#">Cottages</a></li>
                    <li><a href="#">Condos</a></li>
                </ul>
            </div>
            
            {{-- Company Column --}}
            <div class="footer-column">
                <h4>Company</h4>
                <ul class="footer-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Press</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Partners</a></li>
                </ul>
            </div>
            
            {{-- Support Column --}}
            <div class="footer-column">
                <h4>Support</h4>
                <ul class="footer-links">
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Cancellation Policy</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        
        {{-- Bottom Bar --}}
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Tref Stays. All rights reserved.</p>
            <p>Made with ❤️ for travelers everywhere</p>
        </div>
    </div>
</footer>
