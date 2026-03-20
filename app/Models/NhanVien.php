<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class NhanVien extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $table = 'nhan_vien';

    protected $fillable = [
        'ho_ten',
        'email',
        'password',
        'vai_tro',
        'so_dien_thoai',
        'anh_dai_dien',
        'dia_chi',
        'kich_hoat',
        'dang_nhap_cuoi_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'kich_hoat'         => 'boolean',
        'dang_nhap_cuoi_at' => 'datetime',
    ];

    // ── Constants ──
    const VAI_TRO = [
        'admin'      => ['label' => 'Admin',       'color' => '#e74c3c', 'bg' => '#fff0f0', 'icon' => 'fas fa-crown'],
        'nguon_hang' => ['label' => 'Nguồn hàng',  'color' => '#8e44ad', 'bg' => '#f5eeff', 'icon' => 'fas fa-building'],
        'sale'       => ['label' => 'Sale',         'color' => '#2d6a9f', 'bg' => '#e8f4fd', 'icon' => 'fas fa-user-tie'],
    ];

    // ══════════════════════════════
    // HELPERS VAI TRÒ
    // ══════════════════════════════

    /**
     * Dùng trong CheckRole middleware: $user->hasRole('admin')
     * hoặc $user->hasRole(['admin', 'sale'])
     */
    public function hasRole(string|array $roles): bool
    {
        return in_array($this->vai_tro, (array) $roles);
    }

    public function isAdmin(): bool
    {
        return $this->vai_tro === 'admin';
    }

    public function isSale(): bool
    {
        return $this->vai_tro === 'sale';
    }

    public function isNguonHang(): bool
    {
        return $this->vai_tro === 'nguon_hang';
    }

    // ══════════════════════════════
    // ACCESSORS
    // ══════════════════════════════

    public function getVaiTroLabelAttribute(): string
    {
        return match ($this->vai_tro) {
            'admin'      => 'Quản trị viên',
            'sale'       => 'Sale',
            'nguon_hang' => 'Nguồn hàng',
            default      => 'Nhân viên',
        };
    }

    public function getVaiTroInfoAttribute(): array
    {
        return self::VAI_TRO[$this->vai_tro]
            ?? ['label' => $this->vai_tro, 'color' => '#999', 'bg' => '#f5f5f5', 'icon' => 'fas fa-user'];
    }

    /**
     * URL ảnh đại diện — fallback về ui-avatars nếu không có file
     */
    public function getAnhDaiDienUrlAttribute(): string
    {
        if ($this->anh_dai_dien && Storage::disk('public')->exists($this->anh_dai_dien)) {
            return asset('storage/' . $this->anh_dai_dien);
        }

        $name = urlencode($this->ho_ten ?: 'NV');
        return 'https://ui-avatars.com/api/?name=' . $name
            . '&background=1a3c5e&color=fff&size=100&bold=true';
    }

    /** Alias cho getAnhDaiDienUrlAttribute — tương thích code cũ dùng $nv->avatar_url */
    public function getAvatarUrlAttribute(): string
    {
        return $this->anh_dai_dien_url;
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->vai_tro === 'admin';
    }

    // ══════════════════════════════
    // RELATIONSHIPS
    // ══════════════════════════════

    // ── RELATIONSHIPS ──

    public function batDongSans()
    {
        return $this->hasMany(\App\Models\BatDongSan::class, 'nhan_vien_phu_trach_id');
    }

    public function khachHangs()
    {
        return $this->hasMany(\App\Models\KhachHang::class, 'nhan_vien_phu_trach_id');
    }

    public function lichHenSale()
    {
        return $this->hasMany(\App\Models\LichHen::class, 'nhan_vien_sale_id');
    }

    public function lichHenNguonHang()
    {
        return $this->hasMany(\App\Models\LichHen::class, 'nhan_vien_nguon_hang_id');
    }

    public function kyGuis()
    {
        return $this->hasMany(\App\Models\KyGui::class, 'nhan_vien_phu_trach_id');
    }

    public function baiViets()
    {
        return $this->hasMany(\App\Models\BaiViet::class, 'nhan_vien_id');
    }
}
