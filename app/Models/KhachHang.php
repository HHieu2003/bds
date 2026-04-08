<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class KhachHang extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $table    = 'khach_hang';
    protected $guard    = 'customer';

    protected $fillable = [
        'ho_ten',
        'so_dien_thoai',
        'email',
        'password',
        'verification_token',
        'token_expiry',
        'nguon_khach_hang',
        'muc_do_tiem_nang',
        'nhan_vien_phu_trach_id',
        'ghi_chu_noi_bo',
        'kich_hoat',
        'sdt_xac_thuc_at',
        'email_xac_thuc_at',
        'lien_he_cuoi_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'kich_hoat'          => 'boolean',
        'sdt_xac_thuc_at'    => 'datetime',
        'email_xac_thuc_at'  => 'datetime',
        'lien_he_cuoi_at'    => 'datetime',
        'email_xac_thuc_at' => 'datetime',
        'token_expiry'      => 'datetime',
    ];

    // ============================================================
    // HELPER — dùng trong view: getContactInfo()
    // Trả ve SĐT nếu có, không thì trả về email
    // ============================================================
    public function getContactInfo(): string
    {
        return $this->so_dien_thoai ?? $this->email ?? 'Chưa cập nhật';
    }

    public function getNameAttribute(): string
    {
        return $this->ho_ten ?? 'Khách hàng';
    }

    // ============================================================
    // RELATIONSHIPS
    // ============================================================
    public function nhanVienPhuTrach()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_phu_trach_id');
    }

    public function yeuCauLienHe()
    {
        return $this->hasMany(YeuCauLienHe::class, 'khach_hang_id');
    }

    public function lichHen()
    {
        return $this->hasMany(LichHen::class, 'khach_hang_id');
    }

    public function kyGui()
    {
        return $this->hasMany(KyGui::class, 'khach_hang_id');
    }

    public function yeuThich()
    {
        return $this->hasMany(YeuThich::class, 'khach_hang_id');
    }

    public function danhSachYeuThich()
    {
        return $this->belongsToMany(
            BatDongSan::class,
            'yeu_thich',       // Tên bảng trung gian
            'khach_hang_id',   // Khóa ngoại của bảng hiện tại (KhachHang)
            'bat_dong_san_id'  // Khóa ngoại của bảng đích (BatDongSan)
        )->withTimestamps();
    }

    public function canhBaoGia()
    {
        return $this->hasMany(DangKyNhanTin::class, 'khach_hang_id');
    }

    public function lichSuTimKiem()
    {
        return $this->hasMany(LichSuTimKiem::class, 'khach_hang_id');
    }

    public function phienChat()
    {
        return $this->hasMany(PhienChat::class, 'khach_hang_id');
    }

    public function ghiChu()
    {
        return $this->hasMany(GhiChuKhachHang::class, 'khach_hang_id');
    }

    public function nhatKyEmail()
    {
        return $this->hasMany(NhatKyEmail::class, 'khach_hang_id');
    }

    public function luotXem()
    {
        return $this->hasMany(LichSuXemBds::class, 'khach_hang_id');
    }

    public function thongBao()
    {
        return $this->morphMany(
            ThongBao::class,
            'doi_tuong_nhan',
            'doi_tuong_nhan',
            'doi_tuong_nhan_id'
        );
    }

    // ============================================================
    // SCOPES
    // ============================================================
    public function scopeNong($query)
    {
        return $query->where('muc_do_tiem_nang', 'nong');
    }
    public function scopeAm($query)
    {
        return $query->where('muc_do_tiem_nang', 'am');
    }
    public function scopeLanh($query)
    {
        return $query->where('muc_do_tiem_nang', 'lanh');
    }

    public function lichHens()
    {
        return $this->hasMany(LichHen::class, 'khach_hang_id');
    }
}
