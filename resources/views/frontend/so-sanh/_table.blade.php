<div class="table-responsive">
    <table class="table table-bordered align-middle text-center mb-0" style="min-width: 700px;">
        <thead class="bg-light">
            <tr>
                <th style="width: 15%; background-color: #f1f5f9; color: #64748B;" class="text-start p-3 fs-6 serif-font">
                    Tiêu chí</th>
                @foreach ($danhSachBds as $bds)
                    <th style="width: {{ 85 / $danhSachBds->count() }}%; padding: 1rem;">
                        <div class="position-relative">
                            {{-- Gọi hàm xóa và load lại Modal --}}
                            <button class="btn btn-sm btn-danger rounded-circle position-absolute"
                                style="top: -10px; right: -10px; z-index: 10;"
                                onclick="removeSoSanhTuModal({{ $bds->id }})" title="Xóa khỏi bảng">
                                <i class="fas fa-times"></i>
                            </button>

                            <a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}" target="_blank"
                                class="d-block overflow-hidden rounded-3 mb-2 bg-light" style="height: 120px;">
                                @php $anh = is_array($bds->album_anh) && count($bds->album_anh) > 0 ? $bds->album_anh[0] : null; @endphp
                                <img src="{{ $anh ? asset('storage/' . $anh) : asset('images/default-bds.jpg') }}"
                                    class="w-100 h-100" style="object-fit: cover;">
                            </a>

                            <h6 class="fw-bold mb-1 text-dark line-clamp-2" style="font-size: 0.85rem;">
                                {{ $bds->tieu_de }}</h6>
                            <h6 class="fw-bold mb-0" style="color: #FF8C42; font-size: 0.95rem;">
                                {{ $bds->gia_hien_thi ?? 'Thỏa thuận' }}</h6>
                        </div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="fw-bold text-start text-muted p-2 bg-light">Khu vực</td>
                @foreach ($danhSachBds as $bds)
                    <td class="p-2 fw-semibold">{{ $bds->khuVuc->ten_khu_vuc ?? 'N/A' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="fw-bold text-start text-muted p-2 bg-light">Dự án</td>
                @foreach ($danhSachBds as $bds)
                    <td class="p-2 text-primary fw-bold">{{ $bds->duAn->ten_du_an ?? 'BĐS Tự Do' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="fw-bold text-start text-muted p-2 bg-light">Diện tích</td>
                @foreach ($danhSachBds as $bds)
                    <td class="p-2 fw-bold">{{ $bds->dien_tich }} m²</td>
                @endforeach
            </tr>
            <tr>
                <td class="fw-bold text-start text-muted p-2 bg-light">Phòng</td>
                @foreach ($danhSachBds as $bds)
                    <td class="p-2">{{ $bds->so_phong_ngu }} PN | {{ $bds->so_phong_tam }} WC</td>
                @endforeach
            </tr>
            <tr>
                <td class="fw-bold text-start text-muted p-2 bg-light">Pháp lý</td>
                @foreach ($danhSachBds as $bds)
                    <td class="p-2">{{ $bds->phap_ly ?? 'N/A' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="fw-bold text-start text-muted p-2 bg-light"></td>
                @foreach ($danhSachBds as $bds)
                    <td class="p-2"><a href="{{ route('frontend.bat-dong-san.show', $bds->slug) }}" target="_blank"
                            class="btn btn-sm btn-outline-dark rounded-pill fw-bold w-100">Chi Tiết</a></td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>
