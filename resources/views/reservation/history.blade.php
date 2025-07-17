@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Reservation History</h4>

    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-2">
                <label>REQ ID:</label>
                <input type="text" name="req_id" class="form-control" value="{{ request('req_id') }}">
            </div>
            <div class="col-md-3">
                <label>Date Requested:</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label>to</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2">
                <label>Status:</label>
                <select name="status" class="form-select">
                    <option value="">ALL</option>
                    <option value="REQUESTED" {{ request('status') == 'REQUESTED' ? 'selected' : '' }}>REQUESTED</option>
                    <option value="APPROVED" {{ request('status') == 'APPROVED' ? 'selected' : '' }}>APPROVED</option>
                    <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>REJECTED</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-hover text-center">
        <thead class="table-secondary">
            <tr>
                <th>#</th>
                <th>REQ ID</th>
                <th>Date Requested</th>
                <th>Program</th>
                <th>Send</th>
                <th>Fetch</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reservations as $index => $res)
                <tr class="align-middle">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $res->request_id }}</td>
                    <td>{{ $res->created_at->format('d-m-Y H:i:s') }}</td>
                    <td>{{ $res->program }}</td>
                    <td>{{ \Carbon\Carbon::parse($res->start_date)->format('d-m-Y') }} {{ $res->start_time }}</td>
                    <td>{{ \Carbon\Carbon::parse($res->end_date)->format('d-m-Y') }} {{ $res->end_time }}</td>
                    <td>{{ $res->booking_type }}</td>
                    <td>{{ $res->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No reservation history found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
