@extends('frontend.layouts.master')
@section('title', 'Ký gửi của tôi')

@section('content')
    <div style="background:#f8faff;min-height:70vh;padding:40px 0 60px">
        <div style="max-width:900px;margin:0 auto;padding:0 16px">

            <div
                style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px">
                <div>
                    <h1
                        style="font-size:1.4rem;font-weight:800;color:#1a3c5e;margin:0 0 4px;display:flex;align-items:center;gap:10px">
                        <i class="fas fa-file-signature" style="color:#FF8C42"></i>
                        Ký gửi của tôi
                    </h1>
                    <p style="color:#aaa;font-size:.83rem;margin:0">
                        Tổng cộng <strong style="color:#1a3c5e">{{ $kyGuis->total() }}</strong> yêu cầu đã gửi
                    </p>
                </div>
                <a href="{{ route('ky-gui.create') }}"
                    style="display:inline-flex;align-items:center;gap:7px;background:linear-gradient(135deg,#FF8C42,#f5a623);color:#fff;padding:10px 20px;border-radius:10px;font-weight:700;font-size:.875rem;text-decoration:none;box-shadow:0 4px 14px rgba(255,140,66,.3)">
                    <i class="fas fa-plus"></i> Ký gửi mới
                </a>
            </div>

            @forelse($kyGuis as $kg)
                @php
                    $ttInfo = \App\Models\KyGui::TRANG_THAI[$kg->trang_thai] ?? [
                        'label' => '?',
                        'color' => '#999',
                        'bg' => '#f5f5f5',
                        'icon' => 'fas fa-question',
                    ];
                    $lhInfo = \App\Models\KyGui::LOAI_HINH[$kg->loai_hinh] ?? [
                        'label' => '?',
                        'icon' => 'fas fa-home',
                        'color' => '#999',
                    ];
                    $ncInfo = \App\Models\KyGui::NHU_CAU[$kg->nhu_cau] ?? [
                        'label' => '?',
                        'color' => '#999',
                        'bg' => '#f5f5f5',
                    ];
                @endphp
                <div style="background:#fff;border-radius:14px;box-shadow:0 2px 12px rgba(0,0,0,.06);padding:20px 22px;margin-bottom:16px;border:1.5px solid #f0f2f5;transition:box-shadow .2s"
                    onmouseover="this.style.boxShadow='0 6px 24px rgba(0,0,0,.1)'"
                    onmouseout="this.style.boxShadow='0 2px 12px rgba(0,0,0,.06)'">

                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap">

                        {{-- Thông tin chính --}}
                        <div style="flex:1;min-width:200px">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;flex-wrap:wrap">
                                <span style="font-size:.75rem;font-weight:700;color:{{ $lhInfo['color'] }}">
                                    <i class="{{ $lhInfo['icon'] }}"></i> {{ $lhInfo['label'] }}
                                </span>
                                <span
                                    style="font-size:.72rem;font-weight:700;padding:.2rem .55rem;border-radius:20px;background:{{ $ncInfo['bg'] }};color:{{ $ncInfo['color'] }}">
                                    {{ $ncInfo['label'] }}
                                </span>
                                <span
                                    style="font-family:monospace;font-size:.72rem;color:#bbb;background:#f5f5f5;padding:.1rem .4rem;border-radius:4px">
                                    #KG-{{ str_pad($kg->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>

                            @if ($kg->dia_chi)
                                <div
                                    style="font-size:.875rem;color:#444;margin-bottom:6px;display:flex;align-items:center;gap:5px">
                                    <i class="fas fa-map-marker-alt" style="color:#FF8C42;font-size:.8rem"></i>
                                    {{ $kg->dia_chi }}
                                </div>
                            @endif

                            <div style="font-size:.82rem;color:#888;display:flex;gap:14px;flex-wrap:wrap">
                                <span><i class="fas fa-ruler-combined" style="color:#ddd"></i> {{ $kg->dien_tich }}
                                    m²</span>
                                @if ($kg->so_phong_ngu)
                                    <span><i class="fas fa-bed" style="color:#ddd"></i> {{ $kg->so_phong_ngu }} PN</span>
                                @endif
                                <span><i class="fas fa-calendar" style="color:#ddd"></i>
                                    {{ $kg->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        {{-- Giá & Trạng thái --}}
                        <div style="text-align:right;flex-shrink:0">
                            <div style="font-size:1.05rem;font-weight:800;color:#1a3c5e;margin-bottom:8px">
                                {{ $kg->gia_hien_thi }}
                            </div>
                            <span
                                style="display:inline-flex;align-items:center;gap:5px;font-size:.78rem;font-weight:700;padding:.3rem .8rem;border-radius:20px;background:{{ $ttInfo['bg'] }};color:{{ $ttInfo['color'] }}">
                                <i class="{{ $ttInfo['icon'] }}"></i>
                                {{ $ttInfo['label'] }}
                            </span>

                            @if ($kg->thoi_diem_xu_ly)
                                <div style="font-size:.7rem;color:#bbb;margin-top:4px">
                                    Xử lý: {{ $kg->thoi_diem_xu_ly->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Phản hồi admin --}}
                    @if ($kg->phan_hoi_cua_admin)
                        <div
                            style="margin-top:12px;padding:10px 14px;background:#f0fff4;border-left:3px solid #27ae60;border-radius:0 8px 8px 0;font-size:.83rem;color:#27ae60">
                            <div
                                style="font-size:.7rem;font-weight:700;text-transform:uppercase;margin-bottom:3px;opacity:.7">
                                Phản hồi từ chúng tôi</div>
                            {{ $kg->phan_hoi_cua_admin }}
                        </div>
                    @endif

                    {{-- Hình ảnh --}}
                    @if ($kg->hinh_anh_tham_khao && count($kg->hinh_anh_tham_khao) > 0)
                        <div style="display:flex;gap:8px;margin-top:12px;flex-wrap:wrap">
                            @foreach (array_slice($kg->hinh_anh_tham_khao, 0, 4) as $idx => $img)
                                <div style="position:relative">
                                    <img src="{{ asset('storage/' . $img) }}" alt="Ảnh"
                                        style="width:60px;height:60px;object-fit:cover;border-radius:8px;border:1.5px solid #f0f2f5">
                                    @if ($idx === 3 && count($kg->hinh_anh_tham_khao) > 4)
                                        <div
                                            style="position:absolute;inset:0;background:rgba(0,0,0,.55);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.8rem;font-weight:700">
                                            +{{ count($kg->hinh_anh_tham_khao) - 4 }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            @empty
                <div
                    style="text-align:center;padding:60px 20px;background:#fff;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,.06)">
                    <i class="fas fa-inbox" style="font-size:3rem;color:#e8ebf5;display:block;margin-bottom:14px"></i>
                    <p style="color:#bbb;font-size:.95rem;margin:0 0 20px">Bạn chưa có yêu cầu ký gửi nào</p>
                    <a href="{{ route('ky-gui.create') }}"
                        style="display:inline-flex;align-items:center;gap:7px;background:linear-gradient(135deg,#FF8C42,#f5a623);color:#fff;padding:12px 28px;border-radius:10px;font-weight:700;text-decoration:none">
                        <i class="fas fa-plus"></i> Ký gửi BĐS ngay
                    </a>
                </div>
            @endforelse

            {{-- PHÂN TRANG --}}
            @if ($kyGuis->hasPages())
                <div style="margin-top:20px">
                    {{ $kyGuis->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection
