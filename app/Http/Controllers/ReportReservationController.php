<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use App\Models\FlRequest;
use App\Models\FlDriver;
use App\Models\DV;
use App\Models\WorkOrder;
use App\Models\FlVehicle;
use Carbon\Carbon;

class ReportReservationController extends Controller
{
    public function showForm()
    {
        return view('fleet.report.reservation_by_ptj');
    }

    public function export(Request $request)
    {
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');
        $dvId = $request->input('dv_id');
        $dvName = $request->input('dv_name');

        $results = DB::table('fl_wr')
            ->leftJoin('fl_wo', 'fl_wo.wo_id', '=', 'fl_wr.wo_id')
            ->leftJoin('fl_wrv', 'fl_wrv.wo_id', '=', 'fl_wo.wo_id')
            ->leftJoin('fl_vehicle', 'fl_vehicle.registration_no', '=', 'fl_wrv.registration_no')
            ->leftJoin('fl_charted', 'fl_charted.wo_id', '=', 'fl_wo.wo_id')
            ->leftJoin('fl_company', 'fl_company.com_id', '=', 'fl_charted.com_id')
            ->select([
                'fl_wr.request_id',
                'fl_wr.booking_site_id',
                'fl_wr.datetime_pickup',
                'fl_wr.datetime_fetch',
                'fl_wr.booking_assembly',
                'fl_wr.booking_destination',
                'fl_wr.booking_program',
                'fl_wr.vote_category',
                'fl_wr.actual_cost',
                DB::raw('LISTAGG(fl_wrv.registration_no, \', \') WITHIN GROUP (ORDER BY fl_wr.datetime_pickup) as vehicles'),
                'fl_wo.charted',
                DB::raw('UPPER(fl_company.name) as company_name'),
            ])
            ->where('fl_wr.vote_category', '=', 'U')
            ->where('fl_wr.ptj', '=', $dvId)
            ->whereIn('fl_wr.status', ['COMPLETED', 'APPROVED'])
            ->whereBetween(DB::raw("TO_DATE(TO_CHAR(fl_wr.datetime_pickup, 'YYYY-MM-DD'), 'YYYY-MM-DD')"), [$dateStart, $dateEnd])
            ->groupBy('fl_wr.request_id', 'fl_wr.booking_site_id', 'fl_wr.datetime_pickup', 'fl_wr.datetime_fetch', 'fl_wr.booking_assembly', 'fl_wr.booking_destination', 'fl_wr.booking_program', 'fl_wr.vote_category', 'fl_wr.actual_cost', 'fl_wo.charted', 'fl_company.name')
            ->get();

        // Generate Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['Request ID', 'Booking Site', 'Pickup', 'Fetch', 'Assembly', 'Destination', 'Program', 'Vote', 'Actual Cost', 'Vehicles', 'Charted', 'Company Name'];
        $sheet->fromArray($headers, NULL, 'A1');

        $row = 2;
        foreach ($results as $res) {
            $sheet->fromArray([
                $res->request_id,
                $res->booking_site_id,
                $res->datetime_pickup,
                $res->datetime_fetch,
                $res->booking_assembly,
                $res->booking_destination,
                $res->booking_program,
                $res->vote_category,
                $res->actual_cost,
                $res->vehicles,
                $res->charted,
                $res->company_name
            ], NULL, "A{$row}");
            $row++;
        }

        $writer = new Xls($spreadsheet);
        $filename = "reservation_by_ptj_{$dateStart}_to_{$dateEnd}.xls";

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function reservationByVehicleType(Request $request)
    {
        $year = $request->input('year', now()->year);
        $reportType = $request->input('report_type', 'LIST');

        $vehicleTypes = ['Car', 'Van', '4WD', 'MPV']; // Add more types if needed
        $reportData = [];

        // Initialize months
        foreach (range(1, 12) as $month) {
            $monthName = Carbon::create()->month($month)->format('F');
            $reportData[$monthName] = array_fill_keys($vehicleTypes, 0);
        }

        // Fetch grouped data
        $results = DB::table('fl_request')
            ->selectRaw('EXTRACT(MONTH FROM start_date) AS month, vehicle_request AS vehicle_request, COUNT(*) AS total')
            ->whereYear('start_date', $year)
            ->groupByRaw('EXTRACT(MONTH FROM start_date), vehicle_request')
            ->get();

        foreach ($results as $row) {
            $monthName = Carbon::create()->month((int) $row->month)->format('F'); // ✅ cast to int
            if (in_array($row->vehicle_request, $vehicleTypes)) {
                $reportData[$monthName][$row->vehicle_request] = $row->total;
            }
        }

        return view('reports.reservation_by_vehicle_request', [
            'reportData' => $reportData,
            'vehicleTypes' => $vehicleTypes,
            'selectedYear' => $year,
            'reportType' => $reportType,
        ]);
    }

    public function reservationCostBusOnly(Request $request)
    {
        $year = $request->input('year', now()->year);
        $reportType = $request->input('report_type', 'LIST');

        // Get list of departments (PTJ) with costs
        $ptjs = DB::table('fl_request')
            ->whereYear('start_date', $year)
            ->where('vehicle_request', 'Bus')
            ->pluck('vote_ptj')
            ->unique()
            ->values()
            ->all();

        // Initialize result structure
        $reportData = [];
        foreach ($ptjs as $vote_ptj) {
            foreach (range(1, 12) as $month) {
                $reportData[$vote_ptj][$month] = 0;
            }
        }

        // Fetch data
        $results = DB::table('fl_request')
        ->selectRaw('vote_ptj, EXTRACT(MONTH FROM start_date) as month, SUM(estimated_cost) as total')
        ->whereYear('start_date', $year)
        ->where('vehicle_request', 'Bus')
        ->groupByRaw('vote_ptj, EXTRACT(MONTH FROM start_date)')
        ->get();


        // Fill in data
        foreach ($results as $row) {
            $vote_ptj = $row->vote_ptj;
            $month = (int)$row->month;
            $reportData[$vote_ptj][$month] = $row->total;
        }

        return view('reports.reservation_cost_bus', [
            'reportData' => $reportData,
            'selectedYear' => $year,
            'reportType' => $reportType,
        ]);
    }

    public function listByPTJAllSite(Request $request)
    {
        $startDate = $request->input('date_start');
        $endDate = $request->input('date_end');
        $ptj = $request->input('ptj');

        // If you have a separate PTJ table, fetch from there
        // Otherwise, you can define it statically like this
        $ptjList = [
            'J01' => 'CANSELORI',
            'J02' => 'FAKULTI SAINS',
            'J03' => 'FAKULTI KEJURUTERAAN',
            'J04' => 'PUSAT PENGAJIAN ISLAM',
            // Add more based on your system...
        ];

        $query = FlRequest::query();

        if ($startDate && $endDate) {
            $query->whereBetween('start_date', [
                Carbon::createFromFormat('d-m-Y', $startDate)->startOfDay(),
                Carbon::createFromFormat('d-m-Y', $endDate)->endOfDay()
            ]);
        }

        if ($ptj) {
            $query->where('vote_ptj', $ptj);
        }

        $reservations = $query->orderBy('start_date')->get();

        return view('reports.list_by_ptj_all_site', [
            'reservations' => $reservations,
            'selectedStart' => $startDate,
            'selectedEnd' => $endDate,
            'selectedPTJ' => $ptj,
            'ptjList' => $ptjList 
        ]);
    }

    public function listReservationByPtjBus(Request $request)
    {
        $startDate = $request->input('date_start');
        $endDate = $request->input('date_end');
        $ptjCode = $request->input('ptj');
        $reportType = $request->input('report_type', 'LIST');

        $query = FlRequest::query()
            ->where('vehicle_request', 'Bus');

        if ($startDate && $endDate) {
            $query->whereBetween('start_date', [
                Carbon::createFromFormat('d-m-Y', $startDate)->startOfDay(),
                Carbon::createFromFormat('d-m-Y', $endDate)->endOfDay()
            ]);
        }

        if ($ptjCode) {
            $query->where('vote_ptj', $ptjCode);
        }

        $results = $query->with('passengers')->orderBy('start_date')->get();

        $ptjList = DV::whereNotIn('dv_id', ['J25','K44','J44','J43','J42','J38','J39','J40','J35','J36','J15','K15'])
                    ->where('m_st', 1)
                    ->orderBy('name')
                    ->pluck('name', 'dv_id');

        return view('reports.list_by_ptj_bus', compact('results', 'ptjList', 'startDate', 'endDate', 'ptjCode', 'reportType'));
    }

    public function driverMonthlyTrip(Request $request)
    {
        $year = $request->input('year', now()->year);
        $reportType = $request->input('report_type', 'LIST');

        $reportData = [];

        // Get all drivers
        $drivers = FlDriver::with('userByEmId')->get();

        foreach ($drivers as $driver) {
            echo $driver->name . ' | ' . ($driver->userByEmId->name ?? 'No User');
        }


        foreach ($drivers as $driver) {
            foreach (range(1, 12) as $month) {
                $reportData[$driver->em_id]['name'] = $driver->name;
                $reportData[$driver->em_id]['monthly'][$month] = 0;
            }
        }

        $results = DB::table('fl_request')
            ->join('fl_driver', 'fl_request.driver_id', '=', 'fl_driver.driver_id')
            ->selectRaw('fl_driver.em_id, EXTRACT(MONTH FROM start_date) as month, COUNT(*) as total')
            ->whereYear('start_date', $year)
            ->groupBy('fl_driver.em_id', DB::raw('EXTRACT(MONTH FROM start_date)'))
            ->get();

        foreach ($results as $row) {
            $reportData[$row->em_id]['monthly'][(int)$row->month] = $row->total;
        }

        return view('reports.driver_monthly_trip', [
            'reportData' => $reportData,
            'selectedYear' => $year,
            'reportType' => $reportType,
        ]);
    }

    public function vehicleMonthlyUsage(Request $request)
    {
        $year = $request->input('year', now()->year);
        $vehicleType = $request->input('vehicle_request', 'CAR');
        $reportType = $request->input('report_type', 'LIST');

        $vehicles = DB::table('fl_vehicle')
            ->where('vehicle_request', $vehicleType)
            ->pluck('no_vehicle');

        $reportData = [];

        foreach ($vehicles as $regNo) {
            $reportData[$regNo] = array_fill_keys(range(1, 12), 0);
        }

        $results = DB::table('fl_request')
            ->join('fl_vehicle', 'fl_request.vehicle_id', '=', 'fl_vehicle.vehicle_id')
            ->select(
                'fl_vehicle.no_vehicle',
                DB::raw('EXTRACT(MONTH FROM fl_request.start_date) as month'),
                DB::raw('SUM(fl_request.distance) as total_km')
            )
            ->whereYear('fl_request.start_date', 2025)
            ->where('fl_request.vehicle_request', 'CAR')
            ->groupBy('fl_vehicle.no_vehicle', DB::raw('EXTRACT(MONTH FROM fl_request.start_date)'))
            ->get();

        foreach ($results as $row) {
            $regNo = $row->no_vehicle;
            $month = (int) $row->month;
            $reportData[$regNo][$month] = $row->total_km ?? 0;
        }

        return view('reports.vehicle_monthly_usage', [
            'reportData' => $reportData,
            'selectedYear' => $year,
            'vehicleType' => $vehicleType,
            'reportType' => $reportType,
        ]);
    }

    public function monthlyChartedByPtj(Request $request)
    {
        $year = $request->input('s_year', now()->year);

        $results = DB::table('fl_request')
            ->select(
                'vote_ptj',
                DB::raw('EXTRACT(MONTH FROM start_date) AS month'),
                DB::raw('COUNT(*) as total_trips'),
                DB::raw('SUM(estimated_cost) as total_cost') // replace if needed
            )
            ->whereYear('start_date', $year)
            ->groupBy('vote_ptj', DB::raw('EXTRACT(MONTH FROM start_date)'))
            ->orderBy('vote_ptj')
            ->orderBy(DB::raw('EXTRACT(MONTH FROM start_date)'))
            ->get();

        $grouped = $results->groupBy('vote_ptj');

        return view('reports.monthly_charted_by_ptj', [
            'year' => $year,
            'groupedData' => $grouped,
        ]);
    }

    // public function driverTripView(Request $request)
    // {
    //     $sites = Site::orderBy('name')->get();
    //     return view('reports.driver_trip', [
    //         'sites' => $sites,
    //         'today' => now()->format('d-m-Y')
    //     ]);
    // }

    public function driverTripAjax(Request $request)
    {
        $date = Carbon::createFromFormat('d-m-Y', $request->input('date'))->format('Y-m-d');
        $siteId = $request->input('site_id');

        $trips = FlRequest::with(['driver']) // define relation if needed
            ->where('site_id', $siteId)
            ->whereDate('start_date', $date)
            ->get();

        return view('reports.partials.driver_trip_result', compact('trips'));
    }

    public function workOrderDetails()
    {
        $vehicle_types = ['CAR', 'VAN', 'BUS', '4WD']; // or pull from DB
        $dv_list = DV::where('dv_id', 'like', 'J%')->orderBy('name')->get(); // or 'K%' depending on site_id
        return view('reports.work_order_details', compact('vehicle_types', 'dv_list'));
    }

    // public function ajaxWorkOrderDetails(Request $request)
    // {
    //     // Process your query here and return HTML view via AJAX
    //     return view('reports.partials.work_order_result', [ 'data' => $yourFilteredData ]);
    // }

    public function workOrderCharted()
    {
        $vehicle_types = ['CAR', 'VAN', 'BUS', '4WD']; // replace with dynamic if needed
        $dv_list = DV::where('dv_id', 'like', 'J%')->orderBy('name')->get(); // or adjust by session site_id

        return view('reports.work_order_charted', compact('vehicle_types', 'dv_list'));
    }

    public function ajaxWorkOrderCharted(Request $request)
    {
        $data = WorkOrder::filterCharted($request)->get(); // create a scope for your filtering logic

        return view('reports.partials.work_order_charted_result', compact('data'));
    }

    public function chartedTripReport()
{
    $sites = Site::orderBy('name')->get();
    $today = now()->format('d-m-Y');

    return view('reports.charted_trip', compact('sites', 'today'));
}

    public function ajaxChartedTrip(Request $request)
    {
        $date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Ymd');
        $siteId = $request->site_id;

        $data = ChartedTrip::whereDate('travel_date', '=', $date)
            ->where('site_id', $siteId)
            ->get();

        return view('reports.partials.charted_trip_result', compact('data'));
    }

    public function monthlyVehicleCost()
    {
        $vehicleTypes = FlVehicle::distinct()->pluck('type'); // Or manually define if static
        return view('reports.maintenance.monthly_vehicle_maintenance_cost', compact('vehicleTypes'));
    }

    public function monthlyVehicleCostAjax(Request $request)
    {
        // Logic to fetch and return the table HTML based on filters
        // You can also return a partial view here
        return view('reports.maintenance.partials.monthly_cost_result', [
            'data' => [] // Example placeholder
        ]);
    }

    public function monthlyComplaintGraph()
    {
        return view('reports.maintenance.number_monthly_complaints');
    }

    public function monthlyComplaintGraphAjax(Request $request)
    {
        $year = $request->input('s_year');

        // Example result: data from your complaint table, grouped by month
        $data = FlComplaint::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Return a partial view or HTML table/chart directly
        return view('reports.maintenance.partials.monthly_complaint_graph', compact('data', 'year'));
    }

}

