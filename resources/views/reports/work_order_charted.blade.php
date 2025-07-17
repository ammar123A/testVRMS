@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Report &raquo; Work Order Details</h4>

    <form id="searchForm" class="mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="dtStart" class="form-label">Date of Travel (Start):</label>
                <input type="text" id="dtStart" class="form-control datepicker" autocomplete="off">
            </div>
            <div class="col-md-3">
                <label for="dtEnd" class="form-label">Date of Travel (End):</label>
                <input type="text" id="dtEnd" class="form-control datepicker" autocomplete="off">
            </div>
            <div class="col-md-3">
                <label for="vehicle_type" class="form-label">Vehicle Type:</label>
                <select id="vehicle_type" class="form-select">
                    <option value="">All</option>
                    @foreach($vehicle_types as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="dv_id" class="form-label">PTJ:</label>
                <select id="dv_id" class="form-select">
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

    <div id="result" class="mt-4 text-center" style="font-style: italic;">Results will appear here...</div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
$(function () {
    $(".datepicker").datepicker({ dateFormat: "dd-mm-yy" });

    $('#dtStart').on('blur', function () {
        if ($(this).val()) {
            $('#dtEnd').val($(this).val());
        }
    });

    $('#search').on('click', function () {
        const dtStart = $('#dtStart').val();
        const dtEnd = $('#dtEnd').val();

        if (!dtStart) {
            showError('Please enter start date.');
            return;
        }
        if (!dtEnd) {
            showError('Please enter end date.');
            return;
        }

        if (!isDateRangeValid(dtStart, dtEnd)) {
            showError('Invalid date range.');
            return;
        }

        const params = {
            date_start: dtStart,
            date_end: dtEnd,
            vehicle_type: $('#vehicle_type').val(),
            dv_id: $('#dv_id').val()
        };

        $('#result').css('text-align', 'center').css('font-style', 'italic').html('Loading...');

        $.ajax({
            url: "{{ route('reports.work_order_charted.ajax') }}",
            type: 'POST',
            data: params,
            success: function (html) {
                $('#result').css('text-align', 'left').css('font-style', 'normal').html(html);
            },
            error: function () {
                $('#result').html('<div class="text-danger">Failed to load data. Please try again.</div>');
            }
        });
    });

    function showError(message) {
        alert(message);
    }

    function isDateRangeValid(start, end) {
        const [sd, sm, sy] = start.split('-');
        const [ed, em, ey] = end.split('-');

        const startDate = new Date(sy, sm - 1, sd);
        const endDate = new Date(ey, em - 1, ed);

        return startDate <= endDate;
    }
});
</script>
@endpush
