<?php require 'layout/header.php'; ?>

<style>
    :root {
        --shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        --shadow-hover: 0 8px 12px rgba(0, 0, 0, 0.15);
        --divider-color: black; /* Red color for dividers */
        --text-color: #343a40; /* Dark text for buttons */
    }

    html, body {
        height: 100%;
        margin: 0;
        font-family: system-ui, -apple-system, sans-serif;
    }

    .container-fluid {
        display: flex;
        min-height: 100vh;
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

    .sidebar-title {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
        color: var(--text-color);
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
        background-color: transparent; /* No background color */
        /* No border */
    }

    .btn-function:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        background-color: rgba(0, 0, 0, 0.05); /* Subtle hover background */
    }

    .divider {
        border: 0;
        background: var(--divider-color); /* Red divider */
        margin: 0.5rem 0; /* Consistent spacing */
            width: 100%; /* Full sidebar width */
    }

    .main-content {
        flex: 1;
        padding: 2rem;
        background: #fff;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
        }
        .btn-function {
            font-size: 0.75rem;
            height: 40px;
            line-height: 40px;
        }
        .sidebar-title {
            font-size: 1.25rem;
        }
        .divider {
            margin: 0.4rem 0;
            width: 100%; /* Full width on medium screens */
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            flex-direction: column;
        }
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }
        .btn-function {
            height: 40px;
            line-height: 40px;
        }
        .main-content {
            padding: 1rem;
        }
        .divider {
            margin: 0.4rem 0;
            width: 100%; /* Full width on mobile */
        }
    }
</style>

<div class="container-fluid">
    <div class="sidebar">
        <a href="customers.php" class="btn btn-function">Quản lý khách hàng</a>
        <hr class="divider">
        <a href="vehicles.php" class="btn btn-function">Quản lý xe</a>
        <hr class="divider">
        <a href="services.php" class="btn btn-function">Quản lý dịch vụ</a>
        <hr class="divider">
        <a href="invoices.php" class="btn btn-function">Quản lý hóa đơn</a>
        <hr class="divider">
        <a href="parts_inventory.php" class="btn btn-function">Quản lý kho phụ tùng</a>
        <hr class="divider">
        <a href="employees.php" class="btn btn-function">Quản lý nhân viên</a>
        <hr class="divider">
        <a href="reports.php" class="btn btn-function">Thống kê - báo cáo</a>
        <hr class="divider">
        <a href="appointments.php" class="btn btn-function">Lịch hẹn sửa xe</a>
    </div>
    <div class="main-content">
        <h3>Chào mừng đến với hệ thống quản lý</h3>
        <p>Vui lòng chọn một chức năng từ thanh bên để bắt đầu.</p>
    </div>
</div>

