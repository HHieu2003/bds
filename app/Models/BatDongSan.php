<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BatDongSan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bat_dong_san';

    protected $fillable = [
        'du_an_id',
        'nhan_vien_phu_trach_id',
        'chu_nha_id',
        'tieu_de',
        'slug',
        'ma_bat_dong_san',
        'loai_hinh',
        'nhu_cau',
        'ma_can',
        'toa',
        'tang',
        'huong_cua',
        'huong_ban_cong',
        'dien_tich',
        'so_phong_ngu',
        'noi_that',
        'mo_ta',
        'ghi_chu_noi_bo',
        'hinh_anh',
        'album_anh',
        'album_video',
        'gia',
        'phi_moi_gioi',
        'phi_sang_ten',
        'phap_ly',
        'gia_thue',
        'thoi_gian_vao_thue',
        'hinh_thuc_thanh_toan',
        'noi_bat',
        'gui_mail_canh_bao_gia',
        'hien_thi',
        'trang_thai',
        'luot_xem',
        'thu_tu_hien_thi',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'thoi_diem_dang',
    ];

    protected $casts = [
        'album_anh'      => 'array',
        'album_video'    => 'array',
        'noi_bat'        => 'boolean',
        'gui_mail_canh_bao_gia' => 'boolean',
        'hien_thi'       => 'boolean',
        'dien_tich'      => 'decimal:2',
        'gia'            => 'decimal:2',
        'gia_thue'       => 'decimal:2',
        'phi_moi_gioi'   => 'decimal:2',
        'phi_sang_ten'   => 'decimal:2',
        'thoi_diem_dang' => 'datetime',
    ];

    public function getAlbumAnhAttribute($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (!is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        if (is_string($decoded) && trim($decoded) !== '') {
            $decodedNested = json_decode($decoded, true);
            if (is_array($decodedNested)) {
                return $decodedNested;
            }
        }

        return [];
    }

    // ── Relationships ──
    public function duAn()
    {
        return $this->belongsTo(DuAn::class, 'du_an_id');
    }

    public function nhanVienPhuTrach()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_phu_trach_id');
    }

    public function yeuThichs()
    {
        return $this->hasMany(YeuThich::class, 'bat_dong_san_id');
    }

    public function lichHens()
    {
        return $this->hasMany(LichHen::class, 'bat_dong_san_id');
    }

    public function canhBaoGias()
    {
        return $this->hasMany(DangKyNhanTin::class, 'bat_dong_san_id');
    }

    // ── Helpers ──
    public function getGiaHienThiAttribute(): string
    {
        if ($this->nhu_cau === 'ban' && $this->gia) {
            $ty = $this->gia / 1_000_000_000;
            return $ty >= 1
                ? number_format($ty, 2) . ' tỷ'
                : number_format($this->gia / 1_000_000, 0) . ' triệu';
        }
        if ($this->nhu_cau === 'thue' && $this->gia_thue) {
            return number_format($this->gia_thue / 1_000_000, 1) . ' tr/th';
        }
        return 'Thương lượng';
    }

    public function getHinhAnhUrlAttribute(): string
    {
        return $this->hinh_anh
            ? \Illuminate\Support\Facades\Storage::disk('r2')->url($this->hinh_anh)
            : asset('images/no-image.jpg');
    }

    public function getKhuVucAttribute()
    {
        return $this->duAn?->khuVuc;
    }
    public function khuVuc()
    {
        return $this->belongsTo(KhuVuc::class, 'khu_vuc_id', 'id');
    }
    public function chuNha()
    {
        return $this->belongsTo(ChuNha::class, 'chu_nha_id');
    }
}
