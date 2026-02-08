<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In - Tref Stays</title>
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
            max-width: 420px;
            overflow: hidden;
        }
        .auth-header {
            padding: 2rem 2rem 1.5rem;
            text-align: center;
        }
        .auth-logo {
            height: 3rem;
            margin-bottom: 1.5rem;
        }
        .auth-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--tref-blue);
            margin-bottom: 0.5rem;
        }
        .auth-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
        }
        .auth-content {
            padding: 0 2rem 2rem;
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
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--tref-blue);
            box-shadow: 0 0 0 3px rgba(0,128,255,0.1);
        }
        .form-input.error {
            border-color: var(--error);
        }
        .error-text {
            font-size: 0.8125rem;
            color: var(--error);
            margin-top: 0.25rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.75rem 1.5rem;
            font-size: 0.9375rem;
            font-weight: 600;
            border-radius: var(--radius);
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
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: var(--text-muted);
        }
        .auth-footer a {
            color: var(--tref-blue);
            text-decoration: none;
            font-weight: 500;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
        .alert {
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }
        .alert-error {
            background: rgba(239,68,68,0.1);
            color: var(--error);
            border: 1px solid rgba(239,68,68,0.2);
        }
        .alert-success {
            background: rgba(34,197,94,0.1);
            color: #16a34a;
            border: 1px solid rgba(34,197,94,0.2);
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            cursor: pointer;
        }
        .checkbox-label input {
            width: 1rem;
            height: 1rem;
            accent-color: var(--tref-blue);
        }
        .forgot-link {
            font-size: 0.8125rem;
            color: var(--tref-blue);
            text-decoration: none;
            float: right;
        }
        .forgot-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/tref-logo.png') }}" alt="Tref Stays" class="auth-logo">
            </a>
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">Sign in to your account</p>
        </div>
        <div class="auth-content">
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-input @error('email') error @enderror" 
                           value="{{ old('email') }}" placeholder="your@email.com" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">
                        Password
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot?</a>
                    </label>
                    <input type="password" name="password" id="password" class="form-input @error('password') error @enderror" 
                           placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>
            
            <div class="auth-footer">
                Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
            </div>
        </div>
    </div>
</body>
</html>
