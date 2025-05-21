@extends('layouts.app')

@section('content')
<h2>Thêm nhân viên</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('employees.store') }}">
    @csrf
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

