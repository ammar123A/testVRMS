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

        // Auto-expand first section + show errors section if exists
        const firstContent = document.querySelector('.section-content');
        if (firstContent) firstContent.style.display = 'block';
        const errorBox = document.getElementById('error-box');
        if (errorBox) {
            document.querySelectorAll('.section-content').forEach(sc => sc.style.display = 'block');
        }
    });
</script>

<div class="container">
    <h5 class="mt-4">Reservation &raquo; Create Reservation</h5>

    {{-- Success flash --}}
    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error summary --}}
    @if ($errors->any())
        <div id="error-box" class="alert alert-danger mt-3">
            <strong>There were some problems with your submission:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reservation.store') }}">
        @csrf

        {{-- Requestor Information --}}
        <div class="section-title">Requestor Information</div>
        <div class="section-content">
            <table class="table table-bordered mb-0">
                <tr>
                    <th style="width: 280px;">Requestor Type</th>
                    <td>{{ strtoupper(auth()->user()->role ?? '-') }}</td>
                </tr>
                <tr>
                    <th>Requestor ID</th>
                    <td>{{ auth()->user()->username ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ auth()->user()->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Staff No. / New IC / Passport No.</th>
                    <td>{{ auth()->user()->em_id ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Department / Faculty</th>
                    <td>{{ auth()->user()->department ?? '-' }} / {{ auth()->user()->faculty ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Campus</th>
                    <td>{{ auth()->user()->campus ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Handphone</th>
                    <td>{{ auth()->user()->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
                        <input
                            type="email"
                            name="officer_email"
                            class="form-control @error('officer_email') is-invalid @enderror"
                            value="{{ old('officer_email', auth()->user()->email ?? '') }}"
                            required
                        >
                        @error('officer_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
            </table>
        </div>

        {{-- Costing Details --}}
        <div class="section-title">Costing Details</div>
        <div class="section-content">
            <div class="row mb-3">
                <div class="col-md-4"><label class="col-form-label">Attention To:</label></div>
                <div class="col-md-8">
                    <select name="attention_to" class="form-select @error('attention_to') is-invalid @enderror" required>
                        <option value="" disabled {{ old('attention_to') ? '' : 'selected' }}>-- Select campus --</option>
                        @foreach ($campuses as $c)
                            <option value="{{ $c }}" {{ old('attention_to') === $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                    @error('attention_to')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label class="col-form-label">Vote PTJ:</label></div>
                <div class="col-md-8">
                    <select name="vote_ptj" class="form-select @error('vote_ptj') is-invalid @enderror" required>
                        <option value="" disabled {{ old('vote_ptj') ? '' : 'selected' }}>-- Select vote --</option>
                        @foreach ($votePtj as $v)
                            <option value="{{ $v }}" {{ old('vote_ptj') === $v ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('vote_ptj')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label class="col-form-label">Department / Faculty:</label></div>
                <div class="col-md-8">
                    <input
                        type="text"
                        name="dept_faculty"
                        class="form-control @error('dept_faculty') is-invalid @enderror"
                        value="{{ old('dept_faculty') }}"
                        required
                    >
                    @error('dept_faculty')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label class="col-form-label">Vehicle Request (Type):</label></div>
                <div class="col-md-8">
                    <select name="vehicle_request" class="form-select @error('vehicle_request') is-invalid @enderror" required>
                        <option value="" disabled {{ old('vehicle_request') ? '' : 'selected' }}>-- Select vehicle type --</option>
                        @forelse ($vehicleTypes as $t)
                            <option value="{{ $t }}" {{ old('vehicle_request') === $t ? 'selected' : '' }}>
                                {{ strtoupper($t) }}
                            </option>
                        @empty
                            {{-- Fallback if no vehicle types found --}}
                            <option value="" disabled>No vehicle types available</option>
                        @endforelse
                    </select>
                    @error('vehicle_request')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label class="col-form-label">No. of Vehicles:</label></div>
                <div class="col-md-8">
                    <input
                        type="number"
                        name="no_vehicle"
                        class="form-control @error('no_vehicle') is-invalid @enderror"
                        value="{{ old('no_vehicle', 1) }}"
                        min="1"
                        required
                    >
                    @error('no_vehicle')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Booking Details --}}
        <div class="section-title">Booking Details</div>
        <div class="section-content">
            <div class="mb-3">
                <label class="form-label">Program:</label>
                <input
                    type="text"
                    name="program"
                    class="form-control @error('program') is-invalid @enderror"
                    value="{{ old('program') }}"
                    required
                >
                @error('program')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Purpose:</label>
                <input
                    type="text"
                    name="purpose"
                    class="form-control @error('purpose') is-invalid @enderror"
                    value="{{ old('purpose') }}"
                    required
                >
                @error('purpose')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Booking Type:</label>
                <select name="booking_type" class="form-select @error('booking_type') is-invalid @enderror" required>
                    <option value="" disabled {{ old('booking_type') ? '' : 'selected' }}>-- Select booking type --</option>
                    @foreach ($bookingTypes as $bt)
                        <option value="{{ $bt }}" {{ old('booking_type') === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                    @endforeach
                </select>
                @error('booking_type')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Pickup Point:</label>
                <textarea
                    name="pickup_point"
                    class="form-control @error('pickup_point') is-invalid @enderror"
                    required
                >{{ old('pickup_point') }}</textarea>
                @error('pickup_point')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                <select name="pickup_state" class="form-select mt-2 @error('pickup_state') is-invalid @enderror" required>
                    <option value="" disabled {{ old('pickup_state') ? '' : 'selected' }}>-- Select state --</option>
                    @foreach ($states as $st)
                        <option value="{{ $st }}" {{ old('pickup_state') === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
                @error('pickup_state')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Destination:</label>
                <textarea
                    name="destination"
                    class="form-control @error('destination') is-invalid @enderror"
                    required
                >{{ old('destination') }}</textarea>
                @error('destination')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                <select name="destination_state" class="form-select mt-2 @error('destination_state') is-invalid @enderror" required>
                    <option value="" disabled {{ old('destination_state') ? '' : 'selected' }}>-- Select state --</option>
                    @foreach ($states as $st)
                        <option value="{{ $st }}" {{ old('destination_state') === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
                @error('destination_state')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label class="col-form-label">Start Date &amp; Time:</label></div>
                <div class="col-md-4">
                    <input
                        type="date"
                        name="start_date"
                        class="form-control @error('start_date') is-invalid @enderror"
                        value="{{ old('start_date') }}"
                        required
                    >
                    @error('start_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <input
                        type="time"
                        name="start_time"
                        class="form-control @error('start_time') is-invalid @enderror"
                        value="{{ old('start_time') }}"
                        required
                    >
                    @error('start_time')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label class="col-form-label">End Date &amp; Time:</label></div>
                <div class="col-md-4">
                    <input
                        type="date"
                        name="end_date"
                        class="form-control @error('end_date') is-invalid @enderror"
                        value="{{ old('end_date') }}"
                        required
                    >
                    @error('end_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <input
                        type="time"
                        name="end_time"
                        class="form-control @error('end_time') is-invalid @enderror"
                        value="{{ old('end_time') }}"
                        required
                    >
                    @error('end_time')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Remark:</label>
                <input
                    type="text"
                    name="remark"
                    class="form-control @error('remark') is-invalid @enderror"
                    value="{{ old('remark') }}"
                >
                @error('remark')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Disclaimer --}}
        <div class="section-title">Disclaimer</div>
        <div class="section-content">
            <div class="mb-2">
                <strong>Disclaimer:</strong>
                <p>
                    I CERTIFY ALL THE INFORMATION ABOVE IS TRUTH.
                    I UNDERSTAND AND WILL COMPLY WITH THE CONDITIONS AND REGULATION OF USE OF THE VEHICLE
                    AS STATED IN THE MANAGEMENT AND SERVICES OF UNIVERSITY VEHICLE POLICY 2011.
                </p>
            </div>
            <div class="form-check">
                <input
                    class="form-check-input @error('agree') is-invalid @enderror"
                    type="checkbox"
                    name="agree"
                    value="1"
                    id="agreeCheckbox"
                    {{ old('agree') ? 'checked' : '' }}
                    required
                >
                <label class="form-check-label" for="agreeCheckbox">AGREE</label>
                @error('agree')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Submit Reservation</button>
        </div>
    </form>
</div>
@endsection
