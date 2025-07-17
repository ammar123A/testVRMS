@extends('layouts.app')

@section('content')
<h4>Report &raquo; Reservation by Vehicle Type</h4>

<form method="GET" action="{{ route('reports.vehicle-type') }}">
    <div class="mb-3">
        <label for="year">Year:</label>
        <select name="year" id="year">
            @for ($i = now()->year; $i >= 2010; $i--)
                <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>

        <label for="report_type">Report Type:</label>
        <select name="report_type" id="report_type">
            <option value="LIST" {{ $reportType == 'LIST' ? 'selected' : '' }}>LIST</option>
            <option value="GRAPH" {{ $reportType == 'GRAPH' ? 'selected' : '' }}>GRAPH</option>
        </select>

        <button type="submit">Search</button>
    </div>
</form>

@if($reportType == 'LIST')
    <h5>Year: {{ $selectedYear }}</h5>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Month</th>
                @foreach($vehicleTypes as $type)
                    <th>{{ $type }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $month => $counts)
                <tr>
                    <td>{{ $month }}</td>
                    @foreach($vehicleTypes as $type)
                        <td>{{ $counts[$type] ?? 0 }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
