@extends('admin.layouts.master')
@section('title', 'Quản lý liên hệ')

@section('content')

    {{-- HEADER --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px">
        <div>
            <h1
                style="font-size:1.35rem;font-weight:800;color:#1a3c5e;margin:0 0 4px;display:flex;align-items:center;gap:9px">
                <i class="fas fa-headset" style="color:#FF8C42"></i> Quản lý liên hệ
            </h1>
            <p style="color:#aaa;font-size:.83rem;margin:0">
                Tổng <strong style="color:#1a3c5e">{{ $thongKe['tat_ca'] }}</strong> yêu cầu
            </p>
        </div>
    </div>

    {{-- THỐNG KÊ NHANH --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:12px;margin-bottom:24px">
        @php
            $ttItems = array_merge(
                ['tat_ca' => ['label' => 'Tất cả', 'color' => '#1a3c5e', 'bg' => '#eef2ff', 'icon' => 'fas fa-list']],
                \App\Models\YeuCauLienHe::TRANG_THAI,
            );
        @endphp
        @foreach ($ttItems as $key => $info)
            <a href="{{ request()->fullUrlWithQuery(['trang_thai' => $key === 'tat_ca' ? null : $key, 'page' => null]) }}"
                style="background:{{ request('trang_thai') == $key || ($key === 'tat_ca' && !request('trang_thai')) ? $info['bg'] : '#fff' }};border:2px solid {{ request('trang_thai') == $key || ($key === 'tat_ca' && !request('trang_thai')) ? $info['color'] : '#f0f2f5' }};border-radius:12px;padding:14px 16px;text-decoration:none;display:block">
                <div style="font-size:1.3rem;font-weight:800;color:{{ $info['color'] }}">{{ $thongKe[$key] ?? 0 }}</div>
                <div style="font-size:.72rem;color:#888;margin-top:2px">{{ $info['label'] }}</div>
            </a>
        @endforeach
    </div>

    {{-- BỘ LỌC --}}
    <form method="GET"
        style="background:#fff;border-radius:14px;padding:16px 20px;margin-bottom:20px;border:1.5px solid #f0f2f5;display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
        <div style="flex:2;min-width:180px">
            <label style="font-size:.75rem;font-weight:700;color:#aaa;display:block;margin-bottom:4px">Tìm kiếm</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên, SĐT, email..."
                style="width:100%;padding:9px 12px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.875rem;outline:none;box-sizing:border-box">
        </div>
        <div style="flex:1;min-width:130px">
            <label style="font-size:.75rem;font-weight:700;color:#aaa;display:block;margin-bottom:4px">Trạng thái</label>
            <select name="trang_thai"
                style="width:100%;padding:9px 12px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.875rem;outline:none">
                <option value="">Tất cả</option>
                @foreach (\App\Models\YeuCauLienHe::TRANG_THAI as $v => $info)
                    <option value="{{ $v }}" @selected(request('trang_thai') == $v)>{{ $info['label'] }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex:1;min-width:130px">
            <label style="font-size:.75rem;font-weight:700;color:#aaa;display:block;margin-bottom:4px">Nhân viên</label>
            <select name="nhan_vien"
                style="width:100%;padding:9px 12px;border:1.5px solid #e8e8e8;border-radius:9px;font-size:.875rem;outline:none">
                <option value="">Tất cả</option>
                @foreach ($nhanViens as $nv)
                    <option value="{{ $nv->id }}" @selected(request('nhan_vien') == $nv->id)>{{ $nv->ho_ten }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:flex;gap:8px">
            <button type="submit"
                style="background:#1a3c5e;color:#fff;border:none;padding:9px 20px;border-radius:9px;font-weight:700;font-size:.875rem;cursor:pointer">
                <i class="fas fa-search"></i> Lọc
            </button>
            <a href="{{ route('nhanvien.admin.lien-he.index') }}"
                style="background:#f5f7ff;color:#888;border:1.5px solid #e8e8e8;padding:9px 16px;border-radius:9px;font-size:.875rem;text-decoration:none;display:flex;align-items:center">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>

    {{-- BẢNG DANH SÁCH --}}
    <div style="background:#fff;border-radius:14px;border:1.5px solid #f0f2f5;overflow:hidden">
        <table style="width:100%;border-collapse:collapse;font-size:.875rem">
            <thead>
                <tr style="background:#f8faff;border-bottom:2px solid #f0f2f5">
                    <th
                        style="padding:12px 16px;text-align:left;font-size:.72rem;color:#aaa;font-weight:700;text-transform:uppercase">
                        Mã</th>
                    <th
                        style="padding:12px 16px;text-align:left;font-size:.72rem;color:#aaa;font-weight:700;text-transform:uppercase">
                        Khách hàng</th>
                    <th
                        style="padding:12px 16px;text-align:left;font-size:.72rem;color:#aaa;font-weight:700;text-transform:uppercase">
                        BĐS quan tâm</th>
                    <th
                        style="padding:12px 16px;text-align:left;font-size:.72rem;color:#aaa;font-weight:700;text-transform:uppercase">
                        Trạng thái</th>
                    <th
                        style="padding:12px 16px;text-align:left;font-size:.72rem;color:#aaa;font-weight:700;text-transform:uppercase">
                        Phụ trách</th>
                    <th
                        style="padding:12px 16px;text-align:left;font-size:.72rem;color:#aaa;font-weight:700;text-transform:uppercase">
                        Thời gian</th>
                    <th
                        style="padding:12px 16px;text-align:center;font-size:.72rem;color:#aaa;font-weight:700;text-transform:uppercase">
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($lienHes as $lh)
                    @php $ttInfo = $lh->trang_thai_info; @endphp
                    <tr style="border-bottom:1px solid #f8f9fa;transition:background .15s"
                        onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background=''">
                        <td style="padding:12px 16px">
                            <span
                                style="font-family:monospace;font-size:.75rem;color:#2d6a9f;background:#eef2ff;padding:2px 7px;border-radius:5px">
                                {{ $lh->ma_yeu_cau }}
                            </span>
                        </td>
                        <td style="padding:12px 16px">
                            <div style="font-weight:700;color:#1a3c5e">{{ $lh->ho_ten }}</div>
                            <div style="font-size:.78rem;color:#aaa">{{ $lh->so_dien_thoai }}</div>
                            @if ($lh->email)
                                <div style="font-size:.75rem;color:#bbb">{{ $lh->email }}</div>
                            @endif
                        </td>
                        <td style="padding:12px 16px;max-width:200px">
                            @if ($lh->batDongSan)
                                <a href="#" onclick="event.preventDefault()"
                                    style="font-size:.83rem;color:#2d6a9f;text-decoration:none;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                                    {{ $lh->batDongSan->ten_bat_dong_san }}
                                </a>
                            @else
                                <span style="font-size:.8rem;color:#ccc">— Liên hệ chung —</span>
                            @endif
                        </td>
                        <td style="padding:12px 16px">
                            <select onchange="capNhatNhanh({{ $lh->id }}, this.value, this)"
                                style="font-size:.75rem;font-weight:700;padding:4px 8px;border-radius:8px;border:none;background:{{ $ttInfo['bg'] }};color:{{ $ttInfo['color'] }};cursor:pointer;outline:none">
                                @foreach (\App\Models\YeuCauLienHe::TRANG_THAI as $v => $info)
                                    <option value="{{ $v }}" @selected($lh->trang_thai == $v)>{{ $info['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td style="padding:12px 16px">
                            @if ($lh->nhanVienPhuTrach)
                                <span style="font-size:.83rem;color:#444">{{ $lh->nhanVienPhuTrach->ho_ten }}</span>
                            @else
                                <span style="font-size:.78rem;color:#ddd">Chưa phân công</span>
                            @endif
                        </td>
                        <td style="padding:12px 16px">
                            <div style="font-size:.8rem;color:#888">
                                {{ $lh->thoi_diem_lien_he?->format('d/m/Y') ?? $lh->created_at->format('d/m/Y') }}
                            </div>
                            <div style="font-size:.73rem;color:#bbb">
                                {{ $lh->thoi_diem_lien_he?->format('H:i') ?? $lh->created_at->format('H:i') }}
                            </div>
                        </td>
                        <td style="padding:12px 16px;text-align:center">
                            <a href="{{ route('nhanvien.admin.lien-he.show', $lh) }}"
                                style="background:#f0f7ff;color:#2d6a9f;border:none;padding:6px 12px;border-radius:7px;font-size:.78rem;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:4px">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding:50px;text-align:center;color:#ccc">
                            <i class="fas fa-inbox" style="font-size:2.5rem;display:block;margin-bottom:10px"></i>
                            Chưa có yêu cầu liên hệ nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PHÂN TRANG --}}
    @if ($lienHes->hasPages())
        <div style="margin-top:20px">{{ $lienHes->links() }}</div>
    @endif

@endsection

@push('scripts')
    <script>
        async function capNhatNhanh(id, trangThai, el) {
            const old = el.dataset.old || el.value;
            el.dataset.old = el.value;

            try {
                const res = await fetch(`/nhan-vien/admin/lien-he/${id}/cap-nhat-nhanh`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({
                        trang_thai: trangThai
                    })
                });
                const data = await res.json();
                if (data.success) {
                    el.style.background = data.info.bg;
                    el.style.color = data.info.color;
                    el.dataset.old = trangThai;
                }
            } catch {
                el.value = old;
            }
        }
    </script>
@endpush
