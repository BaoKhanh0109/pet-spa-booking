<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

class PetController extends Controller
{
    // ==================== WEB METHODS ====================
    
    public function index() {
        $pets = Pet::where('userID', Auth::user()->userID)->get();
        return view('pets.index', compact('pets'));
    }

    public function create() {
        return view('pets.create');
    }

    public function store(Request $request) {
        $request->validate([
            'petName' => 'required',
            'species' => 'required',
            'petImage' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['userID'] = Auth::user()->userID;

        if ($request->hasFile('petImage')) {
            $path = $request->file('petImage')->store('pets', 'public');
            $data['petImage'] = $path;
        }

        Pet::create($data);
        return redirect()->route('pets.index')->with('success', 'Thêm Boss thành công!');
    }

    public function edit($id) {
        $pet = Pet::find($id);

        if (!$pet || $pet->userID != Auth::user()->userID) {
            abort(403, 'Bạn không có quyền sửa thú cưng này!');
        }

        return view('pets.edit', compact('pet'));
    }

    public function update(Request $request, $id) {
        $pet = Pet::find($id);

        if (!$pet || $pet->userID != Auth::user()->userID) {
            abort(403);
        }

        $data = $request->except(['petImage']);

        if ($request->hasFile('petImage')) {
            if ($pet->petImage) {
                Storage::disk('public')->delete($pet->petImage);
            }
            $data['petImage'] = $request->file('petImage')->store('pets', 'public');
        }

        $pet->update($data);
        return redirect()->route('pets.index')->with('success', 'Cập nhật thông tin thành công!');
    }

    public function destroy($id) {
        $pet = Pet::find($id);

        if (!$pet || $pet->userID != Auth::user()->userID) {
            abort(403);
        }

        $appointmentCount = $pet->appointments()->count();
        
        if ($appointmentCount > 0) {
            return redirect()->route('pets.index')
                ->with('error', "Không thể xóa {$pet->petName}! Thú cưng này đang có {$appointmentCount} lịch hẹn. Vui lòng hủy các lịch hẹn trước khi xóa.");
        }

        if ($pet->petImage) {
            Storage::disk('public')->delete($pet->petImage);
        }

        $pet->delete();
        return redirect()->route('pets.index')->with('success', 'Đã xóa thú cưng!');
    }
    
    // ==================== API METHODS ====================
    
    /**
     * API: Lấy danh sách thú cưng
     * 
     * @OA\Get(
     *     path="/pets",
     *     summary="Lấy danh sách thú cưng của user",
     *     tags={"Pets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Pet"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function apiIndex()
    {
        $pets = Pet::where('userID', Auth::user()->userID)->get();

        return response()->json([
            'success' => true,
            'data' => $pets
        ]);
    }

    /**
     * API: Thêm thú cưng mới
     * 
     * @OA\Post(
     *     path="/pets",
     *     summary="Thêm thú cưng mới",
     *     tags={"Pets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"petName", "species"},
     *                 @OA\Property(property="petName", type="string", example="Lucky"),
     *                 @OA\Property(property="species", type="string", example="Chó"),
     *                 @OA\Property(property="breed", type="string", example="Golden Retriever"),
     *                 @OA\Property(property="weight", type="number", example=15.5),
     *                 @OA\Property(property="backLength", type="number", example=50),
     *                 @OA\Property(property="birthDate", type="string", format="date", example="2022-01-15"),
     *                 @OA\Property(property="gender", type="string", enum={"male", "female"}),
     *                 @OA\Property(property="petImage", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Thêm thú cưng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Thêm thú cưng thành công!"),
     *             @OA\Property(property="data", ref="#/components/schemas/Pet")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function apiStore(Request $request)
    {
        $request->validate([
            'petName' => 'required|string|max:100',
            'species' => 'required|string|max:50',
            'breed' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'backLength' => 'nullable|numeric|min:0',
            'birthDate' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'petImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('petImage');
        $data['userID'] = Auth::user()->userID;

        if ($request->hasFile('petImage')) {
            $path = $request->file('petImage')->store('pets', 'public');
            $data['petImage'] = $path;
        }

        $pet = Pet::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Thêm thú cưng thành công!',
            'data' => $pet
        ], 201);
    }

    /**
     * API: Lấy chi tiết thú cưng
     * 
     * @OA\Get(
     *     path="/pets/{id}",
     *     summary="Lấy chi tiết thú cưng",
     *     tags={"Pets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID thú cưng",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Pet")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiShow($id)
    {
        $pet = Pet::find($id);

        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thú cưng!'
            ], 404);
        }

        if ($pet->userID != Auth::user()->userID) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền truy cập thú cưng này!'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $pet
        ]);
    }

    /**
     * API: Cập nhật thú cưng
     * 
     * @OA\Put(
     *     path="/pets/{id}",
     *     summary="Cập nhật thú cưng",
     *     tags={"Pets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID thú cưng",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="petName", type="string", example="Lucky"),
     *             @OA\Property(property="species", type="string", example="Chó"),
     *             @OA\Property(property="breed", type="string", example="Golden Retriever"),
     *             @OA\Property(property="weight", type="number", example=15.5),
     *             @OA\Property(property="backLength", type="number", example=50),
     *             @OA\Property(property="birthDate", type="string", format="date"),
     *             @OA\Property(property="gender", type="string", enum={"male", "female"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cập nhật thú cưng thành công!"),
     *             @OA\Property(property="data", ref="#/components/schemas/Pet")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiUpdate(Request $request, $id)
    {
        $pet = Pet::find($id);

        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thú cưng!'
            ], 404);
        }

        if ($pet->userID != Auth::user()->userID) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền sửa thú cưng này!'
            ], 403);
        }

        $request->validate([
            'petName' => 'sometimes|string|max:100',
            'species' => 'sometimes|string|max:50',
            'breed' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'backLength' => 'nullable|numeric|min:0',
            'birthDate' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'petImage' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('petImage');

        if ($request->hasFile('petImage')) {
            if ($pet->petImage) {
                Storage::disk('public')->delete($pet->petImage);
            }
            $data['petImage'] = $request->file('petImage')->store('pets', 'public');
        }

        $pet->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thú cưng thành công!',
            'data' => $pet
        ]);
    }

    /**
     * API: Xóa thú cưng
     * 
     * @OA\Delete(
     *     path="/pets/{id}",
     *     summary="Xóa thú cưng",
     *     tags={"Pets"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID thú cưng",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Xóa thú cưng thành công!")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Không thể xóa - thú cưng có lịch hẹn"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiDestroy($id)
    {
        $pet = Pet::find($id);

        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thú cưng!'
            ], 404);
        }

        if ($pet->userID != Auth::user()->userID) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xóa thú cưng này!'
            ], 403);
        }

        $appointmentCount = $pet->appointments()->count();
        
        if ($appointmentCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Không thể xóa {$pet->petName}! Thú cưng này đang có {$appointmentCount} lịch hẹn."
            ], 400);
        }

        if ($pet->petImage) {
            Storage::disk('public')->delete($pet->petImage);
        }

        $pet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa thú cưng thành công!'
        ]);
    }
}