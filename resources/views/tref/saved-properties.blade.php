<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Properties - Tref Stays</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
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

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--light); color: var(--text); }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--text);
        }

        .page-title { font-size: 1.5rem; font-weight: 700; }

        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
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
            height: 200px;
            object-fit: cover;
        }

        .property-content {
            padding: 1.25rem;
        }

        .property-title {
            font-weight: 600;
            font-size: 1.125rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .property-location {
            color: var(--text-muted);
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-bottom: 0.75rem;
        }

        .property-details {
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
        }

        .property-price {
            font-weight: 700;
            font-size: 1.25rem;
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

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            flex: 1;
        }

        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
        }
        .btn-outline:hover { background: var(--light); }

        .btn-danger { background: var(--danger); color: white; }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: var(--radius);
            grid-column: 1 / -1;
        }

        .empty-state svg { width: 64px; height: 64px; color: var(--text-muted); margin-bottom: 1rem; }
        .empty-state h3 { font-size: 1.125rem; margin-bottom: 0.5rem; }
        .empty-state p { color: var(--text-muted); margin-bottom: 1rem; }

        @media (max-width: 768px) {
            .container { padding: 1rem; }
            .properties-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <a href="{{ route('renter.dashboard') }}" class="back-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="page-title">Saved Properties</h1>
        </div>

        @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
        @endif

        <div class="properties-grid">
            @forelse($savedProperties as $saved)
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
                        {{ Str::limit($property->map_address, 40) ?? 'Location not set' }}
                    </div>
                    <div class="property-details">
                        <span>{{ $property->bedroom_count }} beds</span>
                        <span>{{ $property->bathroom_count }} baths</span>
                        <span>{{ $property->guest_count }} guests</span>
                    </div>
                    <div class="property-price">{{ format_price($property->price, $property->currency ?? 'USD') }} <span>/ night</span></div>
                    <div class="property-actions">
                        <a href="/property/{{ $property->id }}" class="btn btn-primary">View</a>
                        <a href="{{ route('renter.start-conversation', $property->id) }}" class="btn btn-outline">Contact</a>
                        <form action="{{ route('renter.toggle-save', $property->id) }}" method="POST" style="flex: 0;">
                            @csrf
                            <button type="submit" class="btn btn-danger" title="Remove from saved">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
                <h3>No saved properties</h3>
                <p>Start exploring and save properties you love!</p>
                <a href="/" class="btn btn-primary">Browse Properties</a>
            </div>
            @endforelse
        </div>

        @if($savedProperties->hasPages())
        <div style="margin-top: 2rem; text-align: center;">
            {{ $savedProperties->links() }}
        </div>
        @endif
    </div>
</body>
</html>
