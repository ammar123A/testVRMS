<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\RecommendReservationController;
use App\Http\Controllers\ReportReservationController;
use App\Http\Controllers\ReservationAdhocController;
use App\Http\Controllers\WorkorderController;
use App\Http\Controllers\WorkorderCalendarController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\WorkshopController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\PreventiveController;
use App\Http\Controllers\ReportController;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.custom');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/main', function () {
    return view('main'); // Or your post-login home
})->middleware('auth');

Route::get('/profile', function () {
    return view('profile');
})->name('profile')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'update'])->name('password.update');
});

Route::get('/create-request', function () {
    return view('reservation/create-request');
});

// Route::get('/allocation', function () {
//     return view('fleet/allocation/create');
// });


// Route in web.php
Route::post('/reservation/calculate-cost', [ReservationController::class, 'calculateCost'])->name('reservation.calculate-cost');
Route::post('/reservation/booking-details', [ReservationController::class, 'saveBookingDetails'])->name('reservation.booking-details.save');
Route::post('/reservation/store', [ReservationController::class, 'store'])->name('reservation.store');
Route::get('/history', [ReservationController::class, 'history'])->name('reservation.history');
// Route::post('/reservation/save', [ReservationController::class, 'savePassenger'])->name('reservation.passenger.save');

Route::prefix('/allocation')->name('allocation.')->group(function () {
    Route::get('/', [AllocationController::class, 'index'])->name('index');
    Route::get('/create', [AllocationController::class, 'create'])->name('create');
    Route::post('/', [AllocationController::class, 'store'])->name('store');
});

Route::get('/allocation/history', [AllocationController::class, ('history')])->name('allocation.history');
Route::get('/fleet/allocation/{id}/edit', [AllocationController::class, 'edit'])->name('allocation.edit');

Route::get('/allocation/recommend', [RecommendReservationController::class, 'index'])->name('recommend.index');
Route::get('/allocation/recommend/history', [RecommendReservationController::class, 'history'])->name('fleet.history');

Route::get('/reservation-management/history', [ReservationAdhocController::class, 'index'])->name('reservation.history');
Route::get('/reservation-management/history/search', [ReservationAdhocController::class, 'search'])->name('reservation.history.search');


Route::get('/allocation/report/reservations-by-ptj', [ReportReservationController::class, 'showForm'])->name('reports.ptj.form');
Route::get('/reports/reservations-by-ptj/export', [ReportReservationController::class, 'export'])->name('reports.ptj.export');

Route::get('/workorder/calendar', [WorkorderCalendarController::class, 'index'])->name('workorder.calendar');
Route::post('/workorder/calendar/load', [WorkorderCalendarController::class, 'loadCalendar'])->name('workorder.calendar.load');
Route::get('/workorder/history', [WorkOrderController::class, 'index'])->name('workorder.history');
Route::get('/workorder/ajax', [WorkOrderController::class, 'fetch'])->name('workorder.ajax');

// Route::get('report/resercation_by_vehicle_type', [ReportReservationController::class, 'report'])->name('');
Route::get('/reports/reservation-by-vehicle-type', [ReportReservationController::class, 'reservationByVehicleType'])->name('reports.vehicle-type');
Route::get('/reports/reservation-cost-bus', [ReportReservationController::class, 'reservationCostBusOnly'])->name('reports.reservation-cost-bus');
Route::get('/reports/list-by-ptj-all-site', [ReportReservationController::class, 'listByPTJAllSite'])->name('reports.list_by_ptj_all_site');
Route::get('/reports/list-by-ptj-bus', [ReportReservationController::class, 'listReservationByPtjBus'])->name('reports.list-by-ptj-bus');
Route::get('/reports/driver-monthly-trip', [ReportReservationController::class, 'driverMonthlyTrip'])->name('reports.driverMonthlyTrip');
Route::get('/reports/vehicle-monthly-usage', [ReportReservationController::class, 'vehicleMonthlyUsage'])->name('reports.vehicle_monthly_usage');
Route::get('/reports/monthly-chart', [ReportReservationController::class, 'monthlyChartedByPtj'])->name('reports.monthly_charted_by_ptj');
Route::get('/reports/work-order-details', [ReportReservationController::class, 'workOrderDetails'])->name('reports.work_order_details');
// Route::post('/reports/work-order-details/ajax', [ReportController::class, 'ajaxWorkOrderDetails'])->name('reports.work_order_details.ajax');
Route::get('/reports/work-order-charted', [ReportReservationController::class, 'workOrderCharted'])->name('reports.work_order_charted');
Route::post('/reports/work-order-charted/ajax', [ReportReservationController::class, 'ajaxWorkOrderCharted'])->name('reports.work_order_charted.ajax');
Route::get('/reports/charted-trip', [ReportReservationController::class, 'chartedTripReport'])->name('reports.charted_trip');
Route::post('/reports/charted-trip/ajax', [ReportReservationController::class, 'ajaxChartedTrip'])->name('reports.charted_trip.ajax');

