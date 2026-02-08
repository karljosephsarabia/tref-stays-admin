@extends('admin.layouts.app')

@section('title', 'User Details - ' . $user->first_name . ' ' . $user->last_name)
@section('page-title', 'User Details')

@section('styles')
<style>
    .user-profile-header {
        background: #2563EB;
        border-radius: 16px;
        padding: 32px;
        color: white;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 24px;
    }
    
    .user-avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        font-weight: 600;
        border: 4px solid rgba(255,255,255,0.3);
    }
    
    .user-profile-info h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .user-profile-info .email {
        font-size: 16px;
        opacity: 0.9;
        margin-bottom: 4px;
    }
    
    .user-profile-info .phone {
        font-size: 14px;
        opacity: 0.8;
    }
    
    .user-profile-badges {
        margin-top: 12px;
        display: flex;
        gap: 8px;
    }
    
    .profile-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: rgba(255,255,255,0.2);
    }
    
    .profile-badge.active {
        background: rgba(16, 185, 129, 0.3);
    }
    
    .profile-badge.inactive {
        background: rgba(239, 68, 68, 0.3);
    }
    
    .user-profile-actions {
        margin-left: auto;
        display: flex;
        gap: 12px;
    }
    
    .user-profile-actions .btn {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
    }
    
    .user-profile-actions .btn:hover {
        background: rgba(255,255,255,0.3);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .stat-card .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 20px;
    }
    
    .stat-card .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
    }
    
    .stat-card .stat-label {
        font-size: 12px;
        color: var(--gray-500);
        margin-top: 4px;
    }
    
    .tabs-container {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .tabs-nav {
        display: flex;
        border-bottom: 1px solid var(--gray-200);
        overflow-x: auto;
    }
    
    .tab-btn {
        padding: 16px 24px;
        font-size: 14px;
        font-weight: 500;
        color: var(--gray-500);
        background: none;
        border: none;
        cursor: pointer;
        white-space: nowrap;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
    }
    
    .tab-btn:hover {
        color: var(--dark);
        background: var(--gray-100);
    }
    
    .tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }
    
    .tab-btn .tab-count {
        background: var(--gray-200);
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 11px;
        margin-left: 8px;
    }
    
    .tab-btn.active .tab-count {
        background: rgba(46, 125, 50, 0.1);
        color: var(--primary);
    }
    
    .tab-content {
        display: none;
        padding: 24px;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 16px;
        color: var(--dark);
    }
    
    .reservation-card {
        background: var(--gray-100);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .reservation-card:hover {
        background: var(--gray-200);
    }
    
    .reservation-image {
        width: 80px;
        height: 60px;
        border-radius: 8px;
        background: var(--gray-300);
        overflow: hidden;
    }
    
    .reservation-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .reservation-info {
        flex: 1;
    }
    
    .reservation-info h4 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .reservation-info p {
        font-size: 12px;
        color: var(--gray-500);
        margin: 0;
    }
    
    .reservation-dates {
        text-align: right;
    }
    
    .reservation-dates .dates {
        font-size: 13px;
        font-weight: 500;
    }
    
    .reservation-dates .price {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary);
    }
    
    .property-mini-card {
        background: var(--gray-100);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .property-mini-card:hover {
        background: var(--gray-200);
    }
    
    .notification-item {
        padding: 12px 0;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
    
    .notification-item:last-child {
        border-bottom: none;
    }
    
    .notification-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gray-500);
    }
    
    .notification-content {
        flex: 1;
    }
    
    .notification-content h5 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .notification-content p {
        font-size: 13px;
        color: var(--gray-500);
        margin: 0;
    }
    
    .notification-time {
        font-size: 11px;
        color: var(--gray-400);
    }
    
    .transaction-row {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .transaction-row:last-child {
        border-bottom: none;
    }
    
    .transaction-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }
    
    .transaction-icon.credit {
        background: rgba(76, 175, 80, 0.1);
        color: var(--success);
    }
    
    .transaction-icon.debit {
        background: rgba(244, 67, 54, 0.1);
        color: var(--danger);
    }
    
    .transaction-details {
        flex: 1;
    }
    
    .transaction-details h5 {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 2px;
    }
    
    .transaction-details p {
        font-size: 12px;
        color: var(--gray-500);
        margin: 0;
    }
    
    .transaction-amount {
        font-size: 16px;
        font-weight: 600;
    }
    
    .transaction-amount.credit {
        color: var(--success);
    }
    
    .transaction-amount.debit {
        color: var(--danger);
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .info-item {
        padding: 12px;
        background: var(--gray-100);
        border-radius: 8px;
    }
    
    .info-item label {
        font-size: 11px;
        text-transform: uppercase;
        color: var(--gray-500);
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 4px;
    }
    
    .info-item span {
        font-size: 14px;
        font-weight: 500;
        color: var(--dark);
    }
    
    .credit-card-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: var(--gray-100);
        border-radius: 12px;
        margin-bottom: 12px;
    }
    
    .card-brand-icon {
        width: 48px;
        height: 32px;
        background: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 10px;
    }
    
    .card-details {
        flex: 1;
    }
    
    .card-number {
        font-size: 14px;
        font-weight: 600;
    }
    
    .card-expiry {
        font-size: 12px;
        color: var(--gray-500);
    }
    
    .empty-state-small {
        text-align: center;
        padding: 40px 20px;
        color: var(--gray-400);
    }
    
    .empty-state-small i {
        font-size: 40px;
        margin-bottom: 12px;
    }
    
    .empty-state-small p {
        margin: 0;
        font-size: 14px;
    }
    
    .activity-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--primary);
        margin-top: 5px;
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-content p {
        margin: 0;
        font-size: 13px;
    }
    
    .activity-time {
        font-size: 11px;
        color: var(--gray-400);
        margin-top: 4px;
    }
