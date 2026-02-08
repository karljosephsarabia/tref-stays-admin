<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Details - Tref Stays</title>
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
            max-width: 800px;
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
        
        .card {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .inquiry-header {
            display: flex;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .inquiry-avatar {
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
            background: var(--tref-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 600;
            flex-shrink: 0;
        }
        
        .inquiry-info h2 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .inquiry-contact {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 0.5rem;
        }
        
        .inquiry-contact a {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.875rem;
            color: var(--tref-blue);
            text-decoration: none;
        }
        
        .inquiry-contact a:hover {
            text-decoration: underline;
        }
        
        .inquiry-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-new { background: rgba(239,68,68,0.1); color: var(--danger); }
        .status-read { background: rgba(245,158,11,0.1); color: var(--warning); }
        .status-responded { background: rgba(22,163,74,0.1); color: var(--success); }
        
        .detail-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border);
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            width: 140px;
            font-size: 0.875rem;
            color: var(--text-muted);
            flex-shrink: 0;
        }
        
        .detail-value {
            font-size: 0.9375rem;
        }
        
        .message-box {
            background: var(--bg-secondary);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-top: 1rem;
        }
        
        .message-box p {
            white-space: pre-wrap;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
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
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-outline {
            background: white;
            color: var(--text);
            border: 1px solid var(--border);
        }
        
        .btn-outline:hover {
            background: var(--bg-secondary);
        }
        
        .actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            background: white;
            font-family: inherit;
        }
        
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--tref-blue);
            box-shadow: 0 0 0 3px rgba(0,128,255,0.1);
        }
        
        .form-textarea {
            min-height: 100px;
            resize: vertical;
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
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('owner.inquiries') }}" class="back-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            Back to Inquiries
        </a>
        
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <!-- Lead Info -->
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Lead Details</h1>
                <span class="inquiry-status status-{{ $inquiry->status }}">{{ $inquiry->status }}</span>
            </div>
            <div class="card-body">
                <div class="inquiry-header">
                    <div class="inquiry-avatar">{{ strtoupper(substr($inquiry->name, 0, 1)) }}</div>
                    <div class="inquiry-info">
                        <h2>{{ $inquiry->name }}</h2>
                        <div class="inquiry-contact">
                            <a href="mailto:{{ $inquiry->email }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                {{ $inquiry->email }}
                            </a>
                            @if($inquiry->phone)
                            <a href="tel:{{ $inquiry->phone }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                {{ $inquiry->phone }}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Property</span>
                    <span class="detail-value">{{ $inquiry->property->title ?? 'Unknown' }}</span>
                </div>
                
                @if($inquiry->check_in_date)
                <div class="detail-row">
                    <span class="detail-label">Check-in</span>
                    <span class="detail-value">{{ $inquiry->check_in_date->format('M d, Y') }}</span>
                </div>
                @endif
                
                @if($inquiry->check_out_date)
                <div class="detail-row">
                    <span class="detail-label">Check-out</span>
                    <span class="detail-value">{{ $inquiry->check_out_date->format('M d, Y') }}</span>
                </div>
                @endif
                
                @if($inquiry->guests)
                <div class="detail-row">
                    <span class="detail-label">Guests</span>
                    <span class="detail-value">{{ $inquiry->guests }}</span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-label">Received</span>
                    <span class="detail-value">{{ $inquiry->created_at->format('M d, Y \a\t g:i A') }}</span>
                </div>
                
                <div class="message-box">
                    <p>{{ $inquiry->message }}</p>
                </div>
                
                <div class="actions" style="margin-top: 1.5rem;">
                    <a href="mailto:{{ $inquiry->email }}?subject=Re: Inquiry about {{ $inquiry->property->title ?? 'your property' }}" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        Reply via Email
                    </a>
                    @if($inquiry->phone)
                    <a href="tel:{{ $inquiry->phone }}" class="btn btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        Call
                    </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Update Status -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Update Status</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('owner.inquiry.update', $inquiry->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="new" {{ $inquiry->status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="read" {{ $inquiry->status === 'read' ? 'selected' : '' }}>Read</option>
                            <option value="responded" {{ $inquiry->status === 'responded' ? 'selected' : '' }}>Responded</option>
                            <option value="converted" {{ $inquiry->status === 'converted' ? 'selected' : '' }}>Converted to Booking</option>
                            <option value="archived" {{ $inquiry->status === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Your Notes (private)</label>
                        <textarea name="owner_notes" class="form-textarea" placeholder="Add notes about this lead...">{{ $inquiry->owner_notes }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
