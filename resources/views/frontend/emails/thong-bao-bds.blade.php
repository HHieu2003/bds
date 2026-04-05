<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: 0 auto;
            border-top: 5px solid #c0662a;
        }

        .title {
            color: #1b3a6b;
            font-size: 20px;
            font-weight: bold;
        }

        .price {
            color: #e53e3e;
            font-size: 24px;
            font-weight: bold;
            margin: 15px 0;
        }

        .old-price {
            text-decoration: line-through;
            color: #a0aec0;
            font-size: 16px;
        }

        .btn {
            display: inline-block;
            background: #c0662a;
            color: #fff;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="title">
            {{ $loaiThongBao == 'BdsMoi' ? 'Có Bất động sản mới phù hợp tiêu chí của bạn!' : 'Cập nhật giá mới cho Bất động sản bạn quan tâm!' }}
        </h2>

        <p>Chào bạn,</p>
        <p>Thành Công Land xin thông báo thông tin mới nhất về bất động sản:</p>

        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #1b3a6b;">{{ $bds->tieu_de }}</h3>
            <p><strong>Dự án/Khu vực:</strong> {{ $bds->duAn->ten_du_an ?? $bds->dia_chi }}</p>
            <p><strong>Loại hình:</strong> {{ $bds->loai_hinh }} | <strong>Phòng ngủ:</strong>
                {{ $bds->so_phong_ngu }}PN</p>

            <div class="price">
                @if ($loaiThongBao != 'BdsMoi' && $mucGiaCu)
                    <span class="old-price">{{ number_format($mucGiaCu, 0, ',', '.') }} đ</span> <br>
                @endif
                Giá mới:
                {{ $bds->nhu_cau == 'ban' ? number_format($bds->gia, 0, ',', '.') : number_format($bds->gia_thue, 0, ',', '.') }}
                đ
            </div>
        </div>

        <center>
            <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}" class="btn">Xem Chi Tiết Căn Hộ</a>
        </center>

        <p style="margin-top: 30px; font-size: 12px; color: #718096;">Bạn nhận được email này vì đã đăng ký nhận thông
            tin tại Thành Công Land.</p>
    </div>
</body>

</html>
