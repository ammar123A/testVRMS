<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlRequest;
use Illuminate\Support\Facades\DB;

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
        $purpose = [];
        $pickup_state = [];
        $booking_type = [];
        $vehicle_request = [];
        $departments = FlRequest::orderBy('created_at', 'desc')->get();

        return view('reservation-management.adhoc.create_request', compact('departments', 'purpose', 'vehicle_request', 'booking_type', 'pickup_state'));
    }

    public function search(Request $request)
    {
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

