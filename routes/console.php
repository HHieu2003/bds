<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Xoa phien chat vang lai het han (khong dang nhap/xac thuc)
Schedule::command('chat:xoa-het-han')->hourly();
