{{-- create.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Viết bài mới')
@section('content')
    <div class="bv-form-hdr">
        <div>
            <div class="bv-bc">
                <a href="{{ route('nhanvien.admin.bai-viet.index') }}">
                    <i class="fas fa-pen-alt"></i> Bài viết
                </a>
                <i class="fas fa-chevron-right"></i>
                <span>Viết bài mới</span>
            </div>
            <h1 class="bv-form-ttl"><i class="fas fa-plus-circle"></i> Viết bài viết mới</h1>
        </div>
    </div>

    <form id="bvForm" method="POST" action="{{ route('nhanvien.admin.bai-viet.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.bai-viet._form')
    </form>
@endsection
