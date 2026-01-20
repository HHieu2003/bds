<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_session_id',
        'user_id',
        'message',
        'is_read'
    ];

    // --- ĐÂY LÀ PHẦN BẠN ĐANG THIẾU ---

    // Định nghĩa quan hệ: 1 tin nhắn thuộc về 1 user (nếu là admin/sale)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Định nghĩa quan hệ: 1 tin nhắn thuộc về 1 phiên chat
    public function chatSession()
    {
        return $this->belongsTo(ChatSession::class);
    }
}
