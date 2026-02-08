<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Owner Dashboard - Tref Stays</title>
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
        
        /* Sidebar Layout */
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 260px;
            background: white;
            border-right: 1px solid var(--border);
            padding: 1.5rem;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            text-decoration: none;
            margin-bottom: 2rem;
        }
        
        .sidebar-logo svg {
            color: var(--tref-blue);
        }
        
        .sidebar-nav {
            list-style: none;
        }
        
        .sidebar-nav li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .sidebar-link:hover {
            background: var(--bg-secondary);
            color: var(--text);
        }
        
        .sidebar-link.active {
            background: rgba(0,128,255,0.1);
            color: var(--tref-blue);
        }
        
        .sidebar-link svg {
            width: 20px;
            height: 20px;
        }
        
        .sidebar-section {
            margin-top: 2rem;
            margin-bottom: 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.05em;
        }
        
        .badge {
            background: var(--danger);
            color: white;
            font-size: 0.7rem;
            padding: 0.15rem 0.5rem;
            border-radius: 1rem;
            margin-left: auto;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 2rem;
        }
        
        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
        }
        
        .page-subtitle {
            color: var(--text-muted);
            font-size: 0.9375rem;
        }
        
        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
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
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
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
            margin-bottom: 0.75rem;
        }
        
        .stat-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stat-icon.blue { background: rgba(0,128,255,0.1); color: var(--tref-blue); }
        .stat-icon.green { background: rgba(22,163,74,0.1); color: var(--success); }
        .stat-icon.orange { background: rgba(245,158,11,0.1); color: var(--warning); }
        .stat-icon.red { background: rgba(239,68,68,0.1); color: var(--danger); }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }
        
        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }
        
        .stat-trend.up { color: var(--success); }
        .stat-trend.down { color: var(--danger); }
        
        /* Cards */
        .card {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        
        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Properties Grid */
        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }
        
        .property-card {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid var(--border);
            overflow: hidden;
            transition: box-shadow 0.2s;
        }
        
        .property-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .property-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: var(--bg-secondary);
        }
        
        .property-content {
            padding: 1.25rem;
        }
        
        .property-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .property-location {
            font-size: 0.875rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-bottom: 1rem;
        }
        
        .property-stats {
            display: flex;
            gap: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }
        
        .property-stat {
            display: flex;
            flex-direction: column;
        }
        
        .property-stat-value {
            font-weight: 600;
            color: var(--text);
        }
        
        .property-stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        
        .property-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        /* Inquiries List */
        .inquiry-list {
            list-style: none;
        }
        
        .inquiry-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border);
        }
        
        .inquiry-item:last-child {
            border-bottom: none;
        }
        
        .inquiry-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: var(--tref-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }
        
        .inquiry-content {
            flex: 1;
            min-width: 0;
        }
        
        .inquiry-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.25rem;
        }
        
        .inquiry-name {
            font-weight: 600;
        }
        
        .inquiry-time {
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        
        .inquiry-preview {
            font-size: 0.875rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .inquiry-property {
            font-size: 0.75rem;
            color: var(--tref-blue);
            margin-top: 0.25rem;
        }
        
        .inquiry-status {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-new { background: rgba(239,68,68,0.1); color: var(--danger); }
        .status-read { background: rgba(245,158,11,0.1); color: var(--warning); }
        .status-responded { background: rgba(22,163,74,0.1); color: var(--success); }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-muted);
        }
        
        .empty-state svg {
            width: 4rem;
            height: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .empty-state h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
        }
        
        /* User Menu */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            border-top: 1px solid var(--border);
            margin-top: auto;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
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
        }
        
        .user-info {
            flex: 1;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .user-email {
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        
        /* Alert */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-success {
            background: rgba(22,163,74,0.1);
            color: var(--success);
            border: 1px solid rgba(22,163,74,0.2);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .main-content {
                margin-left: 0;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <a href="/" class="sidebar-logo">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Tref
            </a>
            
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('owner.dashboard') }}" class="sidebar-link active">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('owner.inquiries') }}" class="sidebar-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Messages
                        @if($newInquiries > 0)
                        <span class="badge">{{ $newInquiries }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('owner.analytics') }}" class="sidebar-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                        Analytics
                    </a>
                </li>
            </ul>
            
            <div class="sidebar-section">Properties</div>
            <ul class="sidebar-nav">
                @foreach($properties as $property)
                <li>
                    <a href="{{ route('owner.property.edit', $property->id) }}" class="sidebar-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        </svg>
                        {{ Str::limit($property->title, 20) }}
                    </a>
                </li>
                @endforeach
                <li>
                    <a href="{{ route('register') }}" class="sidebar-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        Add Property
                    </a>
                </li>
            </ul>
            
            <div class="user-menu">
                <div class="user-avatar">{{ strtoupper(substr($user->first_name, 0, 1)) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ $user->first_name }} {{ $user->last_name }}</div>
                    <div class="user-email">{{ $user->email }}</div>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline" title="Logout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            @if(session('success'))
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                {{ session('success') }}
            </div>
            @endif
            
            <div class="page-header">
                <div>
                    <h1 class="page-title">Welcome back, {{ $user->first_name }}!</h1>
                    <p class="page-subtitle">Here's what's happening with your properties.</p>
                </div>
                <div class="header-actions">
                    <a href="/" class="btn btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        View Site
                    </a>
                </div>
            </div>
            
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon blue">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">{{ number_format($viewsThisMonth) }}</div>
                    <div class="stat-label">Views This Month</div>
                    <div class="stat-trend {{ $viewTrend >= 0 ? 'up' : 'down' }}">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            @if($viewTrend >= 0)
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                            <polyline points="17 6 23 6 23 12"></polyline>
                            @else
                            <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                            <polyline points="17 18 23 18 23 12"></polyline>
                            @endif
                        </svg>
                        {{ abs($viewTrend) }}% vs last month
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon green">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">{{ $totalInquiries }}</div>
                    <div class="stat-label">Total Inquiries</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon orange">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 16v-4"></path>
                                <path d="M12 8h.01"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">{{ $newInquiries }}</div>
                    <div class="stat-label">New Messages</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon blue">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">{{ $properties->count() }}</div>
                    <div class="stat-label">Active Listings</div>
                </div>
            </div>
            
            <!-- Two Column Layout -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                <!-- Properties -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Your Properties</h2>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-primary">+ Add Property</a>
                    </div>
                    <div class="card-body">
                        @if($properties->count() > 0)
                        <div class="properties-grid">
                            @foreach($properties as $property)
                            <div class="property-card">
                                @php
                                    $image = $property->images->first();
                                    $imageUrl = $image ? $image->image_url : '/images/placeholder.jpg';
                                @endphp
                                <img src="{{ $imageUrl }}" alt="{{ $property->title }}" class="property-image">
                                <div class="property-content">
                                    <h3 class="property-title">{{ $property->title }}</h3>
                                    <div class="property-location">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        {{ Str::limit($property->map_address, 30) ?? 'No address' }}
                                    </div>
                                    <div class="property-stats">
                                        <div class="property-stat">
                                            <span class="property-stat-value">{{ $viewsByProperty[$property->id] ?? 0 }}</span>
                                            <span class="property-stat-label">Views</span>
                                        </div>
                                        <div class="property-stat">
                                            <span class="property-stat-value">{{ format_price($property->price, $property->currency ?? 'USD') }}</span>
                                            <span class="property-stat-label">Per Night</span>
                                        </div>
                                        <div class="property-stat">
                                            <span class="property-stat-value">{{ $property->bedroom_count }}</span>
                                            <span class="property-stat-label">Beds</span>
                                        </div>
                                    </div>
                                    <div class="property-actions">
                                        <a href="{{ route('owner.property.edit', $property->id) }}" class="btn btn-sm btn-outline">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <a href="/property/{{ $property->id }}" class="btn btn-sm btn-outline" target="_blank">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <h3>No properties yet</h3>
                            <p>List your first property to start receiving bookings.</p>
                            <a href="{{ route('register') }}" class="btn btn-primary" style="margin-top: 1rem;">Add Your First Property</a>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Recent Inquiries -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Recent Inquiries</h2>
                        <a href="{{ route('owner.inquiries') }}" class="btn btn-sm btn-outline">View All</a>
                    </div>
                    <div class="card-body" style="padding: 0 1.5rem;">
                        @if($recentInquiries->count() > 0)
                        <ul class="inquiry-list">
                            @foreach($recentInquiries->take(5) as $inquiry)
                            <li class="inquiry-item">
                                <div class="inquiry-avatar">{{ strtoupper(substr($inquiry->name, 0, 1)) }}</div>
                                <div class="inquiry-content">
                                    <div class="inquiry-header">
                                        <span class="inquiry-name">{{ $inquiry->name }}</span>
                                        <span class="inquiry-status status-{{ $inquiry->status }}">{{ $inquiry->status }}</span>
                                    </div>
                                    <div class="inquiry-preview">{{ Str::limit($inquiry->message, 50) }}</div>
                                    <div class="inquiry-property">{{ $inquiry->property->title ?? 'Unknown Property' }}</div>
                                    <div class="inquiry-time">{{ $inquiry->created_at->diffForHumans() }}</div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <h3>No inquiries yet</h3>
                            <p>When guests contact you, their messages will appear here.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
