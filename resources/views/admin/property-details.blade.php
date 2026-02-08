@extends('admin.layouts.app')

@section('title', 'Property Details - ' . $property->title)
@section('page-title', 'Property Details')

@section('styles')
<style>
    .property-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 16px;
        padding: 32px;
        color: white;
        margin-bottom: 24px;
    }
    
    .property-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .property-header .address {
        font-size: 16px;
        opacity: 0.9;
        margin-bottom: 16px;
    }
    
    .property-badges {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .property-badge {
        background: rgba(255,255,255,0.2);
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }
    
    .property-actions {
        margin-top: 20px;
        display: flex;
        gap: 12px;
    }
    
    .property-actions .btn {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
    }
    
    .property-actions .btn:hover {
        background: rgba(255,255,255,0.3);
    }
    
    .image-gallery {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 24px;
    }
    
    .image-gallery .gallery-item {
        aspect-ratio: 16/12;
        border-radius: 12px;
        overflow: hidden;
        background: var(--gray-200);
    }
    
    .image-gallery .gallery-item:first-child {
        grid-column: span 2;
        grid-row: span 2;
    }
    
    .image-gallery .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    
    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    .detail-item {
        background: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .detail-item i {
        font-size: 24px;
        color: var(--primary);
        margin-bottom: 8px;
    }
    
    .detail-item .value {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
    }
    
    .detail-item .label {
        font-size: 12px;
        color: var(--gray-500);
        margin-top: 4px;
    }
    
    .amenity-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
    }
    
    .amenity-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: var(--gray-100);
        border-radius: 8px;
    }
    
    .amenity-item i {
        color: var(--primary);
    }
    
    .reservation-mini-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: var(--gray-100);
        border-radius: 12px;
        margin-bottom: 12px;
    }
    
    .reservation-mini-card:hover {
        background: var(--gray-200);
    }
</style>
@endsection

@section('content')
<!-- Back Button -->
<div style="margin-bottom: 16px;">
    <a href="{{ route('admin.properties') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Properties
    </a>
</div>

<!-- Property Header -->
<div class="property-header">
    <h1>{{ $property->title }}</h1>
    <p class="address"><i class="fas fa-map-marker-alt"></i> {{ $property->map_address ?: 'No address set' }}</p>
    <div class="property-badges">
        <span class="property-badge"><i class="fas fa-users"></i> {{ $property->guest_count ?? 0 }} Guests</span>
        <span class="property-badge"><i class="fas fa-bed"></i> {{ $property->bedroom_count ?? 0 }} Bedrooms</span>
        <span class="property-badge"><i class="fas fa-bath"></i> {{ $property->bathroom_count ?? 0 }} Bathrooms</span>
        <span class="property-badge {{ $property->active ? 'active' : '' }}" style="background: {{ $property->active ? 'rgba(16,185,129,0.3)' : 'rgba(239,68,68,0.3)' }};">
            {{ $property->active ? 'Active' : 'Inactive' }}
        </span>
    </div>
    <div class="property-actions">
        <a href="{{ route('admin.properties') }}?edit={{ $property->id }}" class="btn btn-secondary">
            <i class="fas fa-edit"></i> Edit Property
        </a>
        <button class="btn btn-secondary" onclick="togglePropertyStatus({{ $property->id }})">
            <i class="fas fa-power-off"></i> {{ $property->active ? 'Deactivate' : 'Activate' }}
        </button>
    </div>
</div>

