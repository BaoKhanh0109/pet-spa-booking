<?php

namespace App\Http\Controllers; // Namespace chuẩn

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet; // Gọi Model Pet từ thư mục App\Models

class PetController extends Controller
{
    public function create()
    {
    // Trả về file view giao diện vừa tạo
    return view('pets.create');// Lấy tất cả khách hàng từ DB (chỉ lấy ID và Tên cho nhẹ)
        $customers = Customer::select('customerID', 'customerName')->get();

        // Truyền biến $customers sang view
        return view('pets.create', compact('customers'));
    }
    // Lấy danh sách
    public function index()
    {
        return response()->json(Pet::all());
    }

    // Xem chi tiết
    public function show($id)
    {
        $pet = Pet::find($id);
        if($pet) return response()->json($pet);
        return response()->json(['message' => 'Không tìm thấy'], 404);
    }

    // Thêm mới
    public function store(Request $request)
{
    // Validate dữ liệu
    $request->validate([
        'customerID' => 'required',
        'petName' => 'required',
        'petImage' => 'nullable|image|max:2048'
    ]);

    $data = $request->all();

    // Xử lý upload ảnh (như bài trước)
    if ($request->hasFile('petImage')) {
        $path = $request->file('petImage')->store('pets', 'public');
        $data['petImage'] = '/storage/' . $path;
    }

    // Lưu vào DB
    Pet::create($data);

    // QUAN TRỌNG: Thay vì trả về JSON, ta quay lại trang form và báo thành công
    return redirect()->back()->with('success', 'Đã thêm thú cưng thành công!');
}
    // Sửa
    public function update(Request $request, $id)
    {
        $pet = Pet::find($id);
        if($pet) {
            $pet->update($request->all());
            return response()->json($pet);
        }
        return response()->json(['message' => 'Lỗi'], 404);
    }

    // Xóa
    public function destroy($id)
    {
        if(Pet::destroy($id)) return response()->json(['message' => 'Đã xóa']);
        return response()->json(['message' => 'Lỗi'], 404);
    }
}