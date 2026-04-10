<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BatDongSan;
use App\Models\KhuVuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KhuVucController extends Controller
{
    private function yeuCauQuyenQuanLy(): void
    {
        $nhanVien = Auth::guard('nhanvien')->user();
        if ($nhanVien && $nhanVien->vai_tro === 'sale') {
            abort(403, 'Bạn chỉ có quyền xem khu vực.');
        }
    }

    // ──────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────
    public function index(Request $request)
    {
        $query = KhuVuc::with(['cha', 'cha.cha'])
            ->withCount(['con', 'duAn'])
            ->addSelect([
                'bat_dong_san_count' => BatDongSan::query()
                    ->selectRaw('COUNT(*)')
                    ->join('du_an', 'du_an.id', '=', 'bat_dong_san.du_an_id')
                    ->whereNull('du_an.deleted_at')
                    ->whereNull('bat_dong_san.deleted_at')
                    ->whereColumn('du_an.khu_vuc_id', 'khu_vuc.id'),
            ]);

        // Tìm kiếm
        if ($request->filled('tim_kiem')) {
            $kw = '%' . $request->tim_kiem . '%';
            $query->where(function ($q) use ($kw) {
                $q->where('ten_khu_vuc', 'like', $kw)
                    ->orWhere('slug', 'like', $kw);
            });
        }

        // Lọc cấp
        if ($request->filled('cap')) {
            $query->where('cap_khu_vuc', $request->cap);
        }

        // Lọc khu vực cha (khi xem quận/huyện của tỉnh)
        if ($request->filled('cha_id')) {
            $query->where('khu_vuc_cha_id', $request->cha_id);
        }

        // Lọc trạng thái
        if ($request->filled('hien_thi')) {
            $query->where('hien_thi', $request->hien_thi);
        }

        // Sắp xếp
        match ($request->get('sapxep', 'thu_tu')) {
            'ten_az'   => $query->orderBy('ten_khu_vuc'),
            'ten_za'   => $query->orderByDesc('ten_khu_vuc'),
            'moi_nhat' => $query->latest(),
            'cap'      => $query->orderByRaw("FIELD(cap_khu_vuc,'tinh_thanh','quan_huyen','phuong_xa')")
                ->orderBy('thu_tu_hien_thi'),
            default    => $query->orderBy('thu_tu_hien_thi')->orderBy('ten_khu_vuc'),
        };

        $khuVucs = $query->paginate(20)->withQueryString();

        // Thống kê
        $thongKe = [
            'tong'        => KhuVuc::count(),
            'tinh_thanh'  => KhuVuc::where('cap_khu_vuc', 'tinh_thanh')->count(),
            'quan_huyen'  => KhuVuc::where('cap_khu_vuc', 'quan_huyen')->count(),
            'hien_thi'    => KhuVuc::where('hien_thi', true)->count(),
        ];

        // Danh sách tỉnh/thành để lọc
        $tinhThanhs = KhuVuc::where('cap_khu_vuc', 'tinh_thanh')
            ->orderBy('thu_tu_hien_thi')
            ->orderBy('ten_khu_vuc')
            ->get();

        return view('admin.khu-vuc.index', compact('khuVucs', 'thongKe', 'tinhThanhs'));
    }

    // ──────────────────────────────────────
    // CREATE / STORE
    // ──────────────────────────────────────
    public function create(Request $request)
    {
        $this->yeuCauQuyenQuanLy();

        // Nếu truyền cap=quan_huyen và cha_id thì pre-fill
        $capMacDinh = $request->get('cap', 'quan_huyen');
        $chaMacDinh = $request->get('cha_id');

        $tinhThanhs  = KhuVuc::where('cap_khu_vuc', 'tinh_thanh')
            ->orderBy('thu_tu_hien_thi')->orderBy('ten_khu_vuc')->get();
        $quanHuyens  = KhuVuc::where('cap_khu_vuc', 'quan_huyen')
            ->orderBy('thu_tu_hien_thi')->orderBy('ten_khu_vuc')->get();

        return view('admin.khu-vuc.create', compact('tinhThanhs', 'quanHuyens', 'capMacDinh', 'chaMacDinh'));
    }

    public function store(Request $request)
    {
        $this->yeuCauQuyenQuanLy();

        $data = $this->validateRequest($request);

        $data['slug']    = $this->taoSlugDuyNhat($request->ten_khu_vuc);
        $data['hien_thi'] = $request->boolean('hien_thi', true);

        KhuVuc::create($data);

        return redirect()
            ->route('nhanvien.admin.khu-vuc.index')
            ->with('success', '✅ Đã thêm khu vực <strong>' . $data['ten_khu_vuc'] . '</strong> thành công!');
    }

    // ──────────────────────────────────────
    // SHOW
    // ──────────────────────────────────────
    public function show(KhuVuc $khuVuc)
    {
        $khuVuc->load(['cha', 'cha.cha'])
            ->loadCount(['con', 'duAn']);

        $conList = $khuVuc->con()
            ->withCount('con')
            ->withCount('duAn')
            ->get();

        return view('admin.khu-vuc.show', compact('khuVuc', 'conList'));
    }

    // ──────────────────────────────────────
    // EDIT / UPDATE
    // ──────────────────────────────────────
    public function edit(KhuVuc $khuVuc)
    {
        $this->yeuCauQuyenQuanLy();

        $tinhThanhs = KhuVuc::where('cap_khu_vuc', 'tinh_thanh')
            ->where('id', '!=', $khuVuc->id)
            ->orderBy('thu_tu_hien_thi')->orderBy('ten_khu_vuc')->get();

        $quanHuyens = KhuVuc::where('cap_khu_vuc', 'quan_huyen')
            ->where('id', '!=', $khuVuc->id)
            ->orderBy('thu_tu_hien_thi')->orderBy('ten_khu_vuc')->get();

        return view('admin.khu-vuc.edit', compact('khuVuc', 'tinhThanhs', 'quanHuyens'));
    }

    public function update(Request $request, KhuVuc $khuVuc)
    {
        $this->yeuCauQuyenQuanLy();

        $data = $this->validateRequest($request, $khuVuc->id);

        // Cập nhật slug nếu tên thay đổi
        if ($request->ten_khu_vuc !== $khuVuc->ten_khu_vuc) {
            $data['slug'] = $this->taoSlugDuyNhat($request->ten_khu_vuc, $khuVuc->id);
        }

        $data['hien_thi'] = $request->boolean('hien_thi');

        // Không cho đặt chính mình làm cha
        if (!empty($data['khu_vuc_cha_id']) && $data['khu_vuc_cha_id'] == $khuVuc->id) {
            $data['khu_vuc_cha_id'] = null;
        }

        $khuVuc->update($data);

        return redirect()
            ->route('nhanvien.admin.khu-vuc.index')
            ->with('success', '✅ Đã cập nhật khu vực <strong>' . $khuVuc->ten_khu_vuc . '</strong>!');
    }

    // ──────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────
    public function destroy(KhuVuc $khuVuc)
    {
        $this->yeuCauQuyenQuanLy();

        // Không cho xóa nếu có con
        if ($khuVuc->con()->count() > 0) {
            return redirect()
                ->route('nhanvien.admin.khu-vuc.index')
                ->with('error', '❌ Không thể xóa khu vực <strong>' . $khuVuc->ten_khu_vuc
                    . '</strong> vì còn <strong>' . $khuVuc->con()->count() . '</strong> khu vực con!');
        }

        // Không cho xóa nếu có dự án liên kết
        if ($khuVuc->duAn()->count() > 0) {
            return redirect()
                ->route('nhanvien.admin.khu-vuc.index')
                ->with('error', '❌ Không thể xóa: khu vực đang có <strong>'
                    . $khuVuc->duAn()->count() . '</strong> dự án liên kết!');
        }

        $ten = $khuVuc->ten_khu_vuc;
        $khuVuc->delete();

        return redirect()
            ->route('nhanvien.admin.khu-vuc.index')
            ->with('success', '🗑️ Đã xóa khu vực <strong>' . $ten . '</strong>!');
    }

    // ──────────────────────────────────────
    // AJAX: Toggle hiển thị
    // ──────────────────────────────────────
    public function toggleHienThi(KhuVuc $khuVuc)
    {
        $this->yeuCauQuyenQuanLy();

        $khuVuc->update(['hien_thi' => !$khuVuc->hien_thi]);
        return response()->json(['ok' => true, 'hien_thi' => $khuVuc->hien_thi]);
    }

    // ──────────────────────────────────────
    // AJAX: Lấy danh sách con theo cấp cha
    // ──────────────────────────────────────
    public function getDanhSachCon(Request $request)
    {
        $chaId = $request->get('cha_id');
        $cap   = $request->get('cap'); // cap cần lấy: quan_huyen hoặc phuong_xa

        $list = KhuVuc::where('khu_vuc_cha_id', $chaId)
            ->where('cap_khu_vuc', $cap)
            ->orderBy('thu_tu_hien_thi')
            ->orderBy('ten_khu_vuc')
            ->get(['id', 'ten_khu_vuc']);

        return response()->json($list);
    }

    // ══════════════════════════════════════
    // PRIVATE HELPERS
    // ══════════════════════════════════════
    private function validateRequest(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'cap_khu_vuc'     => 'required|in:tinh_thanh,quan_huyen,phuong_xa',
            'khu_vuc_cha_id'  => 'nullable|exists:khu_vuc,id',
            'ten_khu_vuc'     => 'required|string|max:150',
            'mo_ta'           => 'nullable|string',
            'hien_thi'        => 'nullable|boolean',
            'thu_tu_hien_thi' => 'nullable|integer|min:0|max:9999',
            'seo_title'       => 'nullable|string|max:70',
            'seo_description' => 'nullable|string|max:160',
            'seo_keywords'    => 'nullable|string|max:255',
        ]);
    }

    private function taoSlugDuyNhat(string $ten, $ignoreId = null): string
    {
        $base = Str::slug($ten);
        $slug = $base;
        $i    = 1;

        while (KhuVuc::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
