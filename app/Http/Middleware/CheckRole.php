<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|array $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!in_array($request->user()->role_id, array_slice(func_get_args(), 2))) {
            return redirect('/home');
        }

        return $next($request);
    }
}
