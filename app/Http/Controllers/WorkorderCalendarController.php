<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class WorkorderCalendarController extends Controller
{
    public function index()
    {
        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');
        return view('reservation-management.workorder.index', compact('currentMonth', 'currentYear'));
    }

    public function loadCalendar(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        return view('partials.calendar_grid', compact('month', 'year', 'daysInMonth'))->render();
    }
}
