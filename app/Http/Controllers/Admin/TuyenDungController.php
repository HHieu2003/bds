<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TinTuyenDung;
use App\Models\DonUngTuyen;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TuyenDungController extends Controller
{
    // ═══════════════════════════════════════
    // TIN TUYỂN DỤNG
    // ═══════════════════════════════════════

    public function index(Request $request)
    {
        $query = TinTuyenDung::withCount('donUngTuyens');

        if ($request->filled('search')) {
            $query->where('tieu_de', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('hinh_thuc')) {
            $query->where('hinh_thuc', $request->hinh_thuc);
        }

        $tinTuyenDungs = $query->orderBy('thu_tu')->latest()->paginate(15)->withQueryString();

        return view('admin.tuyen-dung.index', compact('tinTuyenDungs'));
    }

    public function create()
    {
        return view('admin.tuyen-dung.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tieu_de'    => 'required|string|max:255',
            'hinh_thuc'  => 'required|string',
            'so_luong'   => 'required|integer|min:1',
        ], [
            'tieu_de.required'   => 'Vui lòng nhập tiêu đề vị trí tuyển dụng.',
            'hinh_thuc.required' => 'Vui lòng chọn hình thức làm việc.',
            'so_luong.required'  => 'Vui lòng nhập số lượng cần tuyển.',
        ]);

        $data = $request->except('_token');
        $data['slug'] = Str::slug($request->tieu_de) . '-' . Str::random(5);
        $data['nhan_vien_id'] = auth('nhanvien')->id();
        $data['hien_thi'] = $request->boolean('hien_thi');
        $data['noi_bat'] = $request->boolean('noi_bat');

        TinTuyenDung::create($data);

        return redirect()->route('nhanvien.admin.tuyen-dung.index')
            ->with('success', 'Đã tạo tin tuyển dụng thành công!');
    }

    public function edit(TinTuyenDung $tinTuyenDung)
    {
        return view('admin.tuyen-dung.edit', compact('tinTuyenDung'));
    }

    public function update(Request $request, TinTuyenDung $tinTuyenDung)
    {
        $request->validate([
            'tieu_de'    => 'required|string|max:255',
            'hinh_thuc'  => 'required|string',
            'so_luong'   => 'required|integer|min:1',
        ]);

        $data = $request->except('_token', '_method');
        $data['hien_thi'] = $request->boolean('hien_thi');
        $data['noi_bat'] = $request->boolean('noi_bat');

        $tinTuyenDung->update($data);

        return redirect()->route('nhanvien.admin.tuyen-dung.index')
            ->with('success', 'Đã cập nhật tin tuyển dụng!');
    }

    public function destroy(TinTuyenDung $tinTuyenDung)
    {
        $tinTuyenDung->delete();
        return redirect()->route('nhanvien.admin.tuyen-dung.index')
            ->with('success', 'Đã xóa tin tuyển dụng.');
    }

    public function toggleHienThi(TinTuyenDung $tinTuyenDung)
    {
        $tinTuyenDung->update(['hien_thi' => !$tinTuyenDung->hien_thi]);
        return back()->with('success', $tinTuyenDung->hien_thi ? 'Đã hiển thị tin.' : 'Đã ẩn tin.');
    }

    // ═══════════════════════════════════════
    // ĐƠN ỨNG TUYỂN
    // ═══════════════════════════════════════

    public function donUngTuyen(Request $request)
    {
        $query = DonUngTuyen::with(['tinTuyenDung', 'nguoiXuLy']);

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }
        if ($request->filled('tin_id')) {
            $query->where('tin_tuyen_dung_id', $request->tin_id);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('ho_ten', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$s}%");
            });
        }

        $dons = $query->latest()->paginate(20)->withQueryString();
        $tinTuyenDungs = TinTuyenDung::orderBy('tieu_de')->get();

        return view('admin.tuyen-dung.don-ung-tuyen', compact('dons', 'tinTuyenDungs'));
    }

    public function showDon(DonUngTuyen $donUngTuyen)
    {
        $donUngTuyen->load(['tinTuyenDung', 'nguoiXuLy']);
        return view('admin.tuyen-dung.show-don', compact('donUngTuyen'));
    }

    public function capNhatTrangThai(Request $request, DonUngTuyen $donUngTuyen)
    {
        $request->validate([
            'trang_thai' => 'required|in:moi,dang_xem_xet,hen_phong_van,trung_tuyen,tu_choi',
        ]);

        $donUngTuyen->update([
            'trang_thai'         => $request->trang_thai,
            'ghi_chu_admin'      => $request->ghi_chu_admin ?? $donUngTuyen->ghi_chu_admin,
            'nhan_vien_xu_ly_id' => auth('nhanvien')->id(),
        ]);

        $label = TinTuyenDung::TRANG_THAI_DON[$request->trang_thai]['label'] ?? $request->trang_thai;
        return back()->with('success', "Đã cập nhật trạng thái: {$label}");
    }

    public function xoaDon(DonUngTuyen $donUngTuyen)
    {
        $donUngTuyen->delete();
        return back()->with('success', 'Đã xóa đơn ứng tuyển.');
    }
}
