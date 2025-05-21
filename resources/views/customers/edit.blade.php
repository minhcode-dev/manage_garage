@extends('layout')

@section('content')
<h2>Sửa khách hàng</h2>
<form method="POST" action="{{ route('customers.update', $customer) }}">
    @csrf @method('PUT')
    Tên: <input name="name" value="{{ $customer->name }}"><br>
    SĐT: <input name="phone" value="{{ $customer->phone }}"><br>
    Email: <input name="email" value="{{ $customer->email }}"><br>
    Địa chỉ: <input name="address" value="{{ $customer->address }}"><br>
    <button type="submit">Cập nhật</button>
</form>
@endsection
