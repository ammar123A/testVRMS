@extends('layouts.app')

@section('content')
<h4>Maintenance » Preventive</h4>

<div class="card p-4 mb-4">
    <form method="GET" action="{{ route('preventive.index') }}">
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Type:</label>
                <select name="type" class="form-control">
                    <option value="">ALL</option>
                    @foreach($vehicleTypes as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Registration No:</label>
                <input type="text" name="registration_no" class="form-control" value="{{ request('registration_no') }}">
            </div>
            <div class="col-md-3">
                <label>Model:</label>
                <input type="text" name="model" class="form-control" value="{{ request('model') }}">
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>
</div>

@if($vehicles->count())
<table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Status</th>
            <th>Type</th>
            <th>Registration No</th>
            <th>Model</th>
            <th>Maintenance</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vehicles as $index => $vehicle)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>UNDER MAINTENANCE</td>
            <td>{{ $vehicle->type }}</td>
            <td>{{ $vehicle->registration_no }}</td>
            <td>{{ $vehicle->model }}</td>
            <td>
                <ul class="mb-0">
                    <li>Date for Next Service: {{ $vehicle->next_service_date ?? 'N/A' }}</li>
                    <li>Road-Tax Expired: {{ $vehicle->road_tax_expiry ?? 'N/A' }}</li>
                    <li>Puspakom Inspection: {{ $vehicle->puspakom_expiry ?? 'N/A' }}</li>
                    <li>Permit Expired: {{ $vehicle->permit_expiry ?? 'N/A' }}</li>
                </ul>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
    <div class="alert alert-info">No vehicles found for the current filters.</div>
@endif

@endsection
