<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit {{ $property->title }} - Tref Stays</title>
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
            max-width: 900px;
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
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            background: white;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }
        
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--tref-blue);
            box-shadow: 0 0 0 3px rgba(0,128,255,0.1);
        }
        
        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.9375rem;
            font-weight: 600;
            border-radius: 0.5rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: var(--tref-blue);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--tref-blue-hover);
        }
        
        .btn-outline {
            background: white;
            color: var(--text);
            border: 1px solid var(--border);
        }
        
        .btn-outline:hover {
            background: var(--bg-secondary);
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
        }
        
        /* Images Grid */
        .images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .image-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid var(--border);
        }
        
        .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .image-delete {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 1.75rem;
            height: 1.75rem;
            background: var(--danger);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s;
        }
        
        .image-item:hover .image-delete {
            opacity: 1;
        }
        
        .upload-zone {
            border: 2px dashed var(--border);
            border-radius: 0.75rem;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        
        .upload-zone:hover {
            border-color: var(--tref-blue);
        }
        
        .upload-zone svg {
            width: 3rem;
            height: 3rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }
        
        /* Checkboxes */
        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 0.75rem;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .checkbox-item input[type="checkbox"] {
            width: 1.125rem;
            height: 1.125rem;
            accent-color: var(--tref-blue);
        }
        
        .checkbox-item label {
            font-size: 0.875rem;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
            margin-top: 1rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: rgba(22,163,74,0.1);
            color: var(--success);
            border: 1px solid rgba(22,163,74,0.2);
        }
        
        .alert-danger {
            background: rgba(239,68,68,0.1);
            color: var(--danger);
            border: 1px solid rgba(239,68,68,0.2);
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
            <h1 class="page-title">Edit Property</h1>
            <p class="page-subtitle">Update your listing details</p>
        </div>
        
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form action="{{ route('owner.property.update', $property->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Basic Info -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Basic Information</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Property Title</label>
                        <input type="text" name="title" class="form-input" value="{{ old('title', $property->title) }}" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Property Type</label>
                            <select name="property_type" class="form-select" required>
                                <option value="1" {{ $property->property_type == 1 ? 'selected' : '' }}>Apartment</option>
                                <option value="2" {{ $property->property_type == 2 ? 'selected' : '' }}>House</option>
                                <option value="3" {{ $property->property_type == 3 ? 'selected' : '' }}>Condo</option>
                                <option value="4" {{ $property->property_type == 4 ? 'selected' : '' }}>Townhouse</option>
                                <option value="5" {{ $property->property_type == 5 ? 'selected' : '' }}>Villa</option>
                                <option value="6" {{ $property->property_type == 6 ? 'selected' : '' }}>Cabin</option>
                                <option value="7" {{ $property->property_type == 7 ? 'selected' : '' }}>Bungalow</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Currency</label>
                            <select name="currency" class="form-select" required>
                                <option value="USD" {{ ($property->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>🇺🇸 USD ($)</option>
                                <option value="GBP" {{ ($property->currency ?? 'USD') == 'GBP' ? 'selected' : '' }}>🇬🇧 GBP (£)</option>
                                <option value="EUR" {{ ($property->currency ?? 'USD') == 'EUR' ? 'selected' : '' }}>🇪🇺 EUR (€)</option>
                                <option value="CAD" {{ ($property->currency ?? 'USD') == 'CAD' ? 'selected' : '' }}>🇨🇦 CAD (CA$)</option>
                                <option value="ILS" {{ ($property->currency ?? 'USD') == 'ILS' ? 'selected' : '' }}>🇮🇱 ILS (₪)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Price Per Night</label>
                            <input type="number" name="price" class="form-input" value="{{ old('price', $property->price) }}" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Cleaning Fee</label>
                            <input type="number" name="cleaning_fee" class="form-input" value="{{ old('cleaning_fee', $property->cleaning_fee ?? 0) }}" step="0.01">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Bedrooms</label>
                            <input type="number" name="bedroom_count" class="form-input" value="{{ old('bedroom_count', $property->bedroom_count) }}" min="1" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bathrooms</label>
                            <input type="number" name="bathroom_count" class="form-input" value="{{ old('bathroom_count', $property->bathroom_count) }}" min="1" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Max Guests</label>
                        <input type="number" name="guest_count" class="form-input" value="{{ old('guest_count', $property->guest_count) }}" min="1" required style="max-width: 200px;">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="additional_information" class="form-textarea">{{ old('additional_information', $property->additional_information) }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Location -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Location</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Full Address</label>
                        <input type="text" name="map_address" class="form-input" value="{{ old('map_address', $property->map_address) }}" placeholder="Enter the full property address">
                    </div>
                </div>
            </div>
            
            <!-- Photos -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Photos</h2>
                </div>
                <div class="card-body">
                    @if($property->images->count() > 0)
                    <div class="images-grid">
                        @foreach($property->images as $image)
                        <div class="image-item" data-id="{{ $image->id }}">
                            <img src="{{ $image->image_url }}" alt="Property Image">
                            <button type="button" class="image-delete" onclick="deleteImage({{ $image->id }})">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    <div class="upload-zone" onclick="document.getElementById('images').click()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <p><strong>Click to upload</strong> or drag and drop</p>
                        <p style="font-size: 0.75rem; color: var(--text-muted);">PNG, JPG up to 10MB</p>
                    </div>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" style="display: none;">
                </div>
            </div>
            
            <!-- Amenities -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Amenities</h2>
                </div>
                <div class="card-body">
                    @php
                        $amenities = json_decode($property->amenities ?? '[]', true) ?: [];
                        $allAmenities = [
                            'wifi' => 'WiFi',
                            'parking' => 'Parking',
                            'pool' => 'Pool',
                            'gym' => 'Gym',
                            'ac' => 'Air Conditioning',
                            'heating' => 'Heating',
                            'washer' => 'Washer',
                            'dryer' => 'Dryer',
                            'kitchen' => 'Kitchen',
                            'tv' => 'TV',
                            'bbq' => 'BBQ Grill',
                            'patio' => 'Patio/Balcony',
                            'fireplace' => 'Fireplace',
                            'hot_tub' => 'Hot Tub',
                            'ev_charger' => 'EV Charger',
                            'wheelchair' => 'Wheelchair Accessible'
                        ];
                    @endphp
                    <div class="checkbox-grid">
                        @foreach($allAmenities as $key => $label)
                        <div class="checkbox-item">
                            <input type="checkbox" name="amenities[]" id="amenity_{{ $key }}" value="{{ $key }}" {{ in_array($key, $amenities) ? 'checked' : '' }}>
                            <label for="amenity_{{ $key }}">{{ $label }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Kosher Info -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Kosher Information</h2>
                </div>
                <div class="card-body">
                    @php
                        $kosherInfo = json_decode($property->kosher_info ?? '[]', true) ?: [];
                        $kosherOptions = [
                            'kosher_kitchen' => 'Kosher Kitchen',
                            'separate_dishes' => 'Separate Dishes (Meat/Dairy)',
                            'shabbat_elevator' => 'Shabbat Elevator',
                            'near_synagogue' => 'Near Synagogue',
                            'eruv' => 'Within Eruv',
                            'mikvah_nearby' => 'Mikvah Nearby',
                            'kosher_restaurants' => 'Kosher Restaurants Nearby',
                            'shabbat_friendly' => 'Shabbat Friendly'
                        ];
                    @endphp
                    <div class="checkbox-grid">
                        @foreach($kosherOptions as $key => $label)
                        <div class="checkbox-item">
                            <input type="checkbox" name="kosher_info[]" id="kosher_{{ $key }}" value="{{ $key }}" {{ in_array($key, $kosherInfo) ? 'checked' : '' }}>
                            <label for="kosher_{{ $key }}">{{ $label }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('owner.dashboard') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
    
    <script>
        function deleteImage(id) {
            if (!confirm('Are you sure you want to delete this image?')) return;
            
            fetch(`/owner/property/image/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`.image-item[data-id="${id}"]`).remove();
                }
            });
        }
        
        // Show selected files
        document.getElementById('images').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                alert(`${files.length} file(s) selected. They will be uploaded when you save.`);
            }
        });
    </script>
</body>
</html>
