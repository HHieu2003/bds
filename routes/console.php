<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\ChatSession;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// --- TÁC VỤ TỰ ĐỘNG ---
// Xóa các phiên chat chưa xác minh quá 24h
Schedule::call(function () {
    $deleted = ChatSession::where('is_verified', false)
        ->where('expires_at', '<', now())
        ->delete();

    if ($deleted > 0) {
        \Illuminate\Support\Facades\Log::info("Đã dọn dẹp {$deleted} phiên chat rác chưa xác minh.");
    }
})->daily(); 
// Bạn có thể đổi ->daily() thành ->hourly() nếu muốn quét thường xuyên hơn