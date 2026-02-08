@extends('admin.layouts.app')

@section('title', 'Add New Property')
@section('page-title', 'Add New Property')

@section('styles')
<style>
    .wizard-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .wizard-progress {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        position: relative;
    }
    
    .wizard-progress::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gray-200);
        z-index: 0;
    }
    
    .wizard-progress-bar {
        position: absolute;
        top: 20px;
        left: 0;
        height: 4px;
        background: var(--primary);
        z-index: 1;
        transition: width 0.3s ease;
    }
    
    .wizard-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        cursor: pointer;
    }
    
    .wizard-step-number {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--gray-200);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: var(--gray-400);
        transition: all 0.3s;
    }
    
    .wizard-step.active .wizard-step-number,
    .wizard-step.completed .wizard-step-number {
        border-color: var(--primary);
        background: var(--primary);
        color: white;
    }
    
    .wizard-step.completed .wizard-step-number {
        background: var(--success);
        border-color: var(--success);
    }
    
    .wizard-step-label {
        margin-top: 8px;
        font-size: 12px;
        font-weight: 500;
        color: var(--gray-400);
        text-align: center;
    }
    
    .wizard-step.active .wizard-step-label,
    .wizard-step.completed .wizard-step-label {
        color: var(--dark);
    }
    
    .wizard-step:hover .wizard-step-number {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .btn-outline-secondary {
        background: transparent;
        border: 2px solid var(--gray-300);
        color: var(--gray-600);
    }
    
    .btn-outline-secondary:hover {
        background: var(--gray-100);
        border-color: var(--gray-400);
        color: var(--gray-700);
    }
    
    .wizard-content {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }
    
    .wizard-section {
        display: none;
    }
    
    .wizard-section.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .wizard-section-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--dark);
    }
    
    .wizard-section-subtitle {
        font-size: 14px;
        color: var(--gray-500);
        margin-bottom: 32px;
    }
    
    /* Media Upload */
    .media-upload-zone {
        border: 2px dashed var(--gray-300);
        border-radius: 16px;
        padding: 48px 24px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        background: var(--gray-100);
    }
    
    .media-upload-zone:hover,
    .media-upload-zone.dragover {
        border-color: var(--primary);
        background: rgba(255,56,92,0.05);
    }
    
    .media-upload-zone i {
        font-size: 48px;
        color: var(--gray-400);
        margin-bottom: 16px;
    }
    
    .media-upload-zone:hover i {
        color: var(--primary);
    }
    
    .media-upload-zone h4 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .media-upload-zone p {
        font-size: 13px;
        color: var(--gray-500);
    }
    
    .media-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 16px;
        margin-top: 24px;
    }
    
    .media-preview-item {
        position: relative;
        aspect-ratio: 4/3;
        border-radius: 12px;
        overflow: hidden;
        background: var(--gray-200);
    }
    
    .media-preview-item img,
    .media-preview-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .media-preview-item .remove-media {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 28px;
        height: 28px;
        background: rgba(0,0,0,0.6);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .media-preview-item .set-primary {
        position: absolute;
        bottom: 8px;
        left: 8px;
        padding: 4px 12px;
        background: rgba(0,0,0,0.6);
        color: white;
        border: none;
        border-radius: 20px;
        font-size: 11px;
        cursor: pointer;
    }
    
    .media-preview-item.primary .set-primary {
        background: var(--primary);
    }
    
    .media-preview-item .media-type-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        padding: 4px 8px;
        background: rgba(0,0,0,0.6);
        color: white;
        border-radius: 6px;
        font-size: 10px;
        text-transform: uppercase;
    }
    
    /* Amenities Grid */
    .amenities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 12px;
    }
    
    .amenity-checkbox {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: var(--gray-100);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid transparent;
    }
    
    .amenity-checkbox:hover {
        background: var(--gray-200);
    }
    
    .amenity-checkbox.checked {
        background: rgba(255,56,92,0.08);
        border-color: var(--primary);
    }
    
    .amenity-checkbox input {
        display: none;
    }
    
    .amenity-checkbox .checkmark {
        width: 22px;
        height: 22px;
        border: 2px solid var(--gray-300);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .amenity-checkbox.checked .checkmark {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }
    
    .amenity-checkbox i.amenity-icon {
        font-size: 18px;
        color: var(--gray-500);
        width: 24px;
        text-align: center;
    }
    
    .amenity-checkbox.checked i.amenity-icon {
        color: var(--primary);
    }
    
    .amenity-checkbox span {
        font-size: 14px;
        font-weight: 500;
    }
    
    /* Custom Amenity Input */
    .custom-amenity-input {
        display: flex;
        gap: 12px;
        margin-top: 16px;
        align-items: flex-end;
    }
    
    .custom-amenity-input input[type="text"] {
        flex: 1;
    }
    
    .custom-amenity-input .icon-upload-wrapper {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .custom-amenity-input .icon-upload-wrapper label {
        font-size: 11px;
        color: var(--gray-500);
        font-weight: 500;
    }
    
    .icon-upload-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: var(--gray-100);
        border: 2px dashed var(--gray-300);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 13px;
        color: var(--gray-600);
    }
    
    .icon-upload-btn:hover {
        border-color: var(--primary);
        background: rgba(255,56,92,0.05);
    }
    
    .icon-upload-btn.has-icon {
        border-style: solid;
        border-color: var(--success);
        background: rgba(37, 99, 235, 0.1);
        color: var(--success);
    }
    
    .icon-upload-btn .preview-icon {
        width: 20px;
        height: 20px;
    }
    
    .icon-upload-btn .preview-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .custom-amenities-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }
    
    .custom-amenity-tag {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: var(--gray-100);
        border: 1px solid var(--gray-200);
        border-radius: 20px;
        font-size: 13px;
    }
    
    .custom-amenity-tag.saved {
        background: linear-gradient(135deg, #E8F5E9 0%, #F1F8E9 100%);
        border-color: #81C784;
    }
    
    .custom-amenity-tag .amenity-svg-icon {
        width: 18px;
        height: 18px;
        object-fit: contain;
    }
    
    .custom-amenity-tag button {
        background: none;
        border: none;
        color: var(--gray-500);
        cursor: pointer;
        padding: 0;
    }
    
    .custom-amenity-tag button:hover {
        color: var(--danger);
    }
    
    .saved-amenities-section {
        margin-top: 24px;
        padding: 16px;
        background: linear-gradient(135deg, #F5F5F5 0%, #FAFAFA 100%);
        border-radius: 12px;
        border: 1px solid var(--gray-200);
    }
    
    .saved-amenities-section h5 {
        font-size: 14px;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .saved-amenities-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    /* Kosher Section */
    .kosher-section {
        margin-top: 32px;
        padding-top: 32px;
        border-top: 1px solid var(--gray-200);
    }
    
    .kosher-toggle {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 20px;
        background: linear-gradient(135deg, #E8F4FD 0%, #F0F7FF 100%);
        border-radius: 12px;
        margin-bottom: 12px;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .kosher-toggle:hover {
        border-color: #2196F3;
    }
    
    .kosher-toggle.checked {
        background: linear-gradient(135deg, #BBDEFB 0%, #E3F2FD 100%);
        border-color: #2196F3;
    }
    
    .kosher-toggle input {
        display: none;
    }
    
    .kosher-toggle .toggle-check {
        width: 24px;
        height: 24px;
        border: 2px solid #90CAF9;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .kosher-toggle.checked .toggle-check {
        background: #2196F3;
        border-color: #2196F3;
        color: white;
    }
    
    .kosher-toggle span {
        font-weight: 500;
        color: #1565C0;
    }
    
    /* Kosher Kitchen Amenities Grid */
    .kosher-section-title {
        margin-top: 28px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #E3F2FD;
    }
    
    .kosher-section-title h4 {
        font-size: 16px;
        font-weight: 600;
        color: #1565C0;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }
    
    .kosher-amenities-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    
    @media (max-width: 900px) {
        .kosher-amenities-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 600px) {
        .kosher-amenities-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .kosher-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        background: #FAFAFA;
        border: 2px solid #E0E0E0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 13px;
    }
    
    .kosher-checkbox:hover {
        border-color: #90CAF9;
        background: #F5F9FF;
    }
    
    .kosher-checkbox.checked {
        background: linear-gradient(135deg, #E8F4FD 0%, #F0F7FF 100%);
        border-color: #42A5F5;
    }
    
    .kosher-checkbox input {
        display: none;
    }
    
    .kosher-checkmark {
        width: 20px;
        height: 20px;
        min-width: 20px;
        border: 2px solid #BDBDBD;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        font-size: 11px;
        color: transparent;
    }
    
    .kosher-checkbox.checked .kosher-checkmark {
        background: #43A047;
        border-color: #43A047;
        color: white;
    }
    
    .kosher-icon {
        font-size: 16px;
        color: #757575;
        min-width: 20px;
        text-align: center;
    }
    
    .kosher-checkbox.checked .kosher-icon {
        color: #1565C0;
    }
    
    .kosher-checkbox span:last-child {
        flex: 1;
        font-weight: 500;
        color: #424242;
        line-height: 1.3;
    }
    
    .kosher-checkbox.checked span:last-child {
        color: #1565C0;
    }
    
    .nearby-section {
        margin-top: 24px;
    }
    
    .nearby-section h4 {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 12px;
        color: var(--gray-600);
    }
    
    .nearby-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    
    /* Preview Section */
    .preview-container {
        background: var(--gray-100);
        border-radius: 16px;
        overflow: hidden;
    }
    
    .preview-gallery {
        position: relative;
        height: 400px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .preview-gallery img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .preview-gallery-nav {
        position: absolute;
        bottom: 16px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
    }
    
    .preview-gallery-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
    }
    
    .preview-gallery-dot.active {
        background: white;
    }
    
    .preview-details {
        padding: 24px;
    }
    
    .preview-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .preview-location {
        font-size: 15px;
        color: var(--gray-500);
        margin-bottom: 16px;
    }
    
    .preview-specs {
        display: flex;
        gap: 24px;
        padding: 16px 0;
        border-top: 1px solid var(--gray-200);
        border-bottom: 1px solid var(--gray-200);
        margin-bottom: 16px;
    }
    
    .preview-spec {
        text-align: center;
    }
    
    .preview-spec-value {
        font-size: 20px;
        font-weight: 600;
    }
    
    .preview-spec-label {
        font-size: 12px;
        color: var(--gray-500);
    }
    
    .preview-price {
        font-size: 24px;
        font-weight: 700;
    }
    
    .preview-price span {
        font-size: 14px;
        font-weight: 400;
        color: var(--gray-500);
    }
    
    .preview-amenities {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 16px;
    }
    
    .preview-amenity {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        background: white;
        border-radius: 20px;
        font-size: 13px;
    }
    
    .preview-amenity i {
        color: var(--primary);
    }
    
    .preview-kosher {
        margin-top: 16px;
        padding: 16px;
        background: linear-gradient(135deg, #E8F4FD 0%, #F0F7FF 100%);
        border-radius: 12px;
    }
    
    .preview-kosher h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1565C0;
        margin-bottom: 12px;
    }
    
    .preview-kosher-item {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        padding: 6px 0;
        border-bottom: 1px solid rgba(33,150,243,0.2);
    }
    
    .preview-kosher-item:last-child {
        border-bottom: none;
    }
    
    /* Wizard Navigation */
    .wizard-nav {
        display: flex;
        justify-content: space-between;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid var(--gray-200);
    }
    
    .wizard-nav .btn {
        min-width: 140px;
    }
</style>
@endsection

@section('content')
<div class="wizard-container">
    <!-- Progress Steps -->
    <div class="wizard-progress">
        <div class="wizard-progress-bar" style="width: 0%"></div>
        <div class="wizard-step active" data-step="1" onclick="goToStep(1)">
            <div class="wizard-step-number">1</div>
            <div class="wizard-step-label">Basic Info</div>
        </div>
        <div class="wizard-step" data-step="2" onclick="goToStep(2)">
            <div class="wizard-step-number">2</div>
            <div class="wizard-step-label">Location</div>
        </div>
        <div class="wizard-step" data-step="3" onclick="goToStep(3)">
            <div class="wizard-step-number">3</div>
            <div class="wizard-step-label">Media</div>
        </div>
        <div class="wizard-step" data-step="4" onclick="goToStep(4)">
            <div class="wizard-step-number">4</div>
            <div class="wizard-step-label">Description</div>
        </div>
        <div class="wizard-step" data-step="5" onclick="goToStep(5)">
            <div class="wizard-step-number">5</div>
            <div class="wizard-step-label">Amenities</div>
        </div>
        <div class="wizard-step" data-step="6" onclick="goToStep(6)">
            <div class="wizard-step-number">6</div>
            <div class="wizard-step-label">Kosher</div>
        </div>
        <div class="wizard-step" data-step="7" onclick="goToStep(7)">
            <div class="wizard-step-number">7</div>
            <div class="wizard-step-label">Preview</div>
        </div>
    </div>
    
    <!-- Wizard Content -->
    <div class="wizard-content">
        <form id="propertyWizardForm" enctype="multipart/form-data">
            @csrf
            
            <!-- Step 1: Basic Info -->
            <div class="wizard-section active" data-step="1">
                <h2 class="wizard-section-title">Basic Information</h2>
                <p class="wizard-section-subtitle">Let's start with the essentials about your property</p>
                
                <div class="form-group">
                    <label class="form-label">Property Title *</label>
                    <input type="text" name="title" id="title" class="form-control" required placeholder="e.g., Luxury Beachfront Villa with Ocean Views">
                </div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Property Type *</label>
                        <select name="property_type" id="property_type" class="form-control" required>
                            <option value="">Select type</option>
                            <option value="1">House</option>
                            <option value="2">Apartment</option>
                            <option value="3">Condo</option>
                            <option value="4">Villa</option>
                            <option value="5">Townhouse</option>
                            <option value="6">Cabin</option>
                            <option value="7">Cottage</option>
                            <option value="8">Bungalow</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Owner *</label>
                        <select name="owner_id" id="owner_id" class="form-control" required>
                            <option value="">Select owner</option>
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}">{{ $owner->first_name }} {{ $owner->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Price per Night ($) *</label>
                        <input type="number" name="price" id="price" class="form-control" min="0" step="0.01" required placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cleaning Fee ($)</label>
                        <input type="number" name="cleaning_fee" id="cleaning_fee" class="form-control" min="0" step="0.01" placeholder="0.00">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Max Guests *</label>
                        <input type="number" name="guest_count" id="guest_count" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bedrooms *</label>
                        <input type="number" name="bedroom_count" id="bedroom_count" class="form-control" min="0" value="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Beds *</label>
                        <input type="number" name="bed_count" id="bed_count" class="form-control" min="0" value="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bathrooms *</label>
                        <input type="number" name="bathroom_count" id="bathroom_count" class="form-control" min="0" value="1" required>
                    </div>
                </div>
            </div>
            
            <!-- Step 2: Location -->
            <div class="wizard-section" data-step="2">
                <h2 class="wizard-section-title">Property Location</h2>
                <p class="wizard-section-subtitle">Help guests find your property</p>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Street Address *</label>
                        <input type="text" name="street_name" id="street_name" class="form-control" placeholder="123 Main Street">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Apt/Suite/Unit</label>
                        <input type="text" name="house_number" id="house_number" class="form-control" placeholder="Apt 4B">
                    </div>
                </div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">City *</label>
                        <input type="text" name="city" id="city" class="form-control" placeholder="New York">
                    </div>
                    <div class="form-group">
                        <label class="form-label">State/Province *</label>
                        <input type="text" name="state" id="state" class="form-control" placeholder="NY">
                    </div>
                </div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">ZIP/Postal Code *</label>
                        <input type="text" name="zipcode" id="zipcode" class="form-control" placeholder="10001">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Country</label>
                        <select name="country" id="country" class="form-control">
                            <option value="US" selected>United States</option>
                            <option value="CA">Canada</option>
                            <option value="UK">United Kingdom</option>
                            <option value="IL">Israel</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Full Display Address</label>
                    <input type="text" name="map_address" id="map_address" class="form-control" placeholder="This is what guests will see">
                    <small style="color: var(--gray-500);">Leave blank to auto-generate from above fields</small>
                </div>
            </div>
            
            <!-- Step 3: Media Upload -->
            <div class="wizard-section" data-step="3">
                <h2 class="wizard-section-title">Photos & Videos</h2>
                <p class="wizard-section-subtitle">High-quality media helps your listing stand out</p>
                
                <div class="media-upload-zone" id="mediaDropZone">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <h4>Drag & drop your files here</h4>
                    <p>or click to browse • JPG, PNG, WEBP, MP4 • Max 10MB each</p>
                    <input type="file" id="mediaInput" name="media[]" multiple accept="image/*,video/*" style="display: none;">
                </div>
                
                <div class="media-preview-grid" id="mediaPreviewGrid"></div>
                
                <div style="margin-top: 24px; padding: 16px; background: var(--gray-100); border-radius: 12px;">
                    <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 8px;">📸 Photo Tips</h4>
                    <ul style="font-size: 13px; color: var(--gray-600); margin: 0; padding-left: 20px;">
                        <li>Use natural lighting whenever possible</li>
                        <li>Capture all rooms and outdoor spaces</li>
                        <li>The first photo will be your cover image</li>
                        <li>Add a video tour for 40% more bookings</li>
                    </ul>
                </div>
            </div>
            
            <!-- Step 4: Description -->
            <div class="wizard-section" data-step="4">
                <h2 class="wizard-section-title">Property Description</h2>
                <p class="wizard-section-subtitle">Tell guests what makes your place special</p>
                
                <div class="form-group">
                    <label class="form-label">Description *</label>
                    <textarea name="description" id="description" class="form-control" rows="6" placeholder="Describe your property in detail. Mention special features, nearby attractions, and what makes it unique..."></textarea>
                    <div style="display: flex; justify-content: space-between; margin-top: 8px;">
                        <small style="color: var(--gray-500);">Minimum 100 characters recommended</small>
                        <small id="descriptionCount" style="color: var(--gray-500);">0 characters</small>
                    </div>
                </div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Headline / Tagline</label>
                        <input type="text" name="tagline" id="tagline" class="form-control" placeholder="e.g., Your perfect getaway awaits">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Neighborhood</label>
                        <input type="text" name="neighborhood" id="neighborhood" class="form-control" placeholder="e.g., Downtown, Beachside">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">House Rules</label>
                    <textarea name="house_rules" id="house_rules" class="form-control" rows="3" placeholder="e.g., No smoking, No parties, Quiet hours 10pm-8am..."></textarea>
                </div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Check-in Time</label>
                        <input type="time" name="checkin_time" id="checkin_time" class="form-control" value="15:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Check-out Time</label>
                        <input type="time" name="checkout_time" id="checkout_time" class="form-control" value="11:00">
                    </div>
                </div>
            </div>
            
            <!-- Step 5: Amenities -->
            <div class="wizard-section" data-step="5">
                <h2 class="wizard-section-title">Amenities</h2>
                <p class="wizard-section-subtitle">Select all the amenities your property offers</p>
                
                <div class="amenities-grid">
                    <label class="amenity-checkbox" data-amenity="wifi">
                        <input type="checkbox" name="amenities[]" value="wifi">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-wifi amenity-icon"></i>
                        <span>WiFi</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="air_conditioning">
                        <input type="checkbox" name="amenities[]" value="air_conditioning">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-snowflake amenity-icon"></i>
                        <span>Air Conditioning</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="heating">
                        <input type="checkbox" name="amenities[]" value="heating">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-fire amenity-icon"></i>
                        <span>Heating</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="kitchen">
                        <input type="checkbox" name="amenities[]" value="kitchen">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-utensils amenity-icon"></i>
                        <span>Kitchen</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="washer">
                        <input type="checkbox" name="amenities[]" value="washer">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-tshirt amenity-icon"></i>
                        <span>Washer</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="dryer">
                        <input type="checkbox" name="amenities[]" value="dryer">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-wind amenity-icon"></i>
                        <span>Dryer</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="free_parking">
                        <input type="checkbox" name="amenities[]" value="free_parking">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-parking amenity-icon"></i>
                        <span>Free Parking</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="pool">
                        <input type="checkbox" name="amenities[]" value="pool">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-swimming-pool amenity-icon"></i>
                        <span>Pool</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="hot_tub">
                        <input type="checkbox" name="amenities[]" value="hot_tub">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-hot-tub amenity-icon"></i>
                        <span>Hot Tub</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="gym">
                        <input type="checkbox" name="amenities[]" value="gym">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-dumbbell amenity-icon"></i>
                        <span>Gym</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="tv">
                        <input type="checkbox" name="amenities[]" value="tv">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-tv amenity-icon"></i>
                        <span>TV</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="workspace">
                        <input type="checkbox" name="amenities[]" value="workspace">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-laptop amenity-icon"></i>
                        <span>Workspace</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="elevator">
                        <input type="checkbox" name="amenities[]" value="elevator">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-sort amenity-icon"></i>
                        <span>Elevator</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="wheelchair">
                        <input type="checkbox" name="amenities[]" value="wheelchair">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-wheelchair amenity-icon"></i>
                        <span>Wheelchair Accessible</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="smoke_detector">
                        <input type="checkbox" name="amenities[]" value="smoke_detector">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-bell amenity-icon"></i>
                        <span>Smoke Detector</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="first_aid">
                        <input type="checkbox" name="amenities[]" value="first_aid">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-first-aid amenity-icon"></i>
                        <span>First Aid Kit</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="bbq">
                        <input type="checkbox" name="amenities[]" value="bbq">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-fire-alt amenity-icon"></i>
                        <span>BBQ Grill</span>
                    </label>
                    <label class="amenity-checkbox" data-amenity="balcony">
                        <input type="checkbox" name="amenities[]" value="balcony">
                        <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                        <i class="fas fa-door-open amenity-icon"></i>
                        <span>Balcony/Patio</span>
                    </label>
                    
                    @if(isset($customAmenities) && $customAmenities->count() > 0)
                        @foreach($customAmenities as $amenity)
                        <label class="amenity-checkbox saved-amenity" data-amenity="{{ $amenity->slug }}" data-saved-id="{{ $amenity->id }}">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity->slug }}">
                            <span class="checkmark"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                            @if($amenity->icon_path)
                                <img src="{{ asset('storage/' . $amenity->icon_path) }}" class="amenity-svg-icon" alt="{{ $amenity->name }}" style="width: 20px; height: 20px;">
                            @else
                                <i class="fas fa-star amenity-icon"></i>
                            @endif
                            <span>{{ $amenity->name }}</span>
                        </label>
                        @endforeach
                    @endif
                </div>
                
                <!-- Add New Custom Amenity -->
                <div class="custom-amenity-input">
                    <div style="flex: 1;">
                        <input type="text" id="customAmenityInput" class="form-control" placeholder="Add custom amenity name...">
                    </div>
                    <div class="icon-upload-wrapper">
                        <label>Icon (SVG)</label>
                        <label class="icon-upload-btn" id="amenityIconBtn">
                            <input type="file" id="customAmenityIcon" accept=".svg" style="display: none;">
                            <span class="preview-icon" id="amenityIconPreview"><i class="fas fa-image"></i></span>
                            <span id="amenityIconText">Upload SVG</span>
                        </label>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="addCustomAmenity()" style="height: 44px;">
                        <i class="fas fa-plus"></i> Add
                    </button>
                    <button type="button" class="btn btn-success" onclick="saveCustomAmenity('general')" style="height: 44px;" title="Save permanently">
                        <i class="fas fa-save"></i>
                    </button>
                </div>
                <div class="custom-amenities-list" id="customAmenitiesList"></div>
            </div>
            
            <!-- Step 6: Kosher Amenities -->
            <div class="wizard-section" data-step="6">
                <h2 class="wizard-section-title">Kosher Amenities</h2>
                <p class="wizard-section-subtitle">Help observant guests find the perfect stay</p>
                
                <label class="kosher-toggle" data-toggle="kosher_kitchen">
                    <input type="checkbox" name="kosher_kitchen" value="1">
                    <span class="toggle-check"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                    <span>🍽️ Kosher Kitchen Available</span>
                </label>
                
                <label class="kosher-toggle" data-toggle="shabbos_friendly">
                    <input type="checkbox" name="shabbos_friendly" value="1">
                    <span class="toggle-check"><i class="fas fa-check" style="font-size: 12px;"></i></span>
                    <span>✡️ Shabbos Friendly</span>
                </label>
                
                <!-- Kitchen Amenities Section -->
                <div class="kosher-section-title">
                    <h4><i class="fas fa-utensils"></i> Kitchen Amenities</h4>
                </div>
                <div class="kosher-amenities-grid">
                    <label class="kosher-checkbox" data-kosher="kosher_certified">
                        <input type="checkbox" name="kosher_amenities[]" value="kosher_certified">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-certificate kosher-icon"></i>
                        <span>Kosher Kitchen (Chezkas Kashrus)</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="eat_in_kitchen">
                        <input type="checkbox" name="kosher_amenities[]" value="eat_in_kitchen">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-chair kosher-icon"></i>
                        <span>Eat-In Kitchen</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="keurig">
                        <input type="checkbox" name="kosher_amenities[]" value="keurig">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-coffee kosher-icon"></i>
                        <span>Keurig Type, Single-Serve Coffee</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="hot_water_urn">
                        <input type="checkbox" name="kosher_amenities[]" value="hot_water_urn">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-mug-hot kosher-icon"></i>
                        <span>Shabbos Hot Water Urn</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="hot_plate">
                        <input type="checkbox" name="kosher_amenities[]" value="hot_plate">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-fire kosher-icon"></i>
                        <span>Shabbos Hot Plate (Electric)</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="crock_pot">
                        <input type="checkbox" name="kosher_amenities[]" value="crock_pot">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-bowl-food kosher-icon"></i>
                        <span>Shabbos Crock Pot</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="meat_dishes">
                        <input type="checkbox" name="kosher_amenities[]" value="meat_dishes">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-drumstick-bite kosher-icon" style="color: #C62828;"></i>
                        <span>Meat Dishes, Cutlery, Pots & Pans</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="dairy_dishes">
                        <input type="checkbox" name="kosher_amenities[]" value="dairy_dishes">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-cheese kosher-icon" style="color: #1565C0;"></i>
                        <span>Dairy Dishes, Cutlery, Pots & Pans</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="pareve_dishes">
                        <input type="checkbox" name="kosher_amenities[]" value="pareve_dishes">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-leaf kosher-icon" style="color: #2563EB;"></i>
                        <span>Pareve Pots and Utensils</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="full_fridge">
                        <input type="checkbox" name="kosher_amenities[]" value="full_fridge">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-temperature-low kosher-icon"></i>
                        <span>Full Size Refrigerator / Freezer</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="full_oven">
                        <input type="checkbox" name="kosher_amenities[]" value="full_oven">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-fire-burner kosher-icon"></i>
                        <span>Full Size Oven / Stove</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="microwave">
                        <input type="checkbox" name="kosher_amenities[]" value="microwave">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-clock kosher-icon"></i>
                        <span>Microwave</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="dishwasher">
                        <input type="checkbox" name="kosher_amenities[]" value="dishwasher">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-soap kosher-icon"></i>
                        <span>Dishwasher</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="milchig_utensils">
                        <input type="checkbox" name="kosher_amenities[]" value="milchig_utensils">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-spoon kosher-icon" style="color: #1565C0;"></i>
                        <span>Basic Milchig Utensils & Serving</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="fleishig_utensils">
                        <input type="checkbox" name="kosher_amenities[]" value="fleishig_utensils">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-utensils kosher-icon" style="color: #C62828;"></i>
                        <span>Basic Fleishig Utensils & Serving</span>
                    </label>
                    <label class="kosher-checkbox" data-kosher="pareve_utensils">
                        <input type="checkbox" name="kosher_amenities[]" value="pareve_utensils">
                        <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                        <i class="fas fa-spoon kosher-icon" style="color: #2563EB;"></i>
                        <span>Basic Parve Utensils & Serving</span>
                    </label>
                    
                    @if(isset($customKosherAmenities) && $customKosherAmenities->count() > 0)
                        @foreach($customKosherAmenities as $amenity)
                        <label class="kosher-checkbox saved-amenity" data-kosher="{{ $amenity->slug }}" data-saved-id="{{ $amenity->id }}">
                            <input type="checkbox" name="kosher_amenities[]" value="{{ $amenity->slug }}">
                            <span class="kosher-checkmark"><i class="fas fa-check"></i></span>
                            @if($amenity->icon_path)
                                <img src="{{ asset('storage/' . $amenity->icon_path) }}" class="kosher-icon" alt="{{ $amenity->name }}" style="width: 18px; height: 18px;">
                            @else
                                <i class="fas fa-star kosher-icon"></i>
                            @endif
                            <span>{{ $amenity->name }}</span>
                        </label>
                        @endforeach
                    @endif
                </div>
                
                <!-- Custom Kosher Amenity Input -->
                <div class="custom-amenity-input" style="margin-top: 16px;">
                    <div style="flex: 1;">
                        <input type="text" id="customKosherInput" class="form-control" placeholder="Add custom kosher amenity name...">
                    </div>
                    <div class="icon-upload-wrapper">
                        <label>Icon (SVG)</label>
                        <label class="icon-upload-btn" id="kosherIconBtn">
                            <input type="file" id="customKosherIcon" accept=".svg" style="display: none;">
                            <span class="preview-icon" id="kosherIconPreview"><i class="fas fa-image"></i></span>
                            <span id="kosherIconText">Upload SVG</span>
                        </label>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="addCustomKosherAmenity()" style="height: 44px;">
                        <i class="fas fa-plus"></i> Add
                    </button>
                    <button type="button" class="btn btn-success" onclick="saveCustomAmenity('kosher')" style="height: 44px;" title="Save permanently">
                        <i class="fas fa-save"></i>
                    </button>
                </div>
                <div class="custom-amenities-list" id="customKosherList"></div>
                
                <div class="nearby-section">
                    <h4><i class="fas fa-synagogue" style="margin-right: 8px;"></i>Nearby Shul</h4>
                    <div class="nearby-inputs">
                        <div class="form-group">
                            <label class="form-label">Shul Name</label>
                            <input type="text" name="nearby_shul_name" class="form-control" placeholder="Beth Israel Synagogue">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Distance</label>
                            <input type="text" name="nearby_shul_distance" class="form-control" placeholder="5 min walk">
                        </div>
                    </div>
                </div>
                
                <div class="nearby-section">
                    <h4><i class="fas fa-store" style="margin-right: 8px;"></i>Nearby Kosher Shops</h4>
                    <div class="nearby-inputs">
                        <div class="form-group">
                            <label class="form-label">Shop Name</label>
                            <input type="text" name="nearby_shop_name" class="form-control" placeholder="Kosher Market">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Distance</label>
                            <input type="text" name="nearby_shop_distance" class="form-control" placeholder="10 min walk">
                        </div>
                    </div>
                </div>
                
                <div class="nearby-section">
                    <h4><i class="fas fa-water" style="margin-right: 8px;"></i>Nearby Mikva</h4>
                    <div class="nearby-inputs">
                        <div class="form-group">
                            <label class="form-label">Mikva Name</label>
                            <input type="text" name="nearby_mikva_name" class="form-control" placeholder="Community Mikva">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Distance</label>
                            <input type="text" name="nearby_mikva_distance" class="form-control" placeholder="3 min walk">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Step 7: Preview -->
            <div class="wizard-section" data-step="7">
                <h2 class="wizard-section-title">Preview Your Listing</h2>
                <p class="wizard-section-subtitle">This is how guests will see your property</p>
                
                <div class="preview-container">
                    <div class="preview-gallery" id="previewGallery">
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-image" style="font-size: 64px; opacity: 0.5;"></i>
                        </div>
                    </div>
                    <div class="preview-details">
                        <h2 class="preview-title" id="previewTitle">Property Title</h2>
                        <p class="preview-location" id="previewLocation"><i class="fas fa-map-marker-alt"></i> <span>Location</span></p>
                        
                        <div class="preview-specs">
                            <div class="preview-spec">
                                <div class="preview-spec-value" id="previewGuests">1</div>
                                <div class="preview-spec-label">Guests</div>
                            </div>
                            <div class="preview-spec">
                                <div class="preview-spec-value" id="previewBedrooms">1</div>
                                <div class="preview-spec-label">Bedrooms</div>
                            </div>
                            <div class="preview-spec">
                                <div class="preview-spec-value" id="previewBeds">1</div>
                                <div class="preview-spec-label">Beds</div>
                            </div>
                            <div class="preview-spec">
                                <div class="preview-spec-value" id="previewBaths">1</div>
                                <div class="preview-spec-label">Baths</div>
                            </div>
                        </div>
                        
                        <div class="preview-price">$<span id="previewPrice">0</span> <span>/ night</span></div>
                        
                        <p id="previewDescription" style="margin-top: 16px; color: var(--gray-600); font-size: 14px; line-height: 1.6;"></p>
                        
                        <div class="preview-amenities" id="previewAmenities"></div>
                        
                        <div class="preview-kosher" id="previewKosher" style="display: none;">
                            <h4>✡️ Kosher Amenities</h4>
                            <div id="previewKosherContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
        <!-- Navigation -->
        <div class="wizard-nav">
            <button type="button" class="btn btn-secondary" id="prevBtn" onclick="prevStep()" style="display: none;">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <div style="margin-left: auto; display: flex; gap: 12px;">
                <button type="button" class="btn btn-secondary" onclick="saveDraft()">
                    <i class="fas fa-save"></i> Save Draft
                </button>
                <button type="button" class="btn btn-outline-secondary" id="skipBtn" onclick="skipStep()">
                    Skip <i class="fas fa-forward"></i>
                </button>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextStep()">
                    Next <i class="fas fa-arrow-right"></i>
                </button>
                <button type="button" class="btn btn-success" id="submitBtn" onclick="submitProperty()" style="display: none;">
                    <i class="fas fa-check"></i> Publish Listing
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentStep = 1;
const totalSteps = 7;
let uploadedMedia = [];
let customAmenities = [];
let customKosherAmenities = [];
let selectedAmenityIcon = null;
let selectedKosherIcon = null;

// Amenity checkbox handling
document.querySelectorAll('.amenity-checkbox').forEach(checkbox => {
    checkbox.addEventListener('click', function() {
        const input = this.querySelector('input');
        input.checked = !input.checked;
        this.classList.toggle('checked', input.checked);
    });
});

// Kosher toggle handling
document.querySelectorAll('.kosher-toggle').forEach(toggle => {
    toggle.addEventListener('click', function() {
        const input = this.querySelector('input');
        input.checked = !input.checked;
        this.classList.toggle('checked', input.checked);
    });
});

// Kosher checkbox handling
document.querySelectorAll('.kosher-checkbox').forEach(checkbox => {
    checkbox.addEventListener('click', function() {
        const input = this.querySelector('input');
        input.checked = !input.checked;
        this.classList.toggle('checked', input.checked);
    });
});

// Icon upload handling for general amenities
document.getElementById('customAmenityIcon').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file && file.type === 'image/svg+xml') {
        selectedAmenityIcon = file;
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('amenityIconPreview').innerHTML = `<img src="${e.target.result}" alt="Icon">`;
            document.getElementById('amenityIconText').textContent = 'Change';
            document.getElementById('amenityIconBtn').classList.add('has-icon');
        };
        reader.readAsDataURL(file);
    }
});

// Icon upload handling for kosher amenities
document.getElementById('customKosherIcon').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file && file.type === 'image/svg+xml') {
        selectedKosherIcon = file;
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('kosherIconPreview').innerHTML = `<img src="${e.target.result}" alt="Icon">`;
            document.getElementById('kosherIconText').textContent = 'Change';
            document.getElementById('kosherIconBtn').classList.add('has-icon');
        };
        reader.readAsDataURL(file);
    }
});

