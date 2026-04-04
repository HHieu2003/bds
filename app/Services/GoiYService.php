<?php

namespace App\Services;

use App\Models\LichSuXemBds;
use App\Models\LichSuTimKiem;
use App\Models\YeuThich;
use App\Models\BatDongSan;
use App\Models\PhienChat;
use Illuminate\Support\Collection;

class GoiYService
{
    // ── Trọng số từng hành vi ──────────────────────────────────
    const W_XEM_MOT_LAN    = 1;
    const W_XEM_LAU        = 2;
    const W_YEU_THICH      = 5;
    const W_TIM_KIEM_LOAI  = 3;
    const W_TIM_KIEM_KHU   = 2;
    const W_TIM_KIEM_DU_AN = 4;
    const W_CHAT_VE_BDS    = 4;

    /**
     * Xây dựng "profile điểm" sở thích của người dùng có áp dụng Hao mòn thời gian
     */
    public function xayDungProfile(?int $khachHangId, string $sessionId): array
    {
        $loaiHinhScore = [];
        $khuVucScore   = [];
        $duAnScore     = [];
        $nhuCauScore   = [];
        $bdsChatScore  = [];
        $giaList       = [];

        $now = now();

        // ── 1. Từ lịch sử xem BĐS (Áp dụng Time Decay 60 ngày) ───
        LichSuXemBds::where(function ($q) use ($khachHangId, $sessionId) {
            if ($khachHangId) $q->where('khach_hang_id', $khachHangId);
            else              $q->where('session_id', $sessionId);
        })
            ->where('created_at', '>=', $now->copy()->subDays(60))
            ->get()
            ->each(function ($row) use (&$loaiHinhScore, &$khuVucScore, &$duAnScore, &$nhuCauScore, &$giaList, $now) {
                // Tính hệ số hao mòn: Mới xem = 100% điểm, xem 60 ngày trước = gần 0% điểm
                $daysAgo = max(0, $row->created_at->diffInDays($now));
                $heSoThoiGian = 1 - ($daysAgo / 60);

                $diem = self::W_XEM_MOT_LAN;
                if ($row->thoi_gian_xem > 60) $diem += self::W_XEM_LAU;

                $diemThucTe = $diem * $heSoThoiGian; // Áp dụng hao mòn

                if ($row->loai_hinh)  $loaiHinhScore[$row->loai_hinh] = ($loaiHinhScore[$row->loai_hinh] ?? 0) + $diemThucTe;
                if ($row->khu_vuc_id) $khuVucScore[$row->khu_vuc_id] = ($khuVucScore[$row->khu_vuc_id] ?? 0) + $diemThucTe;
                if ($row->du_an_id)   $duAnScore[$row->du_an_id] = ($duAnScore[$row->du_an_id] ?? 0) + $diemThucTe;
                if ($row->nhu_cau)    $nhuCauScore[$row->nhu_cau] = ($nhuCauScore[$row->nhu_cau] ?? 0) + $diemThucTe;

                if ($row->gia_tu && $row->gia_tu > 0) {
                    $giaList[] = ($row->gia_tu + $row->gia_den) / 2;
                }
            });

        // ── 2. Từ danh sách yêu thích (Không hao mòn vì đây là chủ ý của KH) ───
        if ($khachHangId) {
            YeuThich::where('khach_hang_id', $khachHangId)
                ->with('batDongSan.duAn')
                ->get()
                ->each(function ($yt) use (&$loaiHinhScore, &$khuVucScore, &$duAnScore, &$nhuCauScore, &$giaList) {
                    $bds = $yt->batDongSan;
                    if (!$bds) return;

                    if ($bds->loai_hinh) $loaiHinhScore[$bds->loai_hinh] = ($loaiHinhScore[$bds->loai_hinh] ?? 0) + self::W_YEU_THICH;

                    $khuVuc = $bds->duAn?->khu_vuc_id;
                    if ($khuVuc) $khuVucScore[$khuVuc] = ($khuVucScore[$khuVuc] ?? 0) + self::W_YEU_THICH;

                    if ($bds->du_an_id) $duAnScore[$bds->du_an_id] = ($duAnScore[$bds->du_an_id] ?? 0) + self::W_YEU_THICH;
                    if ($bds->nhu_cau)  $nhuCauScore[$bds->nhu_cau] = ($nhuCauScore[$bds->nhu_cau] ?? 0) + self::W_YEU_THICH;

                    $gia = $bds->nhu_cau === 'ban' ? $bds->gia : $bds->gia_thue;
                    if ($gia && $gia > 0) $giaList[] = $gia;
                });
        }

        // ── 3. Từ lịch sử tìm kiếm (Áp dụng Time Decay 30 ngày) ───
        LichSuTimKiem::where(function ($q) use ($khachHangId, $sessionId) {
            if ($khachHangId) $q->where('khach_hang_id', $khachHangId);
            else              $q->where('session_id', $sessionId);
        })
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->get()
            ->each(function ($row) use (&$loaiHinhScore, &$khuVucScore, &$duAnScore, &$nhuCauScore, &$giaList, $now) {
                $daysAgo = max(0, $row->created_at->diffInDays($now));
                $heSoThoiGian = 1 - ($daysAgo / 30);

                $boLoc = $row->bo_loc ?? [];

                if (!empty($boLoc['loai_hinh']))  $loaiHinhScore[$boLoc['loai_hinh']] = ($loaiHinhScore[$boLoc['loai_hinh']] ?? 0) + (self::W_TIM_KIEM_LOAI * $heSoThoiGian);
                if (!empty($boLoc['khu_vuc_id'])) $khuVucScore[$boLoc['khu_vuc_id']] = ($khuVucScore[$boLoc['khu_vuc_id']] ?? 0) + (self::W_TIM_KIEM_KHU * $heSoThoiGian);
                if (!empty($boLoc['du_an_id']))   $duAnScore[$boLoc['du_an_id']] = ($duAnScore[$boLoc['du_an_id']] ?? 0) + (self::W_TIM_KIEM_DU_AN * $heSoThoiGian);
                if (!empty($boLoc['nhu_cau']))    $nhuCauScore[$boLoc['nhu_cau']] = ($nhuCauScore[$boLoc['nhu_cau']] ?? 0) + (self::W_TIM_KIEM_LOAI * $heSoThoiGian);

                if (!empty($boLoc['gia_tu']) && $boLoc['gia_tu'] > 0) $giaList[] = $boLoc['gia_tu'];
                if (!empty($boLoc['gia_den']) && $boLoc['gia_den'] > 0) $giaList[] = $boLoc['gia_den'];
            });

        // ── 4. Từ ngữ cảnh chat về BĐS (Time Decay 60 ngày) ───
        $phienChats = PhienChat::query()
            ->where(function ($q) use ($khachHangId, $sessionId) {
                if ($khachHangId) $q->where('khach_hang_id', $khachHangId);
                else              $q->where('session_id', $sessionId);
            })
            ->where('loai_ngu_canh', 'bat_dong_san')
            ->whereNotNull('ngu_canh_id')
            ->where('created_at', '>=', $now->copy()->subDays(60))
            ->get();

        if ($phienChats->isNotEmpty()) {
            $chatBdsMap = BatDongSan::with('duAn')
                ->whereIn('id', $phienChats->pluck('ngu_canh_id')->unique())
                ->get()
                ->keyBy('id');

            foreach ($phienChats as $chat) {
                $bds = $chatBdsMap->get((int) $chat->ngu_canh_id);
                if (!$bds) continue;

                $daysAgo = max(0, $chat->created_at->diffInDays($now));
                $heSoThoiGian = 1 - ($daysAgo / 60);

                $diemChat = self::W_CHAT_VE_BDS * $heSoThoiGian;
                $bdsChatScore[$bds->id] = ($bdsChatScore[$bds->id] ?? 0) + $diemChat;

                if ($bds->loai_hinh) $loaiHinhScore[$bds->loai_hinh] = ($loaiHinhScore[$bds->loai_hinh] ?? 0) + $diemChat;

                $khuVucId = $bds->duAn?->khu_vuc_id;
                if ($khuVucId) $khuVucScore[$khuVucId] = ($khuVucScore[$khuVucId] ?? 0) + $diemChat;
                if ($bds->du_an_id) $duAnScore[$bds->du_an_id] = ($duAnScore[$bds->du_an_id] ?? 0) + $diemChat;
                if ($bds->nhu_cau)  $nhuCauScore[$bds->nhu_cau] = ($nhuCauScore[$bds->nhu_cau] ?? 0) + $diemChat;

                $gia = $bds->nhu_cau === 'ban' ? $bds->gia : $bds->gia_thue;
                if ($gia && $gia > 0) $giaList[] = $gia;
            }
        }

        arsort($loaiHinhScore);
        arsort($khuVucScore);
        arsort($duAnScore);
        arsort($nhuCauScore);
        arsort($bdsChatScore);

        $giaTB  = !empty($giaList) ? array_sum($giaList) / count($giaList) : null;

        return [
            'loai_hinh_scores' => $loaiHinhScore,
            'khu_vuc_scores'   => $khuVucScore,
            'du_an_scores'     => $duAnScore,
            'nhu_cau_scores'   => $nhuCauScore,
            'bds_chat_scores'  => $bdsChatScore,
            'gia_trung_binh'   => $giaTB,
            'loai_hinh_top'    => array_key_first($loaiHinhScore),
            'khu_vuc_top_ids'  => array_keys(array_slice($khuVucScore, 0, 3, true)),
            'du_an_top_ids'    => array_keys(array_slice($duAnScore, 0, 3, true)),
            'nhu_cau_top'      => array_key_first($nhuCauScore),
            'tong_hanh_vi'     => array_sum($loaiHinhScore) + array_sum($khuVucScore) + array_sum($duAnScore) + array_sum($nhuCauScore) + array_sum($bdsChatScore),
        ];
    }

