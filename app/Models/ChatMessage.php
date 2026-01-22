<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_session_id',
        'user_id', // Null = Khách hàng, Có ID = Admin/Sale
        'message',
        'is_read'
    ];

    public function chatSession()
    {
        return $this->belongsTo(ChatSession::class);
    }

    // Lấy thông tin Admin trả lời (nếu có)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
