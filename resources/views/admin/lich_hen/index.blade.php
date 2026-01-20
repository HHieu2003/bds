@extends('admin.layout.master')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Quản Lý Lịch Xem Nhà</h1>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>Khách Hàng</th>
                            <th>Thông Tin Liên Hệ</th>
                            <th>Căn Hộ Quan Tâm</th>
                            <th>Thời Gian Hẹn</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lich_hens as $lh)
                        <tr>
                            <td class="fw-bold">{{ $lh->ten_khach_hang }}</td>
                            <td>
                                <i class="fas fa-phone text-muted small"></i> {{ $lh->sdt_khach_hang }}<br>
                                @if($lh->email_khach_hang)
                                <i class="fas fa-envelope text-muted small"></i> {{ $lh->email_khach_hang }}
                                @endif
                            </td>
                            <td>
                                @if($lh->batDongSan)
                                    <a href="{{ route('bat-dong-san.show', $lh->batDongSan->id) }}" target="_blank" class="text-primary text-decoration-none">
                                        {{ $lh->batDongSan->ma_can }}
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($lh->batDongSan->tieu_de, 30) }}</small>
                                @else
                                    <span class="text-danger">Sản phẩm đã xóa</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="d-block fw-bold text-dark">{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('H:i') }}</span>
                                <span class="small text-muted">{{ \Carbon\Carbon::parse($lh->thoi_gian_hen)->format('d/m/Y') }}</span>
                            </td>
                            <td class="text-center">
                                @if($lh->trang_thai == 'moi_dat')
                                    <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                @elseif($lh->trang_thai == 'da_xac_nhan')
                                    <span class="badge bg-primary">Đã xác nhận</span>
                                @elseif($lh->trang_thai == 'hoan_thanh')
                                    <span class="badge bg-success">Đã xem</span>
                                @else
                                    <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td>
                            <td>
                                @if($lh->trang_thai == 'moi_dat')
                                <form action="{{ route('admin.lich-hen.confirm', $lh->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success" title="Xác nhận lịch"><i class="fas fa-check"></i></button>
                                </form>
                                <form action="{{ route('admin.lich-hen.destroy', $lh->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Hủy lịch" onclick="return confirm('Hủy lịch hẹn này?')"><i class="fas fa-times"></i></button>
                                </form>
                                @elseif($lh->trang_thai == 'da_xac_nhan')
                                <form action="{{ route('admin.lich-hen.confirm', $lh->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-info text-white" title="Hoàn thành (Khách đã đến xem)">Đã xem xong</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $lich_hens->links() }}
            </div>
        </div>
    </div>
</div>
@endsection