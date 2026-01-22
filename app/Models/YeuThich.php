<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YeuThich extends Model
{
    use HasFactory;

    protected $table = 'yeu_thich';

    protected $fillable = [
        'bat_dong_san_id',
        'khach_hang_id', // <--- QUAN TRỌNG: Phải có dòng này mới lưu được
        'user_id',       // Giữ lại nếu sale cũng dùng chức năng này
        'session_id'     // Giữ lại nếu muốn hỗ trợ khách vãng lai cũ
    ];

    // Liên kết ngược về Bất động sản
    public function batDongSan()
    {
        return $this->belongsTo(BatDongSan::class, 'bat_dong_san_id');
    }

    // Liên kết về Khách hàng
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }
}
