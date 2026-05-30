<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || ! $user->canAccessAdminPanel()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/admin/login')->withErrors([
                'email' => 'Hanya admin aktif yang boleh mengakses panel ini.',
            ]);
        }

        return $next($request);
    }
}
