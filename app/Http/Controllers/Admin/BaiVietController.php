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
        if ($request->filled('tukhoa')) {
            $kw = '%' . $request->tukhoa . '%';
            $query->where(function ($q) use ($kw) {
                $q->where('tieu_de', 'like', $kw)
                    ->orWhere('slug', 'like', $kw);
            });
        }

        // Mặc định luôn lấy toàn bộ bài viết.
        // Chỉ khi người dùng chủ động chọn bộ lọc thì mới lọc theo trạng thái.
        $trangThai = $request->input('trang_thai');
        if (in_array($trangThai, ['hien_thi', 'an'], true)) {
            $query->where('hien_thi', $trangThai === 'hien_thi');
        }

        // Sắp xếp
        match ($request->get('sapxep', 'moi_nhat')) {
            'cu_nhat'    => $query->oldest(),
            'nhieu_xem'  => $query->orderByDesc('luot_xem'),
            'luot_xem'   => $query->orderByDesc('luot_xem'),
            // Sắp xếp ổn định để bài có cùng thứ tự (đặc biệt = 0) không bị nhảy trang.
            'thu_tu'     => $query->orderByRaw('COALESCE(thu_tu_hien_thi, 0) asc')
                ->orderByDesc('id'),
            'tieu_de_az' => $query->orderBy('tieu_de'),
            default      => $query->latest(),
        };

        $baiViets = $query->paginate(15)->withQueryString();

        // Thống kê
        $thongKe = [
            'tong'       => BaiViet::count(),
            'hien_thi'   => BaiViet::where('hien_thi', true)->count(),
            'an'         => BaiViet::where('hien_thi', false)->count(),
            'noi_bat'    => BaiViet::where('noi_bat', true)->count(),
            'tong_luot_xem' => (int) BaiViet::sum('luot_xem'),
            'tin_tuc'    => BaiViet::where('loai_bai_viet', 'tin_tuc')->count(),
            'kien_thuc'  => BaiViet::where('loai_bai_viet', 'kien_thuc')->count(),
        ];

        // View hiện tại có filter danh mục, nhưng module bài viết chưa có bảng danh mục riêng.
        $danhMucs = collect();

        return view('admin.bai-viet.index', compact('baiViets', 'thongKe', 'danhMucs'));
    }

    // ──────────────────────────────────────
    // CREATE / STORE
    // ──────────────────────────────────────
    public function create()
    {
        $danhMucs = collect();

        return view('admin.bai-viet.create', compact('danhMucs'));
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        $data['loai_bai_viet'] = $data['loai_bai_viet'] ?? 'tin_tuc';
        $data['thoi_diem_dang'] = $request->input('ngay_dang', $request->input('thoi_diem_dang'));
        $data['thu_tu_hien_thi'] = (int) $request->input('thu_tu', $request->input('thu_tu_hien_thi', 0));
        $data['seo_title'] = $request->input('seo_title', $request->input('meta_title'));
        $data['seo_description'] = $request->input('seo_description', $request->input('meta_description'));

        $data['slug']          = $this->taoSlugDuyNhat($request->tieu_de);
        $data['nhan_vien_id']  = auth('nhanvien')->id();
        $data['noi_bat']       = $request->boolean('noi_bat');

        $trangThai = $request->input('trang_thai', 'hien_thi');
        if ($trangThai === 'an') {
            $data['hien_thi'] = false;
        } else {
            $data['hien_thi'] = true;
            if (empty($data['thoi_diem_dang'])) {
                $data['thoi_diem_dang'] = now();
            }
        }

        // Ảnh đại diện
        if ($request->hasFile('hinh_anh') || $request->hasFile('anh_dai_dien')) {
            $image = $request->file('hinh_anh') ?? $request->file('anh_dai_dien');
            $data['hinh_anh'] = $image
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
        $danhMucs = collect();

        return view('admin.bai-viet.edit', compact('baiViet', 'danhMucs'));
    }

    public function update(Request $request, BaiViet $baiViet)
    {
        $data = $this->validateRequest($request, $baiViet->id);
        $data['loai_bai_viet'] = $data['loai_bai_viet'] ?? ($baiViet->loai_bai_viet ?? 'tin_tuc');
        $data['thoi_diem_dang'] = $request->input('ngay_dang', $request->input('thoi_diem_dang'));
        $data['thu_tu_hien_thi'] = (int) $request->input('thu_tu', $request->input('thu_tu_hien_thi', $baiViet->thu_tu_hien_thi ?? 0));
        $data['seo_title'] = $request->input('seo_title', $request->input('meta_title'));
        $data['seo_description'] = $request->input('seo_description', $request->input('meta_description'));

        // Cập nhật slug nếu tiêu đề thay đổi
        if ($request->tieu_de !== $baiViet->tieu_de) {
            $data['slug'] = $this->taoSlugDuyNhat($request->tieu_de, $baiViet->id);
        }

        $data['noi_bat'] = $request->boolean('noi_bat');

        $trangThai = $request->input('trang_thai', $baiViet->hien_thi ? 'hien_thi' : 'an');
        if ($trangThai === 'an') {
            $data['hien_thi'] = false;
        } else {
            $data['hien_thi'] = true;
            if (empty($data['thoi_diem_dang'])) {
                $data['thoi_diem_dang'] = $baiViet->thoi_diem_dang ?: now();
            }
        }

        // Ảnh đại diện mới
        if ($request->hasFile('hinh_anh') || $request->hasFile('anh_dai_dien')) {
            if ($baiViet->hinh_anh) {
                Storage::disk('public')->delete($baiViet->hinh_anh);
            }
            $image = $request->file('hinh_anh') ?? $request->file('anh_dai_dien');
            $data['hinh_anh'] = $image
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
            ->with('success', '✅ Đã cập nhật bài viết:' . $baiViet->tieu_de . '!');
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

    // ──────────────────────────────────────
    // AJAX: Bulk action
    // ──────────────────────────────────────
    public function bulkAction(Request $request)
    {
        $data = $request->validate([
            'action'   => 'required|in:publish,delete',
            'ids'      => 'required|array|min:1',
            'ids.*'    => 'integer|exists:bai_viet,id',
        ]);

        $baiViets = BaiViet::whereIn('id', $data['ids'])->get();

        if ($data['action'] === 'publish') {
            BaiViet::whereIn('id', $data['ids'])->update([
                'hien_thi' => true,
                'thoi_diem_dang' => now(),
            ]);

            return response()->json([
                'ok' => true,
                'msg' => 'Đã đăng bài viết đã chọn.',
            ]);
        }

        foreach ($baiViets as $baiViet) {
            if ($baiViet->hinh_anh) {
                Storage::disk('public')->delete($baiViet->hinh_anh);
            }
            if ($baiViet->album_anh) {
                foreach ($baiViet->album_anh as $anh) {
                    Storage::disk('public')->delete($anh);
                }
            }
            $baiViet->delete();
        }

        return response()->json([
            'ok' => true,
            'msg' => 'Đã xóa bài viết đã chọn.',
        ]);
    }

    // ══════════════════════════════════════
    // PRIVATE HELPERS
    // ══════════════════════════════════════
    private function validateRequest(Request $request, $ignoreId = null): array
    {
        $rules = [
            'tieu_de'        => 'required|string|max:255',
            'mo_ta_ngan'     => 'nullable|string|max:300',
            'noi_dung'       => 'required|string',
            'loai_bai_viet'  => 'nullable|in:tin_tuc,phong_thuy,tuyen_dung,kien_thuc',
            'trang_thai'     => 'nullable|in:hien_thi,an',
            'hinh_anh'       => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'anh_dai_dien'   => 'nullable|image|mimes:jpeg,png,webp|max:5120',
            'album_anh'      => 'nullable|array|max:10',
            'album_anh.*'    => 'image|mimes:jpeg,png,webp|max:2048',
            'noi_bat'        => 'nullable|boolean',
            'hien_thi'       => 'nullable|boolean',
            'thu_tu_hien_thi' => 'nullable|integer|min:0',
            'thu_tu'         => 'nullable|integer|min:0',
            'ngay_dang'      => 'nullable|date',
            'thoi_diem_dang' => 'nullable|date',
            'seo_title'      => 'nullable|string|max:70',
            'meta_title'     => 'nullable|string|max:70',
            'seo_description' => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:160',
            'seo_keywords'   => 'nullable|string|max:255',
        ];

        $messages = [
            'required' => ':attribute là bắt buộc.',
            'string' => ':attribute phải là chuỗi ký tự.',
            'integer' => ':attribute phải là số nguyên.',
            'max' => ':attribute không được vượt quá :max ký tự.',
            'image' => ':attribute phải là tệp hình ảnh hợp lệ.',
            'mimes' => ':attribute phải có định dạng: :values.',
            'date' => ':attribute không đúng định dạng ngày giờ.',
            'in' => ':attribute không hợp lệ.',
            'array' => ':attribute phải là danh sách hợp lệ.',
            'boolean' => ':attribute chỉ nhận giá trị đúng/sai.',
            'exists' => ':attribute không tồn tại trong hệ thống.',
        ];

        $attributes = [
            'tieu_de' => 'Tiêu đề bài viết',
            'mo_ta_ngan' => 'Mô tả ngắn',
            'noi_dung' => 'Nội dung',
            'loai_bai_viet' => 'Loại bài viết',
            'trang_thai' => 'Trạng thái',
            'hinh_anh' => 'Ảnh đại diện',
            'anh_dai_dien' => 'Ảnh đại diện',
            'album_anh' => 'Album ảnh',
            'album_anh.*' => 'Ảnh trong album',
            'noi_bat' => 'Nổi bật',
            'hien_thi' => 'Hiển thị',
            'thu_tu_hien_thi' => 'Thứ tự hiển thị',
            'thu_tu' => 'Thứ tự hiển thị',
            'ngay_dang' => 'Ngày đăng',
            'thoi_diem_dang' => 'Thời điểm đăng',
            'seo_title' => 'SEO title',
            'meta_title' => 'Meta title',
            'seo_description' => 'SEO description',
            'meta_description' => 'Meta description',
            'seo_keywords' => 'SEO keywords',
        ];

        return $request->validate($rules, $messages, $attributes);
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
