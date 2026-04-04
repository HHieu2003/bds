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
Neu khach co nhu cau sau: gap nhan vien, dat lich xem, dat coc, ky hop dong, phap ly:
- KHONG chuyen ngay, hay hoi mot cau: Ban co muon toi ket noi ban voi nhan vien tu van khong?
- Neu khach xac nhan dong y → them [TRANSFER_TO_AGENT] vao cuoi phan hoi
- Neu khach chua ro hoac tu choi → tiep tuc tu van
Chi them [TRANSFER_TO_AGENT] khi khach XAC NHAN ro rang muon gap nhan vien.";
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
        $maxRetries = 3;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $history  = $this->buildHistory($phienChat);
                $prompt   = $this->buildSystemPrompt($phienChat);

                $chat = Gemini::generativeModel(model: 'gemini-2.5-flash')
                    ->withSystemInstruction(Content::parse($prompt))
                    ->withGenerationConfig($this->buildGenerationConfig())
                    ->startChat(history: $history);

                $response  = $chat->sendMessage(trim($message));
                $text      = $response->text();

                // ✅ Fix: chỉ cần AI trả [TRANSFER_TO_AGENT] là đủ
                $transfer  = str_contains($text, '[TRANSFER_TO_AGENT]');
                $cleanText = trim(str_replace('[TRANSFER_TO_AGENT]', '', $text));

                return ['reply' => $cleanText, 'can_chuyen_nv' => $transfer, 'cau_hoi_goi_y' => $this->getCauHoiGoiY($phienChat),];
            } catch (\Exception $e) {
                $rawMessage  = (string) $e->getMessage();
                $safeMessage = preg_replace('/AIza[0-9A-Za-z_-]{20,}/', '[REDACTED_API_KEY]', $rawMessage) ?? $rawMessage;
                $lower       = strtolower($rawMessage);

                \Log::error("Gemini error (attempt {$attempt}/{$maxRetries}): " . $safeMessage);

                // ✅ Lỗi cURL (mất mạng tạm thời): retry sau 1 giây, không cần báo user
                if (str_contains($lower, 'curl error') && $attempt < $maxRetries) {
                    sleep(1);
                    continue;
                }

                // Các lỗi không thể retry → dừng ngay
                $reply = $this->buildErrorReply($lower);

                return [
                    'reply'         => $reply,
                    'can_chuyen_nv' => false,
                ];
            }
        }

        // Hết số lần retry (chỉ xảy ra khi cURL lỗi liên tục)
        return [
            'reply'         => 'Kết nối tới AI đang không ổn định. Bạn thử lại sau ít phút hoặc chuyển sang nhân viên tư vấn nhé.',
            'can_chuyen_nv' => false,
        ];
    }
    public function getCauHoiGoiY(PhienChat $phienChat): array
    {
        // Nếu không có câu hỏi → trả fallback
        $cauHoi = $this->buildCauHoiFromContext($phienChat);
        if (!empty($cauHoi)) {
            return $cauHoi;
        }

        return $this->getDefaultQuestions();
    }

    /**
     * Xây dựng câu hỏi gợi ý theo ngữ cảnh (BĐS / Dự án / Khu vực)
     */
    private function buildCauHoiFromContext(PhienChat $phienChat): array
    {
        if ($phienChat->loai_ngu_canh === 'bat_dong_san' && $phienChat->ngu_canh_id) {
            $bds = \App\Models\BatDongSan::find($phienChat->ngu_canh_id);
            if ($bds) {
                return [
                    "Giá {$bds->tieu_de} có thương lượng không?",
                    "Pháp lý của căn này thế nào?",
                    "Tôi muốn đặt lịch xem căn này",
                    "Có căn tương tự trong khu vực không?",
                ];
            }
        }

        if ($phienChat->loai_ngu_canh === 'du_an' && $phienChat->ngu_canh_id) {
            $da = \App\Models\DuAn::find($phienChat->ngu_canh_id);
            if ($da) {
                return [
                    "Dự án {$da->ten_du_an} còn căn nào không?",
                    "Tiến độ dự án đến đâu rồi?",
                    "Chính sách thanh toán như thế nào?",
                    "Cho tôi gặp nhân viên tư vấn dự án",
                ];
            }
        }

        if ($phienChat->loai_ngu_canh === 'khu_vuc' && $phienChat->ngu_canh_id) {
            $kv = \App\Models\KhuVuc::find($phienChat->ngu_canh_id);
            if ($kv) {
                return [
                    "Các BĐS nổi bật ở {$kv->ten_khu_vuc} là gì?",
                    "Giá trung bình ở {$kv->ten_khu_vuc} hiện nay?",
                    "Tiện ích xung quanh khu vực này?",
                    "Xem các dự án ở {$kv->ten_khu_vuc}",
                ];
            }
        }

        return [];
    }

    /**
     * Câu hỏi gợi ý mặc định (fallback khi lỗi hoặc không có context)
     */
    private function getDefaultQuestions(): array
    {
        // Lấy 2 BĐS mới nhất từ DB
        $bdsMoi = \App\Models\BatDongSan::where('trang_thai', 'con_hang')
            ->where('hien_thi', 1)
            ->latest()
            ->limit(2)
            ->pluck('tieu_de')
            ->toArray();

        $questions = [
            'Có những BĐS nào đang cho thuê gần đây?',
            'Mức giá chuẩn hiện tại là bao nhiêu?',
            'Cách thanh toán & ký hợp đồng như thế nào?',
        ];

        // Thêm BĐS mới vào cùng với câu hỏi
        foreach ($bdsMoi as $ten) {
            $questions[] = "Tư vấn về: {$ten}";
        }

        return array_slice($questions, 0, 4);
    }

    /**
     * Câu hỏi fallback khi Gemini bị lỗi hoàn toàn
     */
    public function getFallbackQuestions(): array
    {
        return [
            '❓ Các dịch vụ của chúng tôi là gì?',
            '💰 Tôi có đủ kinh phí mua/thuê không?',
            '📍 Nên chọn khu vực nào?',
            '👨‍💼 Tôi muốn nói chuyện với nhân viên',
        ];
    }
    private function buildErrorReply(string $lowerMessage): string
    {
        if (str_contains($lowerMessage, 'has been suspended')) {
            return 'Xin lỗi, kênh AI đang tạm khóa từ phía nhà cung cấp. Bạn có muốn chuyển sang nhân viên tư vấn ngay không?';
        }

        if (str_contains($lowerMessage, 'quota')) {
            return 'Xin lỗi, AI đang vượt giới hạn sử dụng (quota). Bạn thử lại sau ít phút hoặc chuyển sang nhân viên tư vấn nhé.';
        }

        if (str_contains($lowerMessage, 'curl error')) {
            return 'Kết nối tới AI đang không ổn định. Bạn thử lại sau ít phút hoặc chuyển sang nhân viên tư vấn nhé.';
        }

        return 'Xin lỗi, hệ thống AI tạm thời bận. Bạn có muốn kết nối với nhân viên tư vấn không?';
    }
}
