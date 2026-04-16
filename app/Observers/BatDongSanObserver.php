<?php

namespace App\Observers;

use App\Models\BatDongSan;
use App\Mail\ThongBaoBatDongSanMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BatDongSanObserver
{
    public $afterCommit = true;

    /**
     * CHẠY KHI ADMIN VỪA THÊM 1 BẤT ĐỘNG SẢN MỚI
     */
    public function created(BatDongSan $bds)
    {
        Log::info("Observer: Vừa nhận thấy BĐS mới ID " . $bds->id);

        // Bỏ qua nếu BĐS không hiển thị hoặc không còn hàng
        if (!$bds->hien_thi || $bds->trang_thai != 'con_hang') {
            Log::warning("Observer: BĐS mới không đủ điều kiện (ẩn hoặc hết hàng)");
            return;
        }

        $giaHienTai = $bds->nhu_cau == 'ban' ? $bds->gia : $bds->gia_thue;
        $khuVucId = $bds->duAn?->khu_vuc_id;

        // Tìm tất cả khách hàng đăng ký "Theo dõi tiêu chí" (Không fix cứng ID căn hộ)
        $danhSachDangKy = DB::table('dang_ky_nhan_tin')->where('trang_thai', 1)
            ->whereNull('bat_dong_san_id')
            ->get();

        Log::info("Observer: Tìm thấy " . $danhSachDangKy->count() . " khách hàng đang chờ tin.");

        foreach ($danhSachDangKy as $dk) {
            $isMatch = true;

            // Kiểm tra Nhu cầu (Mua/Thuê)
            if ($dk->nhu_cau && $dk->nhu_cau != $bds->nhu_cau) $isMatch = false;

            // Kiểm tra Dự án
            if ($dk->du_an_id && $dk->du_an_id != $bds->du_an_id) $isMatch = false;

            // Kiểm tra Khu vực
            if ($dk->khu_vuc_id && $dk->khu_vuc_id != $khuVucId) $isMatch = false;

            // Kiểm tra Số phòng ngủ (Nếu khách chọn 3PN+ thì check >=3)
            if ($dk->so_phong_ngu) {
                if ($dk->so_phong_ngu == '3' && $bds->so_phong_ngu < 3) $isMatch = false;
                elseif ($dk->so_phong_ngu != '3' && $dk->so_phong_ngu != $bds->so_phong_ngu) $isMatch = false;
            }

            // Kiểm tra Khoảng giá
            if ($dk->muc_gia_tu && $giaHienTai < $dk->muc_gia_tu) $isMatch = false;
            if ($dk->muc_gia_den && $giaHienTai > $dk->muc_gia_den) $isMatch = false;

            // Nếu khớp 100% tiêu chí -> Gửi Mail!
            if ($isMatch) {
                Log::info("Observer: Khớp tiêu chí! Đang bắn email BĐS MỚI tới " . $dk->email);
                Mail::to($dk->email)->queue(new ThongBaoBatDongSanMail($bds, 'BdsMoi'));
            }
        }
    }

    /**
     * CHẠY KHI ADMIN CẬP NHẬT/CHỈNH SỬA 1 BẤT ĐỘNG SẢN
     */
    public function updated(BatDongSan $bds)
    {
        if (!($bds->gui_mail_canh_bao_gia ?? true)) {
            Log::info('Observer: BDS ID ' . $bds->id . ' đã tắt gửi mail cảnh báo giá.');
            return;
        }

        // Bỏ qua nếu BĐS không hiển thị hoặc không còn hàng
        if (!$bds->hien_thi || $bds->trang_thai != 'con_hang') return;

        // Kiểm tra xem giá Bán hoặc giá Thuê có bị thay đổi không?
        if ($bds->wasChanged('gia') || $bds->wasChanged('gia_thue')) {

            $giaCu = $bds->wasChanged('gia') ? $bds->getOriginal('gia') : $bds->getOriginal('gia_thue');
            $giaMoi = $bds->wasChanged('gia') ? $bds->gia : $bds->gia_thue;

            // Gửi cảnh báo khi giá có thay đổi (tăng hoặc giảm)
            $danhSachQuanTam = DB::table('dang_ky_nhan_tin')->where('trang_thai', 1)
                ->where('bat_dong_san_id', $bds->id)
                ->get();

            if ($danhSachQuanTam->count() > 0) {
                Log::info("Observer: BĐS ID " . $bds->id . " vừa thay đổi giá. Đang chuẩn bị gửi email cho " . $danhSachQuanTam->count() . " người.");
            }

            foreach ($danhSachQuanTam as $dk) {
                Mail::to($dk->email)->queue(new ThongBaoBatDongSanMail($bds, 'CapNhatGia', $giaCu));
            }
        }
    }
}
