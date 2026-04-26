@extends('admin.layouts.master')

@section('title', 'Tất cả thông báo')
@section('page_title', 'Thông báo')

@section('content')
@php
    $nv = Auth::guard('nhanvien')->user();

    $loaiMeta = [
        'ky_gui'   => ['label' => 'Ký gửi',    'icon' => 'fas fa-file-signature', 'color' => '#e67e22'],
        'lich_hen' => ['label' => 'Lịch hẹn',   'icon' => 'fas fa-calendar-check', 'color' => '#8e44ad'],
        'yeu_cau'  => ['label' => 'Liên hệ',    'icon' => 'fas fa-phone-alt',      'color' => '#3498db'],
        'chat'     => ['label' => 'Chat',        'icon' => 'fas fa-comment-dots',   'color' => '#e74c3c'],
        'thong_bao_admin' => ['label' => 'Từ Admin',    'icon' => 'fas fa-bullhorn',      'color' => '#8e44ad'],
    ];

    // Nhóm theo loại
    $byLoai = collect($items)->groupBy('loai');
@endphp

<div style="max-width:820px;margin:0 auto;padding:0 0 40px;">

    {{-- ── Header ── --}}
    <div style="display:flex;align-items:center;justify-content:space-between;
                margin-bottom:20px;flex-wrap:wrap;gap:10px;">
        <div>
            <h1 style="font-size:1.25rem;font-weight:800;color:var(--navy);margin:0;
                       display:flex;align-items:center;gap:8px;">
                <i class="fas fa-bell" style="color:var(--primary)"></i>
                Tất cả thông báo
            </h1>
            <p style="font-size:.78rem;color:var(--text-sub);margin:4px 0 0;">
                {{ count($items) }} thông báo đang chờ xử lý
            </p>
        </div>
        <div style="display:flex;gap:10px;">
            @if ($nv->isAdmin())
                <button type="button" onclick="openSendNotifModal()"
                        style="display:inline-flex;align-items:center;gap:6px;
                               padding:7px 14px;border-radius:8px;border:none;
                               background:var(--primary);color:#fff;font-size:.78rem;
                               font-weight:600;cursor:pointer;transition:all .2s;">
                    <i class="fas fa-paper-plane"></i> Gửi thông báo
                </button>
            @endif
            @if (count($items) > 0)
                <button id="btnMarkAllPage" type="button"
                        onclick="pageMarkAllRead()"
                        style="display:inline-flex;align-items:center;gap:6px;
                               padding:7px 14px;border-radius:8px;border:1px solid var(--border);
                               background:#fff;color:var(--primary);font-size:.78rem;
                               font-weight:600;cursor:pointer;transition:all .2s;">
                    <i class="fas fa-check-double"></i> Đánh dấu tất cả đã đọc
                </button>
            @endif
        </div>
    </div>

    @if (count($items) === 0)
        {{-- Trống --}}
        <div style="text-align:center;padding:80px 20px;
                    background:#fff;border-radius:16px;border:1px solid var(--border);">
            <i class="fas fa-bell-slash"
               style="font-size:3rem;color:#d0d5dd;display:block;margin-bottom:16px;"></i>
            <div style="font-size:1rem;font-weight:700;color:var(--navy);margin-bottom:6px;">
                Không có thông báo nào
            </div>
            <p style="font-size:.83rem;color:var(--text-sub);">
                Mọi thứ đều ổn. Khi có thông báo mới sẽ hiển thị ở đây.
            </p>
        </div>
    @else

        @foreach ($byLoai as $loai => $group)
        @php
            $meta = $loaiMeta[$loai] ?? ['label' => 'Khác', 'icon' => 'fas fa-bell', 'color' => '#2d6a9f'];
        @endphp

        {{-- Section header --}}
        <div style="display:flex;align-items:center;gap:8px;
                    margin:20px 0 8px;">
            <span style="width:30px;height:30px;border-radius:8px;
                         background:{{ $meta['color'] }}18;color:{{ $meta['color'] }};
                         display:inline-flex;align-items:center;justify-content:center;
                         font-size:.8rem;">
                <i class="{{ $meta['icon'] }}"></i>
            </span>
            <span style="font-size:.82rem;font-weight:700;color:var(--navy);">
                {{ $meta['label'] }}
            </span>
            <span style="font-size:.72rem;color:#fff;background:{{ $meta['color'] }};
                         border-radius:20px;padding:1px 9px;font-weight:700;">
                {{ $group->count() }}
            </span>
        </div>

        <div style="background:#fff;border-radius:14px;border:1px solid var(--border);
                    overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.04);">
            @foreach ($group as $item)
            <a href="{{ $item['lien_ket'] }}"
               class="notif-page-item {{ $item['da_doc'] ? '' : 'unread' }}"
               style="display:flex;align-items:flex-start;gap:14px;padding:14px 18px;
                      border-bottom:1px solid #f0f2f7;text-decoration:none;color:inherit;
                      transition:background .15s;
                      {{ $item['da_doc'] ? '' : 'background:#fffbf5;' }}">

                {{-- Icon --}}
                <div style="width:40px;height:40px;border-radius:12px;flex-shrink:0;
                            background:{{ $item['bg'] }};color:{{ $item['color'] }};
                            display:flex;align-items:center;justify-content:center;
                            font-size:.9rem;">
                    <i class="{{ $item['icon'] }}"></i>
                </div>

                {{-- Content --}}
                <div style="flex:1;min-width:0;">
                    <div style="font-size:.82rem;font-weight:700;color:var(--navy);
                                margin-bottom:2px;">
                        {{ $item['tieu_de'] }}
                        @if (!$item['da_doc'])
                            <span style="display:inline-block;width:7px;height:7px;
                                         border-radius:50%;background:var(--primary);
                                         margin-left:5px;vertical-align:middle;"></span>
                        @endif
                    </div>
                    <div style="font-size:.76rem;color:var(--text-sub);
                                white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $item['noi_dung'] }}
                    </div>
                    <div style="font-size:.69rem;color:#aab;margin-top:4px;">
                        <i class="far fa-clock" style="margin-right:3px;"></i>
                        {{ $item['thoi_gian_relative'] }}
                        <span style="margin:0 6px;opacity:.4;">·</span>
                        {{ \Carbon\Carbon::parse($item['thoi_gian'])->format('d/m/Y H:i') }}
                    </div>
                </div>

                {{-- Arrow --}}
                <div style="color:#d0d5dd;font-size:.75rem;flex-shrink:0;margin-top:2px;">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
            @endforeach
        </div>
        @endforeach

    @endif
