<?php

namespace App\Http\Middleware;

use Closure;

class SetHeaders
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
        $headers = getallheaders();

        if(is_array($headers) && isset($headers['Authorization'])) {
            $request->headers->set('Authorization', $headers['Authorization']);
        }
        return $next($request)->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorizations');
    }
}
