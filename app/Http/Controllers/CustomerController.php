<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
{
    $query = Customer::query();

    if ($search = $request->input('search')) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $customers = $query->paginate(10); // hoặc get()

    return view('customers.index', compact('customers'));
}
public function history($id)
{
    $customer = Customer::with('repairOrders.vehicle')->findOrFail($id);
    $repairOrders = $customer->repairOrders()->latest()->get();

    return view('customers.history', compact('customer', 'repairOrders'));
}


    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        Customer::create($request->all());
        return redirect()->route('customers.index')->with('success', 'Khách hàng được thêm thành công.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $customer->update($request->all());
        return redirect()->route('customers.index')->with('success', 'Khách hàng được cập nhật.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Đã xóa khách hàng.');
    }
}
