@extends('layouts.app')

@section('content')
<h4>Maintenance » History</h4>

<div class="card p-4 mb-4">
    <form id="filterForm">
        <div class="row mb-2">
            <div class="col-md-3">
                <label>WR ID</label>
                <input type="text" name="wr_id" class="form-control">
            </div>
            <div class="col-md-3">
                <label>WO ID</label>
                <input type="text" name="wo_id" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Vehicle Type</label>
                <select name="vehicle_type" class="form-control">
                    <option value="">ALL</option>
                    @foreach($vehicleTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Registration No</label>
                <input type="text" name="registration_no" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Date Requested (From)</label>
                <input type="date" name="date_1" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Date Requested (To)</label>
                <input type="date" name="date_2" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">ALL</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Requestor Staff No</label>
                <input type="text" name="em_id" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Requestor Name</label>
                <input type="text" name="em_number" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Department/Faculty</label>
                <select name="dv_id" class="form-control">
                    <option value="">ALL</option>
                    @foreach($departments as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Technician Assigned</label>
                <select name="cf_id" class="form-control">
                    <option value="">ALL</option>
                    @foreach($technicians as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-primary mt-3" id="searchBtn">Search</button>
    </form>
</div>

<div id="complaintResults">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>WR ID</th>
                <th>WO ID</th>
                <th>Date Time Requested</th>
                <th>Staff No.</th>
                <th>Requestor</th>
                <th>Type</th>
                <th>Registration No.</th>
                <th>Technician</th>
            </tr>
        </thead>
        <tbody id="complaintTableBody">
            <tr>
                <td colspan="9" class="text-center">Click "Search" to load data.</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    $('#searchBtn').click(function(){
        $.ajax({
            url: '{{ route("complaints.verifywr.search") }}',
            method: 'GET',
            data: $('#filterForm').serialize(),
            success: function(records){
                let tbody = '';

                if (records.length > 0) {
                    records.forEach((r, i) => {
                        tbody += `<tr>
                            <td>${i + 1}</td>
                            <td>${r.wr_id}</td>
                            <td>${r.wo_id}</td>
                            <td>${r.datetime_requested}</td>
                            <td>${r.em_id}</td>
                            <td>${r.em_number}</td>
                            <td>${r.type}</td>
                            <td>${r.registration_no}</td>
                            <td>${r.technician_name}</td>
                        </tr>`;
                    });
                } else {
                    tbody = `<tr><td colspan="9" class="text-center">No records found.</td></tr>`;
                }

                $('#complaintTableBody').html(tbody);
            },
            error: function(){
                $('#complaintTableBody').html('<tr><td colspan="9" class="text-center text-danger">Failed to fetch data.</td></tr>');
            }
        });
    });

    // Optional: auto-trigger search on load
    // $('#searchBtn').trigger('click');
});
</script>
@endpush
