<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceCategory;

class HomeController extends Controller
{
    // ==================== WEB METHODS ====================
    
    public function index() {
        $services = Service::all(); 
        return view('welcome', compact('services'));
    }
    
    // ==================== API METHODS ====================
    
    /**
     * API: Lấy dữ liệu trang chủ
     */
    public function apiIndex()
    {
        $services = Service::with('category')->get();
        $categories = ServiceCategory::withCount('services')->get();
        
        $featuredServices = Service::with('category')
            ->orderBy('price', 'desc')
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'services' => $services,
                'categories' => $categories,
                'featured_services' => $featuredServices,
            ],
            'message' => 'Lấy dữ liệu trang chủ thành công'
        ]);
    }

    /**
     * API: Tìm kiếm dịch vụ
     */
    public function apiSearch(Request $request)
    {
        $query = $request->get('q', '');
        
        $services = Service::with('category')
            ->where('serviceName', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'query' => $query,
                'services' => $services,
                'count' => $services->count(),
            ],
            'message' => 'Tìm kiếm thành công'
        ]);
    }
}   
