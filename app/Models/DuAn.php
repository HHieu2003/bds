<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDuAn
 */
class DuAn extends Model
{
    use HasFactory;

    // 1. Khai báo tên bảng (Bắt buộc vì tên bảng tiếng Việt)
    protected $table = 'du_an';

    // 2. Cho phép điền dữ liệu vào các cột này (Mass Assignment)
    protected $fillable = [
        'ten_du_an',
        'dia_chi',
        'chu_dau_tu',
        'don_vi_thi_cong',
        'mo_ta',
        'hinh_anh',
    ];

    // 3. Mối quan hệ: Một Dự án có nhiều Bất động sản
    public function batDongSans()
    {
        return $this->hasMany(BatDongSan::class, 'du_an_id');
    }
}
