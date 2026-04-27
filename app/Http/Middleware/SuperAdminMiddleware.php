<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()?->isSuperAdmin()) {
            return redirect()
                ->route('admin.dashboard')
                ->withErrors(['admin' => 'Hanya super admin yang boleh mengakses halaman ini.']);
        }

        return $next($request);
    }
}
