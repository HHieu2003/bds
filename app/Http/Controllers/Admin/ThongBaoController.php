<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KyGui;
use App\Models\LichHen;
use App\Models\PhienChat;
use App\Models\YeuCauLienHe;
use App\Models\ThongBao;
use App\Models\NhanVien;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ThongBaoController extends Controller
{
    /**
     * Trang HTML danh sách toàn bộ thông báo.
     */
    public function danhSach(): View
    {
        $nv    = Auth::guard('nhanvien')->user();
        $items = $this->buildItems($nv, limit: 100);
        
        $nhanViens = [];
        if ($nv->isAdmin()) {
            $nhanViens = NhanVien::where('kich_hoat', 1)->where('id', '!=', $nv->id)->get();
        }

        return view('admin.thong-bao.index', compact('items', 'nv', 'nhanViens'));
    }

    /**
     * API JSON cho topbar polling.
     */
    public function index(Request $request): JsonResponse
    {
        $nv    = Auth::guard('nhanvien')->user();
        $limit = (int) $request->query('limit', 20);
        $items = $this->buildItems($nv, $limit);

        return response()->json([
            'ok'    => true,
            'total' => count($items),
            'items' => $items,
        ]);
    }

    /**
     * Đánh dấu tất cả thông báo trong bảng thong_bao là đã đọc.
     */
    public function markAllRead(): JsonResponse
    {
        $nv = Auth::guard('nhanvien')->user();

        ThongBao::where('doi_tuong_nhan', 'nhan_vien')
            ->where('doi_tuong_nhan_id', $nv->id)
            ->whereNull('da_doc_at')
            ->update(['da_doc_at' => now()]);

        return response()->json(['ok' => true]);
    }

    /**
     * Admin gửi thông báo cho nhân viên
     */
    public function store(Request $request): JsonResponse
    {
        $nv = Auth::guard('nhanvien')->user();
        
        if (!$nv->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'doi_tuong_nhan_ids' => 'required|array|min:1',
            'doi_tuong_nhan_ids.*' => 'exists:nhan_vien,id',
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'lien_ket' => 'nullable|string|max:255',
        ], [
            'doi_tuong_nhan_ids.required' => 'Vui lòng chọn ít nhất một người nhận.',
            'tieu_de.required' => 'Vui lòng nhập tiêu đề.',
            'noi_dung.required' => 'Vui lòng nhập nội dung.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $data = $validator->validated();

        foreach ($data['doi_tuong_nhan_ids'] as $nhanVienId) {
            // For all selected staff
            ThongBao::create([
                'loai' => 'thong_bao_admin',
                'doi_tuong_nhan' => 'nhan_vien',
                'doi_tuong_nhan_id' => $nhanVienId,
                'tieu_de' => $data['tieu_de'],
                'noi_dung' => $data['noi_dung'],
                'lien_ket' => $data['lien_ket'] ?? null,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Đã gửi thông báo thành công.']);
    }

    // ══════════════════════════════════════════════════════════
    // PRIVATE HELPERS
    // ══════════════════════════════════════════════════════════

    /**
     * Build danh sách thông báo theo vai trò.
     */
    private function buildItems($nv, int $limit = 20): array
    {
        $items = [];

        // ── NGUỒN HÀNG ──────────────────────────────────────────
        if ($nv->isNguonHang()) {
            KyGui::where('trang_thai', 'cho_duyet')
                ->where(function ($q) use ($nv) {
                    $q->where('nhan_vien_phu_trach_id', $nv->id)
                      ->orWhereNull('nhan_vien_phu_trach_id');
                })
                ->latest()->limit($limit)->get()
                ->each(function ($kg) use (&$items) {
                    $items[] = $this->itemKyGui($kg);
                });

            LichHen::where('nhan_vien_nguon_hang_id', $nv->id)
                ->whereIn('trang_thai', ['cho_xac_nhan', 'moi_dat'])
                ->latest()->limit($limit)->get()
                ->each(function ($lh) use (&$items) {
                    $items[] = $this->itemLichHenNguon($lh);
                });
        }

        // ── SALE ─────────────────────────────────────────────────
        elseif ($nv->isSale()) {
            YeuCauLienHe::where('trang_thai', 'moi')
                ->where(function ($q) use ($nv) {
                    $q->where('nhan_vien_phu_trach_id', $nv->id)
                      ->orWhereNull('nhan_vien_phu_trach_id');
                })
                ->latest()->limit($limit)->get()
                ->each(function ($yc) use (&$items) {
                    $items[] = $this->itemYeuCau($yc);
                });

            LichHen::where('nhan_vien_sale_id', $nv->id)
                ->whereIn('trang_thai', ['cho_xac_nhan', 'moi_dat'])
                ->latest()->limit($limit)->get()
                ->each(function ($lh) use (&$items) {
                    $items[] = $this->itemLichHenSale($lh);
                });

            PhienChat::where('trang_thai', 'dang_cho')
                ->where(function ($q) use ($nv) {
                    $q->where('nhan_vien_phu_trach_id', $nv->id)
                      ->orWhereNull('nhan_vien_phu_trach_id');
                })
                ->latest()->limit(10)->get()
                ->each(function ($pc) use (&$items) {
                    $items[] = $this->itemChat($pc);
                });
        }

        // ── ADMIN ─────────────────────────────────────────────────
        else {
            KyGui::where('trang_thai', 'cho_duyet')
                ->latest()->limit($limit)->get()
                ->each(function ($kg) use (&$items) {
                    $items[] = $this->itemKyGui($kg);
                });

            LichHen::whereIn('trang_thai', ['cho_xac_nhan', 'moi_dat'])
                ->latest()->limit($limit)->get()
                ->each(function ($lh) use (&$items) {
                    $items[] = $this->itemLichHenNguon($lh);
                });

            YeuCauLienHe::where('trang_thai', 'moi')
                ->latest()->limit($limit)->get()
                ->each(function ($yc) use (&$items) {
                    $items[] = $this->itemYeuCau($yc);
                });

            PhienChat::where('trang_thai', 'dang_cho')
                ->latest()->limit(10)->get()
                ->each(function ($pc) use (&$items) {
                    $items[] = $this->itemChat($pc);
                });
        }

        // ── Thêm từ bảng thong_bao ──────────────────────────────
        $iconMap = [
            'ky_gui_moi'      => ['icon' => 'fas fa-file-signature', 'color' => '#e67e22', 'bg' => '#fff8f0'],
            'lich_hen_moi'    => ['icon' => 'fas fa-calendar-check', 'color' => '#8e44ad', 'bg' => '#f5eeff'],
            'yeu_cau_lien_he' => ['icon' => 'fas fa-phone-alt',       'color' => '#3498db', 'bg' => '#eaf4fd'],
            'tin_nhan_chat'   => ['icon' => 'fas fa-comment-dots',    'color' => '#e74c3c', 'bg' => '#fff0f0'],
            'thong_bao_admin' => ['icon' => 'fas fa-bullhorn',        'color' => '#8e44ad', 'bg' => '#f5eeff'],
        ];
        ThongBao::where('doi_tuong_nhan', 'nhan_vien')
            ->where('doi_tuong_nhan_id', $nv->id)
            ->whereNull('da_doc_at')
            ->latest()->limit($limit)->get()
            ->each(function ($tb) use (&$items, $iconMap) {
                $meta = $iconMap[$tb->loai] ?? ['icon' => 'fas fa-bell', 'color' => '#2d6a9f', 'bg' => '#e8f4fd'];
                $items[] = [
                    'id'        => 'tb-' . $tb->id,
                    'loai'      => $tb->loai,
                    'icon'      => $meta['icon'],
                    'color'     => $meta['color'],
                    'bg'        => $meta['bg'],
                    'tieu_de'   => $tb->tieu_de ?? 'Thông báo mới',
                    'noi_dung'  => $tb->noi_dung ?? '',
                    'lien_ket'  => $tb->lien_ket ?? '#',
                    'thoi_gian' => $tb->created_at,
                    'da_doc'    => false,
                ];
            });

        // Sắp xếp mới nhất lên đầu
        usort($items, fn($a, $b) => strtotime($b['thoi_gian']) - strtotime($a['thoi_gian']));
        $items = array_slice($items, 0, $limit);

        // Format thời gian
        foreach ($items as &$item) {
            $item['thoi_gian_relative'] = $this->humanTime($item['thoi_gian']);
            $item['thoi_gian'] = $item['thoi_gian'] instanceof \Carbon\Carbon
                ? $item['thoi_gian']->toISOString()
                : (string) $item['thoi_gian'];
        }
        unset($item);

        return $items;
    }

    // ── Item builders ────────────────────────────────────────────

    private function itemKyGui($kg): array
    {
        return [
            'id'        => 'kg-' . $kg->id,
            'loai'      => 'ky_gui',
            'icon'      => 'fas fa-file-signature',
            'color'     => '#e67e22',
            'bg'        => '#fff8f0',
            'tieu_de'   => 'Ký gửi mới chờ duyệt',
            'noi_dung'  => ($kg->ho_ten_chu_nha ?: 'Khách hàng') . ' — ' . ($kg->loai_hinh_info['label'] ?? $kg->loai_hinh),
            'lien_ket'  => route('nhanvien.admin.ky-gui.show', $kg->id),
            'thoi_gian' => $kg->created_at,
            'da_doc'    => false,
        ];
    }

    private function itemLichHenNguon($lh): array
    {
        return [
            'id'        => 'lh-' . $lh->id,
            'loai'      => 'lich_hen',
            'icon'      => 'fas fa-calendar-check',
            'color'     => '#8e44ad',
            'bg'        => '#f5eeff',
            'tieu_de'   => 'Lịch hẹn chờ xác nhận',
            'noi_dung'  => ($lh->ten_khach_hang ?: 'Khách hàng') . ' — ' . optional($lh->thoi_gian_hen)->format('d/m H:i'),
            'lien_ket'  => route('nhanvien.admin.lich-hen.show', $lh->id),
            'thoi_gian' => $lh->created_at,
            'da_doc'    => false,
        ];
    }

    private function itemLichHenSale($lh): array
    {
        return [
            'id'        => 'lh-' . $lh->id,
            'loai'      => 'lich_hen',
            'icon'      => 'fas fa-calendar-check',
            'color'     => '#27ae60',
            'bg'        => '#e8f8f0',
            'tieu_de'   => 'Lịch hẹn mới',
            'noi_dung'  => ($lh->ten_khach_hang ?: 'Khách hàng') . ' — ' . optional($lh->thoi_gian_hen)->format('d/m H:i'),
            'lien_ket'  => route('nhanvien.admin.lich-hen.show', $lh->id),
            'thoi_gian' => $lh->created_at,
            'da_doc'    => false,
        ];
    }

    private function itemYeuCau($yc): array
    {
        return [
            'id'        => 'yc-' . $yc->id,
            'loai'      => 'yeu_cau',
            'icon'      => 'fas fa-phone-alt',
            'color'     => '#3498db',
            'bg'        => '#eaf4fd',
            'tieu_de'   => 'Yêu cầu liên hệ mới',
            'noi_dung'  => ($yc->ho_ten ?: 'Khách hàng') . ' — ' . ($yc->so_dien_thoai ?? ''),
            'lien_ket'  => route('nhanvien.admin.lien-he.show', $yc->id),
            'thoi_gian' => $yc->created_at,
            'da_doc'    => false,
        ];
    }

    private function itemChat($pc): array
    {
        return [
            'id'        => 'pc-' . $pc->id,
            'loai'      => 'chat',
            'icon'      => 'fas fa-comment-dots',
            'color'     => '#e74c3c',
            'bg'        => '#fff0f0',
            'tieu_de'   => 'Chat đang chờ phản hồi',
            'noi_dung'  => $pc->ten_hien_thi . ' đang chờ tư vấn...',
            'lien_ket'  => route('nhanvien.admin.chat.show', $pc->id),
            'thoi_gian' => $pc->created_at,
            'da_doc'    => false,
        ];
    }

    private function humanTime($time): string
    {
        if (!$time) return '';
        $ts   = $time instanceof \Carbon\Carbon ? $time : \Carbon\Carbon::parse($time);
        $diff = now()->diffInSeconds($ts, false);

        if (abs($diff) < 60)      return 'Vừa xong';
        if (abs($diff) < 3600)    return abs(intdiv($diff, 60)) . ' phút trước';
        if (abs($diff) < 86400)   return abs(intdiv($diff, 3600)) . ' giờ trước';
        if (abs($diff) < 2592000) return abs(intdiv($diff, 86400)) . ' ngày trước';
        return $ts->format('d/m/Y');
    }
}
