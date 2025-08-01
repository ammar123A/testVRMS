@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Reservation » Create Reservation</h4>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('reservation.store') }}">
        @csrf

        {{-- Requestor Information --}}
        <div class="card mb-3">
            <div class="card-header bg-light">Requestor Information</div>
            <div class="card-body">
                <div class="mb-3">
                    <label>Requestor Type</label>
                    <select name="user_type" class="form-select">
                        <option value="STAFF">STAFF</option>
                        <option value="STUDENT">STUDENT</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Staff No</label>
                    <div class="input-group">
                        <input type="text" name="em_id" class="form-control">
                        <button type="button" class="btn btn-secondary" id="checkAvailability">Check Availability</button>
                    </div>
                </div>

                <!-- <div class="mb-3">
                    <label>Requestor ID</label>
                    <input type="text" name="username" class="form-control">
                </div> -->

                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="em_number" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Department / Faculty</label>
                    <select name="dept_faculty" class="form-select">
                        @foreach ($departments as $id => $name)
                            <option value="{{ $id }}">{{ strtoupper($name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                    <div class="form-check mt-1">
                        <input type="checkbox" name="email_alert" class="form-check-input" checked>
                        <label class="form-check-label">Send Email Notification</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Booking Details --}}
        <div class="card mb-3">
            <div class="card-header bg-light">Booking Details</div>
            <div class="card-body">
                <div class="mb-3">
                    <label>PTJ</label>
                    <select name="ptj" class="form-select">
                        @foreach ($departments as $id => $name)
                            <option value="{{ $id }}">{{ strtoupper($name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Program</label>
                    <input type="text" name="program" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Purpose</label>
                    <select name="purpose" class="form-select">
                        @foreach ($purpose as $code => $label)
                            <option value="{{ $code }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Vehicle Request</label>
                    <select name="vehicle" class="form-select">
                        @foreach ($vehicle_request as $vehicle)
                            <option value="{{ $vehicle }}">{{ $vehicle }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Special Request (Remark)</label>
                    <input type="text" name="model_purpose" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Booking Type</label>
                    <select name="type" class="form-select">
                        @foreach ($booking_type as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Assembly / Pick Up Point</label>
                    <textarea name="assembly" class="form-control" rows="3"></textarea>
                    <small class="text-muted">Please enter details location/address (compulsory).</small>
                    <select name="assembly_state" class="form-select mt-2">
                        @foreach ($pickup_state as $code => $label)
                            <option value="{{ $code }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Destination</label>
                    <textarea name="destination" class="form-control" rows="3"></textarea>
                    <select name="destination_state" class="form-select mt-2">
                        @foreach ($pickup_state as $code => $label)
                            <option value="{{ $code }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Date Time Send/Fetch</label>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="date" name="date_pickup" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <input type="time" name="time_pickup" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Supported Document (Ref. No)</label>
                    <input type="text" name="ref_no" class="form-control">
                </div>
            </div>
        </div>

        {{-- Passenger Info --}}
        <div class="card mb-3">
            <div class="card-header bg-light">Passenger Information</div>
            <div class="card-body">
                <div class="mb-3">
                    <input type="checkbox" name="passenger_jpu" value="1" class="form-check-input" id="passengerJPU">
                    <label for="passengerJPU" class="form-check-label fw-bold text-dark text-decoration-underline">
                        JAWATANKUASA PENGURUSAN UNIVERSITI
                    </label>
                </div>

                <div class="mb-3">
                    <label>No. of Passenger</label>
                    <input type="number" name="passenger_number" class="form-control" min="1">
                </div>

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Handphone</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="passenger-list">
                        {{-- Dynamic rows via JS --}}
                    </tbody>
                </table>

                <button type="button" id="addPassenger" class="btn btn-outline-primary">Add Passenger</button>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success">Submit</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </form>
</div>

{{-- Optional JS --}}
<script>
    document.getElementById('addPassenger').addEventListener('click', function () {
        const tbody = document.getElementById('passenger-list');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" name="passenger[name][]" class="form-control" required></td>
            <td><input type="text" name="passenger[position][]" class="form-control" required></td>
            <td><input type="text" name="passenger[phone][]" class="form-control" required></td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">Remove</button></td>
        `;
        tbody.appendChild(row);
    });
</script>
@endsection
