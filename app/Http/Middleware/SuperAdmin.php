<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('admin')->user();
        if (!$user || $user->role !== 'superadmin') {
            abort(403, 'Unauthorized: Only Super Admin can access this.');
        }
        return $next($request);
    }
}



