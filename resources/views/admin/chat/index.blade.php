@extends('admin.layouts.master')
@section('title', 'Chat Tu van truc tuyen')

@section('content')
    <div class="page-header-title mb-1">
        <i class="fas fa-comments"></i>
        <h1 class="h4 mb-0">Trung tam chat khach hang</h1>
    </div>
    <div class="page-header-sub mb-3">
        <span class="dot"></span>
        Social inbox: AI ho tro ban dau, nhan vien tiep nhan va chot giao dich.
    </div>

    @php
        $firstChat = $activeChat ?? ($phienChats->first() ?? null);
        $tongCho = $phienChats->where('trang_thai', 'dang_cho')->count();
        $tongDangChat = $phienChats->where('trang_thai', 'dang_chat')->count();
        $tongDaDong = $phienChats->where('trang_thai', 'da_dong')->count();
        $tongChuaDoc = $phienChats->sum('so_chua_doc');
    @endphp

    <div class="chat-admin-shell">
        <div class="chat-admin-grid">
            <aside class="chat-list-pane">
                <div class="chat-list-head">
                    <div class="fw-bold">Hop thu</div>
                    <div class="d-flex align-items-center gap-1">
                        <span class="count-chip count-chip-orange" title="Tin chua doc">{{ $tongChuaDoc }} moi</span>
                        <span class="count-chip count-chip-blue">{{ $phienChats->count() }} phien</span>
                    </div>
                </div>

                <div class="chat-list-toolbar">
                    <div class="chat-mini-stats">
                        <div><span>Dang cho</span><strong>{{ $tongCho }}</strong></div>
                        <div><span>Dang chat</span><strong>{{ $tongDangChat }}</strong></div>
                        <div><span>Da dong</span><strong>{{ $tongDaDong }}</strong></div>
                    </div>
                    <div class="chat-search-wrap">
                        <i class="fas fa-search"></i>
                        <input id="adminChatSearch" type="text" class="form-control"
                            placeholder="Tim kiem ten khach, ma phien...">
                    </div>
                    <div class="chat-status-tabs" id="chatStatusTabs">
                        <button type="button" class="active" data-filter="all">Tat ca</button>
                        <button type="button" data-filter="dang_cho">Dang cho</button>
                        <button type="button" data-filter="dang_chat">Dang chat</button>
                        <button type="button" data-filter="da_dong">Da dong</button>
                    </div>
                </div>

                <div class="chat-list-body" id="chatListBody">
                    @forelse($phienChats as $chat)
                        @php
                            $isActive = $firstChat && $firstChat->id === $chat->id;
                            $name = $chat->ten_hien_thi;
                            $status = $chat->trang_thai;
                            $statusLabel = match ($status) {
                                'dang_cho' => 'Dang cho NV',
                                'dang_bot' => 'Bot dang xu ly',
                                'dang_chat' => 'NV dang chat',
                                'da_dong' => 'Da dong',
                                default => $status,
                            };
                        @endphp
                        <a href="{{ route('nhanvien.chat.show', $chat->id) }}"
                            class="chat-list-item {{ $isActive ? 'active' : '' }}" data-status="{{ $status }}"
                            data-keyword="{{ strtolower($name . ' ' . $chat->id . ' ' . ($chat->ten_ngu_canh ?: '')) }}">
                            <div class="chat-avatar">{{ mb_substr($name, 0, 1) }}</div>
                            <div class="chat-list-meta">
                                <div class="chat-list-top">
                                    <div class="chat-list-name">{{ $name }}</div>
                                    @if ($chat->so_chua_doc > 0)
                                        <span class="chat-unread">{{ $chat->so_chua_doc }}</span>
                                    @endif
                                </div>
                                <div class="chat-list-sub">#{{ $chat->id }} ·
                                    {{ $chat->ten_ngu_canh ?: 'Khong co ngu canh' }}</div>
                                <div class="chat-list-status {{ $status }}">{{ $statusLabel }}</div>
                                <div class="chat-list-time">{{ optional($chat->tin_nhan_cuoi_at)->diffForHumans() ?: '-' }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="chat-list-empty">Chua co phien chat nao.</div>
                    @endforelse
                </div>
            </aside>

            <section class="chat-thread-pane">
                @if ($firstChat)
                    <div class="chat-thread-head">
                        <div class="chat-thread-user">
                            <div class="chat-avatar large">{{ mb_substr($firstChat->ten_hien_thi, 0, 1) }}</div>
                            <div>
                                <div class="chat-thread-name">{{ $firstChat->ten_hien_thi }}</div>
                                <div class="chat-thread-sub">Ngu canh: {{ $firstChat->ten_ngu_canh ?: 'Khong co' }}</div>
                                <div class="chat-thread-sub">Phien #{{ $firstChat->id }} ·
                                    {{ optional($firstChat->updated_at)->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="chat-thread-actions">
                            @if ($firstChat->trang_thai === 'dang_cho')
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="tiepNhan({{ $firstChat->id }})">
                                    <i class="fas fa-user-check"></i> Tiep nhan
                                </button>
                            @endif
                            @if ($firstChat->trang_thai !== 'da_dong')
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    onclick="dongPhien({{ $firstChat->id }})">
                                    <i class="fas fa-lock"></i> Dong phien
                                </button>
                            @endif
                        </div>
                    </div>

                    <div id="adminChatBody" class="chat-thread-body">
                        @foreach ($currentMessages ?? collect() as $msg)
                            @php
                                $isCustomer = $msg->nguoi_gui === 'khach_hang';
                                $isSystem = $msg->nguoi_gui === 'he_thong';
                                $isBot = $msg->nguoi_gui === 'bot';
                            @endphp
                            <div class="chat-row {{ $isCustomer ? 'from-customer' : 'from-staff' }}"
                                data-msg-id="{{ $msg->id }}">
                                <div
                                    class="chat-bubble {{ $isSystem ? 'is-system' : ($isCustomer ? 'is-customer' : 'is-staff') }}">
                                    @if ($isBot)
                                        <div class="chat-bot-tag"><i class="fas fa-robot"></i> Bot AI</div>
                                    @endif
                                    <div>{{ $msg->noi_dung }}</div>
                                    @if ($msg->tep_dinh_kem)
                                        @if ($msg->loai_tin_nhan === 'hinh_anh')
                                            <div class="chat-media-wrap mt-1">
                                                <a href="{{ asset('storage/' . ltrim($msg->tep_dinh_kem, '/')) }}"
                                                    target="_blank" rel="noopener noreferrer">
                                                    <img class="chat-media-image"
                                                        src="{{ asset('storage/' . ltrim($msg->tep_dinh_kem, '/')) }}"
                                                        alt="Anh dinh kem">
                                                </a>
                                            </div>
                                        @elseif ($msg->loai_tin_nhan === 'video')
                                            <div class="chat-media-wrap mt-1">
                                                <video class="chat-media-video" controls preload="metadata">
                                                    <source src="{{ asset('storage/' . ltrim($msg->tep_dinh_kem, '/')) }}">
                                                </video>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="chat-time {{ $isCustomer ? 'muted' : '' }}">
                                        {{ optional($msg->created_at)->format('H:i d/m') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="chat-quick-replies" id="chatQuickReplies">
                        <button type="button"
                            data-reply="Em da nhan thong tin, em se kiem tra ngay va phan hoi trong it phut.">Da nhan thong
                            tin</button>
                        <button type="button"
                            data-reply="Anh/chi vui long cho em xin so dien thoai de ben em lien he nhanh hon.">Xin so dien
                            thoai</button>
                        <button type="button"
                            data-reply="Anh/chi cho em xin them nhu cau cu the de em tim san pham phu hop nhat.">Xin them
                            nhu cau</button>
                        <button type="button"
                            data-reply="Cam on anh/chi. Em da ghi nhan va se theo sat den khi hoan tat.">Cam on</button>
                    </div>

                    <div class="chat-compose-pane">
                        <form id="adminReplyForm" onsubmit="guiTraLoi(event, {{ $firstChat->id }})"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="chat-compose-box">
                                <input type="file" id="adminReplyFile" accept="image/*,video/*" style="display:none;"
                                    {{ $firstChat->trang_thai === 'da_dong' ? 'disabled' : '' }}>
                                <button class="btn btn-outline-secondary" type="button" id="adminAttachBtn"
                                    onclick="chonTepTraLoi()"
                                    {{ $firstChat->trang_thai === 'da_dong' ? 'disabled' : '' }}>
                                    <i class="fas fa-paperclip"></i>
                                </button>
                                <input type="text" id="adminReplyInput" class="form-control"
                                    placeholder="Nhap noi dung tra loi..."
                                    {{ $firstChat->trang_thai === 'da_dong' ? 'disabled' : '' }}>
                                <button class="btn btn-primary chat-send-btn" type="submit"
                                    {{ $firstChat->trang_thai === 'da_dong' ? 'disabled' : '' }}>
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Gui</span>
                                </button>
                            </div>
                            <div id="adminReplyFileHint" class="chat-file-hint" style="display:none;"></div>
                        </form>
                    </div>
                @else
                    <div class="chat-thread-empty">
                        <i class="fas fa-inbox"></i>
                        <p>Chon mot phien chat de bat dau.</p>
                    </div>
                @endif
            </section>

            <aside class="chat-info-pane">
                <div class="chat-info-head">Thong tin lien quan</div>
                @if ($firstChat)
                    <div class="chat-info-card">
                        <div class="chat-info-title">Lien he</div>
                        <div class="chat-info-row">
                            <span>Ten</span>
                            <strong>{{ $firstChat->ten_hien_thi }}</strong>
                        </div>
                        <div class="chat-info-row">
                            <span>SDT</span>
                            <strong>{{ $firstChat->khachHang?->so_dien_thoai ?: $firstChat->sdt_khach_vang_lai ?: '-' }}</strong>
                        </div>
                        <div class="chat-info-row">
                            <span>Email</span>
                            <strong>{{ $firstChat->khachHang?->email ?: $firstChat->email_khach_vang_lai ?: '-' }}</strong>
                        </div>
                    </div>

                    <div class="chat-info-card">
                        <div class="chat-info-title">Ngu canh va hanh vi</div>
                        <div class="chat-info-row">
                            <span>Loai ngu canh</span>
                            <strong>{{ $firstChat->loai_ngu_canh ?: '-' }}</strong>
                        </div>
                        <div class="chat-info-row">
                            <span>Noi dung</span>
                            <strong>{{ $firstChat->ten_ngu_canh ?: '-' }}</strong>
                        </div>
                        <div class="chat-info-row top">
                            <span>URL</span>
                            <strong class="text-break">{{ $firstChat->url_ngu_canh ?: '-' }}</strong>
                        </div>
                    </div>

                    @if (!$firstChat->khach_hang_id)
                        <div class="chat-warning-box">
                            <i class="fas fa-exclamation-triangle"></i>
                            Khach chua xac thuc tai khoan. Lich su co the bi xoa theo han.
                        </div>
                    @endif
                @else
                    <div class="chat-info-empty">Chua co thong tin de hien thi.</div>
                @endif
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    @if ($firstChat)
        <script>
            let lastId = {{ (int) optional(($currentMessages ?? collect())->last())->id }};
            const adminChatRoutes = {
                traLoi: `{{ route('nhanvien.chat.tra-loi', ['id' => '__ID__']) }}`,
                tiepNhan: `{{ route('nhanvien.chat.tiep-nhan', ['id' => '__ID__']) }}`,
                dong: `{{ route('nhanvien.chat.dong', ['id' => '__ID__']) }}`,
                longPoll: `{{ route('nhanvien.chat.long-poll', ['id' => '__ID__']) }}`,
            };
            const chatBody = document.getElementById('adminChatBody');
            const adminReplyInput = document.getElementById('adminReplyInput');
            const adminReplyFile = document.getElementById('adminReplyFile');
            const adminReplyFileHint = document.getElementById('adminReplyFileHint');
            const adminChatSearch = document.getElementById('adminChatSearch');
            const chatStatusTabs = document.getElementById('chatStatusTabs');
            const chatListBody = document.getElementById('chatListBody');
            const chatQuickReplies = document.getElementById('chatQuickReplies');
            const POLL_INTERVAL_MS = 1200;
            let pollingInFlight = false;
            let activeStatusFilter = 'all';

            function isNearBottom(el, threshold = 96) {
                if (!el) return true;
                const distance = el.scrollHeight - el.scrollTop - el.clientHeight;
                return distance <= threshold;
            }

            function escapeHtml(value) {
                return String(value ?? '').replace(/[&<>'"]/g, (char) => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    "'": '&#39;',
                    '"': '&quot;'
                } [char]));
            }

            function buildAdminAttachmentHtml(msg) {
                if (!msg || !msg.tep_dinh_kem) return '';
                const fileUrl = `/storage/${String(msg.tep_dinh_kem).replace(/^\/+/, '')}`;

                if (msg.loai_tin_nhan === 'hinh_anh') {
                    return `<div class="chat-media-wrap mt-1"><a href="${fileUrl}" target="_blank" rel="noopener noreferrer"><img class="chat-media-image" src="${fileUrl}" alt="Anh dinh kem"></a></div>`;
                }

                if (msg.loai_tin_nhan === 'video') {
                    return `<div class="chat-media-wrap mt-1"><video class="chat-media-video" controls preload="metadata"><source src="${fileUrl}"></video></div>`;
                }

                return '';
            }

            function appendAdminMessage(msg) {
                if (!chatBody) return;
                const shouldAutoScroll = isNearBottom(chatBody);
                const id = Number(msg.id || 0);
                if (id > 0 && chatBody.querySelector(`[data-msg-id="${id}"]`)) return;

                const isCustomer = msg.nguoi_gui === 'khach_hang';
                const isSystem = msg.nguoi_gui === 'he_thong';
                const isBot = msg.nguoi_gui === 'bot';
                const wrap = document.createElement('div');
                if (id > 0) wrap.dataset.msgId = id;
                wrap.className = `chat-row ${isCustomer ? 'from-customer' : 'from-staff'}`;

                const bubble = document.createElement('div');
                bubble.className =
                    `chat-bubble ${isSystem ? 'is-system' : (isCustomer ? 'is-customer' : 'is-staff')}`;
                const t = new Date(msg.created_at).toLocaleString('vi-VN', {
                    hour: '2-digit',
                    minute: '2-digit',
                    day: '2-digit',
                    month: '2-digit'
                });

                bubble.innerHTML = `${isBot ? '<div class="chat-bot-tag"><i class="fas fa-robot"></i> Bot AI</div>' : ''}
                    <div>${escapeHtml(msg.noi_dung)}</div>
                    ${buildAdminAttachmentHtml(msg)}
                    <div class="chat-time ${isCustomer ? 'muted' : ''}">${t}</div>`;
                wrap.appendChild(bubble);
                chatBody.appendChild(wrap);
                if (shouldAutoScroll || msg.nguoi_gui === 'nhan_vien') {
                    chatBody.scrollTo({
                        top: chatBody.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            }

            function applyChatListFilter() {
                if (!chatListBody) return;
                const keyword = (adminChatSearch?.value || '').trim().toLowerCase();
                chatListBody.querySelectorAll('.chat-list-item').forEach((item) => {
                    const status = item.dataset.status || '';
                    const haystack = item.dataset.keyword || '';
                    const matchStatus = activeStatusFilter === 'all' || status === activeStatusFilter;
                    const matchKeyword = !keyword || haystack.includes(keyword);
                    item.style.display = matchStatus && matchKeyword ? '' : 'none';
                });
            }

            function appendOptimisticAdminMessage(text) {
                appendAdminMessage({
                    id: `tmp-${Date.now()}`,
                    nguoi_gui: 'nhan_vien',
                    noi_dung: text,
                    created_at: new Date().toISOString()
                });
            }

            function pollAdmin() {
                if (pollingInFlight) return;
                pollingInFlight = true;

                fetch(`${adminChatRoutes.longPoll.replace('__ID__', String({{ $firstChat->id }}))}?sau_id=${lastId}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success || !Array.isArray(data.tin_nhans)) return;
                        if (data.trang_thai === 'da_dong' && adminReplyInput) {
                            adminReplyInput.disabled = true;
                            const submitBtn = document.querySelector('#adminReplyForm button[type="submit"]');
                            if (submitBtn) submitBtn.disabled = true;
                        }

                        data.tin_nhans.forEach(m => {
                            appendAdminMessage(m);
                            lastId = Math.max(lastId, Number(m.id || 0));
                        });
                    })
                    .catch(() => {})
                    .finally(() => {
                        pollingInFlight = false;
                    });
            }

            window.guiTraLoi = function(e, chatId) {
                e.preventDefault();
                const input = document.getElementById('adminReplyInput');
                const text = input?.value?.trim();
                const selectedFile = adminReplyFile?.files?.[0] || null;
                if (!text && !selectedFile) return;

                const submitBtn = document.querySelector('#adminReplyForm button[type="submit"]');
                if (submitBtn) submitBtn.disabled = true;
                const attachBtn = document.getElementById('adminAttachBtn');
                if (attachBtn) attachBtn.disabled = true;
                input.disabled = true;

                const payload = new FormData();
                if (text) payload.append('noi_dung', text);
                if (selectedFile) payload.append('tep_tin', selectedFile);

                fetch(adminChatRoutes.traLoi.replace('__ID__', String(chatId)), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: payload
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            if (data.tin_nhan) {
                                appendAdminMessage(data.tin_nhan);
                                lastId = Math.max(lastId, Number(data.tin_nhan.id || 0));
                            }
                            input.value = '';
                            if (adminReplyFile) adminReplyFile.value = '';
                            if (adminReplyFileHint) {
                                adminReplyFileHint.style.display = 'none';
                                adminReplyFileHint.textContent = '';
                            }
                        } else if (window.showAdminToast) {
                            showAdminToast(data.message || 'Khong the gui tin nhan', 'error');
                        }
                    })
                    .catch(() => {
                        if (window.showAdminToast) showAdminToast('Loi ket noi, vui long thu lai', 'error');
                    })
                    .finally(() => {
                        input.disabled = false;
                        if (submitBtn) submitBtn.disabled = false;
                        if (attachBtn) attachBtn.disabled = false;
                        input.focus();
                    });
            }

            window.tiepNhan = function(chatId) {
                fetch(adminChatRoutes.tiepNhan.replace('__ID__', String(chatId)), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(() => location.reload());
            }

            window.dongPhien = function(chatId) {
                fetch(adminChatRoutes.dong.replace('__ID__', String(chatId)), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(() => location.reload());
            }

            window.chonTepTraLoi = function() {
                if (adminReplyFile && !adminReplyFile.disabled) adminReplyFile.click();
            }

            adminReplyFile?.addEventListener('change', () => {
                const file = adminReplyFile.files?.[0] || null;
                if (!file) {
                    if (adminReplyFileHint) adminReplyFileHint.style.display = 'none';
                    return;
                }

                const okType = /^(image\/.+|video\/.+)$/.test(file.type || '');
                if (!okType) {
                    if (window.showAdminToast) showAdminToast('Chi ho tro anh hoac video', 'warning');
                    adminReplyFile.value = '';
                    if (adminReplyFileHint) adminReplyFileHint.style.display = 'none';
                    return;
                }

                if (file.size > 20 * 1024 * 1024) {
                    if (window.showAdminToast) showAdminToast('Tep vuot qua 20MB', 'warning');
                    adminReplyFile.value = '';
                    if (adminReplyFileHint) adminReplyFileHint.style.display = 'none';
                    return;
                }

                if (adminReplyFileHint) {
                    adminReplyFileHint.style.display = 'block';
                    adminReplyFileHint.textContent = `Da chon: ${file.name}`;
                }
            });

            setInterval(pollAdmin, POLL_INTERVAL_MS);
            if (chatBody) {
                chatBody.scrollTop = chatBody.scrollHeight;
            }

            adminChatSearch?.addEventListener('input', applyChatListFilter);
            chatStatusTabs?.addEventListener('click', (e) => {
                const btn = e.target.closest('button[data-filter]');
                if (!btn) return;
                activeStatusFilter = btn.dataset.filter || 'all';
                chatStatusTabs.querySelectorAll('button').forEach((b) => b.classList.remove('active'));
                btn.classList.add('active');
                applyChatListFilter();
            });

            chatQuickReplies?.addEventListener('click', (e) => {
                const btn = e.target.closest('button[data-reply]');
                if (!btn || !adminReplyInput) return;
                adminReplyInput.value = btn.dataset.reply || '';
                adminReplyInput.focus();
            });

            applyChatListFilter();
        </script>
    @endif
@endpush
