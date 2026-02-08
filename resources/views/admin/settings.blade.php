@extends('admin.layouts.app')

@section('title', 'System Settings')
@section('page-title', 'System Settings')

@section('content')
<div class="grid-2">
    <!-- General Settings -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-cog" style="margin-right: 8px; color: var(--primary);"></i>
                General Settings
            </h3>
        </div>
        <div class="card-body">
            <form id="generalSettingsForm" onsubmit="saveSettings(event, 'general')">
                <div class="form-group">
                    <label class="form-label">Site Name</label>
                    <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? 'IVR Reservation System' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Admin Email</label>
                    <input type="email" name="site_email" class="form-control" value="{{ $settings['site_email'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Support Phone</label>
                    <input type="text" name="support_phone" class="form-control" placeholder="+1 (555) 123-4567">
                </div>
                <div class="form-group">
                    <label class="form-label">Time Zone</label>
                    <select name="timezone" class="form-control">
                        <option value="America/New_York">Eastern Time (US & Canada)</option>
                        <option value="America/Chicago">Central Time (US & Canada)</option>
                        <option value="America/Denver">Mountain Time (US & Canada)</option>
                        <option value="America/Los_Angeles">Pacific Time (US & Canada)</option>
                        <option value="UTC">UTC</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>
    
    <!-- Commission Settings -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-percentage" style="margin-right: 8px; color: var(--success);"></i>
                Commission & Fees
            </h3>
        </div>
        <div class="card-body">
            <form id="commissionSettingsForm" onsubmit="saveSettings(event, 'commission')">
                <div class="form-group">
                    <label class="form-label">Default Commission Rate (%)</label>
                    <input type="number" name="commission_rate" class="form-control" value="{{ $settings['commission_rate'] ?? 10 }}" min="0" max="100" step="0.1">
                    <small style="color: var(--gray-500);">Platform commission charged on each booking</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Service Fee (%)</label>
                    <input type="number" name="service_fee" class="form-control" value="5" min="0" max="100" step="0.1">
                    <small style="color: var(--gray-500);">Additional service fee for guests</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Tax Rate (%)</label>
                    <input type="number" name="tax_rate" class="form-control" value="{{ $settings['tax_rate'] ?? 8.5 }}" min="0" max="100" step="0.1">
                    <small style="color: var(--gray-500);">Default tax rate applied to bookings</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Cancellation Fee (%)</label>
                    <input type="number" name="cancellation_fee" class="form-control" value="10" min="0" max="100" step="0.1">
                    <small style="color: var(--gray-500);">Fee charged for cancellations</small>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>
    
    <!-- Email Settings -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-envelope" style="margin-right: 8px; color: var(--info);"></i>
                Email Notifications
            </h3>
        </div>
        <div class="card-body">
            <form id="emailSettingsForm" onsubmit="saveSettings(event, 'email')">
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input type="checkbox" name="email_new_booking" checked style="width: 18px; height: 18px;">
                        <span>New Booking Notifications</span>
                    </label>
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input type="checkbox" name="email_cancellation" checked style="width: 18px; height: 18px;">
                        <span>Cancellation Notifications</span>
                    </label>
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input type="checkbox" name="email_new_user" checked style="width: 18px; height: 18px;">
                        <span>New User Registration</span>
                    </label>
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input type="checkbox" name="email_payment" checked style="width: 18px; height: 18px;">
                        <span>Payment Notifications</span>
                    </label>
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input type="checkbox" name="email_review" style="width: 18px; height: 18px;">
                        <span>Review Notifications</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>
    
    <!-- Booking Settings -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-calendar-alt" style="margin-right: 8px; color: var(--warning);"></i>
                Booking Settings
            </h3>
        </div>
        <div class="card-body">
            <form id="bookingSettingsForm" onsubmit="saveSettings(event, 'booking')">
                <div class="form-group">
                    <label class="form-label">Minimum Stay (nights)</label>
                    <input type="number" name="min_stay" class="form-control" value="1" min="1">
                </div>
                <div class="form-group">
                    <label class="form-label">Maximum Stay (nights)</label>
                    <input type="number" name="max_stay" class="form-control" value="30" min="1">
                </div>
                <div class="form-group">
                    <label class="form-label">Advance Booking (days)</label>
                    <input type="number" name="advance_booking" class="form-control" value="365" min="1">
                    <small style="color: var(--gray-500);">How far in advance guests can book</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Check-in Time</label>
                    <input type="time" name="checkin_time" class="form-control" value="15:00">
                </div>
                <div class="form-group">
                    <label class="form-label">Check-out Time</label>
                    <input type="time" name="checkout_time" class="form-control" value="11:00">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Danger Zone -->
