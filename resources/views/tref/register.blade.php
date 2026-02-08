<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign Up - Tref Stays</title>
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
            --radius: 0.5rem;
            --error: #ef4444;
            --success: #22c55e;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg) 100%);
            color: var(--text);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 2rem;
        }
        .auth-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
        }
        .auth-card.wide { max-width: 640px; }
        .auth-header {
            padding: 2rem 2rem 1.5rem;
            text-align: center;
        }
        .auth-logo { height: 3rem; margin-bottom: 1.5rem; }
        .auth-title { font-size: 1.5rem; font-weight: 700; color: var(--tref-blue); margin-bottom: 0.5rem; }
        .auth-subtitle { font-size: 0.875rem; color: var(--text-muted); }
        .auth-content { padding: 0 2rem 2rem; }
        
        /* Progress Bar */
        .progress-bar { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; }
        .progress-step { height: 0.25rem; flex: 1; background: var(--border); border-radius: 9999px; }
        .progress-step.active { background: var(--tref-blue); }
        
        /* Role Selection */
        .role-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; }
        .role-option {
            position: relative;
            display: flex; flex-direction: column; align-items: center;
            padding: 1.5rem; border: 2px solid var(--border);
            border-radius: var(--radius); cursor: pointer;
            transition: all 0.2s; text-align: center;
        }
        .role-option:hover { border-color: rgba(0,128,255,0.5); }
        .role-option.active { border-color: var(--tref-blue); background: rgba(0,128,255,0.05); }
        .role-option input { position: absolute; opacity: 0; }
        .role-icon { width: 2.5rem; height: 2.5rem; margin-bottom: 0.75rem; color: var(--tref-blue); }
        .role-title { font-weight: 600; margin-bottom: 0.25rem; }
        .role-desc { font-size: 0.75rem; color: var(--text-muted); }
        
        /* Form Elements */
        .form-group { margin-bottom: 1rem; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 0.75rem 1rem; font-size: 0.875rem;
            border: 1px solid var(--border); border-radius: var(--radius);
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none; border-color: var(--tref-blue);
            box-shadow: 0 0 0 3px rgba(0,128,255,0.1);
        }
        .form-input.error { border-color: var(--error); }
        .form-textarea { resize: vertical; min-height: 100px; }
        .error-text { font-size: 0.8125rem; color: var(--error); margin-top: 0.25rem; }
        
        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.75rem 1.5rem; font-size: 0.9375rem; font-weight: 600;
            border-radius: var(--radius); border: none; cursor: pointer; transition: all 0.2s;
        }
        .btn-primary { background: var(--tref-blue); color: white; }
        .btn-primary:hover { background: var(--tref-blue-hover); }
        .btn-primary:disabled { background: #94a3b8; cursor: not-allowed; }
        .btn-outline { background: white; border: 1px solid var(--border); color: var(--text); }
        .btn-outline:hover { border-color: var(--tref-blue); }
        .btn-full { width: 100%; }
        .btn-row { display: flex; gap: 0.75rem; margin-top: 1.5rem; }
        .btn-row .btn { flex: 1; }
        
        .auth-footer { text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: var(--text-muted); }
        .auth-footer a { color: var(--tref-blue); text-decoration: none; font-weight: 500; }
        .auth-footer a:hover { text-decoration: underline; }
        
        .hidden { display: none !important; }
        
        /* Checkbox */
        .checkbox-group { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; }
        .checkbox-item { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; }
        .checkbox-item input { width: 1rem; height: 1rem; accent-color: var(--tref-blue); }
        
        /* Feature box */
        .feature-box {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.75rem 1rem; background: rgba(0,128,255,0.05);
            border-radius: var(--radius); margin-bottom: 0.5rem;
        }
        .feature-box input { width: 1rem; height: 1rem; accent-color: var(--tref-blue); }
        
        /* Image Upload */
        .upload-area {
            border: 2px dashed var(--border); border-radius: var(--radius);
            padding: 2rem; text-align: center; cursor: pointer;
            transition: border-color 0.2s;
        }
        .upload-area:hover { border-color: var(--tref-blue); }
        .upload-icon { width: 2.5rem; height: 2.5rem; margin: 0 auto 0.5rem; color: var(--text-muted); }
        .upload-text { font-size: 0.875rem; color: var(--text-muted); }
        .image-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem; margin-top: 1rem; }
        .image-preview {
            position: relative; aspect-ratio: 1; border-radius: var(--radius);
            overflow: hidden; border: 2px solid transparent;
        }
        .image-preview.main { border-color: var(--tref-blue); }
        .image-preview img { width: 100%; height: 100%; object-fit: cover; }
        .image-preview .remove-btn {
            position: absolute; top: 0.25rem; right: 0.25rem;
            width: 1.5rem; height: 1.5rem; background: rgba(239,68,68,0.9);
            border: none; border-radius: 50%; color: white; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.2s;
        }
        .image-preview:hover .remove-btn { opacity: 1; }
        .image-preview .main-badge {
            position: absolute; top: 0.25rem; left: 0.25rem;
            background: var(--tref-blue); color: white; font-size: 0.625rem;
            padding: 0.125rem 0.375rem; border-radius: 9999px; font-weight: 600;
        }
        
        /* Preview Card */
        .preview-section { margin-bottom: 1.5rem; }
        .preview-title { font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-muted); }
        .preview-image { width: 100%; aspect-ratio: 16/9; border-radius: var(--radius); object-fit: cover; margin-bottom: 1rem; }
        .preview-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
        .preview-property-title { font-size: 1.25rem; font-weight: 700; }
        .preview-location { font-size: 0.875rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.25rem; }
        .preview-price { font-size: 1.5rem; font-weight: 700; color: var(--tref-blue); }
        .preview-price span { font-size: 0.875rem; font-weight: 400; color: var(--text-muted); }
        .preview-stats { display: flex; gap: 1rem; padding: 1rem; background: var(--bg-secondary); border-radius: var(--radius); margin-bottom: 1rem; }
        .preview-stat { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-muted); }
        .preview-amenities { display: flex; flex-wrap: wrap; gap: 0.5rem; }
        .preview-amenity { background: rgba(0,128,255,0.1); color: var(--tref-blue); padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; }
        .kosher-badge { background: rgba(34,197,94,0.1); color: #16a34a; padding: 0.5rem 1rem; border-radius: var(--radius); font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem; margin-right: 0.5rem; margin-bottom: 0.5rem; }
        
        .alert { padding: 0.75rem 1rem; border-radius: var(--radius); margin-bottom: 1rem; font-size: 0.875rem; }
        .alert-error { background: rgba(239,68,68,0.1); color: var(--error); border: 1px solid rgba(239,68,68,0.2); }
    </style>
</head>
<body>
    <div class="auth-card" id="authCard">
        <div class="auth-header">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/tref-logo.png') }}" alt="Tref Stays" class="auth-logo">
            </a>
            <h1 class="auth-title" id="authTitle">Welcome</h1>
            <p class="auth-subtitle" id="authSubtitle">How would you like to use our platform?</p>
        </div>
        <div class="auth-content">
            <!-- Errors are now displayed inline below each field -->
            
            <!-- Step 0: Role Selection -->
            <div id="step0">
                <div class="role-grid">
                    <label class="role-option" id="roleRenter">
                        <input type="radio" name="role" value="renter">
                        <svg class="role-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg>
                        <span class="role-title">Renter</span>
                        <span class="role-desc">Looking for vacation rentals</span>
                    </label>
                    <label class="role-option" id="roleOwner">
                        <input type="radio" name="role" value="owner">
                        <svg class="role-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <span class="role-title">Property Owner</span>
                        <span class="role-desc">List your property</span>
                    </label>
                </div>
                <button class="btn btn-primary btn-full" id="continueBtn" disabled>
                    Continue
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
                <div class="auth-footer">
                    Already have an account? <a href="{{ route('login') }}">Sign In</a>
                </div>
            </div>
            
            <!-- Step 1: Renter Signup -->
            <div id="stepRenter" class="hidden">
                <form method="POST" action="{{ route('register') }}" id="renterForm">
                    @csrf
                    <input type="hidden" name="role_id" value="renter">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-input {{ $errors->has('first_name') ? 'error' : '' }}" placeholder="John" required data-validate="required" value="{{ old('first_name') }}">
                            <div class="error-text" id="error-first_name" style="{{ $errors->has('first_name') ? 'display:block;' : '' }}">{{ $errors->first('first_name') }}</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-input {{ $errors->has('last_name') ? 'error' : '' }}" placeholder="Doe" required data-validate="required" value="{{ old('last_name') }}">
                            <div class="error-text" id="error-last_name" style="{{ $errors->has('last_name') ? 'display:block;' : '' }}">{{ $errors->first('last_name') }}</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}" placeholder="your@email.com" required data-validate="email" value="{{ old('email') }}">
                        <div class="error-text" id="error-email" style="{{ $errors->has('email') ? 'display:block;' : '' }}">{{ $errors->first('email') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone_number" class="form-input {{ $errors->has('phone_number') ? 'error' : '' }}" placeholder="+1 (555) 000-0000" required data-validate="phone" value="{{ old('phone_number') }}">
                        <div class="error-text" id="error-phone_number" style="{{ $errors->has('phone_number') ? 'display:block;' : '' }}">{{ $errors->first('phone_number') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">PIN (4-6 digits)</label>
                        <input type="text" name="pin" class="form-input {{ $errors->has('pin') ? 'error' : '' }}" placeholder="1234" required data-validate="pin" maxlength="6" value="{{ old('pin') }}">
                        <div class="error-text" id="error-pin" style="{{ $errors->has('pin') ? 'display:block;' : '' }}">{{ $errors->first('pin') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}" placeholder="••••••••" required data-validate="password">
                        <div class="error-text" id="error-password" style="{{ $errors->has('password') ? 'display:block;' : '' }}">{{ $errors->first('password') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-input {{ $errors->has('password_confirmation') ? 'error' : '' }}" placeholder="••••••••" required data-validate="confirm_password">
                        <div class="error-text" id="error-password_confirmation" style="{{ $errors->has('password_confirmation') ? 'display:block;' : '' }}">{{ $errors->first('password_confirmation') }}</div>
                    </div>
                    @if($errors->has('role_id'))
                    <div class="error-text" style="display:block;margin-bottom:1rem;">{{ $errors->first('role_id') }}</div>
                    @endif
                    <div class="btn-row">
                        <button type="button" class="btn btn-outline" onclick="goToStep(0)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                            Back
                        </button>
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </div>
                </form>
            </div>
            
            <!-- Owner Wizard Steps -->
            <form id="ownerForm" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="hidden" name="role" value="owner">
                
                <!-- Progress Bar -->
                <div class="progress-bar" id="progressBar">
                    <div class="progress-step active"></div>
                    <div class="progress-step"></div>
                    <div class="progress-step"></div>
                    <div class="progress-step"></div>
                    <div class="progress-step"></div>
                    <div class="progress-step"></div>
                    <div class="progress-step"></div>
                </div>
                
                <!-- Owner Step 1: Account Info -->
                <div id="ownerStep1">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-input {{ $errors->has('first_name') ? 'error' : '' }}" placeholder="John" required data-validate="required" value="{{ old('first_name') }}">
                            <div class="error-text" id="error-owner-first_name" style="{{ $errors->has('first_name') ? 'display:block;' : '' }}">{{ $errors->first('first_name') }}</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-input {{ $errors->has('last_name') ? 'error' : '' }}" placeholder="Doe" required data-validate="required" value="{{ old('last_name') }}">
                            <div class="error-text" id="error-owner-last_name" style="{{ $errors->has('last_name') ? 'display:block;' : '' }}">{{ $errors->first('last_name') }}</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}" placeholder="your@email.com" required data-validate="email" value="{{ old('email') }}">
                        <div class="error-text" id="error-owner-email" style="{{ $errors->has('email') ? 'display:block;' : '' }}">{{ $errors->first('email') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone_number" class="form-input {{ $errors->has('phone_number') ? 'error' : '' }}" placeholder="+1 (555) 000-0000" required data-validate="phone" value="{{ old('phone_number') }}">
                        <div class="error-text" id="error-owner-phone_number" style="{{ $errors->has('phone_number') ? 'display:block;' : '' }}">{{ $errors->first('phone_number') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">PIN (4-6 digits)</label>
                        <input type="text" name="pin" class="form-input {{ $errors->has('pin') ? 'error' : '' }}" placeholder="1234" required data-validate="pin" maxlength="6" value="{{ old('pin') }}">
                        <div class="error-text" id="error-owner-pin" style="{{ $errors->has('pin') ? 'display:block;' : '' }}">{{ $errors->first('pin') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}" placeholder="••••••••" required data-validate="password">
                        <div class="error-text" id="error-owner-password" style="{{ $errors->has('password') ? 'display:block;' : '' }}">{{ $errors->first('password') }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-input {{ $errors->has('password_confirmation') ? 'error' : '' }}" placeholder="••••••••" required data-validate="confirm_password">
                        <div class="error-text" id="error-owner-password_confirmation" style="{{ $errors->has('password_confirmation') ? 'display:block;' : '' }}">{{ $errors->first('password_confirmation') }}</div>
                    </div>
                </div>
                
                <!-- Owner Step 2: Property Basics -->
                <div id="ownerStep2" class="hidden">
                    <div class="form-group">
                        <label class="form-label">Property Title</label>
                        <input type="text" name="property_title" class="form-input" placeholder="Beautiful Lakefront Cottage">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Property Type</label>
                        <select name="property_type" class="form-select">
                            <option value="">Select property type</option>
                            <option value="apartment">Apartment</option>
                            <option value="house">House</option>
                            <option value="condo">Condo</option>
                            <option value="townhouse">Townhouse</option>
                            <option value="villa">Villa</option>
                            <option value="cottage">Cottage</option>
                            <option value="cabin">Cabin</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Bedrooms</label>
                            <input type="number" name="bedrooms" class="form-input" value="1" min="1">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Bathrooms</label>
                            <input type="number" name="bathrooms" class="form-input" value="1" min="1">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Max Guests</label>
                            <input type="number" name="max_guests" class="form-input" value="2" min="1">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Currency</label>
                            <select name="currency" class="form-select">
                                <option value="USD">🇺🇸 USD ($)</option>
                                <option value="CAD">🇨🇦 CAD (CA$)</option>
                                <option value="GBP">🇬🇧 GBP (£)</option>
                                <option value="EUR">🇪🇺 EUR (€)</option>
                                <option value="ILS">🇮🇱 ILS (₪)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Price per Night</label>
                            <input type="number" name="price_per_night" class="form-input" placeholder="150" min="0">
                        </div>
                    </div>
                </div>
                
                <!-- Owner Step 3: Location -->
                <div id="ownerStep3" class="hidden">
                    <div class="form-group">
                        <label class="form-label">Street Address</label>
                        <input type="text" name="address" class="form-input" placeholder="123 Main Street">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-input" placeholder="Lakewood">
                        </div>
                        <div class="form-group">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-input" placeholder="New Jersey">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <select name="country" class="form-select">
                                <option value="">Select country</option>
                                <option value="us">🇺🇸 United States</option>
                                <option value="ca">🇨🇦 Canada</option>
                                <option value="uk">🇬🇧 United Kingdom</option>
                                <option value="be">🇧🇪 Belgium</option>
                                <option value="il">🇮🇱 Israel</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Zipcode</label>
                            <input type="text" name="zipcode" class="form-input" placeholder="08701">
                        </div>
                    </div>
                </div>
                
                <!-- Owner Step 4: Photos -->
                <div id="ownerStep4" class="hidden">
                    <div class="form-group">
                        <label class="form-label">Property Photos (Max 10)</label>
                        <p style="font-size:0.8125rem;color:var(--text-muted);margin-bottom:0.75rem;">Upload images and click on one to set it as the main photo</p>
                        <div class="upload-area" onclick="document.getElementById('imageUpload').click()">
                            <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            <p class="upload-text">Click to upload or drag and drop<br><span style="font-size:0.75rem;">PNG, JPG up to 10MB each</span></p>
                        </div>
                        <input type="file" id="imageUpload" name="images[]" multiple accept="image/*" style="display:none;" onchange="handleImageUpload(this)">
                        <div class="image-grid" id="imagePreviewGrid"></div>
                    </div>
                </div>
                
                <!-- Owner Step 5: Description -->
                <div id="ownerStep5" class="hidden">
                    <div class="form-group">
                        <label class="form-label">Property Description</label>
                        <textarea name="description" class="form-textarea" placeholder="Describe your property in detail. Mention special features, nearby attractions, and what makes it unique..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Amenities</label>
                        <div class="checkbox-group">
                            @foreach(['WiFi', 'Air Conditioning', 'Heating', 'Kitchen', 'Washer', 'Dryer', 'Free Parking', 'Pool', 'Hot Tub', 'Gym', 'TV', 'Workspace'] as $amenity)
                            <label class="checkbox-item">
                                <input type="checkbox" name="amenities[]" value="{{ $amenity }}">
                                {{ $amenity }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Owner Step 6: Kosher Amenities -->
                <div id="ownerStep6" class="hidden">
                    <div class="feature-box">
                        <input type="checkbox" name="kosher_kitchen" value="1">
                        <label>Kosher Kitchen Available</label>
                    </div>
                    <div class="feature-box">
                        <input type="checkbox" name="shabbos_friendly" value="1">
                        <label>Shabbos Friendly</label>
                    </div>
                    <h4 style="margin:1.5rem 0 1rem;font-size:0.9375rem;">Nearby Shul</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Shul Name</label>
                            <input type="text" name="nearby_shul" class="form-input" placeholder="Beth Israel Synagogue">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Distance</label>
                            <input type="text" name="nearby_shul_distance" class="form-input" placeholder="5 min walk">
                        </div>
                    </div>
                    <h4 style="margin:1rem 0;font-size:0.9375rem;">Nearby Kosher Shops</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Shop Name</label>
                            <input type="text" name="nearby_kosher_shops" class="form-input" placeholder="Kosher Market">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Distance</label>
                            <input type="text" name="nearby_kosher_shops_distance" class="form-input" placeholder="10 min walk">
                        </div>
                    </div>
                    <h4 style="margin:1rem 0;font-size:0.9375rem;">Nearby Mikva</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Mikva Name</label>
                            <input type="text" name="nearby_mikva" class="form-input" placeholder="Community Mikva">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Distance</label>
                            <input type="text" name="nearby_mikva_distance" class="form-input" placeholder="3 min walk">
                        </div>
                    </div>
                </div>
                
                <!-- Owner Step 7: Preview -->
                <div id="ownerStep7" class="hidden">
                    <div style="text-align:center;margin-bottom:1.5rem;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--tref-blue)" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <h3 style="font-size:1.125rem;font-weight:600;margin-top:0.5rem;">Preview Your Listing</h3>
                        <p style="font-size:0.875rem;color:var(--text-muted);">Review how your property will appear to renters</p>
                    </div>
                    <div id="previewContent">
                        <!-- Preview will be populated by JS -->
                    </div>
                </div>
                
                <!-- Navigation Buttons -->
                <div class="btn-row" id="ownerNavBtns">
                    <button type="button" class="btn btn-outline" onclick="prevOwnerStep()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                        Back
                    </button>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextOwnerStep()">
                        Next
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let selectedRole = null;
        let currentOwnerStep = 1;
        const totalOwnerSteps = 7;
        let uploadedImages = [];
        let mainImageIndex = 0;
        
        // Role selection
        document.getElementById('roleRenter').addEventListener('click', function() {
            selectedRole = 'renter';
            this.classList.add('active');
            document.getElementById('roleOwner').classList.remove('active');
            document.getElementById('continueBtn').disabled = false;
        });
        
        document.getElementById('roleOwner').addEventListener('click', function() {
            selectedRole = 'owner';
            this.classList.add('active');
            document.getElementById('roleRenter').classList.remove('active');
            document.getElementById('continueBtn').disabled = false;
        });
        
        document.getElementById('continueBtn').addEventListener('click', function() {
            if (selectedRole === 'renter') {
                goToStep('renter');
            } else if (selectedRole === 'owner') {
                goToStep('owner');
            }
        });
        
        function goToStep(step) {
            document.getElementById('step0').classList.add('hidden');
            document.getElementById('stepRenter').classList.add('hidden');
            document.getElementById('ownerForm').classList.add('hidden');
            
            if (step === 0) {
                document.getElementById('step0').classList.remove('hidden');
                document.getElementById('authTitle').textContent = 'Welcome';
                document.getElementById('authSubtitle').textContent = 'How would you like to use our platform?';
                document.getElementById('authCard').classList.remove('wide');
            } else if (step === 'renter') {
                document.getElementById('stepRenter').classList.remove('hidden');
                document.getElementById('authTitle').textContent = 'Create Account';
                document.getElementById('authSubtitle').textContent = 'Sign up as a Renter';
                document.getElementById('authCard').classList.remove('wide');
            } else if (step === 'owner') {
                document.getElementById('ownerForm').classList.remove('hidden');
                document.getElementById('authCard').classList.add('wide');
                currentOwnerStep = 1;
                showOwnerStep(1);
            }
        }
        
        function showOwnerStep(step) {
            currentOwnerStep = step;
            
            // Update title
            const stepTitles = ['', 'Account Info', 'Property Basics', 'Location', 'Photos', 'Description', 'Kosher Amenities', 'Preview'];
            document.getElementById('authTitle').textContent = 'List Your Property';
            document.getElementById('authSubtitle').textContent = `Step ${step} of ${totalOwnerSteps}: ${stepTitles[step]}`;
            
            // Update progress bar
            const progressSteps = document.querySelectorAll('.progress-step');
            progressSteps.forEach((el, idx) => {
                el.classList.toggle('active', idx < step);
            });
            
            // Show/hide steps
            for (let i = 1; i <= totalOwnerSteps; i++) {
                const stepEl = document.getElementById('ownerStep' + i);
                if (stepEl) stepEl.classList.toggle('hidden', i !== step);
            }
            
            // Update button text
            const nextBtn = document.getElementById('nextBtn');
            if (step === totalOwnerSteps) {
                nextBtn.innerHTML = 'Complete Listing <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>';
            } else {
                nextBtn.innerHTML = 'Next <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>';
            }
            
            // Generate preview on last step
            if (step === totalOwnerSteps) {
                generatePreview();
            }
        }
        
        function nextOwnerStep() {
            // Validate current step before proceeding
            const currentStepDiv = document.getElementById('ownerStep' + currentOwnerStep);
            const inputs = currentStepDiv.querySelectorAll('input[data-validate]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!validateField(input)) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                // Scroll to first error
                const firstError = currentStepDiv.querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }
            
            if (currentOwnerStep < totalOwnerSteps) {
                showOwnerStep(currentOwnerStep + 1);
            } else {
                // Submit form
                document.getElementById('ownerForm').submit();
            }
        }
        
        function prevOwnerStep() {
            if (currentOwnerStep > 1) {
                showOwnerStep(currentOwnerStep - 1);
            } else {
                goToStep(0);
            }
        }
        
        // Image upload handling
        function handleImageUpload(input) {
            const files = Array.from(input.files);
            if (uploadedImages.length + files.length > 10) {
                alert('Maximum 10 images allowed');
                return;
            }
            
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    uploadedImages.push({ file: file, url: e.target.result });
                    renderImagePreviews();
                };
                reader.readAsDataURL(file);
            });
        }
        
        function renderImagePreviews() {
            const grid = document.getElementById('imagePreviewGrid');
            grid.innerHTML = uploadedImages.map((img, idx) => `
                <div class="image-preview ${idx === mainImageIndex ? 'main' : ''}" onclick="setMainImage(${idx})">
                    <img src="${img.url}" alt="Preview">
                    ${idx === mainImageIndex ? '<span class="main-badge">★ Main</span>' : ''}
                    <button type="button" class="remove-btn" onclick="event.stopPropagation(); removeImage(${idx})">✕</button>
                </div>
            `).join('');
        }
        
        function setMainImage(idx) {
            mainImageIndex = idx;
            renderImagePreviews();
        }
        
        function removeImage(idx) {
            uploadedImages.splice(idx, 1);
            if (mainImageIndex >= uploadedImages.length) {
                mainImageIndex = Math.max(0, uploadedImages.length - 1);
            }
            renderImagePreviews();
        }
        
        function generatePreview() {
            const form = document.getElementById('ownerForm');
            const formData = new FormData(form);
            
            const title = formData.get('property_title') || 'Your Property Title';
            const city = formData.get('city') || 'City';
            const state = formData.get('state') || 'State';
            const country = formData.get('country') || '';
            const price = formData.get('price_per_night') || '0';
            const currency = formData.get('currency') || 'USD';
            const bedrooms = formData.get('bedrooms') || '1';
            const bathrooms = formData.get('bathrooms') || '1';
            const maxGuests = formData.get('max_guests') || '2';
            const description = formData.get('description') || 'No description provided';
            const amenities = formData.getAll('amenities[]');
            const kosherKitchen = formData.get('kosher_kitchen');
            const shabbosFriendly = formData.get('shabbos_friendly');
            
            const currencySymbols = { USD: '$', CAD: 'CA$', GBP: '£', EUR: '€', ILS: '₪' };
            const symbol = currencySymbols[currency] || '$';
            
            const mainImage = uploadedImages[mainImageIndex]?.url || 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800';
            
            document.getElementById('previewContent').innerHTML = `
                <img src="${mainImage}" alt="Property" class="preview-image">
                <div class="preview-header">
                    <div>
                        <div class="preview-property-title">${title}</div>
                        <div class="preview-location">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            ${city}, ${state}
                        </div>
                    </div>
                    <div class="preview-price">${symbol}${price}<span>/night</span></div>
                </div>
                <div class="preview-stats">
                    <div class="preview-stat">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/></svg>
                        ${bedrooms} bed${bedrooms > 1 ? 's' : ''}
                    </div>
                    <div class="preview-stat">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/></svg>
                        ${bathrooms} bath${bathrooms > 1 ? 's' : ''}
                    </div>
                    <div class="preview-stat">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        ${maxGuests} guest${maxGuests > 1 ? 's' : ''}
                    </div>
                </div>
                <p style="margin-bottom:1rem;color:var(--text-muted);font-size:0.875rem;">${description}</p>
                ${kosherKitchen || shabbosFriendly ? `
                <div style="margin-bottom:1rem;">
                    ${kosherKitchen ? '<span class="kosher-badge">🍽️ Kosher Kitchen</span>' : ''}
                    ${shabbosFriendly ? '<span class="kosher-badge">✡️ Shabbos Friendly</span>' : ''}
                </div>
                ` : ''}
                ${amenities.length > 0 ? `
                <div class="preview-amenities">
                    ${amenities.map(a => `<span class="preview-amenity">${a}</span>`).join('')}
                </div>
                ` : ''}
            `;
        }
        
        // Inline Validation
        function validateField(input) {
            const name = input.name;
            const value = input.value.trim();
            const validateType = input.getAttribute('data-validate');
            const form = input.closest('form');
            const prefix = form.id === 'ownerForm' ? 'owner-' : '';
            const errorElement = document.getElementById(`error-${prefix}${name}`);
            
            let errorMessage = '';
            
            // Remove error styling first
            input.classList.remove('error');
            
            if (!validateType) return true;
            
            // Required field
            if (validateType.includes('required') && !value) {
                errorMessage = 'This field is required';
            }
            
            // Email validation
            else if (validateType === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    errorMessage = 'Please enter a valid email address';
                }
            }
            
            // Phone validation
            else if (validateType === 'phone' && value) {
                const phoneRegex = /^\+?[\d\s\-()]+$/;
                const digitsOnly = value.replace(/\D/g, '');
                if (!phoneRegex.test(value) || digitsOnly.length < 10 || digitsOnly.length > 15) {
                    errorMessage = 'Please enter a valid phone number (10-15 digits)';
                }
            }
            
            // PIN validation
            else if (validateType === 'pin' && value) {
                const pinRegex = /^\d{4,6}$/;
                if (!pinRegex.test(value)) {
                    errorMessage = 'PIN must be 4-6 digits';
                }
            }
            
            // Password validation
            else if (validateType === 'password' && value) {
                if (value.length < 6) {
                    errorMessage = 'Password must be at least 6 characters';
                }
            }
            
            // Confirm password validation
            else if (validateType === 'confirm_password' && value) {
                const passwordInput = form.querySelector('input[name=\"password\"]');
                if (value !== passwordInput.value) {
                    errorMessage = 'Passwords do not match';
                }
            }
            
            // Show/hide error
            if (errorElement) {
                errorElement.textContent = errorMessage;
                errorElement.style.display = errorMessage ? 'block' : 'none';
            }
            
            if (errorMessage) {
                input.classList.add('error');
                return false;
            }
            
            return true;
        }
        
        // Add validation listeners to all inputs
        document.addEventListener('DOMContentLoaded', function() {
            // If there are Laravel validation errors, show the appropriate step
            @if($errors->any() && old('role_id'))
                @if(old('role_id') == '2')
                    // Renter form
                    document.getElementById('step0').classList.add('hidden');
                    document.getElementById('stepRenter').classList.remove('hidden');
                    document.getElementById('authTitle').textContent = 'Create Renter Account';
                    document.getElementById('authSubtitle').textContent = 'Find your perfect vacation rental';
                @elseif(old('role_id') == '1')
                    // Owner form
                    document.getElementById('step0').classList.add('hidden');
                    document.getElementById('ownerForm').classList.remove('hidden');
                    document.getElementById('authCard').classList.add('wide');
                    document.getElementById('authTitle').textContent = 'List Your Property';
                    document.getElementById('authSubtitle').textContent = 'Join thousands of property owners';
                @endif
            @endif
            
            const allInputs = document.querySelectorAll('input[data-validate]');
            
            allInputs.forEach(input => {
                // Validate on blur (when user leaves field)
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                // Validate on input for certain types
                input.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        validateField(this);
                    }
                });
                
                // Real-time validation for password confirmation
                if (input.getAttribute('data-validate') === 'confirm_password') {
                    const passwordInput = input.closest('form').querySelector('input[name=\"password\"]');
                    if (passwordInput) {
                        passwordInput.addEventListener('input', function() {
                            const confirmInput = input.closest('form').querySelector('input[data-validate=\"confirm_password\"]');
                            if (confirmInput && confirmInput.value) {
                                validateField(confirmInput);
                            }
                        });
                    }
                }
            });
            
            // Validate on form submit
            const renterForm = document.getElementById('renterForm');
            if (renterForm) {
                renterForm.addEventListener('submit', function(e) {
                    const inputs = this.querySelectorAll('input[data-validate]');
                    let isValid = true;
                    
                    inputs.forEach(input => {
                        if (!validateField(input)) {
                            isValid = false;
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        this.querySelector('.error')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            }
            
            const ownerForm = document.getElementById('ownerForm');
            if (ownerForm) {
                ownerForm.addEventListener('submit', function(e) {
                    const inputs = this.querySelectorAll('input[data-validate]');
                    let isValid = true;
                    
                    inputs.forEach(input => {
                        if (!validateField(input)) {
                            isValid = false;
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        this.querySelector('.error')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            }
        });
    </script>
</body>
</html>
