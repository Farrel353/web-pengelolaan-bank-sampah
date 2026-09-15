<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $user = auth()->user();

        if (in_array('admin_or_petugas', $roles) && in_array($user->role, ['admin', 'petugas'])) {
            return $next($request);
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Akses tidak diizinkan untuk peran Anda.');
        }

        return $next($request);
    }
}
