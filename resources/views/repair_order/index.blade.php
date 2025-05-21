@extends('layouts.app')

@section('content')
<h2>Danh sách đơn sửa chữa</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Nút mở modal thêm đơn -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#repairOrderModal">
  + Thêm đơn
</button>

<table class="table">
    <thead>
    <tr>
        <th>Khách hàng</th>
        <th>Xe</th>
        <th>Dịch vụ</th>
        <th>Phụ tùng</th>
        <th>Ngày tạo</th>
        <th>Tổng tiền</th>
    </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->customer_name }}</td>
            <td>{{ $order->vehicle_info }}</td>
            <td>
                <ul>
                    @foreach($order->services as $service)
                        <li>{{ $service->name }}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <ul>
                    @foreach($order->parts as $part)
                        <li>{{ $part->name }} ({{ $part->pivot->quantity }})</li>
                    @endforeach
                </ul>
            </td>
            <td>{{ $order->created_at->format('d/m/Y') }}</td>
            <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Modal thêm/sửa đơn sửa chữa -->
<div class="modal fade" id="repairOrderModal" tabindex="-1" aria-labelledby="repairOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('repair_orders.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="repairOrderModalLabel">Thêm đơn sửa chữa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <div class="modal-body">
          <!-- Chọn khách hàng -->
          <div class="mb-3">
            <label for="customer_id" class="form-label">Khách hàng</label>
            <select class="form-select" name="customer_id" id="customer_id" required>
              <option value="">Chọn khách hàng</option>
              @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Chọn xe -->
          <div class="mb-3">
            <label for="vehicle_id" class="form-label">Xe</label>
            <select class="form-select" name="vehicle_id" id="vehicle_id" required>
              <option value="">Chọn xe</option>
              @foreach($vehicles as $vehicle)
                <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }} - {{ $vehicle->model }}</option>
              @endforeach
            </select>
          </div>

          <!-- Chọn dịch vụ (checkbox) -->
          <div class="mb-3">
            <label class="form-label">Dịch vụ</label>
            <div class="row">
              @foreach($services as $service)
                <div class="col-md-4">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="service_ids[]" value="{{ $service->id }}" id="service{{ $service->id }}">
                    <label class="form-check-label" for="service{{ $service->id }}">{{ $service->name }}</label>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <!-- Chọn phụ tùng (checkbox + số lượng) -->
          <div class="mb-3">
            <label class="form-label">Phụ tùng</label>
            <div class="row">
              @foreach($parts as $part)
                <div class="col-md-6 d-flex align-items-center mb-2">
                  <div class="form-check flex-grow-1">
                    <input class="form-check-input part-checkbox" type="checkbox" name="part_ids[]" value="{{ $part->id }}" id="part{{ $part->id }}">
                    <label class="form-check-label" for="part{{ $part->id }}">{{ $part->name }} ({{ $part->stock }} trong kho)</label>
                  </div>
                  <input type="number" min="1" name="part_quantities[{{ $part->id }}]" class="form-control ms-3 part-quantity" placeholder="Số lượng" style="width: 80px;" disabled>
                </div>
              @endforeach
            </div>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Script kích hoạt input số lượng khi checkbox phụ tùng được chọn -->
<script>
  document.querySelectorAll('.part-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      const quantityInput = this.closest('div').querySelector('.part-quantity');
      quantityInput.disabled = !this.checked;
      if (!this.checked) quantityInput.value = '';
    });
  });
</script>

@endsection
