<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlLeave;
use App\Models\FlDriver;

class LeaveController extends Controller
{
    public function index(Request $request)
{
    $query = FlLeave::with('driver');

    if ($request->em_id) {
        $query->where('em_id', 'like', '%' . $request->em_id . '%');
    }

    if ($request->name) {
        $query->whereHas('driver', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->name . '%');
        });
    }

    if ($request->date_1 && $request->date_2) {
        $query->whereBetween('start_date', [$request->date_1, $request->date_2]);
    }

    $leaves = $query->orderBy('start_date', 'desc')->paginate(10);
    $drivers = FlDriver::all();

    return view('supervisor.leave.leave', compact('leaves', 'drivers'));
}

public function store(Request $request)
{
    $request->validate([
        'em_id' => 'required|exists:fl_driver,em_id',
        'name' => 'nullable|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|string|max:255',
    ]);

    FlLeave::create($request->only('em_id', 'name', 'start_date', 'end_date', 'reason'));

    return redirect()->route('leave.index')->with('success', 'Leave registered successfully.');
}

}
