<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{

    public function index(Request $request)
    {
        $query = Service::query();
    
        // Nếu có từ khóa tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            // Tìm kiếm theo tên dịch vụ hoặc mô tả
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
    
        // Lấy danh sách dịch vụ đã lọc, ví dụ paginate 10 bản ghi mỗi trang
        $services = $query->orderBy('id', 'desc')->paginate(10);
    
        // Truyền biến $services và từ khóa tìm kiếm về view để giữ giá trị ô input
        return view('services.index', compact('services'))
               ->with('search', $request->search);
    }
    
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);
    
        Service::create($request->all());
        return back()->with('success', 'Đã thêm dịch vụ');
    }
    
    public function destroy(Service $service) {
        $service->delete();
        return back()->with('success', 'Đã xóa dịch vụ');
    }
    
}
