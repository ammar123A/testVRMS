<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlCf;

class TechnicianController extends Controller
{
    public function index(Request $request)
    {
        $query = FlCf::query();

        if ($request->filled('em_id')) {
            $query->where('em_id', 'like', '%' . $request->em_id . '%');
        }

        if ($request->filled('em_number')) {
            $query->where('em_number', 'like', '%' . $request->em_number . '%');
        }

        $technicians = $query->orderBy('updated_at', 'desc')->paginate(10);

        return view('supervisor.technician.technician', compact('technicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'em_id' => 'required|string|unique:fl_cf,em_id|max:20',
            'em_number' => 'nullable|string|max:100',
            'gred' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:100',
            'dv_name' => 'nullable|string|max:100',
            'site_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'user_name' => 'nullable|string|max:50',
        ]);

        FlCf::create($validated);

        return redirect()->route('technician.index')->with('success', 'Technician registered successfully.');
    }
}
