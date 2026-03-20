<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ThongBao extends Model
{
    use HasFactory;

    protected $table      = 'thong_bao';
    // UUID primary key (theo migration)
    protected $keyType    = 'string';
    public    $incrementing = false;

    protected $fillable = [
        'id',
        'loai',
        'doi_tuong_nhan',
        'doi_tuong_nhan_id',
        'tieu_de',
        'noi_dung',
        'du_lieu',
        'lien_ket',
        'da_doc_at',
    ];

    protected $casts = [
        'du_lieu'   => 'array',
        'da_doc_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($model) => $model->id = (string) Str::uuid());
    }

    // ============================================================
    // HELPERS
    // ============================================================
    public function daDoc(): bool
    {
        return $this->da_doc_at !== null;
    }
    public function chuaDoc(): bool
    {
        return $this->da_doc_at === null;
    }

    public function danhDauDaDoc(): void
    {
        if (!$this->daDoc()) {
            $this->update(['da_doc_at' => now()]);
        }
    }

    // ============================================================
    // SCOPES
    // ============================================================
    public function scopeChuaDoc($query)
    {
        return $query->whereNull('da_doc_at');
    }

    public function scopeCuaNhanVien($query, int $nhanVienId)
    {
        return $query->where('doi_tuong_nhan', 'nhan_vien')
            ->where('doi_tuong_nhan_id', $nhanVienId);
    }

    public function scopeCuaKhachHang($query, int $khachHangId)
    {
        return $query->where('doi_tuong_nhan', 'khach_hang')
            ->where('doi_tuong_nhan_id', $khachHangId);
    }
}
