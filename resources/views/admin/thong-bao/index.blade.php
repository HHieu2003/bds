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
</script>
@endsection
