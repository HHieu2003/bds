<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DangKyNhanTin extends Model
{
    use HasFactory;

    protected $table = 'dang_ky_nhan_tin';

    protected $fillable = [
        'khach_hang_id',
        'email',
        'nhu_cau',
        'khu_vuc_id',
        'du_an_id',
        'bat_dong_san_id',
        'so_phong_ngu',
        'muc_gia_tu',
        'muc_gia_den',
        'trang_thai',
    ];

    protected $casts = [
        'muc_gia_tu' => 'decimal:2',
        'muc_gia_den' => 'decimal:2',
        'trang_thai' => 'boolean',
    ];

    // Các mối quan hệ (Relationships)
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }

    public function duAn()
    {
        return $this->belongsTo(DuAn::class, 'du_an_id');
    }

    public function khuVuc()
    {
        return $this->belongsTo(KhuVuc::class, 'khu_vuc_id');
    }

    public function batDongSan()
    {
        return $this->belongsTo(BatDongSan::class, 'bat_dong_san_id');
    }
}
