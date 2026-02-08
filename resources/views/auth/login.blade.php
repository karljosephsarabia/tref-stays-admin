<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ trans('auth.login') }} - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon.png') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('/css/tref-stays.css') }}" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        body { margin: 0; padding: 0; background: #f7f7f7; }
    </style>
</head>
<body class="tref-page tref-auth-page">
    
    <!-- Navigation -->
    <nav class="tref-navbar scrolled">
        <div class="tref-navbar-container">
            <a href="{{ url('/') }}" class="tref-logo">
                <img src="{{ asset('/images/logo.png') }}" alt="{{ config('app.name') }}">
                <span class="tref-logo-text">{{ config('app.name') }}</span>
            </a>
            
            <div class="tref-nav-links">
                <a href="{{ route('register') }}" class="tref-nav-link">Sign up</a>
                <a href="#" class="tref-nav-link">Help</a>
            </div>
        </div>
    </nav>
    
    <!-- Auth Container -->
    <div class="tref-auth-container">
        <div class="tref-auth-card">
            <div class="tref-auth-header">
                <h1 class="tref-auth-title">Welcome back</h1>
                <p class="tref-auth-subtitle">Log in to continue to your account</p>
            </div>
            
            <form method="POST" action="{{ url('login') }}">
                @csrf
                
                <div class="tref-form-group">
                    <label class="tref-form-label">{{ trans('auth.email_phone_number') }}</label>
                    <input type="text" name="email" class="tref-form-input @if($errors->has('email')) error @endif" 
                           placeholder="Enter your email or phone" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <p class="tref-form-error">{{ $errors->first('email') }}</p>
                    @endif
                </div>
                
                <div class="tref-form-group">
                    <label class="tref-form-label">{{ trans('user.password') }}</label>
                    <input type="password" name="password" class="tref-form-input @if($errors->has('password')) error @endif" 
                           placeholder="Enter your password" required>
                    @if ($errors->has('password'))
                        <p class="tref-form-error">{{ $errors->first('password') }}</p>
                    @endif
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="remember" style="width: 18px; height: 18px;">
                        <span style="font-size: 14px; color: #484848;">Remember me</span>
                    </label>
                    <a href="{{ url('password/reset') }}" style="font-size: 14px; color: #222; font-weight: 500;">Forgot password?</a>
                </div>
                
                <button type="submit" class="tref-btn tref-btn-primary tref-btn-full">
                    {{ trans('auth.btn_sign_in') }}
                </button>
            </form>
            
            <div class="tref-social-divider">
                <span>or continue with</span>
            </div>
            
            <div class="tref-social-buttons">
                <button type="button" class="tref-social-btn">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Continue with Google
                </button>
                
                <button type="button" class="tref-social-btn">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#1877F2">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Continue with Facebook
                </button>
            </div>
            
            <div class="tref-auth-links">
                Don't have an account? <a href="{{ route('register') }}">{{ trans('auth.btn_sign_up') }}</a>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background: #f7f7f7; padding: 24px; text-align: center; border-top: 1px solid #ddd;">
        <p style="font-size: 14px; color: #717171; margin: 0;">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</body>
</html>