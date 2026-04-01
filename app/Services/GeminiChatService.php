<?php

namespace App\Services;

use App\Models\PhienChat;
use App\Models\TinNhanChat;
use App\Models\BatDongSan;
use App\Models\DuAn;
use App\Models\KhuVuc;
use Gemini\Data\Content;
use Gemini\Data\GenerationConfig;
use Gemini\Enums\Role;
use Gemini\Laravel\Facades\Gemini;

class GeminiChatService
{
    private function buildSystemPrompt(PhienChat $phienChat): string
    {
        $ctx = '';
        $tenKhach = $phienChat->khachHang?->ho_ten
            ?: $phienChat->ten_khach_vang_lai
            ?: 'Khach hang';

        $sdtKhach = $phienChat->khachHang?->so_dien_thoai
            ?: $phienChat->sdt_khach_vang_lai
            ?: 'Chua cung cap';

        $emailKhach = $phienChat->khachHang?->email
            ?: $phienChat->email_khach_vang_lai
            ?: 'Chua cung cap';

        if ($phienChat->ngu_canh_id) {
            match ($phienChat->loai_ngu_canh) {
                'bat_dong_san' => (function () use (&$ctx, $phienChat) {
                    $bds = BatDongSan::find($phienChat->ngu_canh_id);
                    if ($bds) {
                        $gia = $bds->nhu_cau === 'thue'
                            ? ($bds->gia_thue ? number_format($bds->gia_thue) . ' VNĐ/tháng' : 'Thương lượng')
                            : ($bds->gia ? number_format($bds->gia) . ' VNĐ' : 'Thương lượng');

                        $ctx = "Khách đang xem căn hộ/BĐS: {$bds->tieu_de}. "
                            . "Loại hình: {$bds->loai_hinh}. Nhu cầu: {$bds->nhu_cau}. "
                            . "Giá: {$gia}. Diện tích: {$bds->dien_tich}m2. "
                            . "Pháp lý: {$bds->phap_ly}. Mô tả: {$bds->mo_ta}";
                    }
                })(),
                'du_an' => (function () use (&$ctx, $phienChat) {
                    $da = DuAn::find($phienChat->ngu_canh_id);
                    if ($da) {
                        $ctx = "Khách đang xem dự án: {$da->ten_du_an}. "
                            . "Địa chỉ: {$da->dia_chi}. Chủ đầu tư: {$da->chu_dau_tu}. "
                            . "Mô tả ngắn: {$da->mo_ta_ngan}";
                    }
                })(),
                'khu_vuc' => (function () use (&$ctx, $phienChat) {
                    $kv = KhuVuc::find($phienChat->ngu_canh_id);
                    if ($kv) {
                        $ctx = "Khách đang xem khu vực: {$kv->ten_khu_vuc}. "
                            . "Mô tả khu vực: {$kv->mo_ta}";
                    }
                })(),
                default => null,
            };
        }

        return "Bạn là trợ lý tư vấn BĐS của Thành Công Land, chuyên nghiệp, hiểu ngữ cảnh, phản hồi tự nhiên.

MUC TIEU CHAT:
- Tra loi bang tieng Viet, ro rang, de hieu, co cau truc.
- Uu tien giai quyet nhu cau mua/thue BDS cua khach.
- Khong boc me, khong suy doan khi thieu thong tin. Neu chua du du lieu, hoi toi da 1-2 cau bo sung.
- Nho mach hoi thoai: tham chieu cac thong tin khach da noi o nhung tin nhan truoc.

THONG TIN KHACH:
- Ten: {$tenKhach}
- SDT: {$sdtKhach}
- Email: {$emailKhach}

NGU CANH HIEN TAI:
{$ctx}

PHONG CACH TRA LOI:
- Neu cau hoi don gian: tra loi gon 2-4 cau.
- Neu can so sanh/tu van: dung danh sach ngan gon theo tung y chinh.
- Co dua ra goi y hanh dong tiep theo (vd: can ngan sach, khu vuc, so phong, lich xem).

QUY TAC CHUYEN NHAN VIEN:
Chi them [TRANSFER_TO_AGENT] khi KHACH THE HIEN RO rang mot trong cac nhu cau sau:
- Muon gap nhan vien/tu van vien truc tiep
- Muon dat lich xem nha
- Muon thuong luong dat coc/mua ban/hop dong
- Hoac yeu cau xu ly phap ly chuyen sau
Neu chi la cau hoi thong tin thong thuong thi KHONG them [TRANSFER_TO_AGENT].";
    }

