@extends('admin.layouts.master')
@section('title', 'Chi tiết liên hệ — ' . $lienHe->ma_yeu_cau)

@section('content')
    @php $ttInfo = $lienHe->trang_thai_info; @endphp

    <div style="margin-bottom:20px">
        <div style="font-size:.8rem;color:#aaa;margin-bottom:6px;display:flex;align-items:center;gap:6px">
            <a href="{{ route('nhanvien.admin.lien-he.index') }}" style="color:#aaa;text-decoration:none">
                <i class="fas fa-headset"></i> Liên hệ
            </a>
            <i class="fas fa-chevron-right" style="font-size:.65rem"></i>
            <span>{{ $lienHe->ma_yeu_cau }}</span>
        </div>
        <h1 style="font-size:1.3rem;font-weight:800;color:#1a3c5e;margin:0;display:flex;align-items:center;gap:10px">
            <i class="fas fa-user-headset" style="color:#FF8C42"></i>
            {{ $lienHe->ho_ten }}
            <span
                style="font-size:.8rem;font-weight:700;padding:.3rem .8rem;border-radius:20px;background:{{ $ttInfo['bg'] }};color:{{ $ttInfo['color'] }}">
                <i class="{{ $ttInfo['icon'] }}"></i> {{ $ttInfo['label'] }}
            </span>
        </h1>
    </div>

    <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start">

        {{-- CỘT TRÁI --}}
        <div>

            {{-- Thông tin khách --}}
            <div style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;margin-bottom:16px;overflow:hidden">
                <div
                    style="padding:14px 20px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;display:flex;align-items:center;gap:8px">
                    <i class="fas fa-user" style="color:#FF8C42"></i> Thông tin khách hàng
                </div>
                <div style="padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    @php
                        $fields = [
                            ['label' => 'Họ tên', 'val' => $lienHe->ho_ten, 'icon' => 'fas fa-user'],
                            ['label' => 'Điện thoại', 'val' => $lienHe->so_dien_thoai, 'icon' => 'fas fa-phone'],
                            ['label' => 'Email', 'val' => $lienHe->email ?: '—', 'icon' => 'fas fa-envelope'],
                            [
                                'label' => 'Nguồn',
                                'val' =>
                                    \App\Models\YeuCauLienHe::NGUON[$lienHe->nguon_lien_he] ?? $lienHe->nguon_lien_he,
                                'icon' => 'fas fa-globe',
                            ],
                        ];
                    @endphp
                    @foreach ($fields as $f)
                        <div>
                            <div
                                style="font-size:.72rem;color:#aaa;margin-bottom:3px;display:flex;align-items:center;gap:5px">
                                <i class="{{ $f['icon'] }}" style="width:12px"></i> {{ $f['label'] }}
                            </div>
                            <div style="font-size:.9rem;font-weight:600;color:#1a3c5e">{{ $f['val'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Nội dung --}}
            @if ($lienHe->noi_dung)
                <div
                    style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;margin-bottom:16px;overflow:hidden">
                    <div
                        style="padding:14px 20px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;display:flex;align-items:center;gap:8px">
                        <i class="fas fa-comment" style="color:#FF8C42"></i> Nội dung khách gửi
                    </div>
                    <div
                        style="padding:20px;font-size:.9rem;color:#555;line-height:1.7;background:#fafbff;border-radius:0 0 14px 14px">
                        {{ $lienHe->noi_dung }}
                    </div>
                </div>
            @endif

            {{-- BĐS quan tâm --}}
            @if ($lienHe->batDongSan)
                <div
                    style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;margin-bottom:16px;overflow:hidden">
                    <div
                        style="padding:14px 20px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;display:flex;align-items:center;gap:8px">
                        <i class="fas fa-home" style="color:#FF8C42"></i> BĐS quan tâm
                    </div>
                    <div style="padding:20px;display:flex;align-items:center;gap:14px">
                        @if ($lienHe->batDongSan->anh_chinh)
                            <img src="{{ asset('storage/' . $lienHe->batDongSan->anh_chinh) }}" alt=""
                                style="width:70px;height:70px;object-fit:cover;border-radius:10px;flex-shrink:0"
                                onerror="this.onerror=null;this.src='/images/no-image.jpg'">
                        @endif
                        <div>
                            <a href="{{ route('nhanvien.admin.bat-dong-san.show', $lienHe->batDongSan) }}"
                                style="font-weight:700;color:#1a3c5e;text-decoration:none;font-size:.9rem">
                                {{ $lienHe->batDongSan->ten_bat_dong_san }}
                            </a>
                            <div style="font-size:.8rem;color:#aaa;margin-top:3px">
                                {{ $lienHe->batDongSan->dia_chi }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- CỘT PHẢI — Xử lý --}}
        <div>
            <form method="POST" action="{{ route('nhanvien.admin.lien-he.update', $lienHe) }}">
                @csrf @method('PUT')

                <div
                    style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;overflow:hidden;margin-bottom:16px">
                    <div
                        style="padding:14px 20px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;display:flex;align-items:center;gap:8px">
                        <i class="fas fa-cog" style="color:#FF8C42"></i> Xử lý yêu cầu
                    </div>
                    <div style="padding:16px">

                        <div style="margin-bottom:12px">
                            <label style="font-size:.78rem;font-weight:700;color:#888;display:block;margin-bottom:5px">Trạng
                                thái</label>
                            <select name="trang_thai"
                                style="width:100%;padding:9px 12px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.875rem;outline:none">
                                @foreach (\App\Models\YeuCauLienHe::TRANG_THAI as $v => $info)
                                    <option value="{{ $v }}" @selected($lienHe->trang_thai == $v)>{{ $info['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom:12px">
                            <label style="font-size:.78rem;font-weight:700;color:#888;display:block;margin-bottom:5px">Mức
                                độ quan tâm</label>
                            <select name="muc_do_quan_tam"
                                style="width:100%;padding:9px 12px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.875rem;outline:none">
                                <option value="">— Chưa xác định —</option>
                                @foreach (\App\Models\YeuCauLienHe::MUC_DO as $v => $info)
                                    <option value="{{ $v }}" @selected($lienHe->muc_do_quan_tam == $v)>{{ $info['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom:12px">
                            <label style="font-size:.78rem;font-weight:700;color:#888;display:block;margin-bottom:5px">Nhân
                                viên phụ trách</label>
                            <select name="nhan_vien_phu_trach_id"
                                style="width:100%;padding:9px 12px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.875rem;outline:none">
                                <option value="">— Chưa phân công —</option>
                                @foreach ($nhanViens as $nv)
                                    <option value="{{ $nv->id }}" @selected($lienHe->nhan_vien_phu_trach_id == $nv->id)>{{ $nv->ho_ten }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom:14px">
                            <label style="font-size:.78rem;font-weight:700;color:#888;display:block;margin-bottom:5px">Ghi
                                chú nội bộ</label>
                            <textarea name="ghi_chu_admin" rows="4" placeholder="Ghi chú nội bộ về khách hàng này..."
                                style="width:100%;padding:9px 12px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.875rem;outline:none;resize:vertical;box-sizing:border-box">{{ $lienHe->ghi_chu_admin }}</textarea>
                        </div>

                        <button type="submit"
                            style="width:100%;background:linear-gradient(135deg,#1a3c5e,#2d6a9f);color:#fff;border:none;padding:12px;border-radius:10px;font-weight:700;font-size:.9rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px">
                            <i class="fas fa-save"></i> Lưu xử lý
                        </button>
                    </div>
                </div>

            </form>

            {{-- Thông tin hệ thống --}}
            <div style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;overflow:hidden">
                <div
                    style="padding:14px 20px;border-bottom:1px solid #f5f5f5;font-weight:700;color:#1a3c5e;font-size:.875rem">
                    <i class="fas fa-info-circle" style="color:#aaa"></i> Thông tin
                </div>
                <div style="padding:16px">
                    @foreach ([['Mã YC', $lienHe->ma_yeu_cau], ['Thời gian', $lienHe->thoi_diem_lien_he?->format('d/m/Y H:i') ?? $lienHe->created_at->format('d/m/Y H:i')], ['Nguồn', \App\Models\YeuCauLienHe::NGUON[$lienHe->nguon_lien_he] ?? $lienHe->nguon_lien_he]] as [$k, $v])
                        <div
                            style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f8f9fa;font-size:.82rem">
                            <span style="color:#aaa">{{ $k }}</span>
                            <span style="color:#444;font-weight:600">{{ $v }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Xóa --}}
            <form method="POST" action="{{ route('nhanvien.admin.lien-he.destroy', $lienHe) }}"
                onsubmit="return confirm('Xác nhận xóa yêu cầu này?')" style="margin-top:12px">
                @csrf @method('DELETE')
                <button type="submit"
                    style="width:100%;background:#fff;color:#e74c3c;border:1.5px solid #fde8e8;padding:10px;border-radius:10px;font-weight:700;font-size:.875rem;cursor:pointer">
                    <i class="fas fa-trash"></i> Xóa yêu cầu này
                </button>
            </form>

        </div>
    </div>

@endsection
