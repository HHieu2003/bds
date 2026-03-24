<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return match ($guard) {
                    'nhanvien' => redirect()->route('nhanvien.dashboard'),
                    'customer' => redirect()->route('frontend.home'),
                    default    => redirect()->route('frontend.home'),
                };
            }
        }

        return $next($request);
    }
}
