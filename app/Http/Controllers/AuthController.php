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
        ]);

        // Auth::attempt sẽ tự động mã hóa password và so sánh với DB
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Chuyển hướng về Dashboard thay vì home
            return redirect()->route('admin.dashboard');
        }

        // Đăng nhập thất bại -> Quay lại và báo lỗi
        return back()->withErrors([
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
