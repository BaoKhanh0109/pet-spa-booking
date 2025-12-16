<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Appointment;

class AdminController extends Controller
{

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
        $appointments = Appointment::with(['user', 'pet', 'service'])
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
}