<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\flAllocation;
use Illuminate\Support\Facades\DB;

class AllocationController extends Controller
{
    public function index()
    {
        $allocations = FlAllocation::orderBy('altn_date', 'desc')->get();
        return view('fleet.allocation.index', compact('allocations'));
    }

    public function create()
    {
        $phbs = ['UTM JOHOR BAHRU', 'UTM KUALA LUMPUR'];
        $categories = ['BUDGET OF UNIVERSITY'];
        return view('fleet.allocation.create', compact('phbs', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'altn_year' => 'required|digits:4',
            'altn_phb' => 'required|string',
            'altn_category' => 'required|string',
            'altn_allocation' => 'required|numeric',
            'altn_date' => 'required|date',
        ]);

        FlAllocation::create($request->all());

        return redirect()->route('allocation.index')->with('success', 'Allocation created successfully.');
    }

    public function history(Request $request)
    {
        // Static list for demo (replace with DB query if needed)
        $phbs = [
            'UTM JOHOR BAHRU',
            'UTM KUALA LUMPUR',
        ];

        $years = range(now()->year, 2010);

        // Query with filters
        $query = DB::table('fl_allocation');

        if ($request->filled('year')) {
            $query->whereYear('altn_date', $request->year);
        }

        if ($request->filled('phb')) {
            $query->where('altn_phb', $request->phb);
        }

        $allocations = $query->orderByDesc('altn_date')->paginate(10);

        return view('fleet.allocation.history', compact('allocations', 'years', 'phbs'));
    }

}
