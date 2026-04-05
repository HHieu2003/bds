<?php

namespace App\Mail;

use App\Models\BatDongSan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ThongBaoBatDongSanMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $bds;
    public $loaiThongBao; // 'GiamGia' hoặc 'BdsMoi'
    public $mucGiaCu;

    public function __construct(BatDongSan $bds, $loaiThongBao, $mucGiaCu = null)
    {
        $this->bds = $bds;
        $this->loaiThongBao = $loaiThongBao;
        $this->mucGiaCu = $mucGiaCu;
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->loaiThongBao) {
            'GiamGia' => '🔥 Giảm giá Bất động sản bạn đang quan tâm!',
            'CapNhatGia' => '📢 Giá Bất động sản bạn theo dõi vừa thay đổi',
            default => '✨ Có Bất động sản mới phù hợp với bạn!',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'frontend.emails.thong-bao-bds',
        );
    }
}
