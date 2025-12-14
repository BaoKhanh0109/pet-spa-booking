<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // 1. Xem danh sách
    public function index()
    {
        $services = Service::orderBy('serviceID', 'desc')->get();
        return view('services.index', compact('services'));
    }

    // 2. Form thêm mới
    public function create()
    {
        return view('services.create');
    }

    // 3. Lưu thêm mới
    public function store(Request $request)
    {
        $request->validate([
            'serviceName' => 'required|max:100|unique:services,serviceName',
            'price' => 'required|numeric|min:0',
        ]);

        Service::create($request->all());

        return redirect()->route('services.index')->with('success', 'Thêm dịch vụ thành công!');
    }

    // 4. Form sửa
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('services.edit', compact('service'));
    }

    // 5. Cập nhật
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        
        $request->validate([
            'serviceName' => 'required|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        $service->update($request->all());

        return redirect()->route('services.index')->with('success', 'Đã cập nhật dịch vụ thành công!');
    }

    // 6. Xóa
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Đã xóa dịch vụ!');
    }
}
?>