<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use PDF;
use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{
    /**
     * Hiển thị danh sách phụ tùng
     */
    public function index(Request $request)
    {
        $parts = Part::all();
        $query = Part::query();

    if ($request->filled('start_date')) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }

    if ($request->has('low_stock')) {
        $query->where('stock', '<=', 5); // Hàng sắp hết
    }

    $parts = $query->get();
        return view('parts.index', compact('parts'));
    }

    /**
     * Hiển thị form tạo phụ tùng mới
     */
    public function create()
    {
        return view('parts.create');
    }

    /**
     * Lưu phụ tùng mới vào database
     */

    public function store(Request $request)
    {
        // Tạo biến lưu dữ liệu request nhưng bỏ validate code ban đầu để cho phép code trống
        $request->validate([
            // 'code' => 'required|unique:parts,code', // Bỏ required ở đây để cho phép tự tạo nếu trống
            'code' => 'nullable|unique:parts,code',
            'name' => 'required|string|max:255',
            'unit' => 'required',
            'stock' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);
    
        // Nếu người dùng không nhập code hoặc nhập trùng thì tạo mới code random
        $code = $request->code;
    
        // Hàm để tạo mã phụ tùng random, kiểm tra trùng trong DB
        function generateUniqueCode() {
            do {
                $newCode = 'PT-' . strtoupper(Str::random(6));
            } while (Part::where('code', $newCode)->exists());
            return $newCode;
        }
    
        if (empty($code) || Part::where('code', $code)->exists()) {
            $code = generateUniqueCode();
        }
    
        Part::create([
            'code' => $code,
            'name' => $request->name,
            'unit' => 'required',
            'stock' => $request->stock ?? 0,
            'price' => $request->price ?? 0,
            'description' => $request->description,
        ]);
    
        return redirect()->route('parts.index')->with('success', 'Thêm phụ tùng thành công!');
    }
    
    /**
     * Hiển thị chi tiết phụ tùng (nếu cần)
     */
    public function show(Part $part)
    {
        return view('parts.show', compact('part'));
    }

    /**
     * Hiển thị form sửa phụ tùng
     */
    public function edit(Part $part)
    {
        return view('parts.edit', compact('part'));
    }

    /**
     * Cập nhật thông tin phụ tùng
     */
    public function update(Request $request, Part $part)
    {
        $request->validate([
            'code' => 'required|unique:parts,code,' . $part->id,
            'name' => 'required|string|max:255',
            'unit' => 'required',
            'stock' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $part->update([
            'code' => $request->code,
            'name' => $request->name,
            'unit' => $request->unit,
            'stock' => $request->stock ?? 0,
            'price' => $request->price ?? 0,
            'description' => $request->description,
        ]);

        return redirect()->route('parts.index')->with('success', 'Cập nhật phụ tùng thành công!');
    }

    public function exportPdf()
    {
        $parts = Part::all();
        $pdf = PDF::loadView('parts.pdf', compact('parts'));
        return $pdf->download('danh-sach-phu-tung.pdf');
    }
    
    /**
     * Xóa phụ tùng
     */
    public function destroy(Part $part)
    {
        $part->delete();

        return redirect()->route('parts.index')->with('success', 'Xóa phụ tùng thành công!');
    }
}
?>