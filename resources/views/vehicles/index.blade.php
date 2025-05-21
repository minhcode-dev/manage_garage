@extends('layouts.app')

@section('content')
    <h2>Danh sách xe</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Nút mở modal thêm xe -->
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
            + Thêm xe
        </button>
        <!-- Form tìm kiếm -->
        <form action="{{ route('vehicles.index') }}" method="GET" class="d-flex" style="width: 300px;">
            <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm..."
                value="{{ request('search') }}" aria-label="Search">
            <button class="btn btn-outline-primary" type="submit">Tìm</button>
        </form>

    </div>


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Bảng danh sách xe -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Biển số</th>
                <th>Hãng</th>
                <th>Mẫu</th>
                <th>Năm</th>
                <th>Màu</th>
                <th>Chủ xe</th>
                <th>Ảnh</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->id }}</td>
                    <td>{{ $vehicle->license_plate }}</td>
                    <td>{{ $vehicle->brand }}</td>
                    <td>{{ $vehicle->model }}</td>
                    <td>{{ $vehicle->year }}</td>
                    <td>{{ $vehicle->color }}</td>
                    <td>@if($vehicle->owner)
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#ownerModal{{ $vehicle->id }}">
                            {{ $vehicle->owner->name }}
                        </button>
                    @endif
                    </td>
                    <td>
                        @if($vehicle->image)
                            <img src="{{ asset('storage/' . $vehicle->image) }}" alt="Ảnh xe" width="100">
                        @else
                            Không có ảnh
                        @endif
                    </td>
                    <td>
                        <!-- Nút sửa -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editModal{{ $vehicle->id }}">Sửa</button>

                        <!-- Form xóa -->
                        <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa?')"
                                class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal sửa xe -->
                <div class="modal fade" id="editModal{{ $vehicle->id }}" tabindex="-1"
                    aria-labelledby="editModalLabel{{ $vehicle->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('vehicles.update', $vehicle) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $vehicle->id }}">Sửa xe:
                                        {{ $vehicle->license_plate }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Biển số</label>
                                        <input name="license_plate" class="form-control" value="{{ $vehicle->license_plate }}"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Hãng</label>
                                        <input name="brand" class="form-control" value="{{ $vehicle->brand }}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Mẫu</label>
                                        <input name="model" class="form-control" value="{{ $vehicle->model }}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Năm</label>
                                        <input name="year" type="number" class="form-control" value="{{ $vehicle->year }}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Màu</label>
                                        <input name="color" class="form-control" value="{{ $vehicle->color }}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Chủ xe</label>
                                        <select name="owner_id" class="form-control select2">
                                            <option value="">-- Chọn chủ xe --</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" {{ $vehicle->owner_id == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }} (ID: {{ $customer->id }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="mb-3">
                                        <label>Ảnh xe (có thể thay mới)</label>
                                        <input type="file" name="image" class="form-control">
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

    <!-- Modal thêm xe -->
    <div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('vehicles.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addVehicleModalLabel">Thêm xe mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Biển số</label>
                            <input name="license_plate" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Hãng</label>
                            <input name="brand" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Mẫu</label>
                            <input name="model" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Năm</label>
                            <input name="year" type="number" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Màu</label>
                            <input name="color" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Chủ xe</label>
                            <select name="owner_id" class="form-control select2">
                                <option value="">-- Chọn chủ xe --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} (ID: {{ $customer->id }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Ảnh xe</label>
                            <input type="file" name="image" class="form-control">
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
    @if($vehicle->owner)
        <div class="modal fade" id="ownerModal{{ $vehicle->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thông tin chủ xe</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Tên:</strong> {{ $vehicle->owner->name }}</p>
                        <p><strong>Email:</strong> {{ $vehicle->owner->email }}</p>
                        <p><strong>SĐT:</strong> {{ $vehicle->owner->phone }}</p>
                        <p><strong>Địa chỉ:</strong> {{ $vehicle->owner->address }}</p>
                        <!-- Thêm các trường khác nếu có -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
@push('scripts')
    <!-- jQuery trước nếu chưa có -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Kích hoạt select2 -->
    <script>
        $('#addVehicleModal, .modal').on('shown.bs.modal', function () {
            $(this).find('.select2').select2({
                dropdownParent: $(this),
                placeholder: "Tìm tên khách hàng...",
                allowClear: true
            });
        });

    </script>
@endpush