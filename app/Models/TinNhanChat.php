<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinNhanChat extends Model
{
    use HasFactory;

    protected $table = 'tin_nhan_chat';
    protected $guarded = [];

    // Tin nhắn thuộc về 1 Phiên chat
    public function phienChat()
    {
        return $this->belongsTo(PhienChat::class, 'phien_chat_id');
    }
}
