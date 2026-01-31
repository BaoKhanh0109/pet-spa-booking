<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    // ==================== WEB METHODS ====================
    
    /**
     * Hiển thị danh sách users
     */
    public function index()
    {
        $users = User::where('role', 'user')
            ->withCount(['pets', 'appointments'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị chi tiết user
     */
    public function show($id)
    {
        $user = User::with(['pets', 'appointments.pet', 'appointments.services.category'])
            ->withCount(['pets', 'appointments'])
            ->findOrFail($id);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Xóa user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Không cho phép xóa admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Không thể xóa tài khoản admin!');
        }
        
        // Xóa user (cascade sẽ xóa pets và appointments)
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Đã xóa người dùng thành công!');
    }
    
    // ==================== API METHODS ====================
    
    /**
     * API: Get all users
     * 
     * @OA\Get(
     *     path="/admin/users",
     *     summary="Lấy danh sách người dùng",
     *     tags={"Admin Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only")
     * )
     */
    public function apiIndex()
    {
        $users = User::where('role', 'user')
            ->withCount(['pets', 'appointments'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * API: Get user detail
     * 
     * @OA\Get(
     *     path="/admin/users/{id}",
     *     summary="Lấy chi tiết người dùng",
     *     tags={"Admin Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID người dùng",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiShow($id)
    {
        $user = User::with(['pets', 'appointments.pet', 'appointments.services'])
            ->withCount(['pets', 'appointments'])
            ->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy người dùng!'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * API: Delete user
     * 
     * @OA\Delete(
     *     path="/admin/users/{id}",
     *     summary="Xóa người dùng",
     *     tags={"Admin Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID người dùng",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Không thể xóa tài khoản admin"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiDestroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy người dùng!'
            ], 404);
        }

        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa tài khoản admin!'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa người dùng thành công!'
        ]);
    }
}
