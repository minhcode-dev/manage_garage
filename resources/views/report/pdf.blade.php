<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo PDF</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
    </style>
</head>
<body>

<h2>Báo cáo năm {{ $year }}</h2>
<p>Tổng doanh thu: {{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</p>
<p>Tổng số đơn hàng: {{ $totalOrders }}</p>
<p>Tổng số khách hàng sử dụng dịch vụ: {{ $totalCustomers }}</p>

<h4>Doanh thu theo tháng</h4>
<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Tháng</th>
            <th>Doanh thu (VNĐ)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($monthlyRevenue as $data)
            <tr>
                <td>{{ $data['month'] }}</td>
                <td>{{ number_format($data['total_revenue'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<h4>Top khách hàng</h4>
<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Tên khách hàng</th>
            <th>Số lần sử dụng dịch vụ</th>
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
