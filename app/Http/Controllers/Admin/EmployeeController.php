<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeRole;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

class EmployeeController extends Controller
{
    /**
     * Hiển thị danh sách nhân viên
     */
    public function index()
    {
        $employees = Employee::with(['services', 'role'])->paginate(10);
        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Hiển thị form thêm nhân viên mới
     */
    public function create()
    {
        $services = Service::all();
        $roles = EmployeeRole::orderBy('roleName')->get();
        return view('admin.employees.create', compact('services', 'roles'));
    }

    /**
     * Lưu nhân viên mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'employeeName' => 'required|string|max:100',
            'roleID' => 'required|exists:employee_roles,roleID',
            'phoneNumber' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'info' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,serviceID'
        ]);

        $data = $request->except(['avatar', 'services']);

        // Xử lý upload avatar
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('employees', 'public');
            $data['avatar'] = $avatarPath;
        }

        $employee = Employee::create($data);

        // Gán dịch vụ cho nhân viên
        if ($request->has('services')) {
            $employee->services()->sync($request->services);
        }

        return redirect()->route('admin.employees.index')
            ->with('success', 'Thêm nhân viên thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa nhân viên
     */
    public function edit($id)
    {
        $employee = Employee::with('services')->findOrFail($id);
        $services = Service::all();
        $roles = EmployeeRole::orderBy('roleName')->get();
        return view('admin.employees.edit', compact('employee', 'services', 'roles'));
    }

    /**
     * Cập nhật thông tin nhân viên
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'employeeName' => 'required|string|max:100',
            'roleID' => 'required|exists:employee_roles,roleID',
            'phoneNumber' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'info' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,serviceID'
        ]);

        $data = $request->except(['avatar', 'services']);

        // Xử lý upload avatar mới
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($employee->avatar && Storage::disk('public')->exists($employee->avatar)) {
                Storage::disk('public')->delete($employee->avatar);
            }
            $avatarPath = $request->file('avatar')->store('employees', 'public');
            $data['avatar'] = $avatarPath;
        }

        $employee->update($data);

        // Cập nhật dịch vụ
        if ($request->has('services')) {
            $employee->services()->sync($request->services);
        } else {
            $employee->services()->sync([]);
        }

        return redirect()->route('admin.employees.index')
            ->with('success', 'Cập nhật nhân viên thành công!');
    }

    /**
     * Xóa nhân viên
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        // Xóa avatar nếu có
        if ($employee->avatar && Storage::disk('public')->exists($employee->avatar)) {
            Storage::disk('public')->delete($employee->avatar);
        }

        // Xóa các liên kết dịch vụ (cascade sẽ tự động xóa)
        $employee->services()->detach();
        
        // Xóa nhân viên
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Xóa nhân viên thành công!');
    }

    /**
     * Hiển thị chi tiết nhân viên
     */
    public function show($id)
    {
        $employee = Employee::with(['services', 'role', 'workSchedules', 'appointments.user', 'appointments.pet', 'appointments.services'])->findOrFail($id);
        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Thêm lịch làm việc cho nhân viên
     */
    public function storeSchedule(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $request->validate([
            'dayOfWeek' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'startTime' => 'required|date_format:H:i',
            'endTime' => 'required|date_format:H:i|after:startTime',
        ]);

        // Kiểm tra xem đã có lịch trong ngày này chưa
        $exists = $employee->workSchedules()
            ->where('dayOfWeek', $request->dayOfWeek)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Nhân viên đã có lịch làm việc trong ngày này!');
        }

        $employee->workSchedules()->create([
            'dayOfWeek' => $request->dayOfWeek,
            'startTime' => $request->startTime,
            'endTime' => $request->endTime,
        ]);

        return back()->with('success', 'Thêm lịch làm việc thành công!');
    }

    /**
     * Cập nhật lịch làm việc
     */
    public function updateSchedule(Request $request, $id, $scheduleId)
    {
        $employee = Employee::findOrFail($id);
        $schedule = $employee->workSchedules()->findOrFail($scheduleId);
        
        $request->validate([
            'dayOfWeek' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'startTime' => 'required|date_format:H:i',
            'endTime' => 'required|date_format:H:i|after:startTime',
        ]);

        // Kiểm tra trùng lặp (ngoại trừ schedule hiện tại)
        $exists = $employee->workSchedules()
            ->where('dayOfWeek', $request->dayOfWeek)
            ->where('scheduleID', '!=', $scheduleId)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Nhân viên đã có lịch làm việc trong ngày này!');
        }

        $schedule->update([
            'dayOfWeek' => $request->dayOfWeek,
            'startTime' => $request->startTime,
            'endTime' => $request->endTime,
        ]);

        return back()->with('success', 'Cập nhật lịch làm việc thành công!');
    }

