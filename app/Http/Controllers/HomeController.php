<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceCategory;
use OpenApi\Annotations as OA;

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
     * 
     * @OA\Get(
     *     path="/home",
     *     summary="Lấy dữ liệu trang chủ",
     *     tags={"Home"},
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="services", type="array", @OA\Items(ref="#/components/schemas/Service")),
     *                 @OA\Property(property="categories", type="array", @OA\Items(ref="#/components/schemas/ServiceCategory")),
     *                 @OA\Property(property="featured_services", type="array", @OA\Items(ref="#/components/schemas/Service"))
     *             ),
     *             @OA\Property(property="message", type="string", example="Lấy dữ liệu trang chủ thành công")
     *         )
     *     )
     * )
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
     * 
     * @OA\Get(
     *     path="/home/search",
     *     summary="Tìm kiếm dịch vụ",
     *     tags={"Home"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Từ khóa tìm kiếm",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="query", type="string"),
     *                 @OA\Property(property="services", type="array", @OA\Items(ref="#/components/schemas/Service")),
     *                 @OA\Property(property="count", type="integer")
     *             ),
     *             @OA\Property(property="message", type="string", example="Tìm kiếm thành công")
     *         )
     *     )
     * )
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
