<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class DonUngTuyen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'don_ung_tuyen';

    protected $fillable = [
        'tin_tuyen_dung_id', 'ho_ten', 'email', 'so_dien_thoai',
        'nam_sinh', 'link_cv', 'file_cv', 'gioi_thieu',
        'trang_thai', 'ghi_chu_admin', 'nhan_vien_xu_ly_id',
    ];

    // ── Relationships ──
    public function tinTuyenDung()
    {
        return $this->belongsTo(TinTuyenDung::class, 'tin_tuyen_dung_id');
    }

    public function nguoiXuLy()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_xu_ly_id');
    }

    // ── Accessors ──
    public function getTrangThaiInfoAttribute(): array
    {
        return TinTuyenDung::TRANG_THAI_DON[$this->trang_thai]
            ?? ['label' => $this->trang_thai, 'color' => '#999', 'bg' => '#f5f5f5', 'icon' => 'fas fa-question'];
    }

    public function getFileCvUrlAttribute(): ?string
    {
        if ($this->file_cv) {
            return Storage::disk('r2')->url($this->file_cv);
        }
        return null;
    }
}
