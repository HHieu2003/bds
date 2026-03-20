<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhienChat extends Model
{
    use HasFactory;

    protected $table = 'phien_chat';
    protected $guarded = [];

    // Phiên chat thuộc về 1 Khách hàng
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }

    // Phiên chat được tiếp nhận bởi 1 Nhân viên (Sale)
    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }

    // 1 Phiên chat có nhiều Tin nhắn
    public function tinNhan()
    {
        return $this->hasMany(TinNhanChat::class, 'phien_chat_id');
    }
}