    /**
     * Tính điểm phù hợp
     */
    public function tinhDiemBds(BatDongSan $bds, array $profile): array
    {
        $diem  = 0;
        $lyDo  = [];

        $loaiHinhScores = $profile['loai_hinh_scores'] ?? [];
        $khuVucScores   = $profile['khu_vuc_scores']   ?? [];
        $duAnScores     = $profile['du_an_scores']     ?? [];
        $nhuCauScores   = $profile['nhu_cau_scores']   ?? [];
        $bdsChatScores  = $profile['bds_chat_scores']  ?? [];
        $giaTB          = $profile['gia_trung_binh']   ?? null;

        if ($bds->loai_hinh && isset($loaiHinhScores[$bds->loai_hinh])) {
            $diem += $loaiHinhScores[$bds->loai_hinh] * 2;
            $lyDo[] = "Phù hợp loại hình bạn quan tâm";
        }

        $khuVucId = $bds->duAn?->khu_vuc_id;
        if ($khuVucId && isset($khuVucScores[$khuVucId])) {
            $diem += $khuVucScores[$khuVucId] * 1.5;
            $lyDo[] = "Nằm trong khu vực bạn tìm kiếm";
        }

        if ($bds->du_an_id && isset($duAnScores[$bds->du_an_id])) {
            $diem += $duAnScores[$bds->du_an_id] * 2.2;
            $lyDo[] = "Thuộc dự án bạn đã xem nhiều";
        }

        if ($bds->nhu_cau && isset($nhuCauScores[$bds->nhu_cau])) {
            $diem += $nhuCauScores[$bds->nhu_cau];
            $lyDo[] = $bds->nhu_cau === 'ban' ? "Phù hợp nhu cầu mua" : "Phù hợp nhu cầu thuê";
        }

        if (isset($bdsChatScores[$bds->id])) {
            $diem += $bdsChatScores[$bds->id];
            $lyDo[] = "Bạn từng chat về bất động sản này";
        }

        // Chống lỗi chia cho 0 an toàn tuyệt đối
        if ($giaTB && $giaTB > 0) {
            $gia = $bds->nhu_cau === 'ban' ? (float)$bds->gia : (float)$bds->gia_thue;
            if ($gia > 0) {
                $tyLe = abs($gia - $giaTB) / $giaTB;
                if ($tyLe <= 0.15) {
                    $diem += 8;
                    $lyDo[] = "Giá rất phù hợp với ngân sách";
                } elseif ($tyLe <= 0.30) {
                    $diem += 4;
                    $lyDo[] = "Giá trong tầm ngân sách";
                }
            }
        }

        if ($bds->noi_bat)        $diem += 3;
        if ($bds->luot_xem > 100) $diem += 2;

        return [
            'diem'  => round($diem, 2),
            'ly_do' => array_unique($lyDo),
        ];
    }

