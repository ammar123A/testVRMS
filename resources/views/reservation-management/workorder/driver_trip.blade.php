@extends('layouts.app')

@section('content')
<style>
    .td-left-search { width: 15%; }
    .td-right-search { width: 35%; }
    #tabs-1 { background-color: #f4f4f4; padding: 20px; }
    .table-head { font-weight: bold; background-color: #cccccc; }
    .table-row { border-right: 1px dotted #cccccc; border-bottom: 1px dotted #cccccc; }
    .form-control[readonly] { background-color: #fff; }
</style>

<h3 class="text-center">Driver Trip</h3>

<div id="tabs">
    <div id="tabs-1">
        <form id="trip-search-form">
            <table class="table table-borderless" style="width: 100%;">
                <tr>
                    <td class="td-left-search text-end"><strong>Campus:</strong></td>
                    <td class="td-right-search">
                        <select id="site_id" class="form-control" name="site_id">
                            @foreach($sites as $site)
                                <option value="{{ $site->site_id }}" {{ session('FLEET.site_id') === $site->site_id ? 'selected' : '' }}>
                                    {{ strtoupper($site->name) }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="td-left-search text-end"><strong>Date:</strong></td>
                    <td class="td-right-search">
                        <input type="text" id="date" name="date" class="form-control d-inline w-auto" value="{{ $today }}" readonly>
                        <button type="button" id="search" class="btn btn-primary">Search...</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<br>

<div id="result"></div>
@endsection

@section('scripts')
<!-- Include jQuery UI for the datepicker if not already in your layout -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script>
$(document).ready(function () {
    $("#date").datepicker({ dateFormat: 'dd-mm-yy' });

    function loadTrips() {
        let formData = {
            date: $('#date').val(),
            site_id: $('#site_id').val()
        };

        $('#result').css('text-align', 'center').css('font-style', 'italic').html('Loading...');

        $.ajax({
            url: '{{ route("reports.driver_trip_ajax") }}',
            method: 'POST',
            data: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function (response) {
                $('#result').css('text-align', 'left').css('font-style', 'normal').html(response);
            },
            error: function () {
                $('#result').html('<div class="alert alert-danger">Error loading data.</div>');
            }
        });
    }

    $('#search').click(function () {
        loadTrips();
    });

    // auto-trigger on load
    loadTrips();
});
</script>
@endsection
