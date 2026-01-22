<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'khach_hang_id',
        'session_id',
        'user_name',
        'user_phone',
        'is_verified',
        'context_url'
    ];

    // Quan hệ: 1 Session có nhiều tin nhắn
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    // Quan hệ: Session thuộc về 1 khách hàng
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class);
    }
}
