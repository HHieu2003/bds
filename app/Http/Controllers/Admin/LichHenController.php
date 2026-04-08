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
     * HIỂN THỊ GIAO DIỆN THEO PHÂN QUYỀN
     */
    public function index(Request $request)
    {
        $nhanVien = $this->currentNhanVien();
        $stats = $this->_stats($nhanVien);

        // ========================================================
        // 1. XÂY DỰNG DATA CHO TAB "DANH SÁCH TOÀN BỘ" (CHUNG CHO MỌI ROLE)
        // ========================================================
        $queryList = LichHen::with(['khachHang', 'batDongSan.chuNha', 'nhanVienSale', 'nhanVienNguonHang']);

        // Phân quyền nhìn thấy trên Danh sách
        if ($nhanVien->isSale()) {
            $queryList->where(function ($q) use ($nhanVien) {
                $q->where('nhan_vien_sale_id', $nhanVien->id)
                    ->orWhere('trang_thai', 'moi_dat')
                    ->orWhere(function ($qWeb) {
                        $qWeb->where('nguon_dat_lich', 'website')
                            ->whereNull('nhan_vien_sale_id')
                            ->whereNotIn('trang_thai', ['huy', 'tu_choi', 'hoan_thanh']);
                    });
            });
        } elseif ($nhanVien->isNguonHang()) {
            $queryList->where('nhan_vien_nguon_hang_id', $nhanVien->id);
        }
        // Nếu là Admin thì queryList lấy tất cả

        // Bộ lọc tìm kiếm cho Tab Danh sách
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

        // Lấy dữ liệu phân trang
        $lichHensList = $queryList->orderBy('thoi_gian_hen', 'desc')->paginate(15)->withQueryString();

        // ========================================================
        // 2. TRẢ VỀ VIEW TƯƠNG ỨNG VỚI ROLE (KÈM DỮ LIỆU LIST VÀ TAB)
        // ========================================================
        if ($nhanVien->isNguonHang()) {
            // Data riêng cho Tab "Cần xử lý" của Nguồn hàng
            $lichHensTodo = LichHen::with(['khachHang', 'batDongSan.chuNha', 'nhanVienSale'])
                ->where('nhan_vien_nguon_hang_id', $nhanVien->id)
                ->whereIn('trang_thai', ['cho_xac_nhan', 'da_xac_nhan'])
                ->orderBy('thoi_gian_hen', 'asc')
                ->get();

            return view('admin.lich-hen.nguon_hang', compact('lichHensTodo', 'lichHensList', 'stats'));
        }

        // Admin có thể vào cả 2 giao diện: Calendar/List và To-do của Nguồn hàng.
        if ($nhanVien->isAdmin() && $request->get('giao_dien') === 'nguon_hang') {
            $lichHensTodo = LichHen::with(['khachHang', 'batDongSan.chuNha', 'nhanVienSale', 'nhanVienNguonHang'])
                ->whereIn('trang_thai', ['cho_xac_nhan', 'da_xac_nhan'])
                ->orderBy('thoi_gian_hen', 'asc')
                ->get();

            $adminMode = true;
            return view('admin.lich-hen.nguon_hang', compact('lichHensTodo', 'lichHensList', 'stats', 'adminMode'));
        }

        // Data riêng cho Modal/Tab Calendar của Sale & Admin
        $dsNguonHang = NhanVien::where('vai_tro', 'nguon_hang')->where('kich_hoat', 1)->get();
        return view('admin.lich-hen.index', compact('stats', 'dsNguonHang', 'nhanVien', 'lichHensList'));
    }
    /**
     * API TRẢ VỀ DỮ LIỆU CHO FULLCALENDAR (Dành cho Sale/Admin)
     */
    public function apiEvents(Request $request)
    {
        $nhanVien = $this->currentNhanVien();
        $query = LichHen::with(['khachHang', 'batDongSan']);

        if ($nhanVien->isSale()) {
            // Sale thấy:
            // 1) Lịch của chính mình
            // 2) Lịch mới khách đặt (moi_dat)
            // 3) Lịch từ website chưa có sale phụ trách để tránh sót kèo khách
            $query->where(function ($q) use ($nhanVien) {
                $q->where('nhan_vien_sale_id', $nhanVien->id)
                    ->orWhere('trang_thai', 'moi_dat')
                    ->orWhere(function ($qWeb) {
                        $qWeb->where('nguon_dat_lich', 'website')
                            ->whereNull('nhan_vien_sale_id')
                            ->whereNotIn('trang_thai', ['huy', 'tu_choi', 'hoan_thanh']);
                    });
            });
        }

        $lichHens = $query->get();
        $events = [];

        $colorMap = [
            'moi_dat'      => '#f59e0b', // Cam: Cần người nhận (Cướp lịch)
            'cho_xac_nhan' => '#3b82f6', // Xanh dương: Đang chờ nguồn gọi chủ
            'da_xac_nhan'  => '#10b981', // Xanh lá: Xách xe đi xem
            'hoan_thanh'   => '#6b7280', // Xám: Đã xong
            'tu_choi'      => '#ef4444', // Đỏ
            'huy'          => '#ef4444', // Đỏ
        ];

        foreach ($lichHens as $lh) {
            $tenBds = $lh->batDongSan ? $lh->batDongSan->tieu_de : 'Nhà lẻ / Chưa xác định';
            $noteSale = mb_strtolower((string) $lh->ghi_chu_sale);
            $noteNguon = mb_strtolower((string) $lh->ghi_chu_nguon_hang);
            $isDoiGio = str_contains((string) $lh->ghi_chu_sale, '[DOI_GIO]')
                || str_contains((string) $lh->ghi_chu_nguon_hang, '[DOI_GIO]')
                || str_contains($noteSale, 'doi gio')
                || str_contains($noteSale, 'dời')
                || str_contains($noteNguon, 'doi gio')
                || str_contains($noteNguon, 'dời');

            $events[] = [
                'id' => $lh->id,
                'title' => ($isDoiGio ? '[DOI GIO] ' : '') . date('H:i', strtotime($lh->thoi_gian_hen)) . ' - ' . $lh->ten_khach_hang,
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
                    'is_doi_gio' => $isDoiGio,
                    'ly_do_huy'  => $lh->trang_thai == 'tu_choi' ? $lh->ly_do_tu_choi : ($lh->trang_thai == 'hoan_thanh' ? $lh->ghi_chu_sale : null),
                ]
            ];
        }
        return response()->json($events);
    }

    /**
     * FORM TẠO LỊCH HẸN THỦ CÔNG (Cho Sale/Admin)
     */
    public function create(Request $request)
    {
        $nhanVien    = $this->currentNhanVien();
        $dsBds       = BatDongSan::where('hien_thi', 1)->select('id', 'ma_bat_dong_san', 'tieu_de')->get();
        $dsNguonHang = NhanVien::where('vai_tro', 'nguon_hang')->where('kich_hoat', 1)->get();
        $dsKhachHang = KhachHang::select('id', 'ho_ten', 'so_dien_thoai', 'email')->get();

        $batDongSanId = $request->bat_dong_san_id;
        $khachHangId  = $request->khach_hang_id;

        return view('admin.lich-hen.create', compact(
            'dsBds',
            'dsNguonHang',
            'dsKhachHang',
            'batDongSanId',
            'khachHangId'
        ));
    }

    /**
     * LƯU LỊCH HẸN MỚI
     */
    public function store(Request $request)
    {
        $nhanVien = $this->currentNhanVien();

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
            'trang_thai'              => 'cho_xac_nhan', // Tạo xong là chờ Nguồn duyệt luôn
            'nguon_dat_lich'          => 'sale',
        ]);

        $this->_thongBaoNguonHang(
            $lichHen,
            'Lịch hẹn mới cần xác nhận',
            $nhanVien->ho_ten . ' vừa đặt lịch xem ' . optional($lichHen->batDongSan)->tieu_de
        );

        return redirect()->route('nhanvien.admin.lich-hen.index')->with('success', 'Đã tạo lịch hẹn và gửi yêu cầu xác nhận đến Nguồn hàng!');
    }

    /**
     * CHI TIẾT LỊCH HẸN
     */
    public function show(LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        $lichHen->load(['khachHang', 'batDongSan.khuVuc', 'nhanVienSale', 'nhanVienNguonHang']);
        return view('admin.lich-hen.show', compact('lichHen', 'nhanVien'));
    }

    // ============================================================
    // CÁC HÀM XỬ LÝ WORKFLOW (QUY TRÌNH)
    // ============================================================

    /**
     * SALE NHẬN LỊCH TỪ KHÁCH (moi_dat -> cho_xac_nhan)
     */
    public function tiepNhan(Request $request, LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'sale']), 403);

        if ($lichHen->trang_thai !== 'moi_dat') {
            return back()->with('error', 'Rất tiếc! Lịch hẹn này đã có người khác nhận xử lý.');
        }

        $request->validate([
            'nhan_vien_nguon_hang_id' => 'required|exists:nhan_vien,id',
            'dia_diem_hen'            => 'nullable|string|max:255',
        ]);

        $lichHen->update([
            'nhan_vien_sale_id'       => $nhanVien->id,
            'nhan_vien_nguon_hang_id' => $request->nhan_vien_nguon_hang_id,
            'dia_diem_hen'            => $request->dia_diem_hen ?? $lichHen->dia_diem_hen,
            'trang_thai'              => 'cho_xac_nhan',
        ]);

        $this->_thongBaoNguonHang($lichHen, 'Có lịch hẹn mới cần lấy chìa khóa', 'Sale ' . $nhanVien->ho_ten . ' vừa yêu cầu xác nhận lịch.');
        return back()->with('success', 'Bạn đã nhận lịch thành công! Đang chờ Nguồn hàng xác nhận với Chủ nhà.');
    }

    /**
     * NGUỒN HÀNG XÁC NHẬN LỊCH (cho_xac_nhan -> da_xac_nhan)
     */
    public function xacNhan(LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'nguon_hang']), 403, 'Chỉ Nguồn hàng mới có quyền xác nhận lịch này.');
        abort_unless(in_array($lichHen->trang_thai, ['cho_xac_nhan']), 422, 'Lỗi trạng thái.');

        $lichHen->update([
            'trang_thai'  => 'da_xac_nhan',
            'xac_nhan_at' => now(),
        ]);

        $this->_thongBaoSale(
            $lichHen,
            'Lịch hẹn ĐÃ ĐƯỢC XÁC NHẬN ✓',
            'Nguồn hàng đã chốt xong với chủ nhà lúc ' . $lichHen->thoi_gian_hen->format('H:i d/m/Y') . '. Hãy dẫn khách đi xem.'
        );

        return back()->with('success', 'Đã xác nhận lịch hẹn thành công!');
    }

    /**
     * NGUỒN HÀNG BÁO LẠI GIỜ NẾU CHỦ VẮNG NHÀ
     */
    public function baoLaiGio(Request $request, LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'nguon_hang']), 403);
        abort_unless(in_array($lichHen->trang_thai, ['cho_xac_nhan', 'da_xac_nhan']), 422);

        $request->validate([
            'thoi_gian_hen' => 'required|date',
            'ghi_chu_nguon_hang' => 'required|string|max:1000',
        ]);

        $oldTime = optional($lichHen->thoi_gian_hen)->format('H:i d/m/Y');
        $newTime = date('H:i d/m/Y', strtotime($request->thoi_gian_hen));
        $doiGioNote = '[DOI_GIO][NGUON] ' . $oldTime . ' -> ' . $newTime . ' | ' . trim($request->ghi_chu_nguon_hang);

        $lichHen->update([
            'thoi_gian_hen' => $request->thoi_gian_hen,
            'ghi_chu_nguon_hang' => $doiGioNote,
            'trang_thai' => 'cho_xac_nhan' // Vẫn giữ trạng thái chờ để Sale nắm được
        ]);

        $this->_thongBaoSale($lichHen, 'Nguồn hàng BÁO ĐỔI GIỜ', 'Chủ nhà báo lại: ' . $request->ghi_chu_nguon_hang);
        return back()->with('success', 'Đã báo lại giờ mới cho Sale!');
    }

    /**
     * SALE BÁO LẠI GIỜ (Khách hàng bận đột xuất)
     */
    public function saleDoiGio(Request $request, LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'sale']), 403);
        abort_unless(in_array($lichHen->trang_thai, ['cho_xac_nhan', 'da_xac_nhan']), 422);

        $request->validate([
            'thoi_gian_hen' => 'required|date',
            'ghi_chu_sale'  => 'required|string|max:1000',
        ]);

        $oldTime = optional($lichHen->thoi_gian_hen)->format('H:i d/m/Y');
        $newTime = date('H:i d/m/Y', strtotime($request->thoi_gian_hen));
        $doiGioNote = '[DOI_GIO][SALE] ' . $oldTime . ' -> ' . $newTime . ' | ' . trim($request->ghi_chu_sale);

        $lichHen->update([
            'thoi_gian_hen' => $request->thoi_gian_hen,
            'ghi_chu_sale'  => $doiGioNote,
            'trang_thai'    => 'cho_xac_nhan',
        ]);

        $this->_thongBaoNguonHang(
            $lichHen,
            'Khách hàng ĐỔI GIỜ ĐỘT XUẤT',
            'Sale ' . $nhanVien->ho_ten . ' dời lịch thành: ' . date('H:i d/m', strtotime($request->thoi_gian_hen)) . '. Lời nhắn: ' . $request->ghi_chu_sale
        );

        return back()->with('success', 'Đã dời lịch và báo cho Nguồn hàng!');
    }

    /**
     * NGUỒN HÀNG TỪ CHỐI (cho_xac_nhan -> tu_choi)
     */
    public function tuChoi(Request $request, LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'nguon_hang']), 403);
        abort_unless(in_array($lichHen->trang_thai, ['cho_xac_nhan']), 422);

        $request->validate(['ly_do_tu_choi' => 'required|string|max:500']);

        $lichHen->update([
            'trang_thai'    => 'tu_choi',
            'ly_do_tu_choi' => $request->ly_do_tu_choi,
            'tu_choi_at'    => now(),
        ]);

        $this->_thongBaoSale($lichHen, 'Lịch hẹn BỊ TỪ CHỐI', 'Lý do: ' . $request->ly_do_tu_choi);
        return back()->with('success', 'Đã từ chối lịch hẹn.');
    }

    /**
     * SALE BÁO CÁO HOÀN THÀNH (da_xac_nhan -> hoan_thanh)
     */
    public function hoanThanh(Request $request, LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'sale']), 403);
        abort_unless($lichHen->trang_thai === 'da_xac_nhan', 422);

        $lichHen->update([
            'trang_thai' => 'hoan_thanh',
            'hoan_thanh_at' => now(),
            'ghi_chu_sale' => $request->ghi_chu_sale // Note lại ý kiến của khách (chê, ưng, v.v)
        ]);

        return back()->with('success', 'Đã đánh dấu hoàn thành lịch hẹn!');
    }

    /**
     * HỦY LỊCH (Từ phía Sale / Khách bận)
     */
    public function huy(Request $request, LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'sale', 'nguon_hang']), 403);
        abort_unless(in_array($lichHen->trang_thai, ['moi_dat', 'cho_xac_nhan', 'da_xac_nhan']), 422);

        $request->validate(['ly_do' => 'required|string|max:500']);

        $lichHen->update([
            'trang_thai'    => 'huy',
            'ly_do_tu_choi' => 'Hủy bởi ' . $nhanVien->ho_ten . ' - Lý do: ' . $request->ly_do,
            'huy_at'        => now(),
        ]);

        if ($nhanVien->isSale() && $lichHen->nhan_vien_nguon_hang_id) {
            $this->_thongBaoNguonHang($lichHen, 'Lịch hẹn BỊ HỦY', 'Sale ' . $nhanVien->ho_ten . ' hủy lịch. Lý do: ' . $request->ly_do);
        }
        if ($nhanVien->isNguonHang() && $lichHen->nhan_vien_sale_id) {
            $this->_thongBaoSale($lichHen, 'Lịch hẹn BỊ HỦY', 'Nguồn ' . $nhanVien->ho_ten . ' hủy lịch. Lý do: ' . $request->ly_do);
        }

        return back()->with('success', 'Đã hủy lịch hẹn thành công.');
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    /**
     * Lấy đúng user nhân viên đang đăng nhập (guard nhanvien) với kiểu dữ liệu rõ ràng.
     */
    private function currentNhanVien(): NhanVien
    {
        $user = Auth::guard('nhanvien')->user();
        abort_unless($user instanceof NhanVien, 401);

        return $user;
    }

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
            'hom_nay'       => (clone $q)->whereDate('thoi_gian_hen', today())->whereNotIn('trang_thai', ['hoan_thanh', 'huy', 'tu_choi'])->count(),
        ];
    }
}
