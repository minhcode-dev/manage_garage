@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Thống kê và báo cáo</h2>

    {{-- Bộ lọc theo năm --}}
    <form method="GET" action="{{ route('report.index') }}" class="mb-4">
        <label for="year">Chọn năm:</label>
        <select name="year" id="year" class="form-control w-auto d-inline-block">
            @for ($y = date('Y'); $y >= 2020; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <button class="btn btn-primary ml-2">Lọc</button>
        <a href="{{ route('report.pdf', ['year' => $year]) }}" class="btn btn-danger ml-2">Xuất PDF</a>
    </form>

    {{-- Tổng kết --}}
    <div class="mb-4">
        <p><strong>Tổng doanh thu năm {{ $year }}:</strong> {{ number_format($revenueByMonth->sum('total_revenue'), 0, ',', '.') }} VNĐ</p>
        <p><strong>Tổng số đơn hàng:</strong> {{ $totalOrders }}</p>
        <p><strong>Tổng số khách hàng sử dụng dịch vụ:</strong> {{ $totalCustomers }}</p>
    </div>

    {{-- Biểu đồ doanh thu --}}
    <canvas id="revenueChart" width="600" height="300"></canvas>

    <h4 class="mt-4">Doanh thu theo tháng trong năm {{ $year }}</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tháng</th>
                <th>Doanh thu (VNĐ)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($revenueByMonth as $data)
                <tr>
                    <td>Tháng {{ $data->month }}</td>
                    <td>{{ number_format($data->total_revenue, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Khách hàng sử dụng dịch vụ nhiều nhất</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Khách hàng</th>
                <th>Số đơn sử dụng dịch vụ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topCustomers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->orders_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($revenueByMonth->pluck('month')->map(fn($m) => 'Tháng ' . $m)) !!},
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: {!! json_encode($revenueByMonth->pluck('total_revenue')) !!},
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
