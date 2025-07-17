@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Reservation Management » Ad-Hoc</h4>
    <form id="searchForm">
        <div class="row">
            <div class="col-md-2">
                <label>REQ ID:</label>
                <input type="text" name="req_id" class="form-control">
            </div>
            <div class="col-md-2">
                <label>Requestor Type:</label>
                <select name="requestor_type" class="form-control">
                    <option value="">ALL</option>
                    <option value="STAFF">STAFF</option>
                    <option value="STUDENT">STUDENT</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Date Requested:</label>
                <input type="date" name="date1" class="form-control d-inline w-45">
                <input type="date" name="date2" class="form-control d-inline w-45">
            </div>
            <div class="col-md-4">
                <label>Date Send:</label>
                <input type="date" name="date3" class="form-control d-inline w-45">
                <input type="date" name="date4" class="form-control d-inline w-45">
            </div>
            <div class="col-md-3">
                <label>Booking Type:</label>
                <select name="type" class="form-control">
                    <option value="">ALL</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Status:</label>
                <select name="status" class="form-control">
                    <option value="">ALL</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 align-self-end">
                <button type="button" id="search" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>
    
    <div id="result" class="mt-4"></div>
</div>
@endsection

@section('scripts')
<script>
    $('#search').click(function() {
        let params = $('#searchForm').serialize();
        $('#result').html('Loading...');
        $.get("{{ route('reservation.history.search') }}", params, function(data) {
            $('#result').html(data);
        });
    });
</script>
@endsection
