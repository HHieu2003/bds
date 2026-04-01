<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class XoaPhienChatHetHan extends Command
{


    protected $signature   = 'chat:xoa-het-han';
    protected $description = 'Xóa phiên chat vãng lai đã hết hạn';

    public function handle()
    {
        $n = \App\Models\PhienChat::whereNotNull('het_han_at')
            ->where('het_han_at', '<', now())
            ->whereNull('khach_hang_id')
            ->delete();
        $this->info("Đã xóa {$n} phiên chat hết hạn.");
    }
}
