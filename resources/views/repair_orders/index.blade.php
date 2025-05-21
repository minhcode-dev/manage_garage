@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Danh sách đơn sửa chữa</h2>
    <div>
        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm theo tên khách hàng..." style="width: 300px;">
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Nút mở modal thêm đơn -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#repairOrderModal">
    + Thêm đơn
</button>

<table class="table" id="repairOrdersTable">
    <thead>
        <tr>
            <th>Khách hàng</th>
            <th>Xe</th>
            <th>Dịch vụ</th>
            <th>Phụ tùng</th>
            <th>Phương thức thanh toán</th>
            <th>Ngày tạo</th>
            <th>Tổng tiền</th>
            <th>Trạng thái sửa chữa</th>
            <th>Trạng thái thanh toán</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
            <tr data-id="{{ $order->id }}" data-customer-name="{{ $order->customer->name }}">
                <td>
                    <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#customerModal{{ $order->id }}">{{ $order->customer->name }}</button>
                    <!-- Modal thông tin khách hàng -->
                    <div class="modal fade" id="customerModal{{ $order->id }}" tabindex="-1" aria-hidden="true" data-bs-scroll="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Thông tin khách hàng</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Họ tên:</strong> {{ $order->customer->name }}</p>
                                    <p><strong>SĐT:</strong> {{ $order->customer->phone ?? '-' }}</p>
                                    <p><strong>Email:</strong> {{ $order->customer->email ?? '-' }}</p>
                                    <p><strong>Địa chỉ:</strong> {{ $order->customer->address ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#vehicleModal{{ $order->id }}">{{ $order->vehicle->license_plate }}</button>
                    <!-- Modal thông tin xe -->
                    <div class="modal fade" id="vehicleModal{{ $order->id }}" tabindex="-1" aria-hidden="true" data-bs-scroll="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Thông tin xe</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Biển số:</strong> {{ $order->vehicle->license_plate }}</p>
                                    <p><strong>Model:</strong> {{ $order->vehicle->model }}</p>
                                    <p><strong>Hãng:</strong> {{ $order->vehicle->brand }}</p>
                                    <p><strong>Năm:</strong> {{ $order->vehicle->year }}</p>
                                    <p><strong>Ảnh:</strong></p>
                                    @if(!empty($order->vehicle->image))
                                        <img src="{{ asset('storage/' . $order->vehicle->image) }}" alt="Ảnh xe" class="img-fluid mt-3" style="max-height: 100px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
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
                <td>
                    @switch($order->payment_method)
                        @case('tien_mat')
                            Tiền mặt
                            @break
                        @case('chuyen_khoan')
                            Chuyển khoản
                            @break
                        @case('the_tin_dung')
                            Thẻ tín dụng
                            @break
                        @default
                            -
                    @endswitch
                </td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>
                    <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#totalAmountModal{{ $order->id }}">
                        {{ number_format($order->total_amount, 0, ',', '.') }} đ
                    </button>
                    <!-- Modal chi tiết tổng tiền -->
                    <div class="modal fade" id="totalAmountModal{{ $order->id }}" tabindex="-1" aria-hidden="true" data-bs-scroll="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Chi tiết thanh toán</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Dịch vụ:</strong></p>
                                    <ul>
                                        @foreach($order->services as $service)
                                            <li>{{ $service->name }} - Giá: {{ number_format($service->price, 0, ',', '.') }} đ</li>
                                        @endforeach
                                    </ul>
                                    <p><strong>Phụ tùng:</strong></p>
                                    <ul>
                                        @foreach($order->parts as $part)
                                            <li>{{ $part->name }} (x{{ $part->pivot->quantity }}) - Giá: {{ number_format($part->price * $part->pivot->quantity, 0, ',', '.') }} đ</li>
                                        @endforeach
                                    </ul>
                                    <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} đ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <form method="POST" action="{{ route('repair_orders.update_status', $order->id) }}">
                        @csrf
                        @method('PATCH')
                        <select name="repair_status" class="form-select form-select-sm">
                            <option value="dang_sua" {{ $order->repair_status == 'dang_sua' ? 'selected' : '' }}>Đang sửa</option>
                            <option value="hoan_thanh" {{ $order->repair_status == 'hoan_thanh' ? 'selected' : '' }}>Hoàn thành</option>
                        </select>
                    </form>
                </td>
                <td>
                    <form method="POST" action="{{ route('repair_orders.update_payment_status', $order->id) }}">
                        @csrf
                        @method('PATCH')
                        <select name="payment_status" class="form-select form-select-sm">
                            <option value="chua_thanh_toan" {{ $order->payment_status == 'chua_thanh_toan' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="da_thanh_toan" {{ $order->payment_status == 'da_thanh_toan' ? 'selected' : '' }}>Đã thanh toán</option>
                        </select>
                    </form>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#editRepairOrderModal{{ $order->id }}">Sửa</button>
                    <form method="POST" action="{{ route('repair_orders.destroy', $order->id) }}" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                    <!-- Modal sửa đơn sửa chữa -->
                    <div class="modal fade" id="editRepairOrderModal{{ $order->id }}" tabindex="-1" aria-labelledby="editRepairOrderModalLabel{{ $order->id }}" aria-hidden="true" data-bs-scroll="false">
                        <div class="modal-dialog modal-lg">
                            <form method="POST" action="{{ route('repair_orders.update', $order->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editRepairOrderModalLabel{{ $order->id }}">Sửa đơn sửa chữa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Chọn khách hàng -->
                                        <div class="mb-3">
                                            <label for="customer_id_{{ $order->id }}" class="form-label">Khách hàng</label>
                                            <select class="form-select select2" name="customer_id" id="customer_id_{{ $order->id }}" required>
                                                <option value="">Chọn khách hàng</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}" {{ $order->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>

                                        <!-- Chọn xe -->
                                        <div class="mb-3">
                                            <label for="vehicle_id_{{ $order->id }}" class="form-label">Xe</label>
                                            <select class="form-select select2" name="vehicle_id" id="vehicle_id_{{ $order->id }}" required>
                                                <option value="">Chọn xe</option>
                                                @foreach($vehicles as $vehicle)
                                                    <option value="{{ $vehicle->id }}" {{ $order->vehicle_id == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->license_plate }} - {{ $vehicle->model }}</option>
                                                @endforeach
                                            </select>
                                            @error('vehicle_id')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>

                                        <!-- Chọn nhân viên -->
                                        <div class="mb-3">
                                            <label for="employee_id_{{ $order->id }}" class="form-label">Nhân viên sửa chữa</label>
                                            <select class="form-select select2" name="employee_id" id="employee_id_{{ $order->id }}" required>
                                                <option value="">Chọn nhân viên</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}" {{ $order->employee_id == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('employee_id')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>

                                        <!-- Chọn phương thức thanh toán -->
                                        <div class="mb-3">
                                            <label for="payment_method_{{ $order->id }}" class="form-label">Phương thức thanh toán</label>
                                            <select class="form-select select2" name="payment_method" id="payment_method_{{ $order->id }}" required>
                                                <option value="">Chọn phương thức</option>
                                                <option value="tien_mat" {{ $order->payment_method == 'tien_mat' ? 'selected' : '' }}>Tiền mặt</option>
                                                <option value="chuyen_khoan" {{ $order->payment_method == 'chuyen_khoan' ? 'selected' : '' }}>Chuyển khoản</option>
                                                <option value="the_tin_dung" {{ $order->payment_method == 'the_tin_dung' ? 'selected' : '' }}>Thẻ tín dụng</option>
                                            </select>
                                            @error('payment_method')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>

                                        <!-- Chọn dịch vụ -->
                                        <div class="mb-3">
                                            <label for="service_ids_{{ $order->id }}" class="form-label">Dịch vụ</label>
                                            <select class="form-select select2" name="service_ids[]" id="service_ids_{{ $order->id }}" multiple="multiple">
                                                <option value="">Chọn dịch vụ</option>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}" {{ in_array($service->id, $order->services->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $service->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('service_ids')<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>

                                        <!-- Chọn phụ tùng và số lượng -->
                                        <div class="mb-3">
                                            <label for="part_ids_{{ $order->id }}" class="form-label">Phụ tùng</label>
                                            <select class="form-select select2" name="part_ids[]" id="part_ids_{{ $order->id }}" multiple="multiple">
                                                <option value="">Chọn phụ tùng</option>
                                                @foreach($parts as $part)
                                                    <option value="{{ $part->id }}" data-stock="{{ $part->stock }}" {{ in_array($part->id, $order->parts->pluck('id')->toArray()) ? 'selected' : '' }} data-quantity="{{ $order->parts->where('id', $part->id)->first()->pivot->quantity ?? 1 }}">{{ $part->name }} ({{ $part->stock }} trong kho)</option>
                                                @endforeach
                                            </select>
                                            @error('part_ids')<small class="text-danger">{{ $message }}</small>@enderror
                                            <div id="part-quantities-{{ $order->id }}" class="mt-2">
                                                @foreach($order->parts as $part)
                                                    <div class="mb-2 d-flex align-items-center">
                                                        <label class="form-label me-2" style="min-width: 200px;">{{ $part->name }}</label>
                                                        <input type="number" min="1" max="{{ $part->stock }}" name="part_quantities[{{ $part->id }}]" value="{{ $part->pivot->quantity }}" class="form-control part-quantity" placeholder="Số lượng" style="width: 100px;" required>
                                                        <small class="ms-2 text-muted">Còn {{ $part->stock }} trong kho</small>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Ghi chú -->
                                        <div class="mb-3">
                                            <label for="notes_{{ $order->id }}" class="form-label">Ghi chú</label>
                                            <textarea class="form-control" name="notes" id="notes_{{ $order->id }}" rows="3">{{ $order->notes }}</textarea>
                                            @error('notes')<small class="text-danger">{{ $message }}</small>@enderror
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
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Modal thêm đơn sửa chữa -->
<div class="modal fade" id="repairOrderModal" tabindex="-1" aria-labelledby="repairOrderModalLabel" aria-hidden="true" data-bs-scroll="false">
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
                        <select class="form-select select2" name="customer_id" id="customer_id" required>
                            <option value="">Chọn khách hàng</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <!-- Chọn xe -->
                    <div class="mb-3">
                        <label for="vehicle_id" class="form-label">Xe</label>
                        <select class="form-select select2" name="vehicle_id" id="vehicle_id" required>
                            <option value="">Chọn xe</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }} - {{ $vehicle->model }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <!-- Chọn nhân viên -->
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Nhân viên sửa chữa</label>
                        <select class="form-select select2" name="employee_id" id="employee_id" required>
                            <option value="">Chọn nhân viên</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('employee_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <!-- Chọn phương thức thanh toán -->
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Phương thức thanh toán</label>
                        <select class="form-select select2" name="payment_method" id="payment_method" required>
                            <option value="">Chọn phương thức</option>
                            <option value="tien_mat">Tiền mặt</option>
                            <option value="chuyen_khoan">Chuyển khoản</option>
                            <option value="the_tin_dung">Thẻ tín dụng</option>
                        </select>
                        @error('payment_method')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <!-- Chọn dịch vụ -->
                    <div class="mb-3">
                        <label for="service_ids" class="form-label">Dịch vụ</label>
                        <select class="form-select select2" name="service_ids[]" id="service_ids" multiple="multiple">
                            <option value="">Chọn dịch vụ</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                        @error('service_ids')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <!-- Chọn phụ tùng và số lượng -->
                    <div class="mb-3">
                        <label for="part_ids" class="form-label">Phụ tùng</label>
                        <select class="form-select select2" name="part_ids[]" id="part_ids" multiple="multiple">
                            <option value="">Chọn phụ tùng</option>
                            @foreach($parts as $part)
                                <option value="{{ $part->id }}" data-stock="{{ $part->stock }}">{{ $part->name }} ({{ $part->stock }} trong kho)</option>
                            @endforeach
                        </select>
                        @error('part_ids')<small class="text-danger">{{ $message }}</small>@enderror
                        <div id="part-quantities" class="mt-2"></div>
                    </div>

                    <!-- Ghi chú -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Ghi chú</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')<small class="text-danger">{{ $message }}</small>@enderror
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

@endsection

@push('styles')
    <style>
        .select2-container--open .select2-dropdown {
            max-height: 200px;
            overflow-y: auto;
            z-index: 9999;
        }
        .select2-container--default .select2-results__option {
            padding: 6px 12px;
        }
        .select2-dropdown {
            border: 1px solid #ced4da;
            background-color: #fff;
        }
        .modal {
            overflow-y: hidden !important;
        }
        .modal-dialog {
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-content {
            position: relative;
        }
    </style>
@endpush

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Script xử lý tìm kiếm và Select2 -->
    <script>
        $(document).ready(function () {
            // Initialize Select2 for all select2 elements on page load
            $('.select2').each(function () {
                $(this).select2({
                    placeholder: "Chọn một tùy chọn",
                    allowClear: true,
                    dropdownParent: $(this).closest('.modal')
                });
            });

            // Handle part quantity generation for the ADD modal
            $('#part_ids').on('change', function () {
                const selectedParts = $(this).val() || [];
                const $quantitiesContainer = $('#part-quantities');
                $quantitiesContainer.empty();

                if (selectedParts.length > 0) {
                    selectedParts.forEach(partId => {
                        const $option = $(`#part_ids option[value="${partId}"]`);
                        const partName = $option.text();
                        const stock = $option.data('stock') || 0;
                        const inputHtml = `
                            <div class="mb-2 d-flex align-items-center">
                                <label class="form-label me-2" style="min-width: 200px;">${partName}</label>
                                <input type="number" min="1" max="${stock}" name="part_quantities[${partId}]" class="form-control part-quantity" placeholder="Số lượng" style="width: 100px;" required>
                                <small class="ms-2 text-muted">Còn ${stock} trong kho</small>
                            </div>
                        `;
                        $quantitiesContainer.append(inputHtml);
                    });
                }
            });

            // Handle part quantity generation for all EDIT modals
            $('[id^=editRepairOrderModal]').on('shown.bs.modal', function () {
                const modalId = $(this).attr('id');
                const orderId = modalId.replace('editRepairOrderModal', '');
                const $partSelect = $(`#part_ids_${orderId}`);
                const $quantitiesContainer = $(`#part-quantities-${orderId}`);

                // Initialize Select2 for this specific modal
                $partSelect.select2({
                    placeholder: "Chọn một tùy chọn",
                    allowClear: true,
                    dropdownParent: $(this)
                });

                // Trigger change to populate quantities for already selected parts
                $partSelect.trigger('change');

                // Handle change event for part selection in edit modal
                $partSelect.off('change').on('change', function () {
                    const selectedParts = $(this).val() || [];
                    $quantitiesContainer.empty();

                    if (selectedParts.length > 0) {
                        selectedParts.forEach(partId => {
                            const $option = $(`#part_ids_${orderId} option[value="${partId}"]`);
                            const partName = $option.text();
                            const stock = $option.data('stock') || 0;
                            const existingQuantity = $option.data('quantity') || 1;
                            const inputHtml = `
                                <div class="mb-2 d-flex align-items-center">
                                    <label class="form-label me-2" style="min-width: 200px;">${partName}</label>
                                    <input type="number" min="1" max="${stock}" name="part_quantities[${partId}]" value="${existingQuantity}" class="form-control part-quantity" placeholder="Số lượng" style="width: 100px;" required>
                                    <small class="ms-2 text-muted">Còn ${stock} trong kho</small>
                                </div>
                            `;
                            $quantitiesContainer.append(inputHtml);
                        });
                    }
                });
            });

            // Search by customer name only
            $('#searchInput').on('keyup', function () {
                const searchValue = $(this).val().toLowerCase();
                $('#repairOrdersTable tbody tr').each(function () {
                    const customerName = $(this).data('customer-name') ? $(this).data('customer-name').toLowerCase() : '';
                    if (customerName.includes(searchValue)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Prevent page scroll when opening modals
            $('[data-bs-toggle="modal"]').on('click', function () {
                const scrollPosition = $(window).scrollTop();
                $(this).data('scroll-position', scrollPosition);
            });

            $('.modal').on('hidden.bs.modal', function () {
                const scrollPosition = $(this).data('scroll-position') || 0;
                $(window).scrollTop(scrollPosition);
            });

            // Handle status dropdowns with AJAX to prevent page reload
            $('select[name="repair_status"], select[name="payment_status"]').on('change', function () {
                const scrollPosition = $(window).scrollTop();
                const $form = $(this).closest('form');
                const url = $form.attr('action');
                const data = $form.serialize();

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    success: function (response) {
                        $(window).scrollTop(scrollPosition);
                        if (response.success) {
                            alert(response.success); // Có thể thay bằng toast
                        }
                    },
                    error: function (xhr) {
                        alert('Có lỗi xảy ra, vui lòng thử lại.');
                    }
                });
            });
        });
    </script>
@endpush