    /**
     * Xóa lịch làm việc
     */
    public function destroySchedule($id, $scheduleId)
    {
        $employee = Employee::findOrFail($id);
        $schedule = $employee->workSchedules()->findOrFail($scheduleId);
        $schedule->delete();

        return back()->with('success', 'Xóa lịch làm việc thành công!');
    }
    
    // ==================== API METHODS ====================
    
    /**
     * API: Get all employees
     * 
     * @OA\Get(
     *     path="/admin/employees",
     *     summary="Lấy danh sách nhân viên",
     *     tags={"Admin Employees"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Employee"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only")
     * )
     */
    public function apiIndex()
    {
        $employees = Employee::with(['services', 'role', 'workSchedules'])->get();

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }

    /**
     * API: Create employee
     * 
     * @OA\Post(
     *     path="/admin/employees",
     *     summary="Thêm nhân viên mới",
     *     tags={"Admin Employees"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"employeeName", "roleID", "phoneNumber", "email"},
     *             @OA\Property(property="employeeName", type="string", example="Trần Văn B"),
     *             @OA\Property(property="roleID", type="integer", example=1),
     *             @OA\Property(property="phoneNumber", type="string", example="0987654321"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="info", type="string"),
     *             @OA\Property(property="services", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Thêm thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Employee")
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
            'employeeName' => 'required|string|max:100',
            'roleID' => 'required|exists:employee_roles,roleID',
            'phoneNumber' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'info' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,serviceID'
        ]);

        $employee = Employee::create($request->except('services'));

        if ($request->has('services')) {
            $employee->services()->sync($request->services);
        }

        return response()->json([
            'success' => true,
            'message' => 'Thêm nhân viên thành công!',
            'data' => $employee->load(['services', 'role'])
        ], 201);
    }

    /**
     * API: Get employee detail
     * 
     * @OA\Get(
     *     path="/admin/employees/{id}",
     *     summary="Lấy chi tiết nhân viên",
     *     tags={"Admin Employees"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID nhân viên",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Employee")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiShow($id)
    {
        $employee = Employee::with(['services', 'role', 'workSchedules'])->find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy nhân viên!'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $employee
        ]);
    }

    /**
     * API: Update employee
     * 
     * @OA\Put(
     *     path="/admin/employees/{id}",
     *     summary="Cập nhật nhân viên",
     *     tags={"Admin Employees"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID nhân viên",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="employeeName", type="string"),
     *             @OA\Property(property="roleID", type="integer"),
     *             @OA\Property(property="phoneNumber", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="info", type="string"),
     *             @OA\Property(property="services", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Employee")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiUpdate(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy nhân viên!'
            ], 404);
        }

        $request->validate([
            'employeeName' => 'sometimes|string|max:100',
            'roleID' => 'sometimes|exists:employee_roles,roleID',
            'phoneNumber' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|max:100',
            'info' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,serviceID'
        ]);

        $employee->update($request->except('services'));

        if ($request->has('services')) {
            $employee->services()->sync($request->services);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật nhân viên thành công!',
            'data' => $employee->load(['services', 'role'])
        ]);
    }

    /**
     * API: Delete employee
     * 
     * @OA\Delete(
     *     path="/admin/employees/{id}",
     *     summary="Xóa nhân viên",
     *     tags={"Admin Employees"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID nhân viên",
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
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiDestroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy nhân viên!'
            ], 404);
        }

        if ($employee->avatar && Storage::disk('public')->exists($employee->avatar)) {
            Storage::disk('public')->delete($employee->avatar);
        }

        $employee->services()->detach();
        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa nhân viên thành công!'
        ]);
    }

    /**
     * API: Add schedule
     * 
     * @OA\Post(
     *     path="/admin/employees/{id}/schedules",
     *     summary="Thêm lịch làm việc cho nhân viên",
     *     tags={"Admin Employees"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID nhân viên",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"dayOfWeek", "startTime", "endTime"},
     *             @OA\Property(property="dayOfWeek", type="string", enum={"Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"}),
     *             @OA\Property(property="startTime", type="string", format="time", example="08:00"),
     *             @OA\Property(property="endTime", type="string", format="time", example="17:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Thêm thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/WorkSchedule")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Đã có lịch trong ngày này"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiStoreSchedule(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy nhân viên!'
            ], 404);
        }

        $request->validate([
            'dayOfWeek' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'startTime' => 'required|date_format:H:i',
            'endTime' => 'required|date_format:H:i|after:startTime',
        ]);

        $exists = $employee->workSchedules()
            ->where('dayOfWeek', $request->dayOfWeek)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Nhân viên đã có lịch làm việc trong ngày này!'
            ], 400);
        }

        $schedule = $employee->workSchedules()->create([
            'dayOfWeek' => $request->dayOfWeek,
            'startTime' => $request->startTime,
            'endTime' => $request->endTime,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm lịch làm việc thành công!',
            'data' => $schedule
        ], 201);
    }

    /**
     * API: Update schedule
     * 
     * @OA\Put(
     *     path="/admin/employees/{id}/schedules/{scheduleId}",
     *     summary="Cập nhật lịch làm việc",
     *     tags={"Admin Employees"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID nhân viên",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="scheduleId",
     *         in="path",
     *         description="ID lịch làm việc",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"dayOfWeek", "startTime", "endTime"},
     *             @OA\Property(property="dayOfWeek", type="string", enum={"Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"}),
     *             @OA\Property(property="startTime", type="string", format="time", example="08:00"),
     *             @OA\Property(property="endTime", type="string", format="time", example="17:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/WorkSchedule")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiUpdateSchedule(Request $request, $id, $scheduleId)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy nhân viên!'
            ], 404);
        }

        $schedule = $employee->workSchedules()->find($scheduleId);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lịch làm việc!'
            ], 404);
        }

        $request->validate([
            'dayOfWeek' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'startTime' => 'required|date_format:H:i',
            'endTime' => 'required|date_format:H:i|after:startTime',
        ]);

        $schedule->update([
            'dayOfWeek' => $request->dayOfWeek,
            'startTime' => $request->startTime,
            'endTime' => $request->endTime,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật lịch làm việc thành công!',
            'data' => $schedule
        ]);
    }

    /**
     * API: Delete schedule
     * 
     * @OA\Delete(
     *     path="/admin/employees/{id}/schedules/{scheduleId}",
     *     summary="Xóa lịch làm việc",
     *     tags={"Admin Employees"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID nhân viên",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="scheduleId",
     *         in="path",
     *         description="ID lịch làm việc",
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
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiDestroySchedule($id, $scheduleId)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy nhân viên!'
            ], 404);
        }

        $schedule = $employee->workSchedules()->find($scheduleId);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lịch làm việc!'
            ], 404);
        }

        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa lịch làm việc thành công!'
        ]);
    }
}
