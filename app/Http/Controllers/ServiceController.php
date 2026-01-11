<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Pet;
use App\Helpers\PricingHelper;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
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
        $service = Service::findOrFail($request->serviceID);
        $pet = Pet::findOrFail($request->petID);
        
        $size = PricingHelper::getPetSize($pet->weight, $pet->backLength);
        $price = PricingHelper::calculatePriceBySize($service->price, $size);
        
        return response()->json([
            'success' => true,
            'petName' => $pet->petName,
            'weight' => $pet->weight,
            'backLength' => $pet->backLength,
            'size' => $size,
            'sizeLabel' => PricingHelper::getSizeLabel($size),
            'price' => $price,
            'priceFormatted' => number_format($price, 0, ',', '.') . ' đ'
        ]);
    }
}
?>