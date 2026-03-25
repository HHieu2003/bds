<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public string $otp,
        public string $ten
    ) {}

    public function build()
    {
        return $this->subject("[{$this->otp}] Mã xác thực tài khoản Thành Công Land")
                    ->view('frontend.emails.verify-email');
    }
}
