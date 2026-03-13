<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuAn extends Model
{
    use HasFactory;

    // Tên bảng tương ứng trong cơ sở dữ liệu
    protected $table = 'du_an';

    /**
     * Các thuộc tính có thể gán dữ liệu hàng loạt (Mass Assignment).
     * Cần liệt kê đầy đủ các cột bạn muốn lưu từ Form vào Database.
     */
    protected $fillable = [
        'ten_du_an',
        'slug',
        'dia_chi',
        'chu_dau_tu',
        'don_vi_thi_cong',
        'mo_ta_ngan',
        'noi_dung_chi_tiet',
        'hinh_anh_dai_dien',
        'album_anh',
        'dien_tich_tong_the',
        'map_url',
        'trang_thai',
    ];

    /**
     * Khai báo kiểu dữ liệu cho các cột đặc biệt.
     * Cột 'album_anh' lưu dạng JSON nên cần cast về 'array' để Laravel tự động xử lý.
     */
    protected $casts = [
        'album_anh' => 'array',
    ];

    /**
     * Mối quan hệ: Một Dự án có nhiều Bất động sản.
     */
    public function batDongSans()
    {
        return $this->hasMany(BatDongSan::class, 'du_an_id');
    }
}
