<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeRole;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
}
