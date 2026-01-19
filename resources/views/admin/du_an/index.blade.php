@extends('admin.layout.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">🏙 Quản Lý Dự Án</h2>
        <a href="{{ route('du-an.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus me-1"></i> Thêm Dự Án Mới
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">ID</th>
                        <th width="10%">Hình ảnh</th>
                        <th width="25%">Tên Dự Án</th>
                        <th width="25%">Địa chỉ</th>
                        <th width="20%">Chủ đầu tư</th>
                        <th width="15%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($danhSachDuAn as $duAn)
                    <tr>
                        <td>{{ $duAn->id }}</td>
                        <td>
                            @if($duAn->hinh_anh)
                                <img src="{{ asset('storage/' . $duAn->hinh_anh) }}" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                            @else
                                <span class="badge bg-secondary">No Img</span>
                            @endif
                        </td>
                        <td class="fw-bold text-primary">{{ $duAn->ten_du_an }}</td>
                        <td>{{ $duAn->dia_chi }}</td>
                        <td>{{ $duAn->chu_dau_tu }}</td>
                        <td>
                            <a href="{{ route('du-an.edit', $duAn->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            
                             <form action="{{ route('du-an.destroy', $duAn->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa dự án này sẽ xóa TOÀN BỘ BĐS thuộc về nó. Tiếp tục?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection