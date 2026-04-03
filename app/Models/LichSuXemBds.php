<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuXemBds extends Model
{
    protected $table = 'lich_su_xem_bds';

    protected $fillable = [
        'bat_dong_san_id',
        'khach_hang_id',
        'session_id',
        'loai_hinh',
        'nhu_cau',
        'du_an_id',
        'khu_vuc_id',
        'gia_tu',
        'gia_den',
        'thoi_gian_xem',
    ];

    public function batDongSan()
    {
        return $this->belongsTo(BatDongSan::class, 'bat_dong_san_id');
    }
}
