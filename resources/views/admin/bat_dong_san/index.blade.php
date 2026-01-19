@extends('admin.layout.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold">📦 Kho Hàng Bất Động Sản</h2>
    <a href="{{ route('bat-dong-san.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> Đăng Tin Mới
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-dark text-white">
                <tr>
                    <th width="5%" class="ps-3">ID</th>
                    <th width="10%">Ảnh</th>
                    <th>Tòa / Mã căn</th>
                    <th width="30%">Tiêu đề / Dự án</th>
                    <th width="15%">Giá / Diện tích</th>
                    <th width="10%">Trạng thái</th>
                    <th width="15%" class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($danhSachBDS as $item)
                <tr>
                    <td class="ps-3 fw-bold">{{ $item->id }}</td>
                    <td>
                        @if($item->hinh_anh && count($item->hinh_anh) > 0)
                        <img src="{{ asset('storage/' . $item->hinh_anh[0]) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                        @else
                        <span class="text-muted small">No Img</span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-primary">{{ $item->toa }}</div>
                        <div class="text-danger small">{{ $item->ma_can }}</div>
                    </td>
                    <td>
                        <div class="fw-bold">{{ Str::limit($item->tieu_de, 40) }}</div>
                        <small class="text-muted"><i class="fa-regular fa-building me-1"></i> {{ $item->duAn->ten_du_an }}</small>
                    </td>
                    <td>
                        <div class="text-success fw-bold">{{ number_format($item->gia) }} ₫</div>
                        <small>{{ $item->dien_tich }} m²</small>
                    </td>
                    <td>
                        @if($item->trang_thai == 'con_hang')
                        <span class="badge bg-success">Còn hàng</span>
                        @else
                        <span class="badge bg-secondary">Đã chốt</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('bat-dong-san.edit', $item->id) }}" class="btn btn-sm btn-outline-warning mx-1">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <form action="{{ route('bat-dong-san.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xóa tin này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger mx-1">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- <div class="d-flex justify-content-center mt-3">{{ $danhSachBDS->links() }}
</div> --}}
</div>
@endsection