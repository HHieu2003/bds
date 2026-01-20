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
        'user_id',
        'session_id'
    ];

    // Liên kết ngược về Bất động sản để lấy thông tin hiển thị
    public function batDongSan()
    {
        return $this->belongsTo(BatDongSan::class, 'bat_dong_san_id');
    }
}
