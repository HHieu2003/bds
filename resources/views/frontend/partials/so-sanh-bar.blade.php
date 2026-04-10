    {{-- ══════════════════════════════════════
     SO SÁNH — Floating Bar + Modal Popup
     JS: addSoSanh(id, ten) | removeSoSanh(id)
         clearSoSanh() | openSoSanhModal()
══════════════════════════════════════ --}}
    <style>
        /* ── Floating Bar ── */
        #so-sanh-bar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--secondary-dark);
            color: #fff;
            padding: .8rem 1.5rem;
            z-index: 9999;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, .2);
            animation: slideUpBar .3s ease;
        }

        @keyframes slideUpBar {
            from {
                transform: translateY(100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .ss-bar-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .8rem;
        }

        .ss-bar-left {
            display: flex;
            align-items: center;
            gap: .8rem;
            flex-wrap: wrap;
        }

        .ss-bar-title {
            font-weight: 700;
            font-size: .85rem;
        }

        .ss-bar-title i {
            color: var(--primary);
        }

        /* Chip từng BĐS trong bar */
        .ss-item-chip {
            background: rgba(255, 255, 255, .15);
            padding: .25rem .7rem;
            border-radius: 20px;
            font-size: .75rem;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            border: 1px solid rgba(255, 255, 255, .2);
        }

        .ss-item-remove {
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            font-size: .7rem;
            padding: 0;
            opacity: .7;
            transition: opacity var(--transition);
        }

        .ss-item-remove:hover {
            opacity: 1;
        }

        /* Nút hành động */
        .ss-bar-actions {
            display: flex;
            gap: .6rem;
        }

        .ss-btn-compare {
            background: linear-gradient(135deg, #d9834a, var(--primary));
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: .45rem 1rem;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: transform var(--transition), box-shadow var(--transition);
        }

        .ss-btn-compare:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(192, 102, 42, .4);
        }

        .ss-btn-page {
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .3);
            color: #fff;
            padding: .45rem .9rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: .82rem;
            font-family: inherit;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: background var(--transition);
        }

        .ss-btn-page:hover {
            background: rgba(255, 255, 255, .25);
            color: #fff;
        }

        .ss-btn-clear {
            background: rgba(198, 40, 40, .2);
            border: 1px solid rgba(198, 40, 40, .35);
            color: #ffb3ae;
            padding: .45rem .9rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: .82rem;
            font-family: inherit;
            transition: background var(--transition);
        }

        .ss-btn-clear:hover {
            background: rgba(198, 40, 40, .4);
            color: #fff;
        }

        /* Modal header & badge */
        #soSanhModal .modal-header {
            background: var(--secondary);
        }

        #soSanhModal .modal-header .badge {
            background: var(--primary) !important;
            font-size: .7rem;
        }

        @media (max-width: 576px) {
            .ss-btn-page {
                display: none;
            }

            .ss-bar-title span {
                display: none;
            }
        }
    </style>

    {{-- ── Floating Bar ── --}}
    <div id="so-sanh-bar">
        <div class="ss-bar-inner">
            <div class="ss-bar-left">
                <span class="ss-bar-title">
                    <i class="fas fa-balance-scale"></i>
                    <span>So sánh:</span>
                    <strong id="ssSanhCount">0</strong>/3 BĐS
                </span>
                <div id="ssSanhItems"></div>
            </div>
            <div class="ss-bar-actions">
                <button type="button" class="ss-btn-compare" onclick="openSoSanhModal()">
                    <i class="fas fa-chart-bar"></i> So sánh ngay
                </button>
                <button type="button" class="ss-btn-page" onclick="moTrangSoSanh()">
                    <i class="fas fa-expand-alt"></i> Xem đầy đủ
                </button>
                <button type="button" class="ss-btn-clear" onclick="clearSoSanh()">
                    <i class="fas fa-trash"></i> Xoá tất cả
                </button>
            </div>
        </div>
    </div>

    {{-- ── Modal Popup So Sánh ── --}}
    <div class="modal fade" id="soSanhModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4" style="box-shadow: 0 24px 60px rgba(0,0,0,.18)">

                <div class="modal-header border-0 p-3">
                    <h5 class="modal-title fw-bold mb-0 text-white">
                        <i class="fas fa-balance-scale me-2" style="color: var(--primary)"></i>
                        Bảng So Sánh Chi Tiết
                        <span class="badge ms-2" id="soSanhModalCount"></span>
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="ss-btn-page" onclick="moTrangSoSanh()">
                            <i class="fas fa-expand-alt"></i> Xem đầy đủ
                        </button>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                </div>

                <div class="modal-body p-0" id="soSanhModalBody">
                    <div class="text-center py-5" id="soSanhLoading">
                        <div class="spinner-border" style="color: var(--primary)" role="status"></div>
                        <p class="mt-2 text-muted fw-bold">Đang tải dữ liệu so sánh...</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
