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
      <th>Lịch sử sửa chữa</th>
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
  @if ($customer->repairOrders->isNotEmpty())
    <!-- Nút xem lịch sử mở modal -->
    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
      data-bs-target="#viewHistoryModal{{ $customer->id }}">
      Lịch sử
    </button>

    <!-- Modal xem lịch sử (ví dụ) -->
    <div class="modal fade" id="viewHistoryModal{{ $customer->id }}" tabindex="-1" aria-labelledby="viewHistoryLabel{{ $customer->id }}" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewHistoryLabel{{ $customer->id }}">Lịch sử sửa chữa - {{ $customer->name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
          </div>
          <div class="modal-body">
          <ul class="list-group">
@foreach ($customer->repairOrders as $order)
  <li class="list-group-item">
    <strong>Ngày sửa:</strong> {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}<br>
    <strong>Tổng tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }}₫<br>
    <strong>Trạng thái sửa chữa:</strong> {{ $order->repair_status }}<br>
    <strong>Thanh toán:</strong> {{ $order->payment_status }} ({{ $order->payment_method }})<br>
    <strong>Ghi chú:</strong> {{ $order->notes ?? 'Không có ghi chú' }}<br>

    {{-- Danh sách phụ tùng --}}
    @if ($order->parts->count())
      <strong>Phụ tùng đã dùng:</strong>
      <ul class="mb-1">
        @foreach ($order->parts as $part)
          <li>
            {{ $part->name }} - SL: {{ $part->pivot->quantity }} - Giá: {{ number_format($part->price, 0, ',', '.') }}₫
          </li>
        @endforeach
      </ul>
    @else
      <em>Không có phụ tùng sử dụng</em><br>
    @endif

    {{-- Danh sách dịch vụ --}}
    @if ($order->services->count())
      <strong>Dịch vụ:</strong>
      <ul>
        @foreach ($order->services as $service)
          <li>{{ $service->name }} - {{ number_format($service->price, 0, ',', '.') }}₫</li>
        @endforeach
      </ul>
    @else
      <em>Không có dịch vụ</em>
    @endif
  </li>
@endforeach
</ul>

          </div>
        </div>
      </div>
    </div>
  @else
    <span class="text-muted">Không có</span>
  @endif
</td>

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