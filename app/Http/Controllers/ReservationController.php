<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlRequest;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    
    public function history(Request $request)
    {
        $query = FlRequest::with('user');

        // Filtering (optional)
        if ($request->filled('req_id')) {
            $query->where('request_id', $request->input('req_id'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('reservation_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->orderBy('created_at', 'desc')->get();

        return view('reservation.history', compact('reservations'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purpose' => 'required|string',
            'attention_to' => 'required|string',
            'vote_ptj' => 'required|string',
            'dept_faculty' => 'required|string',
            'officer_email' => 'required|email',
            'vehicle_request' => 'required|string',
            'no_vehicle' => 'required|integer',
            'program' => 'required|string',
            'booking_type' => 'required|string',
            'pickup_point' => 'required|string',
            'pickup_state' => 'required|string',
            'destination' => 'required|string',
            'destination_state' => 'required|string',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date',
            'end_time' => 'required',
            'agree' => 'required|boolean',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['reservation_date'] = now();
        $validated['status'] = 'PENDING';

        FlRequest::create($validated);

        return redirect()->back()->with('success', 'Reservation saved successfully.');
    }

}

