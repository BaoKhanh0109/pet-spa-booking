<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class AdminServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'serviceName' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        Service::create([
            'serviceName' => $request->serviceName,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Thêm dịch vụ thành công!');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'serviceName' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $service = Service::findOrFail($id);
        $service->update([
            'serviceName' => $request->serviceName,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Đã xóa dịch vụ!');
    }
}