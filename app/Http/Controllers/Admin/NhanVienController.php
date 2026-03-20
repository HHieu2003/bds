<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class NhanVienController extends Controller
{
    // ──────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────
    public function index(Request $request)
    {

        $query = NhanVien::query();

        // Tìm kiếm
        if ($request->filled('tukhoa')) {
            $kw = '%' . $request->tukhoa . '%';
            $query->where(function ($q) use ($kw) {
                $q->where('ho_ten', 'like', $kw)
                    ->orWhere('email', 'like', $kw)
                    ->orWhere('so_dien_thoai', 'like', $kw);
            });
        }

        // Lọc
        if ($request->filled('vai_tro'))   $query->where('vai_tro',   $request->vai_tro);
        if ($request->filled('kich_hoat')) $query->where('kich_hoat', $request->kich_hoat);

        // Sắp xếp
        match ($request->get('sapxep', 'moi_nhat')) {
            'ten_az'      => $query->orderBy('ho_ten'),
            'ten_za'      => $query->orderByDesc('ho_ten'),
            'dang_nhap'   => $query->orderByDesc('dang_nhap_cuoi_at'),
            'bds_nhieu'   => $query->orderByDesc('bat_dong_san_phu_trach_count'),
            default       => $query->orderByDesc('created_at'),
        };

        $nhanViens = $query->paginate(15)->withQueryString();

        // Thống kê
        $thongKe = [
            'tong'       => NhanVien::count(),
            'admin'      => NhanVien::where('vai_tro', 'admin')->count(),
            'nguon_hang' => NhanVien::where('vai_tro', 'nguon_hang')->count(),
            'sale'       => NhanVien::where('vai_tro', 'sale')->count(),
            'kich_hoat'  => NhanVien::where('kich_hoat', true)->count(),
        ];

        return view('admin.nhan-vien.index', compact('nhanViens', 'thongKe'));
    }

    // ──────────────────────────────────────
    // CREATE / STORE
    // ──────────────────────────────────────
    public function create()
    {
        return view('admin.nhan-vien.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        $data['password']   = Hash::make($data['password']);
        $data['kich_hoat']  = $request->boolean('kich_hoat', true);
        $data['anh_dai_dien'] = $this->handleAvatar($request);

        NhanVien::create($data);

        return redirect()
            ->route('nhanvien.admin.nhan-vien.index')
            ->with('success', '✅ Đã thêm nhân viên <strong>' . $data['ho_ten'] . '</strong> thành công!');
    }

    // ──────────────────────────────────────
    // SHOW (chi tiết)
    // ──────────────────────────────────────
    public function show(NhanVien $nhanVien)
    {
        $nhanVien->loadCount(['khachHangPhuTrach']);
        $khachHangs = $nhanVien->khachHangPhuTrach()
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        $batDongSans = collect();

        return view('admin.nhan-vien.show', compact('nhanVien', 'batDongSans', 'khachHangs'));
    }

    // ──────────────────────────────────────
    // EDIT / UPDATE
    // ──────────────────────────────────────
    public function edit(NhanVien $nhanVien)
    {
        return view('admin.nhan-vien.edit', compact('nhanVien'));
    }

    public function update(Request $request, NhanVien $nhanVien)
    {
        $data = $this->validateRequest($request, $nhanVien->id);

        // Mật khẩu — chỉ cập nhật nếu nhập
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['kich_hoat'] = $request->boolean('kich_hoat');

        // Xử lý avatar
        $newAvatar = $this->handleAvatar($request);
        if ($newAvatar) {
            // Xóa ảnh cũ
            if ($nhanVien->anh_dai_dien) {
                Storage::disk('public')->delete($nhanVien->anh_dai_dien);
            }
            $data['anh_dai_dien'] = $newAvatar;
        }

        $nhanVien->update($data);

        return redirect()
            ->route('nhanvien.admin.nhan-vien.index')
            ->with('success', '✅ Đã cập nhật nhân viên <strong>' . $nhanVien->ho_ten . '</strong> thành công!');
    }

    // ──────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────
    public function destroy(NhanVien $nhanVien)
    {
        // Không cho xóa chính mình
        if ($nhanVien->id === auth('nhanvien')->id()) {
            return redirect()
                ->route('nhanvien.admin.nhan-vien.index')
                ->with('error', '❌ Không thể xóa tài khoản đang đăng nhập!');
        }

        $ten = $nhanVien->ho_ten;

        // Xóa ảnh đại diện
        if ($nhanVien->anh_dai_dien) {
            Storage::disk('public')->delete($nhanVien->anh_dai_dien);
        }

        $nhanVien->delete();

        return redirect()
            ->route('nhanvien.admin.nhan-vien.index')
            ->with('success', '🗑️ Đã xóa nhân viên <strong>' . $ten . '</strong>!');
    }

    // ──────────────────────────────────────
    // AJAX: Toggle kích hoạt
    // ──────────────────────────────────────
    public function toggleKichHoat(NhanVien $nhanVien)
    {
        if ($nhanVien->id === auth('nhanvien')->id()) {
            return response()->json(['ok' => false, 'msg' => 'Không thể tự vô hiệu hóa chính mình!'], 403);
        }

        $nhanVien->update(['kich_hoat' => !$nhanVien->kich_hoat]);
        return response()->json(['ok' => true, 'kich_hoat' => $nhanVien->kich_hoat]);
    }

    // ──────────────────────────────────────
    // AJAX: Đổi mật khẩu nhanh
    // ──────────────────────────────────────
    public function doiMatKhau(Request $request, NhanVien $nhanVien)
    {
        $request->validate([
            'mat_khau_moi'      => 'required|string|min:6|max:50',
            'xac_nhan_mat_khau' => 'required|same:mat_khau_moi',
        ]);

        $nhanVien->update(['password' => Hash::make($request->mat_khau_moi)]);

        return response()->json(['ok' => true, 'msg' => 'Đã đổi mật khẩu thành công!']);
    }

    // ══════════════════════════════════════
    // PRIVATE HELPERS
    // ══════════════════════════════════════
    private function validateRequest(Request $request, $ignoreId = null): array
    {
        $rules = [
            'ho_ten'        => 'required|string|max:100',
            'email'         => [
                'required',
                'email',
                'max:150',
                Rule::unique('nhan_vien', 'email')->ignore($ignoreId)->whereNull('deleted_at'),
            ],
            'password'      => $ignoreId ? 'nullable|string|min:6|max:50' : 'required|string|min:6|max:50',
            'vai_tro'       => 'required|in:admin,nguon_hang,sale',
            'so_dien_thoai' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('nhan_vien', 'so_dien_thoai')->ignore($ignoreId)->whereNull('deleted_at'),
            ],
            'anh_dai_dien'  => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'dia_chi'       => 'nullable|string|max:255',
            'kich_hoat'     => 'nullable|boolean',
        ];

        return $request->validate($rules);
    }

    private function handleAvatar(Request $request): ?string
    {
        if ($request->hasFile('anh_dai_dien')) {
            return $request->file('anh_dai_dien')
                ->store('avatars/nhan-vien', 'public');
        }
        return null;
    }
}
