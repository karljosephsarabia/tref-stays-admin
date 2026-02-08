@extends('admin.layouts.app')

@section('title', 'Reservations Management')
@section('page-title', 'Reservations Management')

@section('content')
<!-- Filters & Actions -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reservations') }}" class="filters">
            <div class="filter-group">
                <input type="text" name="search" class="form-control" placeholder="Search reservations..." value="{{ request('search') }}" style="width: 200px;">
            </div>
            <div class="filter-group">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>From:</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="filter-group">
                <label>To:</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.reservations') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Clear
            </a>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Reservations</div>
            <div class="stat-value">{{ $reservations->total() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $reservations->where('status', 1)->count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Confirmed</div>
            <div class="stat-value">{{ $reservations->where('status', 2)->count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">${{ number_format($reservations->sum('total_price'), 0) }}</div>
        </div>
    </div>
</div>

<!-- Reservations Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Reservations</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Confirmation #</th>
                        <th>Property</th>
                        <th>Guest</th>
                        <th>Dates</th>
                        <th>Nights</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                    <tr id="reservation-row-{{ $reservation->id }}">
                        <td>
                            <strong>#{{ $reservation->confirmation_number ?: $reservation->id }}</strong>
                        </td>
                        <td>
                            <div class="property-cell">
                                <div class="property-thumb" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                                <div>
                                    <div style="font-weight: 500;">{{ $reservation->property->title ?? 'N/A' }}</div>
                                    <div style="font-size: 12px; color: var(--gray-500);">
                                        {{ $reservation->property->map_address ?? 'No address' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar-sm" style="background: var(--info);">
                                    {{ strtoupper(substr($reservation->customer->first_name ?? 'G', 0, 1)) }}
                                </div>
                                <div class="user-details">
                                    <div class="name">{{ $reservation->customer->first_name ?? 'N/A' }} {{ $reservation->customer->last_name ?? '' }}</div>
                                    <div class="email">{{ $reservation->customer->email ?? 'No email' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 13px;">
                                <div><strong>{{ $reservation->date_start ? date('M d, Y', strtotime($reservation->date_start)) : 'N/A' }}</strong></div>
                                <div style="color: var(--gray-500);">to {{ $reservation->date_end ? date('M d, Y', strtotime($reservation->date_end)) : 'N/A' }}</div>
                            </div>
                        </td>
                        <td>
                            <span style="font-weight: 500;">{{ $reservation->night_count }} nights</span>
                        </td>
                        <td>
                            <div>
                                <strong style="font-size: 16px;">${{ number_format($reservation->total_price, 2) }}</strong>
                                @if($reservation->broker_cut > 0)
                                <div style="font-size: 11px; color: var(--gray-500);">
                                    Commission: ${{ number_format($reservation->broker_cut, 2) }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            @php
                                $statusColors = [1 => 'warning', 2 => 'success', 3 => 'danger', 4 => 'info', 5 => 'primary', 6 => 'secondary'];
                            @endphp
                            <select class="form-control" style="width: auto; padding: 6px 12px; font-size: 12px;" onchange="updateReservationStatus({{ $reservation->id }}, this.value)">
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ $reservation->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <div class="actions">
                                <button class="btn btn-secondary btn-icon" onclick="viewReservation({{ $reservation->id }})" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if($reservation->status != 3)
                                <button class="btn btn-danger btn-icon" onclick="cancelReservation({{ $reservation->id }})" title="Cancel">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <h3>No Reservations Found</h3>
                                <p>Try adjusting your filters to see more results.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($reservations->hasPages())
<div class="pagination">
    {{ $reservations->withQueryString()->links() }}
</div>
@endif

<!-- View Reservation Modal -->
<div class="modal-backdrop" id="viewModal">
    <div class="modal" style="max-width: 700px;">
        <div class="modal-header">
            <h3 class="modal-title">Reservation Details</h3>
            <div class="modal-close" onclick="closeModal('viewModal')">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <div class="modal-body" id="viewReservationContent">
            <!-- Content loaded dynamically -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewModal')">Close</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const reservationsData = [
        @foreach($reservations as $r)
        {
            id: {{ $r->id }},
            confirmation_number: "{{ $r->confirmation_number ?? '' }}",
            status: {{ $r->status }},
            total_price: {{ $r->total_price ?? 0 }},
            date_start: "{{ $r->getRawOriginal('date_start') }}",
            date_end: "{{ $r->getRawOriginal('date_end') }}",
            created_at: "{{ $r->getRawOriginal('created_at') }}",
            property: {!! $r->property ? json_encode(['id' => $r->property->id, 'title' => $r->property->title, 'map_address' => $r->property->map_address]) : 'null' !!},
            customer: {!! $r->customer ? json_encode(['id' => $r->customer->id, 'first_name' => $r->customer->first_name, 'last_name' => $r->customer->last_name, 'email' => $r->customer->email, 'phone_number' => $r->customer->phone_number]) : 'null' !!}
        },
        @endforeach
    ];
    const statuses = @json($statuses);
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }
    
    function viewReservation(reservationId) {
        const reservation = reservationsData.find(r => r.id === reservationId);
        if (reservation) {
            const statusLabel = statuses[reservation.status] || 'Unknown';
            const statusColors = {1: 'warning', 2: 'success', 3: 'danger', 4: 'info', 5: 'primary', 6: 'secondary'};
            
            const content = `
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
                    <div>
                        <h4 style="font-size: 14px; color: var(--gray-500); margin-bottom: 8px;">Property</h4>
                        <div style="background: var(--gray-100); padding: 16px; border-radius: 12px;">
                            <div style="font-weight: 600; margin-bottom: 4px;">${reservation.property?.title || 'N/A'}</div>
                            <div style="font-size: 13px; color: var(--gray-500);">${reservation.property?.map_address || 'No address'}</div>
                        </div>
                    </div>
                    <div>
                        <h4 style="font-size: 14px; color: var(--gray-500); margin-bottom: 8px;">Guest</h4>
                        <div style="background: var(--gray-100); padding: 16px; border-radius: 12px;">
                            <div style="font-weight: 600; margin-bottom: 4px;">${reservation.customer?.first_name || ''} ${reservation.customer?.last_name || ''}</div>
                            <div style="font-size: 13px; color: var(--gray-500);">${reservation.customer?.email || 'No email'}</div>
                            <div style="font-size: 13px; color: var(--gray-500);">${reservation.customer?.phone_number || 'No phone'}</div>
                        </div>
                    </div>
                </div>
                
                <h4 style="font-size: 14px; color: var(--gray-500); margin: 24px 0 8px;">Booking Details</h4>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                    <div style="background: var(--gray-100); padding: 16px; border-radius: 12px; text-align: center;">
                        <div style="font-size: 12px; color: var(--gray-500); margin-bottom: 4px;">Check-in</div>
                        <div style="font-weight: 600;">${reservation.date_start || 'N/A'}</div>
                    </div>
                    <div style="background: var(--gray-100); padding: 16px; border-radius: 12px; text-align: center;">
                        <div style="font-size: 12px; color: var(--gray-500); margin-bottom: 4px;">Check-out</div>
                        <div style="font-weight: 600;">${reservation.date_end || 'N/A'}</div>
                    </div>
                    <div style="background: var(--gray-100); padding: 16px; border-radius: 12px; text-align: center;">
                        <div style="font-size: 12px; color: var(--gray-500); margin-bottom: 4px;">Nights</div>
                        <div style="font-weight: 600;">${reservation.night_count || 0}</div>
                    </div>
                </div>
                
                <h4 style="font-size: 14px; color: var(--gray-500); margin: 24px 0 8px;">Payment Summary</h4>
                <div style="background: var(--gray-100); padding: 20px; border-radius: 12px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                        <span>Nightly Rate × ${reservation.night_count} nights</span>
                        <span>$${parseFloat(reservation.price || 0).toFixed(2)}</span>
                    </div>
                    ${reservation.broker_cut > 0 ? `
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; color: var(--gray-500);">
                        <span>Broker Commission</span>
                        <span>$${parseFloat(reservation.broker_cut).toFixed(2)}</span>
                    </div>
                    ` : ''}
                    <div style="border-top: 1px solid var(--gray-300); padding-top: 12px; display: flex; justify-content: space-between; font-weight: 600; font-size: 18px;">
                        <span>Total</span>
                        <span>$${parseFloat(reservation.total_price || 0).toFixed(2)}</span>
                    </div>
                </div>
                
                <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span class="badge badge-${statusColors[reservation.status] || 'secondary'}" style="font-size: 14px; padding: 8px 16px;">
                            ${statusLabel}
                        </span>
                    </div>
                    <div style="font-size: 13px; color: var(--gray-500);">
                        Confirmation: #${reservation.confirmation_number || reservation.id}
                    </div>
                </div>
            `;
            
            document.getElementById('viewReservationContent').innerHTML = content;
            document.getElementById('viewModal').classList.add('active');
        }
    }
    
    async function updateReservationStatus(reservationId, status) {
        try {
            const response = await fetch(`/admin/reservations/${reservationId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Reservation status updated!', 'success');
            } else {
                showToast('Failed to update status', 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
        }
    }
    
    async function cancelReservation(reservationId) {
        if (!confirm('Are you sure you want to cancel this reservation?')) {
            return;
        }
        
        try {
            const response = await fetch(`/admin/reservations/${reservationId}/cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Reservation cancelled!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Failed to cancel reservation', 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
        }
    }
</script>
@endsection
