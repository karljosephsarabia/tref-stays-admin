@extends('admin.layouts.app')

@section('title', 'Analytics')
@section('page-title', 'Analytics & Reports')

@section('content')
<!-- Date Range Filter -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-body">
        <form class="filters-form" method="GET" style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label class="form-label">Date Range</label>
                <select name="days" class="form-control" onchange="this.form.submit()">
                    <option value="7" {{ ($days ?? 30) == 7 ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30" {{ ($days ?? 30) == 30 ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="90" {{ ($days ?? 30) == 90 ? 'selected' : '' }}>Last 90 Days</option>
                    <option value="365" {{ ($days ?? 30) == 365 ? 'selected' : '' }}>Last Year</option>
                </select>
            </div>
            <button type="button" class="btn btn-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Print Report
            </button>
        </form>
    </div>
</div>

<!-- Key Metrics - Real Data -->
<div class="stats-grid" style="grid-template-columns: repeat(5, 1fr);">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(37,99,235,0.1);">
            <i class="fas fa-users" style="color: var(--primary);"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($totalUsers ?? 0) }}</div>
            <div class="stat-label">Total Users</div>
            <div class="stat-change positive">+{{ $newUsers ?? 0 }} new</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16,185,129,0.1);">
            <i class="fas fa-home" style="color: var(--success);"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($totalProperties ?? 0) }}</div>
            <div class="stat-label">Total Properties</div>
            <div class="stat-change">{{ $activeProperties ?? 0 }} active</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(59,130,246,0.1);">
            <i class="fas fa-calendar-check" style="color: var(--info);"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($totalReservations ?? 0) }}</div>
            <div class="stat-label">Total Reservations</div>
            <div class="stat-change positive">+{{ $periodReservations ?? 0 }} this period</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(245,158,11,0.1);">
            <i class="fas fa-dollar-sign" style="color: var(--warning);"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">${{ number_format($totalRevenue ?? 0, 2) }}</div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-change positive">${{ number_format($periodRevenue ?? 0, 2) }} this period</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(139,92,246,0.1);">
            <i class="fas fa-percentage" style="color: #8b5cf6;"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($conversionRate ?? 0, 1) }}%</div>
            <div class="stat-label">Conversion Rate</div>
            <div class="stat-change">${{ number_format($avgBookingValue ?? 0, 2) }} avg</div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid-2" style="margin-top: 24px;">
    <!-- Revenue Chart -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Monthly Revenue</h3>
        </div>
        <div class="card-body">
            <canvas id="revenueChart" height="300"></canvas>
        </div>
    </div>
    
    <!-- Reservations by Status -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Reservations by Status</h3>
        </div>
        <div class="card-body">
            <canvas id="statusChart" height="250"></canvas>
            <div style="margin-top: 20px;">
                @foreach($reservationsByStatus ?? [] as $status)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--gray-100);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: {{ $status['color'] }};"></div>
                        <span>{{ $status['status'] }}</span>
                    </div>
                    <span style="font-weight: 600;">{{ $status['count'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Second Row -->
<div class="grid-2" style="margin-top: 24px;">
    <!-- Top Properties -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Top Properties by Bookings</h3>
        </div>
        <div class="card-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Owner</th>
                        <th>Bookings</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProperties ?? [] as $property)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 50px; height: 50px; background: var(--gray-200); border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                    @if($property->images && $property->images->first())
                                        <img src="{{ property_image_url($property->images->first()->image_url) }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('/images/property-placeholder.svg') }}'">
                                    @else
                                        <i class="fas fa-home" style="color: var(--gray-400);"></i>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight: 500;">{{ Str::limit($property->title, 25) }}</div>
                                    <div style="font-size: 12px; color: var(--gray-500);">{{ $property->city ?? 'N/A' }}, {{ $property->state ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $property->owner->first_name ?? 'N/A' }} {{ $property->owner->last_name ?? '' }}</td>
                        <td>
                            <span class="badge badge-success">{{ $property->reservations_count ?? 0 }}</span>
                        </td>
                        <td>${{ number_format($property->price ?? 0, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--gray-500);">No property data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Top Locations -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Properties by Location</h3>
        </div>
        <div class="card-body">
            @if(isset($topLocations) && $topLocations->count() > 0)
                @php $maxCount = $topLocations->first()->property_count ?? 1; @endphp
                @foreach($topLocations as $location)
                <div style="display: flex; align-items: center; gap: 16px; padding: 12px 0; border-bottom: 1px solid var(--gray-100);">
                    <i class="fas fa-map-marker-alt" style="color: var(--primary); width: 20px;"></i>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="font-weight: 500;">{{ $location->city }}, {{ $location->state }}</span>
                            <span style="color: var(--gray-500);">{{ $location->property_count }} properties</span>
                        </div>
                        <div style="background: var(--gray-200); height: 6px; border-radius: 3px;">
                            <div style="background: var(--primary); height: 100%; width: {{ ($location->property_count / $maxCount) * 100 }}%; border-radius: 3px;"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div style="text-align: center; color: var(--gray-500); padding: 40px;">
                    <i class="fas fa-map-marker-alt" style="font-size: 40px; margin-bottom: 12px;"></i>
                    <p>No location data available</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- User Growth -->
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h3 class="card-title">User Growth (Last 12 Months)</h3>
    </div>
    <div class="card-body">
        <canvas id="userGrowthChart" height="200"></canvas>
    </div>
</div>

<!-- Daily Activity -->
<div class="card" style="margin-top: 24px;">
    <div class="card-header">
        <h3 class="card-title">Daily Reservations & Revenue (Last {{ $days ?? 30 }} Days)</h3>
    </div>
    <div class="card-body">
        <canvas id="dailyChart" height="250"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($monthlyRevenue ?? [] as $month)
                    '{{ $month['month'] }}',
                @endforeach
            ],
            datasets: [{
                label: 'Revenue ($)',
                data: [
                    @foreach($monthlyRevenue ?? [] as $month)
                        {{ $month['revenue'] }},
                    @endforeach
                ],
                backgroundColor: 'rgba(37, 99, 235, 0.8)',
                borderColor: '#2563EB',
                borderWidth: 1
            }, {
                label: 'Bookings',
                data: [
                    @foreach($monthlyRevenue ?? [] as $month)
                        {{ $month['bookings'] }},
                    @endforeach
                ],
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: '#10B981',
                borderWidth: 1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revenue ($)'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    title: {
                        display: true,
                        text: 'Bookings'
                    }
                }
            }
        }
    });

    // Reservations by Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($reservationsByStatus ?? [] as $status)
                    '{{ $status['status'] }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($reservationsByStatus ?? [] as $status)
                        {{ $status['count'] }},
                    @endforeach
                ],
                backgroundColor: [
                    @foreach($reservationsByStatus ?? [] as $status)
                        '{{ $status['color'] }}',
                    @endforeach
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($userGrowth ?? [] as $month)
                    '{{ $month['month'] }}',
                @endforeach
            ],
            datasets: [{
                label: 'Total Users',
                data: [
                    @foreach($userGrowth ?? [] as $month)
                        {{ $month['users'] }},
                    @endforeach
                ],
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Daily Activity Chart
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($dailyData ?? [] as $day)
                    '{{ $day['date'] }}',
                @endforeach
            ],
            datasets: [{
                label: 'Reservations',
                data: [
                    @foreach($dailyData ?? [] as $day)
                        {{ $day['reservations'] }},
                    @endforeach
                ],
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                fill: true,
                tension: 0.4,
                yAxisID: 'y'
            }, {
                label: 'Revenue ($)',
                data: [
                    @foreach($dailyData ?? [] as $day)
                        {{ $day['revenue'] }},
                    @endforeach
                ],
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Reservations'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    title: {
                        display: true,
                        text: 'Revenue ($)'
                    }
                }
            }
        }
    });
});
</script>
@endsection
