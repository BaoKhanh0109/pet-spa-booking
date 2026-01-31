<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Pet;
use App\Helpers\PricingHelper;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

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
     * 
     * @OA\Post(
     *     path="/services/calculate-price",
     *     summary="Tính giá dịch vụ theo thú cưng",
     *     tags={"Services"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"service_id", "pet_id"},
     *             @OA\Property(property="service_id", type="integer", example=1),
     *             @OA\Property(property="pet_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tính giá thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="petName", type="string", example="Lucky"),
     *                 @OA\Property(property="weight", type="number", example=15.5),
     *                 @OA\Property(property="backLength", type="number", example=50),
     *                 @OA\Property(property="size", type="string", example="M"),
     *                 @OA\Property(property="sizeLabel", type="string", example="Vừa"),
     *                 @OA\Property(property="price", type="number", example=200000),
     *                 @OA\Property(property="priceFormatted", type="string", example="200.000 đ")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
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
     * 
     * @OA\Get(
     *     path="/services",
     *     summary="Lấy danh sách dịch vụ",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Tìm kiếm theo tên hoặc mô tả",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Lọc theo danh mục",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Service"))
     *         )
     *     )
     * )
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
     * 
     * @OA\Get(
     *     path="/services/{id}",
     *     summary="Lấy chi tiết dịch vụ",
     *     tags={"Services"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID dịch vụ",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="service", ref="#/components/schemas/Service"),
     *                 @OA\Property(property="prices_by_size", type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Không tìm thấy dịch vụ")
     * )
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
     * 
     * @OA\Get(
     *     path="/service-categories",
     *     summary="Lấy danh sách danh mục dịch vụ",
     *     tags={"Services"},
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ServiceCategory"))
     *         )
     *     )
     * )
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