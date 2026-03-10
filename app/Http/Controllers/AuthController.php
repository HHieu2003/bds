<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Hiển thị form đăng nhập
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    // 2. Xử lý đăng nhập
    public function login(Request $request)
    {
        // Validate dữ liệu
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Mật khẩu là bắt buộc.',
        ]);

        // Auth::attempt sẽ tự động mã hóa password và so sánh với DB
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            // Chuyển hướng về Dashboard
            return redirect()->intended(route('admin.dashboard'));
        }

        // Đăng nhập thất bại -> Quay lại và báo lỗi
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Email hoặc mật khẩu không chính xác.',
            ]);
    }

    // 3. Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
