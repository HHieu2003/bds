<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KhuVuc extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'khu_vuc';

    protected $fillable = [
        'cap_khu_vuc',
        'khu_vuc_cha_id',
        'ten_khu_vuc',
        'slug',
        'mo_ta',
        'hien_thi',
        'thu_tu_hien_thi',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'hien_thi' => 'boolean',
    ];

    // ── Constants ──
    const CAP = [
        'tinh_thanh' => ['label' => 'Tỉnh / Thành phố', 'color' => '#e74c3c', 'bg' => '#fff0f0', 'icon' => 'fas fa-city',     'order' => 1],
        'quan_huyen' => ['label' => 'Quận / Huyện',     'color' => '#2d6a9f', 'bg' => '#e8f4fd', 'icon' => 'fas fa-map-marked-alt', 'order' => 2],
        'phuong_xa'  => ['label' => 'Phường / Xã',      'color' => '#27ae60', 'bg' => '#e8f8f0', 'icon' => 'fas fa-map-pin',  'order' => 3],
    ];

    // ── Relationships ──
    public function cha()
    {
        return $this->belongsTo(KhuVuc::class, 'khu_vuc_cha_id');
    }

    public function con()
    {
        return $this->hasMany(KhuVuc::class, 'khu_vuc_cha_id')
            ->orderBy('thu_tu_hien_thi');
    }

    public function duAn()
    {
        return $this->hasMany(DuAn::class, 'khu_vuc_id');
    }

    // ── Scopes ──
    public function scopeHienThi($query)
    {
        return $query->where('hien_thi', true)->orderBy('thu_tu_hien_thi');
    }

    public function scopeCap($query, string $cap)
    {
        return $query->where('cap_khu_vuc', $cap);
    }

    // ── Accessors ──
    public function getCapInfoAttribute(): array
    {
        return self::CAP[$this->cap_khu_vuc]
            ?? ['label' => $this->cap_khu_vuc, 'color' => '#999', 'bg' => '#f5f5f5', 'icon' => 'fas fa-map'];
    }

    public function getDuongDanAttribute(): string
    {
        $parts = [];
        if ($this->cha) {
            if ($this->cha->cha) {
                $parts[] = $this->cha->cha->ten_khu_vuc;
            }
            $parts[] = $this->cha->ten_khu_vuc;
        }
        $parts[] = $this->ten_khu_vuc;
        return implode(' › ', $parts);
    }
}
