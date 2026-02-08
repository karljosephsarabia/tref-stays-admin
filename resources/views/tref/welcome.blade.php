<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tref Stays - Your Perfect Rental Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --tref-blue: #0080ff;
            --tref-blue-hover: #0066cc;
            --tref-dark: #0a1628;
            --bg: #ffffff;
            --bg-secondary: #f8fafc;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius: 0.5rem;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }
        .container { max-width: 1400px; margin: 0 auto; padding: 0 1.5rem; }
        
        /* Header */
        .header {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            padding: 1rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
            background: transparent;
            transition: background 0.3s;
        }
        .header.scrolled {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .header-nav { display: flex; align-items: center; gap: 2.5rem; }
        .header-nav a {
            color: white; text-decoration: none;
            font-size: 0.9375rem; font-weight: 500;
            transition: opacity 0.2s;
        }
        .header.scrolled .header-nav a { color: var(--text); }
        .header-nav a:hover { opacity: 0.8; }
        .header-nav a.active { color: var(--tref-blue); }
        .header-actions { display: flex; align-items: center; gap: 0.75rem; }
        .user-avatar {
            width: 2.25rem; height: 2.25rem; border-radius: 50%;
            background: var(--tref-blue); color: white;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 0.75rem;
        }
        .user-menu {
            display: flex; align-items: center; gap: 0.75rem;
        }
        
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.625rem 1.25rem; font-size: 0.875rem; font-weight: 500;
            border-radius: var(--radius); border: none; cursor: pointer;
            text-decoration: none; transition: all 0.2s;
        }
        .btn-ghost { background: transparent; color: white; }
        .header.scrolled .btn-ghost { color: var(--text); }
        .btn-ghost:hover { background: rgba(255,255,255,0.1); }
        .header.scrolled .btn-ghost:hover { background: var(--bg-secondary); }
        .btn-primary { background: var(--tref-blue); color: white; }
        .btn-primary:hover { background: var(--tref-blue-hover); }
        .btn-outline { background: white; border: 1px solid var(--border); color: var(--text); }
        .btn-outline:hover { border-color: var(--tref-blue); }

        /* Hero */
        .hero {
            position: relative; min-height: 100vh;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 6rem 1.5rem 4rem; overflow: hidden;
        }
        .hero-bg {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover; z-index: 0;
        }
        .hero-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to bottom, rgba(10,22,40,0.5), rgba(10,22,40,0.4) 50%, rgba(10,22,40,0.7));
            z-index: 1;
        }
        .hero-content {
            position: relative; z-index: 10;
            text-align: center; max-width: 900px; width: 100%;
        }
        .hero-title {
            font-size: 3.75rem; font-weight: 700; color: white;
            margin-bottom: 1rem; line-height: 1.1;
        }
        .hero-title span { color: var(--tref-blue); }
        .hero-subtitle {
            font-size: 1.125rem; color: rgba(255,255,255,0.85);
            margin-bottom: 2.5rem;
        }

        /* Search Card */
        .search-card {
            background: white; border-radius: 1rem;
            padding: 0;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            width: 100%; max-width: 900px;
            overflow: hidden;
        }
        .search-row { 
            display: flex; 
            align-items: stretch; 
            gap: 0; 
            flex-wrap: wrap;
            border-bottom: 1px solid var(--border);
        }
        .search-row:last-child { border-bottom: none; }
        .search-field {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 1rem 1.25rem; border: none;
            border-radius: 0; background: white;
            min-width: 140px; cursor: pointer; transition: background 0.2s;
            flex: 1;
            border-right: 1px solid var(--border);
            position: relative;
        }
        .search-field:last-of-type { border-right: none; }
        .search-field:hover { background: var(--bg-secondary); }
        .search-field svg { color: var(--text-muted); flex-shrink: 0; }
        .search-field input, .search-field select {
            border: none; outline: none; font-size: 0.875rem;
            color: var(--text); background: transparent;
            width: 100%; font-family: inherit;
            padding: 0.25rem 0;
        }
        .search-field input::placeholder { color: #9ca3af; }
        .search-field select { 
            cursor: pointer; 
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 100%;
            font-weight: 500;
            opacity: 0;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }
        /* Custom dropdown display */
        .custom-select-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .custom-dropdown {
            position: absolute;
            top: 100%;
            left: -1.25rem;
            right: -1.25rem;
            background: white;
            border: 1px solid var(--border);
            border-top: none;
            max-height: 320px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .custom-dropdown.active { display: block; }
        .custom-option {
            padding: 0.875rem 1.25rem;
            cursor: pointer;
            transition: background 0.15s;
            font-size: 0.9375rem;
            border: none;
            display: block;
            width: 100%;
            text-align: left;
        }
        .custom-option:hover {
            background: var(--bg-secondary);
        }
        .custom-option.selected {
            background: rgba(0, 128, 255, 0.08);
            color: var(--tref-blue);
            font-weight: 600;
        }
        .custom-option.separator {
            border-bottom: 1px solid var(--border);
            padding: 0.25rem 1.25rem;
            color: var(--text-muted);
            font-size: 0.75rem;
            cursor: default;
            pointer-events: none;
        }
        .search-field.has-dropdown {
            position: relative;
        }
        .filters-btn {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 1rem 1.25rem; border: none;
            border-radius: 0; background: white;
            cursor: pointer; font-size: 0.875rem; color: var(--text);
            border-right: 1px solid var(--border);
            transition: background 0.2s;
        }
        .filters-btn:hover { background: var(--bg-secondary); }
        .search-btn {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 1rem 2rem; background: var(--tref-blue);
            color: white; border: none; border-radius: 0;
            font-size: 0.9375rem; font-weight: 600; cursor: pointer;
            margin-left: auto;
        }
        .search-btn:hover { background: var(--tref-blue-hover); }
        
        /* Filters Panel */
        .filters-field { cursor: pointer; }
        .filters-field .date-range { color: var(--text-muted); font-size: 0.8125rem; }
        .filters-panel {
            padding: 1.25rem;
            background: var(--bg-secondary);
            animation: slideDown 0.2s ease-out;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .filters-grid {
            display: grid; grid-template-columns: repeat(6, 1fr);
            gap: 1rem;
        }
        @media (max-width: 900px) { .filters-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 600px) { .filters-grid { grid-template-columns: repeat(2, 1fr); } }
        .filter-group { display: flex; flex-direction: column; gap: 0.375rem; }
        .filter-group label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; }
        .filter-group input, .filter-group select {
            padding: 0.5rem 0.75rem; border: 1px solid var(--border);
            border-radius: var(--radius); font-size: 0.875rem;
            font-family: inherit;
        }
        .filter-group input:focus, .filter-group select:focus {
            outline: none; border-color: var(--tref-blue);
        }
        .filter-group.checkbox-group { flex-direction: row; align-items: center; padding-top: 1.5rem; }
        .filter-group.checkbox-group label { text-transform: none; font-weight: 500; color: var(--text); display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
        .filter-group.checkbox-group input { width: 1rem; height: 1rem; accent-color: var(--tref-blue); }

        /* Stats */
        .hero-stats { display: flex; justify-content: center; gap: 4rem; margin-top: 4rem; flex-wrap: wrap; }
        .hero-stat { text-align: center; }
        .hero-stat-value {
            font-size: 2.25rem; font-weight: 700; color: white;
            display: flex; align-items: center; justify-content: center; gap: 0.25rem;
        }
        .hero-stat-value .star { color: #fbbf24; }
        .hero-stat-label { font-size: 0.875rem; color: rgba(255,255,255,0.7); margin-top: 0.25rem; }

        /* Property Grid Section */
        .properties-section { padding: 4rem 0; background: var(--bg); }
        .section-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;
        }
        .section-title { font-size: 1.5rem; font-weight: 700; }
        .section-count { color: var(--text-muted); font-size: 0.875rem; }
        .currency-selector {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 1rem; border: 1px solid var(--border);
            border-radius: var(--radius); font-size: 0.875rem;
        }
        .properties-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }
        @media (max-width: 1200px) { .properties-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 900px) { .properties-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px) { .properties-grid { grid-template-columns: 1fr; } }

        /* Property Card */
        .property-card {
            background: white; border-radius: var(--radius);
            overflow: hidden; border: 1px solid var(--border);
            transition: all 0.3s; text-decoration: none; color: inherit;
            display: block;
        }
        .property-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.1); }
        .property-card-image { position: relative; aspect-ratio: 4/3; overflow: hidden; }
        .property-card-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
        .property-card:hover .property-card-image img { transform: scale(1.05); }
        .property-card-badge {
            position: absolute; bottom: 0.75rem; left: 0.75rem;
            background: white; padding: 0.375rem 0.75rem;
            border-radius: 9999px; font-size: 0.8125rem; font-weight: 600;
            color: var(--tref-blue);
        }
        .property-card-heart {
            position: absolute; top: 0.75rem; right: 0.75rem;
            width: 2rem; height: 2rem;
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,0.9); border-radius: 50%;
            border: none; cursor: pointer;
        }
        .property-card-heart:hover { background: #ef4444; color: white; }
        .property-card-content { padding: 1rem; }
        .property-card-type {
            font-size: 0.75rem; font-weight: 600; color: var(--tref-blue);
            text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;
        }
        .property-card-title {
            font-size: 1rem; font-weight: 600; margin-bottom: 0.375rem;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .property-card-location {
            font-size: 0.875rem; color: var(--text-muted);
            display: flex; align-items: center; gap: 0.25rem; margin-bottom: 0.75rem;
        }
        .property-card-features {
            display: flex; flex-wrap: wrap; gap: 0.75rem;
            font-size: 0.8125rem; color: var(--text-muted);
        }
        .property-card-feature { display: flex; align-items: center; gap: 0.25rem; }

        /* Trust Section */
        .trust-section { padding: 4rem 0; background: rgba(0,128,255,0.03); }
        .trust-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; }
        @media (max-width: 900px) { .trust-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px) { .trust-grid { grid-template-columns: 1fr; } }
        .trust-item { text-align: center; padding: 2rem; }
        .trust-icon {
            width: 3.5rem; height: 3.5rem; margin: 0 auto 1rem;
            display: flex; align-items: center; justify-content: center;
            background: rgba(0,128,255,0.1); border-radius: 1rem; color: var(--tref-blue);
        }
        .trust-title { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; }
        .trust-desc { font-size: 0.875rem; color: var(--text-muted); }

        /* Footer */
        .footer { background: var(--bg-secondary); border-top: 1px solid var(--border); padding: 3rem 0 0; }
        .footer-grid { display: grid; grid-template-columns: 2fr repeat(3, 1fr); gap: 3rem; margin-bottom: 2rem; }
        @media (max-width: 900px) { .footer-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px) { .footer-grid { grid-template-columns: 1fr; } }
        .footer-brand { max-width: 300px; }
        .footer-logo { height: 2rem; margin-bottom: 1rem; }
        .footer-desc { font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1rem; }
        .footer-column h4 { font-size: 0.875rem; font-weight: 600; margin-bottom: 1rem; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 0.5rem; }
        .footer-links a { font-size: 0.875rem; color: var(--text-muted); text-decoration: none; }
        .footer-links a:hover { color: var(--tref-blue); }
        .footer-bottom {
            border-top: 1px solid var(--border); padding: 1.5rem 0;
            display: flex; justify-content: space-between; align-items: center;
            font-size: 0.8125rem; color: var(--text-muted); flex-wrap: wrap; gap: 1rem;
        }

        @media (max-width: 768px) {
            .header-nav { display: none; }
            .hero-title { font-size: 2.5rem; }
            .search-row { flex-direction: column; align-items: stretch; }
            .search-field { 
                width: 100%; 
                border-right: none;
                border-bottom: 1px solid var(--border);
            }
            .search-field:last-of-type { border-bottom: none; }
            .filters-btn { border-right: none; border-bottom: 1px solid var(--border); }
            .search-btn { width: 100%; justify-content: center; border-radius: 0; }
            .hero-stats { gap: 2rem; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header" id="mainHeader">
        <nav class="header-nav">
            <a href="{{ url('/') }}" class="active">Properties</a>
            <a href="{{ route('register') }}">List Your Property</a>
            <a href="#">About</a>
        </nav>
        <div class="header-actions">
            @guest
                <a href="{{ route('login') }}" class="btn btn-ghost">Sign In</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
            @else
                <div class="user-menu">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->first_name ?? 'U', 0, 1) . substr(Auth::user()->last_name ?? 'S', 0, 1)) }}</div>
                    <a href="{{ route('home') }}" class="btn btn-ghost">My Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="border: none; background: transparent; color: inherit;">Sign Out</button>
                    </form>
                </div>
            @endguest
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="" class="hero-bg">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title"><span>Tref</span> Your Perfect<br>Rental Home</h1>
            <p class="hero-subtitle">Discover unique stays and experiences around the world.</p>
            
            <!-- Search Card -->
            <form action="{{ url('/search') }}" method="GET" class="search-card" id="searchForm">
                <div class="search-row">
                    <div class="search-field has-dropdown" onclick="toggleCustomDropdown(event)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                        <div class="custom-select-display" id="countryDisplay">All Countries</div>
                        <select name="country" id="countrySelect" onchange="handleCountrySelectChange(this)">
                            <option value="">All Countries</option>
                            <option value="us">🇺🇸 United States</option>
                            <option value="uk">🇬🇧 United Kingdom</option>
                            <option value="ca">🇨🇦 Canada</option>
                            <option value="il">🇮🇱 Israel</option>
                            <option value="be">🇧🇪 Belgium</option>
                            <option disabled>────────</option>
                            <option value="map">🗺️ Map View</option>
                        </select>
                        <div class="custom-dropdown" id="countryDropdown">
                            <div class="custom-option" onclick="selectCountry('', 'All Countries', event)">All Countries</div>
                            <div class="custom-option" onclick="selectCountry('us', '🇺🇸 United States', event)">🇺🇸 United States</div>
                            <div class="custom-option" onclick="selectCountry('uk', '🇬🇧 United Kingdom', event)">🇬🇧 United Kingdom</div>
                            <div class="custom-option" onclick="selectCountry('ca', '🇨🇦 Canada', event)">🇨🇦 Canada</div>
                            <div class="custom-option" onclick="selectCountry('il', '🇮🇱 Israel', event)">🇮🇱 Israel</div>
                            <div class="custom-option" onclick="selectCountry('be', '🇧🇪 Belgium', event)">🇧🇪 Belgium</div>
                            <div class="custom-option separator">────────</div>
                            <div class="custom-option" onclick="selectCountry('map', '🗺️ Map View', event)">🗺️ Map View</div>
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </div>
                    <div class="search-field">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <input type="text" name="location" id="locationInput" placeholder="Enter City or Zipcode">
                    </div>
                    <div class="search-field">
                        <select name="rental_type" id="rentalTypeSelect">
                            <option value="">All Rental Types</option>
                            <option value="short">Short Term Rent</option>
                            <option value="long">Long Term Rent</option>
                            <option value="vacation">Vacation Rental</option>
                        </select>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </div>
                    <div class="search-field filters-field" onclick="toggleFilters()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/></svg>
                        <span>Filters</span>
                        <span class="date-range" id="dateRangeText"></span>
                    </div>
                    <button type="submit" class="search-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Search
                    </button>
                </div>
                <!-- Expandable Filters Row -->
                <div class="filters-panel" id="filtersPanel" style="display:none;">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label>Check-in</label>
                            <input type="date" name="checkin" id="checkinDate">
                        </div>
                        <div class="filter-group">
                            <label>Check-out</label>
                            <input type="date" name="checkout" id="checkoutDate">
                        </div>
                        <div class="filter-group">
                            <label>Property Type</label>
                            <select name="type">
                                <option value="">All Types</option>
                                <option value="house">House</option>
                                <option value="apartment">Apartment</option>
                                <option value="condo">Condo</option>
                                <option value="villa">Villa</option>
                                <option value="cottage">Cottage</option>
                                <option value="cabin">Cabin</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Bedrooms</label>
                            <select name="bedrooms">
                                <option value="">Any</option>
                                <option value="1">1+</option>
                                <option value="2">2+</option>
                                <option value="3">3+</option>
                                <option value="4">4+</option>
                                <option value="5">5+</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Max Price</label>
                            <input type="number" name="max_price" placeholder="$ Max">
                        </div>
                        <div class="filter-group checkbox-group">
                            <label><input type="checkbox" name="kosher_kitchen" value="1"> Kosher Kitchen</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Property Grid Section -->
    <section class="properties-section">
        <div class="container">
            @php
                $propertyCount = \SMD\Common\ReservationSystem\Models\RsProperty::where('active', true)->count();
            @endphp
            <div class="section-header">
                <div>
                    <h2 class="section-title">Featured Properties</h2>
                    <p class="section-count">{{ $propertyCount }} properties available</p>
                </div>
                <div class="currency-selector">
                    <span>💵</span>
                    <select>
                        <option value="USD">USD ($)</option>
                        <option value="EUR">EUR (€)</option>
                        <option value="GBP">GBP (£)</option>
                        <option value="ILS">ILS (₪)</option>
                    </select>
                </div>
            </div>
            <div class="properties-grid">
                @php
                // Fetch real properties from database (active ones)
                $dbProperties = \SMD\Common\ReservationSystem\Models\RsProperty::with(['images'])
                    ->where('active', true)
                    ->orderBy('created_at', 'desc')
                    ->limit(8)
                    ->get();
                
                $properties = $dbProperties->map(function($p) {
                    // Use map_address or fallback to city/state combination
                    $location = $p->map_address ?: (implode(', ', array_filter([$p->city, $p->state])) ?: 'Location');
                    // Truncate long addresses
                    if (strlen($location) > 40) {
                        $location = substr($location, 0, 37) . '...';
                    }
                    $primaryImage = $p->images->where('is_primary', true)->first();
                    $image = $primaryImage ? $primaryImage->image_url : ($p->images->first()->image_url ?? 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800');
                    
                    return [
                        'id' => $p->id,
                        'title' => $p->title,
                        'type' => $p->property_type ?? 'Short Term Rent',
                        'location' => $location,
                        'price' => $p->price ?? 0,
                        'currency' => $p->currency ?? 'USD',
                        'guests' => $p->guest_count ?? 1,
                        'beds' => $p->bed_count ?? 1,
                        'baths' => $p->bathroom_count ?? 1,
                        'image' => $image,
                    ];
                })->toArray();
                
                // If no properties in database, show sample properties
                if (empty($properties)) {
                    $properties = [
                        ['id' => 1, 'title' => '26 Quickway 1 flight up', 'type' => 'Short Term Rent', 'location' => 'Monroe, NY', 'price' => 150, 'currency' => 'USD', 'guests' => 6, 'beds' => 3, 'baths' => 2, 'image' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800'],
                        ['id' => 2, 'title' => '26 Quickway walk in', 'type' => 'Short Term Rent', 'location' => 'Monroe, NY', 'price' => 95, 'currency' => 'USD', 'guests' => 2, 'beds' => 1, 'baths' => 1, 'image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800'],
                        ['id' => 3, 'title' => 'Luxury Villa Estate', 'type' => 'Vacation Rental', 'location' => 'Miami, FL', 'price' => 450, 'currency' => 'USD', 'guests' => 12, 'beds' => 6, 'baths' => 4, 'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800'],
                        ['id' => 4, 'title' => 'Cozy Mountain Retreat', 'type' => 'Short Term Rent', 'location' => 'Aspen, CO', 'price' => 275, 'currency' => 'USD', 'guests' => 8, 'beds' => 4, 'baths' => 3, 'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800'],
                    ];
                }
                @endphp
                @foreach($properties as $property)
                <a href="{{ url('/property/'.$property['id']) }}" class="property-card">
                    <div class="property-card-image">
                        <img src="{{ $property['image'] }}" alt="{{ $property['title'] }}">
                        <div class="property-card-badge">{{ currency_symbol($property['currency']) }}{{ $property['price'] }}/night</div>
                        <button type="button" class="property-card-heart" onclick="event.preventDefault();">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </button>
                    </div>
                    <div class="property-card-content">
                        <div class="property-card-type">{{ $property['type'] }}</div>
                        <h3 class="property-card-title">{{ $property['title'] }}</h3>
                        <div class="property-card-location">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $property['location'] }}
                        </div>
                        <div class="property-card-features">
                            <span class="property-card-feature">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                {{ $property['guests'] }} guests
                            </span>
                            <span class="property-card-feature">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/></svg>
                                {{ $property['beds'] }} beds
                            </span>
                            <span class="property-card-feature">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/></svg>
                                {{ $property['baths'] }} baths
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Trust Section -->
    <section class="trust-section">
        <div class="container">
            <div class="trust-grid">
                <div class="trust-item">
                    <div class="trust-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3 class="trust-title">Verified Properties</h3>
                    <p class="trust-desc">All listings are verified for authenticity and quality standards</p>
                </div>
                <div class="trust-item">
                    <div class="trust-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <h3 class="trust-title">Instant Booking</h3>
                    <p class="trust-desc">Book instantly with real-time availability confirmation</p>
                </div>
                <div class="trust-item">
                    <div class="trust-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>
                    <h3 class="trust-title">24/7 Support</h3>
                    <p class="trust-desc">Round-the-clock customer support for any assistance</p>
                </div>
                <div class="trust-item">
                    <div class="trust-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    </div>
                    <h3 class="trust-title">Best Price Guarantee</h3>
                    <p class="trust-desc">Competitive prices with our best price guarantee policy</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <img src="{{ asset('images/tref-logo.png') }}" alt="Tref Stays" class="footer-logo">
                    <p class="footer-desc">Find your perfect rental property. Short-term, long-term, or vacation rentals.</p>
                </div>
                <div class="footer-column">
                    <h4>Properties</h4>
                    <ul class="footer-links">
                        <li><a href="#">Short Term Rentals</a></li>
                        <li><a href="#">Long Term Rentals</a></li>
                        <li><a href="#">Vacation Rentals</a></li>
                        <li><a href="#">Properties for Sale</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Company</h4>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">List Your Property</a></li>
                        <li><a href="#">Careers</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Support</h4>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© {{ date('Y') }} Tref. All rights reserved.</p>
                <div style="display:flex;gap:1.5rem;">
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('mainHeader');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Toggle filters panel
        function toggleFilters() {
            const panel = document.getElementById('filtersPanel');
            if (panel.style.display === 'none') {
                panel.style.display = 'block';
            } else {
                panel.style.display = 'none';
            }
        }
        
        // Update date range text when dates change
        const checkinInput = document.getElementById('checkinDate');
        const checkoutInput = document.getElementById('checkoutDate');
        const dateRangeText = document.getElementById('dateRangeText');
        
        function updateDateRange() {
            const checkin = checkinInput.value;
            const checkout = checkoutInput.value;
            if (checkin && checkout) {
                const checkinDate = new Date(checkin);
                const checkoutDate = new Date(checkout);
                const options = { month: 'short', day: 'numeric' };
                dateRangeText.textContent = checkinDate.toLocaleDateString('en-US', options) + ' - ' + checkoutDate.toLocaleDateString('en-US', options);
            } else if (checkin) {
                const checkinDate = new Date(checkin);
                const options = { month: 'short', day: 'numeric' };
                dateRangeText.textContent = checkinDate.toLocaleDateString('en-US', options) + ' - Add date';
            } else {
                dateRangeText.textContent = '';
            }
        }
        
        checkinInput?.addEventListener('change', updateDateRange);
        checkoutInput?.addEventListener('change', updateDateRange);
        
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        if (checkinInput) checkinInput.min = today;
        if (checkoutInput) checkoutInput.min = today;
        
        // Ensure checkout is after checkin
        checkinInput?.addEventListener('change', function() {
            checkoutInput.min = this.value;
            if (checkoutInput.value && checkoutInput.value < this.value) {
                checkoutInput.value = this.value;
            }
            updateDateRange();
        });

        // Handle country dropdown change for map option
        function handleCountrySelectChange(select) {
            if (select.value === 'map') {
                window.location.href = '{{ url('/search') }}?view=map';
            }
        }

        // Custom dropdown functions
        function toggleCustomDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('countryDropdown');
            dropdown.classList.toggle('active');
        }

        function selectCountry(value, text, event) {
            event.stopPropagation();
            const select = document.getElementById('countrySelect');
            const display = document.getElementById('countryDisplay');
            const dropdown = document.getElementById('countryDropdown');
            
            // Update native select
            select.value = value;
            
            // Update display text
            display.textContent = text;
            
            // Update selected state
            document.querySelectorAll('.custom-option').forEach(opt => opt.classList.remove('selected'));
            event.target.classList.add('selected');
            
            // Close dropdown
            dropdown.classList.remove('active');
            
            // Trigger change event
            handleCountrySelectChange(select);
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            const dropdown = document.getElementById('countryDropdown');
            if (dropdown) {
                dropdown.classList.remove('active');
            }
        });
    </script>
</body>
</html>
