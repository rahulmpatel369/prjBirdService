<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    const DELIMITER = '|';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {   
        if (!is_array($roles)) {
			$roles = explode(self::DELIMITER, $roles);
        }
        $user = auth()->user();
        if($user != null && $user->hasRole($roles)) return $next($request);
        abort(403);
    }
}
