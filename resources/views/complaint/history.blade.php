@extends('layouts.app')

@section('content')
<h4>Maintenance » History</h4>

<div class="card p-4 mb-4">
    <form id="filterForm">
        <div class="row mb-2">
            <div class="col-md-3">
                <label>REQ ID</label>
                <input type="text" name="req_id" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Registration No</label>
                <select name="registration_no" class="form-control">
                    <option value="">ALL</option>
                    @foreach ($vehicles as $id => $plate)
                        <option value="{{ $plate }}">{{ $plate }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Date Requested From</label>
                <input type="date" name="date_1" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Date Requested To</label>
                <input type="date" name="date_2" class="form-control">
            </div>
            <div class="col-md-3 mt-2">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">--</option>
                    <option>REQUESTED</option>
                    <option>APPROVED</option>
                    <option>REJECTED</option>
                    <option>IN PROGRESS</option>
                    <option>COMPLETED</option>
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-primary mt-3" id="searchBtn">Search</button>
    </form>
</div>

<div id="historyResults">
    <!-- Results will be injected here via AJAX -->
</div>
@endsection

@push('scripts')
<script>
$(function(){
    // Trigger search on button click
    $('#searchBtn').click(function(){
        $.ajax({
            url: '{{ route('complaint.history.search') }}',
            data: $('#filterForm').serialize(),
            success: function(response){
                $('#historyResults').html(response);
            },
            error: function(){
                $('#historyResults').html('<div class="alert alert-danger">Failed to fetch data.</div>');
            }
        });
    });

    // Auto-trigger initial load
    $('#searchBtn').trigger('click');
});
</script>
@endpush
