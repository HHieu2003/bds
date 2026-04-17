<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChuNha;
use App\Models\NhanVien;
use App\Models\DuAn;
use App\Models\BatDongSan;
use Illuminate\Http\Request;

class ChuNhaController extends Controller
{
    public function index(Request $request)
    {
        $query = ChuNha::with([
            'nhanVienPhuTrach',
            'batDongSans' => function ($q) {
                $q->select('id', 'chu_nha_id', 'du_an_id', 'toa', 'ma_can', 'nhu_cau');
            },
            'batDongSans.duAn:id,ten_du_an'
        ])->withCount('batDongSans');

        // Lọc theo từ khóa
        if ($request->filled('tim_kiem')) {
            $kw = '%' . $request->tim_kiem . '%';
            $query->where(function ($q) use ($kw) {
                $q->where('ho_ten', 'like', $kw)
                    ->orWhere('so_dien_thoai', 'like', $kw)
                    ->orWhere('cccd', 'like', $kw)
                    ->orWhereHas('batDongSans', function ($qBds) use ($kw) {
                        $qBds->where('ma_can', 'like', $kw)
                            ->orWhereHas('duAn', function ($qDa) use ($kw) {
                                $qDa->where('ten_du_an', 'like', $kw);
                            });
                    });
            });
        }

        if ($request->filled('nhan_vien_phu_trach_id')) {
            if ($request->nhan_vien_phu_trach_id === 'none') {
                $query->whereNull('nhan_vien_phu_trach_id');
            } else {
                $query->where('nhan_vien_phu_trach_id', $request->nhan_vien_phu_trach_id);
            }
        }

        if ($request->filled('du_an_id')) {
            $query->whereHas('batDongSans', function ($q) use ($request) {
                $q->where('du_an_id', $request->du_an_id);
            });
        }

        if ($request->filled('toa')) {
            $query->whereHas('batDongSans', function ($q) use ($request) {
                $q->where('toa', 'like', '%' . $request->toa . '%');
            });
        }

        if ($request->filled('sort_date') && $request->sort_date === 'oldest') {
            $query->oldest('updated_at');
        } else {
            $query->latest('updated_at');
        }

        // Tính thống kê theo filter (trước khi paginate)
        $thongKe = [
            'tong' => (clone $query)->count(),
            'new_thang_nay' => (clone $query)->where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        // Xuất toàn bộ báo cáo theo bộ lọc hiện tại (không phân trang)
        if ($request->get('export') === 'csv') {
            return $this->exportChuNhaCsv((clone $query)->get(), $request, $thongKe);
        }

        $chuNhas = $query->paginate(15)->withQueryString();

        $nhanViens = NhanVien::where('kich_hoat', true)
            ->whereIn('vai_tro', ['admin', 'nguon_hang', 'sale'])
            ->get();

        $duAns = DuAn::where('hien_thi', true)->orderBy('ten_du_an')->get(['id', 'ten_du_an']);

        $toasQuery = BatDongSan::whereNotNull('toa')->where('toa', '!=', '');
        if ($request->filled('du_an_id')) {
            $toasQuery->where('du_an_id', $request->du_an_id);
        }
        $toas = $toasQuery->distinct()->orderBy('toa')->pluck('toa');

        $allBdsList = BatDongSan::with('duAn:id,ten_du_an')
            ->select('id', 'ma_can', 'toa', 'du_an_id', 'chu_nha_id')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.chu-nha.index', compact('chuNhas', 'nhanViens', 'duAns', 'toas', 'allBdsList', 'thongKe'));
    }

    private function exportChuNhaCsv($rows, Request $request, array $thongKe)
    {
        $fileName = 'Bao_Cao_Chu_Nha_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
            'Expires'             => '0',
        ];

        $boLoc = [];
        if ($request->filled('tim_kiem')) {
            $boLoc[] = 'Tu khoa: ' . $request->tim_kiem;
        }
        if ($request->filled('du_an_id')) {
            $boLoc[] = 'Du an ID: ' . $request->du_an_id;
        }
        if ($request->filled('toa')) {
            $boLoc[] = 'Toa: ' . $request->toa;
        }
        if ($request->filled('nhan_vien_phu_trach_id')) {
            $boLoc[] = $request->nhan_vien_phu_trach_id === 'none'
                ? 'Phu trach: Chung'
                : 'Phu trach ID: ' . $request->nhan_vien_phu_trach_id;
        }

        $sapXepLabel = $request->get('sort_date', 'newest') === 'oldest' ? 'Lau chua tuong tac' : 'Moi cap nhat';
        $tongCan = $rows->sum('bat_dong_sans_count');

        $callback = function () use ($rows, $boLoc, $sapXepLabel, $thongKe, $tongCan) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            $delimiter = ';';

            fputcsv($file, ['BAO CAO CHU NHA'], $delimiter);
            fputcsv($file, ['Thoi gian xuat', now()->format('d/m/Y H:i:s')], $delimiter);
            fputcsv($file, ['Tong dong', $rows->count()], $delimiter);
            fputcsv($file, ['Sap xep', $sapXepLabel], $delimiter);
            fputcsv($file, ['Bo loc', empty($boLoc) ? 'Khong co' : implode(' | ', $boLoc)], $delimiter);
            fputcsv($file, [], $delimiter);

            fputcsv($file, ['TONG QUAN'], $delimiter);
            fputcsv($file, ['Tong chu nha (theo bo loc)', (int) ($thongKe['tong'] ?? 0)], $delimiter);
            fputcsv($file, ['Chu nha moi trong thang (theo bo loc)', (int) ($thongKe['new_thang_nay'] ?? 0)], $delimiter);
            fputcsv($file, ['Tong tai san dang lien ket', (int) $tongCan], $delimiter);
            fputcsv($file, [], $delimiter);

            fputcsv($file, ['CHI TIET DANH SACH'], $delimiter);
            fputcsv($file, ['STT', 'ID', 'Ho ten', 'So dien thoai', 'Email', 'CCCD', 'Phu trach', 'So tai san', 'Ma can/Toa', 'Dia chi', 'Ghi chu', 'Cap nhat luc'], $delimiter);

            foreach ($rows->values() as $index => $cn) {
                $maCanList = $cn->batDongSans
                    ->take(10)
                    ->map(fn($bds) => ($bds->toa ? ($bds->toa . '-') : '') . ($bds->ma_can ?: ('#' . $bds->id)))
                    ->implode(', ');

                fputcsv($file, [
                    $index + 1,
                    $cn->id,
                    $cn->ho_ten,
                    $cn->so_dien_thoai,
                    $cn->email,
                    $cn->cccd,
                    optional($cn->nhanVienPhuTrach)->ho_ten ?? 'Chung',
                    (int) $cn->bat_dong_sans_count,
                    $maCanList,
                    $cn->dia_chi,
                    str_replace(["\r", "\n"], ' ', (string) $cn->ghi_chu),
                    optional($cn->updated_at)->format('d/m/Y H:i:s'),
                ], $delimiter);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Thiết lập các câu thông báo lỗi Tiếng Việt
    private function getValidationMessages()
    {
        return [
            'ho_ten.required' => 'Vui lòng nhập họ tên chủ nhà.',
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại.',
            'so_dien_thoai.regex' => 'Số điện thoại không hợp lệ (Chỉ nhập số, từ 9-11 ký tự).',
            'so_dien_thoai.unique' => 'Số điện thoại này đã tồn tại trong hệ thống.',
            'email.email' => 'Định dạng email không hợp lệ.',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ho_ten' => 'required|string|max:100',
            // Dùng Regex để ép buộc số điện thoại chỉ được chứa chữ số và độ dài 9-11
            'so_dien_thoai' => ['required', 'string', 'regex:/^[0-9]{9,11}$/', 'unique:chu_nha,so_dien_thoai'],
            'email' => 'nullable|email|max:100',
            'cccd' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string|max:1000',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
            'bat_dong_san_ids' => 'nullable|array',
        ], $this->getValidationMessages());

        $chuNha = ChuNha::create($data);

        if ($request->has('bat_dong_san_ids') && is_array($request->bat_dong_san_ids)) {
            BatDongSan::whereIn('id', $request->bat_dong_san_ids)->update(['chu_nha_id' => $chuNha->id]);
        }

        return redirect()->route('nhanvien.admin.chu-nha.index')->with('success', 'Đã thêm chủ nhà và gán căn hộ thành công!');
    }

    public function update(Request $request, ChuNha $chuNha)
    {
        if ($request->has('is_quick_update')) {
            $request->validate(['ghi_chu_moi' => 'nullable|string|max:1000']);

            if ($request->filled('ghi_chu_moi')) {
                $oldGhiChu = $chuNha->ghi_chu ? $chuNha->ghi_chu . "\n" : "";
                $newGhiChu = $oldGhiChu . "- [" . now()->format('d/m/Y H:i') . "] " . $request->ghi_chu_moi;
                $chuNha->ghi_chu = $newGhiChu;
            }

            $chuNha->touch();
            $chuNha->save();

            return back()->with('success', 'Đã cập nhật tương tác với chủ nhà!');
        }

        $data = $request->validate([
            'ho_ten' => 'required|string|max:100',
            'so_dien_thoai' => ['required', 'string', 'regex:/^[0-9]{9,11}$/', 'unique:chu_nha,so_dien_thoai,' . $chuNha->id],
            'email' => 'nullable|email|max:100',
            'cccd' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string|max:1000',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
            'bat_dong_san_ids' => 'nullable|array',
        ], $this->getValidationMessages());

        $chuNha->update($data);

        BatDongSan::where('chu_nha_id', $chuNha->id)->update(['chu_nha_id' => null]);

        if ($request->has('bat_dong_san_ids') && is_array($request->bat_dong_san_ids)) {
            BatDongSan::whereIn('id', $request->bat_dong_san_ids)->update(['chu_nha_id' => $chuNha->id]);
        }

        return redirect()->route('nhanvien.admin.chu-nha.index')->with('success', 'Đã cập nhật thông tin và danh sách căn hộ!');
    }

    public function destroy(ChuNha $chuNha)
    {
        if ($chuNha->batDongSans()->count() > 0) {
            BatDongSan::where('chu_nha_id', $chuNha->id)->update(['chu_nha_id' => null]);
        }

        $chuNha->delete();
        return redirect()->route('nhanvien.admin.chu-nha.index')->with('success', 'Đã xóa chủ nhà và gỡ liên kết các BĐS liên quan!');
    }
}
