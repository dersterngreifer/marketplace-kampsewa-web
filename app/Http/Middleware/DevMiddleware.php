<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DevMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->type == 1) {
            return $next($request);
        }

        // Jika bukan developer, arahkan ke dashboard customer atau KYC
        if (auth()->user()->type == 0) {
            if (empty(auth()->user()->nomor_identitas)) {
                return redirect()->route('customer.isi-identitas');
            }
            return redirect()->route('dashboard-cust');
        }

        return redirect()->route('landing-page.halaman-beranda');
    }
}
