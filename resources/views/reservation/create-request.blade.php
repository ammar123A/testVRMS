@extends('layouts.app')

@section('content')
<style>
    .section-title {
        font-weight: bold;
        background-color: #e1f0f5;
        padding: 8px;
        margin-top: 20px;
        border: 1px solid #ccc;
        cursor: pointer;
    }
    .section-content {
        border: 1px solid #ccc;
        border-top: none;
        padding: 15px;
        display: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.section-title').forEach(section => {
            section.addEventListener('click', () => {
                const content = section.nextElementSibling;
                content.style.display = (content.style.display === 'none' || content.style.display === '') ? 'block' : 'none';
            });
        });
    });
</script>

<div class="container">
    <h5 class="mt-4">Reservation &raquo; Create Reservation</h5>

    <form method="POST" action="{{ route('reservation.store') }}">
        @csrf

        <div class="section-title">Requestor Information</div>
        <div class="section-content">
            <table class="table table-bordered">
                <tr><th>Requestor Type</th><td>{{ strtoupper(auth()->user()->role ?? '-') }}</td></tr>
                <tr><th>Requestor ID</th><td>{{ auth()->user()->username ?? '-' }}</td></tr>
                <tr><th>Name</th><td>{{ auth()->user()->name ?? '-' }}</td></tr>
                <tr><th>Staff No. / New IC / Passport No.</th><td>{{ auth()->user()->em_id ?? '-' }}</td></tr>
                <tr><th>Department / Faculty</th><td>{{ auth()->user()->department ?? '-' }} / {{ auth()->user()->faculty ?? '-' }}</td></tr>
                <tr><th>Campus</th><td>{{ auth()->user()->campus ?? '-' }}</td></tr>
                <tr><th>Handphone</th>
                    <td>
                        <input type="text" name="handphone" class="form-control" required>
                    </td>
                </tr>
                <tr><th>Email</th><td><input type="email" name="officer_email" class="form-control" value="{{ auth()->user()->email ?? '' }}" required></td></tr>
            </table>
        </div>

        <div class="section-title">Costing Details</div>
        <div class="section-content">
            <div class="row mb-3">
                <div class="col-md-4"><label>Attention To:</label></div>
                <div class="col-md-8">
                    <select name="attention_to" class="form-select" required>
                        <option>UTM JOHOR BAHRU</option>
                        <option>UTM KUALA LUMPUR</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label>Vote PTJ:</label></div>
                <div class="col-md-8">
                    <select name="vote_ptj" class="form-select" required>
                        <option>BUDGET OF UNIVERSITY</option>
                        <option>FACULTY VOTE</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label>Department / Faculty:</label></div>
                <div class="col-md-8">
                    <input type="text" name="dept_faculty" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label>Vehicle Request:</label></div>
                <div class="col-md-8">
                    <select name="vehicle_request" class="form-select" required>
                        <option>CAR</option>
                        <option>BUS</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label>No. of Vehicles:</label></div>
                <div class="col-md-8">
                    <input type="number" name="no_vehicle" class="form-control" value="1" min="1">
                </div>
            </div>
        </div>

        <div class="section-title">Booking Details</div>
        <div class="section-content">
            <div class="mb-3">
                <label>Program:</label>
                <input type="text" name="program" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Purpose:</label>
                <input type="text" name="purpose" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Booking Type:</label>
                <select name="booking_type" class="form-select">
                    <option>SEND AND FETCH</option>
                    <option>SEND ONLY</option>
                    <option>FETCH ONLY</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Pickup Point:</label>
                <textarea name="pickup_point" class="form-control" required></textarea>
                <select name="pickup_state" class="form-select mt-2">
                    <option>JOHOR</option>
                    <option>PERAK</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Destination:</label>
                <textarea name="destination" class="form-control" required></textarea>
                <select name="destination_state" class="form-select mt-2">
                    <option>JOHOR</option>
                    <option>PERAK</option>
                </select>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><label>Start Date & Time:</label></div>
                <div class="col-md-4"><input type="date" name="start_date" class="form-control" required></div>
                <div class="col-md-4"><input type="time" name="start_time" class="form-control" required></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><label>End Date & Time:</label></div>
                <div class="col-md-4"><input type="date" name="end_date" class="form-control" required></div>
                <div class="col-md-4"><input type="time" name="end_time" class="form-control" required></div>
            </div>
            <div class="mb-3">
                <label>Remark:</label>
                <input type="text" name="remark" class="form-control">
            </div>
        </div>

        <div class="section-title">Disclaimer</div>
        <div class="section-content">
            <div class="mb-2">
                <strong>Disclaimer:</strong>
                <p>I CERTIFY ALL THE INFORMATION ABOVE IS TRUTH. I UNDERSTAND AND WILL COMPLY WITH THE CONDITIONS AND REGULATION OF USE OF THE VEHICLE AS STATED IN THE MANAGEMENT AND SERVICES OF UNIVERSITY VEHICLE POLICY 2011.</p>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="agree" value="1" id="agreeCheckbox" required>
                <label class="form-check-label" for="agreeCheckbox">AGREE</label>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Submit Reservation</button>
        </div>
    </form>
</div>
@endsection
