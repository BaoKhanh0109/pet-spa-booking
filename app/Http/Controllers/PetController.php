<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function index() {
        $pets = Pet::where('userID', Auth::user()->userID)->get();
        return view('pets.index', compact('pets'));
    }

    public function create() {
        return view('pets.create');
    }

    public function store(Request $request) {
        $request->validate([
            'petName' => 'required',
            'species' => 'required',
            'petImage' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['userID'] = Auth::user()->userID;

        if ($request->hasFile('petImage')) {
            $path = $request->file('petImage')->store('pets', 'public');
            $data['petImage'] = $path;
        }

        Pet::create($data);
        return redirect()->route('pets.index')->with('success', 'Thêm Boss thành công!');
    }

    public function edit($id) {
        $pet = Pet::find($id);

        if (!$pet || $pet->userID != Auth::user()->userID) {
            abort(403, 'Bạn không có quyền sửa thú cưng này!');
        }

        return view('pets.edit', compact('pet'));
    }

    public function update(Request $request, $id) {
        $pet = Pet::find($id);

        // Bảo mật
        if (!$pet || $pet->userID != Auth::user()->userID) {
            abort(403);
        }

        $data = $request->except(['petImage']);

        if ($request->hasFile('petImage')) {
            if ($pet->petImage) {
                Storage::disk('public')->delete($pet->petImage);
            }
            $data['petImage'] = $request->file('petImage')->store('pets', 'public');
        }

        $pet->update($data);
        return redirect()->route('pets.index')->with('success', 'Cập nhật thông tin thành công!');
    }

    public function destroy($id) {
        $pet = Pet::find($id);

        if (!$pet || $pet->userID != Auth::user()->userID) {
            abort(403);
        }

        // Kiểm tra xem thú cưng có lịch hẹn nào không
        $appointmentCount = $pet->appointments()->count();
        
        if ($appointmentCount > 0) {
            return redirect()->route('pets.index')
                ->with('error', "Không thể xóa {$pet->petName}! Thú cưng này đang có {$appointmentCount} lịch hẹn. Vui lòng hủy các lịch hẹn trước khi xóa.");
        }

        if ($pet->petImage) {
            Storage::disk('public')->delete($pet->petImage);
        }

        $pet->delete();
        return redirect()->route('pets.index')->with('success', 'Đã xóa thú cưng!');
    }
}