    /**
     * Hàm chính: Lấy danh sách gợi ý và tính % tương đối
     */
    public function layGoiY(?int $khachHangId, string $sessionId, ?int $truBdsId = null, int $limit = 8): Collection
    {
        $profile = $this->xayDungProfile($khachHangId, $sessionId);

        if (($profile['tong_hanh_vi'] ?? 0) === 0) {
            return BatDongSan::where('hien_thi', true)
                ->where('trang_thai', 'con_hang')
                ->when($truBdsId, fn($q) => $q->where('id', '!=', $truBdsId))
                ->with(['duAn.khuVuc'])
                ->orderByDesc('noi_bat')
                ->orderByDesc('luot_xem')
                ->limit($limit)
                ->get()
                ->map(fn($b) => $b->setAttribute('goi_y_meta', ['diem' => 0, 'phan_tram' => 0, 'ly_do' => ['Bất động sản nổi bật'], 'loai' => 'noi_bat']));
        }

        $ungCuVien = BatDongSan::where('hien_thi', true)
            ->where('trang_thai', 'con_hang')
            ->when($truBdsId, fn($q) => $q->where('id', '!=', $truBdsId))
            ->when(
                !empty($profile['loai_hinh_scores']) || !empty($profile['khu_vuc_top_ids']) || !empty($profile['du_an_top_ids']) || !empty($profile['bds_chat_scores']),
                function ($baseQuery) use ($profile) {
                    $baseQuery->where(function ($q) use ($profile) {
                        $loaiHinhs = array_keys(array_slice($profile['loai_hinh_scores'] ?? [], 0, 2, true));
                        $khuVucIds = $profile['khu_vuc_top_ids'] ?? [];
                        $duAnIds = $profile['du_an_top_ids'] ?? [];
                        $chatBdsIds = array_keys(array_slice($profile['bds_chat_scores'] ?? [], 0, 5, true));

                        if (!empty($loaiHinhs)) $q->whereIn('loai_hinh', $loaiHinhs);

                        if (!empty($khuVucIds)) {
                            $method = !empty($loaiHinhs) ? 'orWhereHas' : 'whereHas';
                            $q->{$method}('duAn', fn($dq) => $dq->whereIn('khu_vuc_id', $khuVucIds));
                        }
                        if (!empty($duAnIds)) {
                            $method = (!empty($loaiHinhs) || !empty($khuVucIds)) ? 'orWhereIn' : 'whereIn';
                            $q->{$method}('du_an_id', $duAnIds);
                        }
                        if (!empty($chatBdsIds)) {
                            $method = (!empty($loaiHinhs) || !empty($khuVucIds) || !empty($duAnIds)) ? 'orWhereIn' : 'whereIn';
                            $q->{$method}('id', $chatBdsIds);
                        }
                    });
                }
            )
            ->with(['duAn.khuVuc'])
            ->limit(50)
            ->get();

        // Map và lấy ra Điểm cao nhất để tính % (Dynamic Percentage)
        $danhSachDaChamDiem = $ungCuVien->map(function ($bds) use ($profile) {
            $meta = $this->tinhDiemBds($bds, $profile);
            $bds->setAttribute('goi_y_meta_temp', $meta);
            return $bds;
        });

        $diemCaoNhat = $danhSachDaChamDiem->max('goi_y_meta_temp.diem') ?: 10;

        $ketQua = $danhSachDaChamDiem->map(function ($bds) use ($diemCaoNhat) {
            $meta = $bds->goi_y_meta_temp;
            // Tính % tương đối so với BĐS phù hợp nhất (Max 99%)
            $phanTram = min(round(($meta['diem'] / max($diemCaoNhat, 1)) * 95) + 4, 99);

            $bds->setAttribute('goi_y_meta', array_merge($meta, ['phan_tram' => $phanTram, 'loai' => 'ca_nhan_hoa']));
            unset($bds->goi_y_meta_temp);
            return $bds;
        })
            ->sortByDesc(fn($b) => $b->goi_y_meta['diem'])
            ->values()
            ->take($limit);

        if ($ketQua->count() < 4) {
            $ids = $ketQua->pluck('id')->toArray();
            if ($truBdsId) $ids[] = $truBdsId;

            $boSung = BatDongSan::where('hien_thi', true)
                ->whereNotIn('id', $ids)
                ->with(['duAn.khuVuc'])
                ->orderByDesc('noi_bat')
                ->limit($limit - $ketQua->count())
                ->get()
                ->map(fn($b) => $b->setAttribute('goi_y_meta', ['diem' => 0, 'phan_tram' => 0, 'ly_do' => ['Bất động sản nổi bật'], 'loai' => 'noi_bat']));

            $ketQua = $ketQua->merge($boSung);
        }

        return $ketQua;
    }

