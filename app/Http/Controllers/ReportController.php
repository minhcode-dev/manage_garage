<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RepairOrder;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Thêm ở đầu file nếu dùng Laravel DomPDF

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Lấy năm từ request hoặc mặc định là năm hiện tại
        $year = $request->get('year', date('Y'));

        // Doanh thu theo tháng trong năm được chọn
        $revenueByMonth = RepairOrder::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Khách hàng sử dụng dịch vụ nhiều nhất
        $topCustomers = Customer::select(
                'customers.id',
                'customers.name',
                DB::raw('COUNT(repair_orders.id) as orders_count')
            )
            ->join('repair_orders', 'customers.id', '=', 'repair_orders.customer_id')
            ->whereYear('repair_orders.created_at', $year)
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('orders_count')
            ->limit(5)
            ->get();

        // Tổng doanh thu cả năm
        $totalRevenue = $revenueByMonth->sum('total_revenue');

        // Tổng số đơn hàng trong năm
        $totalOrders = RepairOrder::whereYear('created_at', $year)->count();

        // Tổng số khách hàng sử dụng dịch vụ trong năm (không trùng)
        $totalCustomers = RepairOrder::whereYear('created_at', $year)->distinct('customer_id')->count('customer_id');

        // Truyền tất cả dữ liệu sang view
        return view('report.index', compact(
            'revenueByMonth',
            'topCustomers',
            'year',
            'totalRevenue',
            'totalOrders',
            'totalCustomers'
        ));
    }
    public function exportPdf(Request $request)
{
    $year = $request->get('year', date('Y'));

    $revenueByMonth = RepairOrder::select(
        DB::raw('MONTH(created_at) as month'),
        DB::raw('SUM(total_amount) as total_revenue')
    )
    ->whereYear('created_at', $year)
    ->groupBy('month')
    ->orderBy('month')
    ->get();

    $monthlyRevenue = collect(range(1, 12))->map(function ($month) use ($revenueByMonth) {
        $data = $revenueByMonth->firstWhere('month', $month);
        return [
            'month' => $month,
            'total_revenue' => $data ? $data->total_revenue : 0
        ];
    });

    $topCustomers = Customer::select(
        'customers.id',
        'customers.name',
        DB::raw('COUNT(repair_orders.id) as orders_count')
    )
    ->join('repair_orders', 'customers.id', '=', 'repair_orders.customer_id')
    ->whereYear('repair_orders.created_at', $year)
    ->groupBy('customers.id', 'customers.name')
    ->orderByDesc('orders_count')
    ->limit(5)
    ->get();

    $totalRevenue = $monthlyRevenue->sum('total_revenue');
    $totalOrders = RepairOrder::whereYear('created_at', $year)->count();
    $totalCustomers = RepairOrder::whereYear('created_at', $year)->distinct('customer_id')->count('customer_id');

    $pdf = Pdf::loadView('report.pdf', compact(
        'monthlyRevenue',
        'topCustomers',
        'year',
        'totalRevenue',
        'totalOrders',
        'totalCustomers'
    ));

    return $pdf->download("bao-cao-nam-$year.pdf");
}
}
