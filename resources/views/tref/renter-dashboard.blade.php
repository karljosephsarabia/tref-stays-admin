<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Dashboard - Tref Stays</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --light: #f8fafc;
            --border: #e2e8f0;
            --text: #334155;
            --text-muted: #94a3b8;
            --shadow: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.1);
            --radius: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light);
            color: var(--text);
            line-height: 1.6;
        }

        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            border-right: 1px solid var(--border);
            padding: 1.5rem;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .sidebar-nav {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            background: var(--primary);
            color: white;
        }

        .nav-link svg {
            width: 20px;
            height: 20px;
        }

        .nav-badge {
            background: var(--danger);
            color: white;
            font-size: 0.75rem;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            margin-left: auto;
        }

        .sidebar-divider {
            height: 1px;
            background: var(--border);
            margin: 1.5rem 0;
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: var(--light);
            border-radius: 8px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-name {
            font-weight: 500;
            font-size: 0.875rem;
        }

        .user-email {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
        }

        .page-subtitle {
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .stat-icon.blue { background: #dbeafe; color: var(--primary); }
        .stat-icon.green { background: #d1fae5; color: var(--success); }
        .stat-icon.orange { background: #fef3c7; color: var(--warning); }
        .stat-icon.red { background: #fee2e2; color: var(--danger); }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        /* Section */
        .section {
            margin-bottom: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .section-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .section-link:hover {
            text-decoration: underline;
        }

        /* Property Cards */
        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .property-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .property-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .property-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .property-content {
            padding: 1rem;
        }

        .property-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--dark);
        }

        .property-location {
            color: var(--text-muted);
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .property-price {
            font-weight: 700;
            color: var(--primary);
        }

        .property-price span {
            font-weight: 400;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .property-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
        }

        .btn-outline:hover {
            background: var(--light);
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        /* Conversations List */
        .conversations-list {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .conversation-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            text-decoration: none;
            color: inherit;
            transition: background 0.2s;
        }

        .conversation-item:last-child {
            border-bottom: none;
        }

        .conversation-item:hover {
            background: var(--light);
        }

        .conversation-item.unread {
            background: #eff6ff;
        }

        .conversation-avatar {
            width: 48px;
            height: 48px;
            background: var(--secondary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }

        .conversation-content {
            flex: 1;
            min-width: 0;
        }

        .conversation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.25rem;
        }

        .conversation-name {
            font-weight: 600;
            font-size: 0.9375rem;
        }

        .conversation-time {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .conversation-preview {
            font-size: 0.875rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .conversation-property {
            font-size: 0.75rem;
            color: var(--primary);
            margin-top: 0.25rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: var(--radius);
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.125rem;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                width: 80px;
                padding: 1rem 0.5rem;
            }
            
            .sidebar-logo span,
            .nav-link span,
            .user-info .user-details,
            .sidebar-divider {
                display: none;
            }
            
            .nav-link {
                justify-content: center;
                padding: 0.75rem;
            }
            
            .main-content {
                margin-left: 80px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <a href="/" class="sidebar-logo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 3L4 9v12h16V9l-8-6zm0 2.5L18 10v9H6v-9l6-4.5z"/>
                </svg>
                <span>Tref Stays</span>
            </a>
            
            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a href="{{ route('renter.dashboard') }}" class="nav-link active">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="9" rx="1"></rect>
                            <rect x="14" y="3" width="7" height="5" rx="1"></rect>
                            <rect x="14" y="12" width="7" height="9" rx="1"></rect>
                            <rect x="3" y="16" width="7" height="5" rx="1"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('renter.saved') }}" class="nav-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        <span>Saved Properties</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('renter.messages') }}" class="nav-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>Messages</span>
                        @if($unreadCount > 0)
                        <span class="nav-badge">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('renter.reviews') }}" class="nav-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <span>My Reviews</span>
                    </a>
                </li>
            </ul>
            
            <div class="sidebar-divider"></div>
            
            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a href="/" class="nav-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Browse Properties</span>
                    </a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="nav-link" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}</div>
                    <div class="user-details">
                        <div class="user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                        <div class="user-email">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Welcome back, {{ Auth::user()->first_name }}!</h1>
                <p class="page-subtitle">Here's what's happening with your account</p>
            </div>

            @if(session('success'))
            <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
            @endif

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </div>
                    <div class="stat-value">{{ $savedProperties->count() }}</div>
                    <div class="stat-label">Saved Properties</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                    <div class="stat-value">{{ $unreadCount }}</div>
                    <div class="stat-label">Unread Messages</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>
                    <div class="stat-value">{{ $reviewCount }}</div>
                    <div class="stat-label">Reviews Given</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="stat-value">{{ $reservationCount }}</div>
                    <div class="stat-label">Reservations</div>
                </div>
            </div>

            <!-- Saved Properties -->
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Saved Properties</h2>
                    <a href="{{ route('renter.saved') }}" class="section-link">View All →</a>
                </div>
                
                @if($savedProperties->count() > 0)
                <div class="properties-grid">
                    @foreach($savedProperties as $saved)
                    @php
                        $property = $saved->property;
                        $imageUrl = $property->images->first()->image_url ?? '/images/placeholder.jpg';
                    @endphp
                    <div class="property-card">
                        <img src="{{ $imageUrl }}" alt="{{ $property->title }}" class="property-image">
                        <div class="property-content">
                            <h3 class="property-title">{{ $property->title }}</h3>
                            <div class="property-location">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                {{ Str::limit($property->map_address, 30) ?? 'Location not set' }}
                            </div>
                            <div class="property-price">{{ format_price($property->price, $property->currency ?? 'USD') }} <span>/ night</span></div>
                            <div class="property-actions">
                                <a href="/property/{{ $property->id }}" class="btn btn-sm btn-primary">View</a>
                                <a href="{{ route('renter.start-conversation', $property->id) }}" class="btn btn-sm btn-outline">Contact Host</a>
                                <form action="{{ route('renter.toggle-save', $property->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    <h3>No saved properties yet</h3>
                    <p>Start exploring and save properties you love!</p>
                    <a href="/" class="btn btn-primary">Browse Properties</a>
                </div>
                @endif
            </div>

            <!-- Recent Messages -->
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Recent Conversations</h2>
                    <a href="{{ route('renter.messages') }}" class="section-link">View All →</a>
                </div>
                
                @if($conversations->count() > 0)
                <div class="conversations-list">
                    @foreach($conversations as $conversation)
                    @php
                        $otherUser = $conversation->getOtherUser(Auth::id());
                        $unread = $conversation->getUnreadCount(Auth::id());
                    @endphp
                    <a href="{{ route('renter.conversation', $conversation->id) }}" class="conversation-item {{ $unread > 0 ? 'unread' : '' }}">
                        <div class="conversation-avatar">
                            {{ strtoupper(substr($otherUser->first_name ?? 'H', 0, 1)) }}
                        </div>
                        <div class="conversation-content">
                            <div class="conversation-header">
                                <span class="conversation-name">{{ $otherUser->first_name ?? 'Host' }} {{ $otherUser->last_name ?? '' }}</span>
                                <span class="conversation-time">
                                    {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : '' }}
                                </span>
                            </div>
                            <div class="conversation-preview">
                                {{ Str::limit($conversation->latestMessage->message ?? 'No messages yet', 50) }}
                            </div>
                            @if($conversation->property)
                            <div class="conversation-property">
                                Re: {{ $conversation->property->title }}
                            </div>
                            @endif
                        </div>
                        @if($unread > 0)
                        <span class="nav-badge">{{ $unread }}</span>
                        @endif
                    </a>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <h3>No conversations yet</h3>
                    <p>Contact a host to start a conversation!</p>
                    <a href="/" class="btn btn-primary">Find Properties</a>
                </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
