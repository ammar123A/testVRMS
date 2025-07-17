@extends('layouts.app')

@section('content')
<h4>Fleet Management >> History</h4>

<form method="GET" action="{{ route('fleet.history') }}">
    <div class="row g-3">
        <div class="col-md-2">
            <label>REQ ID:</label>
            <input type="text" name="req_id" class="form-control" value="{{ request('req_id') }}">
        </div>
        <div class="col-md-2">
            <label>WR ID:</label>
            <input type="text" name="wr_id" class="form-control" value="{{ request('wr_id') }}">
        </div>
        <div class="col-md-3">
            <label>Date Requested:</label>
            <div class="input-group">
                <input type="text" name="date_1" class="form-control" placeholder="From" value="{{ request('date_1') }}">
                <input type="text" name="date_2" class="form-control" placeholder="To" value="{{ request('date_2') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label>Date Send:</label>
            <div class="input-group">
                <input type="text" name="date_3" class="form-control" placeholder="From" value="{{ request('date_3') }}">
                <input type="text" name="date_4" class="form-control" placeholder="To" value="{{ request('date_4') }}">
            </div>
        </div>
        <div class="col-md-2">
            <label>Type:</label>
            <select name="type" class="form-select">
                <option value="">ALL</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Status:</label>
            <select name="status" class="form-select">
                <option value="">ALL</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>
</form>

<br>

@if($requests->count())
    <table class="table table-bordered mt-4">
        <thead class="table-secondary">
            <tr>
                <th>#</th>
                <th>REQ ID</th>
                <th>Date Time Requested</th>
                <th>Program</th>
                <th>Send</th>
                <th>Fetch</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $index => $res)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $res->request_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($res->created_at)->format('d-m-Y H:i:s') }}</td>
                    <td>{{ $res->program }}</td>
                    <td>{{ $res->start_date ? \Carbon\Carbon::parse($res->start_date)->format('d-m-Y H:i') : 'N/A' }}</td>
                    <td>{{ $res->end_date ? \Carbon\Carbon::parse($res->end_date)->format('d-m-Y H:i') : 'N/A' }}</td>
                    <td>{{ $res->booking_type }}</td>
                    <td>{{ $res->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $requests->withQueryString()->links() }}
@else
    <div class="alert alert-info mt-3">No reservation history found.</div>
@endif

@endsection
