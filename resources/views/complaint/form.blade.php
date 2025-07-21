@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Maintenance » Complaint</h2>

    @if(session('success'))
        <div class="alert alert-success" id="success-message">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('complaint.store') }}" method="POST">
        @csrf

        <!-- Vehicle dropdown -->
        <div class="mb-3">
            <label>Registration No</label>
            <select class="form-select" name="vehicle_id" id="vehicle_id" required>
                <option value="">-- Select --</option>
                @foreach($vehicles as $id => $plate)
                    <option value="{{ $id }}">{{ $plate }}</option>
                @endforeach
            </select>
        </div>

        <!-- Auto-populated fields -->
        <div class="mb-3">
            <label>Type</label>
            <input type="text" id="type" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <label>Model</label>
            <input type="text" id="model" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <label>Chassis No</label>
            <input type="text" id="chassis_no" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <label>Engine No</label>
            <input type="text" id="engine_no" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <label>Colour</label>
            <input type="text" id="colour" class="form-control" readonly>
        </div>

        <!-- Editable fields -->
        <div class="mb-3">
            <label>Odometer (km)</label>
            <input type="number" class="form-control" name="odometer">
        </div>

        <div class="mb-3">
            <label>Fuel Level</label>
            <input type="range" class="form-range" name="fuel" min="0" max="100" step="1">
        </div>

        <div class="mb-3">
            <label>Complaint</label>
            <textarea name="complaint" class="form-control" rows="4"></textarea>
        </div>

        <!-- Expiry Dates (read only) -->
        <div class="mb-3">
            <label>Road-Tax Expiry Date</label>
            <input type="text" id="road_tax_expiry" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <label>Puspakom Inspection Next Date</label>
            <input type="text" id="puspakom_expiry" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <label>Permit Expiry Date</label>
            <input type="text" id="permit_expiry" class="form-control" readonly>
        </div>

        <button class="btn btn-primary">Submit Complaint</button>
    </form>
</div>

<script>
document.getElementById('vehicle_id').addEventListener('change', function() {
    let vehicleId = this.value;

    if (!vehicleId) return;

    fetch(`/vehicle/details/${vehicleId}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('type').value = data.type || '';
            document.getElementById('model').value = data.model || '';
            document.getElementById('chassis_no').value = data.chassis_no || '';
            document.getElementById('engine_no').value = data.engine_no || '';
            document.getElementById('colour').value = data.colour || '';
            document.getElementById('road_tax_expiry').value = data.road_tax_expiry || '';
            document.getElementById('puspakom_expiry').value = data.puspakom_expiry || '';
            document.getElementById('permit_expiry').value = data.permit_expiry || '';
        });
});
</script>
@endsection
