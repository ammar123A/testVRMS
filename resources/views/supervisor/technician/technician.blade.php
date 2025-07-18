@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Supervisor » Technician</h4>

    @if(session('success'))
        <div class="alert alert-success" id="success-msg">{{ session('success') }}</div>
    @endif

    {{-- SEARCH --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="em_id" class="form-control" placeholder="Staff No" value="{{ request('em_id') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="em_number" class="form-control" placeholder="Name" value="{{ request('em_number') }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('technician.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    {{-- REGISTER --}}
    <form method="POST" action="{{ route('technician.store') }}" class="border p-4 mb-4">
        @csrf
        <h5>Register Technician</h5>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Staff No</label>
                <input type="text" name="em_id" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label>Name</label>
                <input type="text" name="em_number" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Gred</label>
                <input type="text" name="gred" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Position</label>
                <input type="text" name="position" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Department</label>
                <input type="text" name="dv_name" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Campus</label>
                <input type="text" name="site_name" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Username</label>
                <input type="text" name="user_name" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-success">Register</button>
    </form>

    {{-- TABLE --}}
    <h5>Technician List</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Last Updated</th>
                <th>Staff No</th>
                <th>Name</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($technicians as $i => $tech)
            <tr>
                <td>{{ $i + $technicians->firstItem() }}</td>
                <td>{{ $tech->updated_at }}</td>
                <td>{{ $tech->em_id }}</td>
                <td>{{ $tech->em_number }}</td>
                <td>{{ $tech->phone }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $technicians->links() }}
</div>

<script>
    setTimeout(() => {
        const msg = document.getElementById('success-msg');
        if (msg) msg.style.display = 'none';
    }, 2000);
</script>
@endsection
