<?php

namespace App\Observers;

use App\Models\BatDongSan;
use App\Mail\ThongBaoBatDongSanMail;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BatDongSanObserver
{
    public $afterCommit = true;

    /**
     * TỰ ĐỘNG TẠO SLUG KHI THÊM MỚI BĐS (nếu chưa có slug)
     * Đảm bảo URL thân thiện: /bat-dong-san/can-ho-2pn-vinhomes-smart-city
     */
    public function creating(BatDongSan $bds): void
    {
        if (empty($bds->slug) && !empty($bds->tieu_de)) {
            $bds->slug = $this->generateUniqueSlug($bds->tieu_de);
        }
    }

    /**
     * Tạo slug duy nhất. Nếu bị trùng, thêm hậu tố -2, -3,...
     */
    private function generateUniqueSlug(string $title, ?int $exceptId = null): string
    {
        $slug = Str::slug($title);

        // Nếu slug trống (tiêu đề toàn ký tự đặc biệt), fallback
        if (empty($slug)) {
            $slug = 'bat-dong-san-' . time();
        }

        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = BatDongSan::withTrashed()->where('slug', $slug);

            if ($exceptId) {
                $query->where('id', '!=', $exceptId);
            }

            if (!$query->exists()) {
                break;
            }

            $counter++;
            $slug = $originalSlug . '-' . $counter;
        }

        return $slug;
    }

    /**
     * CHẠY KHI ADMIN VỪA THÊM 1 BẤT ĐỘNG SẢN MỚI
     */
    public function created(BatDongSan $bds)
    {
        AppServiceProvider::clearMenuCache();
        Log::info("Observer: Vừa nhận thấy BĐS mới ID " . $bds->id);

        if (!$bds->hien_thi || $bds->trang_thai != 'con_hang') {
            Log::warning("Observer: BĐS mới không đủ điều kiện (ẩn hoặc hết hàng)");
            return;
        }

        $this->guiMailTheoTieuChi($bds, 'BdsMoi');
    }

    /**
     * CHẠY KHI ADMIN CẬP NHẬT/CHỈNH SỬA 1 BẤT ĐỘNG SẢN
     */
    public function updated(BatDongSan $bds)
    {
        AppServiceProvider::clearMenuCache();
        // ── Trường hợp 1: BĐS được tái kích hoạt (ẩn/hết hàng → hiển thị + còn hàng)
        $vuaKichHoat = ($bds->wasChanged('hien_thi') || $bds->wasChanged('trang_thai'))
            && $bds->hien_thi
            && $bds->trang_thai == 'con_hang';

        if ($vuaKichHoat) {
            Log::info("Observer: BĐS ID {$bds->id} vừa được tái kích hoạt → gửi mail tiêu chí chung.");
            $this->guiMailTheoTieuChi($bds, 'BdsMoi');
            return; // Không cần check thay đổi giá nếu vừa tái kích hoạt
        }

        // ── Trường hợp 2: BĐS thay đổi giá (chỉ khi đang hiển thị và còn hàng)
        if (!$bds->hien_thi || $bds->trang_thai != 'con_hang') return;

        if (!($bds->gui_mail_canh_bao_gia ?? true)) {
            Log::info('Observer: BDS ID ' . $bds->id . ' đã tắt gửi mail cảnh báo giá.');
            return;
        }

        if ($bds->wasChanged('gia') || $bds->wasChanged('gia_thue')) {
            $giaCu  = $bds->wasChanged('gia')      ? $bds->getOriginal('gia')      : $bds->getOriginal('gia_thue');
            $giaMoi = $bds->wasChanged('gia')      ? $bds->gia                     : $bds->gia_thue;

            $danhSachQuanTam = DB::table('dang_ky_nhan_tin')
                ->where('trang_thai', 1)
                ->where('bat_dong_san_id', $bds->id)
                ->get();

            if ($danhSachQuanTam->count() > 0) {
                Log::info("Observer: BĐS ID {$bds->id} vừa thay đổi giá từ {$giaCu} → {$giaMoi}. Gửi email cho {$danhSachQuanTam->count()} người.");
            }

            foreach ($danhSachQuanTam as $dk) {
                try {
                    Mail::to($dk->email)->queue(new ThongBaoBatDongSanMail($bds, 'CapNhatGia', $giaCu));
                    
                    \App\Models\NhatKyEmail::create([
                        'khach_hang_id' => $dk->khach_hang_id,
                        'loai_email' => 'canh_bao_gia',
                        'email_nguoi_nhan' => $dk->email,
                        'tieu_de' => '📢 Giá Bất động sản bạn theo dõi vừa thay đổi',
                        'noi_dung' => 'Hệ thống gửi tự động cho BĐS: ' . $bds->tieu_de,
                        'trang_thai' => 'thanh_cong',
                        'doi_tuong_lien_quan' => 'bat_dong_san',
                        'doi_tuong_id' => $bds->id,
                        'thoi_diem_gui' => now(),
                    ]);
                } catch (\Throwable $e) {
                    Log::error("Observer: Lỗi gửi mail CapNhatGia tới {$dk->email}: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Gửi email thông báo BĐS mới cho tất cả khách đăng ký theo tiêu chí chung.
     * Được dùng cả khi tạo mới lẫn khi tái kích hoạt BĐS.
     */
    private function guiMailTheoTieuChi(BatDongSan $bds, string $loai = 'BdsMoi'): void
    {
        $giaHienTai = $bds->nhu_cau == 'ban' ? $bds->gia : $bds->gia_thue;

        // BUG FIX 1: khu_vuc_id của BĐS — ưu tiên khu vực của DuAn (nếu có),
        // nếu không có dự án thì BĐS nhà lẻ không có khu vực → $khuVucId = null
        // Khi null, đăng ký có khu_vuc_id ≠ null sẽ bị loại → đúng hành vi mong muốn
        $khuVucId = $bds->duAn?->khu_vuc_id;

        // Eager load duAn nếu chưa load để tránh N+1
        if (!$bds->relationLoaded('duAn')) {
            $bds->load('duAn');
            $khuVucId = $bds->duAn?->khu_vuc_id;
        }

        $danhSachDangKy = DB::table('dang_ky_nhan_tin')
            ->where('trang_thai', 1)
            ->whereNull('bat_dong_san_id')
            ->get();

        Log::info("Observer [{$loai}]: Kiểm tra {$danhSachDangKy->count()} đăng ký tiêu chí chung cho BĐS ID {$bds->id}.");

        $soGuiThanhCong = 0;
        foreach ($danhSachDangKy as $dk) {

            // ── Kiểm tra Nhu cầu (Mua/Thuê)
            if ($dk->nhu_cau && $dk->nhu_cau !== $bds->nhu_cau) continue;

            // ── Kiểm tra Dự án
            if ($dk->du_an_id && $dk->du_an_id != $bds->du_an_id) continue;

            // ── Kiểm tra Khu vực
            // Nếu khách đăng ký theo khu vực mà BĐS không có khu vực → bỏ qua
            if ($dk->khu_vuc_id) {
                if (is_null($khuVucId) || $dk->khu_vuc_id != $khuVucId) continue;
            }

            // ── BUG FIX 2: Kiểm tra Số phòng ngủ — Xử lý các chuỗi phức tạp như "2n2vs+", "3 PN"
            if ($dk->so_phong_ngu) {
                $bdsPhong = strtolower(trim((string) $bds->so_phong_ngu));
                $dkPhong  = (string) $dk->so_phong_ngu;

                $isStudio = ($bdsPhong === 'studio' || $bdsPhong === '0');
                
                // Trích xuất số đầu tiên tìm thấy trong chuỗi BĐS (VD: "2n2vs+" -> 2)
                preg_match('/\d+/', $bdsPhong, $matches);
                $bdsNum = !empty($matches) ? (int)$matches[0] : 0;

                if ($dkPhong === '3') {
                    // Đăng ký 3+: BĐS phải có số >= 3 và không phải studio
                    if ($isStudio || $bdsNum < 3) continue;
                } elseif ($dkPhong === 'studio') {
                    // Đăng ký Studio: BĐS phải là studio
                    if (!$isStudio) continue;
                } else {
                    // Đăng ký 1 hoặc 2: Số lấy ra phải khớp và không phải studio
                    $dkNum = (int) $dkPhong;
                    if ($isStudio || $bdsNum !== $dkNum) continue;
                }
            }

            // ── Kiểm tra Khoảng giá (Ép kiểu float để tránh lỗi so sánh chuỗi trong PHP)
            $giaHienTaiFloat = (float) $giaHienTai;
            
            // Nếu khách hàng có thiết lập ngân sách cụ thể, ta bỏ qua các BĐS "Thỏa thuận" (giá = 0)
            // vì giá 0 sẽ luôn bị tính là nhỏ hơn mức giá đến (muc_gia_den)
            if (($dk->muc_gia_tu || $dk->muc_gia_den) && $giaHienTaiFloat <= 0) {
                continue;
            }

            if ($dk->muc_gia_tu) {
                if ($giaHienTaiFloat < (float) $dk->muc_gia_tu) continue;
            }
            if ($dk->muc_gia_den) {
                if ($giaHienTaiFloat > (float) $dk->muc_gia_den) continue;
            }

            // ── Khớp 100% → Gửi Mail
            try {
                Mail::to($dk->email)->queue(new ThongBaoBatDongSanMail($bds, $loai));
                
                \App\Models\NhatKyEmail::create([
                    'khach_hang_id' => $dk->khach_hang_id,
                    'loai_email' => 'canh_bao_gia',
                    'email_nguoi_nhan' => $dk->email,
                    'tieu_de' => '✨ Có Bất động sản mới phù hợp với bạn!',
                    'noi_dung' => 'Hệ thống gửi tự động cho BĐS: ' . $bds->tieu_de,
                    'trang_thai' => 'thanh_cong',
                    'doi_tuong_lien_quan' => 'bat_dong_san',
                    'doi_tuong_id' => $bds->id,
                    'thoi_diem_gui' => now(),
                ]);

                $soGuiThanhCong++;
                Log::info("Observer [{$loai}]: Gửi email tới {$dk->email} (DK ID {$dk->id})");
            } catch (\Throwable $e) {
                Log::error("Observer [{$loai}]: Lỗi gửi mail tới {$dk->email}: " . $e->getMessage());
            }
        }

        Log::info("Observer [{$loai}]: Hoàn tất — gửi {$soGuiThanhCong}/{$danhSachDangKy->count()} email.");
    }
}
