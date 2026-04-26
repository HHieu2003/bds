<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NganHang extends Model
{
    use HasFactory;

    // Chỉ định tên bảng trong CSDL
    protected $table = 'ngan_hang';

    // Cho phép điền dữ liệu hàng loạt vào các cột này
    protected $fillable = [
        'ten_ngan_hang',
        'logo',
        'lai_suat_uu_dai',
        'thoi_gian_uu_dai',
        'lai_suat_tha_noi',
        'ty_le_vay_toi_da',
        'thoi_gian_vay_toi_da',
        'trang_thai',
    ];

    // Ép kiểu dữ liệu khi lấy ra để dễ tính toán
    protected $casts = [
        'trang_thai' => 'boolean',
        'lai_suat_uu_dai' => 'float',
        'lai_suat_tha_noi' => 'float',
        'ty_le_vay_toi_da' => 'integer',
        'thoi_gian_vay_toi_da' => 'integer',
    ];

    // Helper function để lấy đường dẫn ảnh logo
    public function getLogoUrlAttribute(): string
    {
        return $this->logo
            ? \Illuminate\Support\Facades\Storage::disk('r2')->url($this->logo)
            : asset('images/default-bank.png');
    }
}
