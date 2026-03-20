<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GhiChuKhachHang extends Model
{
    use HasFactory;

    protected $table = 'ghi_chu_khach_hang';

    protected $fillable = [
        'khach_hang_id',
        'nhan_vien_id',
        'bat_dong_san_id',
        'lich_hen_id',
        'noi_dung',
        'kenh_tuong_tac',
        'ket_qua',
        'nhac_lai_at',
    ];

    protected $casts = [
        'nhac_lai_at' => 'datetime',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }

    public function batDongSan()
    {
        return $this->belongsTo(BatDongSan::class, 'bat_dong_san_id');
    }

    public function lichHen()
    {
        return $this->belongsTo(LichHen::class, 'lich_hen_id');
    }

    // ============================================================
    // SCOPES
    // ============================================================
    public function scopeCanNhacLai($query)
    {
        return $query->whereNotNull('nhac_lai_at')
            ->where('nhac_lai_at', '<=', now());
    }
}
