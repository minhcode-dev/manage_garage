<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Danh sách phụ tùng</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Danh sách phụ tùng</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Mã</th>
                <th>Tên</th>
                <th>Đơn vị</th>
                <th>Số lượng tồn</th>
                <th>Giá</th>
                <th>Mô tả</th>
            </tr>
        </thead>
        <tbody>
            @foreach($parts as $part)
                <tr>
                    <td>{{ $part->id }}</td>
                    <td>{{ $part->code }}</td>
                    <td>{{ $part->name }}</td>
                    <td>{{ $part->unit }}</td>
                    <td>{{ $part->stock }}</td>
                    <td>{{ number_format($part->price) }} đ</td>
                    <td>{{ $part->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
