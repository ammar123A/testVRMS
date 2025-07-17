@extends('layouts.app')

@section('content')
<style>
    .td-left-search {
        width: 15%;
        font-weight: bold;
        text-align: right;
        padding-right: 10px;
    }

    .td-right-search {
        width: 35%;
    }

    table.report-table {
        width: 100%;
        border-collapse: collapse;
    }

    table.report-table th, table.report-table td {
        border: 1px solid #ccc;
        padding: 6px;
        text-align: center;
    }

    table.report-table th {
        background-color: #2c3e50;
        color: #fff;
    }

    table.report-table td.highlight {
        background-color: #ccffcc;
    }
</style>

<h4>Report &raquo; Reservation Cost (Bus Only)</h4>

<form method="GET" action="{{ route('reports.reservation-cost-bus') }}">
    <table cellpadding="3" cellspacing="3">
        <tr>
            <td class="td-left-search">Year:</td>
            <td class="td-right-search">
                <select name="year" id="year">
                    @for ($i = now()->year; $i >= 2010; $i--)
                        <option value="{{ $i }}" {{ $i == $selectedYear ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </td>

            <td class="td-left-search">Report Type:</td>
            <td class="td-right-search">
                <select name="report_type" id="report_type">
                    <option value="LIST" {{ $reportType == 'LIST' ? 'selected' : '' }}>LIST</option>
                    {{-- Future: <option value="GRAPH">GRAPH</option> --}}
                </select>
            </td>

            <td><button type="submit">Search...</button></td>
        </tr>
    </table>
</form>

<br>

<p><strong>Report: Reservation Cost (Bus Only)</strong></p>
<p>Year: {{ $selectedYear }}</p>

<table class="report-table">
    <thead>
        <tr>
            <th>PTJ</th>
            @foreach(range(1, 12) as $m)
                <th>{{ \Carbon\Carbon::create()->month($m)->format('M') }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($reportData as $vote_ptj => $monthly)
            <tr>
                <td>{{ $vote_ptj }}</td>
                @foreach($monthly as $month => $value)
                    <td class="{{ $value > 0 ? 'highlight' : '' }}">{{ $value > 0 ? number_format($value, 2) : '' }}</td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="13">No data found for the selected year.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection
