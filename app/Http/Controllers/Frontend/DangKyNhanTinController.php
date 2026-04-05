<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DangKyNhanTin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DangKyNhanTinController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'nhu_cau' => 'nullable|in:ban,thue',
            'khu_vuc_id' => 'nullable|exists:khu_vuc,id',
            'du_an_id' => 'nullable|exists:du_an,id',
            'bat_dong_san_id' => 'nullable|exists:bat_dong_san,id',
            'so_phong_ngu' => 'nullable|in:studio,1,2,3',
            'muc_gia_tu' => 'nullable|numeric|min:0|max:9999999999999',
            'muc_gia_den' => 'nullable|numeric|min:0|max:9999999999999|gte:muc_gia_tu',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'khu_vuc_id.exists' => 'Khu vực bạn chọn không tồn tại.',
            'so_phong_ngu.in' => 'Giá trị số phòng ngủ không hợp lệ.',
            'muc_gia_den.gte' => 'Mức giá tối đa phải lớn hơn mức giá tối thiểu.',
            'muc_gia_tu.max' => 'Ngân sách bạn nhập vượt quá giới hạn hệ thống.',
            'muc_gia_den.max' => 'Ngân sách bạn nhập vượt quá giới hạn hệ thống.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu gửi lên chưa hợp lệ.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        $email = strtolower(trim((string) $validated['email']));
        $khachHangId = Auth::guard('customer')->id();
        $now = now();

        try {
            $result = DB::transaction(function () use ($validated, $email, $khachHangId, $now) {
                $query = DB::table('dang_ky_nhan_tin')
                    ->whereRaw('LOWER(email) = ?', [$email])
                    ->where('nhu_cau', $validated['nhu_cau'] ?? null)
                    ->where('khu_vuc_id', $validated['khu_vuc_id'] ?? null)
                    ->where('du_an_id', $validated['du_an_id'] ?? null)
                    ->where('bat_dong_san_id', $validated['bat_dong_san_id'] ?? null)
                    ->where('so_phong_ngu', $validated['so_phong_ngu'] ?? null)
                    ->where('muc_gia_tu', $validated['muc_gia_tu'] ?? null)
                    ->where('muc_gia_den', $validated['muc_gia_den'] ?? null);

                $existing = $query->lockForUpdate()->orderByDesc('id')->first();

                $payload = [
                    'khach_hang_id' => $khachHangId ?: ($existing->khach_hang_id ?? null),
                    'email' => $email,
                    'nhu_cau' => $validated['nhu_cau'] ?? null,
                    'khu_vuc_id' => $validated['khu_vuc_id'] ?? null,
                    'du_an_id' => $validated['du_an_id'] ?? null,
                    'bat_dong_san_id' => $validated['bat_dong_san_id'] ?? null,
                    'so_phong_ngu' => $validated['so_phong_ngu'] ?? null,
                    'muc_gia_tu' => $validated['muc_gia_tu'] ?? null,
                    'muc_gia_den' => $validated['muc_gia_den'] ?? null,
                    'trang_thai' => true,
                    'updated_at' => $now,
                ];

                if ($existing) {
                    DB::table('dang_ky_nhan_tin')
                        ->where('id', $existing->id)
                        ->update($payload);

                    return 'reactivated';
                }

                DB::table('dang_ky_nhan_tin')->insert($payload + [
                    'created_at' => $now,
                ]);

                return 'created';
            });

            return response()->json([
                'success' => true,
                'message' => $result === 'reactivated'
                    ? 'Bạn đã đăng ký bộ tiêu chí này trước đó. Hệ thống đã kích hoạt lại thông báo cho bạn.'
                    : 'Đăng ký thành công! Bạn có thể đăng ký thêm nhiều tiêu chí khác cho cùng email.',
                'action' => $result,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Không thể xử lý đăng ký lúc này. Vui lòng thử lại sau.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        // Tìm bản ghi và đảm bảo nó thuộc về khách hàng đang đăng nhập
        $dangKy = DangKyNhanTin::where('id', $id)
            ->where('khach_hang_id', Auth::guard('customer')->id())
            ->firstOrFail();

        // Xóa bản ghi
        $dangKy->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã hủy đăng ký nhận thông báo thành công!'
        ]);
    }
}