    // ... (Giữ nguyên các hàm ghiLichSuXem và ghiLichSuTimKiem bên dưới) ...
    public function ghiLichSuXem(BatDongSan $bds, ?int $khachHangId, string $sessionId, int $thoiGianXem = 0): void
    {
        $bds->loadMissing('duAn');
        $daXem = LichSuXemBds::where('bat_dong_san_id', $bds->id)
            ->where(function ($q) use ($khachHangId, $sessionId) {
                if ($khachHangId) $q->where('khach_hang_id', $khachHangId);
                else              $q->where('session_id', $sessionId);
            })->where('created_at', '>=', now()->subHour())->exists();

        if ($daXem) return;

        $bds->increment('luot_xem');
        $gia = $bds->nhu_cau === 'ban' ? $bds->gia : $bds->gia_thue;

        LichSuXemBds::create([
            'bat_dong_san_id' => $bds->id,
            'khach_hang_id'   => $khachHangId,
            'session_id'      => $sessionId,
            'loai_hinh'       => $bds->loai_hinh,
            'nhu_cau'         => $bds->nhu_cau,
            'du_an_id'        => $bds->du_an_id ?: $bds->duAn?->id,
            'khu_vuc_id'      => $bds->duAn?->khu_vuc_id,
            'gia_tu'          => $gia ? $gia * 0.9 : null,
            'gia_den'         => $gia ? $gia * 1.1 : null,
            'thoi_gian_xem'   => $thoiGianXem,
        ]);
    }

