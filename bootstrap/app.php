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
        $middleware->redirectGuestsTo(function (Request $request) {

            if ($request->is('nhan-vien/*') || $request->is('nhan-vien')) {
                return route('nhanvien.login');
            }

            return route('khach-hang.login');
        });

        // Tự động track lượt truy cập frontend (silent, không crash)
        $middleware->append(\App\Http\Middleware\TrackPageView::class);

        $middleware->alias([
            'guest'      => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'check.role' => \App\Http\Middleware\CheckRole::class,
            'checkrole'  => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
