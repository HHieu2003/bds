<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#f8f4f1;padding:30px;margin:0">
    <div style="max-width:460px;margin:auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.09)">
        <div style="background:linear-gradient(135deg,#0F172A,#1a3c5e);padding:26px 32px;text-align:center">
            <div style="font-size:1.3rem;font-weight:900;color:#fff">🏠 Thành Công Land</div>
            <div style="color:rgba(255,255,255,.7);font-size:.82rem;margin-top:4px">Xác thực tài khoản</div>
        </div>
        <div style="padding:32px">
            <p style="color:#1a3c5e;font-size:.95rem;margin:0 0 10px">Xin chào <strong>{{ $ten }}</strong>,</p>
            <p style="color:#555;line-height:1.7;margin:0 0 24px;font-size:.88rem">
                Vui lòng dùng mã OTP bên dưới để xác thực tài khoản.<br>Mã chỉ có hiệu lực trong <strong>15 phút</strong>.
            </p>
            <div style="text-align:center;margin:0 0 24px">
                <div style="display:inline-block;background:#fff5ef;border:2px dashed #FF8C42;border-radius:14px;padding:18px 44px">
                    <div style="font-size:2.6rem;font-weight:900;color:#FF5722;letter-spacing:12px">{{ $otp }}</div>
                </div>
            </div>
            <p style="color:#aaa;font-size:.76rem;text-align:center;margin:0">Nếu bạn không yêu cầu đăng ký này, hãy bỏ qua email này.</p>
        </div>
        <div style="background:#f8f4f1;padding:14px;text-align:center;font-size:.72rem;color:#aaa">
            © {{ date('Y') }} Thành Công Land. Mọi quyền được bảo lưu.
        </div>
    </div>
</body>
</html>
