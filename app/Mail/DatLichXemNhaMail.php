<?php

namespace App\Mail;

use App\Models\LichHen;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DatLichXemNhaMail extends Mailable
{
    use SerializesModels;

    public function __construct(public LichHen $lichHen) {}

    public function build()
    {
        $thoiGian = $this->lichHen->thoi_gian_hen
            ? \Carbon\Carbon::parse($this->lichHen->thoi_gian_hen)->format('H:i \n\g\à\y d/m/Y')
            : 'Đang cập nhật';

        return $this
            ->subject("✅ Xác nhận đặt lịch xem nhà – Thành Công Land")
            ->view('frontend.emails.dat-lich-xem-nha')
            ->with(['thoiGian' => $thoiGian]);
    }
}
