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
    /**
     * Danh sách lịch hẹn — lọc theo role.
     * - Admin  : xem tất cả
     * - Sale   : chỉ xem lịch do mình phụ trách
     * - NguonHang: chỉ xem lịch được assign cho mình
     */
    public function index(Request $request)
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        $query = LichHen::with(['khachHang', 'batDongSan', 'nhanVienSale', 'nhanVienNguonHang'])
            ->when($request->trang_thai, fn($q) => $q->where('trang_thai', $request->trang_thai))
            ->when($request->ngay,       fn($q) => $q->whereDate('thoi_gian_hen', $request->ngay))
            ->when($request->bat_dong_san_id, fn($q) => $q->where('bat_dong_san_id', $request->bat_dong_san_id));

        if ($nhanVien->isSale()) {
            $query->where('nhan_vien_sale_id', $nhanVien->id);
        } elseif ($nhanVien->isNguonHang()) {
            $query->where('nhan_vien_nguon_hang_id', $nhanVien->id);
        }

        $lichHen = $query->orderByRaw("
            CASE trang_thai
                WHEN 'moi_dat'      THEN 1
                WHEN 'cho_xac_nhan' THEN 2
                WHEN 'da_xac_nhan'  THEN 3
                WHEN 'hoan_thanh'   THEN 4
                WHEN 'tu_choi'      THEN 5
                WHEN 'huy'          THEN 6
                ELSE 9
            END
        ")->orderBy('thoi_gian_hen')->paginate(20)->withQueryString();

        // Thống kê nhanh cho tab-count
        $stats = $this->_stats($nhanVien);

        // Danh sách để filter
        $dsNguonHang = NhanVien::where('vai_tro', 'nguon_hang')->where('kich_hoat', 1)->get();
        $dsSale      = $nhanVien->isAdmin()
            ? NhanVien::where('vai_tro', 'sale')->where('kich_hoat', 1)->get()
            : collect();

        return view('admin.lich-hen.index', compact(
            'lichHen', 'stats', 'dsNguonHang', 'dsSale'
        ));
    }

    /**
     * Form tạo lịch hẹn mới (Sale / Admin).
     */
    public function create(Request $request)
    {
        $nhanVien    = Auth::guard('nhanvien')->user();
        $dsBds       = BatDongSan::where('kich_hoat', 1)->select('id','ma_can','ten_bat_dong_san')->get();
        $dsNguonHang = NhanVien::where('vai_tro', 'nguon_hang')->where('kich_hoat', 1)->get();
        $dsKhachHang = KhachHang::where('kich_hoat', 1)->select('id','ho_ten','so_dien_thoai','email')->get();

        // Pre-fill nếu đến từ trang BDS hoặc KH
        $batDongSanId = $request->bat_dong_san_id;
        $khachHangId  = $request->khach_hang_id;

        return view('admin.lich-hen.create', compact(
            'dsBds', 'dsNguonHang', 'dsKhachHang', 'batDongSanId', 'khachHangId'
        ));
    }

    /**
     * Sale tạo lịch hẹn mới — trạng thái "cho_xac_nhan".
     * Tự động gửi thông báo cho nhân viên nguồn hàng.
     */
    public function store(Request $request)
    {
        $nhanVien = Auth::guard('nhanvien')->user();

        $request->validate([
            'bat_dong_san_id'         => 'required|exists:bat_dong_san,id',
            'nhan_vien_nguon_hang_id' => 'required|exists:nhan_vien,id',
            'ten_khach_hang'          => 'required|string|max:100',
            'sdt_khach_hang'          => 'required|string|max:20',
            'email_khach_hang'        => 'nullable|email|max:100',
            'thoi_gian_hen'           => 'required|date|after:now',
            'dia_diem_hen'            => 'nullable|string|max:255',
            'ghi_chu_sale'            => 'nullable|string|max:1000',
        ]);

        $lichHen = LichHen::create([
            'bat_dong_san_id'         => $request->bat_dong_san_id,
            'nhan_vien_nguon_hang_id' => $request->nhan_vien_nguon_hang_id,
            'ten_khach_hang'          => $request->ten_khach_hang,
            'sdt_khach_hang'          => $request->sdt_khach_hang,
            'email_khach_hang'        => $request->email_khach_hang,
            'thoi_gian_hen'           => $request->thoi_gian_hen,
            'dia_diem_hen'            => $request->dia_diem_hen,
            'ghi_chu_sale'            => $request->ghi_chu_sale,
            'khach_hang_id'           => $request->khach_hang_id ?: null,
            'nhan_vien_sale_id'       => $nhanVien->id,
            'trang_thai'              => 'cho_xac_nhan',
            'nguon_dat_lich'          => 'sale',
        ]);

        $this->_thongBaoNguonHang($lichHen, 'Lịch hẹn mới cần xác nhận',
            $nhanVien->ho_ten . ' vừa đặt lịch xem ' . optional($lichHen->batDongSan)->ten_bat_dong_san
        );

        return redirect()->route('nhanvien.admin.lich-hen.index')
            ->with('success', 'Đã tạo lịch hẹn và gửi yêu cầu xác nhận đến Nguồn hàng!');
    }

    /**
     * Sale tiếp nhận lịch từ khách hàng tự đặt (moi_dat → cho_xac_nhan)
     * — assign nguồn hàng, đặt lịch chính thức.
     */
    public function tiepNhan(Request $request, LichHen $lichHen)
    {
        $nhanVien = Auth::guard('nhanvien')->user();
        abort_unless($nhanVien->hasRole(['admin', 'sale']), 403);
        abort_unless($lichHen->trang_thai === 'moi_dat', 422, 'Lịch hẹn này đã được xử lý.');

        $request->validate([
            'nhan_vien_nguon_hang_id' => 'required|exists:nhan_vien,id',
            'thoi_gian_hen'           => 'required|date|after:now',
            'dia_diem_hen'            => 'nullable|string|max:255',
            'ghi_chu_sale'            => 'nullable|string|max:1000',
        ]);

        $lichHen->update([
            'nhan_vien_sale_id'       => $nhanVien->id,
            'nhan_vien_nguon_hang_id' => $request->nhan_vien_nguon_hang_id,
            'thoi_gian_hen'           => $request->thoi_gian_hen,
            'dia_diem_hen'            => $request->dia_diem_hen,
            'ghi_chu_sale'            => $request->ghi_chu_sale,
            'trang_thai'              => 'cho_xac_nhan',
        ]);

        $this->_thongBaoNguonHang($lichHen, 'Lịch hẹn mới cần xác nhận',
            $nhanVien->ho_ten . ' đã tiếp nhận và yêu cầu xác nhận lịch xem ' .
            optional($lichHen->batDongSan)->ten_bat_dong_san
        );

        return back()->with('success', 'Đã tiếp nhận và gửi xác nhận đến Nguồn hàng!');
    }

    /**
     * Nguồn hàng xác nhận lịch (cho_xac_nhan → da_xac_nhan).
     */
    public function xacNhan(LichHen $lichHen)
    {
        $nhanVien = Auth::guard('nhanvien')->user();
        abort_unless($nhanVien->hasRole(['admin', 'nguon_hang']), 403);
        abort_unless(in_array($lichHen->trang_thai, ['cho_xac_nhan']), 422, 'Không thể xác nhận lịch này.');

        $lichHen->update([
            'trang_thai'  => 'da_xac_nhan',
            'xac_nhan_at' => now(),
        ]);

        $this->_thongBaoSale($lichHen, 'Lịch hẹn đã được xác nhận ✓',
            'Nguồn hàng đã xác nhận lịch xem ' . optional($lichHen->batDongSan)->ten_bat_dong_san .
            ' lúc ' . $lichHen->thoi_gian_hen->format('H:i d/m/Y')
        );

        return back()->with('success', 'Đã xác nhận lịch hẹn!');
    }

    /**
     * Nguồn hàng từ chối lịch (cho_xac_nhan → tu_choi).
     */
    public function tuChoi(Request $request, LichHen $lichHen)
    {
        $nhanVien = Auth::guard('nhanvien')->user();
        abort_unless($nhanVien->hasRole(['admin', 'nguon_hang']), 403);
        abort_unless(in_array($lichHen->trang_thai, ['cho_xac_nhan']), 422);

        $request->validate(['ly_do_tu_choi' => 'required|string|max:500']);

        $lichHen->update([
            'trang_thai'    => 'tu_choi',
            'ly_do_tu_choi' => $request->ly_do_tu_choi,
            'tu_choi_at'    => now(),
        ]);

        $this->_thongBaoSale($lichHen, 'Lịch hẹn bị từ chối',
            'Lý do: ' . $request->ly_do_tu_choi
        );

        return back()->with('success', 'Đã từ chối lịch hẹn.');
    }

    /**
     * Sale đánh dấu hoàn thành (da_xac_nhan → hoan_thanh).
     */
    public function hoanThanh(LichHen $lichHen)
    {
        $nhanVien = Auth::guard('nhanvien')->user();
        abort_unless($nhanVien->hasRole(['admin', 'sale']), 403);
        abort_unless($lichHen->trang_thai === 'da_xac_nhan', 422);

        $lichHen->update(['trang_thai' => 'hoan_thanh', 'hoan_thanh_at' => now()]);

        return back()->with('success', 'Đã đánh dấu hoàn thành!');
    }

    /**
     * Hủy lịch hẹn (Sale / Admin, ở các trạng thái chưa xong).
     */
    public function huy(Request $request, LichHen $lichHen)
    {
        $nhanVien = Auth::guard('nhanvien')->user();
        abort_unless($nhanVien->hasRole(['admin', 'sale']), 403);
        abort_unless(!in_array($lichHen->trang_thai, ['hoan_thanh', 'huy']), 422);

        $lichHen->update([
            'trang_thai'    => 'huy',
            'ly_do_tu_choi' => $request->ly_do ?? null,
            'huy_at'        => now(),
        ]);

        return back()->with('success', 'Đã hủy lịch hẹn.');
    }

    /**
     * Chi tiết / timeline 1 lịch hẹn.
     */
    public function show(LichHen $lichHen)
    {
        $nhanVien = Auth::guard('nhanvien')->user();
        $lichHen->load(['khachHang', 'batDongSan.khuVuc', 'nhanVienSale', 'nhanVienNguonHang']);
        return view('admin.lich-hen.show', compact('lichHen', 'nhanVien'));
    }

    // ──────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────

    private function _thongBaoNguonHang(LichHen $lichHen, string $tieuDe, string $noiDung): void
    {
        if (!$lichHen->nhan_vien_nguon_hang_id) return;
        ThongBao::create([
            'loai'              => 'lich_hen_moi',
            'doi_tuong_nhan'    => 'nhan_vien',
            'doi_tuong_nhan_id' => $lichHen->nhan_vien_nguon_hang_id,
            'tieu_de'           => $tieuDe,
            'noi_dung'          => $noiDung,
            'du_lieu'           => json_encode(['lich_hen_id' => $lichHen->id]),
            'lien_ket'          => route('nhanvien.admin.lich-hen.index'),
        ]);
    }

    private function _thongBaoSale(LichHen $lichHen, string $tieuDe, string $noiDung): void
    {
        if (!$lichHen->nhan_vien_sale_id) return;
        ThongBao::create([
            'loai'              => 'lich_hen_xac_nhan',
            'doi_tuong_nhan'    => 'nhan_vien',
            'doi_tuong_nhan_id' => $lichHen->nhan_vien_sale_id,
            'tieu_de'           => $tieuDe,
            'noi_dung'          => $noiDung,
            'du_lieu'           => json_encode(['lich_hen_id' => $lichHen->id]),
            'lien_ket'          => route('nhanvien.admin.lich-hen.index'),
        ]);
    }

    private function _stats(NhanVien $nhanVien): array
    {
        $q = LichHen::query();
        if ($nhanVien->isSale())      $q->where('nhan_vien_sale_id', $nhanVien->id);
        if ($nhanVien->isNguonHang()) $q->where('nhan_vien_nguon_hang_id', $nhanVien->id);

        return [
            'moi_dat'       => (clone $q)->where('trang_thai', 'moi_dat')->count(),
            'cho_xac_nhan'  => (clone $q)->where('trang_thai', 'cho_xac_nhan')->count(),
            'da_xac_nhan'   => (clone $q)->where('trang_thai', 'da_xac_nhan')->count(),
            'hoan_thanh'    => (clone $q)->where('trang_thai', 'hoan_thanh')->count(),
            'tu_choi'       => (clone $q)->where('trang_thai', 'tu_choi')->count(),
            'huy'           => (clone $q)->where('trang_thai', 'huy')->count(),
            'hom_nay'       => (clone $q)->whereDate('thoi_gian_hen', today())->whereNotIn('trang_thai', ['hoan_thanh','huy','tu_choi'])->count(),
        ];
    }
}
