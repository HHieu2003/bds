<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
    // CẬP NHẬT HỒ SƠ (NHÂN VIÊN ĐANG ĐĂNG NHẬP)
    // ══════════════════════════════
    public function updateProfile(Request $request)
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        if (! $nhanVien) {
            return response()->json([
                'success' => false,
                'message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.',
            ], 401);
        }

        $validated = $request->validate([
            'ho_ten' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('nhan_vien', 'email')->ignore($nhanVien->id)->whereNull('deleted_at'),
            ],
            'so_dien_thoai' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('nhan_vien', 'so_dien_thoai')->ignore($nhanVien->id)->whereNull('deleted_at'),
            ],
            'dia_chi' => ['nullable', 'string', 'max:255'],
        ], [
            'ho_ten.required' => 'Vui lòng nhập họ và tên.',
            'ho_ten.max' => 'Họ và tên không được vượt quá 100 ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 150 ký tự.',
            'email.unique' => 'Email này đã được sử dụng.',
            'so_dien_thoai.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'so_dien_thoai.unique' => 'Số điện thoại này đã được sử dụng.',
            'dia_chi.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
        ]);

        \App\Models\NhanVien::whereKey($nhanVien->id)->update([
            'ho_ten' => trim($validated['ho_ten']),
            'email' => trim($validated['email']),
            'so_dien_thoai' => $validated['so_dien_thoai'] !== null ? trim($validated['so_dien_thoai']) : null,
            'dia_chi' => $validated['dia_chi'] !== null ? trim($validated['dia_chi']) : null,
        ]);

        $fresh = \App\Models\NhanVien::query()->find($nhanVien->id);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật hồ sơ thành công!',
            'nhan_vien' => [
                'ho_ten' => $fresh?->ho_ten,
                'email' => $fresh?->email,
                'so_dien_thoai' => $fresh?->so_dien_thoai,
                'dia_chi' => $fresh?->dia_chi,
                'vai_tro_label' => $fresh?->vai_tro_label,
            ],
        ]);
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