<!-- Image Gallery -->
@if($property->images && $property->images->count() > 0)
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-images"></i> Property Images</h3>
    </div>
    <div class="card-body">
        <div class="image-gallery">
            @foreach($property->images->take(5) as $image)
            <div class="gallery-item">
                <img src="{{ property_image_url($image->image_url) }}" alt="Property image" onerror="this.onerror=null; this.src='{{ asset('/images/property-placeholder.svg') }}'">
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Quick Stats -->
<div class="detail-grid">
    <div class="detail-item">
        <i class="fas fa-dollar-sign"></i>
        <div class="value">${{ number_format($property->price ?? 0, 2) }}</div>
        <div class="label">Per Night</div>
    </div>
    <div class="detail-item">
        <i class="fas fa-calendar-check"></i>
        <div class="value">{{ $property->reservations->count() }}</div>
        <div class="label">Total Reservations</div>
    </div>
    <div class="detail-item">
        <i class="fas fa-chart-line"></i>
        <div class="value">${{ number_format($property->reservations->sum('total_price'), 2) }}</div>
        <div class="label">Total Revenue</div>
    </div>
    <div class="detail-item">
        <i class="fas fa-user"></i>
        <div class="value">{{ $property->owner->first_name ?? 'N/A' }}</div>
        <div class="label">Owner</div>
    </div>
</div>

