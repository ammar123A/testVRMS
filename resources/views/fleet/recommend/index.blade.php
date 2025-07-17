@extends('layouts.app')

@section('content')
<h4>Fleet Management » Recommend New Reservation</h4>

<form method="GET" action="{{ route('recommend.index') }}">
    <div class="row mb-3">
        <div class="col-md-2">
            <label>REQ ID:</label>
            <input type="text" name="req_id" class="form-control" value="{{ request('req_id') }}">
        </div>

        <div class="col-md-2">
            <label>WR ID:</label>
            <input type="text" name="wr_id" class="form-control" value="{{ request('wr_id') }}">
        </div>

        <div class="col-md-2">
            <label>Requestor Type:</label>
            <select name="requestor_type" class="form-select">
                <option value="">All</option>
                @foreach ($request_types as $type)
                    <option value="{{ $type }}" {{ request('requestor_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label>Booking Type:</label>
            <select name="type" class="form-select">
                <option value="">All</option>
                @foreach ($booking_types as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <label>Date Requested:</label>
            <div class="d-flex gap-1">
                <input type="date" name="date_1" class="form-control" value="{{ request('date_1') }}">
                <input type="date" name="date_2" class="form-control" value="{{ request('date_2') }}">
            </div>
        </div>

        <div class="col-md-3">
            <label>Date Pickup:</label>
            <div class="d-flex gap-1">
                <input type="date" name="date_3" class="form-control" value="{{ request('date_3') }}">
                <input type="date" name="date_4" class="form-control" value="{{ request('date_4') }}">
            </div>
        </div>

        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>
</form>

<table class="table table-bordered mt-4">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>REQ ID</th>
            <th>Requestor</th>
            <th>Date Time Requested</th>
            <th>Date Time Pickup</th>
            <th>Pickup Point</th>
            <th>Destination</th>
            <th>Booking Type</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($requests as $index => $req)
            <tr>
                <td>{{ $index + $requests->firstItem() }}</td>
                <td>{{ $req->request_id }}</td>
                <td>{{ $req->user->user_type ?? '-' }}</td>
                <td>{{ $req->created_at->format('d-m-Y H:i:s') }}</td>
                <td>{{ $req->start_date }} {{ $req->start_time }}</td>
                <td>{{ $req->pickup_point }}</td>
                <td>{{ $req->destination }}</td>
                <td>{{ $req->booking_type }}</td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center">No records found</td></tr>
        @endforelse
    </tbody>
</table>

{{ $requests->withQueryString()->links() }}
@endsection
