@extends('layouts.app')

@section('content')
<h2>Sửa xe</h2>

<form method="POST" action="{{ route('vehicles.update', $vehicle) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Biển số</label>
        <input type="text" name="license_plate" class="form-control"
            value="{{ old('license_plate', $vehicle->license_plate) }}" required>
        @error('license_plate')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label>Hãng</label>
        <input type="text" name="brand" class="form-control" value="{{ old('brand', $vehicle->brand) }}" required>
        @error('brand')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label>Mẫu</label>
        <input type="text" name="model" class="form-control" value="{{ old('model', $vehicle->model) }}" required>
        @error('model')<div class="text-danger">{{ $message }}</div>@
        <div class="mb-3">
            <label>Năm sản xuất</label>
            <input type="number" name="year" class="form-control" value="{{ old('year', $vehicle->year) }}">
            @error('year')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label>Màu sắc</label>
            <input type="text" name="color" class="form-control" value="{{ old('color', $vehicle->color) }}">
            @error('color')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label>Chủ xe</label>
            <select name="owner_id" class="form-control">
                <option value="">-- Chọn khách hàng --</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ old('owner_id', $vehicle->owner_id) == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
            @error('owner_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label>Ảnh xe</label><br>
            @if($vehicle->image)
                <img src="{{ asset('storage/' . $vehicle->image) }}" alt="Ảnh xe" width="150"><br>
            @endif
            <input type="file" name="image" class="form-control" accept="image/*">
            @error('image')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form> @endsection 