    public function ghiLichSuTimKiem(?int $khachHangId, string $sessionId, array $payload): void
    {
        $tuKhoa = trim((string) ($payload['tu_khoa'] ?? ''));
        $sapXepTheo = $payload['sap_xep_theo'] ?? null;
        $soKetQua = (int) ($payload['so_ket_qua'] ?? 0);
        $boLocRaw = $payload['bo_loc'] ?? [];
        $boLoc = [];

        foreach ((array) $boLocRaw as $key => $value) {
            if ($value === null || $value === '' || $value === []) continue;
            $boLoc[$key] = $value;
        }

        if ($tuKhoa === '' && empty($boLoc) && empty($sapXepTheo)) return;

        $ganNhat = LichSuTimKiem::query()
            ->where(function ($q) use ($khachHangId, $sessionId) {
                if ($khachHangId) $q->where('khach_hang_id', $khachHangId);
                else              $q->where('session_id', $sessionId);
            })->where('created_at', '>=', now()->subMinutes(3))->latest('id')->first();

        if ($ganNhat && (string) $ganNhat->tu_khoa === $tuKhoa && (array) ($ganNhat->bo_loc ?? []) === $boLoc && (string) ($ganNhat->sap_xep_theo ?? '') === (string) ($sapXepTheo ?? '')) {
            $ganNhat->update(['so_ket_qua' => $soKetQua, 'thoi_diem_tim_kiem' => now()]);
            return;
        }

        LichSuTimKiem::create([
            'khach_hang_id' => $khachHangId,
            'session_id' => $sessionId,
            'tu_khoa' => $tuKhoa !== '' ? $tuKhoa : null,
            'bo_loc' => !empty($boLoc) ? $boLoc : null,
            'sap_xep_theo' => $sapXepTheo,
            'so_ket_qua' => $soKetQua,
            'thoi_diem_tim_kiem' => now(),
        ]);
    }
}
