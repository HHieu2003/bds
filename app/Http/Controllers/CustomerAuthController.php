<?php

namespace App\Http\Controllers;

use App\Models\KhachHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{
    /**
     * Login nhanh: Nhận SĐT hoặc Email -> Tự động đăng nhập
     */
    public function quickLogin(Request $request)
    {
        // 1. Xác định input là Email hay SĐT
        $input = $request->input('contact'); // Trường nhập chung
        $name = $request->input('name', 'Khách hàng');

        if (empty($input)) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng nhập SĐT hoặc Email'], 400);
        }

        $isEmail = filter_var($input, FILTER_VALIDATE_EMAIL);
        $field = $isEmail ? 'email' : 'so_dien_thoai';

        // 2. Tìm hoặc Tạo mới
        // Logic: Nếu tìm thấy thì login, chưa thấy thì tạo mới
        $khachHang = KhachHang::where($field, $input)->first();

        if (!$khachHang) {
            $khachHang = KhachHang::create([
                $field => $input,
                'ho_ten' => $name,
                'is_active' => true
            ]);
        }

        // 3. Đăng nhập ngay (Lazy Login)
        Auth::guard('customer')->login($khachHang);

        return response()->json([
            'status' => 'success',
            'message' => 'Đăng nhập thành công',
            'user' => $khachHang
        ]);
    }

    /**
     * Gửi mã OTP để xác thực (Khi khách bấm vào cảnh báo trên Header)
     */
    public function sendVerificationOtp()
    {
        $user = Auth::guard('customer')->user();
        if (!$user) return response()->json(['status' => 'error'], 401);

        if ($user->isVerified()) {
            return response()->json(['status' => 'success', 'message' => 'Tài khoản đã xác thực.']);
        }

        // Tạo OTP
        $otp = rand(100000, 999999);
        Session::put('verify_otp', $otp);

        // Giả lập gửi (Thực tế bạn check xem user có email hay phone để gửi qua kênh đó)
        $contact = $user->getContactInfo();

        // LOG OTP RA FILE LOG ĐỂ TEST
        \Log::info("OTP Xác thực cho {$contact}: $otp");

        return response()->json([
            'status' => 'success',
            'message' => "Mã OTP đã gửi tới $contact (Check Log: $otp)"
        ]);
    }

    /**
     * Xác nhận OTP
     */
    public function confirmVerificationOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);
        $user = Auth::guard('customer')->user();

        if (!$user) return response()->json(['status' => 'error'], 401);

        $sessionOtp = Session::get('verify_otp');

        if ($request->otp == $sessionOtp) {

            // Cập nhật trường verified_at tương ứng
            if ($user->so_dien_thoai) {
                $user->update(['phone_verified_at' => now()]);
            } elseif ($user->email) {
                $user->update(['email_verified_at' => now()]);
            }

            Session::forget('verify_otp');

            return response()->json(['status' => 'success', 'message' => 'Xác thực tài khoản thành công!']);
        }

        return response()->json(['status' => 'error', 'message' => 'Mã OTP không đúng.'], 400);
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('home');
    }
}