<div class="grid-2" style="margin-bottom: 24px;">
    <!-- Property Details -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-info-circle"></i> Property Information</h3>
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <label>Title</label>
                    <span>{{ $property->title }}</span>
                </div>
                <div class="info-item">
                    <label>Property Type</label>
                    <span>{{ $property->property_type ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Street</label>
                    <span>{{ $property->house_number }} {{ $property->street_name }}</span>
                </div>
                <div class="info-item">
                    <label>City</label>
                    <span>{{ $property->city ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>State</label>
                    <span>{{ $property->state ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Zipcode</label>
                    <span>{{ $property->zipcode ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Country</label>
                    <span>{{ $property->country ?? 'US' }}</span>
                </div>
                <div class="info-item">
                    <label>Cleaning Fee</label>
                    <span>${{ number_format($property->cleaning_fee ?? 0, 2) }}</span>
                </div>
                <div class="info-item">
                    <label>Check-in Time</label>
                    <span>{{ $property->checkin_time ?? '3:00 PM' }}</span>
                </div>
                <div class="info-item">
                    <label>Check-out Time</label>
                    <span>{{ $property->checkout_time ?? '11:00 AM' }}</span>
                </div>
            </div>
            
            @if($property->additional_information)
            <div style="margin-top: 20px;">
                <label style="font-size: 12px; color: var(--gray-500); display: block; margin-bottom: 8px;">Description</label>
                <p style="color: var(--gray-600);">{{ $property->additional_information }}</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Owner Information -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user"></i> Owner Information</h3>
        </div>
        <div class="card-body">
            @if($property->owner)
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                <div class="user-avatar" style="width: 60px; height: 60px; font-size: 24px;">
                    {{ strtoupper(substr($property->owner->first_name ?? 'O', 0, 1)) }}
                </div>
                <div>
                    <h4 style="margin-bottom: 4px;">{{ $property->owner->first_name }} {{ $property->owner->last_name }}</h4>
                    <p style="color: var(--gray-500); margin: 0;">{{ $property->owner->email }}</p>
                </div>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Phone</label>
                    <span>{{ $property->owner->phone_number ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Role</label>
                    <span>{{ ucfirst($property->owner->role_id) }}</span>
                </div>
            </div>
            <a href="{{ route('admin.users.details', $property->owner->id) }}" class="btn btn-secondary" style="margin-top: 16px;">
                <i class="fas fa-user"></i> View Owner Profile
            </a>
            @else
            <p style="color: var(--gray-500);">No owner assigned</p>
            @endif
        </div>
    </div>
</div>

<!-- Amenities -->
@php
    $amenities = json_decode($property->amenities ?? '[]', true);
    $kosherInfo = json_decode($property->kosher_info ?? '{}', true);
@endphp

@if(is_array($amenities) && count($amenities) > 0)
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-star"></i> Amenities</h3>
    </div>
    <div class="card-body">
        <div class="amenity-list">
            @foreach($amenities as $amenity)
            <div class="amenity-item">
                <i class="fas fa-check-circle"></i>
                <span>{{ ucwords(str_replace('_', ' ', $amenity)) }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Kosher Information -->
@if(is_array($kosherInfo) && (($kosherInfo['kosher_kitchen'] ?? false) || ($kosherInfo['shabbos_friendly'] ?? false)))
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-utensils"></i> Kosher Information</h3>
    </div>
    <div class="card-body">
        <div class="info-grid">
            <div class="info-item">
                <label>Kosher Kitchen</label>
                <span class="badge badge-{{ ($kosherInfo['kosher_kitchen'] ?? false) ? 'success' : 'secondary' }}">
                    {{ ($kosherInfo['kosher_kitchen'] ?? false) ? 'Yes' : 'No' }}
                </span>
            </div>
            <div class="info-item">
                <label>Shabbos Friendly</label>
                <span class="badge badge-{{ ($kosherInfo['shabbos_friendly'] ?? false) ? 'success' : 'secondary' }}">
                    {{ ($kosherInfo['shabbos_friendly'] ?? false) ? 'Yes' : 'No' }}
                </span>
            </div>
            @if(isset($kosherInfo['nearby_shul']) && $kosherInfo['nearby_shul']['name'])
            <div class="info-item">
                <label>Nearby Shul</label>
                <span>{{ $kosherInfo['nearby_shul']['name'] }} ({{ $kosherInfo['nearby_shul']['distance'] }})</span>
            </div>
            @endif
            @if(isset($kosherInfo['nearby_shop']) && $kosherInfo['nearby_shop']['name'])
            <div class="info-item">
                <label>Kosher Shop</label>
                <span>{{ $kosherInfo['nearby_shop']['name'] }} ({{ $kosherInfo['nearby_shop']['distance'] }})</span>
            </div>
            @endif
            @if(isset($kosherInfo['nearby_mikva']) && $kosherInfo['nearby_mikva']['name'])
            <div class="info-item">
                <label>Nearby Mikva</label>
                <span>{{ $kosherInfo['nearby_mikva']['name'] }} ({{ $kosherInfo['nearby_mikva']['distance'] }})</span>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

<!-- Recent Reservations -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Reservations ({{ $property->reservations->count() }})</h3>
    </div>
    <div class="card-body">
        @if($property->reservations->count() > 0)
            @foreach($property->reservations->take(10) as $reservation)
            <div class="reservation-mini-card">
                <div class="user-avatar-sm" style="background: var(--info);">
                    {{ strtoupper(substr($reservation->customer->first_name ?? 'G', 0, 1)) }}
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: 500;">{{ $reservation->customer->first_name ?? 'Guest' }} {{ $reservation->customer->last_name ?? '' }}</div>
                    <div style="font-size: 12px; color: var(--gray-500);">
                        @php
                            $start = $reservation->getRawOriginal('date_start');
                            $end = $reservation->getRawOriginal('date_end');
                        @endphp
                        {{ $start ? date('M d', strtotime($start)) : 'N/A' }} - {{ $end ? date('M d, Y', strtotime($end)) : 'N/A' }}
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-weight: 600; font-size: 16px;">${{ number_format($reservation->total_price, 2) }}</div>
                    @php
                        $statusColors = [1 => 'warning', 2 => 'success', 3 => 'danger', 4 => 'info'];
                        $statusLabels = [1 => 'Pending', 2 => 'Confirmed', 3 => 'Cancelled', 4 => 'Completed'];
                    @endphp
                    <span class="badge badge-{{ $statusColors[$reservation->status] ?? 'secondary' }}">
                        {{ $statusLabels[$reservation->status] ?? 'Unknown' }}
                    </span>
                </div>
            </div>
            @endforeach
        @else
            <div style="text-align: center; padding: 40px; color: var(--gray-400);">
                <i class="fas fa-calendar-times" style="font-size: 40px; margin-bottom: 12px;"></i>
                <p>No reservations yet</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePropertyStatus(propertyId) {
    if (!confirm('Are you sure you want to change this property\'s status?')) return;
    
    fetch(`/admin/properties/${propertyId}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Failed to update status', 'error');
        }
    })
    .catch(() => showToast('Error updating status', 'error'));
}
</script>
@endsection
