<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BatDongSan;
use App\Models\KhachHang;
use App\Models\LichHen;
use App\Models\NhanVien;
use App\Models\ThongBao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LichHenController extends Controller
{
    public function index(Request $request)
    {
        $nhanVien = $this->currentNhanVien();
        $stats = $this->_stats($nhanVien);

        // ========================================================
        // 1. DATA CHO TAB "DANH SÁCH TOÀN BỘ"
        // ========================================================
        $queryList = LichHen::with(['khachHang', 'batDongSan.chuNha', 'batDongSan.khuVuc.duAn', 'nhanVienSale', 'nhanVienNguonHang']);

        if ($nhanVien->isSale()) {
            $queryList->where(function ($q) use ($nhanVien) {
                // Thấy lịch của mình HOẶC lịch mới trên web chưa ai nhận
                $q->where('nhan_vien_sale_id', $nhanVien->id)
                    ->orWhere(function ($qWeb) {
                        $qWeb->where('trang_thai', 'moi_dat')->whereNull('nhan_vien_sale_id');
                    });
            });
        } elseif ($nhanVien->isNguonHang()) {
            $queryList->where('nhan_vien_nguon_hang_id', $nhanVien->id);
        }

        if ($request->filled('tim_kiem')) {
            $kw = '%' . $request->tim_kiem . '%';
            $queryList->where(function ($q) use ($kw) {
                $q->where('ten_khach_hang', 'like', $kw)
                    ->orWhere('sdt_khach_hang', 'like', $kw)
                    ->orWhereHas('batDongSan', function ($qBds) use ($kw) {
                        $qBds->where('tieu_de', 'like', $kw)->orWhere('ma_can', 'like', $kw);
                    });
            });
        }
        if ($request->filled('trang_thai')) {
            $queryList->where('trang_thai', $request->trang_thai);
        }
        if ($request->filled('tu_ngay')) {
            $queryList->whereDate('thoi_gian_hen', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $queryList->whereDate('thoi_gian_hen', '<=', $request->den_ngay);
        }

        $lichHensList = $queryList->orderBy('thoi_gian_hen', 'desc')->paginate(15)->withQueryString();

        // ========================================================
        // 2. TRẢ VỀ VIEW TƯƠNG ỨNG VỚI ROLE
        // ========================================================
        if ($nhanVien->isNguonHang()) {
            $lichHensTodo = LichHen::with(['khachHang', 'batDongSan.chuNha', 'batDongSan.khuVuc.duAn', 'nhanVienSale'])
                ->where('nhan_vien_nguon_hang_id', $nhanVien->id)
                ->whereIn('trang_thai', ['cho_xac_nhan', 'da_xac_nhan'])
                ->orderBy('thoi_gian_hen', 'asc')
                ->get();
            return view('admin.lich-hen.nguon_hang', compact('lichHensTodo', 'lichHensList', 'stats'));
        }

        if ($nhanVien->isAdmin() && $request->get('giao_dien') === 'nguon_hang') {
            $lichHensTodo = LichHen::with(['khachHang', 'batDongSan.chuNha', 'batDongSan.khuVuc.duAn', 'nhanVienSale', 'nhanVienNguonHang'])
                ->whereIn('trang_thai', ['cho_xac_nhan', 'da_xac_nhan'])
                ->orderBy('thoi_gian_hen', 'asc')
                ->get();
            $adminMode = true;
            return view('admin.lich-hen.nguon_hang', compact('lichHensTodo', 'lichHensList', 'stats', 'adminMode'));
        }

        // ========================================================
        // 3. DATA CHO SALE & ADMIN (Thay thế Tab Liên Hệ bằng Yêu cầu xem nhà)
        // ========================================================
        $dsNguonHang = NhanVien::where('vai_tro', 'nguon_hang')->where('kich_hoat', 1)->get();

        // Lấy danh sách Lịch hẹn mới chưa có ai nhận cho Tab 3
        $lichHenMoiItems = LichHen::with(['batDongSan'])
            ->where('trang_thai', 'moi_dat')
            ->whereNull('nhan_vien_sale_id')
            ->orderBy('created_at', 'desc')
            ->paginate(12, ['*'], 'lh_page')->withQueryString();

        return view('admin.lich-hen.index', compact(
            'stats',
            'dsNguonHang',
            'nhanVien',
            'lichHensList',
            'lichHenMoiItems'
        ));
    }

    public function apiEvents(Request $request)
    {
        $nhanVien = $this->currentNhanVien();
        $query = LichHen::with(['khachHang', 'batDongSan']);

        if ($nhanVien->isSale()) {
            $query->where(function ($q) use ($nhanVien) {
                $q->where('nhan_vien_sale_id', $nhanVien->id)
                    ->orWhere(function ($qWeb) {
                        $qWeb->where('trang_thai', 'moi_dat')->whereNull('nhan_vien_sale_id');
                    });
            });
        }

        $lichHens = $query->get();
        $events = [];

        $colorMap = [
            'moi_dat'      => '#f59e0b', // Cam: Trống, cần nhận
            'sale_da_nhan' => '#8b5cf6', // Tím: Sale đang xác nhận thông tin
            'cho_xac_nhan' => '#3b82f6', // Xanh dương
            'da_xac_nhan'  => '#10b981', // Xanh lá
            'hoan_thanh'   => '#6b7280', // Xám
            'tu_choi'      => '#ef4444', // Đỏ
            'huy'          => '#ef4444', // Đỏ
        ];

        foreach ($lichHens as $lh) {
            $tenBds = $lh->batDongSan ? $lh->batDongSan->tieu_de : 'Nhà lẻ / Chưa xác định';
            $nguonPhuTrachId = optional($lh->batDongSan)->nhan_vien_phu_trach_id;

            $events[] = [
                'id' => $lh->id,
                'title' => date('H:i', strtotime($lh->thoi_gian_hen)) . ' - ' . $lh->ten_khach_hang,
                'start' => $lh->thoi_gian_hen,
                'backgroundColor' => $colorMap[$lh->trang_thai] ?? '#000',
                'borderColor' => $colorMap[$lh->trang_thai] ?? '#000',
                'extendedProps' => [
                    'trang_thai' => $lh->trang_thai,
                    'ten_khach'  => $lh->ten_khach_hang,
                    'sdt_khach'  => $lh->sdt_khach_hang,
                    'bds'        => $tenBds,
                    'dia_diem'   => $lh->dia_diem_hen,
                    'sale_id'    => $lh->nhan_vien_sale_id,
                    'ghi_chu'    => $lh->ghi_chu_sale,
                    'nguon_phu_trach_id' => $nguonPhuTrachId,
                ]
            ];
        }
        return response()->json($events);
    }

    public function create(Request $request)
    { /* Giữ nguyên hàm create của bạn */
        $nhanVien = $this->currentNhanVien();
        $dsBds = BatDongSan::where('hien_thi', 1)->select('id', 'ma_bat_dong_san', 'tieu_de', 'nhan_vien_phu_trach_id')->get();
        $dsNguonHang = NhanVien::where('vai_tro', 'nguon_hang')->where('kich_hoat', 1)->get();
        $dsKhachHang = KhachHang::select('id', 'ho_ten', 'so_dien_thoai', 'email')->get();
        $batDongSanId = $request->bat_dong_san_id;
        $khachHangId  = $request->khach_hang_id;
        return view('admin.lich-hen.create', compact('dsBds', 'dsNguonHang', 'dsKhachHang', 'batDongSanId', 'khachHangId'));
    }

    public function store(Request $request)
    { /* Giữ nguyên hàm store của bạn */
        $nhanVien = $this->currentNhanVien();
        $request->validate([
            'bat_dong_san_id' => 'required|exists:bat_dong_san,id',
            'nhan_vien_nguon_hang_id' => 'required|exists:nhan_vien,id',
            'ten_khach_hang' => 'required|string|max:100',
            'sdt_khach_hang' => 'required|string|max:20',
            'email_khach_hang' => 'nullable|email|max:100',
            'thoi_gian_hen' => 'required|date|after:now',
            'dia_diem_hen' => 'nullable|string|max:255',
            'ghi_chu_sale' => 'nullable|string|max:1000',
        ]);
        $lichHen = LichHen::create([
            'bat_dong_san_id' => $request->bat_dong_san_id,
            'nhan_vien_nguon_hang_id' => $request->nhan_vien_nguon_hang_id,
            'ten_khach_hang' => $request->ten_khach_hang,
            'sdt_khach_hang' => $request->sdt_khach_hang,
            'email_khach_hang' => $request->email_khach_hang,
            'thoi_gian_hen' => $request->thoi_gian_hen,
            'dia_diem_hen' => $request->dia_diem_hen,
            'ghi_chu_sale' => $request->ghi_chu_sale,
            'khach_hang_id' => $request->khach_hang_id ?: null,
            'nhan_vien_sale_id' => $nhanVien->id,
            'trang_thai' => 'cho_xac_nhan',
            'nguon_dat_lich' => 'sale',
        ]);
        $this->_thongBaoNguonHang($lichHen, 'Lịch hẹn mới cần xác nhận', $nhanVien->ho_ten . ' vừa đặt lịch xem ' . optional($lichHen->batDongSan)->tieu_de);
        return redirect()->route('nhanvien.admin.lich-hen.index')->with('success', 'Đã tạo lịch hẹn và gửi yêu cầu xác nhận đến Nguồn hàng!');
    }

    public function show(LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        $lichHen->load(['khachHang', 'batDongSan.khuVuc', 'nhanVienSale', 'nhanVienNguonHang']);

        $dsNguonHang = NhanVien::where('vai_tro', 'nguon_hang')->where('kich_hoat', 1)->get();
        return view('admin.lich-hen.show', compact('lichHen', 'nhanVien', 'dsNguonHang'));
    }

    // ============================================================
    // WORKFLOW MỚI: NHẬN LỊCH -> CHUYỂN NGUỒN -> CHỐT BĐS
    // ============================================================

    /**
     * BƯỚC 1: SALE NHẬN LỊCH (Chưa báo Nguồn)
     */
    public function nhanLich(LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'sale']), 403);

        if (!is_null($lichHen->nhan_vien_sale_id)) {
            return back()->with('error', 'Lịch này đã có nhân viên khác nhận!');
        }

        $lichHen->update([
            'nhan_vien_sale_id' => $nhanVien->id,
            'trang_thai'        => 'sale_da_nhan',
        ]);

        return back()->with('success', 'Đã nhận lịch! Hãy gọi cho khách hàng để xác nhận thông tin.');
    }

    /**
     * BƯỚC 2: SALE CHUYỂN NGUỒN (Sau khi xác nhận OK)
     */
    public function tiepNhan(Request $request, LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'sale']), 403);

        // Phải là sale đã nhận lịch này mới được chuyển nguồn
        abort_unless($lichHen->trang_thai === 'sale_da_nhan' && $lichHen->nhan_vien_sale_id == $nhanVien->id, 403, 'Sai trạng thái hoặc không có quyền.');

        $request->validate([
            'nhan_vien_nguon_hang_id' => 'required|exists:nhan_vien,id',
            'ghi_chu_sale'            => 'nullable|string',
        ]);

        $lichHen->update([
            'nhan_vien_nguon_hang_id' => $request->nhan_vien_nguon_hang_id,
            'ghi_chu_sale'            => $request->ghi_chu_sale ?? $lichHen->ghi_chu_sale,
            'trang_thai'              => 'cho_xac_nhan',
        ]);

        $this->_thongBaoNguonHang($lichHen, 'Có lịch xem nhà', 'Sale ' . $nhanVien->ho_ten . ' yêu cầu mở cửa.');
        return back()->with('success', 'Đã chuyển yêu cầu cho Nguồn hàng!');
    }

    /**
     * BƯỚC 2 (Phụ): SALE TỪ CHỐI (Khách ảo, sai sđt)
     */
    public function saleTuChoi(Request $request, LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'sale']), 403);
        abort_unless($lichHen->trang_thai === 'sale_da_nhan', 422);

        $request->validate(['ly_do_tu_choi' => 'required|string|max:500']);

        $lichHen->update([
            'trang_thai'    => 'tu_choi',
            'ly_do_tu_choi' => 'Sale báo: ' . $request->ly_do_tu_choi,
            'tu_choi_at'    => now(),
        ]);

        return back()->with('success', 'Đã từ chối lịch xem nhà.');
    }

    /**
     * BƯỚC CUỐI: HOÀN THÀNH & CHỐT
     */
    public function hoanThanh(Request $request, LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'sale']), 403);
        abort_unless($lichHen->trang_thai === 'da_xac_nhan', 422);

        $request->validate([
            'ket_qua' => 'required|in:chot,khong_chot',
            'ghi_chu_sale' => 'nullable|string'
        ]);

        $lichHen->update([
            'trang_thai' => 'hoan_thanh',
            'hoan_thanh_at' => now(),
            'ghi_chu_sale' => "Kết quả: " . ($request->ket_qua == 'chot' ? 'CHỐT THÀNH CÔNG' : 'KHÔNG CHỐT') . " - " . $request->ghi_chu_sale
        ]);

        // Đổi trạng thái BĐS sang Đã bán/Đã thuê
        if ($request->ket_qua === 'chot' && $lichHen->batDongSan) {
            $lichHen->batDongSan->update(['trang_thai' => 'da_ban']);
        }

        return back()->with('success', 'Cập nhật kết quả thành công!');
    }

    // Các hàm giữ nguyên: xacNhan, baoLaiGio, saleDoiGio, tuChoi, huy, destroy...
    public function destroy(LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole('admin'), 403);
        $lichHen->delete();
        return back()->with('success', 'Đã xóa lịch hẹn!');
    }
    public function xacNhan(LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'nguon_hang']), 403);
        $lichHen->update(['trang_thai' => 'da_xac_nhan', 'xac_nhan_at' => now()]);
        $this->_thongBaoSale($lichHen, 'Lịch ĐÃ ĐƯỢC XÁC NHẬN', 'Nguồn hàng đã chốt.');
        return back()->with('success', 'Đã xác nhận lịch!');
    }
    public function tuChoi(Request $request, LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'nguon_hang']), 403);
        $request->validate(['ly_do_tu_choi' => 'required|string|max:500']);
        $lichHen->update(['trang_thai' => 'tu_choi', 'ly_do_tu_choi' => $request->ly_do_tu_choi, 'tu_choi_at' => now()]);
        $this->_thongBaoSale($lichHen, 'Lịch BỊ TỪ CHỐI', 'Lý do: ' . $request->ly_do_tu_choi);
        return back()->with('success', 'Đã từ chối lịch hẹn.');
    }
    public function huy(Request $request, LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        $request->validate(['ly_do' => 'required|string|max:500']);
        $lichHen->update(['trang_thai' => 'huy', 'ly_do_tu_choi' => 'Hủy bởi ' . $nhanVien->ho_ten . ' - Lý do: ' . $request->ly_do, 'huy_at' => now()]);
        return back()->with('success', 'Đã hủy lịch.');
    }
    public function baoLaiGio(Request $request, LichHen $lichHen)
    {
        $request->validate(['thoi_gian_hen' => 'required|date', 'ghi_chu_nguon_hang' => 'required|string']);
        $lichHen->update(['thoi_gian_hen' => $request->thoi_gian_hen, 'ghi_chu_nguon_hang' => $request->ghi_chu_nguon_hang, 'trang_thai' => 'cho_xac_nhan']);
        return back()->with('success', 'Đã báo lại giờ!');
    }
    public function saleDoiGio(Request $request, LichHen $lichHen)
    {
        $request->validate(['thoi_gian_hen' => 'required|date', 'ghi_chu_sale' => 'required|string']);
        $lichHen->update(['thoi_gian_hen' => $request->thoi_gian_hen, 'ghi_chu_sale' => $request->ghi_chu_sale, 'trang_thai' => 'cho_xac_nhan']);
        return back()->with('success', 'Đã dời lịch!');
    }

    private function currentNhanVien(): NhanVien
    {
        return Auth::guard('nhanvien')->user();
    }
    private function _thongBaoNguonHang(LichHen $lichHen, string $tieuDe, string $noiDung): void
    {
        if (!$lichHen->nhan_vien_nguon_hang_id) return;
        ThongBao::create(['loai' => 'lich_hen_moi', 'doi_tuong_nhan' => 'nhan_vien', 'doi_tuong_nhan_id' => $lichHen->nhan_vien_nguon_hang_id, 'tieu_de' => $tieuDe, 'noi_dung' => $noiDung, 'du_lieu' => json_encode(['lich_hen_id' => $lichHen->id]), 'lien_ket' => route('nhanvien.admin.lich-hen.index')]);
    }
    private function _thongBaoSale(LichHen $lichHen, string $tieuDe, string $noiDung): void
    {
        if (!$lichHen->nhan_vien_sale_id) return;
        ThongBao::create(['loai' => 'lich_hen_xac_nhan', 'doi_tuong_nhan' => 'nhan_vien', 'doi_tuong_nhan_id' => $lichHen->nhan_vien_sale_id, 'tieu_de' => $tieuDe, 'noi_dung' => $noiDung, 'du_lieu' => json_encode(['lich_hen_id' => $lichHen->id]), 'lien_ket' => route('nhanvien.admin.lich-hen.index')]);
    }

    private function _stats(NhanVien $nhanVien): array
    {
        $q = LichHen::query();
        if ($nhanVien->isSale())      $q->where('nhan_vien_sale_id', $nhanVien->id);
        if ($nhanVien->isNguonHang()) $q->where('nhan_vien_nguon_hang_id', $nhanVien->id);

        return [
            'moi_dat'       => LichHen::where('trang_thai', 'moi_dat')->whereNull('nhan_vien_sale_id')->count(), // Tính chung hệ thống
            'sale_da_nhan'  => (clone $q)->where('trang_thai', 'sale_da_nhan')->count(),
            'cho_xac_nhan'  => (clone $q)->where('trang_thai', 'cho_xac_nhan')->count(),
            'da_xac_nhan'   => (clone $q)->where('trang_thai', 'da_xac_nhan')->count(),
            'hoan_thanh'    => (clone $q)->where('trang_thai', 'hoan_thanh')->count(),
            'tu_choi'       => (clone $q)->where('trang_thai', 'tu_choi')->count(),
            'huy'           => (clone $q)->where('trang_thai', 'huy')->count(),
            'hom_nay'       => (clone $q)->whereDate('thoi_gian_hen', today())->whereNotIn('trang_thai', ['hoan_thanh', 'huy', 'tu_choi'])->count(),
        ];
    }
}
