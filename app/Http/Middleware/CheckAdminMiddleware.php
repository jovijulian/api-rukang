<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class CheckAdminMiddleware extends Middleware
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
        if ($user && in_array($user->isAdmin(), $roleCode)) {
            return $next($request);
        }
        /*
        Super Admin = 1
        Admin Produksi = 2
        Admin Pengiriman = 3
        Admin onsite = 4
        Owner = 5
            */
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
