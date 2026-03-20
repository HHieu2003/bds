<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuotXemBatDongSan extends Model
{
    use HasFactory;

    protected $table = 'luot_xem_bat_dong_san';

    protected $fillable = [
        'bat_dong_san_id',
        'khach_hang_id',
        'session_id',
        'ip_address',
        'user_agent',
        'nguon_truy_cap',
        'url_trang_truoc',
        'thiet_bi',
        'thoi_diem_xem',
    ];

    protected $casts = [
        'thoi_diem_xem' => 'datetime',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================
    public function batDongSan()
    {
        return $this->belongsTo(BatDongSan::class, 'bat_dong_san_id');
    }

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }
}
