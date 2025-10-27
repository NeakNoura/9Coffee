<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckForPrice
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('products/checkout') ||
            $request->is('products/paypal') ||
            $request->is('products/success')) {

          if (!Session::has('price') || Session::get('price') <= 0) {
    Session::put('price', 1.00); // temporary default price for testing
}
        }

        return $next($request);
    }
}
