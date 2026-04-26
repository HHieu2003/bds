<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DuAn;
use App\Models\KhuVuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DuAnController extends Controller
{
    private function yeuCauQuyenQuanLy(): void
    {
        $nhanVien = Auth::guard('nhanvien')->user();
        if ($nhanVien && $nhanVien->vai_tro === 'sale') {
            abort(403, 'Bạn chỉ có quyền xem dự án.');
        }
    }

    public function index(Request $request)
    {
        $query = DuAn::with('khuVuc')
            ->withCount([
                'batDongSans as so_can_ban' => fn($q) => $q->where('nhu_cau', 'ban')->where('trang_thai', 'con_hang'),
                'batDongSans as so_can_thue' => fn($q) => $q->where('nhu_cau', 'thue')->where('trang_thai', 'con_hang'),
            ]);

        if ($request->filled('tukhoa')) {
            $query->where(function ($q) use ($request) {
                $q->where('ten_du_an', 'like', '%' . $request->tukhoa . '%')
                    ->orWhere('dia_chi', 'like', '%' . $request->tukhoa . '%');
            });
        }

        if ($request->filled('khu_vuc_id')) {
            $selectedKhuVuc = KhuVuc::find($request->khu_vuc_id);
            if ($selectedKhuVuc) {
                $descendantIds = $selectedKhuVuc->getAllDescendantIds();
                $query->whereIn('khu_vuc_id', $descendantIds);
            }
        }

        if ($request->filled('hien_thi')) {
            $query->where('hien_thi', $request->hien_thi);
        }

        // Tính thống kê theo filter (trước khi paginate)
        $thongKe = [
            'tong' => (clone $query)->count(),
            'hien_thi' => (clone $query)->where('hien_thi', 1)->count(),
            'can_ban' => (clone $query)->withCount(['batDongSans as ban_count' => fn($q) => $q->where('nhu_cau', 'ban')->where('trang_thai', 'con_hang')])->get()->sum('ban_count'),
            'can_thue' => (clone $query)->withCount(['batDongSans as thue_count' => fn($q) => $q->where('nhu_cau', 'thue')->where('trang_thai', 'con_hang')])->get()->sum('thue_count'),
        ];

        $duAns   = $query->orderBy('thu_tu_hien_thi')->paginate(10)->withQueryString();
        $khuVucs = KhuVuc::whereNull('deleted_at')->orderBy('ten_khu_vuc')->get();

        return view('admin.du-an.index', compact('duAns', 'khuVucs', 'thongKe'));
    }

    public function create()
    {
        $this->yeuCauQuyenQuanLy();

        $khuVucs = KhuVuc::whereNull('deleted_at')->orderBy('ten_khu_vuc')->get();
        return view('admin.du-an.create', compact('khuVucs'));
    }

    public function store(Request $request)
    {
        $this->yeuCauQuyenQuanLy();

        $validated = $request->validate([
            'ten_du_an'        => 'required|string|max:255',
            'khu_vuc_id'       => 'required|exists:khu_vuc,id',
            'dia_chi'          => 'required|string|max:255',
            'chu_dau_tu'       => 'nullable|string|max:255',
            'don_vi_thi_cong'  => 'nullable|string|max:255',
            'mo_ta_ngan'       => 'nullable|string|max:500',
            'noi_dung_chi_tiet' => 'nullable|string',
            'hinh_anh_dai_dien' => 'nullable|image|mimes:jpeg,png,webp|max:3072',
            'map_url'          => 'nullable|string',
            'noi_bat'          => 'nullable|boolean',
            'hien_thi'         => 'nullable|boolean',
            'thu_tu_hien_thi'  => 'nullable|integer|min:0|max:999',
            'trang_thai'       => 'nullable|in:sap_mo_ban,dang_mo_ban,da_ban_het',
            'seo_title'        => 'nullable|string|max:255',
            'seo_description'  => 'nullable|string|max:500',
            'seo_keywords'     => 'nullable|string|max:255',
        ]);

        // Slug
        $validated['slug'] = $this->taoSlug($validated['ten_du_an']);

        // Upload ảnh
        if ($request->hasFile('hinh_anh_dai_dien')) {
            $validated['hinh_anh_dai_dien'] = $request->file('hinh_anh_dai_dien')
                ->store('du-an', 'r2');
        }

        // Checkbox → boolean
        $validated['hien_thi']  = $request->boolean('hien_thi');
        $validated['noi_bat']   = $request->boolean('noi_bat');
        $validated['trang_thai'] = $validated['trang_thai'] ?? 'dang_mo_ban';

        DuAn::create($validated);

        return redirect()->route('nhanvien.admin.du-an.index')
            ->with('success', 'Thêm dự án thành công!');
    }

    public function edit(DuAn $duAn)
    {
        $this->yeuCauQuyenQuanLy();

        $khuVucs = KhuVuc::whereNull('deleted_at')->orderBy('ten_khu_vuc')->get();
        return view('admin.du-an.edit', compact('duAn', 'khuVucs'));
    }

    public function update(Request $request, DuAn $duAn)
    {
        $this->yeuCauQuyenQuanLy();

        $validated = $request->validate([
            'ten_du_an'        => 'required|string|max:255',
            'khu_vuc_id'       => 'required|exists:khu_vuc,id',
            'dia_chi'          => 'required|string|max:255',
            'chu_dau_tu'       => 'nullable|string|max:255',
            'don_vi_thi_cong'  => 'nullable|string|max:255',
            'mo_ta_ngan'       => 'nullable|string|max:500',
            'noi_dung_chi_tiet' => 'nullable|string',
            'hinh_anh_dai_dien' => 'nullable|image|mimes:jpeg,png,webp|max:3072',
            'map_url'          => 'nullable|string',
            'noi_bat'          => 'nullable|boolean',
            'hien_thi'         => 'nullable|boolean',
            'thu_tu_hien_thi'  => 'nullable|integer|min:0|max:999',
            'trang_thai'       => 'nullable|in:sap_mo_ban,dang_mo_ban,da_ban_het',
            'seo_title'        => 'nullable|string|max:255',
            'seo_description'  => 'nullable|string|max:500',
            'seo_keywords'     => 'nullable|string|max:255',
        ]);

        // Upload ảnh mới → xóa ảnh cũ
        if ($request->hasFile('hinh_anh_dai_dien')) {
            if ($duAn->hinh_anh_dai_dien) {
                Storage::disk('r2')->delete($duAn->hinh_anh_dai_dien);
            }
            $validated['hinh_anh_dai_dien'] = $request->file('hinh_anh_dai_dien')
                ->store('du-an', 'r2');
        }

        $validated['hien_thi'] = $request->boolean('hien_thi');
        $validated['noi_bat']  = $request->boolean('noi_bat');

        $duAn->update($validated);

        return redirect()->route('nhanvien.admin.du-an.index')
            ->with('success', 'Cập nhật dự án thành công!');
    }

    public function destroy(DuAn $duAn)
    {
        $this->yeuCauQuyenQuanLy();

        if ($duAn->hinh_anh_dai_dien) {
            Storage::disk('r2')->delete($duAn->hinh_anh_dai_dien);
        }
        $duAn->delete();

        return redirect()->route('nhanvien.admin.du-an.index')
            ->with('success', 'Đã xóa dự án!');
    }

    public function toggleHienThi(DuAn $duAn)
    {
        $this->yeuCauQuyenQuanLy();

        $duAn->update(['hien_thi' => !$duAn->hien_thi]);
        return response()->json(['ok' => true, 'hien_thi' => $duAn->hien_thi, 'msg' => 'Cập nhật thành công!']);
    }

    // ── Tạo slug unique ──
    private function taoSlug(string $ten): string
    {
        $base = Str::slug($ten);
        $slug = $base;
        $i    = 1;
        while (DuAn::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
