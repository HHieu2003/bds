<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public string $resetLink,
        public string $otp,
        public string $ten
    ) {}

    public function build()
    {
        return $this->subject("[{$this->otp}] Đặt lại mật khẩu - Thành Công Land")
                    ->view('frontend.emails.reset-password');
    }
}
