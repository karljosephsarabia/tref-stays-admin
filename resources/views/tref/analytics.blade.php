<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Tref Stays</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            max-width: 1100px;
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
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
        }
        
        .page-subtitle {
            color: var(--text-muted);
        }
        
        .card {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .stat-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid var(--border);
            text-align: center;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--tref-blue);
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
        
        .source-list {
            list-style: none;
        }
        
        .source-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border);
        }
        
        .source-item:last-child {
            border-bottom: none;
        }
        
        .source-name {
            font-weight: 500;
            text-transform: capitalize;
        }
        
        .source-count {
            font-weight: 600;
            color: var(--tref-blue);
        }
        
        .property-bar {
            margin-bottom: 1rem;
        }
        
        .property-bar-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        
        .property-bar-name {
            font-weight: 500;
        }
        
        .property-bar-value {
            color: var(--text-muted);
        }
        
        .property-bar-track {
            height: 8px;
            background: var(--bg-secondary);
            border-radius: 4px;
            overflow: hidden;
        }
        
        .property-bar-fill {
            height: 100%;
            background: var(--tref-blue);
            border-radius: 4px;
            transition: width 0.3s;
        }
        
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
            <h1 class="page-title">Analytics</h1>
            <p class="page-subtitle">Track how your properties are performing</p>
        </div>
        
        <!-- Summary Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $dailyViews->sum('views') }}</div>
                <div class="stat-label">Views (Last 30 Days)</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $properties->count() }}</div>
                <div class="stat-label">Active Listings</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $dailyViews->count() > 0 ? round($dailyViews->sum('views') / max($dailyViews->count(), 1)) : 0 }}</div>
                <div class="stat-label">Avg Views/Day</div>
            </div>
        </div>
        
        <!-- Views Chart -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Views Over Time (Last 30 Days)</h2>
            </div>
            <div class="card-body">
                @if($dailyViews->count() > 0)
                <div class="chart-container">
                    <canvas id="viewsChart"></canvas>
                </div>
                @else
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    <h3>No views yet</h3>
                    <p>When people view your properties, the data will appear here.</p>
                </div>
                @endif
            </div>
        </div>
        
        <div class="grid-2">
            <!-- Views by Source -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Traffic Sources</h2>
                </div>
                <div class="card-body">
                    @if($viewsBySource->count() > 0)
                    <ul class="source-list">
                        @foreach($viewsBySource as $source)
                        <li class="source-item">
                            <span class="source-name">{{ $source->source ?: 'Unknown' }}</span>
                            <span class="source-count">{{ $source->count }} views</span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="empty-state" style="padding: 2rem;">
                        <p>No traffic data yet</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Views by Property -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Views by Property</h2>
                </div>
                <div class="card-body">
                    @if($viewsByProperty->count() > 0)
                        @php
                            $maxViews = $viewsByProperty->max('views') ?: 1;
                        @endphp
                        @foreach($viewsByProperty as $prop)
                        <div class="property-bar">
                            <div class="property-bar-header">
                                <span class="property-bar-name">{{ Str::limit($prop->name, 30) }}</span>
                                <span class="property-bar-value">{{ $prop->views }} views</span>
                            </div>
                            <div class="property-bar-track">
                                <div class="property-bar-fill" style="width: {{ ($prop->views / $maxViews) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="empty-state" style="padding: 2rem;">
                        <p>No property data yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @if($dailyViews->count() > 0)
    <script>
        const ctx = document.getElementById('viewsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyViews->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M j'))) !!},
                datasets: [{
                    label: 'Views',
                    data: {!! json_encode($dailyViews->pluck('views')) !!},
                    borderColor: '#0080ff',
                    backgroundColor: 'rgba(0, 128, 255, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
    @endif
</body>
</html>
