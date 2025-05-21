<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Hệ thống quản lý</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ✅ Thêm Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        :root {
            --shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 8px 12px rgba(0, 0, 0, 0.15);
            --divider-color: black;
            --text-color: #343a40;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            font-family: system-ui, -apple-system, sans-serif;
        }

        .container-fluid {
            display: flex;
            min-height: 100vh;
            padding: 0;
        }

        .sidebar {
            width: 250px;
            background: #f8f9fa;
            padding: 1rem;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .btn-function {
            display: block;
            font-size: 0.875rem;
            border-radius: 6px;
            box-shadow: var(--shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            font-weight: 600;
            text-align: center;
            line-height: 45px;
            text-decoration: none;
            color: var(--text-color);
            background-color: transparent;
        }

        .btn-function:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            background-color: rgba(0, 0, 0, 0.05);
        }

        .divider {
            border: 0;
            background: var(--divider-color);
            margin: 0.5rem 0;
            width: 100%;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
            background: #fff;
        }

        .navbar-custom {
            background-color: orange;
        }

        .navbar-custom .navbar-brand {
            color: #fff !important;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .navbar-custom .nav-link,
        .navbar-custom .dropdown-toggle {
            color: #fff !important;
        }

        .navbar-custom .dropdown-menu {
            right: 0;
            left: auto;
        }
    </style>
</head>

<body>

    <!-- ✅ Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="mx-auto">
            <a class="navbar-brand" href="#">Garage Management</a>
        </div>

        <div class="dropdown ms-auto me-3">
            <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                @if (Auth::check())
                    Xin chào, {{ Auth::user()->name }}
                @endif
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                <li><a class="dropdown-item" href="{{ url('/logout') }}">Đăng xuất</a></li>
            </ul>
        </div>
    </nav>

    <!-- ✅ Layout -->
    <div class="container-fluid">
        <div class="sidebar">
            <a href="{{ url('customers') }}" class="btn btn-function">Quản lý khách hàng</a>
            <hr class="divider">
            <a href="{{ url('vehicles') }}" class="btn btn-function">Quản lý xe</a>
            <hr class="divider">
            <a href="{{ url('services') }}" class="btn btn-function">Quản lý dịch vụ</a>
            <hr class="divider">
            <a href="{{ url('repair_order') }}" class="btn btn-function">Quản lý đơn sửa chữa</a>
            <hr class="divider">
            <a href="{{ url('parts') }}" class="btn btn-function">Quản lý kho phụ tùng</a>
            <hr class="divider">
            <a href="{{ url('employees') }}" class="btn btn-function">Quản lý nhân viên</a>
            <hr class="divider">
            <a href="{{ url('reports') }}" class="btn btn-function">Thống kê - báo cáo</a>
            <hr class="divider">
            <a href="{{ url('appointments') }}" class="btn btn-function">Lịch hẹn sửa xe</a>
        </div>
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <!-- ✅ Bootstrap Bundle JS (dropdown sẽ hoạt động) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>

</html>