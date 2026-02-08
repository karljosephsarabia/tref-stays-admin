@extends('admin.layouts.app')

@section('title', 'Properties Management')
@section('page-title', 'Properties Management')

@section('content')
<!-- Filters & Actions -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.properties') }}" class="filters">
            <div class="filter-group">
                <input type="text" name="search" class="form-control" placeholder="Search properties..." value="{{ request('search') }}" style="width: 250px;">
            </div>
            <div class="filter-group">
                <select name="owner_id" class="form-control">
                    <option value="">All Owners</option>
                    @foreach($owners as $owner)
                        <option value="{{ $owner->id }}" {{ request('owner_id') == $owner->id ? 'selected' : '' }}>
                            {{ $owner->first_name }} {{ $owner->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.properties') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Clear
            </a>
            <div style="margin-left: auto;">
                <a href="{{ route('admin.properties.new') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Property
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon secondary">
            <i class="fas fa-building"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Properties</div>
            <div class="stat-value">{{ $properties->total() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Active Listings</div>
            <div class="stat-value">{{ $properties->where('active', true)->count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Avg. Price/Night</div>
            <div class="stat-value">${{ number_format($properties->avg('price'), 0) }}</div>
        </div>
    </div>
</div>

<!-- Properties Grid -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Properties</h3>
        <div>
            <button class="btn btn-secondary btn-sm" onclick="toggleView('grid')">
                <i class="fas fa-th"></i>
            </button>
            <button class="btn btn-secondary btn-sm" onclick="toggleView('list')">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Grid View -->
        <div id="gridView" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
            @forelse($properties as $property)
            <div class="property-card" style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)';" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)';">
                <div style="position: relative; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    @if($property->images->count() > 0)
                        <img src="{{ property_image_url($property->images->first()->image_url) }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('/images/property-placeholder.svg') }}'">
                    @else
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                            <i class="fas fa-home"></i>
                        </div>
                    @endif
                    <div style="position: absolute; top: 12px; right: 12px; display: flex; flex-direction: column; gap: 8px; align-items: flex-end;">
                        <span class="badge {{ $property->active ? 'badge-success' : 'badge-danger' }}">
                            {{ $property->active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($property->show_on_homepage)
                            <span class="badge" style="background: {{ $property->highlight_color ?? '#FF385C' }}; color: #fff;">
                                Homepage
                                @if($property->homepage_order)
                                    · #{{ $property->homepage_order }}
                                @endif
                            </span>
                        @endif
                        @if($property->featured_badge)
                            <span class="badge" style="background: rgba(255,255,255,0.9); color: {{ $property->highlight_color ?? '#FF385C' }}; border: 1px solid {{ $property->highlight_color ?? '#FF385C' }};">
                                {{ $property->featured_badge }}
                            </span>
                        @endif
                    </div>
                    <div style="position: absolute; bottom: 12px; left: 12px; background: white; padding: 6px 12px; border-radius: 8px; font-weight: 600;">
                        ${{ number_format($property->price) }}/night
                    </div>
                </div>
                <div style="padding: 16px;">
                    <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $property->title }}
                    </h4>
                    <p style="font-size: 13px; color: var(--gray-500); margin-bottom: 12px;">
                        <i class="fas fa-map-marker-alt"></i> {{ $property->map_address ?? 'No address' }}
                    </p>
                    <div style="display: flex; gap: 16px; font-size: 13px; color: var(--gray-500); margin-bottom: 16px;">
                        <span><i class="fas fa-user"></i> {{ $property->guest_count }} guests</span>
                        <span><i class="fas fa-bed"></i> {{ $property->bedroom_count }} beds</span>
                        <span><i class="fas fa-bath"></i> {{ $property->bathroom_count }} baths</span>
                    </div>
                    @if($property->hero_title || $property->spotlight_message)
                        <div style="background: {{ ($property->highlight_color ?? '#FF385C') }}10; border: 1px solid {{ ($property->highlight_color ?? '#FF385C') }}33; border-radius: 12px; padding: 12px 16px; margin-bottom: 16px;">
                            @if($property->hero_title)
                                <div style="font-weight: 600; color: {{ $property->highlight_color ?? '#FF385C' }};">{{ $property->hero_title }}</div>
                                <div style="font-size: 13px; color: var(--gray-500);">{{ $property->hero_subtitle }}</div>
                            @endif
                            @if($property->spotlight_message)
                                <p style="font-size: 13px; color: var(--gray-600); margin-top: 8px;">{{ $property->spotlight_message }}</p>
                            @endif
                            @if($property->hero_cta_text && $property->hero_cta_url)
                                <a href="{{ $property->hero_cta_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; margin-top: 10px; font-size: 12px; text-transform: uppercase; font-weight: 600; color: {{ $property->highlight_color ?? '#FF385C' }};">
                                    {{ $property->hero_cta_text }} <i class="fas fa-arrow-up-right-from-square"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--gray-200); padding-top: 12px;">
                        <div style="font-size: 13px; color: var(--gray-500);">
                            <i class="fas fa-user-circle"></i> {{ $property->owner->first_name ?? 'N/A' }} {{ $property->owner->last_name ?? '' }}
                        </div>
                        <div class="actions">
                            <button class="btn btn-secondary btn-icon btn-sm" onclick="editProperty({{ $property->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-secondary btn-icon btn-sm" onclick="toggleProperty({{ $property->id }})" title="Toggle Status">
                                <i class="fas fa-power-off"></i>
                            </button>
                            <button class="btn btn-danger btn-icon btn-sm" onclick="deleteProperty({{ $property->id }})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1;">
                <div class="empty-state">
                    <i class="fas fa-building"></i>
                    <h3>No Properties Found</h3>
                    <p>Try adjusting your filters or add a new property.</p>
                </div>
            </div>
            @endforelse
        </div>
        
        <!-- List View (hidden by default) -->
        <div id="listView" style="display: none;">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th>Owner</th>
                            <th>Price/Night</th>
                            <th>Capacity</th>
                            <th>Promo</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($properties as $property)
                        <tr>
                            <td>
                                <div class="property-cell">
                                    <div class="property-thumb" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                                    <div>
                                        <div style="font-weight: 500;">{{ $property->title }}</div>
                                        <div style="font-size: 12px; color: var(--gray-500);">{{ $property->map_address ?? 'No address' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $property->owner->first_name ?? 'N/A' }} {{ $property->owner->last_name ?? '' }}</td>
                            <td><strong>${{ number_format($property->price, 2) }}</strong></td>
                            <td>
                                <span style="font-size: 13px; color: var(--gray-500);">
                                    <i class="fas fa-user"></i> {{ $property->guest_count }} · 
                                    <i class="fas fa-bed"></i> {{ $property->bedroom_count }} · 
                                    <i class="fas fa-bath"></i> {{ $property->bathroom_count }}
                                </span>
                            </td>
                            <td>
                                @if($property->show_on_homepage)
                                    <span class="badge" style="background: {{ $property->highlight_color ?? '#FF385C' }}; color: #fff;">Homepage</span>
                                @endif
                                @if($property->featured_badge)
                                    <span class="badge" style="border: 1px solid {{ $property->highlight_color ?? '#FF385C' }}; color: {{ $property->highlight_color ?? '#FF385C' }}; background: transparent;">{{ $property->featured_badge }}</span>
                                @endif
                                @if($property->allow_instant_booking)
                                    <span class="badge badge-info">Instant</span>
                                @endif
                                @if($property->is_luxury_tier)
                                    <span class="badge badge-warning">Luxury</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $property->active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $property->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <button class="btn btn-secondary btn-icon" onclick="toggleProperty({{ $property->id }})">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                    <button class="btn btn-secondary btn-icon" onclick="editProperty({{ $property->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-icon" onclick="deleteProperty({{ $property->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($properties->hasPages())
<div class="pagination">
    {{ $properties->withQueryString()->links() }}
</div>
@endif

<!-- Create Property Modal -->
<div class="modal-backdrop" id="createModal">
    <div class="modal" style="max-width: 700px;">
        <div class="modal-header">
            <h3 class="modal-title">Add New Property</h3>
            <div class="modal-close" onclick="closeModal('createModal')">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <form id="createPropertyForm" onsubmit="submitCreateProperty(event)">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Property Title *</label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g., Luxury Beachfront Villa">
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Owner *</label>
                        <select name="owner_id" class="form-control" required>
                            <option value="">Select Owner</option>
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}">{{ $owner->first_name }} {{ $owner->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price per Night *</label>
                        <input type="number" name="price" class="form-control" min="0" step="0.01" required>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Street Name</label>
                        <input type="text" name="street_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">House Number</label>
                        <input type="text" name="house_number" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Full Address</label>
                    <input type="text" name="map_address" class="form-control" placeholder="e.g., 123 Ocean Drive, Miami Beach, FL 33139">
                </div>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Guests</label>
                        <input type="number" name="guest_count" class="form-control" min="1" value="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bedrooms</label>
                        <input type="number" name="bedroom_count" class="form-control" min="0" value="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Beds</label>
                        <input type="number" name="bed_count" class="form-control" min="0" value="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bathrooms</label>
                        <input type="number" name="bathroom_count" class="form-control" min="0" value="1">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Additional Information</label>
                    <textarea name="additional_information" class="form-control" rows="3" placeholder="Property description, amenities, rules, etc."></textarea>
                </div>
                <div style="margin: 32px 0 12px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-500);">Visibility & Promotion</div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Homepage Spotlight</label>
                        <label style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: var(--gray-600);">
                            <input type="checkbox" name="show_on_homepage" style="width: 18px; height: 18px;"> Show this property on the hero carousel
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Homepage Order</label>
                        <input type="number" name="homepage_order" class="form-control" min="1" placeholder="1 = first">
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Featured Badge Text</label>
                        <input type="text" name="featured_badge" class="form-control" placeholder="e.g., New, Luxe, Staff Pick">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Highlight / Accent Color</label>
                        <input type="color" name="highlight_color" class="form-control" value="#FF385C" style="height: 48px; padding: 6px;">
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Instant Booking</label>
                        <label style="display: flex; gap: 10px; align-items: center; font-size: 13px; color: var(--gray-600);">
                            <input type="checkbox" name="allow_instant_booking" style="width: 18px; height: 18px;"> Allow guests to confirm without approval
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Luxury Tier</label>
                        <label style="display: flex; gap: 10px; align-items: center; font-size: 13px; color: var(--gray-600);">
                            <input type="checkbox" name="is_luxury_tier" style="width: 18px; height: 18px;"> Flag as premium / Black Card stay
                        </label>
                    </div>
                </div>

                <div style="margin: 32px 0 12px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-500);">Hero Banner & Storytelling</div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Hero Title</label>
                        <input type="text" name="hero_title" class="form-control" placeholder="Headline for homepage banner">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hero Subtitle</label>
                        <input type="text" name="hero_subtitle" class="form-control" placeholder="Support copy or vibe description">
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">CTA Label</label>
                        <input type="text" name="hero_cta_text" class="form-control" placeholder="Book now, Explore gallery, etc.">
                    </div>
                    <div class="form-group">
                        <label class="form-label">CTA URL</label>
                        <input type="url" name="hero_cta_url" class="form-control" placeholder="https://">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Banner Image URL</label>
                    <input type="text" name="banner_image_url" class="form-control" placeholder="Paste a hosted hero/banner image">
                    <small style="color: var(--gray-500);">Recommended 2400×1200px JPG/WEBP for crisp hero renderings.</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Spotlight Message</label>
                    <textarea name="spotlight_message" class="form-control" rows="2" placeholder="Describe unique story, concierge perks, chef add-ons..."></textarea>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Layout Mood</label>
                        <select name="display_layout" class="form-control">
                            <option value="gallery">Modern Gallery</option>
                            <option value="immersive">Immersive Story</option>
                            <option value="minimal">Minimal Editorial</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">SEO Title / Card Headline</label>
                        <input type="text" name="seo_title" class="form-control" placeholder="Luxury Hudson Valley Treehouse">
                    </div>
                </div>

                <div style="margin: 32px 0 12px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-500);">SEO & Metadata</div>
                <div class="form-group">
                    <label class="form-label">SEO Description</label>
                    <textarea name="seo_description" class="form-control" rows="2" placeholder="Shown on Google snippets or share cards."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">SEO Keywords / Tags</label>
                    <textarea name="seo_keywords" class="form-control" rows="2" placeholder="villa, hamptons, private-chef, infinity-pool"></textarea>
                </div>

                <div style="margin: 32px 0 12px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-500);">Design Overrides</div>
                <div class="form-group">
                    <label class="form-label">Custom CSS</label>
                    <textarea name="custom_css" class="form-control" rows="3" placeholder="Target .property-hero to inject gradients, fonts, etc."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Custom JS</label>
                    <textarea name="custom_js" class="form-control" rows="3" placeholder="Optional analytics or animation hooks."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Property
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Property Modal -->
<div class="modal-backdrop" id="editModal">
    <div class="modal" style="max-width: 700px;">
        <div class="modal-header">
            <h3 class="modal-title">Edit Property</h3>
            <div class="modal-close" onclick="closeModal('editModal')">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <form id="editPropertyForm" onsubmit="submitEditProperty(event)">
            <input type="hidden" name="property_id" id="edit_property_id">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Property Title *</label>
                    <input type="text" name="title" id="edit_title" class="form-control" required>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Owner *</label>
                        <select name="owner_id" id="edit_owner_id" class="form-control" required>
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}">{{ $owner->first_name }} {{ $owner->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price per Night *</label>
                        <input type="number" name="price" id="edit_price" class="form-control" min="0" step="0.01" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Full Address</label>
                    <input type="text" name="map_address" id="edit_map_address" class="form-control">
                </div>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Guests</label>
                        <input type="number" name="guest_count" id="edit_guest_count" class="form-control" min="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bedrooms</label>
                        <input type="number" name="bedroom_count" id="edit_bedroom_count" class="form-control" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Beds</label>
                        <input type="number" name="bed_count" id="edit_bed_count" class="form-control" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bathrooms</label>
                        <input type="number" name="bathroom_count" id="edit_bathroom_count" class="form-control" min="0">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Additional Information</label>
                    <textarea name="additional_information" id="edit_additional_information" class="form-control" rows="3"></textarea>
                </div>
                <div style="margin: 32px 0 12px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-500);">Visibility & Promotion</div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Homepage Spotlight</label>
                        <label style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: var(--gray-600);">
                            <input type="checkbox" name="show_on_homepage" id="edit_show_on_homepage" style="width: 18px; height: 18px;"> Show this property on the hero carousel
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Homepage Order</label>
                        <input type="number" name="homepage_order" id="edit_homepage_order" class="form-control" min="1">
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Featured Badge Text</label>
                        <input type="text" name="featured_badge" id="edit_featured_badge" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Highlight / Accent Color</label>
                        <input type="color" name="highlight_color" id="edit_highlight_color" class="form-control" style="height: 48px; padding: 6px;">
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Instant Booking</label>
                        <label style="display: flex; gap: 10px; align-items: center; font-size: 13px; color: var(--gray-600);">
                            <input type="checkbox" name="allow_instant_booking" id="edit_allow_instant_booking" style="width: 18px; height: 18px;"> Allow guests to confirm without approval
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Luxury Tier</label>
                        <label style="display: flex; gap: 10px; align-items: center; font-size: 13px; color: var(--gray-600);">
                            <input type="checkbox" name="is_luxury_tier" id="edit_is_luxury_tier" style="width: 18px; height: 18px;"> Flag as premium stay
                        </label>
                    </div>
                </div>

                <div style="margin: 32px 0 12px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-500);">Hero Banner & Storytelling</div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Hero Title</label>
                        <input type="text" name="hero_title" id="edit_hero_title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hero Subtitle</label>
                        <input type="text" name="hero_subtitle" id="edit_hero_subtitle" class="form-control">
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">CTA Label</label>
                        <input type="text" name="hero_cta_text" id="edit_hero_cta_text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">CTA URL</label>
                        <input type="url" name="hero_cta_url" id="edit_hero_cta_url" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Banner Image URL</label>
                    <input type="text" name="banner_image_url" id="edit_banner_image_url" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Spotlight Message</label>
                    <textarea name="spotlight_message" id="edit_spotlight_message" class="form-control" rows="2"></textarea>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Layout Mood</label>
                        <select name="display_layout" id="edit_display_layout" class="form-control">
                            <option value="gallery">Modern Gallery</option>
                            <option value="immersive">Immersive Story</option>
                            <option value="minimal">Minimal Editorial</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">SEO Title / Card Headline</label>
                        <input type="text" name="seo_title" id="edit_seo_title" class="form-control">
                    </div>
                </div>

                <div style="margin: 32px 0 12px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-500);">SEO & Metadata</div>
                <div class="form-group">
                    <label class="form-label">SEO Description</label>
                    <textarea name="seo_description" id="edit_seo_description" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">SEO Keywords / Tags</label>
                    <textarea name="seo_keywords" id="edit_seo_keywords" class="form-control" rows="2"></textarea>
                </div>

                <div style="margin: 32px 0 12px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-500);">Design Overrides</div>
                <div class="form-group">
                    <label class="form-label">Custom CSS</label>
                    <textarea name="custom_css" id="edit_custom_css" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Custom JS</label>
                    <textarea name="custom_js" id="edit_custom_js" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const propertiesData = [
        @foreach($properties as $property)
        {
            id: {{ $property->id }},
            title: {!! json_encode($property->title) !!},
            owner_id: {{ $property->owner_id ?? 'null' }},
            price: {{ $property->price ?? 0 }},
            guest_count: {{ $property->guest_count ?? 1 }},
            bedroom_count: {{ $property->bedroom_count ?? 1 }},
            bed_count: {{ $property->bed_count ?? 1 }},
            bathroom_count: {{ $property->bathroom_count ?? 1 }},
            map_address: {!! json_encode($property->map_address) !!},
            additional_information: {!! json_encode($property->additional_information) !!},
            show_on_homepage: {{ $property->show_on_homepage ? 'true' : 'false' }},
            homepage_order: {{ $property->homepage_order ?? 'null' }},
            featured_badge: {!! json_encode($property->featured_badge) !!},
            highlight_color: {!! json_encode($property->highlight_color ?? '#FF385C') !!},
            hero_title: {!! json_encode($property->hero_title) !!},
            hero_subtitle: {!! json_encode($property->hero_subtitle) !!},
            hero_cta_text: {!! json_encode($property->hero_cta_text) !!},
            hero_cta_url: {!! json_encode($property->hero_cta_url) !!},
            banner_image_url: {!! json_encode($property->banner_image_url) !!},
            spotlight_message: {!! json_encode($property->spotlight_message) !!},
            display_layout: {!! json_encode($property->display_layout ?? 'gallery') !!},
            allow_instant_booking: {{ $property->allow_instant_booking ? 'true' : 'false' }},
            is_luxury_tier: {{ $property->is_luxury_tier ? 'true' : 'false' }},
            seo_title: {!! json_encode($property->seo_title) !!},
            seo_description: {!! json_encode($property->seo_description) !!},
            seo_keywords: {!! json_encode($property->seo_keywords) !!},
            custom_css: {!! json_encode($property->custom_css) !!},
            custom_js: {!! json_encode($property->custom_js) !!}
        },
        @endforeach
    ];

    function toggleView(view) {
        document.getElementById('gridView').style.display = view === 'grid' ? 'grid' : 'none';
        document.getElementById('listView').style.display = view === 'list' ? 'block' : 'none';
    }

    function openCreateModal() {
        document.getElementById('createPropertyForm').reset();
        document.querySelector('#createPropertyForm input[name="highlight_color"]').value = '#FF385C';
        document.querySelector('#createPropertyForm select[name="display_layout"]').value = 'gallery';
        document.getElementById('createModal').classList.add('active');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }

    function editProperty(propertyId) {
        const property = propertiesData.find(p => p.id === propertyId);
        if (!property) return;

        document.getElementById('edit_property_id').value = property.id;
        document.getElementById('edit_title').value = property.title || '';
        document.getElementById('edit_owner_id').value = property.owner_id || '';
        document.getElementById('edit_price').value = property.price || '';
        document.getElementById('edit_map_address').value = property.map_address || '';
        document.getElementById('edit_guest_count').value = property.guest_count || 1;
        document.getElementById('edit_bedroom_count').value = property.bedroom_count || 1;
        document.getElementById('edit_bed_count').value = property.bed_count || 1;
        document.getElementById('edit_bathroom_count').value = property.bathroom_count || 1;
        document.getElementById('edit_additional_information').value = property.additional_information || '';

        document.getElementById('edit_show_on_homepage').checked = !!property.show_on_homepage;
        document.getElementById('edit_homepage_order').value = property.homepage_order ?? '';
        document.getElementById('edit_featured_badge').value = property.featured_badge || '';
        document.getElementById('edit_highlight_color').value = property.highlight_color || '#FF385C';
        document.getElementById('edit_allow_instant_booking').checked = !!property.allow_instant_booking;
        document.getElementById('edit_is_luxury_tier').checked = !!property.is_luxury_tier;

        document.getElementById('edit_hero_title').value = property.hero_title || '';
        document.getElementById('edit_hero_subtitle').value = property.hero_subtitle || '';
        document.getElementById('edit_hero_cta_text').value = property.hero_cta_text || '';
        document.getElementById('edit_hero_cta_url').value = property.hero_cta_url || '';
        document.getElementById('edit_banner_image_url').value = property.banner_image_url || '';
        document.getElementById('edit_spotlight_message').value = property.spotlight_message || '';
        document.getElementById('edit_display_layout').value = property.display_layout || 'gallery';

        document.getElementById('edit_seo_title').value = property.seo_title || '';
        document.getElementById('edit_seo_description').value = property.seo_description || '';
        document.getElementById('edit_seo_keywords').value = property.seo_keywords || '';

        document.getElementById('edit_custom_css').value = property.custom_css || '';
        document.getElementById('edit_custom_js').value = property.custom_js || '';

        document.getElementById('editModal').classList.add('active');
    }

    function serializeForm(form) {
        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        form.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            data[cb.name] = cb.checked;
        });
        return data;
    }

    async function submitCreateProperty(e) {
        e.preventDefault();
        const form = e.target;
        const data = serializeForm(form);

        try {
            const response = await fetch('{{ route("admin.properties.create") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                showToast('Property created successfully!', 'success');
                closeModal('createModal');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(result.message || 'Failed to create property', 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
        }
    }

    async function submitEditProperty(e) {
        e.preventDefault();
        const form = e.target;
        const data = serializeForm(form);
        const propertyId = data.property_id;

        try {
            const response = await fetch(`/admin/properties/${propertyId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                showToast('Property updated successfully!', 'success');
                closeModal('editModal');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(result.message || 'Failed to update property', 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
        }
    }
    
    async function toggleProperty(propertyId) {
        try {
            const response = await fetch(`/admin/properties/${propertyId}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Property status updated!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Failed to update status', 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
        }
    }
    
    async function deleteProperty(propertyId) {
        if (!confirm('Are you sure you want to delete this property? This action cannot be undone.')) {
            return;
        }
        
        try {
            const response = await fetch(`/admin/properties/${propertyId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Property deleted successfully!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(result.message || 'Failed to delete property', 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
        }
    }
</script>
@endsection
