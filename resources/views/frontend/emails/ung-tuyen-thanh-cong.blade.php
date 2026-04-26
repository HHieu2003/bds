<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Xac nhan ung tuyen thanh cong</title>
</head>

<body
    style="margin:0;padding:0;background-color:#f5f6f8;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;">

    <div
        style="display:none;font-size:1px;color:#f5f6f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
        Ho so ung tuyen cua ban da duoc ghi nhan. Bo phan Nhan su Thanh Cong Land se lien he trong thoi gian som nhat.
    </div>

    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
        style="background-color:#f5f6f8;">
        <tr>
            <td align="center" style="padding:28px 12px;">

                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                    style="max-width:620px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #eceef2;">

                    {{-- Header --}}
                    <tr>
                        <td
                            style="background:linear-gradient(135deg,#0F172A 0%,#1A2948 100%);padding:26px 28px 24px 28px;text-align:left;">
                            <div style="font-size:24px;line-height:1;font-weight:700;color:#ffffff;">Thanh Cong Land
                            </div>
                            <div style="font-size:13px;line-height:1.5;color:rgba(255,255,255,0.9);margin-top:8px;">
                                Xac nhan tiep nhan ho so ung tuyen
                            </div>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:28px 28px 16px 28px;">
                            <div
                                style="display:inline-block;background:#ecfdf5;color:#059669;font-size:12px;font-weight:700;padding:6px 12px;border-radius:999px;border:1px solid #a7f3d0;">
                                DA TIEP NHAN
                            </div>
                            <h1
                                style="margin:14px 0 10px 0;font-size:24px;line-height:1.3;color:#222222;font-weight:800;">
                                Ung tuyen thanh cong!
                            </h1>
                            <p style="margin:0 0 8px 0;font-size:15px;line-height:1.7;color:#444444;">
                                Chao <strong style="color:#202020;">{{ $don->ho_ten }}</strong>,
                            </p>
                            <p style="margin:0;font-size:15px;line-height:1.7;color:#555555;">
                                Cam on ban da quan tam den co hoi nghe nghiep tai Thanh Cong Land. He thong da ghi nhan
                                ho so ung tuyen cua ban. Vui long xem thong tin chi tiet ben duoi.
                            </p>
                        </td>
                    </tr>

                    {{-- Chi tiet --}}
                    <tr>
                        <td style="padding:0 28px 8px 28px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                                style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:12px;">
                                <tr>
                                    <td
                                        style="padding:16px 16px 4px 16px;font-size:13px;color:#0369a1;font-weight:700;">
                                        THONG TIN UNG TUYEN
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:2px 16px 16px 16px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0"
                                            width="100%" style="font-size:14px;line-height:1.7;color:#333333;">
                                            <tr>
                                                <td style="padding:3px 0;width:38%;color:#7a7a7a;">Vi tri ung tuyen
                                                </td>
                                                <td style="padding:3px 0;font-weight:700;color:#222222;">
                                                    {{ $don->tinTuyenDung->tieu_de ?? 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:3px 0;color:#7a7a7a;">Ho va ten</td>
                                                <td style="padding:3px 0;font-weight:700;color:#222222;">
                                                    {{ $don->ho_ten }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:3px 0;color:#7a7a7a;">Email</td>
                                                <td style="padding:3px 0;font-weight:700;color:#222222;">
                                                    {{ $don->email }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:3px 0;color:#7a7a7a;">So dien thoai</td>
                                                <td style="padding:3px 0;font-weight:700;color:#222222;">
                                                    {{ $don->so_dien_thoai }}
                                                </td>
                                            </tr>
                                            @if ($don->nam_sinh)
                                                <tr>
                                                    <td style="padding:3px 0;color:#7a7a7a;">Nam sinh</td>
                                                    <td style="padding:3px 0;font-weight:700;color:#222222;">
                                                        {{ $don->nam_sinh }}
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Note --}}
                    <tr>
                        <td style="padding:8px 28px 10px 28px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                                style="border-left:4px solid #FF8C42;background:#f8fafc;border-radius:10px;">
                                <tr>
                                    <td
                                        style="padding:14px 14px 14px 16px;font-size:14px;line-height:1.7;color:#4b5563;">
                                        Bo phan Nhan su se lien he voi ban qua so dien thoai
                                        <strong style="color:#111827;">{{ $don->so_dien_thoai }}</strong>
                                        hoac email <strong style="color:#111827;">{{ $don->email }}</strong>
                                        trong vong 24h lam viec de thong bao buoc tiep theo.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>



                    {{-- Footer --}}
                    <tr>
                        <td style="background:#f0f4f8;padding:16px 24px;text-align:center;">
                            <p style="margin:0;font-size:12px;line-height:1.6;color:#7b7b7b;">
                                Cam on ban da quan tam den Thanh Cong Land.<br>
                                © {{ date('Y') }} Thanh Cong Land. Moi quyen duoc bao luu.
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>

</html>
