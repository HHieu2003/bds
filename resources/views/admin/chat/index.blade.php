@extends('admin.layouts.master')
@section('title', 'Chat Tư vấn – Trung tâm hội thoại')

@push('styles')
    <style>
        /* ═══════════════════════════════════════════════════
       Override padding layout cha cho trang chat
    ═══════════════════════════════════════════════════ */
        .main-content-wrapper,
        .page-content,
        #main-content {
            padding: 0 !important;
            overflow: hidden;
        }

        :root {
            --chat-col-left: 320px;
            --chat-col-right: 280px;
            --chat-header: 56px;
            --chat-height: calc(100vh - var(--topbar-height, 60px));
            --chat-bg: #f0f2f5;
            --msg-out-bg: #0d6efd;
            --msg-in-bg: #ffffff;
            --msg-in-border: #e4e6ea;
            --sidebar-bg: #ffffff;
            --online-dot: #31a24c;
            --tab-active: #0d6efd;
            --unread-dot: #e3342f;
        }

        /* ── Outer shell ── */
        .zl-shell {
            display: flex;
            height: var(--chat-height);
            overflow: hidden;
            background: var(--chat-bg);
            border-top: 1px solid #e4e6ea;
        }

        /* ══════════════ COLUMN LEFT ══════════════ */
        .zl-left {
            width: var(--chat-col-left);
            min-width: 240px;
            max-width: 360px;
            display: flex;
            flex-direction: column;
            background: var(--sidebar-bg);
            border-right: 1px solid #e4e6ea;
            transition: transform .25s ease;
            z-index: 20;
        }

        .zl-left-head {
            padding: 14px 16px 10px;
            border-bottom: 1px solid #f0f0f0;
            flex-shrink: 0;
        }

        .zl-left-head h6 {
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #65676b;
            margin: 0 0 10px;
        }

        .zl-search {
            position: relative;
        }

        .zl-search input {
            width: 100%;
            padding: 7px 12px 7px 34px;
            background: #f0f2f5;
            border: none;
            border-radius: 20px;
            font-size: .84rem;
            color: #1c1e21;
            outline: none;
        }

        .zl-search input:focus {
            background: #e4e6ea;
        }

        .zl-search .zl-search-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #65676b;
            font-size: .78rem;
            pointer-events: none;
        }

        /* Filter tabs */
        .zl-filter-tabs {
            display: flex;
            gap: 4px;
            padding: 8px 12px 0;
            flex-shrink: 0;
            border-bottom: 1px solid #e4e6ea;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .zl-filter-tabs::-webkit-scrollbar {
            display: none;
        }

        .zl-filter-tabs button {
            flex-shrink: 0;
            background: none;
            border: none;
            padding: 5px 12px 8px;
            font-size: .75rem;
            font-weight: 600;
            color: #65676b;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            white-space: nowrap;
            margin-bottom: -1px;
        }

        .zl-filter-tabs button.active {
            color: var(--tab-active);
            border-bottom-color: var(--tab-active);
        }

        /* Session list */
        .zl-list {
            flex: 1;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #d4d6da transparent;
        }

        .zl-list::-webkit-scrollbar {
            width: 4px;
        }

        .zl-list::-webkit-scrollbar-thumb {
            background: #d4d6da;
            border-radius: 4px;
        }

        .zl-session {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            cursor: pointer;
            border-bottom: 1px solid #f5f5f5;
            text-decoration: none;
            color: inherit;
            transition: background .12s;
        }

        .zl-session:hover {
            background: #f5f6fa;
        }

        .zl-session.active {
            background: #e7f0ff;
        }

        .zl-session.unread .zl-session-name {
            font-weight: 700;
            color: #1c1e21;
        }

        .zl-session.unread .zl-session-preview {
            color: #1c1e21;
            font-weight: 600;
        }

        /* Avatar */
        .zl-av {
            position: relative;
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
        }

        .zl-av.av-bot {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }

        .zl-av.av-done {
            background: #adb5bd;
        }

        .zl-av.av-queue {
            background: linear-gradient(135deg, #ffd200, #f7971e);
        }

        .zl-av-status {
            position: absolute;
            bottom: 1px;
            right: 1px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
            background: var(--online-dot);
        }

        .zl-av-status.offline {
            background: #adb5bd;
        }

        .zl-session-meta {
            flex: 1;
            min-width: 0;
        }

        .zl-session-top {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 4px;
        }

        .zl-session-name {
            font-size: .86rem;
            font-weight: 500;
            color: #1c1e21;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .zl-session-time {
            font-size: .7rem;
            color: #8a8d91;
            flex-shrink: 0;
        }

        .zl-session-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 3px;
        }

        .zl-session-preview {
            font-size: .78rem;
            color: #65676b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 160px;
        }

        .zl-unread-badge {
            background: var(--unread-dot);
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            flex-shrink: 0;
        }

        .zl-status-pill {
            font-size: .64rem;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 10px;
            white-space: nowrap;
        }

        .sp-queue {
            background: #fff3cd;
            color: #856404;
        }

        .sp-bot {
            background: #e7f3ff;
            color: #0a58ca;
        }

        .sp-chat {
            background: #d1e7dd;
            color: #0f5132;
        }

        .sp-done {
            background: #e9ecef;
            color: #6c757d;
        }

        .zl-list-empty {
            text-align: center;
            padding: 40px 20px;
            color: #adb5bd;
        }

        .zl-list-empty i {
            font-size: 2.5rem;
            margin-bottom: 8px;
            opacity: .4;
        }

        /* ════════════════ COLUMN MIDDLE ════════════════ */
        .zl-middle {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            background: var(--chat-bg);
        }

        .zl-thread-head {
            height: var(--chat-header);
            flex-shrink: 0;
            background: #fff;
            border-bottom: 1px solid #e4e6ea;
            display: flex;
            align-items: center;
            padding: 0 16px;
            gap: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }

        .zl-thread-head .zl-av {
            width: 38px;
            height: 38px;
            font-size: .9rem;
        }

        .zl-thread-user {
            flex: 1;
            min-width: 0;
        }

        .zl-thread-name {
            font-weight: 700;
            font-size: .92rem;
            color: #1c1e21;
        }

        .zl-thread-sub {
            font-size: .74rem;
            color: #65676b;
        }

        .zl-thread-actions {
            display: flex;
            gap: 6px;
        }

        .zl-thread-actions .btn {
            border-radius: 20px;
            font-size: .78rem;
            padding: 4px 12px;
        }

        /* Messages */
        .zl-messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            scrollbar-width: thin;
            scrollbar-color: #d4d6da transparent;
        }

        .zl-messages::-webkit-scrollbar {
            width: 5px;
        }

        .zl-messages::-webkit-scrollbar-thumb {
            background: #d4d6da;
            border-radius: 4px;
        }

        .zl-row {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            max-width: 72%;
        }

        .zl-row.from-customer {
            align-self: flex-start;
        }

        .zl-row.from-staff {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .zl-row.from-system {
            align-self: center;
            max-width: 90%;
        }

        .zl-bubble {
            padding: 9px 13px;
            border-radius: 18px;
            font-size: .875rem;
            line-height: 1.5;
            max-width: 100%;
            word-break: break-word;
        }

        .from-customer .zl-bubble {
            background: var(--msg-in-bg);
            border: 1px solid var(--msg-in-border);
            border-radius: 4px 18px 18px 18px;
            color: #1c1e21;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .06);
        }

        .from-staff .zl-bubble {
            background: var(--msg-out-bg);
            color: #fff;
            border-radius: 18px 4px 18px 18px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .12);
        }

        .from-system .zl-bubble {
            background: rgba(0, 0, 0, .06);
            color: #65676b;
            border-radius: 12px;
            font-size: .77rem;
            padding: 5px 14px;
            text-align: center;
        }

        .zl-bot-tag {
            font-size: .64rem;
            font-weight: 700;
            color: #f5576c;
            background: #fff0f3;
            border: 1px solid #f5576c;
            border-radius: 8px;
            padding: 1px 7px;
            margin-bottom: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .zl-msg-time {
            font-size: .68rem;
            color: #adb5bd;
            align-self: flex-end;
            flex-shrink: 0;
            margin-bottom: 2px;
        }

        .from-staff .zl-msg-time {
            color: rgba(255, 255, 255, .6);
        }

        .zl-media img,
        .zl-media video {
            max-width: 220px;
            max-height: 160px;
            border-radius: 10px;
            display: block;
            margin-top: 6px;
        }

        .zl-date-sep {
            text-align: center;
            font-size: .72rem;
            color: #adb5bd;
            position: relative;
            margin: 10px 0;
        }

        .zl-date-sep::before,
        .zl-date-sep::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: #e4e6ea;
        }

        .zl-date-sep::before {
            left: 0;
        }

        .zl-date-sep::after {
            right: 0;
        }

        /* Quick replies */
        .zl-quick {
            padding: 8px 12px;
            background: #fff;
            border-top: 1px solid #e4e6ea;
            display: flex;
            gap: 6px;
            overflow-x: auto;
            flex-shrink: 0;
            scrollbar-width: none;
        }

        .zl-quick::-webkit-scrollbar {
            display: none;
        }

        .zl-quick button {
            flex-shrink: 0;
            background: #f0f2f5;
            border: none;
            border-radius: 16px;
            padding: 5px 13px;
            font-size: .76rem;
            color: #0d6efd;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: background .15s;
        }

        .zl-quick button:hover {
            background: #e7f0ff;
        }

        /* Compose */
        .zl-compose {
            padding: 10px 14px;
            background: #fff;
            border-top: 1px solid #e4e6ea;
            flex-shrink: 0;
        }

        .zl-compose-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f0f2f5;
            border-radius: 22px;
            padding: 6px 6px 6px 14px;
        }

        .zl-compose-box input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            font-size: .875rem;
            color: #1c1e21;
        }

        .zl-compose-box input::placeholder {
            color: #adb5bd;
        }

        .zl-compose-box .btn-attach {
            background: none;
            border: none;
            color: #65676b;
            font-size: 1rem;
            padding: 4px 6px;
            border-radius: 50%;
            cursor: pointer;
            transition: background .15s;
        }

        .zl-compose-box .btn-attach:hover {
            background: #e4e6ea;
        }

        .zl-send-btn {
            background: var(--msg-out-bg);
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            color: #fff;
            font-size: .82rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s;
            flex-shrink: 0;
        }

        .zl-send-btn:hover {
            background: #0b5ed7;
        }

        .zl-send-btn:disabled {
            background: #adb5bd;
            cursor: not-allowed;
        }

        .zl-file-hint {
            font-size: .74rem;
            color: #0d6efd;
            padding: 4px 14px 0;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .zl-thread-empty {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
        }

        .zl-thread-empty i {
            font-size: 3rem;
            margin-bottom: 12px;
            opacity: .3;
        }

        /* ════════════════ COLUMN RIGHT ════════════════ */
        .zl-right {
            width: var(--chat-col-right);
            min-width: 220px;
            display: flex;
            flex-direction: column;
            background: #fff;
            border-left: 1px solid #e4e6ea;
            overflow-y: auto;
        }

        .zl-info-head {
            padding: 14px 16px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #65676b;
            border-bottom: 1px solid #f0f0f0;
            flex-shrink: 0;
        }

        .zl-info-section {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
        }

        .zl-info-label {
            font-size: .66rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #adb5bd;
            margin-bottom: 2px;
        }

        .zl-info-val {
            font-size: .82rem;
            color: #1c1e21;
            font-weight: 500;
        }

        .zl-info-val a {
            color: #0d6efd;
            text-decoration: none;
        }

        .zl-warning {
            margin: 12px;
            border-radius: 8px;
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 10px 12px;
            font-size: .77rem;
            color: #856404;
        }

        /* ════════════════ BELL ════════════════ */
        .zl-bell-btn {
            position: relative;
            background: none;
            border: none;
            padding: 6px;
            color: #65676b;
            font-size: 1.1rem;
            cursor: pointer;
            border-radius: 50%;
            transition: background .15s;
        }

        .zl-bell-btn:hover {
            background: #f0f2f5;
        }

        .zl-bell-btn .bell-dot {
            position: absolute;
            top: 3px;
            right: 3px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--unread-dot);
            border: 2px solid #fff;
            display: none;
        }

        .zl-bell-btn .bell-dot.show {
            display: block;
        }

        /* ════════════════ MOBILE ════════════════ */
        @media (max-width:991px) {
            .zl-right {
                display: none;
            }
        }

        @media (max-width:767px) {
            :root {
                --chat-col-left: 100vw;
            }

            .zl-shell {
                position: relative;
            }

            .zl-left {
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                transform: translateX(0);
            }

            .zl-left.mobile-hidden {
                transform: translateX(-100%);
            }

            .zl-middle {
                width: 100%;
            }

            .zl-thread-head .zl-back-btn {
                display: flex !important;
            }

            .zl-row {
                max-width: 85%;
            }
        }

        .zl-back-btn {
            display: none;
            background: none;
            border: none;
            color: #0d6efd;
            font-size: 1rem;
            padding: 4px 8px 4px 0;
            cursor: pointer;
        }
    </style>
