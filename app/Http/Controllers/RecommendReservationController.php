<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlRequest;
use Carbon\Carbon;

class RecommendReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = FlRequest::query();

        // Filters
        if ($request->filled('req_id')) {
            $query->where('request_id', $request->req_id);
        }

        if ($request->filled('wr_id')) {
            $query->where('wr_id', $request->wr_id);
        }

        if ($request->filled('requestor_type')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('user_type', $request->requestor_type);
            });
        }

        if ($request->filled('type')) {
            $query->where('booking_type', $request->type);
        }

        if ($request->filled('date_1') && $request->filled('date_2')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->date_1)->startOfDay(),
                Carbon::parse($request->date_2)->endOfDay()
            ]);
        }

        if ($request->filled('date_3') && $request->filled('date_4')) {
            $query->whereBetween('start_date', [
                $request->date_3,
                $request->date_4
            ]);
        }

        $requests = $query->orderByDesc('created_at')->paginate(10);

        return view('fleet.recommend.index', [
            'requests' => $requests,
            'request_types' => ['STAFF', 'STUDENT'],
            'booking_types' => ['SEND', 'FETCH', 'SEND AND FETCH'], // adjust based on existing data
        ]);
    }

    public function history(Request $request)
    {
        $query = FlRequest::query();

        if ($request->filled('req_id')) {
            $query->where('request_id', $request->req_id);
        }

        if ($request->filled('wr_id')) {
            $query->where('wr_id', $request->wr_id);
        }

        if ($request->filled('date_1') && $request->filled('date_2')) {
            $query->whereBetween('created_at', [
                Carbon::createFromFormat('d-m-Y', $request->date_1)->startOfDay(),
                Carbon::createFromFormat('d-m-Y', $request->date_2)->endOfDay()
            ]);
        }

        if ($request->filled('date_3') && $request->filled('date_4')) {
            $query->whereBetween('start_date', [
                Carbon::createFromFormat('d-m-Y', $request->date_3),
                Carbon::createFromFormat('d-m-Y', $request->date_4)
            ]);
        }

        if ($request->filled('type')) {
            $query->where('booking_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(10);

        $types = ['SEND', 'FETCH', 'SEND AND FETCH', 'UNTIL FINISH'];
        $statuses = ['RECOMMENDED', 'NOT RECOMMENDED', 'CONFIRM'];

        return view('fleet.recommend.history', compact('requests', 'types', 'statuses'));
    }
}

