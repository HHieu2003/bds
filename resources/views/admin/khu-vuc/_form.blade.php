@php
    $isEdit = !is_null($khuVuc);
    $oldCap = old('cap_khu_vuc', $capMacDinh ?? 'quan_huyen');
    $oldChaId = old('khu_vuc_cha_id', $chaMacDinh ?? '');
@endphp

<div class="row g-4">
    {{-- ════ CỘT TRÁI (Nội dung chính) ════ --}}
    <div class="col-12 col-lg-8">

        {{-- Card Thông tin --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><i class="fas fa-info-circle text-primary me-2"></i>Thông tin cơ bản</div>
            <div class="card-body">

                {{-- Cấp khu vực --}}
                <div class="mb-4">
                    <label class="form-label">Cấp hành chính <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        @foreach (\App\Models\KhuVuc::CAP as $v => $info)
                            <div class="col-4">
                                <label
                                    class="role-card h-100 flex-column text-center justify-content-center {{ $oldCap == $v ? 'selected' : '' }}"
                                    style="padding: 1rem 0.5rem">
                                    <input type="radio" name="cap_khu_vuc" value="{{ $v }}"
                                        class="d-none kv-cap-radio" {{ $oldCap == $v ? 'checked' : '' }}>
                                    <i class="{{ $info['icon'] }} fs-4 mb-2" style="color: {{ $info['color'] }}"></i>
                                    <span class="role-card-name">{{ $info['label'] }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('cap_khu_vuc')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    {{-- Tên khu vực --}}
                    <div class="col-12">
                        <label class="form-label">Tên khu vực <span class="text-danger">*</span></label>
                        <input type="text" name="ten_khu_vuc" id="kvTenInput"
                            class="form-control @error('ten_khu_vuc') is-invalid @enderror"
                            value="{{ old('ten_khu_vuc', $isEdit ? $khuVuc->ten_khu_vuc : '') }}"
                            placeholder="VD: Quận 1, Hà Nội...">
                        <div class="form-hint">Slug: <span id="slugPreview"
                                class="text-primary font-monospace">{{ $isEdit ? $khuVuc->slug : '' }}</span></div>
                        @error('ten_khu_vuc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Mô tả --}}
                <div class="mb-0">
                    <label class="form-label">Mô tả khu vực</label>
                    <textarea name="mo_ta" class="form-control" rows="4" placeholder="Đặc điểm khu vực, thị trường BĐS...">{{ old('mo_ta', $isEdit ? $khuVuc->mo_ta : '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Card SEO --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><i class="fas fa-search text-success me-2"></i>Tối ưu SEO</div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label mb-0">SEO Title</label>
                        <span id="seoTitleCount" class="badge bg-light text-secondary">0/70</span>
                    </div>
                    <input type="text" name="seo_title" id="seoTitle" class="form-control" maxlength="70"
                        value="{{ old('seo_title', $isEdit ? $khuVuc->seo_title : '') }}">
                    <div class="progress mt-1" style="height: 4px;">
                        <div id="seoTitleBar" class="progress-bar bg-success" style="width: 0%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label mb-0">SEO Description</label>
                        <span id="seoDescCount" class="badge bg-light text-secondary">0/160</span>
                    </div>
                    <textarea name="seo_description" id="seoDesc" class="form-control" rows="3" maxlength="160">{{ old('seo_description', $isEdit ? $khuVuc->seo_description : '') }}</textarea>
                    <div class="progress mt-1" style="height: 4px;">
                        <div id="seoDescBar" class="progress-bar bg-success" style="width: 0%"></div>
                    </div>
                </div>

                <div class="mb-0">
                    <label class="form-label">SEO Keywords</label>
                    <input type="text" name="seo_keywords" class="form-control"
                        value="{{ old('seo_keywords', $isEdit ? $khuVuc->seo_keywords : '') }}"
                        placeholder="Cách nhau bởi dấu phẩy">
                </div>
            </div>
        </div>
    </div>

    {{-- ════ CỘT PHẢI (Cài đặt) ════ --}}
    <div class="col-12 col-lg-4">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <button type="submit" class="btn btn-primary w-100 mb-2 py-2">
                    <i class="fas fa-save me-1"></i> {{ $isEdit ? 'Lưu thay đổi' : 'Tạo khu vực' }}
                </button>
                <a href="{{ route('nhanvien.admin.khu-vuc.index') }}" class="btn btn-light border w-100 py-2">Hủy
                    bỏ</a>
            </div>
        </div>

        {{-- Phân cấp cha --}}
        <div class="card border-0 shadow-sm mb-4" id="kv-cha-box">
            <div class="card-header bg-white"><i class="fas fa-sitemap text-info me-2"></i><span id="kvChaTitle">Thuộc
                    khu vực</span></div>
            <div class="card-body">
                <div class="mb-3" id="kv-chon-tinh">
                    <label class="form-label">Tỉnh / Thành phố</label>
                    <select name="khu_vuc_cha_id" id="selectTinh" class="form-select">
                        <option value="">— Không chọn —</option>
                        @foreach ($tinhThanhs as $tinh)
                            <option value="{{ $tinh->id }}" @selected((string) $oldChaId === (string) $tinh->id)>{{ $tinh->ten_khu_vuc }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-0" id="kv-chon-quan" style="display:none">
                    <label class="form-label">Quận / Huyện</label>
                    <select name="khu_vuc_cha_id" id="selectQuan" class="form-select" disabled>
                        <option value="">— Chọn quận/huyện —</option>
                        @foreach ($quanHuyens as $quan)
                            <option value="{{ $quan->id }}" @selected((string) $oldChaId === (string) $quan->id)>{{ $quan->ten_khu_vuc }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-hint mt-1"><i class="fas fa-info-circle"></i> Vui lòng chọn Tỉnh/Thành trước.
                    </div>
                </div>
                @error('khu_vuc_cha_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Cấu hình hiển thị --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white"><i class="fas fa-cog text-secondary me-2"></i>Thiết lập</div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                    <div>
                        <div class="fw-bold text-dark">Hiển thị</div>
                        <div class="text-muted" style="font-size: 0.8rem">Công khai trên Web</div>
                    </div>
                    <label class="toggle-sw">
                        <input type="checkbox" name="hien_thi" value="1"
                            {{ old('hien_thi', $isEdit ? $khuVuc->hien_thi : true) ? 'checked' : '' }}>
                        <span class="toggle-sw-track"><span class="toggle-sw-thumb"></span></span>
                    </label>
                </div>
                <div class="mb-0">
                    <label class="form-label">Thứ tự ưu tiên</label>
                    <input type="number" name="thu_tu_hien_thi" class="form-control"
                        value="{{ old('thu_tu_hien_thi', $isEdit ? $khuVuc->thu_tu_hien_thi : 0) }}" min="0">
                    <div class="form-hint">Số nhỏ hiển thị trước</div>
                </div>
            </div>
        </div>

        @if ($isEdit)
            <div class="card border-0 shadow-sm">
                <div class="card-body bg-light text-muted" style="font-size: 0.8rem">
                    <div class="d-flex justify-content-between mb-2"><span>ID:</span> <span
                            class="font-monospace text-primary fw-bold">#{{ $khuVuc->id }}</span></div>
                    <div class="d-flex justify-content-between mb-2"><span>Tạo lúc:</span>
                        <span>{{ $khuVuc->created_at->format('d/m/Y H:i') }}</span></div>
                    <div class="d-flex justify-content-between"><span>Cập nhật:</span>
                        <span>{{ $khuVuc->updated_at->format('d/m/Y H:i') }}</span></div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        const CSRF = document.querySelector('meta[name=csrf-token]').content;
        const isEdit = {{ $isEdit ? 'true' : 'false' }};

        // Style the Radio Cards (Tận dụng logic selectRoleCard trong admin.js)
        document.querySelectorAll('.kv-cap-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                // Đổi class UI
                document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
                this.closest('.role-card').classList.add('selected');
                applyCapLogic(this.value);
            });
        });

        // Handle logic for Parent Area selection
        const capBox = document.getElementById('kv-cha-box');
        const capTitle = document.getElementById('kvChaTitle');
        const chonTinh = document.getElementById('kv-chon-tinh');
        const chonQuan = document.getElementById('kv-chon-quan');
        const selectTinh = document.getElementById('selectTinh');
        const selectQuan = document.getElementById('selectQuan');

        function applyCapLogic(val) {
            if (val === 'tinh_thanh') {
                capBox.style.display = 'none';
                selectTinh.disabled = true;
                selectQuan.disabled = true;
            } else if (val === 'quan_huyen') {
                capBox.style.display = 'block';
                capTitle.textContent = 'Thuộc tỉnh / thành phố';
                chonTinh.style.display = 'block';
                chonQuan.style.display = 'none';

                selectTinh.disabled = false;
                selectTinh.name = 'khu_vuc_cha_id';
                selectQuan.disabled = true;
                selectQuan.name = '_ignore';
            } else if (val === 'phuong_xa') {
                capBox.style.display = 'block';
                capTitle.textContent = 'Thuộc quận / huyện';
                chonTinh.style.display = 'block';
                chonQuan.style.display = 'block';

                selectTinh.disabled = false;
                selectTinh.name = '_tinh_filter';
                selectQuan.disabled = false;
                selectQuan.name = 'khu_vuc_cha_id';

                if (selectTinh.value) loadQuanByTinh(selectTinh.value);
            }
        }

        // Trigger on load
        applyCapLogic('{{ $oldCap }}');

        // Load Quận/Huyện via AJAX
        selectTinh.addEventListener('change', function() {
            const cap = document.querySelector('.kv-cap-radio:checked')?.value;
            if (cap === 'phuong_xa' && this.value) {
                loadQuanByTinh(this.value);
            }
        });

        function loadQuanByTinh(tinhId) {
            fetch('/nhan-vien/admin/khu-vuc/ajax/danh-sach-con?cha_id=' + tinhId + '&cap=quan_huyen', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    }
                })
                .then(r => r.json())
                .then(data => {
                    const currentVal = '{{ $oldChaId }}';
                    selectQuan.innerHTML = '<option value="">— Chọn quận/huyện —</option>';
                    data.forEach(q => {
                        const opt = document.createElement('option');
                        opt.value = q.id;
                        opt.textContent = q.ten_khu_vuc;
                        if (String(q.id) === String(currentVal)) opt.selected = true;
                        selectQuan.appendChild(opt);
                    });
                }).catch(err => showAdminToast("Lỗi tải danh sách Quận/Huyện", "error"));
        }

        // Auto Slug
        const tenInput = document.getElementById('kvTenInput');
        const slugEl = document.getElementById('slugPreview');
        if (!isEdit && tenInput && slugEl) {
            tenInput.addEventListener('input', function() {
                slugEl.textContent = this.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                    .replace(/đ/g, 'd').replace(/[^a-z0-9\s-]/g, '').trim().replace(/\s+/g, '-') || '...';
            });
        }

        // SEO counters
        function setupSeoCount(inputId, countId, barId, max) {
            const inp = document.getElementById(inputId);
            const cnt = document.getElementById(countId);
            const bar = document.getElementById(barId);
            if (!inp) return;
            inp.addEventListener('input', () => {
                const len = inp.value.length;
                const pct = Math.min(100, Math.round(len / max * 100));
                cnt.textContent = len + '/' + max;
                bar.style.width = pct + '%';
                bar.className = 'progress-bar ' + (pct < 50 ? 'bg-danger' : (pct < 80 ? 'bg-warning' :
                    'bg-success'));
            });
            inp.dispatchEvent(new Event('input'));
        }
        setupSeoCount('seoTitle', 'seoTitleCount', 'seoTitleBar', 70);
        setupSeoCount('seoDesc', 'seoDescCount', 'seoDescBar', 160);
    </script>
@endpush
