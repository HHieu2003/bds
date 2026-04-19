<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Mail\VerifyEmailMail;
use App\Models\KhachHang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Throwable;

class KhachHangAuthController extends Controller
{
    // ══════════════════════════════════════════════════
    // ĐĂNG NHẬP
    // ══════════════════════════════════════════════════
    public function showLogin()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('frontend.home');
        }
        return redirect()->back()->with('open_auth_modal', 'login');
    }

    public function login(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email'    => ['required'],
            'password' => ['required'],
        ]);

        if ($v->fails()) {
            return response()->json(['success' => false, 'errors' => $v->errors()], 422);
        }

        $kh = KhachHang::where('email', $request->email)->first();

        if (!$kh || !Hash::check($request->password, $kh->password)) {
            return response()->json(['success' => false, 'message' => 'Email hoặc mật khẩu không chính xác.']);
        }

        if (!$kh->kich_hoat) {
            return response()->json(['success' => false, 'message' => 'Tài khoản đã bị vô hiệu hoá. Vui lòng liên hệ hỗ trợ.']);
        }

        // Tuong thich du lieu cu: tai khoan da duoc Admin kich hoat
        // nhung chua co moc thoi gian xac thuc email.
        if (!$kh->email_xac_thuc_at && $kh->kich_hoat && empty($kh->verification_token)) {
            $kh->forceFill(['email_xac_thuc_at' => now()])->save();
            $kh->refresh();
        }

        if (!$kh->email_xac_thuc_at) {
            return response()->json(['success' => false, 'message' => 'Tài khoản chưa xác thực email. Vui lòng kiểm tra hộp thư.']);
        }

        Auth::guard('customer')->login($kh, $request->boolean('remember'));
        $request->session()->regenerate();

        return response()->json(['success' => true, 'message' => 'Đăng nhập thành công!']);
    }

    // ══════════════════════════════════════════════════
    // ĐĂNG KÝ
    // ══════════════════════════════════════════════════
    public function showRegister()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('frontend.home');
        }
        return redirect()->back()->with('open_auth_modal', 'register');
    }

    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'ho_ten'               => ['required', 'string', 'max:100'],
            'so_dien_thoai'        => ['nullable', 'string', 'max:15'],
            'email'                => ['required', 'email'],
            'password'             => ['required', 'min:6', 'confirmed'],
        ], [
            'ho_ten.required'      => 'Vui lòng nhập họ tên.',
            'email.required'       => 'Vui lòng nhập email.',
            'email.email'          => 'Email không hợp lệ.',
            'password.min'         => 'Mật khẩu ít nhất 6 ký tự.',
            'password.confirmed'   => 'Xác nhận mật khẩu không khớp.',
        ]);

        if ($v->fails()) {
            return response()->json(['success' => false, 'errors' => $v->errors()], 422);
        }

        $existing = KhachHang::where('email', $request->email)->first();
        $alreadyVerified = $existing && (
            $existing->email_xac_thuc_at ||
            ($existing->kich_hoat && empty($existing->verification_token))
        );

        if ($alreadyVerified) {
            return response()->json(['success' => false, 'errors'  => ['email' => ['Email này đã được sử dụng.']]], 422);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $kh = KhachHang::updateOrCreate(
            ['email' => $request->email],
            [
                'ho_ten'             => $request->ho_ten,
                'so_dien_thoai'      => $request->so_dien_thoai,
                'password'           => Hash::make($request->password),
                'nguon_khach_hang'   => 'website',
                'verification_token' => $otp,
                'token_expiry'       => Carbon::now()->addMinutes(15),
                'email_xac_thuc_at'  => null,
            ]
        );

        try {
            Mail::to($kh->email)->send(new VerifyEmailMail($otp, $kh->ho_ten));
        } catch (Throwable $e) {
            Log::error('Không gửi được OTP đăng ký.', [
                'email' => $kh->email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hệ thống email tạm thời gặp sự cố. Vui lòng thử lại sau ít phút.'
            ], 503);
        }

        return response()->json(['success' => true, 'email' => $kh->email, 'message' => 'OTP đã gửi đến ' . $kh->email]);
    }

    // ══════════════════════════════════════════════════
    // XÁC THỰC OTP EMAIL
    // ══════════════════════════════════════════════════
    public function sendOtp(Request $request)
    {
        $kh = KhachHang::where('email', $request->email)
            ->whereNull('email_xac_thuc_at')
            ->first();

        if (!$kh) {
            return response()->json(['success' => false, 'message' => 'Email không tồn tại hoặc đã được xác thực.']);
        }

        if (!$kh->kich_hoat) {
            return response()->json(['success' => false, 'message' => 'Tài khoản đã bị vô hiệu hoá.']);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $kh->update([
            'verification_token' => $otp,
            'token_expiry'       => Carbon::now()->addMinutes(15),
        ]);

        try {
            Mail::to($kh->email)->send(new VerifyEmailMail($otp, $kh->ho_ten));
        } catch (Throwable $e) {
            Log::error('Không gửi lại được OTP.', [
                'email' => $kh->email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Không thể gửi OTP lúc này. Vui lòng thử lại sau.'
            ], 503);
        }

        return response()->json(['success' => true, 'message' => 'Đã gửi lại OTP.']);
    }

    public function verifyOtp(Request $request)
    {
        $kh = KhachHang::where('email', $request->email)->whereNull('email_xac_thuc_at')->first();
        if (!$kh) return response()->json(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);

        $dbToken  = str_pad((string) $kh->verification_token, 6, '0', STR_PAD_LEFT);
        $reqToken = str_pad((string) $request->otp, 6, '0', STR_PAD_LEFT);

        if ($dbToken !== $reqToken) {
            return response()->json(['success' => false, 'message' => 'Mã OTP không đúng.']);
        }

        if (Carbon::parse($kh->token_expiry)->isPast()) {
            return response()->json(['success' => false, 'message' => 'OTP đã hết hạn. Vui lòng gửi lại.']);
        }

        $kh->update([
            'email_xac_thuc_at'  => Carbon::now(),
            'verification_token' => null,
            'token_expiry'       => null,
            'kich_hoat'          => true,
        ]);

        Auth::guard('customer')->login($kh);
        $request->session()->regenerate();

        return response()->json(['success' => true, 'message' => '🎉 Xác thực thành công! Chào mừng ' . $kh->ho_ten]);
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('frontend.home')->with('success', 'Đã đăng xuất.');
    }

    // ══════════════════════════════════════════════════
    // QUÊN MẬT KHẨU
    // ══════════════════════════════════════════════════
    public function showForgot()
    {
        return view('frontend.auth.forgot');
    }

    public function sendReset(Request $request)
    {
        // 1. Validate định dạng email cơ bản
        $v = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email'    => 'Email không hợp lệ.',
        ]);

        if ($v->fails()) {
            return response()->json(['success' => false, 'errors' => $v->errors()], 422);
        }

        // 2. Tìm khách hàng trong cơ sở dữ liệu
        $kh = KhachHang::where('email', $request->email)->first();

        // 3. KIỂM TRA: Nếu email không tồn tại trong hệ thống
        if (!$kh) {
            return response()->json([
                'success' => false,
                'errors'  => [
                    'email' => ['Email này chưa được đăng ký trong hệ thống.']
                ]
            ], 422);
        }

        // 4. KIỂM TRA: Nếu tài khoản tồn tại nhưng chưa xác thực email (chưa kích hoạt)
        if (!$kh->email_xac_thuc_at && $kh->kich_hoat && empty($kh->verification_token)) {
            $kh->forceFill(['email_xac_thuc_at' => now()])->save();
            $kh->refresh();
        }

        if (!$kh->email_xac_thuc_at) {
            return response()->json([
                'success' => false,
                'errors'  => [
                    'email' => ['Tài khoản này chưa được xác thực. Vui lòng đăng ký lại hoặc liên hệ hỗ trợ.']
                ]
            ], 422);
        }

        // 5. Nếu mọi thứ hợp lệ, tiến hành tạo mã OTP và gửi Mail
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $kh->verification_token = $otp;
        $kh->token_expiry = Carbon::now()->addMinutes(15);
        $kh->save();

        $resetLink = route('khach-hang.reset', ['token' => $otp, 'email' => $kh->email]);
        try {
            Mail::to($kh->email)->send(new ResetPasswordMail($resetLink, $otp, $kh->ho_ten));
        } catch (Throwable $e) {
            Log::error('Không gửi được email đặt lại mật khẩu.', [
                'email' => $kh->email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Không thể gửi email đặt lại mật khẩu lúc này. Vui lòng thử lại sau.'
            ], 503);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mã OTP và link đặt lại mật khẩu đã được gửi đến email.'
        ]);
    }
    public function showReset(Request $request, $token = null)
    {
        $token = $token ?? $request->query('token');
        $email = $request->query('email');

        if (!$email || !$token) {
            return redirect()->route('frontend.home')->with('error', 'Đường dẫn đặt lại mật khẩu không hợp lệ.');
        }

        $kh = KhachHang::where('email', $email)->first();
        if (!$kh) return redirect()->route('frontend.home')->with('error', 'Tài khoản không tồn tại.');

        $dbToken  = str_pad((string) $kh->verification_token, 6, '0', STR_PAD_LEFT);
        $reqToken = str_pad((string) $token, 6, '0', STR_PAD_LEFT);

        if ($dbToken !== $reqToken) {
            return redirect()->route('frontend.home')->with('error', 'Mã xác thực không đúng hoặc đã được sử dụng.');
        }

        if ($kh->token_expiry && Carbon::parse($kh->token_expiry)->isPast()) {
            return redirect()->route('frontend.home')->with('error', 'Mã xác thực đã hết hạn. Vui lòng yêu cầu lại.');
        }

        return view('frontend.auth.reset', compact('token', 'email'));
    }

    public function reset(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email'    => ['required', 'email'],
            'token'    => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
        ], [
            'password.min'       => 'Mật khẩu ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'token.required'     => 'Vui lòng nhập mã OTP.'
        ]);

        if ($v->fails()) {
            return $request->expectsJson() ? response()->json(['success' => false, 'errors' => $v->errors()], 422) : back()->withErrors($v)->withInput();
        }

        $kh = KhachHang::where('email', $request->email)->first();
        if (!$kh) {
            $msg = 'Tài khoản không tồn tại.';
            return $request->expectsJson() ? response()->json(['success' => false, 'message' => $msg]) : back()->withErrors(['email' => $msg]);
        }

        $dbToken  = str_pad((string) $kh->verification_token, 6, '0', STR_PAD_LEFT);
        $reqToken = str_pad((string) $request->token, 6, '0', STR_PAD_LEFT);

        if ($dbToken !== $reqToken) {
            $msg = 'Mã OTP không hợp lệ hoặc đã được sử dụng.';
            return $request->expectsJson() ? response()->json(['success' => false, 'message' => $msg]) : back()->withErrors(['token' => $msg]);
        }

        if ($kh->token_expiry && Carbon::parse($kh->token_expiry)->isPast()) {
            $msg = 'Mã OTP đã hết hạn. Vui lòng gửi lại yêu cầu mới.';
            return $request->expectsJson() ? response()->json(['success' => false, 'message' => $msg]) : back()->withErrors(['token' => $msg]);
        }

        $kh->password           = Hash::make($request->password);
        $kh->verification_token = null;
        $kh->token_expiry       = null;
        $kh->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Đặt lại mật khẩu thành công!']);
        }

        return redirect()->route('frontend.home')->with('success', '✅ Đặt lại mật khẩu thành công! Vui lòng đăng nhập.')->with('open_auth_modal', 'login');
    }

    // ══════════════════════════════════════════════════
    // TRANG CÁ NHÂN & CẬP NHẬT THÔNG TIN
    // ══════════════════════════════════════════════════
    public function profile()
    {
        return view('frontend.auth.profile');
    }
    public function lichSuXem()
    {
        return view('frontend.auth.lich_su_xem');
    }
    public function kyGuiCuaToi()
    {
        // Lấy danh sách ký gửi của khách hàng đang đăng nhập, sắp xếp mới nhất
        $kyGuis = \App\Models\KyGui::where('khach_hang_id', auth('customer')->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Phân trang 10 dòng/trang

        // Truyền biến $kyGuis sang view
        return view('frontend.ky-gui.my-list', compact('kyGuis'));
    }
    public function lichHenCuaToi()
    {
        $khachHang = Auth::guard('customer')->user();

        $lichHens = \App\Models\LichHen::with(['batDongSan', 'nhanVienSale'])
            ->where('khach_hang_id', $khachHang->id)
            ->orderBy('thoi_gian_hen', 'desc')
            ->paginate(10);

        return view('frontend.tai-khoan.lich-hen', compact('khachHang', 'lichHens'));
    }

    public function updateProfile(Request $request)
    {
        $kh = Auth::guard('customer')->user();
        $v = Validator::make($request->all(), [
            'ho_ten'        => 'required|string|max:100',
            'so_dien_thoai' => 'required|string|max:15|unique:khach_hang,so_dien_thoai,' . $kh->id,
            'email'         => 'nullable|email|max:100|unique:khach_hang,email,' . $kh->id,
        ]);
        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        $kh->update($request->only('ho_ten', 'so_dien_thoai', 'email'));
        return response()->json(['success' => true, 'message' => 'Cập nhật thành công!']);
    }

    public function changePassword(Request $request)
    {
        $kh = Auth::guard('customer')->user();
        $v = Validator::make($request->all(), [
            'mat_khau_cu'  => 'required',
            'mat_khau_moi' => 'required|min:6|confirmed',
        ]);
        if ($v->fails()) return response()->json(['success' => false, 'errors' => $v->errors()], 422);

        if (!Hash::check($request->mat_khau_cu, $kh->password)) return response()->json(['success' => false, 'errors' => ['mat_khau_cu' => ['Mật khẩu hiện tại không đúng.']]], 422);

        $kh->update(['password' => Hash::make($request->mat_khau_moi)]);
        return response()->json(['success' => true, 'message' => 'Đổi mật khẩu thành công!']);
    }
}
