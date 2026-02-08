<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - IVR System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1f2937;
            --primary-hover: #374151;
            --secondary: #f3f4f6;
            --bg: #ffffff;
            --text: #1f2937;
            --text-muted: #6b7280;
            --border: #d1d5db;
            --error: #ef4444;
            --success: #10b981;
            --radius: 0.5rem;
        }
        
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--text);
            min-height: 100vh;
            display: flex; 
            align-items: center; 
            justify-content: center;
            padding: 2rem;
        }
        
        .admin-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }
        
        .admin-header {
            background: var(--primary);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .admin-logo {
            width: 4rem;
            height: 4rem;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .admin-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .admin-subtitle {
            font-size: 0.875rem;
            opacity: 0.8;
        }
        
        .admin-content {
            padding: 2rem;
        }
        
        .alert {
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        
        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text);
        }
        
        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            border: 2px solid var(--border);
            border-radius: var(--radius);
            transition: all 0.2s;
            background: var(--bg);
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
        }
        
        .form-input.error {
            border-color: var(--error);
        }
        
        .form-error {
            color: var(--error);
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        
        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .form-checkbox input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
        }
        
        .form-checkbox label {
            font-size: 0.875rem;
            color: var(--text-muted);
        }
        
        .admin-btn {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.875rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: var(--radius);
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .admin-btn:hover {
            background: var(--primary-hover);
        }
        
        .admin-btn:disabled {
            background: var(--text-muted);
            cursor: not-allowed;
        }
        
        .admin-footer {
            padding: 1rem 2rem;
            background: var(--secondary);
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <div class="admin-logo">A</div>
            <h1 class="admin-title">Admin Portal</h1>
            <p class="admin-subtitle">IVR Reservation System</p>
        </div>
        
        <div class="admin-content">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert alert-error">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input @error('email') error @enderror" 
                        value="{{ old('email') }}"
                        placeholder="Enter your admin email"
                        required 
                        autofocus
                    >
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input @error('password') error @enderror" 
                        placeholder="Enter your password"
                        required
                    >
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-checkbox">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me on this device</label>
                </div>
                
                <button type="submit" class="admin-btn">
                    Sign In to Admin Panel
                </button>
            </form>
        </div>
        
        <div class="admin-footer">
            <p>&copy; {{ date('Y') }} IVR Reservation System. Admin access only.</p>
        </div>
    </div>
</body>
</html>