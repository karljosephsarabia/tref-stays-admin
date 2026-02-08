{{-- Property Card Component --}}
{{-- 
    Usage: @include('tref.partials.property-card', ['property' => $property, 'currency' => 'USD'])
    
    Expected $property object with:
    - id, title, property_type, city, state, country
    - max_guests, bedrooms, beds, bathrooms
    - price_per_night, currency
    - main_image (or images array)
--}}

@php
    $imageUrl = $property->main_image ?? ($property->images[0] ?? asset('images/hero-bg.jpg'));
    $currencySymbol = $currency ?? '$';
    $price = $property->price_per_night ?? 0;
@endphp

<div class="property-card">
    <a href="{{ url('/search/' . $property->id . '/view') }}" style="text-decoration: none; color: inherit;">
        <div class="property-card-image">
            <img src="{{ $imageUrl }}" alt="{{ $property->title ?? 'Property' }}">
            
            {{-- Price Badge --}}
            <div class="property-card-badge">
                {{ $currencySymbol }}{{ number_format($price) }}/night
            </div>
            
            {{-- Heart Button --}}
            <button type="button" class="property-card-heart" onclick="event.preventDefault(); toggleFavorite({{ $property->id }})">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
            </button>
        </div>
        
        <div class="property-card-content">
            {{-- Property Type --}}
            <div class="property-card-type">
                {{ ucfirst($property->property_type ?? 'Property') }}
            </div>
            
            {{-- Title --}}
            <h3 class="property-card-title">
                {{ $property->title ?? 'Beautiful Property' }}
            </h3>
            
            {{-- Location --}}
            <div class="property-card-location">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                {{ $property->city ?? '' }}{{ $property->city && $property->state ? ', ' : '' }}{{ $property->state ?? '' }}
            </div>
            
            {{-- Features --}}
            <div class="property-card-features">
                @if(isset($property->max_guests))
                <div class="property-card-feature">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    {{ $property->max_guests }} guests
                </div>
                @endif
                
                @if(isset($property->bedrooms))
                <div class="property-card-feature">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 4v16"/>
                        <path d="M2 8h18a2 2 0 0 1 2 2v10"/>
                        <path d="M2 17h20"/>
                        <path d="M6 8v9"/>
                    </svg>
                    {{ $property->bedrooms }} bed{{ $property->bedrooms > 1 ? 's' : '' }}
                </div>
                @endif
                
                @if(isset($property->bathrooms))
                <div class="property-card-feature">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/>
                        <line x1="10" y1="5" x2="8" y2="7"/>
                        <line x1="2" y1="12" x2="22" y2="12"/>
                        <line x1="7" y1="19" x2="7" y2="21"/>
                        <line x1="17" y1="19" x2="17" y2="21"/>
                    </svg>
                    {{ $property->bathrooms }} bath{{ $property->bathrooms > 1 ? 's' : '' }}
                </div>
                @endif
            </div>
        </div>
    </a>
</div>
