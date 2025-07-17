@extends('layouts.app')

@section('content')
<h4>Report » List of Reservation by PTJ (Bus Only)</h4>

<form method="GET" action="{{ route('reports.list-by-ptj-bus') }}">
    <div class="row mb-3">
        <div class="col-sm-2">Date Pickup:</div>
        <div class="col-sm-4">
            <input type="text" name="date_start" class="form-control" value="{{ $startDate ?? '' }}" placeholder="Start Date">
            <input type="text" name="date_end" class="form-control" value="{{ $endDate ?? '' }}" placeholder="End Date">
        </div>

        <div class="col-sm-2">PTJ:</div>
        <div class="col-sm-4">
            <select name="ptj" class="form-control">
                <option value="">-- Select PTJ --</option>
                @foreach($ptjList as $code => $name)
                    <option value="{{ $code }}" {{ $ptjCode == $code ? 'selected' : '' }}>
                        {{ $code }} | {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Search</button>
</form>

@if(isset($results) && count($results) > 0)
    <h5 class="mt-4">Report: List of Reservation by PTJ (Bus Only)</h5>
    <p>Date: {{ $startDate ?? '-' }} - {{ $endDate ?? '-' }}</p>
    <p>PTJ: {{ $ptjCode }} | {{ $ptjList[$ptjCode] ?? '' }}</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Pickup</th>
                <th>Fetch</th>
                <th>Assembly</th>
                <th>Destination</th>
                <th>Program</th>
                <th>Passenger</th>
                <th>Vote</th>
                <th>PTJ Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $request)
                <tr>
                    <td>{{ $request->pickup_point }}</td>
                    <td>{{ $request->end_point }}</td>
                    <td>{{ $request->pickup_state }}</td>
                    <td>{{ $request->destination }}</td>
                    <td>{{ $request->program }}</td>
                    <td>
                        <ol>
                            @foreach($request->passengers as $p)
                                <li>{{ $p->name }}</li>
                            @endforeach
                        </ol>
                    </td>
                    <td>{{ $request->vote_ptj }}</td>
                    <td>{{ $request->estimated_cost }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="mt-3">No data found.</p>
@endif
@endsection
