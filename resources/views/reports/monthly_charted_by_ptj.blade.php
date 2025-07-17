@extends('layouts.app')

@section('content')
<style>
    .td-left-search { width: 15%; }
    .td-right-search { width: 35%; }
    .label1 { font-style: italic; padding-left: 5px; padding-right: 10px; text-transform: uppercase; }
    #tabs-1 { background-color: #f4f4f4; padding: 20px; border: 1px solid #ddd; }
    .table-bordered td, .table-bordered th { text-align: center; }
</style>

<h4>Report &raquo; Monthly Charted by PTJ</h4>

<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Report</a></li>
    </ul>
    <div id="tabs-1">
        <form method="GET" action="{{ route('reports.monthly_charted_by_ptj') }}">
            <table cellpadding="3" cellspacing="3" width="100%">
                <tr>
                    <td class="td-left-search">Year:</td>
                    <td class="td-right-search">
                        <select name="s_year" class="form-control">
                            <option value=""></option>
                            @for($i = date('Y'); $i > 2009; $i--)
                                <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </td>
                    <td class="td-left-search">Report Type:</td>
                    <td class="td-right-search">
                        <select name="report_type" class="form-control">
                            <option value="LIST" selected>LIST</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="td-left-search">&nbsp;</td>
                    <td class="td-right-search">
                        <button type="submit" class="btn btn-primary">Search...</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<br/>

@if(isset($groupedData) && count($groupedData) > 0)
    <h5>Report: Monthly Charted by PTJ</h5>
    <p><strong>Year:</strong> {{ $year }}</p>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>PTJ</th>
                    @foreach(range(1, 12) as $m)
                        <th>{{ DateTime::createFromFormat('!m', $m)->format('M') }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($groupedData as $ptj => $monthly)
                    <tr>
                        <td class="text-left">{{ $ptj }}</td>
                        @foreach(range(1, 12) as $month)
                            @php
                                $entry = $monthly->firstWhere('month', $month);
                                $trips = $entry->total_trips ?? 0;
                                $km = $entry->total_km ?? 0;
                            @endphp
                            <td>{{ $trips }} / {{ $km }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    @if(request()->has('s_year'))
        <div class="alert alert-warning">No data available for the selected year.</div>
    @endif
@endif
@endsection
