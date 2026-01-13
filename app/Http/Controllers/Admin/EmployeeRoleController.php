<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeRole;
use Illuminate\Http\Request;

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
}
