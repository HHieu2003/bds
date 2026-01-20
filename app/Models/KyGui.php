<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KyGui extends Model
{
    use HasFactory;

    protected $table = 'ky_gui';

    protected $fillable = [
        'ho_ten_chu_nha',
        'so_dien_thoai',
        'email',
        'loai_hinh', // chung_cu, nha_dat...
        'dia_chi',
        'dien_tich',
        'gia_mong_muon',
        'hinh_anh_tham_khao',
        'ghi_chu',
        'trang_thai', // cho_duyet, da_lien_he, da_nhan, tu_choi
        'phan_hoi_cua_admin'
    ];
}
