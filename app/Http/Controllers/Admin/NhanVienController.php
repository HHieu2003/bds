<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use Illuminate\Database\QueryException;
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

        // Xuất toàn bộ báo cáo theo bộ lọc hiện tại (không phân trang)
        if ($request->get('export') === 'csv') {
            return $this->exportNhanVienCsv((clone $query)->get(), $request);
        }

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

    private function exportNhanVienCsv($rows, Request $request)
    {
        $fileName = 'Bao_Cao_Nhan_Vien_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
            'Expires'             => '0',
        ];

        $boLoc = [];
        if ($request->filled('tukhoa')) {
            $boLoc[] = 'Tu khoa: ' . $request->tukhoa;
        }
        if ($request->filled('vai_tro')) {
            $role = NhanVien::normalizeVaiTro($request->vai_tro);
            $boLoc[] = 'Vai tro: ' . (NhanVien::VAI_TRO[$role]['label'] ?? $role);
        }
        if ($request->filled('kich_hoat')) {
            $boLoc[] = 'Trang thai: ' . ($request->kich_hoat === '1' ? 'Dang hoat dong' : 'Vo hieu hoa');
        }

        $sapXepLabel = match ($request->get('sapxep', 'moi_nhat')) {
            'ten_az' => 'Ten A-Z',
            'ten_za' => 'Ten Z-A',
            'dang_nhap' => 'Dang nhap gan nhat',
            default => 'Moi nhat',
        };

        $tongDangHoatDong = $rows->where('kich_hoat', true)->count();
        $tongVoHieuHoa = $rows->count() - $tongDangHoatDong;
        $tongAdmin = $rows->filter(fn($nv) => NhanVien::normalizeVaiTro($nv->vai_tro) === 'admin')->count();
        $tongSale = $rows->filter(fn($nv) => NhanVien::normalizeVaiTro($nv->vai_tro) === 'sale')->count();
        $tongNguonHang = $rows->filter(fn($nv) => NhanVien::normalizeVaiTro($nv->vai_tro) === 'nguon_hang')->count();

        $callback = function () use ($rows, $boLoc, $sapXepLabel, $tongDangHoatDong, $tongVoHieuHoa, $tongAdmin, $tongSale, $tongNguonHang) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            $delimiter = ';';

            fputcsv($file, ['BAO CAO NHAN VIEN'], $delimiter);
            fputcsv($file, ['Thoi gian xuat', now()->format('d/m/Y H:i:s')], $delimiter);
            fputcsv($file, ['Tong so dong', $rows->count()], $delimiter);
            fputcsv($file, ['Sap xep', $sapXepLabel], $delimiter);
            fputcsv($file, ['Bo loc', empty($boLoc) ? 'Khong co' : implode(' | ', $boLoc)], $delimiter);
            fputcsv($file, [], $delimiter);

            fputcsv($file, ['TONG QUAN'], $delimiter);
            fputcsv($file, ['Tong nhan vien (theo bo loc)', $rows->count()], $delimiter);
            fputcsv($file, ['Dang hoat dong', $tongDangHoatDong], $delimiter);
            fputcsv($file, ['Vo hieu hoa', $tongVoHieuHoa], $delimiter);
            fputcsv($file, ['Vai tro Admin', $tongAdmin], $delimiter);
            fputcsv($file, ['Vai tro Sale', $tongSale], $delimiter);
            fputcsv($file, ['Vai tro Nguon hang', $tongNguonHang], $delimiter);
            fputcsv($file, [], $delimiter);

            fputcsv($file, ['CHI TIET DANH SACH'], $delimiter);
            fputcsv($file, ['STT', 'ID', 'Ho ten', 'Email', 'So dien thoai', 'Vai tro', 'Trang thai', 'Dang nhap cuoi', 'Ngay tao'], $delimiter);

            foreach ($rows->values() as $index => $nv) {
                $vaiTro = NhanVien::normalizeVaiTro($nv->vai_tro);
                $vaiTroLabel = NhanVien::VAI_TRO[$vaiTro]['label'] ?? $vaiTro;

                fputcsv($file, [
                    $index + 1,
                    $nv->id,
                    $nv->ho_ten,
                    $nv->email,
                    $nv->so_dien_thoai,
                    $vaiTroLabel,
                    $nv->kich_hoat ? 'Dang hoat dong' : 'Vo hieu hoa',
                    optional($nv->dang_nhap_cuoi_at)->format('d/m/Y H:i:s') ?: 'Chua dang nhap',
                    optional($nv->created_at)->format('d/m/Y H:i:s'),
                ], $delimiter);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // CREATE / STORE
    public function store(Request $request)
    {
        try {
            $data = $this->validateNhanVien($request);
            $data['vai_tro']      = NhanVien::normalizeVaiTro($data['vai_tro']);
            $data['password']     = Hash::make($data['mat_khau']);
            $data['kich_hoat']    = $request->input('kich_hoat', '1') === '1' || $request->boolean('kich_hoat', true);
            $data['anh_dai_dien'] = $this->handleAvatar($request);
            unset($data['mat_khau'], $data['mat_khau_confirmation']);

            // Kiểm tra record bị soft-delete có cùng email/SĐT, nếu có thì restore thay vì INSERT mới
            // để tránh UniqueConstraintViolationException từ DB.
            $trashed = NhanVien::withTrashed()
                ->where(function ($q) use ($data) {
                    $q->where('email', $data['email']);
                    if (!empty($data['so_dien_thoai'])) {
                        $q->orWhere('so_dien_thoai', $data['so_dien_thoai']);
                    }
                })
                ->whereNotNull('deleted_at')
                ->first();

            if ($trashed) {
                $trashed->restore();
                $trashed->forceFill($data)->save();
                $nv = $trashed->fresh();
            } else {
                $nv = NhanVien::create($data);
            }
        } catch (QueryException $e) {
            if ((int) $e->getCode() === 23000) {
                $msg = 'Email hoặc số điện thoại đã tồn tại trong hệ thống.';

                if ($request->wantsJson()) {
                    return response()->json([
                        'ok' => false,
                        'errors' => ['email' => [$msg]],
                    ], 422);
                }

                return back()->withInput()->with('error', '❌ ' . $msg);
            }

            throw $e;
        }

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
        try {
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
                    Storage::disk('r2')->delete($nhanVien->anh_dai_dien);
                }
                $data['anh_dai_dien'] = $newAvatar;
            }

            $nhanVien->update($data);
        } catch (QueryException $e) {
            if ((int) $e->getCode() === 23000) {
                $msg = 'Email hoặc số điện thoại đã tồn tại trong hệ thống.';

                if ($request->wantsJson()) {
                    return response()->json([
                        'ok' => false,
                        'errors' => [
                            'email' => [$msg],
                        ],
                    ], 422);
                }

                return back()->withInput()->with('error', '❌ ' . $msg);
            }

            throw $e;
        }

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
            Storage::disk('r2')->delete($nhanVien->anh_dai_dien);
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
        ], [
            'ho_ten.required'               => 'Vui lòng nhập họ và tên.',
            'ho_ten.max'                    => 'Họ và tên không được vượt quá 100 ký tự.',
            'email.required'                => 'Vui lòng nhập địa chỉ email.',
            'email.email'                   => 'Địa chỉ email không hợp lệ.',
            'email.max'                     => 'Email không được vượt quá 150 ký tự.',
            'email.unique'                  => 'Email này đã được sử dụng bởi nhân viên khác.',
            'mat_khau.required'             => 'Vui lòng nhập mật khẩu.',
            'mat_khau.min'                  => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'mat_khau.max'                  => 'Mật khẩu không được vượt quá 50 ký tự.',
            'mat_khau_confirmation.required' => 'Vui lòng nhập xác nhận mật khẩu.',
            'mat_khau_confirmation.same'    => 'Xác nhận mật khẩu không khớp.',
            'vai_tro.required'              => 'Vui lòng chọn vai trò cho nhân viên.',
            'vai_tro.in'                    => 'Vai trò được chọn không hợp lệ.',
            'so_dien_thoai.max'             => 'Số điện thoại không được vượt quá 20 ký tự.',
            'so_dien_thoai.unique'          => 'Số điện thoại này đã được sử dụng bởi nhân viên khác.',
            'anh_dai_dien.image'            => 'Tệp tải lên phải là hình ảnh.',
            'anh_dai_dien.mimes'            => 'Ảnh đại diện phải có định dạng: JPG, PNG hoặc WebP.',
            'anh_dai_dien.max'              => 'Ảnh đại diện không được vượt quá 2MB.',
        ]);
    }



    private function handleAvatar(Request $request): ?string
    {
        if ($request->hasFile('anh_dai_dien')) {
            return $request->file('anh_dai_dien')->store('avatars/nhan-vien', 'r2');
        }
        return null;
    }
}
