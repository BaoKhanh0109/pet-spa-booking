<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Pet;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
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
            'status' => 'Pending'
        ]);

        return redirect()->route('booking.history')->with('success', 'Đặt lịch thành công! Nhân viên sẽ sớm liên hệ.');
    }
    public function history() {
        $appointments = Appointment::where('userID', Auth::user()->userID)
                        ->with(['pet', 'service']) 
                        ->orderBy('appointmentDate', 'asc')
                        ->get();

        return view('bookings.history', compact('appointments'));
    }
}
