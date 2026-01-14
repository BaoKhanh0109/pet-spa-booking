<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách users
     */
    public function index()
    {
        $users = User::where('role', 'user')
            ->withCount(['pets', 'appointments'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị chi tiết user
     */
    public function show($id)
    {
        $user = User::with(['pets', 'appointments.pet', 'appointments.services.category'])
            ->withCount(['pets', 'appointments'])
            ->findOrFail($id);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Xóa user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Không cho phép xóa admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Không thể xóa tài khoản admin!');
        }
        
        // Xóa user (cascade sẽ xóa pets và appointments)
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Đã xóa người dùng thành công!');
    }
}
