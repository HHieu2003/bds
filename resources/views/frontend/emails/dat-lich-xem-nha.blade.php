<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Xác nhận đặt lịch xem nhà</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f4f8;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;-webkit-text-size-adjust:100%;">

    {{-- Preheader text (hidden) --}}
    <div style="display:none;font-size:1px;color:#f0f4f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
        Yêu cầu đặt lịch xem nhà của bạn đã được ghi nhận. Chuyên viên sẽ liên hệ sớm nhất.
    </div>

    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f0f4f8;">
        <tr>
            <td align="center" style="padding:32px 12px;">

                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                    style="max-width:620px;background:#ffffff;border-radius:20px;overflow:hidden;border:1px solid #e2e8f0;">

                    {{-- Header --}}
                    <tr>
                        <td style="background:linear-gradient(135deg,#1a3c5e 0%,#2d6a9f 100%);padding:28px 32px 26px;">
                            <div style="font-size:22px;font-weight:800;color:#ffffff;letter-spacing:-0.3px;">Thành Công Land</div>
                            <div style="font-size:13px;color:rgba(255,255,255,0.85);margin-top:6px;">Xác nhận đặt lịch xem nhà thành công</div>
                        </td>
                    </tr>

                    {{-- Status badge + greeting --}}
                    <tr>
                        <td style="padding:28px 32px 16px;">
                            <div style="display:inline-block;background:#ebf5ff;color:#1a56db;font-size:11px;font-weight:700;padding:5px 12px;border-radius:999px;border:1px solid #c3ddfd;letter-spacing:.5px;">
                                ĐÃ GHI NHẬN
                            </div>
                            <h1 style="margin:14px 0 10px;font-size:22px;line-height:1.3;color:#1e293b;font-weight:800;">
                                🏠 Lịch xem nhà đã được đặt!
                            </h1>
                            <p style="margin:0 0 6px;font-size:15px;line-height:1.75;color:#475569;">
                                Xin chào <strong style="color:#0f172a;">{{ $lichHen->ten_khach_hang }}</strong>,
                            </p>
                            <p style="margin:0;font-size:15px;line-height:1.75;color:#64748b;">
                                Cảm ơn bạn đã tin tưởng Thành Công Land! Chúng tôi đã nhận được yêu cầu đặt lịch của bạn.
                                Chuyên viên sẽ liên hệ xác nhận qua số điện thoại trong thời gian sớm nhất.
                            </p>
                        </td>
                    </tr>

                    {{-- Appointment details box --}}
                    <tr>
                        <td style="padding:4px 32px 16px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                                style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;">
                                <tr>
                                    <td style="padding:16px 20px 8px;font-size:12px;font-weight:700;color:#64748b;letter-spacing:.8px;">
                                        THÔNG TIN LỊCH HẸN
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 20px 16px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                                            style="font-size:14px;line-height:1.8;color:#334155;">

                                            {{-- Thời gian --}}
                                            <tr>
                                                <td style="padding:4px 0;width:40%;color:#94a3b8;vertical-align:top;">⏰ Thời gian</td>
                                                <td style="padding:4px 0;font-weight:700;color:#0f172a;">{{ $thoiGian }}</td>
                                            </tr>

                                            {{-- Bất động sản --}}
                                            @if($lichHen->batDongSan)
                                            <tr>
                                                <td style="padding:4px 0;color:#94a3b8;vertical-align:top;">🏢 Bất động sản</td>
                                                <td style="padding:4px 0;font-weight:600;color:#1e3a5f;">
                                                    {{ $lichHen->batDongSan->tieu_de ?? 'Đang cập nhật' }}
                                                </td>
                                            </tr>
                                            @if($lichHen->batDongSan->dia_chi)
                                            <tr>
                                                <td style="padding:4px 0;color:#94a3b8;vertical-align:top;">📍 Địa chỉ</td>
                                                <td style="padding:4px 0;color:#334155;">{{ $lichHen->batDongSan->dia_chi }}</td>
                                            </tr>
                                            @endif
                                            @endif

                                            {{-- SĐT --}}
                                            <tr>
                                                <td style="padding:4px 0;color:#94a3b8;">📞 SĐT liên hệ</td>
                                                <td style="padding:4px 0;font-weight:600;color:#0f172a;">{{ $lichHen->sdt_khach_hang }}</td>
                                            </tr>

                                            {{-- Ghi chú --}}
                                            @if($lichHen->ghi_chu_sale && str_starts_with($lichHen->ghi_chu_sale, 'Khách nhắn từ Web:'))
                                            <tr>
                                                <td style="padding:4px 0;color:#94a3b8;vertical-align:top;">💬 Lời nhắn</td>
                                                <td style="padding:4px 0;color:#475569;">{{ Str::after($lichHen->ghi_chu_sale, 'Khách nhắn từ Web: ') }}</td>
                                            </tr>
                                            @endif

                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Info note --}}
                    <tr>
                        <td style="padding:4px 32px 12px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                                style="border-left:4px solid #2d6a9f;background:#eff6ff;border-radius:10px;">
                                <tr>
                                    <td style="padding:14px 16px;font-size:14px;line-height:1.7;color:#3b4f6e;">
                                        Nếu bạn cần thay đổi thời gian hoặc hủy lịch, vui lòng liên hệ hotline
                                        <strong style="color:#1a3c5e;">0336 123 130</strong> để được hỗ trợ kịp thời.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- CTA Button --}}
                    <tr>
                        <td style="padding:12px 32px 28px;" align="center">
                            <a href="tel:0336123130"
                                style="display:inline-block;background:linear-gradient(135deg,#1a3c5e,#2d6a9f);color:#ffffff;text-decoration:none;font-size:14px;font-weight:700;padding:13px 24px;border-radius:10px;">
                                📞 Gọi ngay: 0336 123 130
                            </a>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f1f5f9;padding:18px 28px;text-align:center;border-top:1px solid #e2e8f0;">
                            <p style="margin:0;font-size:12px;line-height:1.7;color:#94a3b8;">
                                Cảm ơn bạn đã sử dụng dịch vụ của Thành Công Land.<br>
                                © {{ date('Y') }} Thành Công Land. Mọi quyền được bảo lưu.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
