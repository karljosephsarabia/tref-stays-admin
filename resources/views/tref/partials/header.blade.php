{{-- Tref Header Component --}}
<header class="header {{ $transparent ?? false ? 'transparent' : '' }}" id="mainHeader" data-transparent="{{ $transparent ?? false ? 'true' : 'false' }}">
    <div class="container">
        <div class="header-inner">
            {{-- Logo --}}
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/tref-logo.png') }}" alt="Tref Stays" class="header-logo">
            </a>
            
            {{-- Desktop Navigation --}}
            <nav class="header-nav">
                <a href="{{ url('/') }}" class="header-nav-link">Properties</a>
                <a href="{{ route('login') }}" class="header-nav-link">List Your Property</a>
                <a href="#" class="header-nav-link">About</a>
            </nav>
            
            {{-- Actions --}}
            <div class="header-actions">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Sign Up</a>
                @else
                    <div class="dropdown" style="position: relative;">
                        <button class="btn btn-ghost btn-sm" onclick="toggleDropdown(this)" style="display: flex; align-items: center; gap: 0.5rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>{{ Auth::user()->name }}</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="dropdown-menu" style="display: none; position: absolute; right: 0; top: 100%; margin-top: 0.5rem; min-width: 180px; background: hsl(var(--background)); border: 1px solid hsl(var(--border)); border-radius: var(--radius); box-shadow: var(--tref-shadow); padding: 0.5rem; z-index: 50;">
                            <a href="{{ route('home') }}" style="display: block; padding: 0.5rem 1rem; text-decoration: none; color: inherit; border-radius: 0.375rem; font-size: 0.875rem;">
                                Dashboard
                            </a>
                            <a href="#" style="display: block; padding: 0.5rem 1rem; text-decoration: none; color: inherit; border-radius: 0.375rem; font-size: 0.875rem;">
                                My Properties
                            </a>
                            <a href="#" style="display: block; padding: 0.5rem 1rem; text-decoration: none; color: inherit; border-radius: 0.375rem; font-size: 0.875rem;">
                                Reservations
                            </a>
                            <hr style="margin: 0.5rem 0; border: none; border-top: 1px solid hsl(var(--border));">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" style="display: block; width: 100%; padding: 0.5rem 1rem; text-align: left; background: none; border: none; color: hsl(var(--destructive)); border-radius: 0.375rem; font-size: 0.875rem; cursor: pointer;">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
                
                {{-- Mobile Menu Button --}}
                <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>

<script>
function toggleDropdown(btn) {
    const menu = btn.nextElementSibling;
    const isOpen = menu.style.display === 'block';
    
    // Close all dropdowns first
    document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
    
    if (!isOpen) {
        menu.style.display = 'block';
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
    }
});
</script>
