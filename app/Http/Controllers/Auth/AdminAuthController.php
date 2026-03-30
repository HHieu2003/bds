<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    // ══════════════════════════════
    // HIỂN THỊ TRANG LOGIN
    // ══════════════════════════════
    public function showLogin()
    {
        // Đã đăng nhập → về dashboard luôn
        if (Auth::guard('nhanvien')->check()) {
            return redirect()->route('nhanvien.dashboard');
        }

        return view('admin.auth.login');
    }

    // ══════════════════════════════
    // XỬ LÝ ĐĂNG NHẬP
    // ══════════════════════════════
    public function login(Request $request)
    {
        // Validate
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ], [
            'email.required'    => 'Vui lòng nhập email.',
            'email.email'       => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min'      => 'Mật khẩu ít nhất 6 ký tự.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        // Thử đăng nhập
        if (! Auth::guard('nhanvien')->attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.'])
                ->withInput($request->only('email'));
        }

        $nhanVien = Auth::guard('nhanvien')->user();

        // Kiểm tra tài khoản bị khoá
        if (! $nhanVien->kich_hoat) {
            Auth::guard('nhanvien')->logout();
            return back()
                ->withErrors(['email' => 'Tài khoản của bạn đã bị vô hiệu hoá. Vui lòng liên hệ Admin.'])
                ->withInput($request->only('email'));
        }

        // Đăng nhập thành công
        $request->session()->regenerate();

        // Cập nhật mốc đăng nhập cuối để hiển thị trong trang quản lý nhân viên.
        \App\Models\NhanVien::whereKey($nhanVien->id)->update([
            'dang_nhap_cuoi_at' => now(),
        ]);

        return redirect()
            ->intended(route('nhanvien.dashboard'))
            ->with('success', 'Chào mừng ' . $nhanVien->ho_ten . ' quay trở lại!');
    }

    // ══════════════════════════════
    // ĐĂNG XUẤT
    // ══════════════════════════════
    public function logout(Request $request)
    {
        Auth::guard('nhanvien')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('nhanvien.login')
            ->with('success', 'Đã đăng xuất thành công.');
    }

    // ══════════════════════════════
    // ĐỔI MẬT KHẨU (NHÂN VIÊN ĐANG ĐĂNG NHẬP)
    // ══════════════════════════════
    public function changePassword(Request $request)
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        if (! $nhanVien) {
            return response()->json([
                'success' => false,
                'message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.',
            ], 401);
        }

        $validated = $request->validate([
            'mat_khau_cu' => ['required'],
            'mat_khau_moi' => ['required', 'string', 'min:6', 'max:50', 'different:mat_khau_cu', 'confirmed'],
        ], [
            'mat_khau_cu.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'mat_khau_moi.required' => 'Vui lòng nhập mật khẩu mới.',
            'mat_khau_moi.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'mat_khau_moi.max' => 'Mật khẩu mới không được vượt quá 50 ký tự.',
            'mat_khau_moi.different' => 'Mật khẩu mới phải khác mật khẩu hiện tại.',
            'mat_khau_moi.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        if (! Hash::check($validated['mat_khau_cu'], $nhanVien->password)) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'mat_khau_cu' => ['Mật khẩu hiện tại không đúng.'],
                ],
            ], 422);
        }

        \App\Models\NhanVien::whereKey($nhanVien->id)->update([
            'password' => Hash::make($validated['mat_khau_moi']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đổi mật khẩu thành công!',
        ]);
    }
}
