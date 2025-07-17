@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Report: Driver Monthly Trip</h4>

    <form method="GET" action="{{ route('reports.driverMonthlyTrip') }}">
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Year:</label>
            <div class="col-sm-3">
                <select name="year" class="form-control">
                    @for ($i = date('Y'); $i >= 2010; $i--)
                        <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <label class="col-sm-2 col-form-label">Report Type:</label>
            <div class="col-sm-3">
                <select name="report_type" class="form-control">
                    <option value="LIST" {{ $reportType == 'LIST' ? 'selected' : '' }}>LIST</option>
                </select>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </div>
    </form>

    <h6>Year: {{ $selectedYear }}</h6>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Staff No.</th>
                <th>Driver</th>
                @foreach(range(1, 12) as $m)
                    <th>{{ \Carbon\Carbon::create()->month($m)->format('M') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $staffNo => $data)
                <tr>
                    <td>{{ $staffNo }}</td>
                    <td>{{ $data['name'] }}</td>
                    @foreach(range(1, 12) as $m)
                        <td>{{ $data['monthly'][$m] }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
