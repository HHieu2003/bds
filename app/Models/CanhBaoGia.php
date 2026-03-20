<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanhBaoGia extends Model
{
    use HasFactory;

    protected $table = 'canh_bao_gia';

    protected $fillable = [
        'khach_hang_id',
        'bat_dong_san_id',
        'email',
        'gia_mong_muon',
        'gia_luc_dang_ky',
        'da_gui_thong_bao',
        'thoi_diem_gui',
    ];

    protected $casts = [
        'gia_mong_muon'    => 'decimal:2',
        'gia_luc_dang_ky'  => 'decimal:2',
        'da_gui_thong_bao' => 'boolean',
        'thoi_diem_gui'    => 'datetime',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }

    public function batDongSan()
    {
        return $this->belongsTo(BatDongSan::class, 'bat_dong_san_id');
    }

    // ============================================================
    // SCOPES
    // ============================================================
    public function scopeChuaGui($query)
    {
        return $query->where('da_gui_thong_bao', false);
    }
}
