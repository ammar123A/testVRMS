@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Report: List of Reservation by PTJ (All Site & Vehicle Type)</h4>
    <form method="GET" action="{{ route('reports.ptj.export') }}">
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Date Pickup (Start)</label>
                <input type="date" name="date_start" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Date Pickup (End)</label>
                <input type="date" name="date_end" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>PTJ</label>
                <input type="text" name="dv_id" class="form-control" placeholder="e.g. UTMDIGITAL" required>
                <input type="hidden" name="dv_name" value="JABATAN PERKHIDMATAN DIGITAL (UTMDIGITAL)">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Export Excel - All Site & Vehicle Type</button>
    </form>
</div>
@endsection
