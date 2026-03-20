<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BaiVietController extends Controller
{
    // ──────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────
    public function index(Request $request)
    {
        $query = BaiViet::with('tacGia');

        // Tìm kiếm
        if ($request->filled('tim_kiem')) {
            $query->where('tieu_de', 'like', '%' . $request->tim_kiem . '%');
        }

        // Lọc loại
        if ($request->filled('loai')) {
            $query->where('loai_bai_viet', $request->loai);
        }

        // Lọc trạng thái hiển thị
        if ($request->filled('hien_thi')) {
            $query->where('hien_thi', $request->hien_thi);
        }

        // Lọc nổi bật
        if ($request->filled('noi_bat')) {
            $query->where('noi_bat', $request->noi_bat);
        }

        // Sắp xếp
        match ($request->get('sapxep', 'moi_nhat')) {
            'cu_nhat'    => $query->oldest(),
            'luot_xem'   => $query->orderByDesc('luot_xem'),
            'thu_tu'     => $query->orderBy('thu_tu_hien_thi'),
            'tieu_de_az' => $query->orderBy('tieu_de'),
            default      => $query->latest(),
        };

        $baiViets = $query->paginate(15)->withQueryString();

        // Thống kê
        $thongKe = [
            'tong'       => BaiViet::count(),
            'hien_thi'   => BaiViet::where('hien_thi', true)->count(),
            'noi_bat'    => BaiViet::where('noi_bat', true)->count(),
            'tin_tuc'    => BaiViet::where('loai_bai_viet', 'tin_tuc')->count(),
            'kien_thuc'  => BaiViet::where('loai_bai_viet', 'kien_thuc')->count(),
        ];

        return view('admin.bai-viet.index', compact('baiViets', 'thongKe'));
    }

    // ──────────────────────────────────────
    // CREATE / STORE
    // ──────────────────────────────────────
    public function create()
    {
        return view('admin.bai-viet.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        $data['slug']          = $this->taoSlugDuyNhat($request->tieu_de);
        $data['nhan_vien_id']  = auth('nhanvien')->id();
        $data['thoi_diem_dang'] = $request->filled('thoi_diem_dang')
            ? $request->thoi_diem_dang
            : now();
        $data['noi_bat']  = $request->boolean('noi_bat');
        $data['hien_thi'] = $request->boolean('hien_thi', true);

        // Ảnh đại diện
        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')
                ->store('bai-viet/hinh-anh', 'public');
        }

        // Album ảnh
        if ($request->hasFile('album_anh')) {
            $data['album_anh'] = $this->luuAlbum($request->file('album_anh'));
        }

        BaiViet::create($data);

        return redirect()
            ->route('nhanvien.admin.bai-viet.index')
            ->with('success', '✅ Đã đăng bài viết <strong>' . $data['tieu_de'] . '</strong>!');
    }

    // ──────────────────────────────────────
    // SHOW (xem trước)
    // ──────────────────────────────────────
    public function show(BaiViet $baiViet)
    {
        return view('admin.bai-viet.show', compact('baiViet'));
    }

    // ──────────────────────────────────────
    // EDIT / UPDATE
    // ──────────────────────────────────────
    public function edit(BaiViet $baiViet)
    {
        return view('admin.bai-viet.edit', compact('baiViet'));
    }

    public function update(Request $request, BaiViet $baiViet)
    {
        $data = $this->validateRequest($request, $baiViet->id);

        // Cập nhật slug nếu tiêu đề thay đổi
        if ($request->tieu_de !== $baiViet->tieu_de) {
            $data['slug'] = $this->taoSlugDuyNhat($request->tieu_de, $baiViet->id);
        }

        $data['noi_bat']  = $request->boolean('noi_bat');
        $data['hien_thi'] = $request->boolean('hien_thi');

        if ($request->filled('thoi_diem_dang')) {
            $data['thoi_diem_dang'] = $request->thoi_diem_dang;
        }

        // Ảnh đại diện mới
        if ($request->hasFile('hinh_anh')) {
            if ($baiViet->hinh_anh) {
                Storage::disk('public')->delete($baiViet->hinh_anh);
            }
            $data['hinh_anh'] = $request->file('hinh_anh')
                ->store('bai-viet/hinh-anh', 'public');
        }

        // Album ảnh mới
        if ($request->hasFile('album_anh')) {
            // Xóa ảnh cũ
            if ($baiViet->album_anh) {
                foreach ($baiViet->album_anh as $old) {
                    Storage::disk('public')->delete($old);
                }
            }
            $data['album_anh'] = $this->luuAlbum($request->file('album_anh'));
        }

        // Xóa ảnh đại diện
        if ($request->boolean('xoa_hinh_anh') && $baiViet->hinh_anh) {
            Storage::disk('public')->delete($baiViet->hinh_anh);
            $data['hinh_anh'] = null;
        }

        $baiViet->update($data);

        return redirect()
            ->route('nhanvien.admin.bai-viet.index')
            ->with('success', '✅ Đã cập nhật bài viết <strong>' . $baiViet->tieu_de . '</strong>!');
    }

    // ──────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────
    public function destroy(BaiViet $baiViet)
    {
        // Xóa ảnh liên quan
        if ($baiViet->hinh_anh) {
            Storage::disk('public')->delete($baiViet->hinh_anh);
        }
        if ($baiViet->album_anh) {
            foreach ($baiViet->album_anh as $anh) {
                Storage::disk('public')->delete($anh);
            }
        }

        $tieu_de = $baiViet->tieu_de;
        $baiViet->delete();

        return redirect()
            ->route('nhanvien.admin.bai-viet.index')
            ->with('success', '🗑️ Đã xóa bài viết <strong>' . $tieu_de . '</strong>!');
    }

    // ──────────────────────────────────────
    // AJAX: Toggle hiển thị
    // ──────────────────────────────────────
    public function toggleHienThi(BaiViet $baiViet)
    {
        $baiViet->update(['hien_thi' => !$baiViet->hien_thi]);
        return response()->json(['ok' => true, 'hien_thi' => $baiViet->hien_thi]);
    }

    // ──────────────────────────────────────
    // AJAX: Toggle nổi bật
    // ──────────────────────────────────────
    public function toggleNoiBat(BaiViet $baiViet)
    {
        $baiViet->update(['noi_bat' => !$baiViet->noi_bat]);
        return response()->json(['ok' => true, 'noi_bat' => $baiViet->noi_bat]);
    }

    // ══════════════════════════════════════
    // PRIVATE HELPERS
    // ══════════════════════════════════════
    private function validateRequest(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'tieu_de'        => 'required|string|max:255',
            'mo_ta_ngan'     => 'nullable|string|max:300',
            'noi_dung'       => 'required|string',
            'loai_bai_viet'  => 'required|in:tin_tuc,phong_thuy,tuyen_dung,kien_thuc',
            'hinh_anh'       => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'album_anh'      => 'nullable|array|max:10',
            'album_anh.*'    => 'image|mimes:jpeg,png,webp|max:2048',
            'noi_bat'        => 'nullable|boolean',
            'hien_thi'       => 'nullable|boolean',
            'thu_tu_hien_thi' => 'nullable|integer|min:0',
            'thoi_diem_dang' => 'nullable|date',
            'seo_title'      => 'nullable|string|max:70',
            'seo_description' => 'nullable|string|max:160',
            'seo_keywords'   => 'nullable|string|max:255',
        ]);
    }

    private function taoSlugDuyNhat(string $tieu_de, $ignoreId = null): string
    {
        $base = Str::slug($tieu_de);
        $slug = $base;
        $i    = 1;

        while (BaiViet::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function luuAlbum(array $files): array
    {
        $paths = [];
        foreach ($files as $file) {
            $paths[] = $file->store('bai-viet/album', 'public');
        }
        return $paths;
    }
}
