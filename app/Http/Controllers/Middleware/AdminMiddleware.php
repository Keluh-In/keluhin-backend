<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle request: hanya admin boleh akses
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            return redirect('/login')->withErrors([
                'access' => 'Akses ditolak, hanya admin'
            ]);
        }

        return $next($request);
    }
}