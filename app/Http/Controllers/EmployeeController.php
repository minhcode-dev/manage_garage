<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // Bảo vệ controller phải đăng nhập
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $employees = Employee::when($search, function($query, $search) {
                return $query->where('name', 'like', "%$search%")
                             ->orWhere('email', 'like', "%$search%");
            })
            ->paginate(10);
        return view('employees.index', compact('employees', 'search'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|confirmed|min:6',
            'phone' => 'nullable|max:20',
            'position' => 'nullable|max:255',
            'salary' => 'nullable|numeric|min:0',
        ]);

        Employee::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'position' => $request->position,
            'salary' => $request->salary,
        ]);

        return redirect()->route('employees.index')->with('success', 'Thêm nhân viên thành công!');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Xóa nhân viên thành công!');
    }
}
?>