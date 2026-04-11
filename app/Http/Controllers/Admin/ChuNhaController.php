<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChuNha;
use App\Models\NhanVien;
use App\Models\DuAn;
use App\Models\BatDongSan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // 1. Lọc theo từ khóa (Bao gồm cả Mã Căn và Tên Dự Án)
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

        // 2. Lọc theo nhân viên phụ trách
        if ($request->filled('nhan_vien_phu_trach_id')) {
            if ($request->nhan_vien_phu_trach_id === 'none') {
                $query->whereNull('nhan_vien_phu_trach_id');
            } else {
                $query->where('nhan_vien_phu_trach_id', $request->nhan_vien_phu_trach_id);
            }
        }

        // 3. Lọc theo Dự án
        if ($request->filled('du_an_id')) {
            $query->whereHas('batDongSans', function ($q) use ($request) {
                $q->where('du_an_id', $request->du_an_id);
            });
        }

        // 4. Lọc theo Tòa
        if ($request->filled('toa')) {
            $query->whereHas('batDongSans', function ($q) use ($request) {
                $q->where('toa', 'like', '%' . $request->toa . '%');
            });
        }

        $chuNhas = $query->latest()->paginate(15)->withQueryString();

        $nhanViens = NhanVien::where('kich_hoat', true)
            ->whereIn('vai_tro', ['admin', 'nguon_hang', 'sale'])
            ->get();

        $duAns = DuAn::where('hien_thi', true)->orderBy('ten_du_an')->get(['id', 'ten_du_an']);

        // 5. Lấy danh sách Tòa (CHỈ HIỂN THỊ TÒA CỦA DỰ ÁN ĐƯỢC CHỌN)
        $toasQuery = BatDongSan::whereNotNull('toa')->where('toa', '!=', '');
        if ($request->filled('du_an_id')) {
            $toasQuery->where('du_an_id', $request->du_an_id);
        }
        $toas = $toasQuery->distinct()->orderBy('toa')->pluck('toa');

        // Dữ liệu dùng cho Select Chọn căn hộ ở Modal
        $allBdsList = BatDongSan::with('duAn:id,ten_du_an')
            ->select('id', 'ma_can', 'toa', 'du_an_id', 'chu_nha_id')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.chu-nha.index', compact('chuNhas', 'nhanViens', 'duAns', 'toas', 'allBdsList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ho_ten' => 'required|string|max:100',
            'so_dien_thoai' => 'required|string|max:20|unique:chu_nha,so_dien_thoai',
            'email' => 'nullable|email|max:100',
            'cccd' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string|max:1000',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
            'bat_dong_san_ids' => 'nullable|array',
        ]);

        $chuNha = ChuNha::create($data);

        if ($request->has('bat_dong_san_ids') && is_array($request->bat_dong_san_ids)) {
            BatDongSan::whereIn('id', $request->bat_dong_san_ids)->update(['chu_nha_id' => $chuNha->id]);
        }

        return redirect()->route('nhanvien.admin.chu-nha.index')->with('success', 'Đã thêm chủ nhà và gán căn hộ thành công!');
    }

    public function update(Request $request, ChuNha $chuNha)
    {
        $data = $request->validate([
            'ho_ten' => 'required|string|max:100',
            'so_dien_thoai' => 'required|string|max:20|unique:chu_nha,so_dien_thoai,' . $chuNha->id,
            'email' => 'nullable|email|max:100',
            'cccd' => 'nullable|string|max:20',
            'dia_chi' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string|max:1000',
            'nhan_vien_phu_trach_id' => 'nullable|exists:nhan_vien,id',
            'bat_dong_san_ids' => 'nullable|array',
        ]);

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
            return back()->with('error', 'Không thể xóa vì chủ nhà này đang có Bất động sản trong hệ thống!');
        }
        $chuNha->delete();
        return redirect()->route('nhanvien.admin.chu-nha.index')->with('success', 'Đã xóa chủ nhà!');
    }
}
