<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinNhanChat extends Model
{
    use HasFactory;

    protected $table = 'tin_nhan_chat';

    // Dùng $guarded = [] để tránh MassAssignmentException
    protected $guarded = [];

    protected $casts = [
        'da_doc'     => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────

    public function phienChat()
    {
        return $this->belongsTo(PhienChat::class, 'phien_chat_id');
    }

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }

    // ── Scopes ─────────────────────────────────────────────

    public function scopeChuaDoc($query)
    {
        return $query->where('da_doc', false);
    }

    public function scopeKhachHang($query)
    {
        return $query->where('nguoi_gui', 'khach_hang');
    }
}
