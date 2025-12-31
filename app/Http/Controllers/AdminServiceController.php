<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminServiceController extends Controller
{
    /**
     * Danh sách dịch vụ
     */
    public function index()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Form thêm dịch vụ
     */
    public function create()
    {

        return view('admin.services.create'); 
    }

    /**
     * Lưu dịch vụ mới (có ảnh)
     */
    public function store(Request $request)
    {
        $request->validate([
            'serviceName'   => 'required|string|max:100', 
            'price'         => 'required|numeric',
            'description'   => 'nullable|string',
            'serviceImage'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'serviceName',
            'price',
            'description',
        ]);

        // Upload ảnh
        if ($request->hasFile('serviceImage')) {
            $data['serviceImage'] = $request->file('serviceImage')
                                            ->store('services', 'public');
        }

        Service::create($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Thêm dịch vụ thành công!');
    }

    /**
     * Form sửa dịch vụ
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Cập nhật dịch vụ (đổi ảnh nếu có)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'serviceName'   => 'required|string|max:100',
            'price'         => 'required|numeric',
            'description'   => 'nullable|string',
            'serviceImage'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $service = Service::findOrFail($id);

        $data = $request->only([
            'serviceName',
            'price',
            'description',
        ]);

        // Nếu upload ảnh mới → xóa ảnh cũ
        if ($request->hasFile('serviceImage')) {
            // Kiểm tra và xóa ảnh cũ
            if ($service->serviceImage && Storage::disk('public')->exists($service->serviceImage)) {
                Storage::disk('public')->delete($service->serviceImage);
            }

            // Lưu ảnh mới
            $data['serviceImage'] = $request->file('serviceImage')
                                            ->store('services', 'public');
        }

        $service->update($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Cập nhật thành công!');
    }

    /**
     * Xóa dịch vụ + xóa ảnh
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        // Xóa ảnh trong storage
        if ($service->serviceImage && Storage::disk('public')->exists($service->serviceImage)) {
            Storage::disk('public')->delete($service->serviceImage);
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Đã xóa dịch vụ!');
    }
}