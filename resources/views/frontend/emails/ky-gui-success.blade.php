<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;background:#f8f4f1;padding:30px;margin:0">
    <div style="max-width:500px;margin:auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.09)">

        <div style="background:linear-gradient(135deg,#0F172A,#1a3c5e);padding:26px 32px;text-align:center">
            <div style="font-size:1.3rem;font-weight:900;color:#fff">🏠 Thành Công Land</div>
            <div style="color:rgba(255,255,255,.7);font-size:.85rem;margin-top:4px">Xác nhận yêu cầu ký gửi</div>
        </div>

        <div style="padding:32px">
            <h2 style="color:#1a3c5e;font-size:1.1rem;margin:0 0 15px;text-align:center;">Ký Gửi Thành Công!</h2>
            <p style="color:#444;font-size:.95rem;margin:0 0 10px">
                Chào <strong>{{ $kyGui->ho_ten_chu_nha }}</strong>,
            </p>
            <p style="color:#555;line-height:1.6;margin:0 0 20px;font-size:.9rem">
                Cảm ơn bạn đã tin tưởng và gửi thông tin ký gửi bất động sản. Hệ thống đã ghi nhận yêu cầu của bạn với các thông tin cơ bản sau:
            </p>

            <div style="background:#f8f9fa;border-left:4px solid #FF8C42;border-radius:8px;padding:15px;margin-bottom:20px;font-size:.85rem;color:#444;line-height:1.8">
                <strong>Nhu cầu:</strong> {{ $kyGui->nhu_cau === 'ban' ? 'Bán' : 'Cho thuê' }} <span style="text-transform: capitalize">{{ str_replace('_', ' ', $kyGui->loai_hinh) }}</span><br>
                <strong>Địa chỉ:</strong> {{ $kyGui->dia_chi ?? 'Đang cập nhật' }}<br>
                <strong>Diện tích:</strong> {{ $kyGui->dien_tich }} m²<br>
                @if($kyGui->nhu_cau === 'ban' && $kyGui->gia_ban_mong_muon)
                    <strong>Giá bán mong muốn:</strong> {{ number_format($kyGui->gia_ban_mong_muon) }} VNĐ
                @elseif($kyGui->nhu_cau === 'thue' && $kyGui->gia_thue_mong_muon)
                    <strong>Giá thuê mong muốn:</strong> {{ number_format($kyGui->gia_thue_mong_muon) }} VNĐ/tháng
                @endif
            </div>

            <p style="color:#555;line-height:1.6;font-size:.9rem;margin:0">
                Chuyên viên của chúng tôi sẽ kiểm tra thông tin và liên hệ với bạn qua số điện thoại <strong>{{ $kyGui->so_dien_thoai }}</strong> trong thời gian sớm nhất để tư vấn thủ tục.
            </p>
        </div>

        <div style="background:#f8f4f1;padding:16px;text-align:center;font-size:.75rem;color:#888;line-height:1.5">
            Cần hỗ trợ gấp? Gọi ngay Hotline: <strong style="color:#FF5722">0336 123 130</strong><br>
            © {{ date('Y') }} Thành Công Land. Mọi quyền được bảo lưu.
        </div>
    </div>
</body>
</html>
