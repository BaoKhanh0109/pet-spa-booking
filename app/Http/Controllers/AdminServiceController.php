<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminServiceController extends Controller
{
    // ==================== WEB METHODS ====================
    
    public function index()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = ServiceCategory::all();
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
        $categories = ServiceCategory::all();
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

    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        if ($service->serviceImage && Storage::disk('public')->exists($service->serviceImage)) {
            Storage::disk('public')->delete($service->serviceImage);
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Đã xóa dịch vụ!');
    }
    
    // ==================== API METHODS ====================
    
    /**
     * API: Get all services
     */
    public function apiIndex(Request $request)
    {
        $query = Service::with('category');

        if ($request->has('search')) {
            $keyword = $request->search;
            $query->where('serviceName', 'LIKE', "%{$keyword}%");
        }

        if ($request->has('category_id')) {
            $query->where('categoryID', $request->category_id);
        }

        $services = $query->get();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * API: Create service
     */
    public function apiStore(Request $request)
    {
        $request->validate([
            'serviceName' => 'required|string|max:255',
            'categoryID' => 'required|exists:service_categories,categoryID',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $service = Service::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Thêm dịch vụ thành công!',
            'data' => $service->load('category')
        ], 201);
    }

    /**
     * API: Get service detail
     */
    public function apiShow($id)
    {
        $service = Service::with('category')->find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy dịch vụ!'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $service
        ]);
    }

    /**
     * API: Update service
     */
    public function apiUpdate(Request $request, $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy dịch vụ!'
            ], 404);
        }

        $request->validate([
            'serviceName' => 'sometimes|string|max:255',
            'categoryID' => 'sometimes|exists:service_categories,categoryID',
            'price' => 'sometimes|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $service->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật dịch vụ thành công!',
            'data' => $service->load('category')
        ]);
    }

    /**
     * API: Delete service
     */
    public function apiDestroy($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy dịch vụ!'
            ], 404);
        }

        if ($service->serviceImage && Storage::disk('public')->exists($service->serviceImage)) {
            Storage::disk('public')->delete($service->serviceImage);
        }

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa dịch vụ thành công!'
        ]);
    }
}
