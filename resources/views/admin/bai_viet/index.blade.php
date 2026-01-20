@extends('admin.layout.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản Lý Tin Tức</h1>
        <a href="{{ route('admin.bai-viet.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Viết bài mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách bài viết</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tiêu đề</th>
                            <th>Hình ảnh</th>
                            <th>Ngày đăng</th>
                            <th style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bai_viets as $bv)
                        <tr>
                            <td>{{ $bv->tieu_de }}</td>
                            <td>
                                @if($bv->hinh_anh)
                                    <img src="{{ asset('storage/' . $bv->hinh_anh) }}" width="80" class="img-thumbnail">
                                @else
                                    <span class="text-muted">Không có ảnh</span>
                                @endif
                            </td>
                            <td>{{ $bv->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.bai-viet.edit', $bv->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('admin.bai-viet.destroy', $bv->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection