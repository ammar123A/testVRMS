<?php

namespace App\Http\Controllers;
use App\Models\FlVehicle;
use Illuminate\Http\Request;

class PreventiveController extends Controller
{
    public function index(Request $request)
    {
        $vehicleTypes = FlVehicle::select('type')->distinct()->pluck('type');

        $query = FlVehicle::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('registration_no')) {
            $query->where('registration_no', 'like', '%' . $request->registration_no . '%');
        }

        if ($request->filled('model')) {
            $query->where('model', 'like', '%' . $request->model . '%');
        }

        $vehicles = $query->get();

        return view('maintenance.vehicle.preventive', compact('vehicles', 'vehicleTypes'));
    }
}
