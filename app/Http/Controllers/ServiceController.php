<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index(Request $request) {
        $query = Service::query();

        if ($request->has('search')) {
            $keyword = $request->search;
            $query->where('serviceName', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%");
        }

        $services = $query->get();

        return view('client.services', compact('services'));
    }
    
    public function show($id) {
        $service = Service::findOrFail($id);
        return view('client.service_detail', compact('service'));
    }
}
?>