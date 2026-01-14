<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\WorkSchedule;
use App\Helpers\PricingHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    private function getServicesByCategoryID($categoryID) {
        return Service::where('categoryID', $categoryID)->get();
    }
    
    private function getServiceByCategoryID($categoryID) {
        return Service::where('categoryID', $categoryID)->first();
    }
    
    public function selectCategory() {
        $pets = Pet::where('userID', Auth::user()->userID)->get();
        $categories = \App\Models\ServiceCategory::all();
        
        return view('bookings.select-category', compact('pets', 'categories'));
    }

    public function createBeauty(Request $request) {
        $petID = $request->get('petID');
        $selectedServiceID = $request->get('serviceID');
        $pet = Pet::where('petID', $petID)->first();
        $services = $this->getServicesByCategoryID(1);
        
        // Tính size và giá điều chỉnh cho từng dịch vụ
        $petSize = PricingHelper::getPetSize($pet->weight, $pet->backLength);
        foreach($services as $service) {
            $service->petSize = $petSize;
            $service->adjustedPrice = PricingHelper::calculatePriceBySize($service->price, $petSize);
        }
        
        return view('bookings.beauty', compact('services', 'pet', 'selectedServiceID'));
    }

    // Bước 2b: Hiển thị form đặt lịch y tế
    public function createMedical(Request $request) {
        $petID = $request->get('petID');
        $selectedServiceID = $request->get('serviceID'); // Nhận serviceID từ query string
        $pet = Pet::where('petID', $petID)->first();
        $services = $this->getServicesByCategoryID(2); // 2 = Y tế
        

        $petSize = PricingHelper::getPetSize($pet->weight, $pet->backLength);
        foreach($services as $service) {
            $service->petSize = $petSize;
            $service->adjustedPrice = PricingHelper::calculatePriceBySize($service->price, $petSize);
        }
        
        $doctors = Employee::with('role')->whereHas('services', function($q) {
            $q->where('categoryID', 2);
        })->get();
        
        return view('bookings.medical', compact('services', 'pet', 'doctors', 'selectedServiceID'));
    }

    public function createPetCare(Request $request) {
        $petID = $request->get('petID');
        $pet = Pet::where('petID', $petID)->first();
        $service = Service::with('category')->where('categoryID', 3)->first();
        
        $petSize = PricingHelper::getPetSize($pet->weight, $pet->backLength);
        $service->petSize = $petSize;
        $service->adjustedPrice = PricingHelper::calculatePriceBySize($service->price, $petSize);
        
        return view('bookings.pet-care', compact('service', 'pet'));
    }

    public function getAvailableStaff(Request $request) {
        $serviceIds = $request->get('service_ids', []);
        $appointmentDate = $request->get('appointment_date');
        $dayOfWeek = date('l', strtotime($appointmentDate));
        $appointmentTime = date('H:i:s', strtotime($appointmentDate));
        
        $totalDuration = Service::whereIn('serviceID', $serviceIds)->sum('duration');
        $appointmentEndTime = date('H:i:s', strtotime($appointmentDate) + ($totalDuration * 60));
        
        $availableStaff = Employee::with('role')
        ->whereHas('services', function($q) use ($serviceIds) {
            $q->whereIn('services.serviceID', $serviceIds);
        })
        ->whereHas('workSchedules', function($q) use ($dayOfWeek, $appointmentTime, $appointmentEndTime) {
            $q->where('work_schedules.dayOfWeek', $dayOfWeek)
              ->where('work_schedules.startTime', '<=', $appointmentTime)
              ->where('work_schedules.endTime', '>=', $appointmentEndTime);
        })
        ->get()
        ->filter(function($employee) use ($appointmentDate, $totalDuration) {
            return !$this->hasTimeConflict($employee->employeeID, $appointmentDate, $totalDuration);
        })
        ->map(function($employee) {
            return [
                'employeeID' => $employee->employeeID,
                'employeeName' => $employee->employeeName,
                'role' => $employee->role ? $employee->role->roleName : 'Nhân viên'
            ];
        })
        ->values();
        
        return response()->json($availableStaff);
    }

    public function getDoctorSchedule(Request $request) {
        try {
            $employeeID = $request->get('employee_id');
            $month = $request->get('month', date('Y-m'));
            
            $schedules = WorkSchedule::where('employeeID', $employeeID)->get();
            
            $startDate = date('Y-m-01', strtotime($month . '-01 -1 month'));
            $endDate = date('Y-m-t', strtotime($month . '-01 +1 month'));
            
            $appointments = Appointment::where('employeeID', $employeeID)
                ->whereBetween('appointmentDate', [$startDate, $endDate])
                ->with('services:serviceID,serviceName,duration')
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

    public function storeBeauty(Request $request) {
        $request->validate([
            'petID' => 'required',
            'service_ids' => 'required|array',
            'appointmentDate' => 'required|date',
            'employeeID' => 'nullable|exists:employees,employeeID'
        ]);

        $totalDuration = Service::whereIn('serviceID', $request->service_ids)->sum('duration');

        if ($this->hasPetTimeConflict($request->petID, $request->appointmentDate, $totalDuration)) {
            return redirect()->back()->withInput()
                ->with('error', 'Thú cưng đã có lịch hẹn vào thời gian này. Vui lòng chọn thời gian khác!');
        }

        $employeeID = $request->employeeID;
        if (!$employeeID) {
            $employeeID = $this->autoAssignStaff($request->service_ids, $request->appointmentDate);
            
            if (!$employeeID) {
                return redirect()->back()->withInput()->with('error', 'Không có nhân viên rảnh vào thời gian này. Vui lòng chọn thời gian khác!');
            }
        } else {
            if ($this->hasTimeConflict($employeeID, $request->appointmentDate, $totalDuration)) {
                return redirect()->back()->withInput()->with('error', 'Nhân viên đã có lịch hẹn vào thời gian này. Vui lòng chọn giờ khác!');
            }
        }

        $appointment = Appointment::create([
            'service_categories' => 1,
            'userID' => Auth::user()->userID,
            'petID' => $request->petID,
            'employeeID' => $employeeID,
            'appointmentDate' => $request->appointmentDate,
            'note' => $request->note,
            'status' => 'Pending'
        ]);

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

        // Kiểm tra xung đột thời gian cho thú cưng
        if ($this->hasPetTimeConflict($request->petID, $request->appointmentDate, $duration)) {
            return redirect()->back()->withInput()
                ->with('error', 'Thú cưng đã có lịch hẹn vào thời gian này. Vui lòng chọn thời gian khác!');
        }

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
            'service_categories' => 2, // 2 = Y tế
            'userID' => Auth::user()->userID,
            'petID' => $request->petID,
            'employeeID' => $employeeID,
            'appointmentDate' => $request->appointmentDate,
            'note' => $request->note,
            'status' => 'Pending',
            'prefer_doctor' => $preferDoctor
        ]);

        // Lưu service vào appointment_services
        $appointment->services()->attach($request->serviceID);

        return redirect()->route('booking.history')->with('success', 'Đặt lịch khám bệnh thành công!');
    }

    // Lưu đặt lịch trông giữ
    public function storePetCare(Request $request) {
        $request->validate([
            'petID' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate'
        ]);

        $service = Service::with('category')->where('categoryID', 3)->first(); // 3 = Trông giữ
        
        // Kiểm tra xung đột thời gian cho thú cưng
        if ($this->hasPetTimeConflict($request->petID, $request->startDate, null, $request->endDate)) {
            return redirect()->back()->withInput()
                ->with('error', 'Thú cưng đã có lịch hẹn vào khoảng thời gian này. Vui lòng chọn thời gian khác!');
        }
        
        // Kiểm tra sức chứa của tiệm trong khoảng thời gian đã chọn
        if ($service && $service->category && $service->category->capacity) {
            $capacity = $service->category->capacity;
            
            // Đếm số lượng thú cưng đang được trông giữ trong khoảng thời gian này
            $occupiedCount = Appointment::where('service_categories', 3) // 3 = Trông giữ
            ->where('status', '!=', 'Cancelled')
            ->where(function($query) use ($request) {
                // Kiểm tra overlap: booking mới có overlap với booking cũ
                $query->where(function($q) use ($request) {
                    // Booking cũ bắt đầu trong khoảng booking mới
                    $q->whereBetween('appointmentDate', [$request->startDate, $request->endDate]);
                })->orWhere(function($q) use ($request) {
                    // Booking cũ kết thúc trong khoảng booking mới
                    $q->whereBetween('endDate', [$request->startDate, $request->endDate]);
                })->orWhere(function($q) use ($request) {
                    // Booking mới nằm hoàn toàn trong booking cũ
                    $q->where('appointmentDate', '<=', $request->startDate)
                      ->where('endDate', '>=', $request->endDate);
                });
            })
            ->count();
            
            // Nếu đã hết chỗ
            if ($occupiedCount >= $capacity) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Xin lỗi! Tiệm đã hết chỗ trống trong khoảng thời gian này (Sức chứa: {$capacity} chỗ). Vui lòng chọn khoảng thời gian khác hoặc liên hệ để được tư vấn.");
            }
        }

        $employeeID = $this->autoAssignStaff([$service->serviceID], $request->startDate);

        $appointment = Appointment::create([
            'service_categories' => 3, // 3 = Trông giữ
            'userID' => Auth::user()->userID,
            'petID' => $request->petID,
            'employeeID' => $employeeID,
            'appointmentDate' => $request->startDate,
            'endDate' => $request->endDate,
            'note' => $request->note,
            'status' => 'Pending'
        ]);

        // Lưu service vào appointment_services
        $appointment->services()->attach($service->serviceID);

        return redirect()->route('booking.history')->with('success', 'Đặt lịch trông giữ thành công!');
    }

    // Hàm tự động chọn nhân viên rảnh
    private function autoAssignStaff($serviceIds, $appointmentDate) {
        $dayOfWeek = date('l', strtotime($appointmentDate));
        $appointmentTime = date('H:i:s', strtotime($appointmentDate));
        
        // Tính tổng thời gian cần cho các dịch vụ
        $totalDuration = Service::whereIn('serviceID', $serviceIds)->sum('duration');
        $appointmentEndTime = date('H:i:s', strtotime($appointmentDate) + ($totalDuration * 60));
        
        $employees = Employee::whereHas('services', function($q) use ($serviceIds) {
            $q->whereIn('services.serviceID', $serviceIds);
        })
        ->whereHas('workSchedules', function($q) use ($dayOfWeek, $appointmentTime, $appointmentEndTime) {
            $q->where('work_schedules.dayOfWeek', $dayOfWeek)
              ->where('work_schedules.startTime', '<=', $appointmentTime)
              ->where('work_schedules.endTime', '>=', $appointmentEndTime);
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
            if($apt->service_categories == 1) { // 1 = Làm đẹp
                $aptDuration = $apt->services()->sum('duration');
            } else if($apt->service_categories == 2) { // 2 = Y tế
                // Lấy duration từ services relationship
                $aptDuration = $apt->services()->sum('duration');
            }
            
            $aptEnd = $aptStart + ($aptDuration * 60);
            
            // Kiểm tra overlap: [startTime, endTime] với [aptStart, aptEnd]
            if(!($endTime <= $aptStart || $startTime >= $aptEnd)) {
                return true; // Có xung đột
            }
        }
        
        return false; // Không xung đột
    }

    // Kiểm tra xung đột thời gian cho cùng một thú cưng
    private function hasPetTimeConflict($petID, $appointmentDate, $duration, $endDate = null) {
        $startTime = strtotime($appointmentDate);
        
        // Nếu có endDate (dịch vụ trông giữ), kiểm tra overlap với khoảng thời gian
        if ($endDate) {
            $endTime = strtotime($endDate);
            
            $existingAppointments = Appointment::where('petID', $petID)
                ->where('status', '!=', 'Cancelled')
                ->where(function($query) use ($appointmentDate, $endDate) {
                    $query->where(function($q) use ($appointmentDate, $endDate) {
                        // Booking cũ bắt đầu trong khoảng booking mới
                        $q->whereBetween('appointmentDate', [$appointmentDate, $endDate]);
                    })->orWhere(function($q) use ($appointmentDate, $endDate) {
                        // Booking cũ kết thúc trong khoảng booking mới
                        $q->whereBetween('endDate', [$appointmentDate, $endDate]);
                    })->orWhere(function($q) use ($appointmentDate, $endDate) {
                        // Booking mới nằm hoàn toàn trong booking cũ
                        $q->where('appointmentDate', '<=', $appointmentDate)
                          ->where('endDate', '>=', $endDate);
                    });
                })
                ->exists();
                
            return $existingAppointments;
        }
        
        // Nếu không có endDate (dịch vụ làm đẹp, y tế), kiểm tra overlap theo duration
        $endTime = $startTime + ($duration * 60);
        
        $existingAppointments = Appointment::where('petID', $petID)
            ->where('status', '!=', 'Cancelled')
            ->whereDate('appointmentDate', date('Y-m-d', $startTime))
            ->get();
        
        foreach($existingAppointments as $apt) {
            $aptStart = strtotime($apt->appointmentDate);
            
            // Tính duration của appointment hiện có
            $aptDuration = 0;
            if($apt->service_categories == 1 || $apt->service_categories == 2) {
                $aptDuration = $apt->services()->sum('duration');
            } else if($apt->service_categories == 3 && $apt->endDate) {
                // Nếu là dịch vụ trông giữ, kiểm tra overlap với khoảng thời gian
                $aptEnd = strtotime($apt->endDate);
                if(!($endTime <= $aptStart || $startTime >= $aptEnd)) {
                    return true; // Có xung đột
                }
                continue;
            }
            
            $aptEnd = $aptStart + ($aptDuration * 60);
            
            // Kiểm tra overlap
            if(!($endTime <= $aptStart || $startTime >= $aptEnd)) {
                return true; // Có xung đột
            }
        }
        
        return false; // Không xung đột
    }

    // Hàm tự động chọn bác sĩ rảnh
    private function autoAssignDoctor($serviceID, $appointmentDate) {
        $dayOfWeek = date('l', strtotime($appointmentDate));
        $appointmentTime = date('H:i:s', strtotime($appointmentDate));
        
        $service = Service::find($serviceID);
        $duration = $service ? $service->duration : 0;
        $appointmentEndTime = date('H:i:s', strtotime($appointmentDate) + ($duration * 60));
        
        $doctors = Employee::whereHas('services', function($q) use ($serviceID) {
            $q->where('services.serviceID', $serviceID);
        })
        ->whereHas('workSchedules', function($q) use ($dayOfWeek, $appointmentTime, $appointmentEndTime) {
            $q->where('work_schedules.dayOfWeek', $dayOfWeek)
              ->where('work_schedules.startTime', '<=', $appointmentTime)
              ->where('work_schedules.endTime', '>=', $appointmentEndTime);
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

        $appointment = Appointment::create([
            'service_categories' => 1, // 1 = Làm đẹp (mặc định cho route cũ)
            'userID' => Auth::user()->userID,
            'petID' => $request->petID,
            'appointmentDate' => $request->appointmentDate,
            'note' => $request->note,
            'status' => 'Pending'
        ]);
        
        // Lưu service vào appointment_services
        $appointment->services()->attach($request->serviceID);

        return redirect()->route('booking.history')->with('success', 'Đặt lịch thành công! Nhân viên sẽ sớm liên hệ.');
    }
    
    public function history() {
        $appointments = Appointment::where('userID', Auth::user()->userID)
                        ->with(['pet', 'serviceCategory', 'services', 'employee']) 
                        ->orderBy('appointmentDate', 'desc')
                        ->get();

        return view('bookings.history', compact('appointments'));
    }

    public function edit($id) {
        $appointment = Appointment::where('appointmentID', $id)
                                  ->where('userID', Auth::user()->userID)
                                  ->with(['pet', 'serviceCategory', 'services', 'employee'])
                                  ->firstOrFail();

        if ($appointment->status !== 'Pending') {
            return redirect()->route('booking.history')
                           ->with('error', 'Chỉ có thể chỉnh sửa lịch hẹn đang chờ phê duyệt!');
        }

        $pets = Pet::where('userID', Auth::user()->userID)->get();
        $pet = $appointment->pet;

        // Dùng service_categories thay vì booking_type
        if ($appointment->service_categories == 1) { // 1 = Làm đẹp
            $services = $this->getServicesByCategoryID(1);
            
            // Tính size và giá điều chỉnh cho từng dịch vụ
            $petSize = PricingHelper::getPetSize($pet->weight, $pet->backLength);
            foreach($services as $service) {
                $service->petSize = $petSize;
                $service->adjustedPrice = PricingHelper::calculatePriceBySize($service->price, $petSize);
            }
            
            return view('bookings.edit-beauty', compact('appointment', 'services', 'pet', 'pets'));
        } elseif ($appointment->service_categories == 2) { // 2 = Y tế
            $services = $this->getServicesByCategoryID(2);
            
            // Tính size và giá điều chỉnh cho từng dịch vụ
            $petSize = PricingHelper::getPetSize($pet->weight, $pet->backLength);
            foreach($services as $service) {
                $service->petSize = $petSize;
                $service->adjustedPrice = PricingHelper::calculatePriceBySize($service->price, $petSize);
            }
            
            $doctors = Employee::with('role')->whereHas('services', function($q) {
                $q->where('categoryID', 2);
            })->get();
            return view('bookings.edit-medical', compact('appointment', 'services', 'doctors', 'pet', 'pets'));
        } else { // 3 = Trông giữ
            $service = Service::with('category')->where('categoryID', 3)->first();
            
            // Tính size và giá điều chỉnh
            $petSize = PricingHelper::getPetSize($pet->weight, $pet->backLength);
            $service->petSize = $petSize;
            $service->adjustedPrice = PricingHelper::calculatePriceBySize($service->price, $petSize);
            
            return view('bookings.edit-pet-care', compact('appointment', 'service', 'pet', 'pets'));
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

        if ($appointment->service_categories == 1) { // 1 = Làm đẹp
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

        } elseif ($appointment->service_categories == 2) { // 2 = Y tế
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

            $service = Service::where('categoryID', 3)->first(); // 3 = Trông giữ
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

        // Xóa services liên kết (tất cả categories đều có services)
        $appointment->services()->detach();

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
            if($apt->service_categories == 1) { // 1 = Làm đẹp
                $aptDuration = $apt->services()->sum('duration');
            } else if($apt->service_categories == 2) { // 2 = Y tế
                $aptDuration = $apt->services()->sum('duration');
            }
            
            $aptEnd = $aptStart + ($aptDuration * 60);
            
            if(!($endTime <= $aptStart || $startTime >= $aptEnd)) {
                return true;
            }
        }
        
        return false;
    }
}
