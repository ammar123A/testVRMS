@extends('layouts.app') {{-- or your base layout --}}

@section('content')
<div class="container">
    <h4>Register New Vehicle</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('vehicle.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="plate_number" class="form-label">Plate Number</label>
            <input type="text" name="plate_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="model" class="form-label">Model</label>
            <input type="text" name="model" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" class="form-select" required>
                <option value="">-- Choose Type --</option>
                <option value="CAR">CAR</option>
                <option value="VAN">VAN</option>
                <option value="BUS">BUS</option>
                <option value="4WD">4WD</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="department" class="form-label">Faculty/Department</label>
            <select name="department" class="form-select" required>
                <option value="">-- Choose Department --</option>
                <option value="Fakulti Komputeran">Fakulti Komputeran</option>
                <option value="Fakulti Matematik">Fakulti Matematik</option>
                <option value="Fakulti Mekatronik">Fakulti Mekatronik</option>
                <option value="Fakulti Elektrik">Fakulti Elektrik</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="registration_date" class="form-label">Registration Date</label>
            <input type="date" name="registration_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Register</button>
        <a href="{{ route('vehicle.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

    <div class="mt-5">
        <h4>Registered Vehicles</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Plate Number</th>
                    <th>Model</th>
                    <th>Type</th>
                    <th>Department</th>
                    <th>Registration Date</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $vehicle)
                    <tr>
                        <td>{{ $vehicle->plate_number }}</td>
                        <td>{{ $vehicle->model }}</td>
                        <td>{{ $vehicle->type }}</td>
                        <td>{{ $vehicle->department }}</td>
                        <td>{{ $vehicle->registration_date }}</td>
                        <td>{{ $vehicle->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No vehicles registered yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
