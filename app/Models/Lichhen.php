<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LichHen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lich_hen';

    protected $fillable = [
        'khach_hang_id',
        'bat_dong_san_id',
        'nhan_vien_sale_id',
        'nhan_vien_nguon_hang_id',
        'ten_khach_hang',
        'sdt_khach_hang',
        'email_khach_hang',
        'thoi_gian_hen',
        'dia_diem_hen',
        'ghi_chu_sale',
        'ghi_chu_nguon_hang',
        'nguon_dat_lich',
        'trang_thai',
        'ly_do_tu_choi',
        'xac_nhan_at',
        'tu_choi_at',
        'hoan_thanh_at',
        'huy_at',
    ];

    protected $casts = [
        'thoi_gian_hen'  => 'datetime',
        'xac_nhan_at'    => 'datetime',
        'tu_choi_at'     => 'datetime',
        'hoan_thanh_at'  => 'datetime',
        'huy_at'         => 'datetime',
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

    public function nhanVienSale()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_sale_id');
    }

    public function nhanVienNguonHang()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_nguon_hang_id');
    }

    public function ghiChu()
    {
        return $this->hasMany(GhiChuKhachHang::class, 'lich_hen_id');
    }

    public function tinNhanNoiBo()
    {
        return $this->hasMany(TinNhanNoiBo::class, 'lich_hen_id');
    }

    // ============================================================
    // SCOPES
    // ============================================================
    public function scopeMoi($query)
    {
        return $query->where('trang_thai', 'moi_dat');
    }
    public function scopeChoXacNhan($query)
    {
        return $query->where('trang_thai', 'cho_xac_nhan');
    }
    public function scopeDaXacNhan($query)
    {
        return $query->where('trang_thai', 'da_xac_nhan');
    }
}
