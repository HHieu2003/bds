<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        // Chưa đăng nhập
        if (! $nhanVien) {
            return redirect()->route('nhanvien.login')
                ->withErrors(['email' => 'Vui lòng đăng nhập để tiếp tục.']);
        }

        // Tài khoản bị khoá
        if (! $nhanVien->kich_hoat) {
            Auth::guard('nhanvien')->logout();
            return redirect()->route('nhanvien.login')
                ->withErrors(['email' => 'Tài khoản đã bị vô hiệu hoá. Liên hệ Admin.']);
        }

        // Không truyền role → cho qua tất cả
        if (empty($roles)) {
            return $next($request);
        }

        // Kiểm tra vai trò
        if (!Auth::guard('nhanvien')->check()) {
            return redirect()->route('nhanvien.login'); // ← redirect về login
        }
        return $next($request);
    }
}
