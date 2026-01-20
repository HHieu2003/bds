<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperBatDongSan
 */
class BatDongSan extends Model
{
    use HasFactory;

    protected $table = 'bat_dong_san';

    protected $fillable = [
        'du_an_id',
        'user_id',
        'tieu_de',  // <--- SEO
        'slug',     // <--- SEO
        'toa',
        'ma_can',
        'ngay_goi',
        'ngay_dang',
        'huong_cua',
        'huong_ban_cong',
        'dien_tich',
        'so_phong_ngu',
        'so_phong_tam',
        'noi_that',
        'gia',
        'hinh_thuc_thanh_toan',
        'thoi_gian_vao',
        'mo_ta',
        'hinh_anh',
        'loai_hinh',
        'trang_thai'
    ];

    protected $casts = [
        'hinh_anh' => 'array',
        'ngay_goi' => 'date',
        'ngay_dang' => 'date',
    ];

    public function duAn()
    {
        return $this->belongsTo(DuAn::class, 'du_an_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
