<?php
namespace App\Http\Controllers;

use App\Models\RepairOrder;
use App\Models\Service;
use App\Models\Part;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RepairOrderController extends Controller
{
    public function index()
    {
        try {
            $orders = RepairOrder::with(['customer', 'vehicle', 'employee', 'services', 'parts'])->get();
            $customers = Customer::all();
            $vehicles = Vehicle::all();
            $employees = Employee::all();
            $services = Service::all();
            $parts = Part::all();

            Log::info('Loaded repair orders index', ['orders_count' => $orders->count()]);

            return view('repair_orders.index', compact('orders', 'customers', 'vehicles', 'employees', 'services', 'parts'));
        } catch (\Exception $e) {
            Log::error('Failed to load repair orders index: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Lỗi khi tải danh sách đơn sửa chữa: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            $customers = Customer::all();
            $vehicles = Vehicle::all();
            $employees = Employee::all();
            $services = Service::all();
            $parts = Part::all();

            return view('repair_orders.create', compact('customers', 'vehicles', 'employees', 'services', 'parts'));
        } catch (\Exception $e) {
            Log::error('Failed to load create form: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Lỗi khi tải form tạo đơn: ' . $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        Log::info('Attempting to delete repair order', ['id' => $id]);
    
        DB::beginTransaction();
        try {
            // Tìm đơn sửa chữa
            $repairOrder = RepairOrder::with(['parts'])->findOrFail($id);
            Log::info('Repair order found', ['id' => $repairOrder->id]);
    
            // Khôi phục tồn kho phụ tùng
            if ($repairOrder->parts->isNotEmpty()) {
                Log::info('Restoring part stock...', ['parts_count' => $repairOrder->parts->count()]);
                foreach ($repairOrder->parts as $part) {
                    $quantity = $part->pivot->quantity;
                    $part->increment('stock', $quantity);
                    Log::info('Restored stock for part', [
                        'part_id' => $part->id,
                        'part_name' => $part->name,
                        'quantity' => $quantity,
                        'new_stock' => $part->stock
                    ]);
                }
            }
    
            // Xóa các quan hệ trong bảng trung gian (services và parts)
            $repairOrder->services()->detach();
            $repairOrder->parts()->detach();
            Log::info('Detached services and parts from repair order');
    
            // Xóa đơn sửa chữa
            $repairOrder->delete();
            Log::info('Repair order deleted successfully', ['id' => $id]);
    
            DB::commit();
            return redirect()->route('repair_orders.index')->with('success', 'Xóa đơn sửa chữa thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete repair order: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e
            ]);
            return redirect()->back()->withErrors(['error' => 'Lỗi khi xóa đơn sửa chữa: ' . $e->getMessage()]);
        }
    }
    public function update(Request $request, $id)
{
    Log::info('Attempting to update repair order', ['id' => $id, 'request_data' => $request->all()]);

    try {
        // Validate dữ liệu
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'employee_id' => 'required|exists:employees,id',
            'payment_method' => 'required|in:tien_mat,chuyen_khoan,the_tin_dung',
            'service_ids' => 'array|nullable',
            'service_ids.*' => 'exists:services,id',
            'part_ids' => 'array|nullable',
            'part_ids.*' => 'exists:parts,id',
            'part_quantities' => 'array|nullable',
            'part_quantities.*' => 'integer|min:1',
            'notes' => 'nullable|string',
        ]);
    } catch (ValidationException $e) {
        Log::error('Validation failed: ' . json_encode($e->errors()), ['errors' => $e->errors()]);
        return redirect()->back()->withErrors($e->errors())->withInput();
    }

    DB::beginTransaction();
    try {
        // Tìm đơn sửa chữa
        $repairOrder = RepairOrder::with(['parts'])->findOrFail($id);
        Log::info('Repair order found', ['id' => $repairOrder->id]);

        // Khôi phục tồn kho phụ tùng hiện tại
        if ($repairOrder->parts->isNotEmpty()) {
            Log::info('Restoring current part stock...', ['parts_count' => $repairOrder->parts->count()]);
            foreach ($repairOrder->parts as $part) {
                $quantity = $part->pivot->quantity;
                $part->increment('stock', $quantity);
                Log::info('Restored stock for part', [
                    'part_id' => $part->id,
                    'part_name' => $part->name,
                    'quantity' => $quantity,
                    'new_stock' => $part->stock
                ]);
            }
        }

        // Cập nhật thông tin đơn sửa chữa
        $repairOrder->update([
            'customer_id' => $request->customer_id,
            'vehicle_id' => $request->vehicle_id,
            'employee_id' => $request->employee_id,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);
        Log::info('Repair order updated', ['id' => $repairOrder->id]);

        $totalAmount = 0;

        // Xử lý dịch vụ
        if ($request->service_ids) {
            Log::info('Processing services...', ['service_ids' => $request->service_ids]);
            $services = [];
            foreach ($request->service_ids as $serviceId) {
                if (!empty($serviceId)) {
                    $service = Service::findOrFail($serviceId);
                    $quantity = 1;
                    $price = $service->price ?? 0;
                    $services[$serviceId] = [
                        'quantity' => $quantity,
                        'price' => $price,
                    ];
                    $totalAmount += $price * $quantity;
                }
            }
            $repairOrder->services()->sync($services);
            Log::info('Services updated');
        } else {
            $repairOrder->services()->detach();
            Log::info('Services detached');
        }

        // Xử lý phụ tùng và trừ kho
        if ($request->part_ids && $request->part_quantities) {
            Log::info('Processing parts...', ['part_ids' => $request->part_ids, 'part_quantities' => $request->part_quantities]);
            $parts = [];
            foreach ($request->part_ids as $partId) {
                if (!empty($partId) && isset($request->part_quantities[$partId])) {
                    $quantity = (int) $request->part_quantities[$partId];
                    $part = Part::findOrFail($partId);

                    if ($part->stock < $quantity) {
                        throw new \Exception("Không đủ tồn kho cho phụ tùng: {$part->name}");
                    }

                    $price = $part->price ?? 0;
                    $parts[$partId] = [
                        'quantity' => $quantity,
                        'price' => $price,
                    ];
                    $totalAmount += $price * $quantity;

                    $part->decrement('stock', $quantity);
                }
            }
            $repairOrder->parts()->sync($parts);
            Log::info('Parts updated');
        } else {
            $repairOrder->parts()->detach();
            Log::info('Parts detached');
        }

        // Cập nhật tổng tiền
        Log::info('Updating total amount...', ['total_amount' => $totalAmount]);
        $repairOrder->update(['total_amount' => $totalAmount]);

        DB::commit();
        Log::info('Repair order updated successfully', ['id' => $repairOrder->id]);
        return redirect()->route('repair_orders.index')->with('success', 'Cập nhật đơn sửa chữa thành công!');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Failed to update repair order: ' . $e->getMessage(), [
            'id' => $id,
            'exception' => $e
        ]);
        return redirect()->back()->withErrors(['error' => 'Lỗi khi cập nhật đơn sửa chữa: ' . $e->getMessage()])->withInput();
    }
}
    public function store(Request $request)
    {
        Log::info('Request data:', $request->all());

        try {
            // Validate dữ liệu
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'vehicle_id' => 'required|exists:vehicles,id',
                'employee_id' => 'required|exists:employees,id',
                'payment_method' => 'required|in:tien_mat,chuyen_khoan,the_tin_dung',
                'service_ids' => 'array|nullable',
                'service_ids.*' => 'exists:services,id',
                'part_ids' => 'array|nullable',
                'part_ids.*' => 'exists:parts,id',
                'part_quantities' => 'array|nullable',
                'part_quantities.*' => 'integer|min:1',
                'notes' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation failed: ' . json_encode($e->errors()), ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        DB::beginTransaction();
        try {
            Log::info('Creating repair order...');
            $repairOrder = RepairOrder::create([
                'customer_id' => $request->customer_id,
                'vehicle_id' => $request->vehicle_id,
                'employee_id' => $request->employee_id,
                'date' => now(),
                'repair_status' => 'dang_sua',
                'payment_status' => 'chua_thanh_toan',
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'total_amount' => 0,
            ]);
            Log::info('Repair order created', ['id' => $repairOrder->id]);

            $totalAmount = 0;

            // Xử lý dịch vụ
            if ($request->service_ids) {
                Log::info('Processing services...', ['service_ids' => $request->service_ids]);
                $services = [];
                foreach ($request->service_ids as $serviceId) {
                    if (!empty($serviceId)) {
                        $service = Service::findOrFail($serviceId);
                        $quantity = 1;
                        $price = $service->price ?? 0;
                        $services[$serviceId] = [
                            'quantity' => $quantity,
                            'price' => $price,
                        ];
                        $totalAmount += $price * $quantity;
                    }
                }
                if ($services) {
                    $repairOrder->services()->attach($services);
                    Log::info('Services attached');
                }
            }

            // Xử lý phụ tùng và trừ kho
            if ($request->part_ids && $request->part_quantities) {
                Log::info('Processing parts...', ['part_ids' => $request->part_ids, 'part_quantities' => $request->part_quantities]);
                $parts = [];
                foreach ($request->part_ids as $partId) {
                    if (!empty($partId) && isset($request->part_quantities[$partId])) {
                        $quantity = (int) $request->part_quantities[$partId];
                        $part = Part::findOrFail($partId);

                        if ($part->stock < $quantity) {
                            throw new \Exception("Không đủ tồn kho cho phụ tùng: {$part->name}");
                        }

                        $price = $part->price ?? 0;
                        $parts[$partId] = [
                            'quantity' => $quantity,
                            'price' => $price,
                        ];
                        $totalAmount += $price * $quantity;

                        $part->decrement('stock', $quantity);
                    }
                }
                if ($parts) {
                    $repairOrder->parts()->attach($parts);
                    Log::info('Parts attached');
                }
            }

            // Cập nhật tổng tiền
            Log::info('Updating total amount...', ['total_amount' => $totalAmount]);
            $repairOrder->update(['total_amount' => $totalAmount]);

            DB::commit();
            Log::info('Repair order created successfully', ['id' => $repairOrder->id]);
            return redirect()->route('repair_orders.index')->with('success', 'Đơn sửa chữa đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create repair order: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Lỗi khi tạo đơn sửa chữa: ' . $e->getMessage()])->withInput();
        }
    }
}
