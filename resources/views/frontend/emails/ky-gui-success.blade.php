<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Xac nhan ky gui thanh cong</title>
</head>

<body
    style="margin:0;padding:0;background-color:#f5f6f8;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;">

    <div
        style="display:none;font-size:1px;color:#f5f6f8;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">
        Yeu cau ky gui cua ban da duoc ghi nhan. Chuyen vien Thanh Cong Land se lien he trong thoi gian som nhat.
    </div>

    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
        style="background-color:#f5f6f8;">
        <tr>
            <td align="center" style="padding:28px 12px;">

                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                    style="max-width:620px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #eceef2;">

                    <tr>
                        <td
                            style="background:linear-gradient(135deg,#ff8c42 0%,#ff6a2b 100%);padding:26px 28px 24px 28px;text-align:left;">
                            <div style="font-size:24px;line-height:1;font-weight:700;color:#ffffff;">Thanh Cong Land
                            </div>
                            <div style="font-size:13px;line-height:1.5;color:rgba(255,255,255,0.9);margin-top:8px;">
                                Xac nhan tiep nhan yeu cau ky gui bat dong san
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:28px 28px 16px 28px;">
                            <div
                                style="display:inline-block;background:#fff3eb;color:#c05f20;font-size:12px;font-weight:700;padding:6px 12px;border-radius:999px;border:1px solid #ffd9c2;">
                                DA TIEP NHAN
                            </div>
                            <h1
                                style="margin:14px 0 10px 0;font-size:24px;line-height:1.3;color:#222222;font-weight:800;">
                                Ky gui thanh cong
                            </h1>
                            <p style="margin:0 0 8px 0;font-size:15px;line-height:1.7;color:#444444;">
                                Chao <strong style="color:#202020;">{{ $kyGui->ho_ten_chu_nha }}</strong>,
                            </p>
                            <p style="margin:0;font-size:15px;line-height:1.7;color:#555555;">
                                Cam on ban da tin tuong Thanh Cong Land. He thong da ghi nhan thong tin ky gui cua ban.
                                Vui long xem tom tat chi tiet ben duoi.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 28px 8px 28px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                                style="background:#fffaf6;border:1px solid #ffe2cf;border-radius:12px;">

                                <tr>
                                    <td
                                        style="padding:16px 16px 4px 16px;font-size:13px;color:#8a5a3b;font-weight:700;">
                                        THONG TIN KY GUI
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:2px 16px 16px 16px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0"
                                            width="100%" style="font-size:14px;line-height:1.7;color:#333333;">
                                            <tr>
                                                <td style="padding:3px 0;width:38%;color:#7a7a7a;">Nhu cau</td>
                                                <td style="padding:3px 0;font-weight:700;color:#222222;">
                                                    {{ $kyGui->nhu_cau === 'ban' ? 'Ban' : 'Cho thue' }}
                                                    {{ str_replace('_', ' ', $kyGui->loai_hinh) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:3px 0;color:#7a7a7a;">Dia chi</td>
                                                <td style="padding:3px 0;font-weight:700;color:#222222;">
                                                    {{ $kyGui->dia_chi ?? 'Dang cap nhat' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:3px 0;color:#7a7a7a;">Dien tich</td>
                                                <td style="padding:3px 0;font-weight:700;color:#222222;">
                                                    {{ $kyGui->dien_tich }} m2
                                                </td>
                                            </tr>

                                            @if ($kyGui->nhu_cau === 'ban' && $kyGui->gia_ban_mong_muon)
                                                <tr>
                                                    <td style="padding:3px 0;color:#7a7a7a;">Gia ban mong muon</td>
                                                    <td style="padding:3px 0;font-weight:700;color:#c05f20;">
                                                        {{ number_format($kyGui->gia_ban_mong_muon) }} VND
                                                    </td>
                                                </tr>
                                            @elseif($kyGui->nhu_cau === 'thue' && $kyGui->gia_thue_mong_muon)
                                                <tr>
                                                    <td style="padding:3px 0;color:#7a7a7a;">Gia thue mong muon</td>
                                                    <td style="padding:3px 0;font-weight:700;color:#c05f20;">
                                                        {{ number_format($kyGui->gia_thue_mong_muon) }} VND/thang
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:8px 28px 10px 28px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
                                style="border-left:4px solid #ff8c42;background:#f8fafc;border-radius:10px;">
                                <tr>
                                    <td
                                        style="padding:14px 14px 14px 16px;font-size:14px;line-height:1.7;color:#4b5563;">
                                        Chuyen vien cua chung toi se lien he qua so
                                        <strong style="color:#111827;">{{ $kyGui->so_dien_thoai }}</strong>
                                        trong thoi gian som nhat de tu van thu tuc va ho tro dinh gia.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:10px 28px 24px 28px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="tel:0336123130"
                                            style="display:inline-block;background:#ff8c42;color:#ffffff;text-decoration:none;font-size:14px;font-weight:700;padding:12px 20px;border-radius:10px;">
                                            Goi hotline: 0336 123 130
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#fff6ef;padding:16px 24px;text-align:center;">
                            <p style="margin:0;font-size:12px;line-height:1.6;color:#7b7b7b;">
                                Cam on ban da su dung dich vu cua Thanh Cong Land.<br>
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
