<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Nếu là Admin thì cho qua hết
        if ($user->role == 'admin') {
            return $next($request);
        }

        // Kiểm tra quyền (roles truyền vào từ route)
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập chức năng này!');
    }
}
