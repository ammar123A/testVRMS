<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlVehicle;
use App\Models\DV;

class VehicleController extends Controller
{
    public function index()
    {
        $departments = [];
        $vehicles = FlVehicle::orderBy('created_at', 'desc')->get();

        return view('supervisor.vehicle.vehicle', compact('departments', 'vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:255|unique:fl_vehicle,plate_number',
            'model' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'department' => 'required|string|max:50',
            'registration_date' => 'required|date',
        ]);

        FlVehicle::create([
            'plate_number' => strtoupper($request->plate_number),
            'model' => strtoupper($request->model),
            'type' => strtoupper($request->type),
            'status' => 'ACTIVE',
            'registration_date' => $request->registration_date,
            'department' => $request->department,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('vehicle.index')->with('success', 'Vehicle registered successfully.');
    }
}
