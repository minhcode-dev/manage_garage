@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Tạo đơn sửa chữa</h2>

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

    <form action="{{ route('repair_orders.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Khách hàng</label>
            <select name="customer_id" class="form-control" required>
                <option value="">-- Chọn khách hàng --</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
            @error('customer_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label>Xe</label>
            <select name="vehicle_id" class="form-control" required>
                <option value="">-- Chọn xe --</option>
                @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }}</option>
                @endforeach
            </select>
            @error('vehicle_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label>Nhân viên sửa chữa</label>
            <select name="employee_id" class="form-control" required>
                <option value="">-- Chọn nhân viên --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
            @error('employee_id')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label>Phương thức thanh toán</label>
            <select name="payment_method" class="form-control" required>
                <option value="">-- Chọn phương thức --</option>
                <option value="tien_mat">Tiền mặt</option>
                <option value="chuyen_khoan">Chuyển khoản</option>
                <option value="the_tin_dung">Thẻ tín dụng</option>
            </select>
            @error('payment_method')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <hr>

        <h4>Dịch vụ</h4>
        <div id="services-wrapper">
            <div class="service-row row mb-2">
                <div class="col-11">
                    <select name="service_ids[]" class="form-control">
                        <option value="">-- Chọn dịch vụ --</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} ({{ number_format($service->price) }} VNĐ)</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-danger btn-remove-service">×</button>
                </div>
            </div>
        </div>
        <button type="button" id="btn-add-service" class="btn btn-secondary btn-sm mb-3">Thêm dịch vụ</button>

        <hr>

        <h4>Phụ tùng</h4>
        <div id="parts-wrapper">
            <div class="part-row row mb-2">
                <div class="col-8">
                    <select name="part_ids[]" class="form-control part-select">
                        <option value="">-- Chọn phụ tùng --</option>
                        @foreach($parts as $part)
                            <option value="{{ $part->id }}">{{ $part->name }} ({{ number_format($part->price) }} VNĐ)</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <input type="number" name="part_quantities[]" class="form-control" value="1" min="1" placeholder="Số lượng">
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-danger btn-remove-part">×</button>
                </div>
            </div>
        </div>
        <button type="button" id="btn-add-part" class="btn btn-secondary btn-sm mb-3">Thêm phụ tùng</button>

        <hr>

        <div class="mb-3">
            <label>Ghi chú</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Tạo đơn sửa chữa</button>
    </form>
</div>

<script>
    let serviceIndex = 1;
    let partIndex = 1;

    document.getElementById('btn-add-service').addEventListener('click', function() {
        let wrapper = document.getElementById('services-wrapper');
        let newRow = document.createElement('div');
        newRow.classList.add('service-row', 'row', 'mb-2');
        newRow.innerHTML = `
            <div class="col-11">
                <select name="service_ids[]" class="form-control">
                    <option value="">-- Chọn dịch vụ --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }} ({{ number_format($service->price) }} VNĐ)</option>
                    @endforeach
                </select>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-danger btn-remove-service">×</button>
            </div>
        `;
        wrapper.appendChild(newRow);
        serviceIndex++;
    });

    document.getElementById('btn-add-part').addEventListener('click', function() {
        let wrapper = document.getElementById('parts-wrapper');
        let newRow = document.createElement('div');
        newRow.classList.add('part-row', 'row', 'mb-2');
        newRow.innerHTML = `
            <div class="col-8">
                <select name="part_ids[]" class="form-control part-select">
                    <option value="">-- Chọn phụ tùng --</option>
                    @foreach($parts as $part)
                        <option value="{{ $part->id }}">{{ $part->name }} ({{ number_format($part->price) }} VNĐ)</option>
                    @endforeach
                </select>
            </div>
            <div class="col-3">
                <input type="number" name="part_quantities[]" class="form-control" value="1" min="1" placeholder="Số lượng">
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-danger btn-remove-part">×</button>
            </div>
        `;
        wrapper.appendChild(newRow);
        partIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-service')) {
            e.target.closest('.service-row').remove();
        }
        if (e.target.classList.contains('btn-remove-part')) {
            e.target.closest('.part-row').remove();
        }
    });
</script>
@endsection