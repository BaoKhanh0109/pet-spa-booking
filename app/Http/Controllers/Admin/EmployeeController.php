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
        $employee = Employee::with(['services', 'role', 'workSchedules', 'appointments'])->findOrFail($id);
        return view('admin.employees.show', compact('employee'));
    }
}
