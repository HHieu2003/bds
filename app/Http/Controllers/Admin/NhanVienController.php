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
    // INDEX
    public function index(Request $request)
    {
        $query = NhanVien::query();

        if ($request->filled('tukhoa')) {
            $kw = '%' . $request->tukhoa . '%';
            $query->where(
                fn($q) => $q
                    ->where('ho_ten', 'like', $kw)
                    ->orWhere('email', 'like', $kw)
                    ->orWhere('so_dien_thoai', 'like', $kw)
            );
        }
        if ($request->filled('vai_tro')) {
            $role = NhanVien::normalizeVaiTro($request->vai_tro);
            if ($role === 'nguon_hang') {
                $query->whereIn('vai_tro', ['nguon_hang', 'nguon']);
            } else {
                $query->where('vai_tro', $role);
            }
        }
        if ($request->filled('kich_hoat')) $query->where('kich_hoat', $request->kich_hoat);

        match ($request->get('sapxep', 'moi_nhat')) {
            'ten_az'    => $query->orderBy('ho_ten'),
            'ten_za'    => $query->orderByDesc('ho_ten'),
            'dang_nhap' => $query->orderByDesc('dang_nhap_cuoi_at'),
            default     => $query->orderByDesc('created_at'),
        };

        $nhanViens = $query->paginate(15)->withQueryString();

        $thongKe = [
            'tong'       => NhanVien::count(),
            'admin'      => NhanVien::where('vai_tro', 'admin')->count(),
            'nguon_hang' => NhanVien::whereIn('vai_tro', ['nguon_hang', 'nguon'])->count(),
            'sale'       => NhanVien::where('vai_tro', 'sale')->count(),
            'kich_hoat'  => NhanVien::where('kich_hoat', true)->count(),
        ];

        return view('admin.nhan-vien.index', compact('nhanViens', 'thongKe'));
    }

    // CREATE / STORE
    public function store(Request $request)
    {
        // Hỗ trợ cả AJAX (JSON) và form thường
        $data = $this->validateNhanVien($request);
        $data['vai_tro']      = NhanVien::normalizeVaiTro($data['vai_tro']);

        $data['password']     = Hash::make($data['mat_khau']);
        $data['kich_hoat']    = $request->input('kich_hoat', '1') === '1' || $request->boolean('kich_hoat', true);
        $data['anh_dai_dien'] = $this->handleAvatar($request);
        unset($data['mat_khau'], $data['mat_khau_confirmation']);

        $nv = NhanVien::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'ok'  => true,
                'msg' => 'Đã thêm nhân viên <strong>' . $nv->ho_ten . '</strong> thành công!',
                'id'  => $nv->id,
            ]);
        }

        return redirect()
            ->route('nhanvien.admin.nhan-vien.index')
            ->with('success', '✅ Đã thêm nhân viên <strong>' . $nv->ho_ten . '</strong> thành công!');
    }

    // EDIT DATA (AJAX — để load vào modal)
    public function editData(NhanVien $nhanVien)
    {
        return response()->json([
            'ok'       => true,
            'nhanVien' => [
                'id'              => $nhanVien->id,
                'ho_ten'          => $nhanVien->ho_ten,
                'email'           => $nhanVien->email,
                'so_dien_thoai'   => $nhanVien->so_dien_thoai,
                'vai_tro'         => NhanVien::normalizeVaiTro($nhanVien->vai_tro),
                'kich_hoat'       => $nhanVien->kich_hoat,
                'anh_dai_dien_url' => $nhanVien->anh_dai_dien_url,
            ],
        ]);
    }

    // EDIT / UPDATE
    public function update(Request $request, NhanVien $nhanVien)
    {
        $data = $this->validateNhanVien($request, $nhanVien->id);
        $data['vai_tro'] = NhanVien::normalizeVaiTro($data['vai_tro']);

        if (!empty($data['mat_khau'])) {
            $data['password'] = Hash::make($data['mat_khau']);
        }
        unset($data['mat_khau'], $data['mat_khau_confirmation']);

        $data['kich_hoat'] = $request->input('kich_hoat', '0') === '1' || $request->boolean('kich_hoat');

        $newAvatar = $this->handleAvatar($request);
        if ($newAvatar) {
            if ($nhanVien->anh_dai_dien) {
                Storage::disk('public')->delete($nhanVien->anh_dai_dien);
            }
            $data['anh_dai_dien'] = $newAvatar;
        }

        $nhanVien->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'ok'  => true,
                'msg' => 'Đã cập nhật nhân viên <strong>' . $nhanVien->ho_ten . '</strong>!',
            ]);
        }

        return redirect()
            ->route('nhanvien.admin.nhan-vien.index')
            ->with('success', '✅ Đã cập nhật nhân viên <strong>' . $nhanVien->ho_ten . '</strong>!');
    }

    // DESTROY
    public function destroy(NhanVien $nhanVien)
    {
        if ($nhanVien->id === auth('nhanvien')->id()) {
            return redirect()
                ->route('nhanvien.admin.nhan-vien.index')
                ->with('error', '❌ Không thể xóa tài khoản đang đăng nhập!');
        }

        $ten = $nhanVien->ho_ten;
        if ($nhanVien->anh_dai_dien) {
            Storage::disk('public')->delete($nhanVien->anh_dai_dien);
        }
        $nhanVien->delete();

        return redirect()
            ->route('nhanvien.admin.nhan-vien.index')
            ->with('success', '🗑️ Đã xóa nhân viên <strong>' . $ten . '</strong>!');
    }

    // AJAX: Toggle kích hoạt
    public function toggleKichHoat(NhanVien $nhanVien)
    {
        if ($nhanVien->id === auth('nhanvien')->id()) {
            return response()->json(['ok' => false, 'msg' => 'Không thể tự vô hiệu hóa chính mình!'], 403);
        }
        $nhanVien->update(['kich_hoat' => !$nhanVien->kich_hoat]);
        return response()->json([
            'ok'       => true,
            'kich_hoat' => $nhanVien->kich_hoat,
            'msg'      => $nhanVien->kich_hoat ? 'Đã kích hoạt!' : 'Đã vô hiệu hóa!',
        ]);
    }

    // AJAX: Đổi mật khẩu
    public function doiMatKhau(Request $request, NhanVien $nhanVien)
    {
        $request->validate([
            'mat_khau_moi'      => 'required|string|min:6|max:50',
            'xac_nhan_mat_khau' => 'required|same:mat_khau_moi',
        ]);

        $nhanVien->update(['password' => Hash::make($request->mat_khau_moi)]);

        return response()->json(['ok' => true, 'msg' => 'Đã đổi mật khẩu thành công!']);
    }

    // ── PRIVATE HELPERS ──
    private function validateNhanVien(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'ho_ten'              => 'required|string|max:100',
            'email'               => [
                'required',
                'email',
                'max:150',
                Rule::unique('nhan_vien', 'email')->ignore($ignoreId)->whereNull('deleted_at'),
            ],
            'mat_khau'            => $ignoreId ? 'nullable|string|min:6|max:50' : 'required|string|min:6|max:50',
            'mat_khau_confirmation' => $ignoreId ? 'nullable|string' : 'required|string',
            'vai_tro'             => 'required|in:admin,nguon_hang,sale,nguon',
            'so_dien_thoai'       => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('nhan_vien', 'so_dien_thoai')->ignore($ignoreId)->whereNull('deleted_at'),
            ],
            'anh_dai_dien'        => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'kich_hoat'           => 'nullable',
        ]);
    }

    private function handleAvatar(Request $request): ?string
    {
        if ($request->hasFile('anh_dai_dien')) {
            return $request->file('anh_dai_dien')->store('avatars/nhan-vien', 'public');
        }
        return null;
    }
}
