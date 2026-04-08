<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhienChat extends Model
{
    use HasFactory;

    protected $table = 'phien_chat';
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function tinNhans()
    {
        return $this->hasMany(TinNhanChat::class, 'phien_chat_id');
    }

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_phu_trach_id');
    }

    public function nhanVienPhuTrach()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_phu_trach_id');
    }

    public function tinNhan()
    {
        return $this->hasMany(TinNhanChat::class, 'phien_chat_id');
    }

    public function tinNhanCuoi()
    {
        return $this->hasOne(TinNhanChat::class, 'phien_chat_id')->latestOfMany();
    }

    public function getTenHienThiAttribute(): string
    {
        return $this->khachHang?->ho_ten
            ?: $this->ten_khach_vang_lai
            ?: 'Khach vang lai';
    }
}
