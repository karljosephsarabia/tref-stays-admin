<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $property->title ?? 'Property' }} - Tref Stays</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --tref-blue: #0080ff;
            --tref-blue-hover: #0066cc;
            --bg: #ffffff;
            --bg-secondary: #f8fafc;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius: 0.5rem;
            --success: #22c55e;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, sans-serif; background: var(--bg); color: var(--text); }
        .container { max-width: 1280px; margin: 0 auto; padding: 0 1.5rem; }
        
        /* Header */
        .header {
            background: white; border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 100;
        }
        .header-inner {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1rem 0; max-width: 1280px; margin: 0 auto; padding-left: 1.5rem; padding-right: 1.5rem;
        }
        .logo { font-size: 1.5rem; font-weight: 800; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; }
        .logo-icon { width: 2rem; height: 2rem; color: var(--tref-blue); }
        .logo span { color: var(--text); }
        .logo span:first-of-type { color: var(--tref-blue); }
        .header-nav { display: flex; align-items: center; gap: 1.5rem; }
        .header-nav a { color: var(--text); text-decoration: none; font-size: 0.9375rem; font-weight: 500; transition: color 0.2s; }
        .header-nav a:hover { color: var(--tref-blue); }
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.625rem 1.25rem; font-size: 0.875rem; font-weight: 600;
            border-radius: var(--radius); border: none; cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); }
        .btn-outline:hover { border-color: var(--tref-blue); }
        .btn-primary { background: var(--tref-blue); color: white; }
        .btn-primary:hover { background: var(--tref-blue-hover); }
        
        /* Breadcrumb */
        .breadcrumb {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 1rem 0; font-size: 0.875rem; color: var(--text-muted);
        }
        .breadcrumb a { color: var(--tref-blue); text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        
        /* Image Gallery */
        .gallery { margin-bottom: 2rem; }
        .gallery-main {
            position: relative; border-radius: var(--radius); overflow: hidden;
            aspect-ratio: 16/9; margin-bottom: 0.5rem;
        }
        .gallery-main img { width: 100%; height: 100%; object-fit: cover; }
        .gallery-nav {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 2.5rem; height: 2.5rem; background: white;
            border: none; border-radius: 50%; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15); z-index: 10;
        }
        .gallery-nav:hover { background: var(--bg-secondary); }
        .gallery-nav.prev { left: 1rem; }
        .gallery-nav.next { right: 1rem; }
        .gallery-badge {
            position: absolute; top: 1rem; left: 1rem;
            background: var(--tref-blue); color: white;
            padding: 0.375rem 0.75rem; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 600;
        }
        .gallery-favorite {
            position: absolute; top: 1rem; right: 1rem;
            width: 2.5rem; height: 2.5rem; background: white;
            border: none; border-radius: 50%; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .gallery-favorite:hover { background: #fef2f2; }
        .gallery-favorite svg { color: var(--text-muted); transition: color 0.2s; }
        .gallery-favorite:hover svg, .gallery-favorite.active svg { color: #ef4444; fill: #ef4444; }
        .gallery-counter {
            position: absolute; bottom: 1rem; right: 1rem;
            background: rgba(0,0,0,0.7); color: white;
            padding: 0.375rem 0.75rem; border-radius: var(--radius);
            font-size: 0.75rem;
        }
        .gallery-thumbnails {
            display: flex; gap: 0.5rem; overflow-x: auto;
            padding: 0.5rem 0; scrollbar-width: none;
        }
        .gallery-thumbnails::-webkit-scrollbar { display: none; }
        .gallery-thumb {
            flex-shrink: 0; width: 80px; height: 60px;
            border-radius: var(--radius); overflow: hidden;
            cursor: pointer; border: 2px solid transparent;
            transition: border-color 0.2s;
        }
        .gallery-thumb.active { border-color: var(--tref-blue); }
        .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }
        
        /* Layout */
        .property-layout { display: grid; grid-template-columns: 1fr 400px; gap: 2rem; margin-bottom: 3rem; }
        @media (max-width: 968px) { .property-layout { grid-template-columns: 1fr; } }
        
        /* Property Info */
        .property-header { margin-bottom: 1.5rem; }
        .property-title { font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem; }
        .property-location { display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.9375rem; }
        .property-rating { display: flex; align-items: center; gap: 0.25rem; margin-top: 0.5rem; }
        .property-rating svg { color: #facc15; fill: #facc15; }
        .property-rating span { font-weight: 600; }
        .property-rating .reviews { color: var(--text-muted); font-weight: 400; }
        
        .property-stats {
            display: flex; gap: 2rem; padding: 1.5rem;
            background: var(--bg-secondary); border-radius: var(--radius);
            margin-bottom: 1.5rem;
        }
        .property-stat { display: flex; align-items: center; gap: 0.75rem; }
        .property-stat svg { color: var(--tref-blue); }
        .property-stat span { font-weight: 500; }
        
        .section { margin-bottom: 2rem; }
        .section-title { font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        .section-title svg { color: var(--tref-blue); }
        
        .description { line-height: 1.7; color: var(--text); }
        .description p { margin-bottom: 1rem; }
        
        /* Amenities */
        .amenities-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
        .amenity-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: var(--bg-secondary); border-radius: var(--radius); font-size: 0.9375rem; }
        .amenity-item svg { color: var(--tref-blue); flex-shrink: 0; }
        
        /* Kosher Features */
        .kosher-section { background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 1px solid #bbf7d0; border-radius: var(--radius); padding: 1.5rem; }
        .kosher-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: white; color: #16a34a; padding: 0.5rem 1rem;
            border-radius: 9999px; font-size: 0.875rem; font-weight: 600;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1rem;
        }
        .kosher-features { display: grid; gap: 1rem; }
        .kosher-feature { display: flex; align-items: flex-start; gap: 0.75rem; }
        .kosher-feature-icon { width: 2rem; height: 2rem; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .kosher-feature-icon svg { width: 1rem; height: 1rem; color: #16a34a; }
        .kosher-feature-title { font-weight: 600; margin-bottom: 0.25rem; }
        .kosher-feature-text { font-size: 0.875rem; color: var(--text-muted); }
        
        /* Booking Card */
        .booking-card {
            background: white; border: 1px solid var(--border);
            border-radius: var(--radius); padding: 1.5rem;
            position: sticky; top: 5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .booking-price { display: flex; align-items: baseline; gap: 0.5rem; margin-bottom: 1.5rem; }
        .booking-price .amount { font-size: 1.75rem; font-weight: 700; }
        .booking-price .period { color: var(--text-muted); }
        
        .booking-form { display: grid; gap: 1rem; }
        .booking-dates {
            display: grid; grid-template-columns: 1fr 1fr;
            border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden;
        }
        .booking-date {
            padding: 0.75rem; border: none; background: white;
            cursor: pointer; text-align: left;
        }
        .booking-date:first-child { border-right: 1px solid var(--border); }
        .booking-date:hover { background: var(--bg-secondary); }
        .booking-date-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.25rem; }
        .booking-date-value { font-size: 0.875rem; }
        
        .booking-guests {
            border: 1px solid var(--border); border-radius: var(--radius);
            padding: 0.75rem;
        }
        .booking-guests-header { display: flex; justify-content: space-between; align-items: center; }
        .booking-guests-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); }
        .booking-guests-counter { display: flex; align-items: center; gap: 0.75rem; }
        .counter-btn {
            width: 2rem; height: 2rem; border: 1px solid var(--border);
            border-radius: 50%; background: white; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
        .counter-btn:hover { border-color: var(--tref-blue); }
        .counter-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .counter-value { font-weight: 600; min-width: 1.5rem; text-align: center; }
        
        .booking-summary { border-top: 1px solid var(--border); padding-top: 1rem; margin-top: 1rem; }
        .booking-row { display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9375rem; }
        .booking-row.total { font-weight: 700; font-size: 1rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border); }
        
        .booking-submit { width: 100%; margin-top: 1rem; padding: 1rem; font-size: 1rem; }
        
        /* Host Info */
        .host-card { display: flex; gap: 1rem; padding: 1.5rem; background: var(--bg-secondary); border-radius: var(--radius); margin-top: 1.5rem; }
        .host-avatar { width: 3.5rem; height: 3.5rem; border-radius: 50%; background: var(--tref-blue); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.25rem; }
        .host-info h4 { font-weight: 600; margin-bottom: 0.25rem; }
        .host-info p { font-size: 0.875rem; color: var(--text-muted); }
        .host-contact { display: flex; gap: 0.5rem; margin-top: 0.75rem; }
        .host-contact .btn { padding: 0.5rem 1rem; font-size: 0.8125rem; }
        
        /* Reviews */
        .reviews-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .reviews-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
        @media (max-width: 768px) { .reviews-grid { grid-template-columns: 1fr; } }
        .review-card { padding: 1.25rem; background: var(--bg-secondary); border-radius: var(--radius); }
        .review-header { display: flex; gap: 0.75rem; margin-bottom: 0.75rem; }
        .review-avatar { width: 2.5rem; height: 2.5rem; border-radius: 50%; background: var(--border); display: flex; align-items: center; justify-content: center; font-weight: 600; }
        .review-author { font-weight: 600; }
        .review-date { font-size: 0.75rem; color: var(--text-muted); }
        .review-stars { display: flex; gap: 0.125rem; margin-bottom: 0.5rem; }
        .review-stars svg { width: 0.875rem; height: 0.875rem; color: #facc15; fill: #facc15; }
        .review-text { font-size: 0.9375rem; line-height: 1.6; color: var(--text); }
        
        /* Footer */
        .footer { background: var(--text); color: white; padding: 3rem 0 1.5rem; margin-top: 4rem; }
        .footer-grid { display: grid; grid-template-columns: 2fr repeat(3, 1fr); gap: 2rem; margin-bottom: 2rem; }
        @media (max-width: 768px) { .footer-grid { grid-template-columns: 1fr; } }
        .footer-brand p { color: #94a3b8; margin-top: 0.75rem; font-size: 0.875rem; line-height: 1.6; }
        .footer-title { font-weight: 600; margin-bottom: 1rem; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 0.5rem; }
        .footer-links a { color: #94a3b8; text-decoration: none; font-size: 0.875rem; }
        .footer-links a:hover { color: white; }
        .footer-bottom { text-align: center; padding-top: 1.5rem; border-top: 1px solid #334155; font-size: 0.875rem; color: #64748b; }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-inner">
            <a href="{{ url('/') }}" class="logo">
                <svg class="logo-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7v10c0 5.55 3.84 10.74 10 12 6.16-1.26 10-6.45 10-12V7L12 2zm0 4l6 3v7c0 3.87-2.64 7.47-6 8.47-3.36-1-6-4.6-6-8.47V9l6-3z"/></svg>
                <span>Tref</span><span>Stays</span>
            </a>
            <nav class="header-nav">
                <a href="{{ url('/') }}">Browse Rentals</a>
                <a href="#">List Property</a>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                @else
                    <a href="{{ url('/home') }}">Dashboard</a>
                @endguest
            </nav>
        </div>
    </header>

    <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <a href="#">{{ $property->location ?? 'Lakewood, NJ' }}</a>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <span>{{ $property->title ?? 'Beautiful Lakefront Cottage' }}</span>
        </nav>
        
        <!-- Image Gallery -->
        <div class="gallery">
            <div class="gallery-main">
                <span class="gallery-badge">{{ $property->type ?? 'House' }}</span>
                <button class="gallery-favorite" onclick="this.classList.toggle('active')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </button>
                <button class="gallery-nav prev" onclick="changeImage(-1)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                <img id="mainImage" src="{{ !empty($property->images) && count($property->images) > 0 ? $property->images[0] : 'https://via.placeholder.com/1200x800?text=No+Image' }}" alt="{{ $property->title ?? 'Property' }}">
                <button class="gallery-nav next" onclick="changeImage(1)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
                <span class="gallery-counter" id="imageCounter">1 / {{ !empty($property->images) ? count($property->images) : 1 }}</span>
            </div>
            <div class="gallery-thumbnails" id="thumbnails">
                <!-- Thumbnails populated by JS -->
            </div>
        </div>
        
        <!-- Main Layout -->
        <div class="property-layout">
            <!-- Property Info -->
            <div class="property-info">
                <div class="property-header">
                    <h1 class="property-title">{{ $property->title ?? 'Beautiful Lakefront Cottage with Amazing Views' }}</h1>
                    <div class="property-location">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $property->location ?? 'Lakewood, New Jersey, United States' }}
                    </div>
                    <div class="property-rating">
                        <svg width="16" height="16" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <span>{{ $property->rating ?? '4.92' }}</span>
                        <span class="reviews">({{ $property->reviews ?? 128 }} reviews)</span>
                    </div>
                </div>
                
                <div class="property-stats">
                    <div class="property-stat">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>{{ $property->guests ?? 1 }} guests</span>
                    </div>
                    <div class="property-stat">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                        <span>{{ $property->beds ?? 1 }} beds</span>
                    </div>
                    <div class="property-stat">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="10" y1="5" x2="8" y2="7"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
                        <span>{{ $property->baths ?? 1 }} bathrooms</span>
                    </div>
                </div>
                
                <div class="section">
                    <h2 class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        About this property
                    </h2>
                    <div class="description">
                        <p>{{ $property->description ?? 'No description available.' }}</p>
                    </div>
                </div>
                
                <!-- Kosher Features -->
                @if(!empty($property->kosher_info) && is_array($property->kosher_info) && count($property->kosher_info) > 0)
                <div class="section">
                    <div class="kosher-section">
                        <span class="kosher-badge">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7v10c0 5.55 3.84 10.74 10 12 6.16-1.26 10-6.45 10-12V7l-10-5z"/></svg>
                            Kosher Friendly Property
                        </span>
                        <h2 class="section-title" style="margin-bottom:1rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                            Kosher Amenities
                        </h2>
                        <div class="kosher-features">
                            @foreach($property->kosher_info as $key => $value)
                            @if(!empty($value) && !is_array($value))
                            <div class="kosher-feature">
                                <div class="kosher-feature-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                </div>
                                <div>
                                    <div class="kosher-feature-title">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                                    <div class="kosher-feature-text">{{ $value }}</div>
                                </div>
                            </div>
                            @elseif(is_array($value) && !empty($value['name']))
                            <div class="kosher-feature">
                                <div class="kosher-feature-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                </div>
                                <div>
                                    <div class="kosher-feature-title">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                                    <div class="kosher-feature-text">{{ $value['name'] }}@if(!empty($value['distance'])) - {{ $value['distance'] }}@endif</div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Amenities -->
                @if(!empty($property->amenities) && count($property->amenities) > 0)
                <div class="section">
                    <h2 class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                        Amenities
                    </h2>
                    <div class="amenities-grid">
                        @foreach($property->amenities as $amenity)
                        <div class="amenity-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                            {{ $amenity }}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Reviews -->
                <div class="section">
                    <div class="reviews-header">
                        <h2 class="section-title" style="margin-bottom:0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            Guest Reviews
                        </h2>
                        @if($property->reviews > 0)
                        <a href="#" class="btn btn-outline">View All ({{ $property->reviews }})</a>
                        @endif
                    </div>
                    @if($property->reviews > 0)
                    <div class="reviews-grid">
                        <p style="color: #666; padding: 1rem 0;">Reviews will be displayed here once available.</p>
                    </div>
                    @else
                    <p style="color: #666; padding: 1rem 0;">No reviews yet. Be the first to book this property!</p>
                    @endif
                </div>
            </div>
            
            <!-- Booking Card -->
            <aside>
                @php $currencySymbol = currency_symbol($property->currency ?? 'USD'); @endphp
                <div class="booking-card">
                    <div class="booking-price">
                        <span class="amount">{{ $currencySymbol }}{{ $property->price ?? 275 }}</span>
                        <span class="period">/ night</span>
                    </div>
                    
                    <div class="booking-form">
                        <div class="booking-dates">
                            <button class="booking-date" type="button">
                                <div class="booking-date-label">Check-in</div>
                                <div class="booking-date-value" id="checkinDate">Add date</div>
                            </button>
                            <button class="booking-date" type="button">
                                <div class="booking-date-label">Check-out</div>
                                <div class="booking-date-value" id="checkoutDate">Add date</div>
                            </button>
                        </div>
                        
                        <div class="booking-guests">
                            <div class="booking-guests-header">
                                <div>
                                    <div class="booking-guests-label">Guests</div>
                                </div>
                                <div class="booking-guests-counter">
                                    <button type="button" class="counter-btn" onclick="updateGuests(-1)">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    </button>
                                    <span class="counter-value" id="guestCount">1</span>
                                    <button type="button" class="counter-btn" onclick="updateGuests(1)">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="booking-summary">
                            <div class="booking-row">
                                <span>{{ $currencySymbol }}{{ $property->price ?? 0 }} x 5 nights</span>
                                <span>{{ $currencySymbol }}{{ number_format(($property->price ?? 0) * 5, 0) }}</span>
                            </div>
                            <div class="booking-row">
                                <span>Cleaning fee</span>
                                <span>{{ $currencySymbol }}{{ number_format($property->cleaning_fee ?? 0, 0) }}</span>
                            </div>
                            <div class="booking-row">
                                <span>Service fee</span>
                                <span>{{ $currencySymbol }}{{ number_format((($property->price ?? 0) * 5) * 0.075, 0) }}</span>
                            </div>
                            <div class="booking-row total">
                                <span>Total</span>
                                <span>{{ $currencySymbol }}{{ number_format((($property->price ?? 0) * 5) + ($property->cleaning_fee ?? 0) + ((($property->price ?? 0) * 5) * 0.075), 0) }}</span>
                            </div>
                        </div>
                        
                        <button class="btn btn-primary booking-submit">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Reserve Now
                        </button>
                    </div>
                    
                    @if(isset($property->owner))
                    <div class="host-card">
                        <div class="host-avatar">{{ strtoupper(substr($property->owner->first_name ?? 'H', 0, 1) . substr($property->owner->last_name ?? 'O', 0, 1)) }}</div>
                        <div class="host-info">
                            <h4>Hosted by {{ $property->owner->first_name ?? 'Host' }} {{ substr($property->owner->last_name ?? '', 0, 1) }}.</h4>
                            <p>Property Owner{{ $property->owner->created_at ? ' · Joined ' . $property->owner->created_at->format('Y') : '' }}</p>
                            <div class="host-contact">
                                <button class="btn btn-outline">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                    Message
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="{{ url('/') }}" class="logo" style="color:white;">
                        <svg class="logo-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7v10c0 5.55 3.84 10.74 10 12 6.16-1.26 10-6.45 10-12V7L12 2zm0 4l6 3v7c0 3.87-2.64 7.47-6 8.47-3.36-1-6-4.6-6-8.47V9l6-3z"/></svg>
                        <span style="color:var(--tref-blue);">Tref</span><span>Stays</span>
                    </a>
                    <p>Find your perfect kosher-friendly vacation rental. Trusted by thousands of families worldwide.</p>
                </div>
                <div>
                    <h4 class="footer-title">Explore</h4>
                    <ul class="footer-links">
                        <li><a href="#">Browse Rentals</a></li>
                        <li><a href="#">Popular Destinations</a></li>
                        <li><a href="#">Kosher Properties</a></li>
                        <li><a href="#">Last Minute Deals</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-title">Host</h4>
                    <ul class="footer-links">
                        <li><a href="#">List Your Property</a></li>
                        <li><a href="#">Host Resources</a></li>
                        <li><a href="#">Host Guidelines</a></li>
                        <li><a href="#">Community</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-title">Support</h4>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Cancellation Policy</a></li>
                        <li><a href="#">Trust & Safety</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                © {{ date('Y') }} Tref Stays. All rights reserved.
            </div>
        </div>
    </footer>
    
    <script>
        // Image Gallery - Use uploaded property images
        const images = [
            @if(!empty($property->images) && count($property->images) > 0)
                @foreach($property->images as $image)
                    '{{ $image }}',
                @endforeach
            @else
                'https://via.placeholder.com/1200x800?text=No+Image'
            @endif
        ];
        let currentImage = 0;
        
        function renderThumbnails() {
            const container = document.getElementById('thumbnails');
            container.innerHTML = images.map((img, idx) => `
                <div class="gallery-thumb ${idx === currentImage ? 'active' : ''}" onclick="setImage(${idx})">
                    <img src="${img}" alt="Thumbnail ${idx + 1}">
                </div>
            `).join('');
        }
        
        function setImage(idx) {
            currentImage = idx;
            document.getElementById('mainImage').src = images[idx];
            document.getElementById('imageCounter').textContent = `${idx + 1} / ${images.length}`;
            renderThumbnails();
        }
        
        function changeImage(dir) {
            currentImage = (currentImage + dir + images.length) % images.length;
            setImage(currentImage);
        }
        
        // Guest Counter
        let guests = 1;
        const maxGuests = {{ $property->guests ?? 8 }};
        
        function updateGuests(change) {
            guests = Math.max(1, Math.min(maxGuests, guests + change));
            document.getElementById('guestCount').textContent = guests;
        }
        
        // Initialize
        renderThumbnails();
    </script>
</body>
</html>
