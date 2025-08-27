<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\FlRequest;
use App\Models\FlVehicle;

class ReservationController extends Controller
{
    /**
     * Show the create reservation form with dynamic options.
     */
    public function create()
    {
        // Vehicle types from vehicles table (distinct, non-null)
        $vehicleTypes = FlVehicle::query()
            ->whereNotNull('type')
            ->where('type', '<>', '')
            ->distinct()
            ->pluck('type')
            ->sort()
            ->values();

        // Example “reference” lookups.
        // If you already have tables for these (e.g., campuses, vote_ptj, booking_types, states),
        // swap these queries in. Otherwise these fall back to config arrays.
        $campuses     = collect(config('vrms.campuses', ['UTM JOHOR BAHRU', 'UTM KUALA LUMPUR']))->values();
        $votePtj      = collect(config('vrms.vote_ptj', ['BUDGET OF UNIVERSITY', 'FACULTY VOTE']))->values();
        $bookingTypes = collect(config('vrms.booking_types', ['SEND AND FETCH','SEND ONLY','FETCH ONLY']))->values();
        $states       = collect(config('vrms.states', ['JOHOR','KEDAH','KELANTAN','MELAKA','NEGERI SEMBILAN','PAHANG','PERAK','PERLIS','PULAU PINANG','SABAH','SARAWAK','SELANGOR','TERENGGANU','KUALA LUMPUR','PUTRAJAYA','LABUAN']))->values();

        return view('reservation.create-request', compact('vehicleTypes', 'campuses', 'votePtj', 'bookingTypes', 'states'));
    }

    /**
     * Store the reservation (validated, no hardcoded sets).
     */
    public function store(Request $request)
    {
        // Pull dynamic constraints we’ll validate against
        $vehicleTypes = FlVehicle::query()
            ->whereNotNull('type')
            ->where('type', '<>', '')
            ->distinct()
            ->pluck('type')
            ->toArray();

        $campuses     = config('vrms.campuses', ['UTM JOHOR BAHRU', 'UTM KUALA LUMPUR']);
        $votePtj      = config('vrms.vote_ptj', ['BUDGET OF UNIVERSITY', 'FACULTY VOTE']);
        $bookingTypes = config('vrms.booking_types', ['SEND AND FETCH','SEND ONLY','FETCH ONLY']);
        $states       = config('vrms.states', ['JOHOR','KEDAH','KELANTAN','MELAKA','NEGERI SEMBILAN','PAHANG','PERAK','PERLIS','PULAU PINANG','SABAH','SARAWAK','SELANGOR','TERENGGANU','KUALA LUMPUR','PUTRAJAYA','LABUAN']);

        $validated = $request->validate([
            'purpose'            => ['required','string'],
            'attention_to'       => ['required', Rule::in($campuses)],
            'vote_ptj'           => ['required', Rule::in($votePtj)],
            'dept_faculty'       => ['required','string'],
            'officer_email'      => ['required','email'],
            // CHANGE: vehicle_request now validates against live vehicle "type" values
            'vehicle_request'    => ['required', Rule::in($vehicleTypes)],
            'no_vehicle'         => ['required','integer','min:1'],

            'program'            => ['required','string'],
            'booking_type'       => ['required', Rule::in($bookingTypes)],

            'pickup_point'       => ['required','string'],
            'pickup_state'       => ['required', Rule::in($states)],
            'destination'        => ['required','string'],
            'destination_state'  => ['required', Rule::in($states)],

            'start_date'         => ['required','date'],
            'start_time'         => ['required','date_format:H:i'],
            'end_date'           => ['required','date'],
            'end_time'           => ['required','date_format:H:i'],

            // For checkboxes use accepted (true for "1", "on", "yes", "true")
            'agree'              => ['accepted'],
        ]);

        // Combine date & time and ensure end > start
        $startAt = Carbon::parse($validated['start_date'].' '.$validated['start_time']);
        $endAt   = Carbon::parse($validated['end_date'].' '.$validated['end_time']);

        if ($endAt->lte($startAt)) {
            return back()
                ->withErrors(['end_date' => 'End date/time must be after start date/time.'])
                ->withInput();
        }

        // Persist
        $payload = $validated;
        $payload['user_id']          = Auth::id();
        $payload['reservation_date'] = now();
        $payload['status']           = 'PENDING';
        $payload['start_at']         = $startAt; // if your table has these columns
        $payload['end_at']           = $endAt;   // (optional) if not, remove

        // If your FlRequest fillable doesn’t include start_at/end_at yet, add them or remove above 2 lines.
        FlRequest::create($payload);

        return redirect()
            ->route('reservation.create')
            ->with('success', 'Reservation saved successfully.');
    }

    /**
     * History (unchanged, just tightened a little).
     */
    public function history(Request $request)
    {
        $query = FlRequest::with('user');

        if ($request->filled('req_id')) {
            $query->where('request_id', $request->input('req_id'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('reservation_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->orderByDesc('created_at')->get();

        return view('reservation.history', compact('reservations'));
    }

    /**
     * Index (unchanged).
     */
    public function index(Request $request)
    {
        $query = FlRequest::with('user');

        if ($request->filled('req_id')) {
            $query->where('request_id', $request->input('req_id'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('reservation_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->orderByDesc('created_at')->get();

        return view('system-admin.reservations.index', compact('reservations'));
    }
}
