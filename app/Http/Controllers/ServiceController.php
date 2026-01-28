<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Pet;
use App\Helpers\PricingHelper;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    // ==================== WEB METHODS ====================
    
    public function index(Request $request) {
        $query = Service::query();

        if ($request->has('search')) {
            $keyword = $request->search;
            $query->where('serviceName', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%");
        }

        $services = $query->get();

        return view('client.services', compact('services'));
    }
    
    public function show($id) {
        $service = Service::with('category')->findOrFail($id);
        
        // Lấy danh sách thú cưng của user nếu đã đăng nhập
        $pets = Auth::check() ? Pet::where('userID', Auth::id())->get() : collect();
        
        // Lấy bảng giá cho tất cả size
        $prices = PricingHelper::getAllSizePrices($service->price);
        
        return view('client.service_detail', compact('service', 'pets', 'prices'));
    }
    
    /**
     * API: Tính giá dịch vụ theo thú cưng được chọn
     */
    public function calculatePrice(Request $request)
    {
        $service = Service::findOrFail($request->serviceID ?? $request->service_id);
        $pet = Pet::findOrFail($request->petID ?? $request->pet_id);
        
        $size = PricingHelper::getPetSize($pet->weight, $pet->backLength);
        $price = PricingHelper::calculatePriceBySize($service->price, $size);
        
        return response()->json([
            'success' => true,
            'data' => [
                'petName' => $pet->petName,
                'weight' => $pet->weight,
                'backLength' => $pet->backLength,
                'size' => $size,
                'sizeLabel' => PricingHelper::getSizeLabel($size),
                'price' => $price,
                'priceFormatted' => number_format($price, 0, ',', '.') . ' đ'
            ]
        ]);
    }
    
    // ==================== API METHODS ====================
    
    /**
     * API: Lấy danh sách dịch vụ
     */
    public function apiIndex(Request $request)
    {
        $query = Service::with('category');

        if ($request->has('search')) {
            $keyword = $request->search;
            $query->where('serviceName', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%");
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
     * API: Lấy chi tiết dịch vụ
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

        $prices = PricingHelper::getAllSizePrices($service->price);

        return response()->json([
            'success' => true,
            'data' => [
                'service' => $service,
                'prices_by_size' => $prices
            ]
        ]);
    }

    /**
     * API: Lấy danh sách danh mục
     */
    public function apiCategories()
    {
        $categories = ServiceCategory::withCount('services')->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}