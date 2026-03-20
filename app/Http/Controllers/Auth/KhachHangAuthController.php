<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class KhachHangAuthController extends Controller
{
    // ── Đăng nhập ──
    public function showLogin()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('frontend.home');
        }
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required'],   // email hoặc SĐT
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('frontend.home'));
        }

        return back()
            ->withErrors(['email' => 'Thông tin đăng nhập không chính xác.'])
            ->withInput($request->only('email'));
    }

    // ── Đăng ký ──
    public function showRegister()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('frontend.home');
        }
        return view('frontend.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'ho_ten'       => ['required', 'string', 'max:100'],
            'so_dien_thoai' => ['required', 'unique:khach_hang,so_dien_thoai'],
            'email'        => ['nullable', 'email', 'unique:khach_hang,email'],
            'password'     => ['required', 'min:6', 'confirmed'],
        ]);

        $khachHang = KhachHang::create([
            'ho_ten'        => $request->ho_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'nguon_khach_hang' => 'website',
        ]);

        Auth::guard('customer')->login($khachHang);
        $request->session()->regenerate();

        return redirect()->route('frontend.home')
            ->with('success', 'Đăng ký thành công! Chào mừng ' . $khachHang->ho_ten);
    }

    // ── OTP ──
    public function sendOtp(Request $request)
    {
        $request->validate([
            'so_dien_thoai' => ['required', 'string'],
        ]);

        // Tạo OTP 6 số
        $otp = rand(100000, 999999);

        // Lưu vào session (thực tế gửi qua SMS API)
        session(['otp' => $otp, 'otp_sdt' => $request->so_dien_thoai]);

        // TODO: Tích hợp SMS API (Twilio, Esms, ...)
        // SmsService::send($request->so_dien_thoai, "Mã OTP của bạn là: $otp");

        return response()->json([
            'success' => true,
            'message' => 'OTP đã được gửi.',
            // DEV ONLY — xoá khi production:
            'otp_dev' => app()->isLocal() ? $otp : null,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'so_dien_thoai' => ['required'],
            'otp'           => ['required', 'digits:6'],
        ]);

        $sessionOtp = session('otp');
        $sessionSdt = session('otp_sdt');

        if (
            $sessionOtp !== (int) $request->otp ||
            $sessionSdt !== $request->so_dien_thoai
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Mã OTP không đúng hoặc đã hết hạn.',
            ], 422);
        }

        session()->forget(['otp', 'otp_sdt']);

        return response()->json([
            'success' => true,
            'message' => 'Xác thực OTP thành công.',
        ]);
    }

    // ── Đăng xuất ──
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('frontend.home')
            ->with('success', 'Đã đăng xuất.');
    }

    // ── Quên mật khẩu (stub) ──
    public function showForgot()
    {
        return view('frontend.auth.forgot');
    }
    public function sendReset()
    {
        return back()->with('success', 'Vui lòng kiểm tra email/SMS.');
    }
    public function showReset()
    {
        return view('frontend.auth.reset');
    }
    public function reset()
    {
        return redirect()->route('khach-hang.login');
    }

    // ── Trang cá nhân (stub) ──
    public function profile()
    {
        return view('frontend.auth.profile');
    }
    public function updateProfile()
    {
        return back()->with('success', 'Đã cập nhật hồ sơ.');
    }
    public function changePassword()
    {
        return back()->with('success', 'Đã đổi mật khẩu.');
    }
    public function lichSuXem()
    {
        return view('frontend.auth.lich_su_xem');
    }
    public function kyGuiCuaToi()
    {
        return view('frontend.auth.ky_gui_cua_toi');
    }
    public function lichHenCuaToi()
    {
        return view('frontend.auth.lich_hen_cua_toi');
    }
}
