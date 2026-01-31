<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Pet;
use App\Models\Employee;
use OpenApi\Annotations as OA;

class AdminController extends Controller
{
    // ==================== WEB METHODS ====================

    public function indexServices() {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    public function storeService(Request $request) {
        $request->validate([
            'serviceName' => 'required',
            'price' => 'required|numeric',
        ]);

        Service::create($request->all());
        return back()->with('success', 'Thêm dịch vụ thành công!');
    }

    public function deleteService($id) {
        Service::destroy($id);
        return back()->with('success', 'Đã xóa dịch vụ!');
    }

    public function indexAppointments() {
        $appointments = Appointment::with(['user', 'pet', 'services.category', 'employee.role'])
                        ->orderBy('appointmentDate', 'desc')
                        ->get();
                        
        return view('admin.appointments.index', compact('appointments'));
    }

    public function updateStatus($id, $status) {
        $appointment = Appointment::find($id);
        if ($appointment) {
            $appointment->status = $status;
            $appointment->save();
        }
        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
    
    // ==================== API METHODS ====================
    
    /**
     * API: Dashboard statistics
     * 
     * @OA\Get(
     *     path="/admin/dashboard",
     *     summary="Thống kê tổng quan dashboard",
     *     tags={"Admin Dashboard"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="stats",
     *                     type="object",
     *                     @OA\Property(property="total_services", type="integer"),
     *                     @OA\Property(property="total_appointments", type="integer"),
     *                     @OA\Property(property="total_users", type="integer"),
     *                     @OA\Property(property="total_pets", type="integer"),
     *                     @OA\Property(property="total_employees", type="integer"),
     *                     @OA\Property(property="pending_appointments", type="integer"),
     *                     @OA\Property(property="confirmed_appointments", type="integer"),
     *                     @OA\Property(property="completed_appointments", type="integer"),
     *                     @OA\Property(property="cancelled_appointments", type="integer")
     *                 ),
     *                 @OA\Property(property="recent_appointments", type="array", @OA\Items(ref="#/components/schemas/Appointment")),
     *                 @OA\Property(property="today_appointments", type="array", @OA\Items(ref="#/components/schemas/Appointment"))
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only")
     * )
     */
    public function apiDashboard()
    {
        $stats = [
            'total_services' => Service::count(),
            'total_appointments' => Appointment::count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_pets' => Pet::count(),
            'total_employees' => Employee::count(),
            'pending_appointments' => Appointment::where('status', 'Pending')->count(),
            'confirmed_appointments' => Appointment::where('status', 'Confirmed')->count(),
            'completed_appointments' => Appointment::where('status', 'Completed')->count(),
            'cancelled_appointments' => Appointment::where('status', 'Cancelled')->count(),
        ];

        $recentAppointments = Appointment::with(['user', 'pet', 'services', 'employee'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $todayAppointments = Appointment::with(['user', 'pet', 'services', 'employee'])
            ->whereDate('appointmentDate', today())
            ->orderBy('appointmentDate')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'recent_appointments' => $recentAppointments,
                'today_appointments' => $todayAppointments,
            ],
            'message' => 'Lấy thống kê dashboard thành công'
        ]);
    }

    /**
     * API: Revenue statistics
     * 
     * @OA\Get(
     *     path="/admin/dashboard/revenue",
     *     summary="Thống kê doanh thu",
     *     tags={"Admin Dashboard"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Ngày bắt đầu",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Ngày kết thúc",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="start_date", type="string", format="date"),
     *                 @OA\Property(property="end_date", type="string", format="date"),
     *                 @OA\Property(property="total_appointments", type="integer"),
     *                 @OA\Property(property="total_revenue", type="number")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only")
     * )
     */
    public function apiRevenue(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $completedAppointments = Appointment::where('status', 'Completed')
            ->whereBetween('appointmentDate', [$startDate, $endDate])
            ->with('services')
            ->get();

        $totalRevenue = 0;
        foreach ($completedAppointments as $appointment) {
            $totalRevenue += $appointment->services->sum('price');
        }

        return response()->json([
            'success' => true,
            'data' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_appointments' => $completedAppointments->count(),
                'total_revenue' => $totalRevenue,
            ],
            'message' => 'Lấy thống kê doanh thu thành công'
        ]);
    }
    
    /**
     * API: Get all appointments
     * 
     * @OA\Get(
     *     path="/admin/appointments",
     *     summary="Lấy danh sách tất cả lịch hẹn",
     *     tags={"Admin Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Appointment"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only")
     * )
     */
    public function apiAppointments()
    {
        $appointments = Appointment::with(['user', 'pet', 'services.category', 'employee.role'])
            ->orderBy('appointmentDate', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }
    
    /**
     * API: Get appointment detail
     * 
     * @OA\Get(
     *     path="/admin/appointments/{id}",
     *     summary="Lấy chi tiết lịch hẹn",
     *     tags={"Admin Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID lịch hẹn",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Appointment")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiAppointmentShow($id)
    {
        $appointment = Appointment::with(['user', 'pet', 'services.category', 'employee.role'])->find($id);

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lịch hẹn!'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $appointment
        ]);
    }

    /**
     * API: Update appointment status
     * 
     * @OA\Patch(
     *     path="/admin/appointments/{id}/status",
     *     summary="Cập nhật trạng thái lịch hẹn",
     *     tags={"Admin Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID lịch hẹn",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"Pending", "Confirmed", "Completed", "Cancelled"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Appointment"),
     *             @OA\Property(property="message", type="string", example="Cập nhật trạng thái thành công!")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiUpdateStatus(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        
        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lịch hẹn!'
            ], 404);
        }

        $request->validate([
            'status' => 'required|in:Pending,Confirmed,Completed,Cancelled'
        ]);

        $appointment->status = $request->status;
        $appointment->save();

        return response()->json([
            'success' => true,
            'data' => $appointment->load(['user', 'pet', 'services', 'employee']),
            'message' => 'Cập nhật trạng thái thành công!'
        ]);
    }

    /**
     * API: Delete appointment
     * 
     * @OA\Delete(
     *     path="/admin/appointments/{id}",
     *     summary="Xóa lịch hẹn",
     *     tags={"Admin Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID lịch hẹn",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Xóa lịch hẹn thành công!")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Admin only"),
     *     @OA\Response(response=404, description="Không tìm thấy")
     * )
     */
    public function apiDeleteAppointment($id)
    {
        $appointment = Appointment::find($id);
        
        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lịch hẹn!'
            ], 404);
        }

        $appointment->services()->detach();
        $appointment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa lịch hẹn thành công!'
        ]);
    }
}
