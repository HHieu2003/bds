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
        // 1. DATA CHO TAB "DANH SÁCH TOÀN BỘ" (Cả Sale & Nguồn đều dùng)
        // ========================================================
        $queryList = LichHen::with(['khachHang', 'batDongSan.chuNha', 'batDongSan.khuVuc.duAn', 'nhanVienSale', 'nhanVienNguonHang']);

        if ($nhanVien->isSale()) {
            $queryList->where(function ($q) use ($nhanVien) {
                $q->where('nhan_vien_sale_id', $nhanVien->id)
                    ->orWhere(function ($qWeb) {
                        $qWeb->where('trang_thai', 'moi_dat')->whereNull('nhan_vien_sale_id');
                    });
            });
        } elseif ($nhanVien->isNguonHang()) {
            $queryList->where('nhan_vien_nguon_hang_id', $nhanVien->id);
        }

        // BỘ LỌC CHUNG CHO DANH SÁCH
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
        if ($request->filled('trang_thai')) $queryList->where('trang_thai', $request->trang_thai);
        if ($request->filled('tu_ngay')) $queryList->whereDate('thoi_gian_hen', '>=', $request->tu_ngay);
        if ($request->filled('den_ngay')) $queryList->whereDate('thoi_gian_hen', '<=', $request->den_ngay);

        $lichHensList = $queryList->orderBy('thoi_gian_hen', 'desc')->paginate(15)->withQueryString();

        // ========================================================
        // 2. DATA CHO VIEW NGUỒN HÀNG (ĐÃ THÊM LOGIC LỌC)
        // ========================================================
        if ($nhanVien->isNguonHang() || ($nhanVien->isAdmin() && $request->get('giao_dien') === 'nguon_hang')) {
            $queryTodo = LichHen::with(['khachHang', 'batDongSan.chuNha', 'batDongSan.khuVuc.duAn', 'nhanVienSale'])
                ->where('nhan_vien_nguon_hang_id', $nhanVien->id ?? null)
                ->when($nhanVien->isAdmin(), function ($q) {
                    $q->orWhereNotNull('nhan_vien_nguon_hang_id');
                });

            // Nếu không chọn trạng thái lọc cụ thể ở form lọc trên giao diện Cần xử lý, mặc định lấy 3 trạng thái
            if (!$request->filled('todo_trang_thai')) {
                $queryTodo->whereIn('trang_thai', ['cho_xac_nhan', 'cho_sale_xac_nhan_doi_gio', 'da_xac_nhan']);
            }

            // BỘ LỌC DÀNH RIÊNG CHO TAB CẦN XỬ LÝ (Sử dụng prefix 'todo_' để không đụng chạm với Tab Danh Sách)
            if ($request->filled('todo_tim_kiem')) {
                $kw = '%' . $request->todo_tim_kiem . '%';
                $queryTodo->where(function ($q) use ($kw) {
                    $q->whereHas('batDongSan', function ($qBds) use ($kw) {
                        $qBds->where('tieu_de', 'like', $kw);
                    })->orWhereHas('nhanVienSale', function ($qSale) use ($kw) {
                        $qSale->where('ho_ten', 'like', $kw);
                    });
                });
            }

            if ($request->filled('todo_trang_thai')) {
                // Nếu chọn trạng thái cụ thể
                $queryTodo->where('trang_thai', $request->todo_trang_thai);
            }

            if ($request->filled('todo_tu_ngay')) {
                $queryTodo->whereDate('thoi_gian_hen', '>=', $request->todo_tu_ngay);
            }
            if ($request->filled('todo_den_ngay')) {
                $queryTodo->whereDate('thoi_gian_hen', '<=', $request->todo_den_ngay);
            }

            // Paginate hoặc Get tùy ý bạn. Ở đây lấy get() vì view cũ đang dùng Collection. Nếu dữ liệu lớn nên chuyển sang paginate
            $lichHensTodo = $queryTodo->orderBy('thoi_gian_hen', 'asc')->get();

            $adminMode = $nhanVien->isAdmin();
            return view('admin.lich-hen.nguon_hang', compact('lichHensTodo', 'lichHensList', 'stats', 'adminMode'));
        }

        // ========================================================
        // 3. DATA CHO VIEW SALE & ADMIN
        // ========================================================
        $dsNguonHang = NhanVien::where('vai_tro', 'nguon_hang')->where('kich_hoat', 1)->get();

        $lichHenMoiItems = LichHen::with(['batDongSan'])
            ->where('trang_thai', 'moi_dat')
            ->whereNull('nhan_vien_sale_id')
            ->orderBy('created_at', 'desc')
            ->paginate(12, ['*'], 'lh_page')->withQueryString();

        $lichHenDangXuLyItems = LichHen::with(['batDongSan', 'nhanVienNguonHang'])
            ->where('nhan_vien_sale_id', $nhanVien->id)
            ->whereIn('trang_thai', ['sale_da_nhan', 'cho_xac_nhan', 'cho_sale_xac_nhan_doi_gio', 'da_xac_nhan'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15, ['*'], 'dxl_page')->withQueryString();

        return view('admin.lich-hen.index', compact(
            'stats',
            'dsNguonHang',
            'nhanVien',
            'lichHensList',
            'lichHenMoiItems',
            'lichHenDangXuLyItems'
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
        } elseif ($nhanVien->isNguonHang()) {
             $query->where('nhan_vien_nguon_hang_id', $nhanVien->id);
        }

        $lichHens = $query->get();
        $events = [];

        $colorMap = [
            'moi_dat'      => '#f59e0b',
            'sale_da_nhan' => '#8b5cf6',
            'cho_xac_nhan' => '#3b82f6',
            'cho_sale_xac_nhan_doi_gio' => '#ef4444',
            'da_xac_nhan'  => '#10b981',
            'hoan_thanh'   => '#6b7280',
            'tu_choi'      => '#ef4444',
            'huy'          => '#ef4444',
        ];

        foreach ($lichHens as $lh) {
            if (!$lh->thoi_gian_hen) continue;

            $thoiGian = \Carbon\Carbon::parse($lh->thoi_gian_hen);
            
            $events[] = [
                'id' => $lh->id,
                'title' => $thoiGian->format('H:i') . ' - ' . $lh->ten_khach_hang,
                'start' => $thoiGian->format('Y-m-d\TH:i:s'), // ISO8601 format requirement for FullCalendar (fixes Safari Invalid Date)
                'backgroundColor' => $colorMap[$lh->trang_thai] ?? '#000',
                'borderColor' => $colorMap[$lh->trang_thai] ?? '#000',
                'extendedProps' => [
                    'trang_thai' => $lh->trang_thai,
                    'ten_khach'  => $lh->ten_khach_hang,
                    'sdt_khach'  => $lh->sdt_khach_hang,
                    'bds'        => optional($lh->batDongSan)->tieu_de ?? 'Nhà lẻ',
                    'dia_diem'   => $lh->dia_diem_hen,
                    'sale_id'    => $lh->nhan_vien_sale_id,
                    'nguon_phu_trach_id' => optional($lh->batDongSan)->nhan_vien_phu_trach_id,
                ]
            ];
        }
        return response()->json($events);
    }

    public function show(LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        $lichHen->load(['khachHang', 'batDongSan.khuVuc', 'nhanVienSale', 'nhanVienNguonHang']);
        $dsNguonHang = NhanVien::where('vai_tro', 'nguon_hang')->where('kich_hoat', 1)->get();
        return view('admin.lich-hen.show', compact('lichHen', 'nhanVien', 'dsNguonHang'));
    }

    public function create(Request $request)
    {
        $nhanVien = $this->currentNhanVien();
        $dsBds = BatDongSan::where('hien_thi', 1)->select('id', 'ma_bat_dong_san', 'tieu_de', 'nhan_vien_phu_trach_id')->get();
        $dsNguonHang = NhanVien::where('vai_tro', 'nguon_hang')->where('kich_hoat', 1)->get();
        $dsKhachHang = KhachHang::select('id', 'ho_ten', 'so_dien_thoai', 'email')->get();
        $batDongSanId = $request->bat_dong_san_id;
        $khachHangId  = $request->khach_hang_id;
        return view('admin.lich-hen.create', compact('dsBds', 'dsNguonHang', 'dsKhachHang', 'batDongSanId', 'khachHangId'));
    }

    public function store(Request $request)
    {
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

    public function nhanLich(LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'sale']), 403);
        if (!is_null($lichHen->nhan_vien_sale_id)) return back()->with('error', 'Lịch này đã có người nhận!');

        $lichHen->update(['nhan_vien_sale_id' => $nhanVien->id, 'trang_thai' => 'sale_da_nhan']);
        return back()->with('success', 'Đã nhận lịch! Hãy gọi khách xác nhận.');
    }

    public function tiepNhan(Request $request, LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'sale']), 403);
        $request->validate(['nhan_vien_nguon_hang_id' => 'required|exists:nhan_vien,id']);

        $lichHen->update([
            'nhan_vien_nguon_hang_id' => $request->nhan_vien_nguon_hang_id,
            'ghi_chu_sale'            => $request->ghi_chu_sale ?? $lichHen->ghi_chu_sale,
            'trang_thai'              => 'cho_xac_nhan',
        ]);
        $this->_thongBaoNguonHang($lichHen, 'Có lịch xem nhà', 'Sale yêu cầu mở cửa.');
        return $this->_smartRedirect($request, 'Đã chuyển cho Nguồn hàng!');
    }

    public function baoLaiGio(Request $request, LichHen $lichHen)
    {
        $request->validate(['thoi_gian_hen' => 'required|date', 'ghi_chu_nguon_hang' => 'required|string']);

        $lichHen->update([
            'thoi_gian_hen' => $request->thoi_gian_hen,
            'ghi_chu_nguon_hang' => $request->ghi_chu_nguon_hang,
            'trang_thai' => 'cho_sale_xac_nhan_doi_gio'
        ]);

        $this->_thongBaoSale($lichHen, 'Nguồn xin dời giờ', 'Xác nhận lại giờ với khách: ' . $request->ghi_chu_nguon_hang);
        return $this->_smartRedirect($request, 'Đã dời giờ! Đang chờ Sale xác nhận lại với khách.');
    }

    public function xacNhanDoiGio(Request $request, LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->hasRole(['admin', 'sale']), 403);

        $lichHen->update(['trang_thai' => 'da_xac_nhan', 'xac_nhan_at' => now()]);
        $this->_thongBaoNguonHang($lichHen, 'Sale chốt giờ mới', 'Khách hàng đồng ý đi xem giờ bạn đề xuất.');
        return $this->_smartRedirect($request, 'Đã chốt giờ mới với khách!');
    }

    public function saleDoiGio(Request $request, LichHen $lichHen)
    {
        $request->validate(['thoi_gian_hen' => 'required|date', 'ghi_chu_sale' => 'required|string']);
        $lichHen->update(['thoi_gian_hen' => $request->thoi_gian_hen, 'ghi_chu_sale' => $request->ghi_chu_sale, 'trang_thai' => 'cho_xac_nhan']);
        return $this->_smartRedirect($request, 'Đã dời lịch và báo cho Nguồn!');
    }

    public function hoanThanh(Request $request, LichHen $lichHen)
    {
        $request->validate(['ket_qua' => 'required|in:chot,khong_chot']);
        $lichHen->update([
            'trang_thai' => 'hoan_thanh',
            'hoan_thanh_at' => now(),
            'ghi_chu_sale' => "Kết quả: " . ($request->ket_qua == 'chot' ? 'CHỐT' : 'KHÔNG CHỐT') . " - " . $request->ghi_chu_sale
        ]);
        if ($request->ket_qua === 'chot' && $lichHen->batDongSan) $lichHen->batDongSan->update(['trang_thai' => 'da_ban']);
        return $this->_smartRedirect($request, 'Cập nhật kết quả thành công!');
    }

    public function xacNhan(LichHen $lichHen)
    {
        $lichHen->update(['trang_thai' => 'da_xac_nhan', 'xac_nhan_at' => now()]);
        $this->_thongBaoSale($lichHen, 'Lịch ĐÃ ĐƯỢC XÁC NHẬN', 'Nguồn hàng đã chốt.');
        return $this->_smartRedirect($request, 'Đã xác nhận lịch!');
    }

    public function tuChoi(Request $request, LichHen $lichHen)
    {
        $lichHen->update(['trang_thai' => 'tu_choi', 'ly_do_tu_choi' => $request->ly_do_tu_choi, 'tu_choi_at' => now()]);
        return $this->_smartRedirect($request, 'Đã từ chối lịch hẹn.');
    }
    public function saleTuChoi(Request $request, LichHen $lichHen)
    {
        $lichHen->update(['trang_thai' => 'tu_choi', 'ly_do_tu_choi' => 'Sale báo: ' . $request->ly_do_tu_choi, 'tu_choi_at' => now()]);
        return $this->_smartRedirect($request, 'Đã từ chối lịch xem nhà.');
    }

    public function huy(Request $request, LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        $lichHen->update(['trang_thai' => 'huy', 'ly_do_tu_choi' => 'Hủy bởi ' . $nhanVien->ho_ten . ' - Lý do: ' . $request->ly_do, 'huy_at' => now()]);
        return $this->_smartRedirect($request, 'Đã hủy lịch.');
    }

    public function destroy(LichHen $lichHen)
    {
        $nhanVien = $this->currentNhanVien();
        abort_unless($nhanVien->isAdmin() || ($nhanVien->isSale() && $lichHen->nhan_vien_sale_id === $nhanVien->id), 403);
        $lichHen->delete();
        return back()->with('success', 'Đã xóa lịch hẹn thành công!');
    }

    private function currentNhanVien(): NhanVien
    {
        return Auth::guard('nhanvien')->user();
    }

    /**
     * Redirect về URL trước đó (giữ tab) nếu có _redirect_back, ngược lại dùng back()
     */
    private function _smartRedirect(Request $request, string $message, string $type = 'success')
    {
        $redirectUrl = $request->input('_redirect_back');
        if ($redirectUrl && str_starts_with($redirectUrl, url('/'))) {
            return redirect($redirectUrl)->with($type, $message);
        }
        return back()->with($type, $message);
    }

    private function _thongBaoNguonHang(LichHen $lh, string $t, string $b): void
    {
        if (!$lh->nhan_vien_nguon_hang_id) return;
        ThongBao::create(['loai' => 'lich_hen', 'doi_tuong_nhan' => 'nhan_vien', 'doi_tuong_nhan_id' => $lh->nhan_vien_nguon_hang_id, 'tieu_de' => $t, 'noi_dung' => $b, 'du_lieu' => json_encode(['id' => $lh->id]), 'lien_ket' => route('nhanvien.admin.lich-hen.index')]);
    }
    private function _thongBaoSale(LichHen $lh, string $t, string $b): void
    {
        if (!$lh->nhan_vien_sale_id) return;
        ThongBao::create(['loai' => 'lich_hen', 'doi_tuong_nhan' => 'nhan_vien', 'doi_tuong_nhan_id' => $lh->nhan_vien_sale_id, 'tieu_de' => $t, 'noi_dung' => $b, 'du_lieu' => json_encode(['id' => $lh->id]), 'lien_ket' => route('nhanvien.admin.lich-hen.index')]);
    }

    private function _stats(NhanVien $nhanVien): array
    {
        $q = LichHen::query();
        if ($nhanVien->isSale())      $q->where('nhan_vien_sale_id', $nhanVien->id);
        if ($nhanVien->isNguonHang()) $q->where('nhan_vien_nguon_hang_id', $nhanVien->id);

        return [
            'moi_dat'       => LichHen::where('trang_thai', 'moi_dat')->whereNull('nhan_vien_sale_id')->count(),
            'sale_da_nhan'  => (clone $q)->where('trang_thai', 'sale_da_nhan')->count(),
            'cho_xac_nhan'  => (clone $q)->where('trang_thai', 'cho_xac_nhan')->count(),
            'cho_sale_xac_nhan_doi_gio' => (clone $q)->where('trang_thai', 'cho_sale_xac_nhan_doi_gio')->count(),
            'da_xac_nhan'   => (clone $q)->where('trang_thai', 'da_xac_nhan')->count(),
            'hoan_thanh'    => (clone $q)->where('trang_thai', 'hoan_thanh')->count(),
            'tu_choi'       => (clone $q)->where('trang_thai', 'tu_choi')->count(),
            'huy'           => (clone $q)->where('trang_thai', 'huy')->count(),
        ];
    }
}
