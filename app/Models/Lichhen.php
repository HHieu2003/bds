<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichHen extends Model
{
    use HasFactory;

    protected $table = 'lich_hen';

    protected $fillable = [
        'ten_khach_hang',
        'sdt_khach_hang',
        'email_khach_hang',
        'bat_dong_san_id',
        'thoi_gian_hen',
        'nhan_vien_id',
        'trang_thai' // moi_dat, da_xac_nhan, hoan_thanh, huy
    ];

    // Liên kết với Bất động sản
    public function batDongSan()
    {
        return $this->belongsTo(BatDongSan::class, 'bat_dong_san_id');
    }

    // Liên kết với Nhân viên (nếu có)
    public function nhanVien()
    {
        return $this->belongsTo(User::class, 'nhan_vien_id');
    }
}
