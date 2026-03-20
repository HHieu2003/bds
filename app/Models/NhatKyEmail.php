<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhatKyEmail extends Model
{
    use HasFactory;

    protected $table = 'nhat_ky_email';

    protected $fillable = [
        'khach_hang_id',
        'nhan_vien_id',
        'loai_email',
        'email_nguoi_nhan',
        'tieu_de',
        'noi_dung',
        'trang_thai',
        'loi',
        'doi_tuong_lien_quan',
        'doi_tuong_id',
        'thoi_diem_gui',
    ];

    protected $casts = [
        'thoi_diem_gui' => 'datetime',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }

    // ============================================================
    // SCOPES
    // ============================================================
    public function scopeThatBai($query)
    {
        return $query->where('trang_thai', 'that_bai');
    }
    public function scopeThanhCong($query)
    {
        return $query->where('trang_thai', 'thanh_cong');
    }
}
