<?php

namespace App\Http\Middleware;

use Closure;
use \Symfony\Component\HttpFoundation\Response as ResponseCode;

class AjaxJsonCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->ajax() && $request->isJson() && $request->isXmlHttpRequest()) {
            return $next($request);
        }
        return abort(ResponseCode::HTTP_BAD_REQUEST);
    }
}