// Route::get('/supervisor/vehicle', [VehicleController::class, 'index'])->name('vehicle.index');
// Route::get('/supervisor/vehicle/search', [VehicleController::class, 'search'])->name('vehicle.search');
// Route::get('/vehicle/register', [VehicleController::class, 'create'])->name('vehicle.register');

Route::get('/supervisor/vehicle', [VehicleController::class, 'index'])->name('vehicle.index');
Route::post('/supervisor/vehicle/store', [VehicleController::class, 'store'])->name('vehicle.store');

Route::get('/supervisor/driver', [DriverController::class, 'index'])->name('driver.index');
Route::post('/supervisor/driver/store', [DriverController::class, 'store'])->name('driver.store');

Route::get('/supervisor/leave', [LeaveController::class, 'index'])->name('leave.index');
Route::post('/supervisor/leave/store', [LeaveController::class, 'store'])->name('leave.store');

Route::get('/supervisor/permission', [PermissionController::class, 'index'])->name('permission.index');
Route::post('/supervisor/permission/store', [PermissionController::class, 'store'])->name('permission.store');

Route::get('/supervisor/company', [CompanyController::class, 'index'])->name('company.index');
Route::post('/supervisor/company/store', [CompanyController::class, 'store'])->name('company.store');

Route::get('/supervisor/technician', [TechnicianController::class, 'index'])->name('technician.index');
Route::post('/supervisor/technician/store', [TechnicianController::class, 'store'])->name('technician.store');

Route::get('supervisor/workshops', [WorkshopController::class, 'index'])->name('workshop.index');
Route::post('supervisor/workshops/store', [WorkshopController::class, 'store'])->name('workshop.store');
Route::put('/workshop/{id}', [WorkshopController::class, 'update'])->name('workshop.update');
Route::delete('/workshop/{id}', [WorkshopController::class, 'destroy'])->name('workshop.destroy');

Route::get('supervisor/parts', [PartController::class, 'index'])->name('parts.index');
Route::post('supervisor/parts/store', [PartController::class, 'store'])->name('parts.store');

Route::get('/complaint/create', [ComplaintController::class, 'create'])->name('complaint.create');
Route::post('/complaint/store', [ComplaintController::class, 'store'])->name('complaint.store');
Route::get('/complaint/history', [ComplaintController::class, 'history'])->name('complaint.history');
Route::get('/vehicle/details/{id}', [ComplaintController::class, 'vehicleDetails'])->name('vehicle.details');
Route::get('/complaint/history/search', [ComplaintController::class, 'histroysearch'])->name('complaint.history.search');

Route::get('/maintenance/verify-r/history', [ComplaintController::class, 'verifyHistory'])->name('maintenance.verify.history');
Route::get('/maintenance/verify-wr/history', [ComplaintController::class, 'verifyWrHistory'])->name('maintenance.verifywr.history');
Route::get('/maintenance/verify-wr/search', [ComplaintController::class, 'verifyWrSearch'])->name('complaints.verifywr.search');
Route::get('/maintenance/vehicle/preventive', [PreventiveController::class, 'index'])->name('preventive.index');

Route::get('/reports/maintenance/monthly-vehicle-cost', [ReportReservationController::class, 'monthlyVehicleCost'])->name('reports.maintenance.monthlyVehicleCost');
Route::post('/reports/maintenance/monthly-vehicle-cost/ajax', [ReportReservationController::class, 'monthlyVehicleCostAjax'])->name('reports.maintenance.monthlyVehicleCostAjax');

Route::get('/reports/maintenance/monthly-complaints', [ReportReservationController::class, 'monthlyComplaintGraph'])->name('reports.maintenance.monthlyComplaintGraph');
Route::post('/reports/maintenance/monthly-complaints/ajax', [ReportReservationController::class, 'monthlyComplaintGraphAjax'])->name('reports.maintenance.monthlyComplaintGraphAjax');

// Route::get('/dashboard', [DashboardController::class, 'index'])->name('main');

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::post('/process_login.php', function () {
//     return view('process_login');
// });
