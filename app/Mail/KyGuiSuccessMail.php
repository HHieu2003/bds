<?php

namespace App\Mail;

use App\Models\KyGui;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KyGuiSuccessMail extends Mailable
{
    use SerializesModels;

    public $kyGui;

    public function __construct(KyGui $kyGui)
    {
        $this->kyGui = $kyGui;
    }

    public function build()
    {
        return $this->subject('Xác nhận đăng ký ký gửi bất động sản thành công - Thành Công Land')
                    ->view('frontend.emails.ky-gui-success');
    }
}