    private function buildGenerationConfig(): GenerationConfig
    {
        return new GenerationConfig(
            candidateCount: 1,
            maxOutputTokens: 900,
            temperature: 0.45,
            topP: 0.9,
            topK: 32,
        );
    }

    private function buildHistory(PhienChat $phienChat): array
    {
        return TinNhanChat::where('phien_chat_id', $phienChat->id)
            ->whereIn('nguoi_gui', ['khach_hang', 'bot'])
            ->latest('id')
            ->limit(40)
            ->get()
            ->reverse()
            ->values()
            ->map(fn($t) => Content::parse(
                part: mb_substr(trim((string) ($t->noi_dung ?? '')), 0, 1500),
                role: $t->nguoi_gui === 'khach_hang' ? Role::USER : Role::MODEL,
            ))
            ->filter(fn(Content $content) => !empty($content->parts[0]->text))
            ->values()
            ->all();
    }

    public function chat(PhienChat $phienChat, string $message): array
    {
        try {
            $history  = $this->buildHistory($phienChat);
            $prompt   = $this->buildSystemPrompt($phienChat);
            $chat = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(Content::parse($prompt))
                ->withGenerationConfig($this->buildGenerationConfig())
                ->startChat(history: $history);
            $response = $chat->sendMessage(trim($message));
            $text     = $response->text();

            $transfer  = $this->shouldTransferToAgent($message, $text);
            $cleanText = trim(str_replace('[TRANSFER_TO_AGENT]', '', $text));

            return ['reply' => $cleanText, 'can_chuyen_nv' => $transfer];
        } catch (\Exception $e) {
            $rawMessage = (string) $e->getMessage();
            $safeMessage = preg_replace('/AIza[0-9A-Za-z_-]{20,}/', '[REDACTED_API_KEY]', $rawMessage) ?? $rawMessage;
            \Log::error('Gemini error: ' . $safeMessage);

            $reply = 'Xin lỗi, hệ thống AI tạm thời bận. Bạn có muốn kết nối với nhân viên tư vấn không?';

            if (str_contains(strtolower($rawMessage), 'has been suspended')) {
                $reply = 'Xin lỗi, kênh AI đang tạm khóa từ phía nhà cung cấp. Bạn có muốn chuyển sang nhân viên tư vấn ngay không?';
            } elseif (str_contains(strtolower($rawMessage), 'quota')) {
                $reply = 'Xin lỗi, AI đang vuot gioi han su dung (quota). Ban thu lai sau it phut hoac chuyen sang nhan vien tu van nhe.';
            } elseif (str_contains(strtolower($rawMessage), 'cURL error')) {
                $reply = 'Kết nối tới AI đang không ổn định. Bạn thử lại sau ít phút hoặc chuyển sang nhân viên tư vấn nhé.';
            }

            return [
                'reply'         => $reply,
                'can_chuyen_nv' => false,
            ];
        }
    }

    private function shouldTransferToAgent(string $userMessage, string $aiText): bool
    {
        if (!str_contains($aiText, '[TRANSFER_TO_AGENT]')) {
            return false;
        }

        $msg = mb_strtolower(trim($userMessage));
        if ($msg === '') {
            return false;
        }

        $explicitTransferKeywords = [
            'nhan vien',
            'tu van vien',
            'goi toi',
            'lien he toi',
            'so dien thoai',
            'dat lich',
            'xem nha',
            'xem can',
            'dat coc',
            'ky hop dong',
            'thuong luong',
            'phap ly',
            'so hong',
            'so do',
        ];

        foreach ($explicitTransferKeywords as $keyword) {
            if (str_contains($msg, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
