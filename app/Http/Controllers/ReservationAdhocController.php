<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlRequest;
use Illuminate\Support\Facades\DB;
use App\Models\FlPassenger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReservationAdhocController extends Controller
{
    public function index()
    {
        $types = ['SEND', 'SEND AND FETCH', 'FETCH', 'UNTIL FINISH'];
        $statuses = ['IN PROGRESS', 'APPROVED', 'COMPLETED'];

        return view('reservation-management.adhoc.history', compact('types', 'statuses'));
    }

    public function create()
    {
        $purpose = [
            'FYP' => 'Final Year Project',
            'RESEARCH' => 'Research',
            'MEETING' => 'Meeting',
            'VISIT' => 'Official Visit',
        ];

        $pickup_state = [
            'JHR' => 'Johor',
            'SGR' => 'Selangor',
            'KUL' => 'Kuala Lumpur',
            'PNG' => 'Penang'
        ];

        $booking_type = ['SEND', 'SEND AND FETCH', 'FETCH', 'UNTIL FINISH'];

        $vehicle_request = ['Bus', 'Van', 'Car'];

        // Example 1: Grouped by department ID and name
        $departments = FlRequest::select('dept_faculty')
            ->distinct()
            ->orderBy('dept_faculty')
            ->pluck('dept_faculty', 'dept_faculty'); // ['dept_faculty' => 'dept_faculty'] – replace with actual name if available

        return view('reservation-management.adhoc.create_request', compact(
            'departments', 'purpose', 'vehicle_request', 'booking_type', 'pickup_state'
        ));
    }



    public function store(Request $request)
    {
        dd($request->all());
        
        $validated = $request->validate([
            'purpose' => 'required|string',
            'vote_ptj' => 'required|string',
            'dept_faculty' => 'required|string',
            'officer_email' => 'required|email',
            'vehicle_request' => 'required|string',
            'program' => 'nullable|string',
            'booking_type' => 'required|string',
            'pickup_point' => 'required|string',
            'pickup_state' => 'required|string',
            'destination' => 'required|string',
            'destination_state' => 'required|string',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date',
            'end_time' => 'required',
            'remark' => 'nullable|string',
            'no_vehicle' => 'nullable|integer',
            'agree' => 'nullable|string', // should be boolean if you change frontend
        ]);

        $validated['user_id'] = Auth::id();
        $validated['reservation_date'] = now()->toDateString();
        $validated['status'] = 'PENDING';

        // Convert string "yes"/"no" to boolean if needed
        $validated['agree'] = ($request->agree === 'yes') ? 1 : 0;

        // Optional defaults
        $validated['attention_to'] = $request->attention_to ?? null;
        $validated['estimated_cost'] = null;
        $validated['distance'] = null;

        // Create the reservation
        $reservation = FlRequest::create($validated);

        return redirect()->route('reservation.history')->with('success', 'Reservation created successfully.');
    }

    public function search(Request $request)
    {
        dd($request->all()); 

        $query = DB::table('fl_wr')->select('*');

        if ($request->filled('req_id')) {
            $query->where('request_id', $request->req_id);
        }

        if ($request->filled('requestor_type')) {
            $query->where('user_type', $request->requestor_type);
        }

        if ($request->filled('date1') && $request->filled('date2')) {
            $query->whereBetween(DB::raw("TO_DATE(datetime_requested, 'YYYY-MM-DD')"), [$request->date1, $request->date2]);
        }

        if ($request->filled('date3') && $request->filled('date4')) {
            $query->whereBetween(DB::raw("TO_DATE(datetime_pickup, 'YYYY-MM-DD')"), [$request->date3, $request->date4]);
        }

        if ($request->filled('type')) {
            $query->where('booking_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $results = $query->get();

        return view('reservation-management.adhoc.result', compact('results'));
    }
}

