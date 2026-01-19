<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    protected $fillable = ['phone', 'is_read', 'du_an_id', 'bat_dong_san_id'];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    // Liên kết Dự án
    public function duAn()
    {
        return $this->belongsTo(DuAn::class, 'du_an_id');
    }

    // Liên kết Bất động sản
    public function batDongSan()
    {
        return $this->belongsTo(BatDongSan::class, 'bat_dong_san_id');
    }
}
