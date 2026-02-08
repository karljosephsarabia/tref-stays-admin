<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tref Stays - Vacation Rentals')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/tref-logo.png') }}" type="image/png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/tref.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    @include('tref.partials.header', ['transparent' => $transparentHeader ?? false])
    
    <main>
        @yield('content')
    </main>
    
    @include('tref.partials.footer')
    
    <!-- Mobile Menu -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="toggleMobileMenu()"></div>
    <div class="mobile-menu" id="mobileMenu">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <img src="{{ asset('images/tref-logo.png') }}" alt="Tref Stays" style="height: 2rem;">
            <button onclick="toggleMobileMenu()" style="background: none; border: none; cursor: pointer;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <nav style="display: flex; flex-direction: column; gap: 1.5rem;">
            <a href="{{ url('/') }}" class="header-nav-link">Properties</a>
            <a href="{{ route('login') }}" class="header-nav-link">List Your Property</a>
            <a href="#" class="header-nav-link">About</a>
        </nav>
        <div style="margin-top: 2rem; display: flex; flex-direction: column; gap: 1rem;">
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline" style="width: 100%;">Sign In</a>
                <a href="{{ route('register') }}" class="btn btn-primary" style="width: 100%;">Sign Up</a>
            @else
                <a href="{{ route('home') }}" class="btn btn-outline" style="width: 100%;">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-ghost" style="width: 100%;">Sign Out</button>
                </form>
            @endguest
        </div>
    </div>
    
    <script>
        // Header scroll effect
        const header = document.getElementById('mainHeader');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
                header.classList.remove('transparent');
            } else {
                header.classList.remove('scrolled');
                if (header.dataset.transparent === 'true') {
                    header.classList.add('transparent');
                }
            }
        });
        
        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('mobileMenuOverlay');
            menu.classList.toggle('open');
            overlay.classList.toggle('open');
            document.body.style.overflow = menu.classList.contains('open') ? 'hidden' : '';
        }
    </script>
    
    @stack('scripts')
</body>
</html>
