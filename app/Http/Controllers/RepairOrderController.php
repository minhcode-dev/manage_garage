<?php
namespace App\Http\Controllers;

use App\Models\RepairOrder;
use App\Models\Service;
use App\Models\Part;
use Illuminate\Http\Request;

class RepairOrderController extends Controller
{
    // app/Http/Controllers/RepairOrderController.php
public function index()
{
    // Lấy danh sách đơn sửa chữa, kèm quan hệ dịch vụ, phụ tùng, khách hàng, xe
    $orders = RepairOrder::with(['customers', 'vehicle', 'services', 'parts'])->get();
    $customers = \App\Models\Customer::all(); // nếu cần dùng
    $vehicles = \App\Models\Vehicle::all();
    $services = Service::all();
    $parts = Part::all();

    return view('repair_order.index', compact('orders','customers','vehicles','parts','services'));
}

    public function create()
    {
        $customers = \App\Models\Customer::all();
        $vehicles = \App\Models\Vehicle::all();
        $employees = \App\Models\Employee::all();
        $services = Service::all();
        $parts = Part::all();

        return view('repair_order.create', compact('customers', 'vehicles', 'employees', 'services', 'parts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'employee_id' => 'required|exists:employees,id',
            'services' => 'array',
            'parts' => 'array',
            'notes' => 'nullable|string',
        ]);

        $repairOrder = RepairOrder::create([
            'customer_id' => $request->customer_id,
            'vehicle_id' => $request->vehicle_id,
            'employee_id' => $request->employee_id,
            'date' => now(),
            'status' => 'Mới',
            'notes' => $request->notes,
        ]);

        $totalAmount = 0;

        if ($request->has('services')) {
            foreach ($request->services as $serviceData) {
                $serviceId = $serviceData['id'] ?? null;
                $quantity = $serviceData['quantity'] ?? 1;
        
                if (!$serviceId) continue;
        
                $service = Service::find($serviceId);
                if (!$service) continue;
        
                $price = $service->price;
        
                $repairOrder->services()->attach($serviceId, [
                    'quantity' => $quantity,
                    'price' => $price
                ]);
        
                $totalAmount += $price * $quantity;
            }
        }
        
        

        if ($request->has('parts')) {
            foreach ($request->parts as $partData) {
                $partId = $partData['id'] ?? null;
                $quantity = $partData['quantity'] ?? 1;
        
                if (!$partId) continue;
        
                $part = Part::find($partId);
                if (!$part) continue;
        
                $price = $part->price;
        
                $repairOrder->parts()->attach($partId, [
                    'quantity' => $quantity,
                    'price' => $price
                ]);
        
                $totalAmount += $price * $quantity;
            }
        }
        
        
        $repairOrder->total_amount = $totalAmount;
        $repairOrder->save();

        return redirect()->route('repair_order.create')->with('success', 'Đơn sửa chữa đã được tạo thành công!');
    }
    
}
?>