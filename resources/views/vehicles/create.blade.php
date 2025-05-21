@extends('layouts.app')

@section('content')
<h2>Thêm xe</h2>

<form method="POST" action="{{ route('vehicles.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Biển số</label>
        <input type="text" name="license_plate" class="form-control" value="{{ old('license_plate') }}" required>
        @error('license_plate')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label>Hãng</label>
        <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" required>
        @error('brand')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label>Mẫu</label>
        <input type="text" name="model" class="form-control" value="{{ old('model') }}" required>
        @error('model')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label>Năm sản xuất</label>
        <input type="number" name="year" class="form-control" value="{{ old('year') }}">
        @error('year')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label>Màu sắc</label>
        <input type="text" name="color" class="form-control" value="{{ old('color') }}">
        @error('color')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label>Chủ xe</label>
        <select name="owner_id" class="form-control">
            <option value="">-- Chọn khách hàng --</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" {{ old('owner_id') == $customer->id ? 'selected' : '' }}>
                    {{ $customer->name }}
                </option>
            @endforeach
        </select>
        @error('owner_id')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label>Ảnh xe</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        @error('image')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn btn-primary">Lưu</button>
</form>
@endsection
