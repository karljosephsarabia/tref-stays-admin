<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Redirect based on role (role_id is stored as integer in database)
                if ($user->role_id == 1 || $user->role_id == 2) { // Admin or Broker
                    return redirect()->route('admin.dashboard');
                } elseif ($user->role_id == 3) { // Owner
                    return redirect()->route('owner.dashboard');
                } elseif ($user->role_id == 5) { // Renter/Customer
                    return redirect()->route('renter.dashboard');
                }
                
                return redirect('/home');
            }
        }

        return $next($request);
    }
}
