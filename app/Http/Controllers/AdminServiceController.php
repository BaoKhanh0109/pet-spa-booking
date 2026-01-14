<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = \App\Models\ServiceCategory::all();
        return view('admin.services.create', compact('categories')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'serviceName'   => 'required|string|max:100', 
            'price'         => 'required|numeric',
            'categoryID'    => 'required|exists:service_categories,categoryID',
            'description'   => 'nullable|string',
            'serviceImage'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'serviceName',
            'price',
            'categoryID',
            'description',
        ]);

        if ($request->hasFile('serviceImage')) {
            $data['serviceImage'] = $request->file('serviceImage')
                                            ->store('services', 'public');
        }

        Service::create($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Thêm dịch vụ thành công!');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $categories = \App\Models\ServiceCategory::all();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'serviceName'   => 'required|string|max:100',
            'price'         => 'required|numeric',
            'categoryID'    => 'required|exists:service_categories,categoryID',
            'description'   => 'nullable|string',
            'serviceImage'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $service = Service::findOrFail($id);

        $data = $request->only([
            'serviceName',
            'price',
            'categoryID',
            'description',
        ]);

        if ($request->hasFile('serviceImage')) {
            if ($service->serviceImage && Storage::disk('public')->exists($service->serviceImage)) {
                Storage::disk('public')->delete($service->serviceImage);
            }

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