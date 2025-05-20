<?php

class MainController
{
    public function handleRequest()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

        switch ($page) {
            case 'dashboard':
                $this->dashboard();
                break;
            case 'customers':
                $this->customers();
                break;
            case 'vehicles':
                $this->vehicles();
                break;
            case 'services':
                $this->services();
                break;
            case 'repair_orders':
                $this->repairOrders();
                break;
            case 'invoices':
                $this->invoices();
                break;
            case 'parts_inventory':
                $this->partsInventory();
                break;
            case 'employees':
                $this->employees();
                break;
            case 'reports':
                $this->reports();
                break;
            case 'appointments':
                $this->appointments();
                break;
            default:
                $this->pageNotFound();
                break;
        }
    }

    private function dashboard()
    {
        // Hiển thị view dashboard với các nút chức năng
        require 'views/dashboard.php';
    }

    private function customers()
    {
        // Hiển thị trang quản lý khách hàng
        require 'views/customers.php';
    }

    private function vehicles()
    {
        // Hiển thị trang quản lý xe
        require 'views/vehicles.php';
    }

    private function services()
    {
        // Hiển thị trang quản lý dịch vụ
        require 'views/services.php';
    }

    private function repairOrders()
    {
        // Hiển thị trang phiếu sửa chữa
        require 'views/repair_orders.php';
    }

    private function invoices()
    {
        // Hiển thị trang hóa đơn
        require 'views/invoices.php';
    }

    private function partsInventory()
    {
        // Hiển thị trang kho phụ tùng
        require 'views/parts_inventory.php';
    }

    private function employees()
    {
        // Hiển thị trang quản lý nhân viên
        require 'views/employees.php';
    }

    private function reports()
    {
        // Hiển thị trang báo cáo thống kê
        require 'views/reports.php';
    }

    private function appointments()
    {
        // Hiển thị trang lịch hẹn sửa xe
        require 'views/appointments.php';
    }

    private function pageNotFound()
    {
        http_response_code(404);
        echo "<h1>404 - Trang không tồn tại</h1>";
        echo "<p><a href='index.php?page=dashboard'>Quay lại Dashboard</a></p>";
    }
}
