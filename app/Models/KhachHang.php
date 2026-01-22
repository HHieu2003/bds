<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @mixin IdeHelperKhachHang
 */
class KhachHang extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'khach_hang';

    protected $fillable = [
        'ho_ten',
        'so_dien_thoai',
        'email',
        'password',
        'is_active',
        'phone_verified_at',
        'email_verified_at'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'phone_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function getContactInfo()
    {
        return $this->so_dien_thoai ?? $this->email;
    }

    public function isVerified()
    {
        return !is_null($this->phone_verified_at) || !is_null($this->email_verified_at);
    }
}
