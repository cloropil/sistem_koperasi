<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Accepts parameter(s) after role: e.g. role:admin or role:admin,user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $allowed = explode(',', $roles);

        if (! in_array($user->role, $allowed, true)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
