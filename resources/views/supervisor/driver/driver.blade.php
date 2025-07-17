@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Supervisor » Driver</h4>

    {{-- Display success messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Display validation errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- SEARCH FORM --}}
    <form method="GET" action="{{ route('driver.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="em_id" class="form-control" placeholder="Staff No" value="{{ request('em_id') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="name" class="form-control" placeholder="Name" value="{{ request('name') }}">
        </div>
        <div class="col-md-6">
            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    {{-- REGISTER FORM --}}
    <form method="POST" action="{{ route('driver.store') }}" class="border p-4 mb-4">
        @csrf
        <h5>Register New Driver</h5>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="em_id" class="form-label">Staff No</label>
                <input type="text" name="em_id" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="license_number" class="form-label">License Number</label>
                <input type="text" name="license_number" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="ic_number" class="form-label">IC Number</label>
                <input type="text" name="ic_number" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-success">Register</button>
    </form>

    {{-- DRIVER TABLE --}}
    <h5>Driver List</h5>
    @if($drivers->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Last Updated</th>
                    <th>Staff No</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                </tr>
            </thead>
            <tbody>
                @foreach($drivers as $i => $driver)
                    <tr>
                        <td>{{ $i + $drivers->firstItem() }}</td>
                        <td>{{ $driver->updated_at }}</td>
                        <td>{{ $driver->em_id }}</td>
                        <td>{{ $driver->name }}</td>
                        <td>{{ $driver->phone }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $drivers->links() }}
    @else
        <p>No drivers found.</p>
    @endif
</div>
@endsection
