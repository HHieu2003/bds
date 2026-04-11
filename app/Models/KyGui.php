<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KyGui extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ky_gui';

    protected $fillable = [
        'khach_hang_id',
        'nhan_vien_phu_trach_id',
        'ho_ten_chu_nha',
        'so_dien_thoai',
        'email',
        'loai_hinh',
        'nhu_cau',
        'du_an',
        'ma_can',
        'dia_chi',
        'dien_tich',
        'huong_nha',
        'so_phong_ngu',
        'so_phong_tam',
        'noi_that',
        'gia_ban_mong_muon',
        'phap_ly',
        'gia_thue_mong_muon',
        'hinh_thuc_thanh_toan',
        'hinh_anh_tham_khao',
        'ghi_chu',
        'nguon_ky_gui',
        'trang_thai',
        'phan_hoi_cua_admin',
        'thoi_diem_xu_ly',
    ];

    protected $casts = [
        'hinh_anh_tham_khao' => 'array',
        'thoi_diem_xu_ly'    => 'datetime',
        'dien_tich'          => 'decimal:2',
        'gia_ban_mong_muon'  => 'decimal:2',
        'gia_thue_mong_muon' => 'decimal:2',
    ];

    // ── Constants ──
    const LOAI_HINH = [
        'can_ho'    => ['label' => 'Căn hộ chung cư', 'icon' => 'fas fa-building',       'color' => '#2d6a9f'],
        'nha_pho'   => ['label' => 'Nhà phố',         'icon' => 'fas fa-home',            'color' => '#e67e22'],
        'biet_thu'  => ['label' => 'Biệt thự',        'icon' => 'fas fa-landmark',        'color' => '#9b59b6'],
        'dat_nen'   => ['label' => 'Đất nền',         'icon' => 'fas fa-map',             'color' => '#27ae60'],
        'shophouse' => ['label' => 'Shophouse',       'icon' => 'fas fa-store',           'color' => '#e74c3c'],
    ];

    const NHU_CAU = [
        'ban'  => ['label' => 'Bán',   'color' => '#e74c3c', 'bg' => '#fff0f0'],
        'thue' => ['label' => 'Thuê',  'color' => '#27ae60', 'bg' => '#e8f8f0'],
    ];

    // ĐÃ CẬP NHẬT THEO ĐÚNG NGHIỆP VỤ NGUỒN HÀNG
    const TRANG_THAI = [
        'cho_duyet'      => ['label' => 'Mới gửi',       'color' => '#e67e22', 'bg' => '#fff8f0', 'icon' => 'fas fa-inbox'],
        'dang_tham_dinh' => ['label' => 'Đang thẩm định', 'color' => '#2d6a9f', 'bg' => '#e8f4fd', 'icon' => 'fas fa-search'],
        'da_duyet'       => ['label' => 'Đã duyệt',      'color' => '#27ae60', 'bg' => '#e8f8f0', 'icon' => 'fas fa-check-circle'],
        'tu_choi'        => ['label' => 'Từ chối',       'color' => '#e74c3c', 'bg' => '#fff0f0', 'icon' => 'fas fa-times-circle'],
    ];

    const PHAP_LY = [
        'so_hong'     => 'Sổ hồng',
        'hop_dong'    => 'Hợp đồng mua bán',
        'chua_co'     => 'Chưa có sổ',
    ];

    const HINH_THUC_THANH_TOAN = [
        'thuong_luong'       => 'Thương lượng',
        '3_coc_1'       => '3 tháng cọc 1 tháng',
        '6_coc_1'       => '6 tháng cọc 1 tháng',
        'theo_quy'      => 'Thanh toán theo quý',
        'theo_nam'      => 'Thanh toán theo năm',
    ];

    const HUONG_NHA = ['dong', 'tay', 'nam', 'bac', 'dong_nam', 'dong_bac', 'tay_nam', 'tay_bac'];

    const NOI_THAT = [
        'nguyen_ban' => 'Nguyên bản',
        'co_ban'     => 'Cơ bản',
        'full'       => 'Full nội thất',
        'cao_cap'    => 'Cao cấp',
    ];

    // ── Relationships ──
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }
    public function nhanVienPhuTrach()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_phu_trach_id');
    }

    // ── Accessors ──
    public function getTrangThaiInfoAttribute(): array
    {
        return self::TRANG_THAI[$this->trang_thai] ?? ['label' => $this->trang_thai, 'color' => '#999', 'bg' => '#f5f5f5', 'icon' => 'fas fa-question'];
    }

    public function getLoaiHinhInfoAttribute(): array
    {
        return self::LOAI_HINH[$this->loai_hinh] ?? ['label' => $this->loai_hinh, 'icon' => 'fas fa-home', 'color' => '#999'];
    }

    public function getGiaHienThiAttribute(): string
    {
        if ($this->nhu_cau === 'ban' && $this->gia_ban_mong_muon) return number_format($this->gia_ban_mong_muon / 1_000_000_000, 2) . ' tỷ';
        if ($this->nhu_cau === 'thue' && $this->gia_thue_mong_muon) return number_format($this->gia_thue_mong_muon / 1_000_000) . ' tr';
        return 'Thỏa thuận';
    }
}