// Media upload handling
const mediaDropZone = document.getElementById('mediaDropZone');
const mediaInput = document.getElementById('mediaInput');
const mediaPreviewGrid = document.getElementById('mediaPreviewGrid');

mediaDropZone.addEventListener('click', () => mediaInput.click());

mediaDropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    mediaDropZone.classList.add('dragover');
});

mediaDropZone.addEventListener('dragleave', () => {
    mediaDropZone.classList.remove('dragover');
});

mediaDropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    mediaDropZone.classList.remove('dragover');
    handleFiles(e.dataTransfer.files);
});

mediaInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
});

function handleFiles(files) {
    Array.from(files).forEach(file => {
        if (file.size > 10 * 1024 * 1024) {
            showToast(`${file.name} is too large (max 10MB)`, 'error');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = (e) => {
            const media = {
                id: Date.now() + Math.random(),
                file: file,
                url: e.target.result,
                type: file.type.startsWith('video/') ? 'video' : 'image',
                primary: uploadedMedia.length === 0
            };
            uploadedMedia.push(media);
            renderMediaPreview();
        };
        reader.readAsDataURL(file);
    });
}

function renderMediaPreview() {
    mediaPreviewGrid.innerHTML = uploadedMedia.map((media, index) => `
        <div class="media-preview-item ${media.primary ? 'primary' : ''}" data-id="${media.id}">
            ${media.type === 'video' 
                ? `<video src="${media.url}" muted></video>`
                : `<img src="${media.url}" alt="Preview">`
            }
            <span class="media-type-badge">${media.type}</span>
            <button type="button" class="remove-media" onclick="removeMedia(${media.id})">
                <i class="fas fa-times"></i>
            </button>
            <button type="button" class="set-primary" onclick="setPrimaryMedia(${media.id})">
                ${media.primary ? '★ Cover' : 'Set as cover'}
            </button>
        </div>
    `).join('');
}

function removeMedia(id) {
    uploadedMedia = uploadedMedia.filter(m => m.id !== id);
    if (uploadedMedia.length > 0 && !uploadedMedia.some(m => m.primary)) {
        uploadedMedia[0].primary = true;
    }
    renderMediaPreview();
}

function setPrimaryMedia(id) {
    uploadedMedia.forEach(m => m.primary = (m.id === id));
    renderMediaPreview();
}

// Custom amenities
function addCustomAmenity() {
    const input = document.getElementById('customAmenityInput');
    const value = input.value.trim();
    if (value && !customAmenities.some(a => a.name === value)) {
        const iconPreview = selectedAmenityIcon ? URL.createObjectURL(selectedAmenityIcon) : null;
        customAmenities.push({
            name: value,
            icon: selectedAmenityIcon,
            iconPreview: iconPreview
        });
        renderCustomAmenities();
        input.value = '';
        // Reset icon
        selectedAmenityIcon = null;
        document.getElementById('amenityIconPreview').innerHTML = '<i class="fas fa-image"></i>';
        document.getElementById('amenityIconText').textContent = 'Upload SVG';
        document.getElementById('amenityIconBtn').classList.remove('has-icon');
        document.getElementById('customAmenityIcon').value = '';
    }
}

document.getElementById('customAmenityInput').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        addCustomAmenity();
    }
});

