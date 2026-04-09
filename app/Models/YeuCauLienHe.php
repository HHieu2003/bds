<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YeuCauLienHe extends Model
{
    use SoftDeletes;

    protected $table = 'yeu_cau_lien_he';

    protected $fillable = [
        'khach_hang_id',
        'bat_dong_san_id',
        'nhan_vien_phu_trach_id',
        'ho_ten',
        'so_dien_thoai',
        'email',
        'noi_dung',
        'nguon_lien_he',
        'muc_do_quan_tam',
        'trang_thai',
        'ghi_chu_admin',
        'thoi_diem_lien_he',
    ];

    protected $casts = [
        'thoi_diem_lien_he' => 'datetime',
    ];

    // ── Constants ──
    const TRANG_THAI = [
        'moi'        => ['label' => 'Mới',        'color' => '#3498db', 'bg' => '#eaf4fd', 'icon' => 'fas fa-bell'],
        'da_lien_he' => ['label' => 'Đã liên hệ', 'color' => '#f39c12', 'bg' => '#fef9e7', 'icon' => 'fas fa-phone'],
        'dang_tu_van' => ['label' => 'Đang tư vấn', 'color' => '#9b59b6', 'bg' => '#f5eeff', 'icon' => 'fas fa-comments'],
        'da_chot'    => ['label' => 'Đã chốt',    'color' => '#27ae60', 'bg' => '#e8f8f0', 'icon' => 'fas fa-check-circle'],
        'dong'       => ['label' => 'Đóng',        'color' => '#95a5a6', 'bg' => '#f5f5f5', 'icon' => 'fas fa-times-circle'],
    ];

    const MUC_DO = [
        'rat_quan_tam' => ['label' => 'Rất quan tâm', 'color' => '#e74c3c'],
        'quan_tam'     => ['label' => 'Quan tâm',     'color' => '#f39c12'],
        'tim_hieu'     => ['label' => 'Tìm hiểu',     'color' => '#3498db'],
        'chua_ro'      => ['label' => 'Chưa rõ',      'color' => '#95a5a6'],
    ];

    const NGUON = [
        'website'  => 'Website',
        'hotline'  => 'Hotline',
        'chat'     => 'Chat',
        'form_bds' => 'Form BĐS',
        'zalo'     => 'Zalo',
    ];

    // ── Relationships ──
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }

    public function batDongSan()
    {
        return $this->belongsTo(BatDongSan::class, 'bat_dong_san_id');
    }

    public function nhanVienPhuTrach()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_phu_trach_id');
    }

    // ── Accessors ──
    public function getTrangThaiInfoAttribute(): array
    {
        return self::TRANG_THAI[$this->trang_thai]
            ?? ['label' => '?', 'color' => '#999', 'bg' => '#f5f5f5', 'icon' => 'fas fa-question'];
    }

    public function getMucDoInfoAttribute(): ?array
    {
        return $this->muc_do_quan_tam
            ? (self::MUC_DO[$this->muc_do_quan_tam] ?? null)
            : null;
    }

    public function getMaYeuCauAttribute(): string
    {
        return 'LH-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Yêu cầu này liên quan đến Dự án nào?
     */
    public function duAn()
    {
        return $this->belongsTo(DuAn::class, 'du_an_id');
    }

    /**
     * Nhân viên (Sale) nào đang nhận xử lý Yêu cầu này?
     */
}