</style>
@endsection

@section('content')
<!-- Back Button -->
<div style="margin-bottom: 16px;">
    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
</div>

<!-- User Profile Header -->
<div class="user-profile-header">
    <div class="user-avatar-large">
        {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
    </div>
    <div class="user-profile-info">
        <h1>{{ $user->first_name }} {{ $user->last_name }}</h1>
        <p class="email"><i class="fas fa-envelope"></i> {{ $user->email }}</p>
        <p class="phone"><i class="fas fa-phone"></i> {{ $user->phone_number ?: 'No phone number' }}</p>
        <div class="user-profile-badges">
            <span class="profile-badge">{{ ucfirst($user->role_id) }}</span>
            <span class="profile-badge {{ ($user->active && $user->activated) ? 'active' : 'inactive' }}">
                {{ ($user->active && $user->activated) ? 'Active' : 'Inactive' }}
            </span>
            @if($user->pin)
            <span class="profile-badge">PIN: {{ $user->pin }}</span>
            @endif
        </div>
    </div>
    <div class="user-profile-actions">
        <a href="{{ route('admin.users') }}?edit={{ $user->id }}" class="btn btn-secondary">
            <i class="fas fa-edit"></i> Edit
        </a>
        <button class="btn btn-secondary" onclick="sendNotification({{ $user->id }})">
            <i class="fas fa-bell"></i> Notify
        </button>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(37, 99, 235, 0.1); color: #2563EB;">
            <i class="fas fa-home"></i>
        </div>
        <div class="stat-value">{{ $stats['total_properties'] }}</div>
        <div class="stat-label">Properties Owned</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-value">{{ $stats['total_reservations_made'] }}</div>
        <div class="stat-label">Reservations Made</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(14, 165, 233, 0.1); color: #0EA5E9;">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-value">{{ $stats['total_reservations_received'] }}</div>
        <div class="stat-label">Bookings Received</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <div class="stat-value">{{ $stats['active_reservations'] }}</div>
        <div class="stat-label">Active Reservations</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">
            <i class="fas fa-arrow-up"></i>
        </div>
        <div class="stat-value">${{ number_format($stats['total_spent'], 2) }}</div>
        <div class="stat-label">Total Spent</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
            <i class="fas fa-arrow-down"></i>
        </div>
        <div class="stat-value">${{ number_format($stats['total_earned'], 2) }}</div>
        <div class="stat-label">Total Earned</div>
    </div>
</div>

<!-- Tabs -->
<div class="tabs-container">
    <div class="tabs-nav">
        <button class="tab-btn active" onclick="switchTab('reservations')">
            <i class="fas fa-calendar"></i> Reservations
            <span class="tab-count">{{ $reservationsAsCustomer->count() + $reservationsAsHost->count() }}</span>
        </button>
        <button class="tab-btn" onclick="switchTab('properties')">
            <i class="fas fa-home"></i> Properties
            <span class="tab-count">{{ $properties->count() }}</span>
        </button>
        <button class="tab-btn" onclick="switchTab('transactions')">
            <i class="fas fa-dollar-sign"></i> Transactions
            <span class="tab-count">{{ $transactions->count() }}</span>
        </button>
        <button class="tab-btn" onclick="switchTab('notifications')">
            <i class="fas fa-bell"></i> Notifications
            <span class="tab-count">{{ $notifications->count() }}</span>
        </button>
        <button class="tab-btn" onclick="switchTab('activity')">
            <i class="fas fa-history"></i> Activity
            <span class="tab-count">{{ $activities->count() }}</span>
        </button>
        <button class="tab-btn" onclick="switchTab('billing')">
            <i class="fas fa-credit-card"></i> Billing
            <span class="tab-count">{{ $creditCards->count() }}</span>
        </button>
        <button class="tab-btn" onclick="switchTab('info')">
            <i class="fas fa-info-circle"></i> Info
        </button>
    </div>
    
    <!-- Reservations Tab -->
    <div class="tab-content active" id="tab-reservations">
        @if($reservationsAsCustomer->count() > 0)
        <h3 class="section-title"><i class="fas fa-plane-departure"></i> Reservations Made (As Guest)</h3>
        @foreach($reservationsAsCustomer as $reservation)
        <div class="reservation-card">
            <div class="reservation-image">
                @if($reservation->property && $reservation->property->images->first())
                    <img src="{{ property_image_url($reservation->property->images->first()->image_url) }}" alt="Property" onerror="this.onerror=null; this.src='{{ asset('/images/property-placeholder.svg') }}'">
                @else
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--gray-400);">
                        <i class="fas fa-image"></i>
                    </div>
                @endif
            </div>
            <div class="reservation-info">
                <h4>{{ $reservation->property->title ?? 'Unknown Property' }}</h4>
                <p>{{ $reservation->property->map_address ?? 'No address' }}</p>
                <p>Confirmation: {{ $reservation->confirmation_number ?? 'N/A' }}</p>
            </div>
            <div class="reservation-dates">
                @php
                    $start = $reservation->getRawOriginal('date_start');
                    $end = $reservation->getRawOriginal('date_end');
                @endphp
                <div class="dates">{{ $start ? date('M d', strtotime($start)) : 'N/A' }} - {{ $end ? date('M d, Y', strtotime($end)) : 'N/A' }}</div>
                <div class="price">${{ number_format($reservation->total_price, 2) }}</div>
                <span class="badge badge-{{ $reservation->status == 1 ? 'success' : ($reservation->status == 2 ? 'warning' : 'danger') }}">
                    {{ $reservation->status == 1 ? 'Confirmed' : ($reservation->status == 2 ? 'Pending' : 'Cancelled') }}
                </span>
            </div>
        </div>
        @endforeach
        @endif
        
        @if($reservationsAsHost->count() > 0)
        <h3 class="section-title" style="margin-top: 32px;"><i class="fas fa-plane-arrival"></i> Bookings Received (As Host)</h3>
        @foreach($reservationsAsHost as $reservation)
        <div class="reservation-card">
            <div class="reservation-image">
                @if($reservation->property && $reservation->property->images->first())
                    <img src="{{ property_image_url($reservation->property->images->first()->image_url) }}" alt="Property" onerror="this.onerror=null; this.src='{{ asset('/images/property-placeholder.svg') }}'">
                @else
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--gray-400);">
                        <i class="fas fa-image"></i>
                    </div>
                @endif
            </div>
            <div class="reservation-info">
                <h4>{{ $reservation->property->title ?? 'Unknown Property' }}</h4>
                <p>Guest: {{ $reservation->customer->first_name ?? '' }} {{ $reservation->customer->last_name ?? 'Unknown' }}</p>
                <p>Confirmation: {{ $reservation->confirmation_number ?? 'N/A' }}</p>
            </div>
            <div class="reservation-dates">
                @php
                    $start = $reservation->getRawOriginal('date_start');
                    $end = $reservation->getRawOriginal('date_end');
                @endphp
                <div class="dates">{{ $start ? date('M d', strtotime($start)) : 'N/A' }} - {{ $end ? date('M d, Y', strtotime($end)) : 'N/A' }}</div>
                <div class="price">${{ number_format($reservation->total_price, 2) }}</div>
                <span class="badge badge-{{ $reservation->status == 1 ? 'success' : ($reservation->status == 2 ? 'warning' : 'danger') }}">
                    {{ $reservation->status == 1 ? 'Confirmed' : ($reservation->status == 2 ? 'Pending' : 'Cancelled') }}
                </span>
            </div>
        </div>
        @endforeach
        @endif
        
        @if($reservationsAsCustomer->count() == 0 && $reservationsAsHost->count() == 0)
        <div class="empty-state-small">
            <i class="fas fa-calendar-times"></i>
            <p>No reservations found for this user</p>
        </div>
        @endif
    </div>
    
    <!-- Properties Tab -->
    <div class="tab-content" id="tab-properties">
        @if($properties->count() > 0)
            @foreach($properties as $property)
            <div class="property-mini-card">
                <div class="reservation-image">
                    @if($property->images->first())
                        <img src="{{ property_image_url($property->images->first()->image_url) }}" alt="Property" onerror="this.onerror=null; this.src='{{ asset('/images/property-placeholder.svg') }}'">
                    @else
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--gray-400);">
                            <i class="fas fa-home"></i>
                        </div>
                    @endif
                </div>
                <div class="reservation-info">
                    <h4>{{ $property->title }}</h4>
                    <p>{{ $property->map_address ?: 'No address' }}</p>
                    <p>{{ $property->bedroom_count }} bed · {{ $property->bathroom_count }} bath · {{ $property->guest_count }} guests</p>
                </div>
                <div class="reservation-dates">
                    <div class="price">${{ number_format($property->price, 2) }}/night</div>
                    <span class="badge badge-{{ $property->active ? 'success' : 'danger' }}">
                        {{ $property->active ? 'Active' : 'Inactive' }}
                    </span>
                    <a href="{{ route('admin.properties.details', $property->id) }}" class="btn btn-sm btn-secondary" style="margin-top: 8px;">
                        <i class="fas fa-eye"></i> View
                    </a>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state-small">
                <i class="fas fa-home"></i>
                <p>No properties owned by this user</p>
            </div>
        @endif
    </div>
    
    <!-- Transactions Tab -->
    <div class="tab-content" id="tab-transactions">
        @if($transactions->count() > 0)
            @foreach($transactions as $transaction)
            <div class="transaction-row">
                <div class="transaction-icon {{ $transaction->type == 1 ? 'credit' : 'debit' }}">
                    <i class="fas fa-{{ $transaction->type == 1 ? 'arrow-down' : 'arrow-up' }}"></i>
                </div>
                <div class="transaction-details">
                    <h5>{{ $transaction->type == 1 ? 'Payment Received' : 'Payment Made' }}</h5>
                    <p>
                        @if($transaction->reservation)
                            Reservation #{{ $transaction->reservation->confirmation_number ?? $transaction->reservation_id }}
                        @else
                            Transaction #{{ $transaction->id }}
                        @endif
                        · @php
                            $created = $transaction->getRawOriginal('created_at');
                            echo $created ? date('M d, Y', strtotime($created)) : 'N/A';
                        @endphp
                    </p>
                </div>
                <div class="transaction-amount {{ $transaction->type == 1 ? 'credit' : 'debit' }}">
                    {{ $transaction->type == 1 ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state-small">
                <i class="fas fa-receipt"></i>
                <p>No transactions found</p>
            </div>
        @endif
    </div>
    
    <!-- Notifications Tab -->
    <div class="tab-content" id="tab-notifications">
        @if($notifications->count() > 0)
            @foreach($notifications as $notification)
            <div class="notification-item">
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="notification-content">
                    <h5>{{ $notification->title ?? 'Notification' }}</h5>
                    <p>{{ $notification->message ?? 'No message' }}</p>
                </div>
                <div class="notification-time">
                    @php
                        $created = $notification->getRawOriginal('created_at');
                        echo $created ? date('M d, Y H:i', strtotime($created)) : 'N/A';
                    @endphp
                    @if($notification->read)
                        <span class="badge badge-success" style="margin-left: 8px;">Read</span>
                    @else
                        <span class="badge badge-warning" style="margin-left: 8px;">Unread</span>
                    @endif
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state-small">
                <i class="fas fa-bell-slash"></i>
                <p>No notifications found</p>
            </div>
        @endif
    </div>
    
    <!-- Activity Tab -->
    <div class="tab-content" id="tab-activity">
        @if($activities->count() > 0)
            @foreach($activities as $activity)
            <div class="activity-item">
                <div class="activity-dot"></div>
                <div class="activity-content">
                    <p>
                        Activity #{{ $activity->activity }}
                        @if($activity->reservation)
                            on Reservation #{{ $activity->reservation->confirmation_number ?? $activity->reservation_id }}
                        @endif
                    </p>
                    @if($activity->notes)
                        <p style="color: var(--gray-500); font-size: 12px;">{{ $activity->notes }}</p>
                    @endif
                    <div class="activity-time">
                        @php
                            $created = $activity->getRawOriginal('created_at');
                            echo $created ? date('M d, Y H:i', strtotime($created)) : 'N/A';
                        @endphp
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state-small">
                <i class="fas fa-history"></i>
                <p>No activity recorded</p>
            </div>
        @endif
    </div>
    
    <!-- Billing Tab -->
    <div class="tab-content" id="tab-billing">
        <h3 class="section-title"><i class="fas fa-credit-card"></i> Payment Methods</h3>
        @if($creditCards->count() > 0)
            @foreach($creditCards as $card)
            <div class="credit-card-item">
                <div class="card-brand-icon">
                    @if(strtolower($card->brand) == 'visa')
                        <span style="color: #1A1F71;">VISA</span>
                    @elseif(strtolower($card->brand) == 'mastercard')
                        <span style="color: #EB001B;">MC</span>
                    @elseif(strtolower($card->brand) == 'amex')
                        <span style="color: #006FCF;">AMEX</span>
                    @else
                        <span>{{ $card->brand }}</span>
                    @endif
                </div>
                <div class="card-details">
                    <div class="card-number">•••• •••• •••• {{ $card->last4 }}</div>
                    <div class="card-expiry">Expires {{ $card->exp_month }}/{{ $card->exp_year }}</div>
                </div>
                <span class="badge badge-{{ $card->verified ? 'success' : 'warning' }}">
                    {{ $card->verified ? 'Verified' : 'Unverified' }}
                </span>
            </div>
            @endforeach
        @else
            <div class="empty-state-small">
                <i class="fas fa-credit-card"></i>
                <p>No payment methods on file</p>
            </div>
        @endif
        
        @if($feeConfig)
        <h3 class="section-title" style="margin-top: 32px;"><i class="fas fa-cog"></i> Fee Configuration</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Broker Fee</label>
                <span>{{ $feeConfig->broker_fee }}%</span>
            </div>
            <div class="info-item">
                <label>Service Fee</label>
                <span>{{ $feeConfig->service_fee }}%</span>
            </div>
            <div class="info-item">
                <label>Cleaning Fee</label>
                <span>${{ number_format($feeConfig->cleaning_fee, 2) }}</span>
            </div>
            <div class="info-item">
                <label>Tax Rate</label>
                <span>{{ $feeConfig->tax_rate }}%</span>
            </div>
            <div class="info-item">
                <label>Stripe Account</label>
                <span class="badge badge-{{ $feeConfig->stripe_account_completed ? 'success' : 'warning' }}">
                    {{ $feeConfig->stripe_account_completed ? 'Connected' : 'Not Connected' }}
                </span>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Info Tab -->
    <div class="tab-content" id="tab-info">
        <h3 class="section-title"><i class="fas fa-user"></i> Personal Information</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>First Name</label>
                <span>{{ $user->first_name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <label>Last Name</label>
                <span>{{ $user->last_name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <label>Email</label>
                <span>{{ $user->email }}</span>
            </div>
            <div class="info-item">
                <label>Phone Number</label>
                <span>{{ $user->phone_number ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <label>Role</label>
                <span>{{ ucfirst($user->role_id) }}</span>
            </div>
            <div class="info-item">
                <label>PIN</label>
                <span>{{ $user->pin ?? 'N/A' }}</span>
            </div>
        </div>
        
        <h3 class="section-title" style="margin-top: 32px;"><i class="fas fa-map-marker-alt"></i> Address</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Address Line 1</label>
                <span>{{ $user->address_1 ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <label>Address Line 2</label>
                <span>{{ $user->address_2 ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <label>City</label>
                <span>{{ $user->city ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <label>State</label>
                <span>{{ $user->state ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <label>Zipcode</label>
                <span>{{ $user->zipcode ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <label>Country</label>
                <span>{{ $user->country ?? 'N/A' }}</span>
            </div>
        </div>
        
        <h3 class="section-title" style="margin-top: 32px;"><i class="fas fa-info-circle"></i> Account Details</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Account Status</label>
                <span class="badge badge-{{ ($user->active && $user->activated) ? 'success' : 'danger' }}">
                    {{ ($user->active && $user->activated) ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="info-item">
                <label>Stripe Customer ID</label>
                <span>{{ $user->stripe_customer_id ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <label>Broker Cut</label>
                <span>{{ $user->broker_cut ? $user->broker_cut . '%' : 'N/A' }}</span>
            </div>
            <div class="info-item">
                <label>Commission</label>
                <span>{{ $user->commission ? $user->commission . '%' : 'N/A' }}</span>
            </div>
            <div class="info-item">
                <label>Member Since</label>
                <span>
                    @php
                        $created = $user->getRawOriginal('created_at');
                        echo $created ? date('F d, Y', strtotime($created)) : 'N/A';
                    @endphp
                </span>
            </div>
            <div class="info-item">
                <label>Last Updated</label>
                <span>
                    @php
                        $updated = $user->getRawOriginal('updated_at');
                        echo $updated ? date('F d, Y', strtotime($updated)) : 'N/A';
                    @endphp
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function switchTab(tabId) {
    // Remove active from all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    // Add active to clicked tab
    event.target.closest('.tab-btn').classList.add('active');
    document.getElementById('tab-' + tabId).classList.add('active');
}

function sendNotification(userId) {
    const title = prompt('Notification Title:');
    if (!title) return;
    
    const message = prompt('Notification Message:');
    if (!message) return;
    
    // Send notification via API
    fetch('/admin/users/' + userId + '/notify', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ title, message })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Notification sent successfully!');
        } else {
            alert('Failed to send notification');
        }
    })
    .catch(() => alert('Error sending notification'));
}
</script>
@endsection
