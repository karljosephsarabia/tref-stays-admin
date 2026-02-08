<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiries - Tref Stays</title>
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
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
        
        .back-link:hover {
            color: var(--tref-blue);
        }
        
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
        
        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .filter-tab {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            color: var(--text-muted);
            background: white;
            border: 1px solid var(--border);
            cursor: pointer;
        }
        
        .filter-tab.active {
            background: var(--tref-blue);
            color: white;
            border-color: var(--tref-blue);
        }
        
        .card {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid var(--border);
        }
        
        .inquiry-row {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            text-decoration: none;
            color: inherit;
            transition: background 0.2s;
        }
        
        .inquiry-row:hover {
            background: var(--bg-secondary);
        }
        
        .inquiry-row:last-child {
            border-bottom: none;
        }
        
        .inquiry-row.unread {
            background: rgba(0,128,255,0.03);
        }
        
        .inquiry-avatar {
            width: 3rem;
            height: 3rem;
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
        
        .inquiry-email {
            font-size: 0.8125rem;
            color: var(--text-muted);
        }
        
        .inquiry-message {
            color: var(--text-muted);
            font-size: 0.875rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .inquiry-property {
            font-size: 0.75rem;
            color: var(--tref-blue);
            margin-top: 0.25rem;
        }
        
        .inquiry-meta {
            text-align: right;
            flex-shrink: 0;
        }
        
        .inquiry-time {
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        
        .inquiry-status {
            display: inline-block;
            padding: 0.25rem 0.625rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 0.25rem;
        }
        
        .status-new { background: rgba(239,68,68,0.1); color: var(--danger); }
        .status-read { background: rgba(245,158,11,0.1); color: var(--warning); }
        .status-responded { background: rgba(22,163,74,0.1); color: var(--success); }
        .status-converted { background: rgba(0,128,255,0.1); color: var(--tref-blue); }
        .status-archived { background: var(--bg-secondary); color: var(--text-muted); }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-muted);
        }
        
        .empty-state svg {
            width: 4rem;
            height: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .empty-state h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }
        
        .pagination a, .pagination span {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            text-decoration: none;
            color: var(--text);
            background: white;
            border: 1px solid var(--border);
        }
        
        .pagination a:hover {
            background: var(--bg-secondary);
        }
        
        .pagination .active {
            background: var(--tref-blue);
            color: white;
            border-color: var(--tref-blue);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('owner.dashboard') }}" class="back-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            Back to Dashboard
        </a>
        
        <div class="page-header">
            <h1 class="page-title">Inquiries & Messages</h1>
        </div>
        
        <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all">All</button>
            <button class="filter-tab" data-filter="new">New</button>
            <button class="filter-tab" data-filter="read">Read</button>
            <button class="filter-tab" data-filter="responded">Responded</button>
        </div>
        
        <div class="card">
            @if($inquiries->count() > 0)
                @foreach($inquiries as $inquiry)
                <a href="{{ route('owner.inquiry.show', $inquiry->id) }}" class="inquiry-row {{ $inquiry->status === 'new' ? 'unread' : '' }}" data-status="{{ $inquiry->status }}">
                    <div class="inquiry-avatar">{{ strtoupper(substr($inquiry->name, 0, 1)) }}</div>
                    <div class="inquiry-content">
                        <div class="inquiry-header">
                            <span class="inquiry-name">{{ $inquiry->name }}</span>
                            <span class="inquiry-email">{{ $inquiry->email }}</span>
                        </div>
                        <div class="inquiry-message">{{ $inquiry->message }}</div>
                        <div class="inquiry-property">{{ $inquiry->property->title ?? 'Unknown Property' }}</div>
                    </div>
                    <div class="inquiry-meta">
                        <div class="inquiry-time">{{ $inquiry->created_at->diffForHumans() }}</div>
                        <span class="inquiry-status status-{{ $inquiry->status }}">{{ $inquiry->status }}</span>
                    </div>
                </a>
                @endforeach
            @else
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <h3>No inquiries yet</h3>
                <p>When guests contact you about your properties, their messages will appear here.</p>
            </div>
            @endif
        </div>
        
        @if($inquiries->hasPages())
        <div class="pagination">
            {{ $inquiries->links() }}
        </div>
        @endif
    </div>
    
    <script>
        // Filter functionality
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                document.querySelectorAll('.inquiry-row').forEach(row => {
                    if (filter === 'all' || row.dataset.status === filter) {
                        row.style.display = 'flex';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>
