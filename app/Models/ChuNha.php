<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChuNha extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'chu_nha';
    protected $fillable = [
        'ho_ten',
        'so_dien_thoai',
        'email',
        'cccd',
        'dia_chi',
        'ghi_chu',
        'nhan_vien_phu_trach_id'
    ];

    public function nhanVienPhuTrach()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_phu_trach_id');
    }
    public function batDongSans()
    {
        return $this->hasMany(BatDongSan::class, 'chu_nha_id');
    }
}
