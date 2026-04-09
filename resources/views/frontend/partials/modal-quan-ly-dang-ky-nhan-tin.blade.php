@auth('customer')
    @php
        $dangKyNhanTinList = \App\Models\DangKyNhanTin::query()
            ->with(['khuVuc:id,ten_khu_vuc', 'duAn:id,ten_du_an', 'batDongSan:id,tieu_de'])
            ->where('khach_hang_id', auth('customer')->id())
            ->dangHoatDong()
            ->orderByDesc('updated_at')
            ->get();
    @endphp

    <div class="kh-modal-backdrop" id="dkQtBackdrop" onclick="closeModalQuanLyDangKyNhanTin()"></div>

    <div class="kh-modal" id="dkQtModal" style="max-width: 760px; width: calc(100% - 24px); max-height: 86vh;">
        <div class="kh-modal-header">
            <div class="kh-modal-icon"
                style="background: linear-gradient(135deg, #f59e0b, #ea580c); box-shadow: 0 4px 10px rgba(234, 88, 12, .28);">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <div class="kh-modal-title">Quản lý đăng ký nhận tin</div>
                <div class="kh-modal-sub">Theo dõi tiêu chí và hủy các đăng ký không còn nhu cầu</div>
            </div>
            <button class="kh-modal-close" onclick="closeModalQuanLyDangKyNhanTin()" title="Đóng cửa sổ">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="kh-modal-body" style="padding: 1rem; overflow-y: auto;">
            <div class="dkqt-list" id="dkqtListWrap">
                @forelse ($dangKyNhanTinList as $dk)
                    <article class="dkqt-item" id="dkqt-item-{{ $dk->id }}">
                        <div class="dkqt-item-head">
                            <span class="dkqt-badge">{{ $dk->nhu_cau_label }}</span>
                            <small>Cập nhật: {{ optional($dk->updated_at)->format('d/m/Y H:i') }}</small>
                        </div>

                        <div class="dkqt-grid">
                            <div><strong>Email:</strong> {{ $dk->email }}</div>
                            <div><strong>Phòng ngủ:</strong> {{ $dk->so_phong_ngu_label }}</div>
                            <div><strong>Khu vực:</strong> {{ $dk->khuVuc->ten_khu_vuc ?? 'Bất kỳ' }}</div>
                            <div><strong>Dự án:</strong> {{ $dk->duAn->ten_du_an ?? 'Bất kỳ' }}</div>
                            <div><strong>Khoảng giá:</strong> {{ $dk->khoang_gia_label }}</div>
                            <div><strong>Loại theo dõi:</strong>
                                {{ $dk->batDongSan?->tieu_de ? 'Theo BĐS cụ thể' : 'Theo tiêu chí chung' }}</div>
                        </div>

                        @if ($dk->batDongSan?->tieu_de)
                            <div class="dkqt-pin">
                                <i class="fas fa-home me-1"></i>
                                <strong>BĐS:</strong> {{ $dk->batDongSan->tieu_de }}
                            </div>
                        @endif

                        <div class="dkqt-actions">
                            <button type="button" class="dkqt-btn-remove"
                                onclick="openConfirmHuyDangKy({{ $dk->id }})">
                                <i class="fas fa-trash-alt me-1"></i> Hủy đăng ký
                            </button>
                        </div>

                        <div class="dkqt-confirm" id="dkqt-confirm-{{ $dk->id }}">
                            <div class="dkqt-confirm-text">
                                <i class="fas fa-triangle-exclamation"></i>
                                Bạn chắc chắn muốn hủy đăng ký này?
                            </div>
                            <div class="dkqt-confirm-actions">
                                <button type="button" class="dkqt-btn-cancel"
                                    onclick="cancelConfirmHuyDangKy({{ $dk->id }})">
                                    Giữ lại
                                </button>
                                <button type="button" class="dkqt-btn-confirm"
                                    onclick="huyDangKyNhanTin({{ $dk->id }}, this)">
                                    <i class="fas fa-trash-alt me-1"></i> Xác nhận hủy
                                </button>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="dkqt-empty">
                        <i class="far fa-bell-slash"></i>
                        <p>Bạn chưa có đăng ký nhận tin nào đang hoạt động.</p>
                        <button type="button" class="btn btn-sm btn-primary-brand" onclick="openModalDangKy()">
                            <i class="fas fa-plus-circle me-1"></i>Tạo đăng ký mới
                        </button>
                    </div>
                @endforelse
            </div>

            @if ($dangKyNhanTinList->isNotEmpty())
                <div class="dkqt-empty" id="dkqtEmptyState" style="display:none;">
                    <i class="far fa-bell-slash"></i>
                    <p>Bạn đã hủy hết đăng ký nhận tin.</p>
                    <button type="button" class="btn btn-sm btn-primary-brand"
                        onclick="closeModalQuanLyDangKyNhanTin(); openModalDangKy();">
                        <i class="fas fa-plus-circle me-1"></i>Tạo đăng ký mới
                    </button>
                </div>
            @endif
        </div>
    </div>

    <style>
        .dkqt-list {
            display: flex;
            flex-direction: column;
            gap: .85rem;
        }

        .dkqt-item {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: .9rem;
            background: linear-gradient(180deg, #fff, #fffdf8);
        }

        .dkqt-item-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .7rem;
            margin-bottom: .7rem;
        }

        .dkqt-item-head small {
            color: #64748b;
            font-size: .75rem;
        }

        .dkqt-badge {
            display: inline-flex;
            align-items: center;
            padding: .24rem .62rem;
            border-radius: 999px;
            background: #fff7ed;
            color: #c2410c;
            font-weight: 700;
            font-size: .72rem;
            border: 1px solid #fed7aa;
        }

        .dkqt-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .46rem .9rem;
            font-size: .83rem;
            color: #334155;
        }

        .dkqt-pin {
            margin-top: .65rem;
            padding: .58rem .68rem;
            border-radius: 10px;
            background: #f8fafc;
            color: #0f172a;
            font-size: .82rem;
            border: 1px dashed #cbd5e1;
        }

        .dkqt-actions {
            margin-top: .8rem;
            display: flex;
            justify-content: flex-end;
        }

        .dkqt-btn-remove {
            border: 1px solid #fecaca;
            background: #fff5f5;
            color: #b91c1c;
            border-radius: 10px;
            padding: .45rem .72rem;
            font-size: .8rem;
            font-weight: 700;
        }

        .dkqt-btn-remove:hover {
            background: #fee2e2;
        }

        .dkqt-empty {
            text-align: center;
            color: #64748b;
            padding: 2.2rem 1rem;
            border: 1px dashed #cbd5e1;
            border-radius: 14px;
            background: #f8fafc;
        }

        .dkqt-empty i {
            font-size: 1.7rem;
            margin-bottom: .5rem;
            color: #94a3b8;
        }

        .dkqt-empty p {
            margin-bottom: .85rem;
            font-size: .9rem;
        }

        .dkqt-confirm {
            margin-top: .72rem;
            border: 1px solid #fde68a;
            background: linear-gradient(180deg, #fffdf2, #fff7ed);
            border-radius: 12px;
            padding: .68rem .75rem;
            display: none;
        }

        .dkqt-confirm.show {
            display: block;
            animation: dkqtFadeIn .18s ease-out;
        }

        .dkqt-confirm-text {
            font-size: .82rem;
            color: #92400e;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: .42rem;
            margin-bottom: .58rem;
        }

        .dkqt-confirm-actions {
            display: flex;
            justify-content: flex-end;
            gap: .5rem;
        }

        .dkqt-btn-cancel,
        .dkqt-btn-confirm {
            border-radius: 9px;
            padding: .4rem .72rem;
            font-size: .78rem;
            font-weight: 700;
            border: 1px solid transparent;
            transition: all .2s ease;
        }

        .dkqt-btn-cancel {
            background: #f8fafc;
            color: #334155;
            border-color: #e2e8f0;
        }

        .dkqt-btn-cancel:hover {
            background: #eef2f7;
        }

        .dkqt-btn-confirm {
            background: #dc2626;
            color: #fff;
            border-color: #dc2626;
        }

        .dkqt-btn-confirm:hover {
            background: #b91c1c;
            border-color: #b91c1c;
        }

        @keyframes dkqtFadeIn {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 767px) {
            .dkqt-grid {
                grid-template-columns: 1fr;
            }

            .dkqt-actions {
                justify-content: stretch;
            }

            .dkqt-btn-remove {
                width: 100%;
            }

            .dkqt-confirm-actions {
                justify-content: stretch;
            }

            .dkqt-btn-cancel,
            .dkqt-btn-confirm {
                flex: 1;
            }
        }
    </style>

    <script>
        window.openModalQuanLyDangKyNhanTin = function() {
            document.getElementById('dkQtBackdrop')?.classList.add('show');
            document.getElementById('dkQtModal')?.classList.add('show');
            document.body.style.overflow = 'hidden';
        };

        window.closeModalQuanLyDangKyNhanTin = function() {
            document.getElementById('dkQtBackdrop')?.classList.remove('show');
            document.getElementById('dkQtModal')?.classList.remove('show');
            document.body.style.overflow = '';
            document.querySelectorAll('.dkqt-confirm.show').forEach(el => el.classList.remove('show'));
        };

        window.openConfirmHuyDangKy = function(id) {
            document.querySelectorAll('.dkqt-confirm.show').forEach(el => {
                if (el.id !== 'dkqt-confirm-' + id) {
                    el.classList.remove('show');
                }
            });

            document.getElementById('dkqt-confirm-' + id)?.classList.add('show');
        };

        window.cancelConfirmHuyDangKy = function(id) {
            document.getElementById('dkqt-confirm-' + id)?.classList.remove('show');
        };

        window.huyDangKyNhanTin = async function(id, btn) {
            const oldHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang hủy...';

            try {
                const endpoint = window.APP.routes.dangKyNhanTinDestroy.replace('__ID__', String(id));
                const response = await fetch(endpoint, {
                    method: 'DELETE',
                    headers: window.getCsrfHeaders ? window.getCsrfHeaders({
                        'Accept': 'application/json'
                    }) : {
                        'X-CSRF-TOKEN': window.APP.csrfToken,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Hủy đăng ký thất bại.');
                }

                const item = document.getElementById('dkqt-item-' + id);
                if (item) {
                    item.remove();
                }

                if (typeof showFlash === 'function') {
                    showFlash(data.message || 'Đã hủy đăng ký thành công.', 'success');
                }

                const leftItems = document.querySelectorAll('.dkqt-item').length;
                if (leftItems === 0) {
                    document.getElementById('dkqtEmptyState')?.style.setProperty('display', 'block');
                }
            } catch (error) {
                if (typeof showFlash === 'function') {
                    showFlash(error.message || 'Có lỗi xảy ra khi hủy đăng ký.', 'danger');
                }
                btn.disabled = false;
                btn.innerHTML = oldHtml;
                return;
            }

            btn.disabled = false;
            btn.innerHTML = oldHtml;
        };
    </script>
@endauth
