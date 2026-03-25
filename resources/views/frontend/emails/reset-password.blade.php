<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
    <h2 style="color: #1a3c5e; text-align: center;">Yêu cầu đặt lại mật khẩu</h2>
    <p>Chào <strong>{{ $ten }}</strong>,</p>
    <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn tại Thành Công Land.</p>

    <div style="text-align: center; margin: 25px 0;">
        <p style="font-size: 14px; color: #555; margin-bottom: 10px;">Nhập mã OTP 6 số này vào màn hình trên website:</p>
        <div style="display: inline-block; font-size: 28px; font-weight: bold; letter-spacing: 5px; color: #FF5722; background: #fff5f2; padding: 15px 30px; border-radius: 8px; border: 1px dashed #FF8C42;">
            {{ $otp }}
        </div>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <p style="font-size: 14px; color: #555; margin-bottom: 10px;">Hoặc nhấp trực tiếp vào nút bên dưới:</p>
        <a href="{{ $resetLink }}" style="background: linear-gradient(135deg, #1a3c5e, #2d6a9f); color: #fff; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
            Đặt Lại Mật Khẩu
        </a>
    </div>

    <p style="font-size: 13px; color: #888; text-align: center;">Mã xác thực và liên kết này sẽ hết hạn sau 15 phút.<br>Nếu bạn không yêu cầu, vui lòng bỏ qua email này.</p>
</div>
