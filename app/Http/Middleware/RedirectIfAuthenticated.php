<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                if ($user->type == 1) {
                    return redirect()->route('home.index');
                } elseif ($user->type == 0) {
                    if (empty($user->nomor_identitas)) {
                        return redirect()->route('customer.isi-identitas');
                    }
                    return redirect()->route('dashboard-cust');
                }
                return redirect('/');
            }
        }

        return $next($request);
    }
}
