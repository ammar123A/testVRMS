<?php

namespace App\Http\Controllers;

use App\Models\FlComplaint;
use App\Models\FlVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FlCf;
use App\Models\DV;

class ComplaintController extends Controller
{
    public function create()
    {
        $vehicles = FlVehicle::pluck('plate_number', 'vehicle_id');
        return view('complaint.form', compact('vehicles'));
    }

    public function vehicleDetails($id)
    {
        $vehicle = FlVehicle::findOrFail($id);
        return response()->json($vehicle);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:fl_vehicle,vehicle_id',
            'odometer' => 'nullable|numeric',
            'fuel' => 'nullable|numeric|min:0|max:100',
            'complaint' => 'nullable|string',
        ]);

        $vehicle = FlVehicle::find($request->vehicle_id);

        FlComplaint::create([
            'vehicle_id' => $vehicle->vehicle_id,
            'plate_number' => $vehicle->plate_number,
            'type' => $vehicle->type,
            'model' => $vehicle->model,
            'chassis_no' => $vehicle->chassis_no,
            'engine_no' => $vehicle->engine_no,
            'colour' => $vehicle->colour,
            'odometer' => $request->odometer,
            'fuel' => $request->fuel,
            'road_tax_expiry' => $vehicle->road_tax_expiry,
            'puspakom_expiry' => $vehicle->puspakom_expiry,
            'permit_expiry' => $vehicle->permit_expiry,
            'complaint' => $request->complaint,
        ]);

        return redirect()->route('complaint.history')->with('success', 'Complaint submitted successfully.');
    }

    public function history()
    {
        $vehicles = FlVehicle::where('em_id', auth()->user()->em_id)
            ->orderBy('plate_number')
            ->pluck('plate_number', 'vehicle_id');

        return view('complaint.history', compact('vehicles'));
    }

    public function historySearch(Request $request)
    {
        $query = FlComplaint::query()->orderBy('created_at', 'desc');

        if ($request->filled('req_id')) {
            $query->where('id', $request->req_id);
        }

        if ($request->filled('date_1')) {
            $query->whereDate('created_at', '>=', $request->date_1);
        }

        if ($request->filled('date_2')) {
            $query->whereDate('created_at', '<=', $request->date_2);
        }

        if ($request->filled('registration_no')) {
            $query->where('plate_number', $request->registration_no);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->get();

        return view('complaint.partials.history-table', compact('complaints'));
    }

    public function verifyHistory(Request $request)
    {
        $vehicles = FlVehicle::pluck('plate_number');

        $query = FlComplaint::with('user');

        if ($request->filled('req_id')) {
            $query->where('id', $request->req_id);
        }
        if ($request->filled('em_id')) {
            $query->where('em_id', $request->em_id);
        }
        if ($request->filled('em_number')) {
            $query->whereHas('user', fn($q) => $q->where('em_number', $request->em_number));
        }
        if ($request->filled('registration_no')) {
            $query->where('plate_number', $request->registration_no);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_1')) {
            $query->whereDate('created_at', '>=', $request->date_1);
        }
        if ($request->filled('date_2')) {
            $query->whereDate('created_at', '<=', $request->date_2);
        }

        $complaints = $query->latest()->get();

        return view('maintenance.verify-r.history', compact('vehicles', 'complaints'));
    }

    
    public function verifyWrHistory()
    {
        // Sample: You may replace with actual queries
        $vehicleTypes = FlVehicle::distinct()->pluck('type');
        $statuses = ['REQUESTED', 'APPROVED', 'REJECTED', 'IN PROGRESS', 'COMPLETED'];

        $departments = DV::pluck('name', 'dv_id'); // Adjust to your actual model/table
        $technicians = FlCf::pluck('em_number', 'em_id'); // Adjust to your actual model/table

        return view('maintenance.verify-wr.history', compact('vehicleTypes', 'statuses', 'departments', 'technicians'));
    }

}


