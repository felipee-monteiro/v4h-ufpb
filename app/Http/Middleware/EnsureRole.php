<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class EnsureRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole($role)) {
            abort(403);
        }

        return $next($request);
    }
}
