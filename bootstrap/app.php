<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        $middleware->validateCsrfTokens(except: [
            'khach-hang/logout',
            'tai-khoan/dang-xuat',
        ]);
        // ✅ THÊM ĐOẠN NÀY — redirect đúng trang login theo URL
        $middleware->redirectGuestsTo(function (Request $request) {

            // Nếu đang ở trang nhân viên/admin → về login nhân viên
            if ($request->is('nhan-vien/*') || $request->is('nhan-vien')) {
                return route('nhanvien.login');
            }

            // Còn lại (khách hàng, frontend) → về login khách hàng
            return route('khach-hang.login');
        });

        // Các middleware alias (giữ nguyên nếu bạn đang có)
        $middleware->alias([
            'guest'      => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'check.role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
