@if($properties->count() > 0)
<div class="table-responsive">
    <table class="table table-bordered text-center align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th style="width: 20%;" class="small text-muted">Tiêu chí</th>
                @foreach($properties as $item)
                <th style="width: {{ 80 / $properties->count() }}%;">
                    <div class="position-relative p-2">
                        <button onclick="removeCompareItem({{ $item->id }})" class="btn btn-sm text-danger position-absolute top-0 end-0" title="Xóa">
                            <i class="fas fa-times-circle fs-5"></i>
                        </button>
                        
                        <img src="{{ asset($item->hinh_anh) }}" class="rounded-3 mb-2" style="width: 100%; height: 100px; object-fit: cover;">
                        <h6 class="fw-bold text-truncate px-1 small">
                            {{ $item->tieu_de }}
                        </h6>
                        <span class="badge bg-danger">{{ number_format($item->gia, 2) }} Tỷ</span>
                    </div>
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="fw-bold bg-light text-start ps-3">Giá / m2</td>
                @foreach($properties as $item)
                <td>{{ number_format(($item->gia * 1000) / $item->dien_tich, 1) }} Tr/m²</td>
                @endforeach
            </tr>
            <tr>
                <td class="fw-bold bg-light text-start ps-3">Diện tích</td>
                @foreach($properties as $item)
                <td>{{ $item->dien_tich }} m²</td>
                @endforeach
            </tr>
            <tr>
                <td class="fw-bold bg-light text-start ps-3">Phòng ngủ</td>
                @foreach($properties as $item)
                <td>{{ $item->so_phong_ngu }} PN</td>
                @endforeach
            </tr>
            <tr>
                <td class="fw-bold bg-light text-start ps-3">Hướng</td>
                @foreach($properties as $item)
                <td>{{ $item->huong_nha ?? 'KXĐ' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="bg-light"></td>
                @foreach($properties as $item)
                <td>
                    <a href="{{ route('home.show', $item->slug) }}" class="btn btn-primary btn-sm rounded-pill w-100">Chi Tiết</a>
                </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>
@else
<div class="text-center py-4">
    <img src="https://cdn-icons-png.flaticon.com/512/3121/3121580.png" width="60" class="mb-3 opacity-50">
    <h6 class="text-muted">Danh sách so sánh trống!</h6>
</div>
@endif