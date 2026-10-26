<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active) {
            abort(403, 'Akun tidak aktif.');
        }

        if (! $user->hasRole('super-admin') && ! $user->can('access-admin')) {
            abort(403, 'Akses terbatas untuk administrator.');
        }

        return $next($request);
    }
}