</div>

<style>
.notif-page-item:hover {
    background: #f5f7ff !important;
}
.notif-page-item:last-child {
    border-bottom: none !important;
}
</style>

<script>
function pageMarkAllRead() {
    var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    fetch('{{ route('nhanvien.admin.thong-bao.mark-all-read') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(() => {
        document.querySelectorAll('.notif-page-item.unread').forEach(function (el) {
            el.style.background = '';
            el.classList.remove('unread');
            var dot = el.querySelector('span[style*="border-radius:50%"]');
            if (dot) dot.remove();
        });
        var btn = document.getElementById('btnMarkAllPage');
        if (btn) { btn.disabled = true; btn.style.opacity = '.5'; }
        if (typeof showAdminToast === 'function') showAdminToast('Đã đánh dấu tất cả đã đọc', 'success');
    })
    .catch(() => {});
}

// ── ADMIN SEND NOTIF MODAL ──────────────────────────────────────────────
@if ($nv->isAdmin())
function openSendNotifModal() {
    document.getElementById('sendNotifModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeSendNotifModal() {
    document.getElementById('sendNotifModal').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('formSendNotif')?.addEventListener('submit', function(e) {
    e.preventDefault();
    var fd = new FormData(this);
    var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    
    var btn = document.getElementById('btnSubmitNotif');
    var originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Đang gửi...';

    // Convert FormData to JSON, handling multiple select
    var payload = {
        doi_tuong_nhan_ids: fd.getAll('doi_tuong_nhan_ids[]'),
        tieu_de: fd.get('tieu_de'),
        noi_dung: fd.get('noi_dung'),
        lien_ket: fd.get('lien_ket')
    };

    fetch('{{ route('nhanvien.admin.thong-bao.store') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            if (typeof showAdminToast === 'function') showAdminToast(res.message, 'success');
            closeSendNotifModal();
            this.reset();
            setTimeout(() => location.reload(), 1000);
        } else {
            var errorMsg = res.message || 'Có lỗi xảy ra.';
            if (res.errors) {
                var firstKey = Object.keys(res.errors)[0];
                errorMsg = res.errors[firstKey][0];
            }
            if (typeof showAdminToast === 'function') showAdminToast(errorMsg, 'error');
        }
    })
    .catch(() => {
        if (typeof showAdminToast === 'function') showAdminToast('Lỗi kết nối.', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});
@endif
</script>

@if ($nv->isAdmin())
<div id="sendNotifModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;padding:16px;">
    <div style="background:#fff;width:100%;max-width:500px;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.2);overflow:hidden;">
        <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
            <h3 style="margin:0;font-size:1rem;font-weight:700;color:var(--navy);"><i class="fas fa-paper-plane" style="color:var(--primary);margin-right:8px;"></i>Gửi thông báo cho nhân viên</h3>
            <button type="button" onclick="closeSendNotifModal()" style="background:none;border:none;font-size:1.2rem;color:var(--text-sub);cursor:pointer;">&times;</button>
        </div>
        <form id="formSendNotif" style="padding:20px;">
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:.8rem;font-weight:600;color:var(--navy);margin-bottom:6px;">Người nhận <span style="color:red">*</span></label>
                <select name="doi_tuong_nhan_ids[]" multiple required style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;font-size:.85rem;min-height:100px;">
                    @foreach($nhanViens as $nvItem)
                        <option value="{{ $nvItem->id }}">{{ $nvItem->ho_ten }} ({{ $nvItem->vai_tro_label }})</option>
                    @endforeach
                </select>
                <div style="font-size:.7rem;color:var(--text-sub);margin-top:4px;">Giữ Ctrl (hoặc Cmd) để chọn nhiều người.</div>
            </div>
            
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:.8rem;font-weight:600;color:var(--navy);margin-bottom:6px;">Tiêu đề <span style="color:red">*</span></label>
                <input type="text" name="tieu_de" required placeholder="VD: Thông báo họp gấp" style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;font-size:.85rem;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:.8rem;font-weight:600;color:var(--navy);margin-bottom:6px;">Nội dung <span style="color:red">*</span></label>
                <textarea name="noi_dung" required rows="4" placeholder="Nhập nội dung thông báo..." style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;font-size:.85rem;resize:vertical;"></textarea>
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:.8rem;font-weight:600;color:var(--navy);margin-bottom:6px;">Đường dẫn đính kèm (Tuỳ chọn)</label>
                <input type="url" name="lien_ket" placeholder="https://..." style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;font-size:.85rem;">
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;">
                <button type="button" onclick="closeSendNotifModal()" style="padding:8px 16px;border-radius:8px;border:1px solid var(--border);background:#fff;color:var(--navy);font-weight:600;font-size:.85rem;cursor:pointer;">Hủy</button>
                <button type="submit" id="btnSubmitNotif" style="padding:8px 16px;border-radius:8px;border:none;background:var(--primary);color:#fff;font-weight:600;font-size:.85rem;cursor:pointer;"><i class="fas fa-paper-plane" style="margin-right:6px;"></i>Gửi thông báo</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
