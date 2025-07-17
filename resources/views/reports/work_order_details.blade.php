@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Report &raquo; Work Order Details</h4>

    <form id="searchForm" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <label for="dtStart" class="form-label">Date of Travel (Start):</label>
                <input type="text" class="form-control datepicker" id="dtStart" name="dtStart" autocomplete="off">
            </div>
            <div class="col-md-3">
                <label for="dtEnd" class="form-label">Date of Travel (End):</label>
                <input type="text" class="form-control datepicker" id="dtEnd" name="dtEnd" autocomplete="off">
            </div>
            <div class="col-md-3">
                <label for="vehicle_type" class="form-label">Vehicle Type:</label>
                <select class="form-select" id="vehicle_type" name="vehicle_type">
                    <option value="">All</option>
                    @foreach($vehicle_types as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="dv_id" class="form-label">PTJ:</label>
                <select class="form-select" id="dv_id" name="dv_id">
                    <option value="">All</option>
                    @foreach($dv_list as $dv)
                        <option value="{{ $dv->dv_id }}">{{ $dv->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-3">
            <button type="button" id="search" class="btn btn-primary">Search</button>
        </div>
    </form>

    <div id="result" class="mt-4 text-center" style="font-style: italic;">Results will be shown here...</div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

@endpush
