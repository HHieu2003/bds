<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichSuTimKiem extends Model
{
    use HasFactory;

    protected $table = 'lich_su_tim_kiem';

    protected $fillable = [
        'khach_hang_id',
        'session_id',
        'tu_khoa',
        'bo_loc',
        'sap_xep_theo',
        'so_ket_qua',
        'thoi_diem_tim_kiem',
    ];

    protected $casts = [
        'bo_loc'              => 'array',
        'thoi_diem_tim_kiem'  => 'datetime',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }
}
