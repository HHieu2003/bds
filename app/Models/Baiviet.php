<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class BaiViet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bai_viet';

    protected $fillable = [
        'nhan_vien_id',
        'tieu_de',
        'slug',
        'mo_ta_ngan',
        'noi_dung',
        'hinh_anh',
        'album_anh',
        'loai_bai_viet',
        'noi_bat',
        'hien_thi',
        'luot_xem',
        'thu_tu_hien_thi',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'thoi_diem_dang',
    ];

    protected $casts = [
        'album_anh'     => 'array',
        'noi_bat'       => 'boolean',
        'hien_thi'      => 'boolean',
        'thoi_diem_dang' => 'datetime',
    ];

    // ── Constants ──
    const LOAI = [
        'tin_tuc'   => ['label' => 'Tin tức',        'color' => '#2d6a9f', 'bg' => '#e8f4fd', 'icon' => 'fas fa-newspaper'],
        'phong_thuy' => ['label' => 'Phong thủy',     'color' => '#27ae60', 'bg' => '#e8f8f0', 'icon' => 'fas fa-yin-yang'],
        'tuyen_dung' => ['label' => 'Tuyển dụng',     'color' => '#e67e22', 'bg' => '#fff8f0', 'icon' => 'fas fa-briefcase'],
        'kien_thuc' => ['label' => 'Kiến thức BĐS',  'color' => '#8e44ad', 'bg' => '#f5eeff', 'icon' => 'fas fa-book-open'],
    ];

    // ── Relationships ──
    public function tacGia()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }

    // ── Accessors ──
    public function getHinhAnhUrlAttribute(): ?string
    {
        if ($this->hinh_anh && Storage::disk('public')->exists($this->hinh_anh)) {
            return asset('storage/' . $this->hinh_anh);
        }
        return null;
    }

    public function getLoaiInfoAttribute(): array
    {
        return self::LOAI[$this->loai_bai_viet]
            ?? ['label' => $this->loai_bai_viet, 'color' => '#999', 'bg' => '#f5f5f5', 'icon' => 'fas fa-file'];
    }

    // ── Scopes ──
    public function scopeHienThi($query)
    {
        return $query->where('hien_thi', true)->orderByDesc('thoi_diem_dang');
    }

    public function scopeLoai($query, string $loai)
    {
        return $query->where('loai_bai_viet', $loai);
    }

    public function scopeNoiBat($query)
    {
        return $query->where('noi_bat', true)->where('hien_thi', true);
    }
}
