<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reviews - Tref Stays</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --dark: #1e293b;
            --light: #f8fafc;
            --border: #e2e8f0;
            --text: #334155;
            --text-muted: #94a3b8;
            --shadow: 0 1px 3px rgba(0,0,0,0.1);
            --radius: 12px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--light); color: var(--text); }

        .container {
            max-width: 800px;
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

        .review-card {
            background: white;
            border-radius: var(--radius);
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow);
        }

        .review-header {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .review-property-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
        }

        .review-property-info { flex: 1; }
        .review-property-title { font-weight: 600; margin-bottom: 0.25rem; }
        .review-date { font-size: 0.875rem; color: var(--text-muted); }

        .review-stars {
            display: flex;
            gap: 0.25rem;
            margin: 0.5rem 0;
        }

        .star {
            width: 20px;
            height: 20px;
            color: var(--border);
        }

        .star.filled { color: var(--warning); }

        .review-text {
            line-height: 1.6;
            color: var(--text);
        }

        .review-status {
            display: inline-block;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            margin-top: 0.75rem;
        }

        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #d1fae5; color: #065f46; }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: var(--radius);
        }

        .empty-state svg { width: 64px; height: 64px; color: var(--text-muted); margin-bottom: 1rem; }
        .empty-state h3 { font-size: 1.125rem; margin-bottom: 0.5rem; }
        .empty-state p { color: var(--text-muted); margin-bottom: 1rem; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            background: var(--primary);
            color: white;
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
            <h1 class="page-title">My Reviews</h1>
        </div>

        @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
        @endif

        @if($reviews->count() > 0)
            @foreach($reviews as $review)
            <div class="review-card">
                <div class="review-header">
                    @php
                        $imageUrl = $review->property->images->first()->image_url ?? '/images/placeholder.jpg';
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $review->property->title }}" class="review-property-image">
                    <div class="review-property-info">
                        <div class="review-property-title">{{ $review->property->title }}</div>
                        <div class="review-date">Reviewed on {{ $review->created_at->format('M j, Y') }}</div>
                        <div class="review-stars">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="star {{ $i <= $review->rating ? 'filled' : '' }}" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                            @endfor
                        </div>
                    </div>
                </div>
                
                @if($review->review)
                <p class="review-text">{{ $review->review }}</p>
                @endif
                
                <span class="review-status {{ $review->is_approved ? 'status-approved' : 'status-pending' }}">
                    {{ $review->is_approved ? 'Published' : 'Pending Approval' }}
                </span>
            </div>
            @endforeach

            <div style="margin-top: 2rem; text-align: center;">
                {{ $reviews->links() }}
            </div>
        @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
            <h3>No reviews yet</h3>
            <p>After staying at a property, you can leave a review!</p>
            <a href="/" class="btn">Browse Properties</a>
        </div>
        @endif
    </div>
</body>
</html>
