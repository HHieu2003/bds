<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\YeuCauLienHe;
use App\Models\ThongBao;
use App\Models\NhanVien;
use Illuminate\Http\Request;

class LienHeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'so_dien_thoai' => 'required|string|max:20',
            'ho_ten'        => 'nullable|string|max:100',
            'noi_dung'      => 'nullable|string|max:1000',
        ]);

        // Tạo Lead mới
        $lienHe = YeuCauLienHe::create([
            'ho_ten'          => $request->ho_ten ?? 'Khách hàng',
            'so_dien_thoai'   => $request->so_dien_thoai,
            'email'           => $request->email,
            'noi_dung'        => $request->noi_dung ?? 'Khách hàng để lại SĐT cần tư vấn.',
            'bat_dong_san_id' => $request->bat_dong_san_id, // Lấy ID BĐS nếu đang ở trang chi tiết BĐS
            'du_an_id'        => $request->du_an_id,        // Lấy ID Dự án nếu ở trang dự án
            'url_ngu_canh'    => url()->previous(),         // Lưu lại URL khách vừa đứng để Sale biết
            'trang_thai'      => 'moi',                     // Trạng thái thẻ Kanban ban đầu
        ]);

        // Bắn thông báo cho bộ phận Sale/Admin
        $sales = NhanVien::whereIn('vai_tro', ['admin', 'sale'])->where('kich_hoat', 1)->get();
        foreach ($sales as $nv) {
            ThongBao::create([
                'loai'              => 'lead_moi',
                'doi_tuong_nhan'    => 'nhan_vien',
                'doi_tuong_nhan_id' => $nv->id,
                'tieu_de'           => '🔔 YÊU CẦU TƯ VẤN MỚI',
                'noi_dung'          => "Khách hàng {$lienHe->so_dien_thoai} vừa gửi yêu cầu tư vấn. Hãy kiểm tra ngay!",
                'lien_ket'          => route('nhanvien.admin.lien-he.index'),
            ]);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Yêu cầu của bạn đã được gửi. Chúng tôi sẽ gọi lại ngay!']);
        }

        return back()->with('success', 'Yêu cầu của bạn đã được gửi thành công!');
    }
}
