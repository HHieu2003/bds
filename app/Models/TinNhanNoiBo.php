<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinNhanNoiBo extends Model
{
    use HasFactory;

    protected $table = 'tin_nhan_noi_bo';

    protected $fillable = [
        'nguoi_gui_id',
        'nguoi_nhan_id',
        'lich_hen_id',
        'loai_tin_nhan',
        'noi_dung',
        'tep_dinh_kem',
        'da_doc',
        'da_doc_at',
    ];

    protected $casts = [
        'da_doc'    => 'boolean',
        'da_doc_at' => 'datetime',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================
    public function nguoiGui()
    {
        return $this->belongsTo(NhanVien::class, 'nguoi_gui_id');
    }

    public function nguoiNhan()
    {
        return $this->belongsTo(NhanVien::class, 'nguoi_nhan_id');
    }

    public function lichHen()
    {
        return $this->belongsTo(LichHen::class, 'lich_hen_id');
    }

    // ============================================================
    // HELPERS
    // ============================================================
    public function danhDauDaDoc(): void
    {
        if (!$this->da_doc) {
            $this->update(['da_doc' => true, 'da_doc_at' => now()]);
        }
    }

    // ============================================================
    // SCOPES
    // ============================================================
    public function scopeChuaDoc($query)
    {
        return $query->where('da_doc', false);
    }

    public function scopeGiua($query, int $nv1, int $nv2)
    {
        return $query->where(
            fn($q) =>
            $q->where('nguoi_gui_id', $nv1)->where('nguoi_nhan_id', $nv2)
        )->orWhere(
            fn($q) =>
            $q->where('nguoi_gui_id', $nv2)->where('nguoi_nhan_id', $nv1)
        );
    }
}
