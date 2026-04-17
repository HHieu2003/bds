<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuotTruyCap extends Model
{
    public $timestamps = false;

    protected $table = 'luot_truy_cap';

    protected $fillable = [
        'session_id',
        'ip_address',
        'url',
        'trang',
        'bat_dong_san_id',
        'du_an_id',
        'khu_vuc_id',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