function renderCustomAmenities() {
    const container = document.getElementById('customAmenitiesList');
    container.innerHTML = customAmenities.map((amenity, index) => `
        <div class="custom-amenity-tag">
            ${amenity.iconPreview 
                ? `<img src="${amenity.iconPreview}" class="amenity-svg-icon" alt="${amenity.name}">`
                : '<i class="fas fa-star"></i>'
            }
            <span>${amenity.name}</span>
            <button type="button" onclick="removeCustomAmenity(${index})"><i class="fas fa-times"></i></button>
        </div>
    `).join('');
}

function removeCustomAmenity(index) {
    customAmenities.splice(index, 1);
    renderCustomAmenities();
}

// Save custom amenity permanently to database
async function saveCustomAmenity(type) {
    const inputId = type === 'kosher' ? 'customKosherInput' : 'customAmenityInput';
    const iconFile = type === 'kosher' ? selectedKosherIcon : selectedAmenityIcon;
    const input = document.getElementById(inputId);
    const name = input.value.trim();
    
    if (!name) {
        showToast('Please enter an amenity name', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('name', name);
    formData.append('type', type === 'kosher' ? 'kosher' : 'general');
    if (iconFile) {
        formData.append('icon', iconFile);
    }
    
    try {
        const response = await fetch('{{ route("admin.amenities.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Amenity saved permanently!', 'success');
            // Add the new checkbox to the grid
            addSavedAmenityToGrid(result.amenity, type);
            // Clear inputs
            input.value = '';
            if (type === 'kosher') {
                selectedKosherIcon = null;
                document.getElementById('kosherIconPreview').innerHTML = '<i class="fas fa-image"></i>';
                document.getElementById('kosherIconText').textContent = 'Upload SVG';
                document.getElementById('kosherIconBtn').classList.remove('has-icon');
                document.getElementById('customKosherIcon').value = '';
            } else {
                selectedAmenityIcon = null;
                document.getElementById('amenityIconPreview').innerHTML = '<i class="fas fa-image"></i>';
                document.getElementById('amenityIconText').textContent = 'Upload SVG';
                document.getElementById('amenityIconBtn').classList.remove('has-icon');
                document.getElementById('customAmenityIcon').value = '';
            }
        } else {
            showToast(result.message || 'Failed to save amenity', 'error');
        }
    } catch (error) {
        console.error(error);
        showToast('An error occurred while saving', 'error');
    }
}

function addSavedAmenityToGrid(amenity, type) {
    const grid = type === 'kosher' 
        ? document.querySelector('.kosher-amenities-grid')
        : document.querySelector('.amenities-grid');
    
    const checkboxClass = type === 'kosher' ? 'kosher-checkbox' : 'amenity-checkbox';
    const inputName = type === 'kosher' ? 'kosher_amenities[]' : 'amenities[]';
    const iconClass = type === 'kosher' ? 'kosher-icon' : 'amenity-icon';
    
    const label = document.createElement('label');
    label.className = `${checkboxClass} saved-amenity`;
    label.dataset[type === 'kosher' ? 'kosher' : 'amenity'] = amenity.slug;
    label.dataset.savedId = amenity.id;
    
    label.innerHTML = `
        <input type="checkbox" name="${inputName}" value="${amenity.slug}">
        <span class="${type === 'kosher' ? 'kosher-checkmark' : 'checkmark'}"><i class="fas fa-check" style="font-size: 12px;"></i></span>
        ${amenity.icon_path 
            ? `<img src="${amenity.icon_path}" class="${iconClass}" alt="${amenity.name}" style="width: ${type === 'kosher' ? '18' : '20'}px; height: ${type === 'kosher' ? '18' : '20'}px;">`
            : `<i class="fas fa-star ${iconClass}"></i>`
        }
        <span>${amenity.name}</span>
    `;
    
    // Add click handler
    label.addEventListener('click', function() {
        const input = this.querySelector('input');
        input.checked = !input.checked;
        this.classList.toggle('checked', input.checked);
    });
    
    grid.appendChild(label);
}

// Custom kosher amenities
function addCustomKosherAmenity() {
    const input = document.getElementById('customKosherInput');
    const value = input.value.trim();
    if (value && !customKosherAmenities.some(a => a.name === value)) {
        const iconPreview = selectedKosherIcon ? URL.createObjectURL(selectedKosherIcon) : null;
        customKosherAmenities.push({
            name: value,
            icon: selectedKosherIcon,
            iconPreview: iconPreview
        });
        renderCustomKosherAmenities();
        input.value = '';
        // Reset icon
        selectedKosherIcon = null;
        document.getElementById('kosherIconPreview').innerHTML = '<i class="fas fa-image"></i>';
        document.getElementById('kosherIconText').textContent = 'Upload SVG';
        document.getElementById('kosherIconBtn').classList.remove('has-icon');
        document.getElementById('customKosherIcon').value = '';
    }
}

document.getElementById('customKosherInput').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        addCustomKosherAmenity();
    }
});

