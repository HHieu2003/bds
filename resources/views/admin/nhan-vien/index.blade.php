@extends('admin.layouts.master')
@section('title', 'Quản lý Nhân viên')
@section('page_title', 'Nhân viên')
@section('page_parent', 'Hệ thống')

@push('styles')
    <style>
        /* ═══════════════════════════════════════
       NHÂN VIÊN — INDEX
    ═══════════════════════════════════════ */

        /* ── Stat Cards ── */
        .nv-stats {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media(max-width:1100px) {
            .nv-stats {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media(max-width:600px) {
            .nv-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .nv-stat {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #eef0f5;
            padding: 1.1rem 1.1rem;
            display: flex;
            align-items: center;
            gap: .85rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
            transition: transform .2s, box-shadow .2s;
            cursor: default;
        }

        .nv-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, .07);
        }

        .nv-stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .nv-stat-num {
            font-size: 1.6rem;
            font-weight: 900;
            color: #1a3c5e;
            line-height: 1;
            letter-spacing: -.5px;
        }

        .nv-stat-lbl {
            font-size: .72rem;
            color: #aaa;
            margin-top: .25rem;
            font-weight: 500;
        }

        /* ── Page Header ── */
        .nv-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .nv-page-title {
            font-size: 1.25rem;
            font-weight: 900;
            color: #1a3c5e;
            margin: 0 0 .3rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .nv-page-title i {
            color: #FF8C42;
        }

        .nv-page-sub {
            font-size: .8rem;
            color: #aaa;
            margin: 0;
        }

        .nv-btn-add {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: linear-gradient(135deg, #FF8C42, #e8721e);
            color: #fff;
            border: none;
            padding: .6rem 1.2rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: .84rem;
            text-decoration: none;
            cursor: pointer;
            font-family: inherit;
            box-shadow: 0 4px 14px rgba(255, 140, 66, .3);
            transition: all .2s;
            white-space: nowrap;
        }

        .nv-btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 140, 66, .4);
            color: #fff;
        }

        /* ── Filter Box ── */
        .nv-filter {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #eef0f5;
            padding: 1rem 1.15rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        }

        .nv-filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            align-items: center;
        }

        .nv-ctrl {
            height: 38px;
            border: 1.5px solid #e8eaef;
            border-radius: 8px;
            padding: 0 .85rem;
            font-size: .83rem;
            color: #333;
            background: #fafbfc;
            outline: none;
            font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
        }

        .nv-ctrl:focus {
            border-color: #FF8C42;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .1);
        }

        .nv-ctrl-search {
            flex: 1;
            min-width: 200px;
            padding-left: 2.2rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23aaa' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: .7rem center;
        }

        select.nv-ctrl {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%23aaa' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .8rem center;
            background-color: #fafbfc;
            padding-right: 2rem;
        }

        .nv-btn-filter {
            height: 38px;
            background: #1a3c5e;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0 1.1rem;
            font-weight: 700;
            font-size: .83rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: .4rem;
            font-family: inherit;
            transition: background .2s;
        }

        .nv-btn-filter:hover {
            background: #0f2742;
        }

        .nv-btn-reset {
            height: 38px;
            background: #fff5f5;
            color: #e74c3c;
            border: 1.5px solid #fcc;
            border-radius: 8px;
            padding: 0 .9rem;
            font-size: .83rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .35rem;
            transition: all .2s;
        }

        .nv-btn-reset:hover {
            background: #e74c3c;
            color: #fff;
            border-color: #e74c3c;
        }

        /* ── Data Box ── */
        .nv-box {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #eef0f5;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            overflow: hidden;
        }

        .nv-box-header {
            padding: .85rem 1.25rem;
            border-bottom: 1px solid #f5f6fa;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
        }

        .nv-result-info {
            font-size: .8rem;
            color: #999;
        }

        .nv-result-info strong {
            color: #1a3c5e;
            font-weight: 700;
        }

        /* ── Table ── */
        .nv-tbl-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .nv-tbl {
            width: 100%;
            border-collapse: collapse;
            min-width: 860px;
        }

        .nv-tbl thead th {
            padding: .7rem 1rem;
            background: #f8faff;
            border-bottom: 2px solid #eef0f8;
            font-size: .68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #8896b3;
            white-space: nowrap;
            text-align: left;
        }

        .nv-tbl td {
            padding: .85rem 1rem;
            border-bottom: 1px solid #f5f6fa;
            vertical-align: middle;
            font-size: .845rem;
            color: #2d3748;
        }

        .nv-tbl tbody tr:last-child td {
            border-bottom: none;
        }

        .nv-tbl tbody tr:hover td {
            background: #fdfeff;
        }

        .nv-row-me td {
            background: #fffdf5 !important;
        }

        .nv-row-me:hover td {
            background: #fff9e8 !important;
        }

        /* ── Person cell ── */
        .nv-person {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .nv-ava-wrap {
            position: relative;
            flex-shrink: 0;
        }

        .nv-ava {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid #eef0f5;
            background: #e8edf5;
            display: block;
        }

        .nv-dot {
            position: absolute;
            bottom: 1px;
            right: 1px;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .nv-dot.on {
            background: #22c55e;
        }

        .nv-dot.off {
            background: #e74c3c;
        }

        .nv-name {
            font-weight: 700;
            color: #1a3c5e;
            font-size: .875rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            line-height: 1.3;
            transition: color .15s;
        }

        .nv-name:hover {
            color: #FF8C42;
        }

        .nv-me-tag {
            font-size: .6rem;
            font-weight: 800;
            background: linear-gradient(135deg, #FF8C42, #e8721e);
            color: #fff;
            padding: .1rem .42rem;
            border-radius: 5px;
            letter-spacing: .3px;
        }

        .nv-join {
            font-size: .72rem;
            color: #c0c8d8;
            margin-top: .15rem;
        }

        /* ── Contact cell ── */
        .nv-contact {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .78rem;
            color: #555;
            margin-bottom: .2rem;
        }

        .nv-contact:last-child {
            margin-bottom: 0;
        }

        .nv-contact a {
            color: #1a3c5e;
            text-decoration: none;
            transition: color .15s;
        }

        .nv-contact a:hover {
            color: #FF8C42;
        }

        .nv-contact i {
            width: 14px;
            text-align: center;
            flex-shrink: 0;
        }

        /* ── Role badge ── */
        .nv-role {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .74rem;
            font-weight: 700;
            padding: .28rem .7rem;
            border-radius: 20px;
            white-space: nowrap;
        }

        /* ── Count link ── */
        .nv-count {
            font-weight: 700;
            font-size: .88rem;
            color: #2d6a9f;
            text-decoration: none;
            padding: .18rem .55rem;
            border-radius: 6px;
            background: #eef4ff;
            transition: background .15s;
            display: inline-block;
        }

        .nv-count:hover {
            background: #ddeaff;
            color: #1a3c5e;
        }

        .nv-count-0 {
            color: #cdd2de;
            font-size: .8rem;
        }

        /* ── Last login ── */
        .nv-login-time {
            font-size: .8rem;
            color: #444;
            line-height: 1.3;
        }

        .nv-login-ago {
            font-size: .71rem;
            color: #bbb;
            margin-top: .1rem;
        }

        .nv-never {
            font-size: .78rem;
            color: #d0d8e8;
            font-style: italic;
        }

        /* ── Toggle switch ── */
        .nv-sw {
            position: relative;
            cursor: pointer;
            display: inline-block;
            line-height: 0;
        }

        .nv-sw.dis {
            opacity: .45;
            cursor: not-allowed;
        }

        .nv-sw input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .nv-sw-track {
            display: block;
            width: 42px;
            height: 24px;
            background: #dde0e8;
            border-radius: 12px;
            transition: background .25s;
            position: relative;
        }

        .nv-sw input:checked~.nv-sw-track {
            background: #22c55e;
        }

        .nv-sw-thumb {
            position: absolute;
            width: 18px;
            height: 18px;
            background: #fff;
            border-radius: 50%;
            top: 3px;
            left: 3px;
            transition: transform .25s;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .2);
        }

        .nv-sw input:checked~.nv-sw-track .nv-sw-thumb {
            transform: translateX(18px);
        }

        /* ── Action buttons ── */
        .nv-acts {
            display: flex;
            gap: .3rem;
            justify-content: center;
            align-items: center;
        }

        .nv-act {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            border: 1px solid transparent;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            text-decoration: none;
            transition: all .15s;
            padding: 0;
            line-height: 1;
            flex-shrink: 0;
        }

        .nv-act-v {
            background: #f0fdf4;
            color: #16a34a;
            border-color: #bbf7d0;
        }

        .nv-act-v:hover {
            background: #16a34a;
            color: #fff;
            border-color: #16a34a;
        }

        .nv-act-e {
            background: #eff6ff;
            color: #2563eb;
            border-color: #bfdbfe;
        }

        .nv-act-e:hover {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
        }

        .nv-act-pw {
            background: #fffbeb;
            color: #d97706;
            border-color: #fde68a;
        }

        .nv-act-pw:hover {
            background: #d97706;
            color: #fff;
            border-color: #d97706;
        }

        .nv-act-d {
            background: #fff5f5;
            color: #e74c3c;
            border-color: #fecaca;
        }

        .nv-act-d:hover {
            background: #e74c3c;
            color: #fff;
            border-color: #e74c3c;
        }

        /* ── Empty state ── */
        .nv-empty {
            text-align: center;
            padding: 3.5rem 1rem;
        }

        .nv-empty-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #f4f6fb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto .85rem;
            font-size: 1.6rem;
            color: #c8d0e0;
        }

        .nv-empty p {
            color: #bbb;
            font-size: .9rem;
            margin: 0 0 .75rem;
        }

        .nv-empty a {
            color: #FF8C42;
            font-weight: 700;
            text-decoration: none;
            font-size: .84rem;
        }

        /* ── Pagination ── */
        .nv-pagi {
            padding: 1rem 1.25rem;
            border-top: 1px solid #f5f6fa;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
        }

        .nv-pagi-info {
            font-size: .78rem;
            color: #aaa;
        }

        .nv-pagi-links {
            display: flex;
            align-items: center;
            gap: .3rem;
            flex-wrap: wrap;
        }

        .nv-pb {
            min-width: 34px;
            height: 34px;
            padding: 0 .6rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: .82rem;
            font-weight: 600;
            color: #4a6a8a;
            background: #f5f7ff;
            text-decoration: none;
            border: 1.5px solid transparent;
            transition: all .15s;
        }

        .nv-pb:hover:not(.act):not(.dis) {
            background: #e8f0ff;
            color: #1a3c5e;
            border-color: #c8daf5;
        }

        .nv-pb.act {
            background: linear-gradient(135deg, #1a3c5e, #2d6a9f);
            color: #fff;
            box-shadow: 0 3px 10px rgba(26, 60, 94, .22);
            cursor: default;
            border-color: transparent;
        }

        .nv-pb.dis {
            color: #d0d8e8;
            background: #fafafa;
            cursor: not-allowed;
            pointer-events: none;
        }

        .nv-dots {
            min-width: 24px;
            text-align: center;
            color: #aaa;
            font-weight: 700;
            font-size: .82rem;
        }

        /* ── Modal ── */
        .nv-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 20, 40, .55);
            z-index: 9000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            animation: fadeIn .2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        .nv-modal {
            background: #fff;
            border-radius: 16px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 24px 64px rgba(0, 0, 0, .22);
            overflow: hidden;
            animation: slideUp .25s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0
            }

            to {
                transform: translateY(0);
                opacity: 1
            }
        }

        .nv-modal-head {
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, #f8faff, #eef3ff);
            border-bottom: 1px solid #e8eeff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 800;
            font-size: .88rem;
            color: #1a3c5e;
        }

        .nv-modal-head i {
            color: #FF8C42;
            margin-right: .4rem;
        }

        .nv-modal-x {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: none;
            border: none;
            cursor: pointer;
            color: #bbb;
            font-size: .9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
        }

        .nv-modal-x:hover {
            background: #fee2e2;
            color: #e74c3c;
        }

        .nv-modal-body {
            padding: 1.25rem;
        }

        .nv-modal-foot {
            padding: .85rem 1.25rem;
            border-top: 1px solid #f5f5f5;
            display: flex;
            gap: .6rem;
            justify-content: flex-end;
            background: #fafbfc;
        }

        /* ── Form fields ── */
        .nv-fg {
            margin-bottom: 1rem;
        }

        .nv-fg:last-child {
            margin-bottom: 0;
        }

        .nv-fl {
            display: block;
            font-weight: 700;
            font-size: .74rem;
            color: #555;
            margin-bottom: .4rem;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .nv-fl.req::after {
            content: ' *';
            color: #e74c3c;
        }

        .nv-fi {
            width: 100%;
            height: 40px;
            border: 1.5px solid #e8eaef;
            border-radius: 8px;
            padding: 0 .85rem;
            font-size: .875rem;
            color: #333;
            background: #fafbfc;
            outline: none;
            font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
        }

        .nv-fi:focus {
            border-color: #FF8C42;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 140, 66, .1);
        }

        .nv-pw-wrap {
            position: relative;
        }

        .nv-pw-wrap .nv-fi {
            padding-right: 2.6rem;
        }

        .nv-pw-eye {
            position: absolute;
            right: .65rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #bbb;
            font-size: .82rem;
            padding: 0;
            transition: color .15s;
        }

        .nv-pw-eye:hover {
            color: #555;
        }

        .nv-fi-err {
            border-color: #e74c3c !important;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, .1) !important;
        }

        .nv-err-msg {
            font-size: .78rem;
            color: #e74c3c;
            margin-top: .35rem;
            display: none;
            align-items: center;
            gap: .3rem;
        }

        .nv-err-msg.show {
            display: flex;
        }

        /* ── Btn ── */
        .nv-btn-cancel {
            height: 38px;
            background: #f5f5f7;
            color: #666;
            border: 1.5px solid #e8e8e8;
            border-radius: 8px;
            padding: 0 1.1rem;
            font-weight: 600;
            font-size: .84rem;
            cursor: pointer;
            font-family: inherit;
            transition: all .2s;
        }

        .nv-btn-cancel:hover {
            background: #eee;
            color: #333;
        }

        .nv-btn-save {
            height: 38px;
            background: linear-gradient(135deg, #FF8C42, #e8721e);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0 1.25rem;
            font-weight: 700;
            font-size: .84rem;
            cursor: pointer;
            font-family: inherit;
            display: flex;
            align-items: center;
            gap: .4rem;
            box-shadow: 0 3px 12px rgba(255, 140, 66, .3);
            transition: all .2s;
        }

        .nv-btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 16px rgba(255, 140, 66, .4);
        }

        .nv-btn-save:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none;
        }

        /* ── Toast ── */
        .nv-toast {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            background: #1a3c5e;
            color: #fff;
            padding: .85rem 1.2rem;
            border-radius: 10px;
            font-size: .84rem;
            font-weight: 600;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: .6rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .18);
            animation: toastIn .3s ease;
            max-width: 320px;
        }

        .nv-toast.ok {
            background: #15803d;
        }

        .nv-toast.err {
            background: #dc2626;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateY(12px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        /* ── Responsive mobile card view ── */
        @media(max-width:640px) {
            .nv-tbl-wrap {
                display: none;
            }

            .nv-card-list {
                display: block;
            }
        }

        @media(min-width:641px) {
            .nv-card-list {
                display: none;
            }
        }

        .nv-card-list {
            padding: .5rem;
        }

        .nv-card {
            background: #fff;
            border: 1px solid #eef0f5;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: .75rem;
            box-shadow: 0 1px 6px rgba(0, 0, 0, .04);
        }

        .nv-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            margin-bottom: .75rem;
        }

        .nv-card-info {
            flex: 1;
            min-width: 0;
        }

        .nv-card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem .85rem;
            font-size: .78rem;
            color: #777;
            margin-bottom: .6rem;
        }

        .nv-card-meta i {
            color: #aaa;
            width: 13px;
        }

        .nv-card-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
            padding-top: .6rem;
            border-top: 1px solid #f5f6fa;
        }
    </style>
