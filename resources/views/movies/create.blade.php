@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Thêm phim mới</h2>

    {{-- Hiển thị lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form thêm phim --}}
    <form action="{{ route('movies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Tên phim</label>
            <input type="text" name="title" class="form-control" placeholder="Nhập tên phim" required>
        </div>

        <div class="mb-3">
            <label for="genre" class="form-label">Thể loại</label>
            <input type="text" name="genre" class="form-control" placeholder="Ví dụ: Hành động, Hài, Kinh dị" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Ảnh phim</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-success">Thêm phim</button>
    </form>
</div>
@endsection
