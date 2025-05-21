@extends('layouts.app')

@section('content')
  <h2>Danh sách khách hàng</h2>

  <div class="d-flex justify-content-between mb-3">
    <!-- Nút mở modal thêm khách hàng -->
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
    + Thêm khách hàng
    </button>

    <!-- Form tìm kiếm -->
    <form action="{{ route('customers.index') }}" method="GET" class="d-flex" style="width: 300px;">
    <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm..."
      value="{{ request('search') }}" aria-label="Search">
    <button class="btn btn-outline-primary" type="submit">Tìm</button>
    </form>
  </div>

  <!-- Modal thêm khách hàng -->
  <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('customers.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="addCustomerModalLabel">Thêm khách hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
        <label for="name" class="form-label">Tên</label>
        <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
        <label for="phone" class="form-label">SĐT</label>
        <input type="text" class="form-control" id="phone" name="phone">
        </div>
        <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="mb-3">
        <label for="address" class="form-label">Địa chỉ</label>
        <input type="text" class="form-control" id="address" name="address">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-primary">Lưu</button>
      </div>
      </form>
    </div>
    </div>
  </div>

  <!-- Bảng danh sách khách hàng -->
  <table class="table table-bordered">
    <thead>
    <tr>
      <th>ID</th>
      <th>Tên</th>
      <th>Điện thoại</th>
      <th>Email</th>
      <th>Hành động</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($customers as $customer)
    <tr>
      <td>{{ $customer->id }}</td>
      <td>{{ $customer->name }}</td>
      <td>{{ $customer->phone }}</td>
      <td>{{ $customer->email }}</td>
      <td>
      <!-- Nút sửa mở modal sửa -->
      <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
      data-bs-target="#editCustomerModal{{ $customer->id }}">
      Sửa
      </button>

      <form method="POST" action="{{ route('customers.destroy', $customer) }}" style="display:inline;">
      @csrf @method('DELETE')
      <button type="submit" class="btn btn-sm btn-danger"
      onclick="return confirm('Bạn có chắc muốn xóa khách hàng này?')">Xóa</button>
      </form>
      </td>
    </tr>

    <!-- Modal sửa khách hàng -->
    <div class="modal fade" id="editCustomerModal{{ $customer->id }}" tabindex="-1"
      aria-labelledby="editCustomerModalLabel{{ $customer->id }}" aria-hidden="true">
      <div class="modal-dialog">
      <div class="modal-content">
      <form method="POST" action="{{ route('customers.update', $customer) }}">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="editCustomerModalLabel{{ $customer->id }}">Sửa khách hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
        <label for="name{{ $customer->id }}" class="form-label">Tên</label>
        <input type="text" class="form-control" id="name{{ $customer->id }}" name="name"
        value="{{ $customer->name }}" required>
        </div>
        <div class="mb-3">
        <label for="phone{{ $customer->id }}" class="form-label">SĐT</label>
        <input type="text" class="form-control" id="phone{{ $customer->id }}" name="phone"
        value="{{ $customer->phone }}">
        </div>
        <div class="mb-3">
        <label for="email{{ $customer->id }}" class="form-label">Email</label>
        <input type="email" class="form-control" id="email{{ $customer->id }}" name="email"
        value="{{ $customer->email }}">
        </div>
        <div class="mb-3">
        <label for="address{{ $customer->id }}" class="form-label">Địa chỉ</label>
        <input type="text" class="form-control" id="address{{ $customer->id }}" name="address"
        value="{{ $customer->address }}">
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
@endsection