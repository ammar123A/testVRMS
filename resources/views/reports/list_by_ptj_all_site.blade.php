@extends('layouts.app')

@section('content')
<h4>Report &raquo; List of Reservation by PTJ (All Site & Vehicle Type)</h4>

<form method="GET" action="{{ route('reports.list_by_ptj_all_site') }}">
    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Date Pickup:</label>
        <div class="col-sm-4">
            <input type="date" name="date_start" value="{{ request('date_start') }}" class="form-control">
        </div>
        <div class="col-sm-4">
            <input type="date" name="date_end" value="{{ request('date_end') }}" class="form-control">
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">PTJ:</label>
        <div class="col-sm-6">
            <select name="ptj" class="form-control">
                <option value="">-- Select PTJ --</option>
                @foreach($ptjList as $ptjCode => $ptjName)
                    <option value="{{ $ptjCode }}" {{ request('ptj') == $ptjCode ? 'selected' : '' }}>
                        {{ $ptjCode }} | {{ $ptjName }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>
</form>

@if($reservations->count())
    <h5>PTJ: {{ $selectedPTJ }}</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Request ID</th>
                <th>Pickup Date</th>
                <th>Pickup Time</th>
                <th>Vehicle Type</th>
                <th>Pickup Point</th>
                <th>Destination</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->request_id }}</td>
                <td>{{ \Carbon\Carbon::parse($row->start_date)->format('d-m-Y') }}</td>
                <td>{{ $row->start_time }}</td>
                <td>{{ $row->vehicle_request }}</td>
                <td>{{ $row->pickup_point }}</td>
                <td>{{ $row->destination }}</td>
                <td>{{ $row->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-warning">No reservation data found for this criteria.</div>
@endif
@endsection
