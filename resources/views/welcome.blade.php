<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ config('app.name') }} - Find Your Perfect Stay</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Discover unique vacation rentals, homes, and experiences around the world.">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon.png') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Plugins -->
    <link href="{{ asset('/plugins/bootstrap-daterangepicker2/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('/css/tref-stays.css') }}" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        body { margin: 0; padding: 0; }
    </style>
</head>
<body class="tref-page">
    
    <!-- Navigation -->
    <nav class="tref-navbar" id="mainNav">
        <div class="tref-navbar-container">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="tref-logo">
                <img src="{{ asset('/images/logo.png') }}" alt="{{ config('app.name') }}">
                <span class="tref-logo-text">{{ config('app.name') }}</span>
            </a>
            
            <!-- Mini Search Bar (visible on scroll) -->
            <div class="tref-search-bar" id="navSearchBar" style="display: none;">
                <button type="button" class="tref-search-bar-item">Anywhere</button>
                <div class="tref-search-bar-divider"></div>
                <button type="button" class="tref-search-bar-item">Any week</button>
                <div class="tref-search-bar-divider"></div>
                <button type="button" class="tref-search-bar-item" style="color: #717171;">Add guests</button>
                <button type="button" class="tref-search-bar-btn" onclick="document.getElementById('heroSearch').scrollIntoView({behavior: 'smooth'})">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
            <!-- Nav Links -->
            <div class="tref-nav-links">
                <a href="{{ route('search') }}" class="tref-nav-link">Explore</a>
                <a href="{{ route('login') }}" class="tref-nav-link">List your property</a>
                
                <div class="tref-user-menu" onclick="toggleUserMenu()">
                    <i class="fas fa-bars tref-user-menu-icon"></i>
                    <div class="tref-user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                
                <!-- Dropdown Menu -->
                <div class="tref-user-dropdown" id="userDropdown" style="display: none; position: absolute; right: 40px; top: 70px; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); min-width: 220px; z-index: 1001;">
                    <div style="padding: 8px 0;">
                        <a href="{{ route('login') }}" style="display: block; padding: 12px 16px; text-decoration: none; color: #222; font-weight: 600;">Log in</a>
                        <a href="{{ route('register') }}" style="display: block; padding: 12px 16px; text-decoration: none; color: #222;">Sign up</a>
                        <hr style="margin: 8px 0; border: none; border-top: 1px solid #ddd;">
                        <a href="{{ route('search') }}" style="display: block; padding: 12px 16px; text-decoration: none; color: #222;">Explore properties</a>
                        <a href="#" style="display: block; padding: 12px 16px; text-decoration: none; color: #222;">Help Center</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="tref-hero">
        <div class="tref-hero-bg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
        <div class="tref-hero-overlay"></div>
        
        <div class="tref-hero-content">
            <h1 class="tref-hero-title">Find Your Perfect Getaway</h1>
            <p class="tref-hero-subtitle">Discover unique vacation rentals, cozy homes, and unforgettable experiences</p>
            
            <!-- Hero Search Box -->
            <form id="heroSearch" class="tref-hero-search" method="get" action="{{ url('search') }}">
                <div class="tref-hero-search-field">
                    <label>Where</label>
                    <select id="search_room_zipcode" name="zipcode" data-minimum-input="1" 
                            data-url="{{ route('zipcode_lookup') }}" data-role="title-location"
                            data-placeholder="Search destinations" data-allow-clear="true"
                            style="border: none; width: 100%; outline: none; font-size: 14px; background: transparent;">
                    </select>
                </div>
                
                <div class="tref-hero-search-field">
                    <label>Check in</label>
                    <input type="text" id="search_check_in_display" placeholder="Add dates" readonly>
                    <input type="hidden" id="search_check_in" name="check_in">
                </div>
                
                <div class="tref-hero-search-field">
                    <label>Check out</label>
                    <input type="text" id="search_check_out_display" placeholder="Add dates" readonly>
                    <input type="hidden" id="search_check_out" name="check_out">
                </div>
                
                <div class="tref-hero-search-field">
                    <label>Guests</label>
                    <input type="number" id="search_guest_count" name="guest_count" placeholder="Add guests" min="1" max="20">
                </div>
                
                <button type="submit" class="tref-hero-search-btn">
                    <i class="fas fa-search"></i>
                    Search
                </button>
            </form>
        </div>
    </section>
    
    <!-- Property Categories -->
    <section class="tref-section">
        <div class="tref-section-header">
            <h2 class="tref-section-title">Explore by property type</h2>
            <a href="{{ route('search') }}" class="tref-section-link">
                View all <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="tref-categories">
            <a href="{{ route('search') }}?type=house" class="tref-category-card">
                <div class="tref-category-icon">🏠</div>
                <span class="tref-category-name">Houses</span>
            </a>
            <a href="{{ route('search') }}?type=apartment" class="tref-category-card">
                <div class="tref-category-icon">🏢</div>
                <span class="tref-category-name">Apartments</span>
            </a>
            <a href="{{ route('search') }}?type=villa" class="tref-category-card">
                <div class="tref-category-icon">🏰</div>
                <span class="tref-category-name">Villas</span>
            </a>
            <a href="{{ route('search') }}?type=cabin" class="tref-category-card">
                <div class="tref-category-icon">🏕️</div>
                <span class="tref-category-name">Cabins</span>
            </a>
            <a href="{{ route('search') }}?type=beach" class="tref-category-card">
                <div class="tref-category-icon">🏖️</div>
                <span class="tref-category-name">Beach Houses</span>
            </a>
            <a href="{{ route('search') }}?type=unique" class="tref-category-card">
                <div class="tref-category-icon">✨</div>
                <span class="tref-category-name">Unique Stays</span>
            </a>
        </div>
    </section>
    
    <!-- Featured Properties -->
    <section class="tref-section" style="background: #f7f7f7;">
        <div class="tref-section-header">
            <h2 class="tref-section-title">Popular destinations</h2>
            <a href="{{ route('search') }}" class="tref-section-link">
                Show all <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="tref-properties" id="featuredProperties">
            <!-- Properties will be loaded dynamically -->
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                <div class="tref-spinner" style="margin: 0 auto 20px;"></div>
                <p style="color: #717171;">Loading featured properties...</p>
            </div>
        </div>
    </section>
    
    <!-- Why Choose Us -->
    <section class="tref-section">
        <div class="tref-section-header">
            <h2 class="tref-section-title">Why choose {{ config('app.name') }}</h2>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 32px;">
            <div style="text-align: center; padding: 32px;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #FF385C 0%, #E31C5F 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <i class="fas fa-shield-alt" style="font-size: 32px; color: white;"></i>
                </div>
                <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 12px;">Trusted & Secure</h3>
                <p style="color: #717171; line-height: 1.6;">All properties are verified and bookings are protected with our secure payment system.</p>
            </div>
            
            <div style="text-align: center; padding: 32px;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #00A699 0%, #00857A 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <i class="fas fa-headset" style="font-size: 32px; color: white;"></i>
                </div>
                <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 12px;">24/7 Support</h3>
                <p style="color: #717171; line-height: 1.6;">Our dedicated support team is available around the clock to assist you.</p>
            </div>
            
            <div style="text-align: center; padding: 32px;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <i class="fas fa-tag" style="font-size: 32px; color: white;"></i>
                </div>
                <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 12px;">Best Price Guarantee</h3>
                <p style="color: #717171; line-height: 1.6;">Find a lower price? We'll match it and give you an extra 10% off.</p>
            </div>
        </div>
    </section>
    
    <!-- Call to Action -->
    <section style="background: linear-gradient(135deg, #222 0%, #444 100%); padding: 80px 40px; text-align: center;">
        <div style="max-width: 700px; margin: 0 auto;">
            <h2 style="font-size: 36px; font-weight: 700; color: white; margin-bottom: 16px;">Ready to find your perfect stay?</h2>
            <p style="font-size: 18px; color: rgba(255,255,255,0.8); margin-bottom: 32px;">Join thousands of travelers who have discovered their dream vacation rentals.</p>
            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('register') }}" class="tref-btn tref-btn-primary tref-btn-lg">
                    Get Started <i class="fas fa-arrow-right"></i>
                </a>
                <a href="{{ route('search') }}" class="tref-btn tref-btn-outline tref-btn-lg" style="border-color: white; color: white;">
                    Explore Properties
                </a>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="tref-footer">
        <div class="tref-footer-container">
            <div class="tref-footer-grid">
                <div class="tref-footer-column">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Safety Information</a></li>
                        <li><a href="#">Cancellation Options</a></li>
                        <li><a href="#">Report a Concern</a></li>
                    </ul>
                </div>
                
                <div class="tref-footer-column">
                    <h4>Community</h4>
                    <ul>
                        <li><a href="#">Diversity & Belonging</a></li>
                        <li><a href="#">Accessibility</a></li>
                        <li><a href="#">Referrals</a></li>
                        <li><a href="#">Gift Cards</a></li>
                    </ul>
                </div>
                
                <div class="tref-footer-column">
                    <h4>Hosting</h4>
                    <ul>
                        <li><a href="{{ route('login') }}">List your property</a></li>
                        <li><a href="#">Host Resources</a></li>
                        <li><a href="#">Community Forum</a></li>
                        <li><a href="#">Hosting Responsibly</a></li>
                    </ul>
                </div>
                
                <div class="tref-footer-column">
                    <h4>{{ config('app.name') }}</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Press</a></li>
                        <li><a href="#">Policies</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="tref-footer-bottom">
                <div class="tref-footer-copyright">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    <span style="margin: 0 8px;">·</span>
                    <a href="#" style="color: inherit;">Privacy</a>
                    <span style="margin: 0 8px;">·</span>
                    <a href="#" style="color: inherit;">Terms</a>
                </div>
                
                <div class="tref-footer-social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="{{ asset('/plugins/common/common.min.js') }}"></script>
    <script src="{{ asset('/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-daterangepicker2/daterangepicker.js') }}"></script>
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    
    <script>
        // User Menu Toggle
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdown');
            const userMenu = document.querySelector('.tref-user-menu');
            if (!userMenu.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
        
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNav');
            const searchBar = document.getElementById('navSearchBar');
            
            if (window.scrollY > 300) {
                navbar.classList.add('scrolled');
                searchBar.style.display = 'flex';
            } else {
                navbar.classList.remove('scrolled');
                searchBar.style.display = 'none';
            }
        });
        
        // Initialize Select2 for location search
        $(document).ready(function() {
            $('#search_room_zipcode').select2({
                placeholder: 'Search destinations',
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route("zipcode_lookup") }}',
                    dataType: 'json',
                    delay: 300,
                    data: function(params) {
                        return { q: params.term };
                    },
                    processResults: function(data) {
                        return { results: data };
                    },
                    cache: true
                }
            });
            
            // Initialize date range picker
            $('#search_check_in_display, #search_check_out_display').daterangepicker({
                autoUpdateInput: false,
                opens: 'center',
                minDate: moment(),
                locale: {
                    cancelLabel: 'Clear',
                    format: 'MMM D, YYYY'
                }
            });
            
            $('#search_check_in_display, #search_check_out_display').on('apply.daterangepicker', function(ev, picker) {
                $('#search_check_in_display').val(picker.startDate.format('MMM D, YYYY'));
                $('#search_check_out_display').val(picker.endDate.format('MMM D, YYYY'));
                $('#search_check_in').val(picker.startDate.format('YYYY-MM-DD'));
                $('#search_check_out').val(picker.endDate.format('YYYY-MM-DD'));
            });
            
            // Load featured properties
            loadFeaturedProperties();
        });
        
        // Load featured properties
        function loadFeaturedProperties() {
            $.ajax({
                url: '{{ route("rooms") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    limit: 8
                },
                success: function(response) {
                    if (response.done && response.data && response.data.length > 0) {
                        displayProperties(response.data);
                    } else {
                        displaySampleProperties();
                    }
                },
                error: function() {
                    displaySampleProperties();
                }
            });
        }
        
        // Display properties
        function displayProperties(properties) {
            const container = document.getElementById('featuredProperties');
            let html = '';
            
            properties.forEach(function(property) {
                const images = property.images || ['/images/properties/default.jpg'];
                const image = images[0] || '/images/properties/default.jpg';
                
                html += `
                    <a href="${property.href || '/search/' + property.id}" class="tref-property-card">
                        <div class="tref-property-image">
                            <img src="${image}" alt="${property.title || 'Property'}" onerror="this.src='/images/properties/default.jpg'">
                            <button type="button" class="tref-property-wishlist" onclick="event.preventDefault(); toggleWishlist(${property.id})">
                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 28c7-4.73 14-10 14-17a6.98 6.98 0 0 0-7-7c-1.8 0-3.58.68-4.95 2.05L16 8.1l-2.05-2.05a6.98 6.98 0 0 0-9.9 9.9L16 28z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="tref-property-info">
                            <div class="tref-property-header">
                                <span class="tref-property-location">${property.location || property.city || 'Beautiful Location'}</span>
                                <span class="tref-property-rating">
                                    <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.094 1.579l-4.124 8.885-9.86 1.27a1 1 0 0 0-.542 1.736l7.293 6.565-1.965 9.852a1 1 0 0 0 1.483 1.061L16 25.951l8.625 4.997a1 1 0 0 0 1.482-1.06l-1.965-9.853 7.293-6.565a1 1 0 0 0-.541-1.735l-9.86-1.271-4.127-8.885a1 1 0 0 0-1.814 0z"/>
                                    </svg>
                                    ${property.rating || '4.9'}
                                </span>
                            </div>
                            <p class="tref-property-type">${property.type || 'Entire home'}</p>
                            <p class="tref-property-specs">${property.specs || property.beds + ' beds · ' + property.baths + ' baths' || '2 beds · 1 bath'}</p>
                            <p class="tref-property-price"><strong>$${property.price || '150'}</strong> <span>per night</span></p>
                        </div>
                    </a>
                `;
            });
            
            container.innerHTML = html;
        }
        
        // Display sample properties when API fails
        function displaySampleProperties() {
            const sampleProperties = [
                { id: 1, title: 'Cozy Beach House', location: 'Miami, Florida', type: 'Entire home', specs: '3 beds · 2 baths', price: '185', rating: '4.92', image: '/images/properties/default.jpg' },
                { id: 2, title: 'Mountain Retreat', location: 'Aspen, Colorado', type: 'Entire cabin', specs: '2 beds · 1 bath', price: '220', rating: '4.89', image: '/images/properties/default.jpg' },
                { id: 3, title: 'Downtown Loft', location: 'New York, NY', type: 'Private room', specs: '1 bed · 1 bath', price: '125', rating: '4.85', image: '/images/properties/default.jpg' },
                { id: 4, title: 'Lakeside Villa', location: 'Lake Tahoe, CA', type: 'Entire villa', specs: '4 beds · 3 baths', price: '350', rating: '4.95', image: '/images/properties/default.jpg' },
            ];
            
            const container = document.getElementById('featuredProperties');
            let html = '';
            
            sampleProperties.forEach(function(property) {
                html += `
                    <a href="{{ route('search') }}" class="tref-property-card">
                        <div class="tref-property-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-home" style="font-size: 48px; color: rgba(255,255,255,0.5);"></i>
                        </div>
                        <div class="tref-property-info">
                            <div class="tref-property-header">
                                <span class="tref-property-location">${property.location}</span>
                                <span class="tref-property-rating">
                                    <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.094 1.579l-4.124 8.885-9.86 1.27a1 1 0 0 0-.542 1.736l7.293 6.565-1.965 9.852a1 1 0 0 0 1.483 1.061L16 25.951l8.625 4.997a1 1 0 0 0 1.482-1.06l-1.965-9.853 7.293-6.565a1 1 0 0 0-.541-1.735l-9.86-1.271-4.127-8.885a1 1 0 0 0-1.814 0z"/>
                                    </svg>
                                    ${property.rating}
                                </span>
                            </div>
                            <p class="tref-property-type">${property.type}</p>
                            <p class="tref-property-specs">${property.specs}</p>
                            <p class="tref-property-price"><strong>$${property.price}</strong> <span>per night</span></p>
                        </div>
                    </a>
                `;
            });
            
            container.innerHTML = html;
        }
        
        // Wishlist toggle (requires auth)
        function toggleWishlist(propertyId) {
            window.location.href = '{{ route("login") }}';
        }
    </script>
</body>
</html>
