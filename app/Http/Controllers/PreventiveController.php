<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlVehicle;

class PreventiveController extends Controller
{
    public function index(Request $request)
    {
        // Get distinct vehicle types for the filter dropdown
        $vehicleTypes = FlVehicle::distinct()->pluck('type');

        // Apply filters conditionally using when() for cleaner chaining
        $vehicles = FlVehicle::query()
            ->when($request->filled('type'), fn($q) => $q->where('type', $request->input('type')))
            ->when($request->filled('registration_no'), fn($q) => $q->where('registration_no', 'like', '%' . $request->input('registration_no') . '%'))
            ->when($request->filled('model'), fn($q) => $q->where('model', 'like', '%' . $request->input('model') . '%'))
            ->get();

        return view('maintenance.vehicle.preventive', compact('vehicles', 'vehicleTypes'));
    }
}
