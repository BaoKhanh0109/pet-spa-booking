<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\WorkSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // Bước 1: Chọn loại dịch vụ (beauty, medical, pet_care)
    public function selectCategory() {
        $pets = Pet::where('userID', Auth::user()->userID)->get();
        
        return view('bookings.select-category', compact('pets'));
    }

    // Bước 2a: Hiển thị form đặt lịch làm đẹp
    public function createBeauty(Request $request) {
        $petID = $request->get('petID');
        $pet = Pet::where('petID', $petID)->first();
        $services = Service::where('category', 'beauty')->get();
        
        return view('bookings.beauty', compact('services', 'pet'));
    }

    // Bước 2b: Hiển thị form đặt lịch y tế
    public function createMedical(Request $request) {
        $petID = $request->get('petID');
        $pet = Pet::where('petID', $petID)->first();
        $services = Service::where('category', 'medical')->get();
        $doctors = Employee::whereHas('services', function($q) {
            $q->where('category', 'medical');
        })->get();
        
        return view('bookings.medical', compact('services', 'pet', 'doctors'));
    }

    // Bước 2c: Hiển thị form đặt lịch trông giữ
    public function createPetCare(Request $request) {
        $petID = $request->get('petID');
        $pet = Pet::where('petID', $petID)->first();
        $service = Service::where('category', 'pet_care')->first();
        
        return view('bookings.pet-care', compact('service', 'pet'));
    }

    // API: Lấy nhân viên rảnh theo dịch vụ và ngày giờ
    public function getAvailableStaff(Request $request) {
        $serviceIds = $request->get('service_ids', []);
        $appointmentDate = $request->get('appointment_date');
        $dayOfWeek = date('l', strtotime($appointmentDate));
        
        // Tính tổng thời gian cần cho các dịch vụ
        $totalDuration = Service::whereIn('serviceID', $serviceIds)->sum('duration');
        
        // Tìm nhân viên có thể làm các dịch vụ này
        $availableStaff = Employee::whereHas('services', function($q) use ($serviceIds) {
            $q->whereIn('services.serviceID', $serviceIds);
        })
        ->whereHas('workSchedules', function($q) use ($dayOfWeek) {
            $q->where('work_schedules.dayOfWeek', $dayOfWeek);
        })
        ->get()
        ->filter(function($employee) use ($appointmentDate, $totalDuration) {
            return !$this->hasTimeConflict($employee->employeeID, $appointmentDate, $totalDuration);
        })
        ->values();
        
        return response()->json($availableStaff);
    }

    // API: Lấy lịch rảnh của bác sĩ
    public function getDoctorSchedule(Request $request) {
        try {
            $employeeID = $request->get('employee_id');
            $month = $request->get('month', date('Y-m'));
            
            $schedules = WorkSchedule::where('employeeID', $employeeID)->get();
            
            // Lấy appointments trong khoảng 3 tháng (tháng trước, tháng hiện tại, tháng sau)
            // để đảm bảo có đủ dữ liệu khi user chọn ngày
            $startDate = date('Y-m-01', strtotime($month . '-01 -1 month'));
            $endDate = date('Y-m-t', strtotime($month . '-01 +1 month'));
            
            $appointments = Appointment::where('employeeID', $employeeID)
                ->whereBetween('appointmentDate', [$startDate, $endDate])
                ->with('service:serviceID,serviceName,duration')
                ->get();
            
            return response()->json([
                'schedules' => $schedules,
                'appointments' => $appointments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'schedules' => [],
                'appointments' => []
            ], 500);
        }
    }

    // Lưu đặt lịch làm đẹp (có thể chọn nhiều dịch vụ)
    public function storeBeauty(Request $request) {
        $request->validate([
            'petID' => 'required',
            'service_ids' => 'required|array',
            'appointmentDate' => 'required|date',
            'employeeID' => 'nullable|exists:employees,employeeID'
        ]);

        // Tính tổng thời gian dịch vụ
        $totalDuration = Service::whereIn('serviceID', $request->service_ids)->sum('duration');

        // Nếu không chọn nhân viên, hệ thống tự động chọn
        $employeeID = $request->employeeID;
        if (!$employeeID) {
            $employeeID = $this->autoAssignStaff($request->service_ids, $request->appointmentDate);
            
            if (!$employeeID) {
                return redirect()->back()->withInput()->with('error', 'Không có nhân viên rảnh vào thời gian này. Vui lòng chọn thời gian khác!');
            }
        } else {
            // Nếu có chọn nhân viên, kiểm tra xung đột thời gian
            if ($this->hasTimeConflict($employeeID, $request->appointmentDate, $totalDuration)) {
                return redirect()->back()->withInput()->with('error', 'Nhân viên đã có lịch hẹn vào thời gian này. Vui lòng chọn giờ khác!');
            }
        }

        $appointment = Appointment::create([
            'userID' => Auth::user()->userID,
            'petID' => $request->petID,
            'employeeID' => $employeeID,
            'appointmentDate' => $request->appointmentDate,
            'note' => $request->note,
            'status' => 'Pending',
            'booking_type' => 'beauty'
        ]);

        // Lưu các dịch vụ được chọn
        $appointment->services()->attach($request->service_ids);

        return redirect()->route('booking.history')->with('success', 'Đặt lịch làm đẹp thành công!');
    }

    // Lưu đặt lịch y tế
    public function storeMedical(Request $request) {
        $request->validate([
            'petID' => 'required',
            'serviceID' => 'required|exists:services,serviceID',
            'booking_method' => 'required|in:by_date,by_doctor',
            'appointmentDate' => 'required|date',
            'employeeID' => 'required_if:booking_method,by_doctor'
        ]);

        $employeeID = $request->employeeID;
        $preferDoctor = $request->booking_method == 'by_doctor' ? 1 : 0;
        $service = Service::find($request->serviceID);
        $duration = $service ? $service->duration : 0;

        // Nếu chọn theo ngày, tự động chọn bác sĩ
        if ($request->booking_method == 'by_date') {
            $employeeID = $this->autoAssignDoctor($request->serviceID, $request->appointmentDate);
            
            if (!$employeeID) {
                return redirect()->back()->withInput()->with('error', 'Không có bác sĩ rảnh vào thời gian này. Vui lòng chọn thời gian khác!');
            }
        } else {
            // Nếu chọn theo bác sĩ, kiểm tra xung đột thời gian
            if ($this->hasTimeConflict($employeeID, $request->appointmentDate, $duration)) {
                return redirect()->back()->withInput()->with('error', 'Bác sĩ đã có lịch hẹn vào thời gian này. Vui lòng chọn giờ khác!');
            }
        }

        $appointment = Appointment::create([
            'userID' => Auth::user()->userID,
            'petID' => $request->petID,
            'employeeID' => $employeeID,
            'serviceID' => $request->serviceID,
            'appointmentDate' => $request->appointmentDate,
            'note' => $request->note,
            'status' => 'Pending',
            'booking_type' => 'medical',
            'prefer_doctor' => $preferDoctor
        ]);

        return redirect()->route('booking.history')->with('success', 'Đặt lịch khám bệnh thành công!');
    }

    // Lưu đặt lịch trông giữ
    public function storePetCare(Request $request) {
        $request->validate([
            'petID' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate'
        ]);

        $service = Service::where('category', 'pet_care')->first();
        $employeeID = $this->autoAssignStaff([$service->serviceID], $request->startDate);

        $appointment = Appointment::create([
            'userID' => Auth::user()->userID,
            'petID' => $request->petID,
            'employeeID' => $employeeID,
            'serviceID' => $service->serviceID,
            'appointmentDate' => $request->startDate,
            'endDate' => $request->endDate,
            'note' => $request->note,
            'status' => 'Pending',
            'booking_type' => 'pet_care'
        ]);

        return redirect()->route('booking.history')->with('success', 'Đặt lịch trông giữ thành công!');
    }

    // Hàm tự động chọn nhân viên rảnh
    private function autoAssignStaff($serviceIds, $appointmentDate) {
        $dayOfWeek = date('l', strtotime($appointmentDate));
        
        // Tính tổng thời gian cần cho các dịch vụ
        $totalDuration = Service::whereIn('serviceID', $serviceIds)->sum('duration');
        
        $employees = Employee::whereHas('services', function($q) use ($serviceIds) {
            $q->whereIn('services.serviceID', $serviceIds);
        })
        ->whereHas('workSchedules', function($q) use ($dayOfWeek) {
            $q->where('work_schedules.dayOfWeek', $dayOfWeek);
        })
        ->get();
        
        // Tìm nhân viên không có xung đột thời gian
        foreach($employees->shuffle() as $employee) {
            if(!$this->hasTimeConflict($employee->employeeID, $appointmentDate, $totalDuration)) {
                return $employee->employeeID;
            }
        }
        
        return null;
    }
    
    // Kiểm tra xung đột thời gian
    private function hasTimeConflict($employeeId, $appointmentDate, $duration) {
        $startTime = strtotime($appointmentDate);
        $endTime = $startTime + ($duration * 60);
        
        // Lấy tất cả appointments của nhân viên trong ngày
        $existingAppointments = Appointment::where('employeeID', $employeeId)
            ->whereDate('appointmentDate', date('Y-m-d', $startTime))
            ->get();
        
        foreach($existingAppointments as $apt) {
            $aptStart = strtotime($apt->appointmentDate);
            
            // Tính duration của appointment hiện có
            $aptDuration = 0;
            if($apt->booking_type === 'beauty') {
                $aptDuration = $apt->services()->sum('duration');
            } else if($apt->booking_type === 'medical') {
                $service = Service::find($apt->serviceID);
                $aptDuration = $service ? $service->duration : 0;
            }
            
            $aptEnd = $aptStart + ($aptDuration * 60);
            
            // Kiểm tra overlap: [startTime, endTime] với [aptStart, aptEnd]
            if(!($endTime <= $aptStart || $startTime >= $aptEnd)) {
                return true; // Có xung đột
            }
        }
        
        return false; // Không xung đột
    }

    // Hàm tự động chọn bác sĩ rảnh
    private function autoAssignDoctor($serviceID, $appointmentDate) {
        $dayOfWeek = date('l', strtotime($appointmentDate));
        
        $service = Service::find($serviceID);
        $duration = $service ? $service->duration : 0;
        
        $doctors = Employee::whereHas('services', function($q) use ($serviceID) {
            $q->where('services.serviceID', $serviceID);
        })
        ->whereHas('workSchedules', function($q) use ($dayOfWeek) {
            $q->where('work_schedules.dayOfWeek', $dayOfWeek);
        })
        ->get();
        
        // Tìm bác sĩ không có xung đột thời gian
        foreach($doctors as $doctor) {
            if(!$this->hasTimeConflict($doctor->employeeID, $appointmentDate, $duration)) {
                return $doctor->employeeID;
            }
        }
        
        return null;
    }

    public function create() {
        $services = Service::all();
        $pets = Pet::where('userID', Auth::user()->userID)->get();
        
        return view('bookings.create', compact('services', 'pets'));
    }

    public function store(Request $request) {
        $request->validate([
            'petID' => 'required',
            'serviceID' => 'required',
            'appointmentDate' => 'required|date',
        ]);

        Appointment::create([
            'userID' => Auth::user()->userID,
            'petID' => $request->petID,
            'serviceID' => $request->serviceID,
            'appointmentDate' => $request->appointmentDate,
            'note' => $request->note,
            'status' => 'Pending',
            'booking_type' => 'beauty'
        ]);

        return redirect()->route('booking.history')->with('success', 'Đặt lịch thành công! Nhân viên sẽ sớm liên hệ.');
    }
    
    public function history() {
        $appointments = Appointment::where('userID', Auth::user()->userID)
                        ->with(['pet', 'service', 'services', 'employee']) 
                        ->orderBy('appointmentDate', 'desc')
                        ->get();

        return view('bookings.history', compact('appointments'));
    }

    public function edit($id) {
        $appointment = Appointment::where('appointmentID', $id)
                                  ->where('userID', Auth::user()->userID)
                                  ->with(['pet', 'service', 'services', 'employee'])
                                  ->firstOrFail();

        if ($appointment->status !== 'Pending') {
            return redirect()->route('booking.history')
                           ->with('error', 'Chỉ có thể chỉnh sửa lịch hẹn đang chờ phê duyệt!');
        }

        $pets = Pet::where('userID', Auth::user()->userID)->get();

        if ($appointment->booking_type === 'beauty') {
            $services = Service::where('category', 'beauty')->get();
            return view('bookings.edit-beauty', compact('appointment', 'services', 'pets'));
        } elseif ($appointment->booking_type === 'medical') {
            $services = Service::where('category', 'medical')->get();
            $doctors = Employee::whereHas('services', function($q) {
                $q->where('category', 'medical');
            })->get();
            return view('bookings.edit-medical', compact('appointment', 'services', 'doctors', 'pets'));
        } else {
            $service = Service::where('category', 'pet_care')->first();
            return view('bookings.edit-pet-care', compact('appointment', 'service', 'pets'));
        }
    }

    public function update(Request $request, $id) {
        $appointment = Appointment::where('appointmentID', $id)
                                  ->where('userID', Auth::user()->userID)
                                  ->firstOrFail();

        if ($appointment->status !== 'Pending') {
            return redirect()->route('booking.history')
                           ->with('error', 'Chỉ có thể chỉnh sửa lịch hẹn đang chờ phê duyệt!');
        }

        if ($appointment->booking_type === 'beauty') {
            $request->validate([
                'petID' => 'required',
                'service_ids' => 'required|array',
                'appointmentDate' => 'required|date',
                'employeeID' => 'nullable|exists:employees,employeeID'
            ]);

            $totalDuration = Service::whereIn('serviceID', $request->service_ids)->sum('duration');
            $employeeID = $request->employeeID;

            if (!$employeeID) {
                $employeeID = $this->autoAssignStaff($request->service_ids, $request->appointmentDate);
                if (!$employeeID) {
                    return redirect()->back()->withInput()
                                   ->with('error', 'Không có nhân viên rảnh vào thời gian này!');
                }
            } else {
                if ($this->hasTimeConflictExcept($employeeID, $request->appointmentDate, $totalDuration, $id)) {
                    return redirect()->back()->withInput()
                                   ->with('error', 'Nhân viên đã có lịch hẹn vào thời gian này!');
                }
            }

            $appointment->update([
                'petID' => $request->petID,
                'employeeID' => $employeeID,
                'appointmentDate' => $request->appointmentDate,
                'note' => $request->note
            ]);

            $appointment->services()->sync($request->service_ids);

        } elseif ($appointment->booking_type === 'medical') {
            $request->validate([
                'petID' => 'required',
                'serviceID' => 'required|exists:services,serviceID',
                'booking_method' => 'required|in:by_date,by_doctor',
                'appointmentDate' => 'required|date',
                'employeeID' => 'required_if:booking_method,by_doctor'
            ]);

            $employeeID = $request->employeeID;
            $preferDoctor = $request->booking_method == 'by_doctor' ? 1 : 0;
            $service = Service::find($request->serviceID);
            $duration = $service ? $service->duration : 0;

            if ($request->booking_method == 'by_date') {
                $employeeID = $this->autoAssignDoctor($request->serviceID, $request->appointmentDate);
                if (!$employeeID) {
                    return redirect()->back()->withInput()
                                   ->with('error', 'Không có bác sĩ rảnh vào thời gian này!');
                }
            } else {
                if ($this->hasTimeConflictExcept($employeeID, $request->appointmentDate, $duration, $id)) {
                    return redirect()->back()->withInput()
                                   ->with('error', 'Bác sĩ đã có lịch hẹn vào thời gian này!');
                }
            }

            $appointment->update([
                'petID' => $request->petID,
                'employeeID' => $employeeID,
                'serviceID' => $request->serviceID,
                'appointmentDate' => $request->appointmentDate,
                'note' => $request->note,
                'prefer_doctor' => $preferDoctor
            ]);

        } else {
            $request->validate([
                'petID' => 'required',
                'startDate' => 'required|date',
                'endDate' => 'required|date|after_or_equal:startDate'
            ]);

            $service = Service::where('category', 'pet_care')->first();
            $employeeID = $this->autoAssignStaff([$service->serviceID], $request->startDate);

            $appointment->update([
                'petID' => $request->petID,
                'employeeID' => $employeeID,
                'appointmentDate' => $request->startDate,
                'endDate' => $request->endDate,
                'note' => $request->note
            ]);
        }

        return redirect()->route('booking.history')
                       ->with('success', 'Cập nhật lịch hẹn thành công!');
    }

    public function destroy($id) {
        $appointment = Appointment::where('appointmentID', $id)
                                  ->where('userID', Auth::user()->userID)
                                  ->firstOrFail();

        if ($appointment->status !== 'Pending') {
            return redirect()->route('booking.history')
                           ->with('error', 'Chỉ có thể xóa lịch hẹn đang chờ phê duyệt!');
        }

        if ($appointment->booking_type === 'beauty') {
            $appointment->services()->detach();
        }

        $appointment->delete();

        return redirect()->route('booking.history')
                       ->with('success', 'Đã xóa lịch hẹn thành công!');
    }

    private function hasTimeConflictExcept($employeeId, $appointmentDate, $duration, $exceptId) {
        $startTime = strtotime($appointmentDate);
        $endTime = $startTime + ($duration * 60);
        
        $existingAppointments = Appointment::where('employeeID', $employeeId)
            ->where('appointmentID', '!=', $exceptId)
            ->whereDate('appointmentDate', date('Y-m-d', $startTime))
            ->get();
        
        foreach($existingAppointments as $apt) {
            $aptStart = strtotime($apt->appointmentDate);
            
            $aptDuration = 0;
            if($apt->booking_type === 'beauty') {
                $aptDuration = $apt->services()->sum('duration');
            } else if($apt->booking_type === 'medical') {
                $service = Service::find($apt->serviceID);
                $aptDuration = $service ? $service->duration : 0;
            }
            
            $aptEnd = $aptStart + ($aptDuration * 60);
            
            if(!($endTime <= $aptStart || $startTime >= $aptEnd)) {
                return true;
            }
        }
        
        return false;
    }
}
