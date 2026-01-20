<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperLienHe
 */
class LienHe extends Model
{
    use HasFactory;

    protected $table = 'lien_he'; // Khai báo tên bảng

    protected $fillable = [
        'so_dien_thoai',
        'noi_dung',
        'bat_dong_san_id',
        'trang_thai'
    ];

    // Mối quan hệ: Tin nhắn này thuộc về BĐS nào?
    public function batDongSan()
    {
        return $this->belongsTo(BatDongSan::class, 'bat_dong_san_id');
    }
}
