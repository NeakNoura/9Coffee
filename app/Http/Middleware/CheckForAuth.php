<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckForAuth
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('admin/login')) {
            if (!Auth::guard('admin')->check()) {
                return redirect()->route('view.login')->with('error', 'Please login to access admin dashboard.');
            }
        }
        return $next($request);
    }
}
