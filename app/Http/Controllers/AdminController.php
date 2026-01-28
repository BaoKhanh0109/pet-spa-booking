<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Pet;
use App\Models\Employee;

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
