<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ $title ?? 'Search Properties' }} - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon.png') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Plugins -->
    <link href="{{ asset('/plugins/bootstrap-daterangepicker2/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('/css/tref-stays.css') }}" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        body { margin: 0; padding: 0; background: #fff; }
        
        .search-page { padding-top: 80px; }
        
        .search-filters-bar {
            position: sticky;
            top: 80px;
            background: white;
            border-bottom: 1px solid #ddd;
            padding: 16px 40px;
            z-index: 100;
            display: flex;
            gap: 12px;
            align-items: center;
            overflow-x: auto;
        }
        
        .filter-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        
        .filter-btn:hover {
            border-color: #222;
        }
        
        .filter-btn.active {
            background: #222;
            color: white;
            border-color: #222;
        }
        
        .search-results-container {
            display: flex;
            min-height: calc(100vh - 160px);
        }
        
        .search-results-list {
            flex: 1;
            padding: 24px 40px;
            overflow-y: auto;
        }
        
        .search-results-map {
            width: 50%;
            position: sticky;
            top: 160px;
            height: calc(100vh - 160px);
            background: #f0f0f0;
        }
        
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .results-count {
            font-size: 14px;
            color: #717171;
        }
        
        .view-toggle {
            display: flex;
            gap: 8px;
        }
        
        .view-toggle button {
            padding: 8px 12px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .view-toggle button.active {
            background: #222;
            color: white;
            border-color: #222;
        }
        
        @media (max-width: 992px) {
            .search-results-map { display: none; }
            .search-filters-bar { padding: 12px 16px; }
            .search-results-list { padding: 16px; }
        }
    </style>
</head>
<body class="tref-page">
    
    @php($user = Auth::user())
    
    <!-- Navigation -->
    <nav class="tref-navbar scrolled">
        <div class="tref-navbar-container">
            <a href="{{ url('/') }}" class="tref-logo">
                <img src="{{ asset('/images/logo.png') }}" alt="{{ config('app.name') }}">
                <span class="tref-logo-text">{{ config('app.name') }}</span>
            </a>
            
            <!-- Search Bar -->
            <form id="navSearchForm" class="tref-search-bar" method="get" action="{{ url('search') }}">
                <input type="hidden" name="zipcode" id="nav_zipcode" value="{{ request('zipcode') }}">
                <input type="hidden" name="check_in" id="nav_check_in" value="{{ request('check_in') }}">
                <input type="hidden" name="check_out" id="nav_check_out" value="{{ request('check_out') }}">
                <input type="hidden" name="guest_count" id="nav_guest_count" value="{{ request('guest_count') }}">
                
                <button type="button" class="tref-search-bar-item" onclick="openSearchModal()">
                    {{ request('zipcode') ? 'Location set' : 'Anywhere' }}
                </button>
                <div class="tref-search-bar-divider"></div>
                <button type="button" class="tref-search-bar-item" onclick="openSearchModal()">
                    {{ request('check_in') ? request('check_in') . ' - ' . request('check_out') : 'Any week' }}
                </button>
                <div class="tref-search-bar-divider"></div>
                <button type="button" class="tref-search-bar-item" style="color: #717171;" onclick="openSearchModal()">
                    {{ request('guest_count') ? request('guest_count') . ' guests' : 'Add guests' }}
                </button>
                <button type="submit" class="tref-search-bar-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            
            <!-- Nav Links -->
            <div class="tref-nav-links">
                @if($user)
                    <a href="{{ route('profile') }}" class="tref-nav-link">{{ user_full_name($user) }}</a>
                    <div class="tref-user-menu" onclick="toggleUserMenu()">
                        <i class="fas fa-bars tref-user-menu-icon"></i>
                        <div class="tref-user-avatar">
                            <img src="{{ asset($user->profile_image) }}" alt="">
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="tref-nav-link">Log in</a>
                    <a href="{{ route('register') }}" class="tref-btn tref-btn-primary" style="padding: 10px 20px;">Sign up</a>
                @endif
                
                <!-- Dropdown Menu -->
                <div class="tref-user-dropdown" id="userDropdown" style="display: none; position: absolute; right: 40px; top: 70px; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); min-width: 220px; z-index: 1001;">
                    <div style="padding: 8px 0;">
                        @if($user)
                            <a href="{{ route('profile') }}" style="display: block; padding: 12px 16px; text-decoration: none; color: #222;">Profile</a>
                            <a href="{{ route('reservations') }}" style="display: block; padding: 12px 16px; text-decoration: none; color: #222;">My Reservations</a>
                            <hr style="margin: 8px 0; border: none; border-top: 1px solid #ddd;">
                            <form id="logoutForm" action="{{ url('logout') }}" method="POST" style="display: none;">@csrf</form>
                            <a href="#" onclick="document.getElementById('logoutForm').submit()" style="display: block; padding: 12px 16px; text-decoration: none; color: #222;">Log out</a>
                        @else
                            <a href="{{ route('login') }}" style="display: block; padding: 12px 16px; text-decoration: none; color: #222; font-weight: 600;">Log in</a>
                            <a href="{{ route('register') }}" style="display: block; padding: 12px 16px; text-decoration: none; color: #222;">Sign up</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="search-page">
        <!-- Filters Bar -->
        <div class="search-filters-bar">
            <button class="filter-btn" onclick="toggleFilter('price')">
                <i class="fas fa-dollar-sign"></i> Price
            </button>
            <button class="filter-btn" onclick="toggleFilter('type')">
                <i class="fas fa-home"></i> Type of place
            </button>
            <button class="filter-btn" onclick="toggleFilter('rooms')">
                <i class="fas fa-bed"></i> Rooms
            </button>
            <button class="filter-btn" onclick="toggleFilter('amenities')">
                <i class="fas fa-wifi"></i> Amenities
            </button>
            <button class="filter-btn" onclick="toggleFilter('all')">
                <i class="fas fa-sliders-h"></i> Filters
            </button>
        </div>
        
        <!-- Search Results -->
        <div class="search-results-container">
            <div class="search-results-list">
                <div class="results-header">
                    <div>
                        <h1 style="font-size: 24px; font-weight: 700; margin: 0 0 4px;">Stays</h1>
                        <p class="results-count" id="resultsCount">Loading properties...</p>
                    </div>
                    <div class="view-toggle">
                        <button type="button" class="active" onclick="setView('grid')" id="gridViewBtn">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button type="button" onclick="setView('map')" id="mapViewBtn">
                            <i class="fas fa-map"></i>
                        </button>
                    </div>
                </div>
                
                <div class="tref-properties" id="propertyResults">
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                        <div class="tref-spinner" style="margin: 0 auto 20px;"></div>
                        <p style="color: #717171;">Searching for properties...</p>
                    </div>
                </div>
            </div>
            
            <div class="search-results-map" id="mapContainer">
                <div id="map" style="width: 100%; height: 100%;"></div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('/plugins/common/common.min.js') }}"></script>
    <script src="{{ asset('/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-daterangepicker2/daterangepicker.js') }}"></script>
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    
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
            if (userMenu && !userMenu.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
        
        // View Toggle
        let currentView = 'grid';
        
        function setView(view) {
            currentView = view;
            document.getElementById('gridViewBtn').classList.toggle('active', view === 'grid');
            document.getElementById('mapViewBtn').classList.toggle('active', view === 'map');
            document.getElementById('mapContainer').style.display = view === 'map' ? 'block' : '';
            
            if (view === 'map') {
                document.getElementById('mapContainer').style.width = '50%';
            }
        }
        
        // Filter Toggle
        function toggleFilter(filter) {
            Swal.fire({
                title: 'Filters',
                text: 'Filter options will be available soon!',
                icon: 'info',
                confirmButtonColor: '#FF385C'
            });
        }
        
        // Open Search Modal
        function openSearchModal() {
            // For now, just scroll to top or show a modal
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        // Load Properties
        $(document).ready(function() {
            loadProperties();
        });
        
        function loadProperties() {
            const formData = {
                _token: '{{ csrf_token() }}',
                zipcode: '{{ request('zipcode') }}',
                check_in: '{{ request('check_in') }}',
                check_out: '{{ request('check_out') }}',
                guest_count: '{{ request('guest_count') }}'
            };
            
            $.ajax({
                url: '{{ route("rooms") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.done && response.data && response.data.length > 0) {
                        displayProperties(response.data);
                        document.getElementById('resultsCount').textContent = response.data.length + ' properties found';
                    } else {
                        displayNoResults();
                    }
                },
                error: function() {
                    displayNoResults();
                }
            });
        }
        
        function displayProperties(properties) {
            const container = document.getElementById('propertyResults');
            let html = '';
            
            properties.forEach(function(property) {
                const images = property.images || ['/images/properties/default.jpg'];
                const image = images[0] || '/images/properties/default.jpg';
                const href = property.href || '/search/' + property.id + '/view';
                
                html += `
                    <a href="${href}" class="tref-property-card">
                        <div class="tref-property-image">
                            <img src="${image}" alt="${property.title || 'Property'}" onerror="this.parentElement.style.background='linear-gradient(135deg, #667eea 0%, #764ba2 100%)'; this.style.display='none';">
                            <button type="button" class="tref-property-wishlist" onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist(${property.id})">
                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 28c7-4.73 14-10 14-17a6.98 6.98 0 0 0-7-7c-1.8 0-3.58.68-4.95 2.05L16 8.1l-2.05-2.05a6.98 6.98 0 0 0-9.9 9.9L16 28z"/>
                                </svg>
                            </button>
                            ${property.is_featured ? '<span class="tref-property-badge">Featured</span>' : ''}
                        </div>
                        <div class="tref-property-info">
                            <div class="tref-property-header">
                                <span class="tref-property-location">${property.location || property.city || property.title || 'Beautiful Location'}</span>
                                <span class="tref-property-rating">
                                    <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.094 1.579l-4.124 8.885-9.86 1.27a1 1 0 0 0-.542 1.736l7.293 6.565-1.965 9.852a1 1 0 0 0 1.483 1.061L16 25.951l8.625 4.997a1 1 0 0 0 1.482-1.06l-1.965-9.853 7.293-6.565a1 1 0 0 0-.541-1.735l-9.86-1.271-4.127-8.885a1 1 0 0 0-1.814 0z"/>
                                    </svg>
                                    ${property.rating || '4.9'}
                                </span>
                            </div>
                            <p class="tref-property-type">${property.type_location || property.type || 'Entire home'}</p>
                            <p class="tref-property-specs">${property.specs || ''}</p>
                            <p class="tref-property-price"><strong>$${property.price || '0'}</strong> <span>per night</span></p>
                        </div>
                    </a>
                `;
            });
            
            container.innerHTML = html;
        }
        
        function displayNoResults() {
            const container = document.getElementById('propertyResults');
            document.getElementById('resultsCount').textContent = 'No properties found';
            
            container.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 80px 20px;">
                    <div style="width: 120px; height: 120px; background: #f7f7f7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                        <i class="fas fa-search" style="font-size: 48px; color: #ccc;"></i>
                    </div>
                    <h3 style="font-size: 22px; font-weight: 600; margin-bottom: 8px;">No results found</h3>
                    <p style="color: #717171; margin-bottom: 24px;">Try adjusting your search or filters to find what you're looking for.</p>
                    <a href="{{ url('/') }}" class="tref-btn tref-btn-outline">Clear all filters</a>
                </div>
            `;
        }
        
        // Wishlist toggle
        function toggleWishlist(propertyId) {
            @if($user)
                // Add to wishlist logic here
                Swal.fire({
                    title: 'Added to wishlist!',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            @else
                window.location.href = '{{ route("login") }}';
            @endif
        }
    </script>
</body>
</html>
