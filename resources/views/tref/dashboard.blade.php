<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Tref Stays</title>
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
            --success: #16a34a;
            --warning: #f59e0b;
            --danger: #ef4444;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-secondary);
            color: var(--text);
            line-height: 1.6;
        }
        /* Header */
        .header {
            background: white;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 700;
        }
        .logo-icon {
            width: 2rem;
            height: 2rem;
            color: var(--tref-blue);
        }
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .nav-link {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.9375rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--tref-blue);
        }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: var(--tref-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 0.5rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-primary {
            background: var(--tref-blue);
            color: white;
        }
        .btn-primary:hover {
            background: var(--tref-blue-hover);
        }
        .btn-outline {
            background: white;
            color: var(--text);
            border: 1px solid var(--border);
        }
        .btn-outline:hover {
            background: var(--bg-secondary);
        }
        /* Container */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }
        /* Page Header */
        .page-header {
            margin-bottom: 2rem;
        }
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .page-subtitle {
            color: var(--text-muted);
        }
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid var(--border);
        }
        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stat-icon.blue { background: rgba(0,128,255,0.1); color: var(--tref-blue); }
        .stat-icon.green { background: rgba(22,163,74,0.1); color: var(--success); }
        .stat-icon.orange { background: rgba(245,158,11,0.1); color: var(--warning); }
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .stat-label {
            font-size: 0.875rem;
            color: var(--text-muted);
        }
        /* Section */
        .section {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid var(--border);
            margin-bottom: 2rem;
        }
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
        }
        /* Booking Card */
        .booking-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .booking-card {
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 1.25rem;
            display: flex;
            gap: 1.25rem;
            transition: box-shadow 0.2s;
        }
        .booking-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .booking-image {
            width: 120px;
            height: 120px;
            border-radius: 0.5rem;
            object-fit: cover;
            flex-shrink: 0;
        }
        .booking-content {
            flex: 1;
            min-width: 0;
        }
        .booking-header {
            display: flex;
            align-items: start;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }
        .booking-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .booking-location {
            font-size: 0.875rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .booking-status {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-active { background: rgba(22,163,74,0.1); color: var(--success); }
        .status-upcoming { background: rgba(0,128,255,0.1); color: var(--tref-blue); }
        .status-completed { background: rgba(100,116,139,0.1); color: var(--text-muted); }
        .booking-details {
            display: flex;
            gap: 2rem;
            margin-bottom: 0.75rem;
        }
        .booking-detail {
            font-size: 0.875rem;
        }
        .booking-detail-label {
            color: var(--text-muted);
            margin-bottom: 0.125rem;
        }
        .booking-detail-value {
            font-weight: 600;
        }
        .booking-actions {
            display: flex;
            gap: 0.5rem;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        .empty-icon {
            width: 4rem;
            height: 4rem;
            margin: 0 auto 1rem;
            color: var(--text-muted);
        }
        .empty-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .empty-text {
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }
        /* Responsive */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .booking-card {
                flex-direction: column;
            }
            .booking-image {
                width: 100%;
                height: 200px;
            }
            .booking-details {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="{{ url('/') }}" class="logo">
                <svg class="logo-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 10 12 6.16-1.26 10-6.45 10-12V7L12 2zm0 4l6 3v7c0 3.87-2.64 7.47-6 8.47-3.36-1-6-4.6-6-8.47V9l6-3z"/>
                </svg>
                <span><span style="color:var(--tref-blue);">Tref</span>Stays</span>
            </a>
            <div class="user-menu">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1) . substr(auth()->user()->last_name ?? 'S', 0, 1)) }}</div>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline">Sign Out</button>
                </form>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Welcome back, {{ auth()->user()->first_name ?? 'Guest' }}!</h1>
            <p class="page-subtitle">{{ $isOwner ?? false ? 'Manage your properties and bookings' : 'Manage your bookings and reservations' }}</p>
        </div>

        <!-- Stats -->
        @if($isOwner ?? false)
        <!-- Owner Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon blue">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $totalProperties ?? 0 }}</div>
                <div class="stat-label">Total Properties</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon green">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $activeProperties ?? 0 }}</div>
                <div class="stat-label">Active Listings</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon orange">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $totalBookings ?? 0 }}</div>
                <div class="stat-label">Total Bookings</div>
            </div>
        </div>
        @else
        <!-- Renter Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon blue">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $reservations->count() }}</div>
                <div class="stat-label">Total Bookings</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon green">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $activeReservations ?? 0 }}</div>
                <div class="stat-label">Active Reservations</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon orange">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $upcomingReservations ?? 0 }}</div>
                <div class="stat-label">Upcoming Stays</div>
            </div>
        </div>
        @endif

        <!-- Owner Properties List -->
        @if($isOwner ?? false)
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">My Properties</h2>
                <a href="{{ route('register') }}" class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Add New Property
                </a>
            </div>

            @if(isset($properties) && $properties->count() > 0)
            <div class="booking-list">
                @foreach($properties as $property)
                <div class="booking-card">
                    @if($property->images->count() > 0)
                    <img src="{{ $property->images->first()->image_url }}" alt="{{ $property->title }}" class="booking-image">
                    @else
                    <img src="https://via.placeholder.com/120?text=No+Image" alt="Property" class="booking-image">
                    @endif
                    
                    <div class="booking-content">
                        <div class="booking-header">
                            <div>
                                <h3 class="booking-title">{{ $property->title }}</h3>
                                <div class="booking-location">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    {{ $property->city ?? 'Location' }}, {{ $property->state ?? '' }}
                                </div>
                            </div>
                            <span class="booking-status status-{{ $property->active ? 'active' : 'completed' }}">
                                {{ $property->active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <div class="booking-details">
                            <div class="booking-detail">
                                <div class="booking-detail-label">Property Type</div>
                                <div class="booking-detail-value">{{ ucfirst($property->property_type ?? 'N/A') }}</div>
                            </div>
                            <div class="booking-detail">
                                <div class="booking-detail-label">Bedrooms</div>
                                <div class="booking-detail-value">{{ $property->bedroom_count ?? 0 }} beds</div>
                            </div>
                            <div class="booking-detail">
                                <div class="booking-detail-label">Max Guests</div>
                                <div class="booking-detail-value">{{ $property->guest_count ?? 0 }} guests</div>
                            </div>
                            <div class="booking-detail">
                                <div class="booking-detail-label">Price</div>
                                <div class="booking-detail-value">{{ format_price($property->price ?? 0, $property->currency ?? 'USD') }}/night</div>
                            </div>
                            <div class="booking-detail">
                                <div class="booking-detail-label">Total Bookings</div>
                                <div class="booking-detail-value">{{ $property->reservations->count() }}</div>
                            </div>
                        </div>
                        
                        <div class="booking-actions">
                            <a href="{{ route('tref.property', $property->id) }}" class="btn btn-primary">View Listing</a>
                            <button class="btn btn-outline">Edit</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <div class="empty-title">No properties listed yet</div>
                <div class="empty-text">Start by listing your first property to reach guests</div>
                <a href="{{ route('register') }}" class="btn btn-primary">List Your Property</a>
            </div>
            @endif
        </div>
        @endif

        <!-- Bookings List (Renter View) -->
        @if(!($isOwner ?? false))
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">My Bookings</h2>
                <a href="{{ url('/') }}" class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    New Booking
                </a>
            </div>

            @if($reservations->count() > 0)
            <div class="booking-list">
                @foreach($reservations as $reservation)
                <div class="booking-card">
                    @if($reservation->property->images->count() > 0)
                    <img src="{{ $reservation->property->images->first()->image_url }}" alt="{{ $reservation->property->title }}" class="booking-image">
                    @else
                    <img src="https://via.placeholder.com/120?text=No+Image" alt="Property" class="booking-image">
                    @endif
                    
                    <div class="booking-content">
                        <div class="booking-header">
                            <div>
                                <h3 class="booking-title">{{ $reservation->property->title }}</h3>
                                <div class="booking-location">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    {{ $reservation->property->city ?? 'Location' }}, {{ $reservation->property->state ?? '' }}
                                </div>
                            </div>
                            @php
                                $status = 'upcoming';
                                if ($reservation->date_start && $reservation->date_end) {
                                    $now = strtotime('now');
                                    $start = strtotime($reservation->date_start);
                                    $end = strtotime($reservation->date_end);
                                    if ($now >= $start && $now <= $end) {
                                        $status = 'active';
                                    } elseif ($now > $end) {
                                        $status = 'completed';
                                    }
                                }
                            @endphp
                            <span class="booking-status status-{{ $status }}">{{ ucfirst($status) }}</span>
                        </div>
                        
                        <div class="booking-details">
                            <div class="booking-detail">
                                <div class="booking-detail-label">Check-in</div>
                                <div class="booking-detail-value">{{ $reservation->date_start ? date('M d, Y', strtotime($reservation->date_start)) : 'N/A' }}</div>
                            </div>
                            <div class="booking-detail">
                                <div class="booking-detail-label">Check-out</div>
                                <div class="booking-detail-value">{{ $reservation->date_end ? date('M d, Y', strtotime($reservation->date_end)) : 'N/A' }}</div>
                            </div>
                            <div class="booking-detail">
                                <div class="booking-detail-label">Guests</div>
                                <div class="booking-detail-value">{{ $reservation->guest_count ?? $reservation->property->guest_count }} guests</div>
                            </div>
                            <div class="booking-detail">
                                <div class="booking-detail-label">Total</div>
                                <div class="booking-detail-value">${{ number_format($reservation->total_price ?? $reservation->property->price, 0) }}</div>
                            </div>
                        </div>
                        
                        <div class="booking-actions">
                            <a href="{{ route('tref.property', $reservation->property->id) }}" class="btn btn-outline">View Property</a>
                            @if($status === 'upcoming' || $status === 'active')
                            <a href="#" class="btn btn-outline">Manage Booking</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <h3 class="empty-title">No bookings yet</h3>
                <p class="empty-text">Start exploring amazing properties and make your first booking!</p>
                <a href="{{ url('/') }}" class="btn btn-primary">Browse Properties</a>
            </div>
            @endif
        </div>
        @endif
    </div>
</body>
</html>
