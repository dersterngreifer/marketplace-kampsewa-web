<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->type == 0) {
            // Syarat menjadi mitra: user harus sudah melengkapi verifikasi identitas (KYC)
            if (empty(auth()->user()->nomor_identitas)) {
                return redirect()->route('customer.isi-identitas');
            }
            return $next($request);
        }
        return back();
    }
}
