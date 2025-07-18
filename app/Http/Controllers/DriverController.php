<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlDriver;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $query = FlDriver::query();

        if ($request->em_id) {
            $query->where('em_id', 'like', '%' . $request->em_id . '%');
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $drivers = $query->orderBy('updated_at', 'desc')->paginate(10);

        return view('supervisor.driver.driver', compact('drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'em_id' => 'required|string|max:20|',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'license_number' => 'nullable|string|max:20',
            'ic_number' => 'nullable|string|max:20',
        ]);

        FlDriver::create($request->all());

        return redirect()->route('driver.index')->with('success', 'Driver registered successfully.');
    }

}
