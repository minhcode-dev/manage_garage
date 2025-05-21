<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Customer;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Lấy customers để chọn chủ xe trong modal
        $customers = Customer::all();

        // Query lấy xe, lọc nếu có từ khóa tìm kiếm
        $vehicles = Vehicle::with('owner') // lấy luôn quan hệ chủ xe
            ->when($search, function ($query, $search) {
                $query->where('license_plate', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhereHas('owner', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->paginate(10); // phân trang nếu cần

        return view('vehicles.index', compact('vehicles', 'customers'));
    }


    public function create()
    {
        $customers = Customer::all();
        return view('vehicles.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'license_plate' => 'required|unique:vehicles',
            'brand' => 'required',
            'model' => 'required',
            'year' => 'nullable|digits:4|integer',
            'color' => 'nullable|string',
            'owner_id' => 'nullable|exists:customers,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('vehicles', 'public');
        }

        Vehicle::create($data);

        return redirect()->route('vehicles.index')->with('success', 'Thêm xe thành công.');
    }

    public function edit(Vehicle $vehicle)
    {
        $customers = Customer::all();
        return view('vehicles.edit', compact('vehicle', 'customers'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'license_plate' => 'required|unique:vehicles,license_plate,' . $vehicle->id,
            'brand' => 'required',
            'model' => 'required',
            'year' => 'nullable|digits:4|integer',
            'color' => 'nullable|string',
            'owner_id' => 'nullable|exists:customers,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($vehicle->image) {
                \Storage::disk('public')->delete($vehicle->image);
            }
            $data['image'] = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle->update($data);

        return redirect()->route('vehicles.index')->with('success', 'Cập nhật xe thành công.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->image) {
            \Storage::disk('public')->delete($vehicle->image);
        }
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Xóa xe thành công.');
    }
}
?>