function renderCustomKosherAmenities() {
    const container = document.getElementById('customKosherList');
    container.innerHTML = customKosherAmenities.map((amenity, index) => `
        <div class="custom-amenity-tag" style="background: linear-gradient(135deg, #E8F4FD 0%, #F0F7FF 100%); border-color: #90CAF9;">
            ${amenity.iconPreview 
                ? `<img src="${amenity.iconPreview}" class="amenity-svg-icon" alt="${amenity.name}">`
                : '✡️'
            }
            <span style="color: #1565C0;">${amenity.name}</span>
            <button type="button" onclick="removeCustomKosherAmenity(${index})"><i class="fas fa-times"></i></button>
        </div>
    `).join('');
}

function removeCustomKosherAmenity(index) {
    customKosherAmenities.splice(index, 1);
    renderCustomKosherAmenities();
}

// Description character count
document.getElementById('description').addEventListener('input', function() {
    document.getElementById('descriptionCount').textContent = this.value.length + ' characters';
});

// Step navigation
function goToStep(step, skipValidation = true) {
    if (step < 1 || step > totalSteps) return;
    // Allow skipping - no validation required when clicking on steps
    currentStep = step;
    updateWizard();
}

function nextStep(validate = true) {
    if (validate && !validateStep(currentStep)) return;
    if (currentStep < totalSteps) {
        currentStep++;
        updateWizard();
    }
}