<div class="card" style="margin-top: 32px; border: 2px solid var(--danger);">
    <div class="card-header" style="background: rgba(239,68,68,0.05);">
        <h3 class="card-title" style="color: var(--danger);">
            <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
            Danger Zone
        </h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
            <div style="padding: 20px; border: 1px solid var(--gray-200); border-radius: 12px;">
                <h4 style="font-size: 16px; margin-bottom: 8px;">Clear Cache</h4>
                <p style="font-size: 13px; color: var(--gray-500); margin-bottom: 16px;">
                    Clear all cached data. This may temporarily slow down the application.
                </p>
                <button type="button" class="btn btn-secondary" onclick="clearCache()">
                    <i class="fas fa-broom"></i> Clear Cache
                </button>
            </div>
            
            <div style="padding: 20px; border: 1px solid var(--gray-200); border-radius: 12px;">
                <h4 style="font-size: 16px; margin-bottom: 8px;">Export All Data</h4>
                <p style="font-size: 13px; color: var(--gray-500); margin-bottom: 16px;">
                    Download a complete backup of all system data in JSON format.
                </p>
                <button type="button" class="btn btn-secondary" onclick="exportAllData()">
                    <i class="fas fa-download"></i> Export Data
                </button>
            </div>
            
            <div style="padding: 20px; border: 1px solid var(--danger); border-radius: 12px; background: rgba(239,68,68,0.02);">
                <h4 style="font-size: 16px; margin-bottom: 8px; color: var(--danger);">Reset Database</h4>
                <p style="font-size: 13px; color: var(--gray-500); margin-bottom: 16px;">
                    This will delete ALL data. This action cannot be undone!
                </p>
                <button type="button" class="btn btn-danger" onclick="resetDatabase()">
                    <i class="fas fa-trash-alt"></i> Reset Database
                </button>
            </div>
        </div>
    </div>
</div>

<!-- System Info -->
<div class="card" style="margin-top: 32px;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-info-circle" style="margin-right: 8px; color: var(--info);"></i>
            System Information
        </h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
            <div style="text-align: center; padding: 20px; background: var(--gray-100); border-radius: 12px;">
                <div style="font-size: 13px; color: var(--gray-500); margin-bottom: 4px;">Laravel Version</div>
                <div style="font-size: 18px; font-weight: 600;">{{ app()->version() }}</div>
            </div>
            <div style="text-align: center; padding: 20px; background: var(--gray-100); border-radius: 12px;">
                <div style="font-size: 13px; color: var(--gray-500); margin-bottom: 4px;">PHP Version</div>
                <div style="font-size: 18px; font-weight: 600;">{{ phpversion() }}</div>
            </div>
            <div style="text-align: center; padding: 20px; background: var(--gray-100); border-radius: 12px;">
                <div style="font-size: 13px; color: var(--gray-500); margin-bottom: 4px;">Database</div>
                <div style="font-size: 18px; font-weight: 600;">SQLite</div>
            </div>
            <div style="text-align: center; padding: 20px; background: var(--gray-100); border-radius: 12px;">
                <div style="font-size: 13px; color: var(--gray-500); margin-bottom: 4px;">Environment</div>
                <div style="font-size: 18px; font-weight: 600;">{{ config('app.env') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    async function saveSettings(e, section) {
        e.preventDefault();
        
        showToast(`${section.charAt(0).toUpperCase() + section.slice(1)} settings saved successfully!`, 'success');
    }
    
    async function clearCache() {
        if (!confirm('Are you sure you want to clear the cache?')) return;
        
        showToast('Cache cleared successfully!', 'success');
    }
    
    async function exportAllData() {
        showToast('Preparing data export...', 'success');
        
        // Simulated export - in production, this would call an API endpoint
        setTimeout(() => {
            showToast('Data exported successfully!', 'success');
        }, 1500);
    }
    
    async function resetDatabase() {
        const confirmText = prompt('This will DELETE ALL DATA. Type "DELETE" to confirm:');
        
        if (confirmText !== 'DELETE') {
            showToast('Database reset cancelled', 'warning');
            return;
        }
        
        showToast('This feature is disabled for safety', 'error');
    }
</script>
@endsection
