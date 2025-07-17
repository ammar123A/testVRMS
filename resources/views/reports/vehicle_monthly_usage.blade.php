@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Report: Vehicle Monthly Usage</h4>
    <form method="GET" action="{{ route('reports.vehicle_monthly_usage') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label>Year:</label>
                <select name="year" class="form-control">
                    @for ($i = date('Y'); $i >= 2010; $i--)
                        <option value="{{ $i }}" {{ $i == $selectedYear ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label>Vehicle Type:</label>
                <select name="vehicle_type" class="form-control">
                    @foreach (['CAR', 'BUS', 'MPV', 'VAN', '4WD'] as $type)
                        <option value="{{ $type }}" {{ $type == $vehicleType ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Registration No.</th>
                @foreach (range(1, 12) as $month)
                    <th>{{ \Carbon\Carbon::create()->month($month)->format('M') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($reportData as $regNo => $months)
                <tr>
                    <td>{{ $regNo }}</td>
                    @foreach ($months as $km)
                        <td>{{ number_format($km, 0) }} / km</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