function skipStep() {
    // Skip current step without validation
    if (currentStep < totalSteps) {
        currentStep++;
        updateWizard();
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        updateWizard();
    }
}

function validateStep(step) {
    const section = document.querySelector(`.wizard-section[data-step="${step}"]`);
    const requiredFields = section.querySelectorAll('[required]');
    
    for (let field of requiredFields) {
        if (!field.value.trim()) {
            field.focus();
            showToast('Please fill in all required fields', 'error');
            return false;
        }
    }
    return true;
}

function updateWizard() {
    // Update sections
    document.querySelectorAll('.wizard-section').forEach(section => {
        section.classList.remove('active');
    });
    document.querySelector(`.wizard-section[data-step="${currentStep}"]`).classList.add('active');
    
    // Update progress steps
    document.querySelectorAll('.wizard-step').forEach(step => {
        const stepNum = parseInt(step.dataset.step);
        step.classList.remove('active', 'completed');
        if (stepNum === currentStep) {
            step.classList.add('active');
        } else if (stepNum < currentStep) {
            step.classList.add('completed');
        }
    });
    
    // Update progress bar
    const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
    document.querySelector('.wizard-progress-bar').style.width = progress + '%';
    
    // Update navigation buttons
    document.getElementById('prevBtn').style.display = currentStep > 1 ? 'inline-flex' : 'none';
    document.getElementById('nextBtn').style.display = currentStep < totalSteps ? 'inline-flex' : 'none';
    document.getElementById('skipBtn').style.display = currentStep < totalSteps ? 'inline-flex' : 'none';
    document.getElementById('submitBtn').style.display = currentStep === totalSteps ? 'inline-flex' : 'none';
    
    // Update preview on last step
    if (currentStep === totalSteps) {
        updatePreview();
    }
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updatePreview() {
    // Title & Location
    document.getElementById('previewTitle').textContent = document.getElementById('title').value || 'Property Title';
    
    const city = document.getElementById('city').value;
    const state = document.getElementById('state').value;
    const location = [city, state].filter(Boolean).join(', ') || 'Location';
    document.getElementById('previewLocation').querySelector('span').textContent = location;
    
    // Specs
    document.getElementById('previewGuests').textContent = document.getElementById('guest_count').value || '1';
    document.getElementById('previewBedrooms').textContent = document.getElementById('bedroom_count').value || '1';
    document.getElementById('previewBeds').textContent = document.getElementById('bed_count').value || '1';
    document.getElementById('previewBaths').textContent = document.getElementById('bathroom_count').value || '1';
    
    // Price
    document.getElementById('previewPrice').textContent = document.getElementById('price').value || '0';
    
    // Description
    document.getElementById('previewDescription').textContent = document.getElementById('description').value || '';
    
    // Gallery
    const gallery = document.getElementById('previewGallery');
    const primaryMedia = uploadedMedia.find(m => m.primary);
    if (primaryMedia) {
        if (primaryMedia.type === 'video') {
            gallery.innerHTML = `<video src="${primaryMedia.url}" autoplay muted loop style="width: 100%; height: 100%; object-fit: cover;"></video>`;
        } else {
            gallery.innerHTML = `<img src="${primaryMedia.url}" style="width: 100%; height: 100%; object-fit: cover;">`;
        }
    }
    
    // Amenities
    const amenitiesContainer = document.getElementById('previewAmenities');
    const selectedAmenities = [];
    document.querySelectorAll('.amenity-checkbox.checked').forEach(cb => {
        const icon = cb.querySelector('.amenity-icon').className;
        const label = cb.querySelector('span:last-child').textContent;
        selectedAmenities.push({ icon, label });
    });
    customAmenities.forEach(amenity => {
        selectedAmenities.push({ icon: 'fas fa-star', label: amenity });
    });
    
    amenitiesContainer.innerHTML = selectedAmenities.map(a => `
        <div class="preview-amenity">
            <i class="${a.icon.replace('amenity-icon', '')}"></i>
            <span>${a.label}</span>
        </div>
    `).join('');
    
    // Kosher
    const kosherSection = document.getElementById('previewKosher');
    const kosherContent = document.getElementById('previewKosherContent');
    const kosherItems = [];
    
    if (document.querySelector('[name="kosher_kitchen"]').checked) {
        kosherItems.push('<div class="preview-kosher-item"><span>Kosher Kitchen</span><span>✓ Available</span></div>');
    }
    if (document.querySelector('[name="shabbos_friendly"]').checked) {
        kosherItems.push('<div class="preview-kosher-item"><span>Shabbos Friendly</span><span>✓ Yes</span></div>');
    }
    
    // Kitchen amenities
    document.querySelectorAll('.kosher-checkbox.checked').forEach(cb => {
        const label = cb.querySelector('span:last-child').textContent;
        kosherItems.push(`<div class="preview-kosher-item"><span>${label}</span><span>✓</span></div>`);
    });
    
    // Custom kosher amenities
    customKosherAmenities.forEach(amenity => {
        kosherItems.push(`<div class="preview-kosher-item"><span>${amenity}</span><span>✓</span></div>`);
    });
    
    const shulName = document.querySelector('[name="nearby_shul_name"]').value;
    const shulDist = document.querySelector('[name="nearby_shul_distance"]').value;
    if (shulName) {
        kosherItems.push(`<div class="preview-kosher-item"><span>Nearby Shul: ${shulName}</span><span>${shulDist}</span></div>`);
    }
    
    const shopName = document.querySelector('[name="nearby_shop_name"]').value;
    const shopDist = document.querySelector('[name="nearby_shop_distance"]').value;
    if (shopName) {
        kosherItems.push(`<div class="preview-kosher-item"><span>Kosher Shop: ${shopName}</span><span>${shopDist}</span></div>`);
    }
    
    const mikvaName = document.querySelector('[name="nearby_mikva_name"]').value;
    const mikvaDist = document.querySelector('[name="nearby_mikva_distance"]').value;
    if (mikvaName) {
        kosherItems.push(`<div class="preview-kosher-item"><span>Mikva: ${mikvaName}</span><span>${mikvaDist}</span></div>`);
    }
    
    if (kosherItems.length > 0) {
        kosherSection.style.display = 'block';
        kosherContent.innerHTML = kosherItems.join('');
    } else {
        kosherSection.style.display = 'none';
    }
}

function saveDraft() {
    showToast('Draft saved successfully!', 'success');
}

async function submitProperty() {
    const form = document.getElementById('propertyWizardForm');
    const formData = new FormData(form);
    
    // Add uploaded media files
    uploadedMedia.forEach((media, index) => {
        formData.append(`media_files[${index}]`, media.file);
        formData.append(`media_primary[${index}]`, media.primary ? '1' : '0');
    });
    
    // Add custom amenities (name only for property storage)
    customAmenities.forEach((amenity, index) => {
        formData.append(`custom_amenities[${index}]`, amenity.name);
    });
    
    // Add custom kosher amenities (name only for property storage)
    customKosherAmenities.forEach((amenity, index) => {
        formData.append(`custom_kosher_amenities[${index}]`, amenity.name);
    });
    
    // Build address if not provided
    if (!formData.get('map_address')) {
        const parts = [
            formData.get('street_name'),
            formData.get('house_number'),
            formData.get('city'),
            formData.get('state'),
            formData.get('zipcode')
        ].filter(Boolean);
        formData.set('map_address', parts.join(', '));
    }
    
    // Add description to additional_information
    formData.set('additional_information', formData.get('description'));
    
    try {
        const response = await fetch('/admin/properties/create', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        // Log the raw response for debugging
        console.log('Response status:', response.status);
        console.log('Response headers:', Object.fromEntries(response.headers.entries()));
        
        // Check if response is ok (status in 200-299 range)
        if (!response.ok) {
            const text = await response.text();
            console.error('Error response text:', text);
            showToast(`Server error: ${response.status} - ${response.statusText}`, 'error');
            return;
        }
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Property published successfully!', 'success');
            setTimeout(() => {
                window.location.href = '{{ route("admin.properties") }}';
            }, 1500);
        } else {
            showToast(result.message || 'Failed to create property', 'error');
        }
    } catch (error) {
        console.error('Fetch error:', error);
        showToast('An error occurred while creating the property: ' + error.message, 'error');
    }
}
</script>
@endsection
