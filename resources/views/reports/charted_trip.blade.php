@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Report &raquo; Charted Trip</h4>

    <div class="card mb-3">
        <div class="card-body">
            <form id="chartedTripForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="site_id" class="form-label"><strong>Campus:</strong></label>
                        <select class="form-select" id="site_id" name="site_id">
                            @foreach($sites as $site)
                                <option value="{{ $site->site_id }}" {{ session('FLEET.site_id') == $site->site_id ? 'selected' : '' }}>
                                    {{ strtoupper($site->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="date" class="form-label"><strong>Date:</strong></label>
                        <div class="input-group">
                            <input type="text" id="date" name="date" class="form-control datepicker" value="{{ $today }}" autocomplete="off" readonly>
                            <button class="btn btn-outline-secondary" type="button" id="search">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="result" class="mt-3 text-center" style="font-style: italic;">Results will appear here...</div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    $(function () {
        $(".datepicker").datepicker({ dateFormat: "dd-mm-yy" });

        function search() {
            const date = $('#date').val();
            const site_id = $('#site_id').val();

            $('#result').show().css({ 'text-align': 'center', 'font-style': 'italic' }).html('Loading...');

            $.ajax({
                type: 'POST',
                url: '{{ route("reports.charted_trip.ajax") }}',
                data: {
                    date: date,
                    site_id: site_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (html) {
                    $('#result').css({ 'text-align': 'left', 'font-style': 'normal' }).html(html);
                },
                error: function () {
                    $('#result').html('<div class="text-danger">Error loading data.</div>');
                }
            });
        }

        $('#search').click(search);
        window.onload = search;
    });
</script>
@endpush
