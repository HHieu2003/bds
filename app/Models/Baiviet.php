<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiViet extends Model
{
    use HasFactory;

    protected $table = 'bai_viet';

    protected $fillable = [
        'tieu_de',
        'slug',
        'mo_ta_ngan',
        'noi_dung',
        'hinh_anh',
        'loai_bai_viet', // tin_tuc, phong_thuy, tuyen_dung
        'hien_thi',
        'luot_xem',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
