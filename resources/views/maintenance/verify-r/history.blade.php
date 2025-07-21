@extends('layouts.app')

@section('content')
<h4>Maintenance » History (Verification)</h4>

<div class="card p-4 mb-4">
    <form method="GET" action="{{ route('maintenance.verify.history') }}">
        <div class="row mb-3">
            <div class="col-md-3">
                <label>REQ ID:</label>
                <input type="text" name="req_id" class="form-control" value="{{ request('req_id') }}">
            </div>
            <div class="col-md-3">
                <label>Requestor Id:</label>
                <input type="text" name="em_id" class="form-control" value="{{ request('em_id') }}">
            </div>
            <div class="col-md-3">
                <label>Requestor Name:</label>
                <input type="text" name="em_number" class="form-control" value="{{ request('em_number') }}">
            </div>
            <div class="col-md-3">
                <label>Registration No:</label>
                <select name="registration_no" class="form-select">
                    <option value="">ALL</option>
                    @foreach ($vehicles as $plate)
                        <option value="{{ $plate }}" {{ request('registration_no') == $plate ? 'selected' : '' }}>{{ $plate }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label>Date Requested (From):</label>
                <input type="date" name="date_1" class="form-control" value="{{ request('date_1') }}">
            </div>
            <div class="col-md-3">
                <label>Date Requested (To):</label>
                <input type="date" name="date_2" class="form-control" value="{{ request('date_2') }}">
            </div>
            <div class="col-md-3">
                <label>Status:</label>
                <select name="status" class="form-select">
                    <option value="">ALL</option>
                    <option {{ request('status') == 'REQUESTED' ? 'selected' : '' }}>REQUESTED</option>
                    <option {{ request('status') == 'APPROVED' ? 'selected' : '' }}>APPROVED</option>
                    <option {{ request('status') == 'REJECTED' ? 'selected' : '' }}>REJECTED</option>
                    <option {{ request('status') == 'IN PROGRESS' ? 'selected' : '' }}>IN PROGRESS</option>
                    <option {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>COMPLETED</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>
</div>

@if(isset($complaints) && $complaints->count())
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-secondary">
                <tr>
                    <th>#</th>
                    <th>REQ ID</th>
                    <th>Date Time Requested</th>
                    <th>Type</th>
                    <th>Registration No</th>
                    <th>Requestor ID</th>
                    <th>Requestor Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($complaints as $index => $complaint)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $complaint->id }}</td>
                        <td>{{ $complaint->created_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $complaint->type }}</td>
                        <td>{{ $complaint->plate_number }}</td>
                        <td>{{ $complaint->em_id }}</td>
                        <td>{{ $complaint->user->em_number ?? '-' }}</td>
                        <td>{{ $complaint->status ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">Maintenance: No records found.</div>
@endif
@endsection