@endpush

@push('topbar_actions')
    <button class="zl-bell-btn" id="globalBellBtn" onclick="window.location='{{ route('nhanvien.chat.index') }}'"
        title="Chat khách hàng">
        <i class="fas fa-bell"></i>
        <span class="bell-dot {{ ($tongChuaDoc ?? 0) > 0 ? 'show' : '' }}" id="globalBellDot"></span>
    </button>
@endpush

@section('content')
    @php
        $firstChat = $activeChat ?? ($phienChats->first() ?? null);
        $tongCho = $phienChats->where('trangthai', 'dangcho')->count();
        $tongDangChat = $phienChats->where('trangthai', 'dangchat')->count();
        $tongDaDong = $phienChats->where('trangthai', 'dadong')->count();
        $tongChuaDoc = $phienChats->sum('sochuadoc');
    @endphp

    <div class="zl-shell" id="zlShell">

        {{-- ══════════════ CỘT TRÁI ══════════════ --}}
        <aside class="zl-left" id="zlLeft">
            <div class="zl-left-head">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6>Hộp thư
                        @if ($tongChuaDoc > 0)
                            <span class="zl-unread-badge ms-1">{{ $tongChuaDoc }}</span>
                        @endif
                    </h6>
                    <div class="d-flex gap-1 align-items-center">
                        <span class="badge text-bg-warning" style="font-size:.65rem;"
                            title="Đang chờ">{{ $tongCho }}</span>
                        <span class="badge text-bg-success" style="font-size:.65rem;"
                            title="Đang chat">{{ $tongDangChat }}</span>
                        <span class="badge text-bg-secondary" style="font-size:.65rem;"
                            title="Đã đóng">{{ $tongDaDong }}</span>
                    </div>
                </div>
                <div class="zl-search">
                    <i class="fas fa-search zl-search-icon"></i>
                    <input type="text" id="adminChatSearch" placeholder="Tìm tên khách, mã phiên...">
                </div>
            </div>

            <div class="zl-filter-tabs" id="chatStatusTabs">
                <button type="button" class="active" data-filter="all">Tất cả</button>
                <button type="button" data-filter="dangcho">Chờ <span class="badge text-bg-warning ms-1"
                        style="font-size:.62rem;">{{ $tongCho }}</span></button>
                <button type="button" data-filter="dangchat">Đang chat</button>
                <button type="button" data-filter="dangbot">Bot</button>
                <button type="button" data-filter="dadong">Đã đóng</button>
            </div>

            <div class="zl-list" id="chatListBody">
                @forelse($phienChats as $chat)
                    @php
                        $isActive = $firstChat && $firstChat->id === $chat->id;
                        $name = $chat->tenhienthi;
                        $status = $chat->trangthai;
                        $hasUnread = $chat->sochuadoc > 0;
                        $avClass = match ($status) {
                            'dangbot' => 'av-bot',
                            'dadong' => 'av-done',
                            'dangcho' => 'av-queue',
                            default => '',
                        };
                        $pillClass = match ($status) {
                            'dangcho' => 'sp-queue',
                            'dangbot' => 'sp-bot',
                            'dangchat' => 'sp-chat',
                            'dadong' => 'sp-done',
                            default => 'sp-done',
                        };
                        $pillText = match ($status) {
                            'dangcho' => 'Chờ NV',
                            'dangbot' => 'Bot AI',
                            'dangchat' => 'Đang chat',
                            'dadong' => 'Đã đóng',
                            default => $status,
                        };
                    @endphp
                    <a href="{{ route('nhanvien.chat.show', $chat->id) }}"
                        class="zl-session {{ $isActive ? 'active' : '' }} {{ $hasUnread ? 'unread' : '' }}"
                        data-status="{{ $status }}"
                        data-keyword="{{ strtolower($name . ' ' . $chat->id . ' ' . ($chat->tenngucanh ?? '')) }}">

                        <div class="zl-av {{ $avClass }}">
                            {{ mb_strtoupper(mb_substr($name, 0, 1)) }}
                            @if ($status === 'dangchat')
                                <span class="zl-av-status"></span>
                            @elseif($status === 'dadong')
                                <span class="zl-av-status offline"></span>
                            @endif
                        </div>

                        <div class="zl-session-meta">
                            <div class="zl-session-top">
                                <span class="zl-session-name">{{ $name }}</span>
                                <span
                                    class="zl-session-time">{{ optional($chat->tinnhancuoiat)->diffForHumans(null, true, true) ?? '' }}</span>
                            </div>
                            <div class="zl-session-bottom">
                                <span class="zl-session-preview">
                                    {{ Str::limit(optional($chat->tinNhanCuoi)->noidung ?? ($chat->tenngucanh ?? '#' . $chat->id), 30) }}
                                </span>
                                @if ($hasUnread)
                                    <span class="zl-unread-badge">{{ $chat->sochuadoc }}</span>
                                @else
                                    <span class="zl-status-pill {{ $pillClass }}">{{ $pillText }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="zl-list-empty">
                        <i class="fas fa-inbox d-block"></i>
                        <p class="mt-2" style="font-size:.83rem;">Chưa có phiên chat nào</p>
                    </div>
                @endforelse
            </div>
        </aside>


        {{-- ══════════════ CỘT GIỮA ══════════════ --}}
        <section class="zl-middle">
            @if ($firstChat)
                @php
                    $status = $firstChat->trangthai;
                    $isDone = $status === 'dadong';
                    $avClass = match ($status) {
                        'dangbot' => 'av-bot',
                        'dadong' => 'av-done',
                        'dangcho' => 'av-queue',
                        default => '',
                    };
                @endphp

                <div class="zl-thread-head">
                    <button class="zl-back-btn" id="zlBackBtn"><i class="fas fa-arrow-left"></i></button>
                    <div class="zl-av {{ $avClass }}" style="width:38px;height:38px;font-size:.9rem;">
                        {{ mb_strtoupper(mb_substr($firstChat->tenhienthi, 0, 1)) }}
                    </div>
                    <div class="zl-thread-user">
                        <div class="zl-thread-name">{{ $firstChat->tenhienthi }}</div>
                        <div class="zl-thread-sub">
                            {{ $firstChat->tenngucanh ?? 'Không có ngữ cảnh' }} &nbsp;·&nbsp;
                            <span
                                class="zl-status-pill {{ match ($status) {'dangcho' => 'sp-queue','dangbot' => 'sp-bot','dangchat' => 'sp-chat','dadong' => 'sp-done',default => 'sp-done'} }}">
                                {{ match ($status) {'dangcho' => 'Chờ NV','dangbot' => 'Bot AI','dangchat' => 'Đang chat','dadong' => 'Đã đóng',default => $status} }}
                            </span>
                        </div>
                    </div>
                    <div class="zl-thread-actions">
                        @if ($status === 'dangcho')
                            <button class="btn btn-primary btn-sm" onclick="tiepNhan({{ $firstChat->id }})">
                                <i class="fas fa-user-check me-1"></i>Tiếp nhận
                            </button>
                        @endif
                        @if (!$isDone)
                            <button class="btn btn-outline-danger btn-sm" onclick="dongPhien({{ $firstChat->id }})">
                                <i class="fas fa-lock me-1"></i>Đóng
                            </button>
                        @endif
                    </div>
                </div>

                <div class="zl-messages" id="adminChatBody">
                    <div class="zl-date-sep">{{ optional($firstChat->created_at)->format('d/m/Y') }}</div>
                    @foreach ($currentMessages ?? collect() as $msg)
                        @php
                            $isCustomer = $msg->nguoigui === 'khachhang';
                            $isSystem = $msg->nguoigui === 'hethong';
                            $isBot = $msg->nguoigui === 'bot';
                        @endphp
                        <div class="zl-row {{ $isSystem ? 'from-system' : ($isCustomer ? 'from-customer' : 'from-staff') }}"
                            data-msg-id="{{ $msg->id }}">
                            @if (!$isSystem && $isCustomer)
                                <div class="zl-av flex-shrink-0"
                                    style="width:30px;height:30px;font-size:.72rem;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;font-weight:700;display:flex;align-items:center;justify-content:center;align-self:flex-end;">
                                    {{ mb_strtoupper(mb_substr($firstChat->tenhienthi, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                @if ($isBot)
                                    <div class="zl-bot-tag"><i class="fas fa-robot"></i> Bot AI</div>
                                @endif
                                <div class="zl-bubble">{{ $msg->noidung }}</div>
                                @if ($msg->tepdinhkem)
                                    <div class="zl-media">
                                        @if ($msg->loaitinnhan === 'hinhanh')
                                            <a href="{{ asset('storage/' . ltrim($msg->tepdinhkem, '/')) }}" target="_blank">
                                                <img src="{{ asset('storage/' . ltrim($msg->tepdinhkem, '/')) }}"
                                                    alt="Ảnh">
                                            </a>
                                        @elseif($msg->loaitinnhan === 'video')
                                            <video controls preload="metadata">
                                                <source src="{{ asset('storage/' . ltrim($msg->tepdinhkem, '/')) }}">
                                            </video>
                                        @endif
                                    </div>
                                @endif
                                <div class="zl-msg-time {{ $isCustomer ? '' : 'text-end' }}">
                                    {{ optional($msg->created_at)->format('H:i d/m') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="zl-quick" id="chatQuickReplies">
                    <button type="button" data-reply="Em đã nhận thông tin, sẽ phản hồi trong ít phút.">✅ Đã
                        nhận</button>
                    <button type="button" data-reply="Anh/chị cho em xin số điện thoại để liên hệ nhanh hơn.">📞 Xin
                        SĐT</button>
                    <button type="button" data-reply="Anh/chị cho em xin thêm nhu cầu để em tìm sản phẩm phù hợp.">🏠 Xin
                        nhu cầu</button>
                    <button type="button" data-reply="Cảm ơn anh/chị. Em đã ghi nhận và sẽ theo sát đến khi hoàn tất.">🙏
                        Cảm ơn</button>
                    <button type="button" data-reply="Em sẽ gửi cho anh/chị một số dự án phù hợp trong vài phút nữa.">📋
                        Gửi dự án</button>
                </div>

                <div class="zl-compose">
                    <form id="adminReplyForm" onsubmit="guiTraLoi(event,{{ $firstChat->id }})"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="file" id="adminReplyFile" accept="image/*,video/*" style="display:none"
                            {{ $isDone ? 'disabled' : '' }}>
                        <div class="zl-compose-box">
                            <button type="button" class="btn-attach" id="adminAttachBtn" onclick="chonTepTraLoi()"
                                {{ $isDone ? 'disabled' : '' }}>
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <input type="text" id="adminReplyInput"
                                placeholder="{{ $isDone ? 'Phiên chat đã đóng' : 'Nhập tin nhắn...' }}"
                                {{ $isDone ? 'disabled' : '' }} autocomplete="off">
                            <button type="submit" class="zl-send-btn" {{ $isDone ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                        <div id="adminReplyFileHint" class="zl-file-hint" style="display:none;">
                            <i class="fas fa-paperclip"></i>
                            <span></span>
                            <button type="button" onclick="xoaFile()"
                                style="background:none;border:none;color:#dc3545;margin-left:4px;cursor:pointer;">✕</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="zl-thread-empty">
                    <i class="fas fa-comments"></i>
                    <p>Chọn một phiên chat để bắt đầu</p>
                    <small class="text-muted">{{ $phienChats->count() }} phiên chat đang chờ</small>
                </div>
            @endif
        </section>


        {{-- ══════════════ CỘT PHẢI ══════════════ --}}
        <aside class="zl-right" id="zlRight">
            <div class="zl-info-head"><i class="fas fa-info-circle me-1"></i>Thông tin</div>
            @if ($firstChat)
                <div class="zl-info-section">
                    <div class="zl-info-label">Khách hàng</div>
                    <div class="zl-info-val fw-bold">{{ $firstChat->tenhienthi }}</div>
                </div>
                <div class="zl-info-section">
                    <div class="zl-info-label">Số điện thoại</div>
                    <div class="zl-info-val">
                        @if ($firstChat->khachHang?->so_dien_thoai)
                            <a
                                href="tel:{{ $firstChat->khachHang->so_dien_thoai }}">{{ $firstChat->khachHang->so_dien_thoai }}</a>
                        @else
                            {{ $firstChat->sdt_khach_vanglai ?? '–' }}
                        @endif
                    </div>
                </div>
                <div class="zl-info-section">
                    <div class="zl-info-label">Email</div>
                    <div class="zl-info-val">{{ $firstChat->khachHang?->email ?? ($firstChat->email_khach_vanglai ?? '–') }}
                    </div>
                </div>
                <div class="zl-info-section">
                    <div class="zl-info-label">Ngữ cảnh</div>
                    <div class="zl-info-val">{{ $firstChat->tenngucanh ?? '–' }}</div>
                </div>
                @if ($firstChat->urlngucanh)
                    <div class="zl-info-section">
                        <div class="zl-info-label">URL trang</div>
                        <div class="zl-info-val">
                            <a href="{{ $firstChat->urlngucanh }}" target="_blank" rel="noopener"
                                style="font-size:.77rem;word-break:break-all;">
                                {{ Str::limit($firstChat->urlngucanh, 40) }}
                            </a>
                        </div>
                    </div>
                @endif
                <div class="zl-info-section">
                    <div class="zl-info-label">Mở lúc</div>
                    <div class="zl-info-val">{{ optional($firstChat->created_at)->format('H:i d/m/Y') }}</div>
                </div>
                @if (!$firstChat->khach_hang_id)
                    <div class="zl-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Khách vãng lai – chưa đăng ký. Lịch sử có thể bị xóa theo hạn.
                    </div>
                @else
                    <div class="zl-info-section">
                        <a href="{{ route('nhanvien.admin.khach-hang.show', $firstChat->khach_hang_id) }}"
                            class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-id-card me-1"></i>Hồ sơ 360°
                        </a>
                    </div>
                @endif
            @else
                <div class="zl-list-empty"><i class="fas fa-user d-block"></i>
                    <p>Chưa chọn phiên</p>
                </div>
            @endif
        </aside>

    </div>
@endsection

@push('scripts')
    @if ($firstChat)
        <script>
            let lastId = {{ (int) optional(($currentMessages ?? collect())->last())->id }};
            const ROUTES = {
                traLoi: '{{ route('nhanvien.chat.tra-loi', ['id' => '__ID__']) }}'.replace('__ID__', ''),
                tiepNhan: '{{ route('nhanvien.chat.tiep-nhan', ['id' => '__ID__']) }}'.replace('__ID__', ''),
                dong: '{{ route('nhanvien.chat.dong', ['id' => '__ID__']) }}'.replace('__ID__', ''),
                longPoll: '{{ route('nhanvien.chat.long-poll', ['id' => '__ID__']) }}'.replace('__ID__', ''),
            };
            const fn = (key, id) => ROUTES[key] + id;
            const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}';
            const CHAT_ID = {{ $firstChat->id }};

            const chatBody = document.getElementById('adminChatBody');
            const inp = document.getElementById('adminReplyInput');
            const fileInput = document.getElementById('adminReplyFile');
            const fileHint = document.getElementById('adminReplyFileHint');
            const fileSpan = fileHint?.querySelector('span');
            let polling = false,
                activeFilter = 'all';

            const esc = v => String(v ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            const nearBot = el => !el || (el.scrollHeight - el.scrollTop - el.clientHeight) < 96;

            function buildMedia(m) {
                if (!m?.tepdinhkem) return '';
                const u = '/storage/' + String(m.tepdinhkem).replace(/^\//, '');
                return m.loaitinnhan === 'hinhanh' ?
                    `<div class="zl-media"><a href="${u}" target="_blank"><img src="${u}" alt="Ảnh"></a></div>` :
                    m.loaitinnhan === 'video' ?
                    `<div class="zl-media"><video controls preload="metadata"><source src="${u}"></video></div>` :
                    '';
            }

            function appendMsg(m) {
                if (!chatBody) return;
                const id = Number(m.id ?? 0);
                if (id > 0 && chatBody.querySelector(`[data-msg-id="${id}"]`)) return;
                const scroll = nearBot(chatBody);
                const isCust = m.nguoigui === 'khachhang',
                    isSys = m.nguoigui === 'hethong',
                    isBot = m.nguoigui === 'bot';
                // Remove optimistic
                chatBody.querySelectorAll('[data-msg-id^="tmp"]').forEach(el => {
                    if (el.querySelector('.zl-bubble')?.textContent?.trim() === String(m.noidung ?? '').trim()) el
                        .remove();
                });
                const row = document.createElement('div');
                if (id > 0) row.dataset.msgId = id;
                row.className = `zl-row ${isSys?'from-system':isCust?'from-customer':'from-staff'}`;
                const t = new Date(m.createdat || m.created_at || Date.now()).toLocaleString('vi-VN', {
                    hour: '2-digit',
                    minute: '2-digit',
                    day: '2-digit',
                    month: '2-digit'
                });
                row.innerHTML = `
    ${!isSys&&isCust?`<div class="zl-av flex-shrink-0" style="width:30px;height:30px;font-size:.72rem;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;font-weight:700;display:flex;align-items:center;justify-content:center;align-self:flex-end;">K</div>`:''}
    <div>
      ${isBot?'<div class="zl-bot-tag"><i class="fas fa-robot"></i> Bot AI</div>':''}
      <div class="zl-bubble">${esc(m.noidung)}</div>
      ${buildMedia(m)}
      <div class="zl-msg-time ${isCust?'':'text-end'}">${t}</div>
    </div>`;
                chatBody.appendChild(row);
                if (scroll || m.nguoigui === 'nhanvien') chatBody.scrollTo({
                    top: chatBody.scrollHeight,
                    behavior: 'smooth'
                });
            }

            function poll() {
                if (polling) return;
                polling = true;
                fetch(fn('longPoll', CHAT_ID) + '?sauid=' + lastId, {
                        headers: {
                            Accept: 'application/json'
                        }
                    })
                    .then(r => r.json()).then(d => {
                        if (!d.success || !Array.isArray(d.tinnhans)) return;
                        if (d.trangthai === 'dadong') {
                            inp && (inp.disabled = true);
                            document.querySelector('#adminReplyForm button[type=submit]') && (document.querySelector(
                                '#adminReplyForm button[type=submit]').disabled = true);
                        }
                        d.tinnhans.forEach(m => {
                            appendMsg(m);
                            lastId = Math.max(lastId, Number(m.id ?? 0));
                        });
                    }).catch(() => {}).finally(() => {
                        polling = false;
                    });
            }
            setInterval(poll, 1200);

            window.guiTraLoi = (e, id) => {
                e.preventDefault();
                const text = inp?.value?.trim(),
                    file = fileInput?.files?.[0] ?? null;
                if (!text && !file) return;
                const sb = document.querySelector('#adminReplyForm button[type=submit]'),
                    ab = document.getElementById('adminAttachBtn');
                [inp, sb, ab].forEach(el => el && (el.disabled = true));
                if (text) {
                    const t = document.createElement('div');
                    t.dataset.msgId = 'tmp-' + Date.now();
                    t.className = 'zl-row from-staff';
                    t.innerHTML = `<div><div class="zl-bubble" style="opacity:.65">${esc(text)}</div></div>`;
                    chatBody?.appendChild(t);
                    chatBody?.scrollTo({
                        top: chatBody.scrollHeight,
                        behavior: 'smooth'
                    });
                }
                const fd = new FormData();
                if (text) fd.append('noidung', text);
                if (file) fd.append('teptin', file);
                fetch(fn('traLoi', id), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Accept': 'application/json'
                        },
                        body: fd
                    })
                    .then(r => r.json()).then(d => {
                        if (d.success) {
                            if (d.tinnhan) {
                                appendMsg(d.tinnhan);
                                lastId = Math.max(lastId, Number(d.tinnhan.id ?? 0));
                            }
                            inp && (inp.value = '');
                            fileInput && (fileInput.value = '');
                            fileHint && (fileHint.style.display = 'none');
                        } else alert(d.message ?? 'Lỗi gửi tin');
                    }).catch(() => alert('Lỗi kết nối'))
                    .finally(() => {
                        [inp, sb, ab].forEach(el => el && (el.disabled = false));
                        inp?.focus();
                    });
            };
            window.tiepNhan = id => fetch(fn('tiepNhan', id), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                }
            }).then(() => location.reload());
            window.dongPhien = id => {
                if (!confirm('Đóng phiên chat này?')) return;
                fetch(fn('dong', id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    }
                }).then(() => location.reload());
            };
            window.chonTepTraLoi = () => {
                if (fileInput && !fileInput.disabled) fileInput.click();
            };
            window.xoaFile = () => {
                if (fileInput) fileInput.value = '';
                if (fileHint) {
                    fileHint.style.display = 'none';
                    if (fileSpan) fileSpan.textContent = '';
                }
            };
            fileInput?.addEventListener('change', () => {
                const f = fileInput.files?.[0];
                if (!f) {
                    fileHint && (fileHint.style.display = 'none');
                    return;
                }
                if (!/^(image|video)\//.test(f.type)) {
                    alert('Chỉ hỗ trợ ảnh hoặc video');
                    fileInput.value = '';
                    return;
                }
                if (f.size > 20 * 1024 * 1024) {
                    alert('File vượt 20MB');
                    fileInput.value = '';
                    return;
                }
                if (fileHint && fileSpan) {
                    fileHint.style.display = 'flex';
                    fileSpan.textContent = f.name;
                }
            });
            document.getElementById('chatQuickReplies')?.addEventListener('click', e => {
                const b = e.target.closest('button[data-reply]');
                if (b && inp) {
                    inp.value = b.dataset.reply;
                    inp.focus();
                }
            });

            function applyFilter() {
                const kw = document.getElementById('adminChatSearch')?.value?.trim().toLowerCase() ?? '';
                document.getElementById('chatListBody')?.querySelectorAll('.zl-session').forEach(el => {
                    const ms = activeFilter === 'all' || el.dataset.status === activeFilter;
                    const mk = !kw || (el.dataset.keyword ?? '').includes(kw);
                    el.style.display = ms && mk ? '' : 'none';
                });
            }
            document.getElementById('adminChatSearch')?.addEventListener('input', applyFilter);
            document.getElementById('chatStatusTabs')?.addEventListener('click', e => {
                const b = e.target.closest('button[data-filter]');
                if (!b) return;
                activeFilter = b.dataset.filter;
                document.querySelectorAll('#chatStatusTabs button').forEach(x => x.classList.remove('active'));
                b.classList.add('active');
                applyFilter();
            });
            // Mobile back
            document.getElementById('zlBackBtn')?.addEventListener('click', () => document.getElementById('zlLeft')?.classList
                .remove('mobile-hidden'));
            document.getElementById('chatListBody')?.addEventListener('click', e => {
                if (e.target.closest('.zl-session') && window.innerWidth < 768) document.getElementById('zlLeft')
                    ?.classList.add('mobile-hidden');
            });
            if (chatBody) chatBody.scrollTop = chatBody.scrollHeight;
        </script>
    @endif
    <script>
        // Bell polling — kiểm tra tin chưa đọc mỗi 15 giây
        (function() {
            const dot = document.getElementById('globalBellDot');
            if (!dot) return;
            setInterval(() => {
                fetch('{{ route('nhanvien.chat.index') }}?check_unread=1', {
                        headers: {
                            Accept: 'application/json'
                        }
                    })
                    .then(r => r.json()).then(d => dot.classList.toggle('show', (d.tong_chua_doc ?? 0) > 0))
                    .catch(() => {});
            }, 15000);
        })();
    </script>
@endpush
