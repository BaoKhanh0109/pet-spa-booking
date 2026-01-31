<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeRole;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class EmployeeRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = EmployeeRole::orderBy('roleName')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'roleName' => 'required|string|max:100|unique:employee_roles,roleName',
            'description' => 'nullable|string',
        ]);

        EmployeeRole::create([
            'roleName' => $request->roleName,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Đã thêm chức vụ mới thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeRole $role)
    {
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = EmployeeRole::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = EmployeeRole::findOrFail($id);
        
        $request->validate([
            'roleName' => 'required|string|max:100|unique:employee_roles,roleName,' . $id . ',roleID',
            'description' => 'nullable|string',
        ]);

        $role->update([
            'roleName' => $request->roleName,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Đã cập nhật chức vụ thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = EmployeeRole::findOrFail($id);
        
        // Check if any employee is using this role
        if ($role->employees()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Không thể xóa chức vụ này vì đang có nhân viên sử dụng!');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Đã xóa chức vụ thành công!');
    }
    
    // ==================== API METHODS ====================
    
    /**
     * API: Get all roles
     * 
     * @OA\Get(
     *     path="/admin/roles",
     *     summary="Lấy danh sách chức vụ",
     *     tags={"Admin Roles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/EmployeeRole"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only")
     * )
     */
    public function apiIndex()
    {
        $roles = EmployeeRole::orderBy('roleName')->get();

        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    /**
     * API: Create role
     * 
     * @OA\Post(
     *     path="/admin/roles",
     *     summary="Thêm chức vụ mới",
     *     tags={"Admin Roles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"roleName"},
     *             @OA\Property(property="roleName", type="string", example="Bác sĩ thú y"),
     *             @OA\Property(property="description", type="string", example="Nhân viên khám chữa bệnh cho thú cưng")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Thêm thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/EmployeeRole")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function apiStore(Request $request)
    {
        $request->validate([
            'roleName' => 'required|string|max:100|unique:employee_roles,roleName',
            'description' => 'nullable|string',
        ]);

        $role = EmployeeRole::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Thêm chức vụ thành công!',
            'data' => $role
        ], 201);
    }

    /**
     * API: Get role detail
     * 
     * @OA\Get(
     *     path="/admin/roles/{id}",
     *     summary="Lấy chi tiết chức vụ",
     *     tags={"Admin Roles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID chức vụ",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/EmployeeRole")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiShow($id)
    {
        $role = EmployeeRole::with('employees')->find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy chức vụ!'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    /**
     * API: Update role
     * 
     * @OA\Put(
     *     path="/admin/roles/{id}",
     *     summary="Cập nhật chức vụ",
     *     tags={"Admin Roles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID chức vụ",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"roleName"},
     *             @OA\Property(property="roleName", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/EmployeeRole")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiUpdate(Request $request, $id)
    {
        $role = EmployeeRole::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy chức vụ!'
            ], 404);
        }

        $request->validate([
            'roleName' => 'required|string|max:100|unique:employee_roles,roleName,' . $id . ',roleID',
            'description' => 'nullable|string',
        ]);

        $role->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật chức vụ thành công!',
            'data' => $role
        ]);
    }

    /**
     * API: Delete role
     * 
     * @OA\Delete(
     *     path="/admin/roles/{id}",
     *     summary="Xóa chức vụ",
     *     tags={"Admin Roles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID chức vụ",
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
     *     @OA\Response(response=400, description="Không thể xóa - đang có nhân viên sử dụng"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiDestroy($id)
    {
        $role = EmployeeRole::find($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy chức vụ!'
            ], 404);
        }

        if ($role->employees()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa chức vụ này vì đang có nhân viên sử dụng!'
            ], 400);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa chức vụ thành công!'
        ]);
    }
}
