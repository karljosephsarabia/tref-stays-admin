@extends('admin.layouts.app')

@section('title', 'Financial Reports')
@section('page-title', 'Financial Reports')

@section('content')
<!-- Date Range Filter -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.finances') }}" class="filters">
            <div class="filter-group">
                <label>Start Date:</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="filter-group">
                <label>End Date:</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Apply Filter
            </button>
            <a href="{{ route('admin.finances') }}" class="btn btn-secondary">
                <i class="fas fa-undo"></i> Reset
            </a>
            <div style="margin-left: auto;">
                <button type="button" class="btn btn-secondary" onclick="exportReport()">
                    <i class="fas fa-download"></i> Export Report
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Financial Stats -->
<div class="stats-grid" style="margin-bottom: 32px;">
    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="stat-icon" style="background: rgba(255,255,255,0.2); color: white;">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label" style="color: rgba(255,255,255,0.8);">Total Revenue</div>
            <div class="stat-value">${{ number_format($totalRevenue, 2) }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Bookings</div>
            <div class="stat-value">{{ number_format($totalBookings) }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-chart-bar"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Avg. Booking Value</div>
            <div class="stat-value">${{ number_format($avgBookingValue, 2) }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-percentage"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Commissions</div>
            <div class="stat-value">${{ number_format($totalCommissions, 2) }}</div>
        </div>
    </div>
</div>

<div class="grid-2" style="margin-bottom: 32px;">
    <!-- Revenue Chart -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Revenue Trend</h3>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Revenue Breakdown -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Revenue Breakdown</h3>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="breakdownChart"></canvas>
            </div>
            <div style="margin-top: 24px;">
                <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--gray-200);">
                    <span style="color: var(--gray-500);">Gross Revenue</span>
                    <strong>${{ number_format($totalRevenue, 2) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--gray-200);">
                    <span style="color: var(--gray-500);">Commissions</span>
                    <strong style="color: var(--warning);">-${{ number_format($totalCommissions, 2) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 12px 0; font-size: 18px;">
                    <span style="font-weight: 600;">Net Revenue</span>
                    <strong style="color: var(--success);">${{ number_format($totalRevenue - $totalCommissions, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Properties by Revenue -->
<div class="card" style="margin-bottom: 32px;">
    <div class="card-header">
        <h3 class="card-title">Top Properties by Revenue</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Bookings</th>
                        <th>Revenue</th>
                        <th>% of Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($revenueByProperty as $item)
                    <tr>
                        <td>
                            <div class="property-cell">
                                <div class="property-thumb" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                                <div>
                                    <div style="font-weight: 500;">{{ $item->property->title ?? 'Unknown Property' }}</div>
                                    <div style="font-size: 12px; color: var(--gray-500);">{{ $item->property->map_address ?? 'No address' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $item->bookings }} bookings</span>
                        </td>
                        <td>
                            <strong style="font-size: 16px;">${{ number_format($item->revenue, 2) }}</strong>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="flex: 1; height: 8px; background: var(--gray-200); border-radius: 4px; overflow: hidden;">
                                    <div style="width: {{ $totalRevenue > 0 ? ($item->revenue / $totalRevenue) * 100 : 0 }}%; height: 100%; background: var(--primary);"></div>
                                </div>
                                <span style="font-weight: 500; min-width: 50px;">{{ $totalRevenue > 0 ? number_format(($item->revenue / $totalRevenue) * 100, 1) : 0 }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="fas fa-chart-bar"></i>
                                <h3>No Revenue Data</h3>
                                <p>There are no completed bookings in the selected date range.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Key Metrics Cards -->
<div class="grid-3">
    <div class="card">
        <div class="card-body" style="text-align: center;">
            <div style="font-size: 48px; color: var(--primary); margin-bottom: 16px;">
                <i class="fas fa-chart-line"></i>
            </div>
            <h4 style="font-size: 32px; font-weight: 700; margin-bottom: 8px;">${{ number_format($avgBookingValue, 0) }}</h4>
            <p style="color: var(--gray-500);">Average Booking Value</p>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body" style="text-align: center;">
            <div style="font-size: 48px; color: var(--success); margin-bottom: 16px;">
                <i class="fas fa-percentage"></i>
            </div>
            <h4 style="font-size: 32px; font-weight: 700; margin-bottom: 8px;">{{ $totalRevenue > 0 ? number_format(($totalCommissions / $totalRevenue) * 100, 1) : 0 }}%</h4>
            <p style="color: var(--gray-500);">Commission Rate</p>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body" style="text-align: center;">
            <div style="font-size: 48px; color: var(--info); margin-bottom: 16px;">
                <i class="fas fa-calendar-day"></i>
            </div>
            <h4 style="font-size: 32px; font-weight: 700; margin-bottom: 8px;">
                @php
                    $days = max(1, (strtotime($endDate) - strtotime($startDate)) / 86400);
                    $dailyAvg = $totalRevenue / $days;
                @endphp
                ${{ number_format($dailyAvg, 0) }}
            </h4>
            <p style="color: var(--gray-500);">Daily Average Revenue</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: @json($revenueByProperty->pluck('property.title')->map(fn($t) => substr($t ?? 'Unknown', 0, 15))),
            datasets: [{
                label: 'Revenue',
                data: @json($revenueByProperty->pluck('revenue')),
                backgroundColor: 'rgba(255, 56, 92, 0.8)',
                borderColor: '#FF385C',
                borderWidth: 1,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#222',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return '$' + context.raw.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    grid: { color: '#f0f0f0' },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
    
    // Breakdown Chart
    const breakdownCtx = document.getElementById('breakdownChart').getContext('2d');
    
    new Chart(breakdownCtx, {
        type: 'doughnut',
        data: {
            labels: ['Net Revenue', 'Commissions'],
            datasets: [{
                data: [{{ $totalRevenue - $totalCommissions }}, {{ $totalCommissions }}],
                backgroundColor: ['#10b981', '#f59e0b'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#222',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return context.label + ': $' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });
    
    function exportReport() {
        // Create CSV export
        const data = [
            ['Financial Report'],
            ['Date Range: {{ $startDate }} to {{ $endDate }}'],
            [''],
            ['Metric', 'Value'],
            ['Total Revenue', '${{ number_format($totalRevenue, 2) }}'],
            ['Total Bookings', '{{ $totalBookings }}'],
            ['Average Booking Value', '${{ number_format($avgBookingValue, 2) }}'],
            ['Total Commissions', '${{ number_format($totalCommissions, 2) }}'],
            ['Net Revenue', '${{ number_format($totalRevenue - $totalCommissions, 2) }}'],
        ];
        
        let csv = data.map(row => row.join(',')).join('\n');
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `financial-report-{{ $startDate }}-{{ $endDate }}.csv`;
        a.click();
        
        showToast('Report exported successfully!', 'success');
    }
</script>
@endsection
