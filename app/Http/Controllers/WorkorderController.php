<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkOrder;
use Carbon\Carbon;

class WorkorderController extends Controller
{
     public function index(Request $request)
    {
        $query = WorkOrder::query();

        // Filtering
        if ($request->filled('request_id')) {
            $query->where('request_id', $request->request_id);
        }

        if ($request->filled('wr_id')) {
            $query->where('wr_id', $request->wr_id);
        }

        if ($request->filled('wo_id')) {
            $query->where('wo_id', $request->wo_id);
        }

        if ($request->filled('date_3') && $request->filled('date_4')) {
            try {
                $start = Carbon::createFromFormat('Y-m-d', $request->date_3)->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', $request->date_4)->endOfDay();
                $query->whereBetween('date_send', [$start, $end]);
            } catch (\Exception $e) {
                // ignore invalid date
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('charted') && in_array($request->charted, ['0', '1'])) {
            $query->where('charted', $request->charted);
        }

        // Sorting and pagination
        $workorders = $query->orderByDesc('date_time_assigned')->paginate(10);

        // Pass filter options
        $statuses = ['REQUESTED', 'RECOMMENDED', 'APPROVED', 'COMPLETED'];

        return view('reservation-management.workorder.history', compact('workorders', 'statuses'));

    }


public function fetch(Request $request)
{
    $query = WorkOrder::with(['drivers', 'company']);

    if ($request->filled('request_id')) {
        $query->where('request_id', $request->request_id);
    }
    if ($request->filled('wr_id')) {
        $query->where('wr_id', $request->wr_id);
    }
    if ($request->filled('wo_id')) {
        $query->where('wo_id', $request->wo_id);
    }
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->charted != -1) {
        $query->where('charted', $request->charted);
    }
    if ($request->filled('date_3') && $request->filled('date_4')) {
        $query->whereBetween('date_send', [$request->date_3, $request->date_4]);
    }

    $workorders = $query->paginate(10);

    return view('partials.table', compact('workorders'));
}
}
