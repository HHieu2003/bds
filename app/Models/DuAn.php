<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DuAn extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'du_an';

    protected $fillable = [
        'khu_vuc_id',
        'ten_du_an',
        'slug',
        'dia_chi',
        'chu_dau_tu',
        'don_vi_thi_cong',
        'mo_ta_ngan',
        'noi_dung_chi_tiet',
        'hinh_anh_dai_dien',
        'album_anh',
        'video_url',
        'map_url',
        'noi_bat',
        'hien_thi',
        'thu_tu_hien_thi',
        'trang_thai',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'thoi_diem_dang',
    ];

    protected $casts = [
        'hien_thi'    => 'boolean',
        'noi_bat'     => 'boolean',
        'album_anh'   => 'array',
        'thoi_diem_dang' => 'datetime',
    ];

    // ── Relationships ──
    public function khuVuc()
    {
        return $this->belongsTo(KhuVuc::class, 'khu_vuc_id');
    }

    public function batDongSans()
    {
        return $this->hasMany(BatDongSan::class, 'du_an_id');
    }

    // ── Scopes ──
    public function scopeHienThi($query)
    {
        return $query->where('hien_thi', true);
    }
}
