<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
{
    // Exclude admin login and logout routes
    $excluded = ['admin/login', 'admin/logout'];

    if (in_array($request->path(), $excluded)) {
        return $next($request);
    }

    // Check admin login
    if (!Auth::check() || !Auth::user()->is_admin) {
        return redirect('/admin/login')->with('error', 'You must be an admin.');
    }

    return $next($request);
}

}