@endpush

@section('content')

    {{-- ══ STAT CARDS ══ --}}
    <div class="nv-stats">
        @php
            $stats = [
                [
                    'num' => $thongKe['tong'],
                    'lbl' => 'Tổng nhân viên',
                    'icon' => 'fa-users',
                    'bg' => '#e8f0ff',
                    'clr' => '#2563eb',
                ],
                [
                    'num' => $thongKe['admin'],
                    'lbl' => 'Quản trị viên',
                    'icon' => 'fa-crown',
                    'bg' => '#fef3c7',
                    'clr' => '#d97706',
                ],
                [
                    'num' => $thongKe['nguon_hang'],
                    'lbl' => 'Nguồn hàng',
                    'icon' => 'fa-building',
                    'bg' => '#f5f3ff',
                    'clr' => '#7c3aed',
                ],
                [
                    'num' => $thongKe['sale'],
                    'lbl' => 'Sale',
                    'icon' => 'fa-user-tie',
                    'bg' => '#ecfdf5',
                    'clr' => '#059669',
                ],
                [
                    'num' => $thongKe['kich_hoat'],
                    'lbl' => 'Đang hoạt động',
                    'icon' => 'fa-check-circle',
                    'bg' => '#f0fdf4',
                    'clr' => '#16a34a',
                ],
            ];
        @endphp
        @foreach ($stats as $s)
            <div class="nv-stat">
                <div class="nv-stat-icon" style="background:{{ $s['bg'] }};color:{{ $s['clr'] }}">
                    <i class="fas {{ $s['icon'] }}"></i>
                </div>
                <div>
                    <div class="nv-stat-num">{{ number_format($s['num']) }}</div>
                    <div class="nv-stat-lbl">{{ $s['lbl'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ══ PAGE HEADER ══ --}}
    <div class="nv-page-header">
        <div>
            <h1 class="nv-page-title">
                <i class="fas fa-id-badge"></i> Quản lý Nhân viên
            </h1>
            <p class="nv-page-sub">Quản lý tài khoản, vai trò & phân quyền toàn hệ thống</p>
        </div>
        <a href="{{ route('nhanvien.admin.nhan-vien.create') }}" class="nv-btn-add">
            <i class="fas fa-user-plus"></i> Thêm nhân viên
        </a>
    </div>

    {{-- ══ FILTER ══ --}}
    <div class="nv-filter">
        <form method="GET" id="nvForm">
            <div class="nv-filter-row">
                <input type="text" name="tukhoa" class="nv-ctrl nv-ctrl-search" value="{{ request('tukhoa') }}"
                    placeholder="Tìm tên, email, số điện thoại...">

                <select name="vai_tro" class="nv-ctrl">
                    <option value="">Tất cả vai trò</option>
                    @foreach (\App\Models\NhanVien::VAI_TRO as $v => $info)
                        <option value="{{ $v }}" @selected(request('vai_tro') == $v)>
                            {{ $info['label'] }}
                        </option>
                    @endforeach
                </select>

                <select name="kich_hoat" class="nv-ctrl">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" @selected(request('kich_hoat') === '1')>Đang hoạt động</option>
                    <option value="0" @selected(request('kich_hoat') === '0')>Vô hiệu hóa</option>
                </select>

                <select name="sapxep" class="nv-ctrl">
                    <option value="moi_nhat" @selected(request('sapxep', 'moi_nhat') == 'moi_nhat')>Mới nhất</option>
                    <option value="ten_az" @selected(request('sapxep') == 'ten_az')>Tên A → Z</option>
                    <option value="ten_za" @selected(request('sapxep') == 'ten_za')>Tên Z → A</option>
                    <option value="dang_nhap" @selected(request('sapxep') == 'dang_nhap')>Đăng nhập gần nhất</option>
                    <option value="bds_nhieu" @selected(request('sapxep') == 'bds_nhieu')>BĐS nhiều nhất</option>
                </select>

                <div style="display:flex;gap:.5rem;">
                    <button type="submit" class="nv-btn-filter">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                    @if (request()->hasAny(['tukhoa', 'vai_tro', 'kich_hoat', 'sapxep']))
                        <a href="{{ route('nhanvien.admin.nhan-vien.index') }}" class="nv-btn-reset">
                            <i class="fas fa-times"></i> Xóa lọc
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- ══ DATA BOX ══ --}}
    <div class="nv-box">
        <div class="nv-box-header">
            <span class="nv-result-info">
                @if ($nhanViens->total() > 0)
                    Hiển thị <strong>{{ $nhanViens->firstItem() }}–{{ $nhanViens->lastItem() }}</strong>
                    / <strong>{{ number_format($nhanViens->total()) }}</strong> nhân viên
                @else
                    Không có kết quả phù hợp
                @endif
            </span>
            <span style="font-size:.75rem;color:#c0c8d8">
                Trang {{ $nhanViens->currentPage() }}/{{ $nhanViens->lastPage() }}
            </span>
        </div>

        {{-- ─── TABLE — Desktop/Tablet ─── --}}
        <div class="nv-tbl-wrap">
            <table class="nv-tbl">
                <thead>
                    <tr>
                        <th style="width:42px;text-align:center">#</th>
                        <th>Nhân viên</th>
                        <th style="width:190px">Liên hệ</th>
                        <th style="width:130px">Vai trò</th>
                        <th style="width:80px;text-align:center">BĐS</th>
                        <th style="width:90px;text-align:center">Khách hàng</th>
                        <th style="width:145px">Đăng nhập cuối</th>
                        <th style="width:80px;text-align:center">Kích hoạt</th>
                        <th style="width:120px;text-align:center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nhanViens as $nv)
                        @php
                            $vt = \App\Models\NhanVien::VAI_TRO[$nv->vai_tro] ?? [
                                'label' => $nv->vai_tro,
                                'color' => '#888',
                                'bg' => '#f5f5f5',
                                'icon' => 'fas fa-user',
                            ];
                            $isMe = $nv->id === auth('nhanvien')->id();
                        @endphp
                        <tr class="{{ $isMe ? 'nv-row-me' : '' }}">

                            {{-- STT --}}
                            <td style="text-align:center;color:#ccc;font-size:.78rem">
                                {{ $nhanViens->firstItem() + $loop->index }}
                            </td>

                            {{-- Avatar + Tên --}}
                            <td>
                                <div class="nv-person">
                                    <div class="nv-ava-wrap">
                                        <img src="{{ $nv->anh_dai_dien_url }}" alt="{{ $nv->ho_ten }}" class="nv-ava"
                                            onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                        <span class="nv-dot {{ $nv->kich_hoat ? 'on' : 'off' }}"></span>
                                    </div>
                                    <div class="nv-pinfo">
                                        <a href="{{ route('nhanvien.admin.nhan-vien.edit', $nv) }}" class="nv-name">
                                            {{ $nv->ho_ten }}
                                            @if ($isMe)
                                                <span class="nv-me-tag">Bạn</span>
                                            @endif
                                        </a>
                                        <div class="nv-join">
                                            Tham gia {{ $nv->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Liên hệ --}}
                            <td>
                                <div class="nv-contact">
                                    <i class="fas fa-envelope" style="color:#3b82f6"></i>
                                    <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:155px"
                                        title="{{ $nv->email }}">{{ $nv->email }}</span>
                                </div>
                                @if ($nv->so_dien_thoai)
                                    <div class="nv-contact">
                                        <i class="fas fa-phone" style="color:#16a34a"></i>
                                        <a href="tel:{{ $nv->so_dien_thoai }}">{{ $nv->so_dien_thoai }}</a>
                                    </div>
                                @endif
                            </td>

                            {{-- Vai trò --}}
                            <td>
                                <span class="nv-role" style="color:{{ $vt['color'] }};background:{{ $vt['bg'] }}">
                                    <i class="{{ $vt['icon'] }}"></i>
                                    {{ $vt['label'] }}
                                </span>
                            </td>

                            {{-- BĐS --}}
                            <td style="text-align:center">
                                @if (!empty($nv->bat_dong_san_phu_trach_count) && $nv->bat_dong_san_phu_trach_count > 0)
                                    <a href="{{ route('nhanvien.admin.bat-dong-san.index', ['nhan_vien_id' => $nv->id]) }}"
                                        class="nv-count" style="color:#d97706;background:#fffbeb">
                                        {{ number_format($nv->bat_dong_san_phu_trach_count) }}
                                    </a>
                                @else
                                    <span class="nv-count-0">—</span>
                                @endif
                            </td>

                            {{-- Khách hàng --}}
                            <td style="text-align:center">
                                @if (!empty($nv->khach_hang_phu_trach_count) && $nv->khach_hang_phu_trach_count > 0)
                                    <a href="{{ route('nhanvien.admin.khach-hang.index', ['nhan_vien_id' => $nv->id]) }}"
                                        class="nv-count">
                                        {{ number_format($nv->khach_hang_phu_trach_count) }}
                                    </a>
                                @else
                                    <span class="nv-count-0">—</span>
                                @endif
                            </td>

                            {{-- Đăng nhập cuối --}}
                            <td>
                                @if ($nv->dang_nhap_cuoi_at)
                                    <div class="nv-login-time">{{ $nv->dang_nhap_cuoi_at->format('d/m/Y H:i') }}</div>
                                    <div class="nv-login-ago">{{ $nv->dang_nhap_cuoi_at->diffForHumans() }}</div>
                                @else
                                    <span class="nv-never">Chưa đăng nhập</span>
                                @endif
                            </td>

                            {{-- Toggle --}}
                            <td style="text-align:center">
                                <label class="nv-sw {{ $isMe ? 'dis' : '' }}"
                                    title="{{ $isMe ? 'Không thể tự vô hiệu hóa' : ($nv->kich_hoat ? 'Đang hoạt động' : 'Đã vô hiệu') }}">
                                    <input type="checkbox" class="nv-toggle" data-id="{{ $nv->id }}"
                                        {{ $nv->kich_hoat ? 'checked' : '' }} {{ $isMe ? 'disabled' : '' }}>
                                    <span class="nv-sw-track"><span class="nv-sw-thumb"></span></span>
                                </label>
                            </td>

                            {{-- Thao tác --}}
                            <td>
                                <div class="nv-acts">
                                    <a href="{{ route('nhanvien.admin.nhan-vien.show', $nv) }}" class="nv-act nv-act-v"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('nhanvien.admin.nhan-vien.edit', $nv) }}" class="nv-act nv-act-e"
                                        title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="nv-act nv-act-pw nv-pw-btn"
                                        data-id="{{ $nv->id }}" data-ten="{{ $nv->ho_ten }}"
                                        title="Đổi mật khẩu">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    @if (!$isMe)
                                        <form action="{{ route('nhanvien.admin.nhan-vien.destroy', $nv) }}"
                                            method="POST" style="margin:0">
                                            @csrf @method('DELETE')
                                            <button type="button" class="nv-act nv-act-d nv-del-btn"
                                                data-ten="{{ $nv->ho_ten }}" title="Xóa nhân viên">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="nv-empty">
                                    <div class="nv-empty-icon"><i class="fas fa-id-badge"></i></div>
                                    <p>Không tìm thấy nhân viên nào</p>
                                    @if (request()->hasAny(['tukhoa', 'vai_tro', 'kich_hoat']))
                                        <a href="{{ route('nhanvien.admin.nhan-vien.index') }}">
                                            <i class="fas fa-times-circle"></i> Xóa bộ lọc
                                        </a>
                                    @else
                                        <a href="{{ route('nhanvien.admin.nhan-vien.create') }}">
                                            <i class="fas fa-user-plus"></i> Thêm nhân viên đầu tiên
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ─── CARD LIST — Mobile ─── --}}
        <div class="nv-card-list">
            @forelse($nhanViens as $nv)
                @php
                    $vt = \App\Models\NhanVien::VAI_TRO[$nv->vai_tro] ?? [
                        'label' => $nv->vai_tro,
                        'color' => '#888',
                        'bg' => '#f5f5f5',
                        'icon' => 'fas fa-user',
                    ];
                    $isMe = $nv->id === auth('nhanvien')->id();
                @endphp
                <div class="nv-card {{ $isMe ? '' : '' }}">
                    <div class="nv-card-top">
                        <div class="nv-ava-wrap">
                            <img src="{{ $nv->anh_dai_dien_url }}" alt="{{ $nv->ho_ten }}" class="nv-ava"
                                style="width:48px;height:48px"
                                onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                            <span class="nv-dot {{ $nv->kich_hoat ? 'on' : 'off' }}"></span>
                        </div>
                        <div class="nv-card-info">
                            <div
                                style="font-weight:700;color:#1a3c5e;font-size:.88rem;display:flex;align-items:center;gap:.4rem;flex-wrap:wrap">
                                {{ $nv->ho_ten }}
                                @if ($isMe)
                                    <span class="nv-me-tag">Bạn</span>
                                @endif
                            </div>
                            <span class="nv-role"
                                style="color:{{ $vt['color'] }};background:{{ $vt['bg'] }};margin-top:.3rem">
                                <i class="{{ $vt['icon'] }}"></i> {{ $vt['label'] }}
                            </span>
                        </div>
                        <label class="nv-sw {{ $isMe ? 'dis' : '' }}">
                            <input type="checkbox" class="nv-toggle" data-id="{{ $nv->id }}"
                                {{ $nv->kich_hoat ? 'checked' : '' }} {{ $isMe ? 'disabled' : '' }}>
                            <span class="nv-sw-track"><span class="nv-sw-thumb"></span></span>
                        </label>
                    </div>
                    <div class="nv-card-meta">
                        <span><i class="fas fa-envelope" style="color:#3b82f6"></i>
                            {{ Str::limit($nv->email, 26) }}</span>
                        @if ($nv->so_dien_thoai)
                            <span><i class="fas fa-phone" style="color:#16a34a"></i> {{ $nv->so_dien_thoai }}</span>
                        @endif
                        @if ($nv->dang_nhap_cuoi_at)
                            <span><i class="fas fa-clock" style="color:#aaa"></i>
                                {{ $nv->dang_nhap_cuoi_at->diffForHumans() }}</span>
                        @endif
                    </div>
                    <div class="nv-card-foot">
                        <div style="display:flex;gap:.5rem;align-items:center;font-size:.75rem;color:#aaa">
                            <span>BĐS: <b style="color:#d97706">{{ $nv->bat_dong_san_phu_trach_count ?? 0 }}</b></span>
                            <span>KH: <b style="color:#2563eb">{{ $nv->khach_hang_phu_trach_count ?? 0 }}</b></span>
                        </div>
                        <div class="nv-acts">
                            <a href="{{ route('nhanvien.admin.nhan-vien.show', $nv) }}" class="nv-act nv-act-v"
                                title="Xem">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('nhanvien.admin.nhan-vien.edit', $nv) }}" class="nv-act nv-act-e"
                                title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="nv-act nv-act-pw nv-pw-btn" data-id="{{ $nv->id }}"
                                data-ten="{{ $nv->ho_ten }}" title="Đổi mật khẩu">
                                <i class="fas fa-key"></i>
                            </button>
                            @if (!$isMe)
                                <form action="{{ route('nhanvien.admin.nhan-vien.destroy', $nv) }}" method="POST"
                                    style="margin:0">
                                    @csrf @method('DELETE')
                                    <button type="button" class="nv-act nv-act-d nv-del-btn"
                                        data-ten="{{ $nv->ho_ten }}" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="nv-empty" style="padding:2.5rem 1rem">
                    <div class="nv-empty-icon"><i class="fas fa-id-badge"></i></div>
                    <p>Không có nhân viên nào</p>
                </div>
            @endforelse
        </div>

        {{-- ─── PAGINATION ─── --}}
        @if ($nhanViens->hasPages())
            @php
                $cur = $nhanViens->currentPage();
                $last = $nhanViens->lastPage();
            @endphp
            <div class="nv-pagi">
                <div class="nv-pagi-info">
                    Trang {{ $cur }} / {{ $last }}
                    &nbsp;·&nbsp; {{ number_format($nhanViens->total()) }} nhân viên
                </div>
                <div class="nv-pagi-links">
                    @if ($nhanViens->onFirstPage())
                        <span class="nv-pb dis"><i class="fas fa-angle-double-left"></i></span>
                        <span class="nv-pb dis"><i class="fas fa-angle-left"></i></span>
                    @else
                        <a href="{{ $nhanViens->url(1) }}" class="nv-pb"><i class="fas fa-angle-double-left"></i></a>
                        <a href="{{ $nhanViens->previousPageUrl() }}" class="nv-pb"><i
                                class="fas fa-angle-left"></i></a>
                    @endif

                    @php
                        $s = max(1, $cur - 2);
                        $e = min($last, $cur + 2);
                    @endphp
                    @if ($s > 1)
                        <a href="{{ $nhanViens->url(1) }}" class="nv-pb">1</a>
                    @endif
                    @if ($s > 2)
                        <span class="nv-dots">…</span>
                    @endif
                    @for ($p = $s; $p <= $e; $p++)
                        @if ($p == $cur)
                            <span class="nv-pb act">{{ $p }}</span>
                        @else
                            <a href="{{ $nhanViens->url($p) }}" class="nv-pb">{{ $p }}</a>
                        @endif
                    @endfor
                    @if ($e < $last - 1)
                        <span class="nv-dots">…</span>
                    @endif
                    @if ($e < $last)
                        <a href="{{ $nhanViens->url($last) }}" class="nv-pb">{{ $last }}</a>
                    @endif

                    @if ($nhanViens->hasMorePages())
                        <a href="{{ $nhanViens->nextPageUrl() }}" class="nv-pb"><i class="fas fa-angle-right"></i></a>
                        <a href="{{ $nhanViens->url($last) }}" class="nv-pb"><i
                                class="fas fa-angle-double-right"></i></a>
                    @else
                        <span class="nv-pb dis"><i class="fas fa-angle-right"></i></span>
                        <span class="nv-pb dis"><i class="fas fa-angle-double-right"></i></span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- ══ MODAL ĐỔI MẬT KHẨU ══ --}}
    <div id="pwModal" class="nv-overlay" style="display:none">
        <div class="nv-modal">
            <div class="nv-modal-head">
                <span><i class="fas fa-key"></i> Đổi mật khẩu — <span id="pwName"></span></span>
                <button type="button" class="nv-modal-x" onclick="closePw()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="nv-modal-body">
                <div class="nv-fg">
                    <label class="nv-fl req">Mật khẩu mới</label>
                    <div class="nv-pw-wrap">
                        <input type="password" id="pw1" class="nv-fi" placeholder="Tối thiểu 6 ký tự"
                            autocomplete="new-password">
                        <button type="button" class="nv-pw-eye" onclick="togglePwEye('pw1','eye1')">
                            <i class="fas fa-eye" id="eye1"></i>
                        </button>
                    </div>
                </div>
                <div class="nv-fg">
                    <label class="nv-fl req">Xác nhận mật khẩu</label>
                    <div class="nv-pw-wrap">
                        <input type="password" id="pw2" class="nv-fi" placeholder="Nhập lại mật khẩu"
                            autocomplete="new-password">
                        <button type="button" class="nv-pw-eye" onclick="togglePwEye('pw2','eye2')">
                            <i class="fas fa-eye" id="eye2"></i>
                        </button>
                    </div>
                </div>
                <div class="nv-err-msg" id="pwErr">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="pwErrMsg"></span>
                </div>
            </div>
            <div class="nv-modal-foot">
                <button type="button" class="nv-btn-cancel" onclick="closePw()">Hủy</button>
                <button type="button" class="nv-btn-save" id="pwSaveBtn" onclick="savePw()">
                    <i class="fas fa-save"></i> Lưu mật khẩu
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        (function() {
            const CSRF = document.querySelector('meta[name=csrf-token]').content;
            let pwId = null;

            /* ── 1. Toggle kích hoạt ── */
            document.querySelectorAll('.nv-toggle').forEach(chk => {
                chk.addEventListener('change', function() {
                    const id = this.dataset.id,
                        self = this;
                    fetch(`/nhan-vien/admin/nhan-vien/${id}/toggle`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': CSRF,
                                'Accept': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(d => {
                            if (!d.ok) {
                                self.checked = !self.checked;
                                showToast(d.msg || 'Không thể thực hiện!', 'err');
                            } else {
                                showToast(d.msg || 'Đã cập nhật!', 'ok');
                                // Cập nhật dot màu
                                const wrap = self.closest('tr,div.nv-card');
                                if (wrap) {
                                    const dot = wrap.querySelector('.nv-dot');
                                    if (dot) {
                                        dot.className = 'nv-dot ' + (self.checked ? 'on' : 'off');
                                    }
                                }
                            }
                        })
                        .catch(() => {
                            self.checked = !self.checked;
                            showToast('Lỗi kết nối!', 'err');
                        });
                });
            });

            /* ── 2. Modal đổi mật khẩu ── */
            document.querySelectorAll('.nv-pw-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    pwId = this.dataset.id;
                    document.getElementById('pwName').textContent = this.dataset.ten;
                    document.getElementById('pw1').value = '';
                    document.getElementById('pw2').value = '';
                    document.getElementById('pwErr').classList.remove('show');
                    document.getElementById('pw1').classList.remove('nv-fi-err');
                    document.getElementById('pw2').classList.remove('nv-fi-err');
                    document.getElementById('pwModal').style.display = 'flex';
                    setTimeout(() => document.getElementById('pw1').focus(), 120);
                });
            });

            window.closePw = function() {
                document.getElementById('pwModal').style.display = 'none';
                pwId = null;
            };

            document.getElementById('pwModal').addEventListener('click', function(e) {
                if (e.target === this) closePw();
            });

            window.togglePwEye = function(inputId, iconId) {
                const inp = document.getElementById(inputId);
                const ico = document.getElementById(iconId);
                inp.type = inp.type === 'password' ? 'text' : 'password';
                ico.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            };

            window.savePw = function() {
                const p1 = document.getElementById('pw1').value.trim();
                const p2 = document.getElementById('pw2').value.trim();
                const btn = document.getElementById('pwSaveBtn');

                document.getElementById('pw1').classList.remove('nv-fi-err');
                document.getElementById('pw2').classList.remove('nv-fi-err');
                document.getElementById('pwErr').classList.remove('show');

                if (!p1) {
                    showPwErr('Vui lòng nhập mật khẩu mới!');
                    document.getElementById('pw1').classList.add('nv-fi-err');
                    return;
                }
                if (p1.length < 6) {
                    showPwErr('Mật khẩu tối thiểu 6 ký tự!');
                    document.getElementById('pw1').classList.add('nv-fi-err');
                    return;
                }
                if (p1 !== p2) {
                    showPwErr('Mật khẩu xác nhận không khớp!');
                    document.getElementById('pw2').classList.add('nv-fi-err');
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';

                fetch(`/nhan-vien/admin/nhan-vien/${pwId}/doi-mat-khau`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            mat_khau_moi: p1,
                            xac_nhan_mat_khau: p2
                        })
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.ok) {
                            closePw();
                            showToast('✅ ' + (d.msg || 'Đã đổi mật khẩu!'), 'ok');
                        } else showPwErr(d.message || 'Có lỗi xảy ra!');
                    })
                    .catch(() => showPwErr('Không thể kết nối server!'))
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-save"></i> Lưu mật khẩu';
                    });
            };

            function showPwErr(msg) {
                document.getElementById('pwErrMsg').textContent = msg;
                document.getElementById('pwErr').classList.add('show');
            }

            /* ── 3. Confirm xóa ── */
            document.querySelectorAll('.nv-del-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (confirm(
                            `Xóa nhân viên "${this.dataset.ten}"?\nThao tác này không thể hoàn tác!`)) {
                        this.closest('form').submit();
                    }
                });
            });

            /* ── 4. Auto-submit select ── */
            document.querySelectorAll('#nvForm select').forEach(s => {
                s.addEventListener('change', () => document.getElementById('nvForm').submit());
            });

            /* ── 5. ESC đóng modal ── */
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closePw();
            });

            /* ── 6. Toast ── */
            window.showToast = function(msg, type = 'ok') {
                const t = document.createElement('div');
                t.className = 'nv-toast ' + type;
                t.innerHTML = msg;
                document.body.appendChild(t);
                setTimeout(() => {
                    t.style.opacity = '0';
                    t.style.transition = 'opacity .4s';
                }, 2600);
                setTimeout(() => t.remove(), 3100);
            };
        })();
    </script>
@endpush
