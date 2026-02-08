<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SMD\Common\ReservationSystem\Enums\RoleType;
use App\RsUser;

class AdminAuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        // Check if user is already logged in as admin (role_id 1 or 2)
        if (Auth::check() && (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find user by email
        $user = RsUser::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Invalid credentials provided.',
            ])->withInput();
        }

        // Check if user is admin (role_id 1 = admin, 2 = broker)
        if ($user->role_id != 1 && $user->role_id != 2) {
            return back()->withErrors([
                'email' => 'Access denied. Admin privileges required.',
            ])->withInput();
        }

        // Check if user is activated
        if (!$user->activated) {
            return back()->withErrors([
                'email' => 'Your account is not activated.',
            ])->withInput();
        }

        // Login the user
        Auth::login($user, $request->filled('remember'));

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Handle admin logout
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }
}