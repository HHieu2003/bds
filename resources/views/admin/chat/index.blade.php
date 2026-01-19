@extends('admin.layout.master')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white fw-bold">
        <i class="fa-solid fa-comments me-2"></i> Danh Sách Khách Hàng Chat
    </div>
    <div class="list-group list-group-flush">
        @foreach($sessions as $s)
        <a href="{{ route('admin.chat.show', $s->id) }}" class="list-group-item list-group-item-action py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold fs-5 text-dark"><i class="fa-solid fa-phone me-2"></i>{{ $s->phone }}</span>
                    
                    <div class="mt-2">
                        @if($s->batDongSan)
                            <span class="badge bg-danger">
                                <i class="fa-solid fa-house me-1"></i> Quan tâm: {{ $s->batDongSan->ma_can }}
                            </span>
                            <small class="text-muted ms-1">({{ $s->batDongSan->duAn->ten_du_an }})</small>
                        @elseif($s->duAn)
                            <span class="badge bg-primary">
                                <i class="fa-solid fa-building me-1"></i> Quan tâm Dự án: {{ $s->duAn->ten_du_an }}
                            </span>
                        @else
                            <span class="badge bg-secondary">Khách vãng lai (Trang chủ)</span>
                        @endif
                    </div>
                </div>

                <div class="text-end">
                    <small class="text-muted d-block mb-1">{{ $s->updated_at->diffForHumans() }}</small>
                    @if(!$s->is_read)
                        <span class="badge bg-success rounded-pill px-3">Tin nhắn mới</span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
        
        @if($sessions->count() == 0)
            <div class="text-center p-5 text-muted">Chưa có cuộc hội thoại nào.</div>
        @endif
    </div>
</div>
@endsection