@extends('layouts.app')

@section('content')
    <h2>Danh sách phụ tùng</h2>

    <!-- Nút mở modal thêm phụ tùng -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Nút thêm và export -->
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPartModal">
                + Thêm phụ tùng
            </button>
            <a href="{{ route('parts.export.pdf') }}" class="btn btn-outline-secondary">Xuất PDF</a>
        </div>
        <form method="GET" action="{{ route('parts.index') }}" class="d-flex align-items-end gap-2">
            <div>
                <label for="start_date">Từ ngày</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div>
                <label for="end_date">Đến ngày</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="form-check mt-4">
                <input type="checkbox" class="form-check-input" name="low_stock" value="1" id="low_stock" {{ request('low_stock') ? 'checked' : '' }}>
                <label class="form-check-label" for="low_stock">Hiển thị phụ tùng sắp hết</label>
            </div>
            <button type="submit" class="btn btn-outline-primary mt-4">Lọc</button>
            <a href="{{ route('parts.index') }}" class="btn btn-outline-secondary mt-4">Đặt lại</a>
        </form>
    </div>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mã</th>
                <th>Tên</th>
                <th>Đơn vị</th>
                <th>Số lượng tồn</th>
                <th>Giá</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($parts as $part)
                <tr>
                    <td>{{ $part->id }}</td>
                    <td>{{ $part->code }}</td>
                    <td>{{ $part->name }}</td>
                    <td>{{ $part->unit }}</td>
                    <td>{{ $part->stock }}</td>
                    <td>{{ number_format($part->price) }} đ</td>
                    <td>{{ $part->description }}</td>
                    <td>
                        <!-- Nút mở modal sửa -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editPartModal{{ $part->id }}">
                            Sửa
                        </button>

                        <form action="{{ route('parts.destroy', $part) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Bạn chắc chắn muốn xóa?')"
                                class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal sửa phụ tùng -->
                <div class="modal fade" id="editPartModal{{ $part->id }}" tabindex="-1"
                    aria-labelledby="editPartModalLabel{{ $part->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('parts.update', $part) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editPartModalLabel{{ $part->id }}">Sửa phụ tùng:
                                        {{ $part->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Mã</label>
                                        <input type="text" name="code" class="form-control" value="{{ $part->code }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Tên</label>
                                        <input type="text" name="name" class="form-control" value="{{ $part->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Đơn vị</label>
                                        <input type="text" name="unit" class="form-control" value="{{ $part->unit }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Số lượng tồn</label>
                                        <input type="number" name="stock" class="form-control" value="{{ $part->stock }}"
                                            min="0">
                                    </div>
                                    <div class="mb-3">
                                        <label>Giá</label>
                                        <input type="number" name="price" class="form-control" value="{{ $part->price }}"
                                            step="0.01" min="0">
                                    </div>
                                    <div class="mb-3">
                                        <label>Mô tả</label>
                                        <textarea name="description" class="form-control"
                                            rows="3">{{ $part->description }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>

    <!-- Modal thêm phụ tùng -->
    <div class="modal fade" id="addPartModal" tabindex="-1" aria-labelledby="addPartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('parts.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPartModalLabel">Thêm phụ tùng mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tên</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Đơn vị</label>
                            <input type="text" name="unit" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Số lượng tồn</label>
                            <input type="number" name="stock" class="form-control" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label>Giá</label>
                            <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label>Mô tả</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection