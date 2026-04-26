<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class TinTuyenDung extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tin_tuyen_dung';

    protected $fillable = [
        'tieu_de', 'slug', 'phong_ban', 'hinh_thuc', 'dia_diem',
        'so_luong', 'muc_luong', 'tag', 'tag_color',
        'mo_ta_ngan', 'mo_ta_cong_viec', 'yeu_cau', 'quyen_loi',
        'han_nop', 'hien_thi', 'noi_bat', 'thu_tu', 'nhan_vien_id',
    ];

    protected $casts = [
        'hien_thi' => 'boolean',
        'noi_bat'  => 'boolean',
        'han_nop'  => 'date',
    ];

    // ── Constants ──
    const HINH_THUC = [
        'toan_thoi_gian' => 'Toàn thời gian',
        'ban_thoi_gian'  => 'Bán thời gian',
        'thuc_tap'       => 'Thực tập',
        'cong_tac_vien'  => 'Cộng tác viên',
    ];

    const TRANG_THAI_DON = [
        'moi'            => ['label' => 'Mới nộp',          'color' => '#3b82f6', 'bg' => '#eff6ff',  'icon' => 'fas fa-inbox'],
        'dang_xem_xet'   => ['label' => 'Đang xem xét',     'color' => '#f59e0b', 'bg' => '#fffbeb',  'icon' => 'fas fa-eye'],
        'hen_phong_van'  => ['label' => 'Hẹn phỏng vấn',    'color' => '#8b5cf6', 'bg' => '#f5f3ff',  'icon' => 'fas fa-calendar-check'],
        'trung_tuyen'    => ['label' => 'Trúng tuyển',       'color' => '#10b981', 'bg' => '#ecfdf5',  'icon' => 'fas fa-check-circle'],
        'tu_choi'        => ['label' => 'Từ chối',           'color' => '#ef4444', 'bg' => '#fef2f2',  'icon' => 'fas fa-times-circle'],
    ];

    const TAG_COLORS = [
        'danger'  => ['label' => 'Đỏ (Hot/Gấp)',     'class' => 'bg-danger-subtle text-danger'],
        'primary' => ['label' => 'Xanh dương',        'class' => 'bg-primary-subtle text-primary'],
        'success' => ['label' => 'Xanh lá',           'class' => 'bg-success-subtle text-success'],
        'warning' => ['label' => 'Vàng',              'class' => 'bg-warning-subtle text-warning'],
        'info'    => ['label' => 'Cyan',               'class' => 'bg-info-subtle text-info'],
    ];

    // ── Relationships ──
    public function nguoiDang()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }

    public function donUngTuyens()
    {
        return $this->hasMany(DonUngTuyen::class, 'tin_tuyen_dung_id');
    }

    // ── Accessors ──
    public function getHinhThucLabelAttribute(): string
    {
        return self::HINH_THUC[$this->hinh_thuc] ?? $this->hinh_thuc;
    }

    public function getTagClassAttribute(): string
    {
        return self::TAG_COLORS[$this->tag_color]['class'] ?? 'bg-secondary-subtle text-secondary';
    }

    public function getConHanAttribute(): bool
    {
        return !$this->han_nop || $this->han_nop->isFuture() || $this->han_nop->isToday();
    }

    public function getSoDonMoiAttribute(): int
    {
        return $this->donUngTuyens()->where('trang_thai', 'moi')->count();
    }

    // ── Scopes ──
    public function scopeHienThi($query)
    {
        return $query->where('hien_thi', true)->orderBy('thu_tu')->orderByDesc('created_at');
    }

    // ── Auto slug ──
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->tieu_de) . '-' . Str::random(5);
            }
        });
    }
}
