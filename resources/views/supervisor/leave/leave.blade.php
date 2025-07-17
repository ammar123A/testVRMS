@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Supervisor » Leave</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- SEARCH FORM --}}
    <form method="GET" action="{{ route('leave.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="em_id" class="form-control" placeholder="Staff No" value="{{ request('em_id') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="name" class="form-control" placeholder="Name" value="{{ request('name') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    {{-- REGISTER FORM --}}
    <form method="POST" action="{{ route('leave.store') }}" class="border p-4 mb-4">
        @csrf
        <h5>Register Leave</h5>
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">Staff No</label>
                <input type="text" name="em_id" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Reason</label>
                <input type="text" name="reason" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-success mt-3">Register</button>
    </form>

    {{-- LEAVE TABLE --}}
    <h5>Leave Records</h5>
    @if($leaves->count())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Staff No</th>
                <th>Name</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaves as $i => $leave)
                <tr>
                    <td>{{ $i + $leaves->firstItem() }}</td>
                    <td>{{ $leave->em_id }}</td>
                    <td>{{ $leave->driver->name ?? '-' }}</td>
                    <td>{{ $leave->start_date }} - {{ $leave->end_date }}</td>
                    <td>{{ $leave->reason }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $leaves->links() }}
    @else
        <p>No leave records found.</p>
    @endif
</div>
@endsection
