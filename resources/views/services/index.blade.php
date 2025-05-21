@extends('layouts.app')

@section('content')
    <h2>Danh sách dịch vụ</h2>
    <div class="d-flex justify-content-between mb-3">

        <!-- Nút mở modal thêm dịch vụ -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">+ Thêm dịch
            vụ</button>
        <!-- Form tìm kiếm -->
        <form action="{{ route('services.index') }}" method="GET" class="d-flex" style="width: 300px;">
            <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm..."
                value="{{ request('search') }}" aria-label="Search">
            <button class="btn btn-outline-primary" type="submit">Tìm</button>
        </form>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên dịch vụ</th>
                <th>Mô tả</th>
                <th>Giá</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
                <tr>
                    <td>{{ $service->name }}</td>
                    <td>{{ $service->description }}</td>
                    <td>{{ number_format($service->price, 0, ',', '.') }} đ</td>
                    <td>
                        <form method="POST" action="{{ route('services.destroy', $service) }}">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Xóa dịch vụ?')" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal thêm dịch vụ -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('services.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm dịch vụ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tên dịch vụ</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Mô tả</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Giá</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection