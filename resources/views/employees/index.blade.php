@extends('layouts.app')

@section('content')
    <h2>Danh sách nhân viên</h2>

    <div class="d-flex justify-content-between mb-3">
        <!-- Nút kích hoạt modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            + Thêm nhân viên
        </button>

        <form action="{{ route('employees.index') }}" method="GET" class="d-flex" style="width: 300px;">
            <input type="search" name="search" class="form-control me-2" placeholder="Tìm kiếm..."
                value="{{ $search ?? '' }}">
            <button class="btn btn-outline-primary" type="submit">Tìm</button>
        </form>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Chức vụ</th>
                <th>Lương</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $emp)
                <tr>
                    <td>{{ $emp->name }}</td>
                    <td>{{ $emp->email }}</td>
                    <td>{{ $emp->phone }}</td>
                    <td>{{ $emp->position }}</td>
                    <td>{{ number_format($emp->salary, 0, ',', '.') }} đ</td>
                    <td>
                        <form action="{{ route('employees.destroy', $emp) }}" method="POST"
                            onsubmit="return confirm('Xóa nhân viên?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
<!-- Modal Thêm nhân viên -->
<div class="modal fade @if($errors->any()) show d-block @endif" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="{{ $errors->any() ? 'false' : 'true' }}" style="{{ $errors->any() ? 'background-color: rgba(0,0,0,0.5);' : '' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('employees.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Thêm nhân viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label>Tên</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Mật khẩu</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Nhập lại mật khẩu</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Điện thoại</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>

                    <div class="mb-3">
                        <label>Chức vụ</label>
                        <input type="text" name="position" class="form-control" value="{{ old('position') }}">
                    </div>

                    <div class="mb-3">
                        <label>Lương</label>
                        <input type="number" name="salary" class="form-control" value="{{ old('salary') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection