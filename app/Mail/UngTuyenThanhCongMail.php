<?php

namespace App\Mail;

use App\Models\DonUngTuyen;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UngTuyenThanhCongMail extends Mailable
{
    use SerializesModels;

    public $don;

    public function __construct(DonUngTuyen $don)
    {
        $this->don = $don;
    }

    public function build()
    {
        return $this->subject('Xác nhận ứng tuyển thành công - Thành Công Land')
                    ->view('frontend.emails.ung-tuyen-thanh-cong');
    }
}
