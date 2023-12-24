<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class CheckRoleMiddleware extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next, ...$roleCode)
    {
        $user = auth()->user();
        if ($user && in_array($user->role(), $roleCode)) {
            return $next($request);
        }
        /*
            Customer = 0
            Admin = 1
            Talnet = 2
            */
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}