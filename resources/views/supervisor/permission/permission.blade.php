@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Supervisor » User Permission</h4>

    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- Search Form --}}
    <form method="GET" action="{{ route('permission.index') }}" class="row g-3 mb-3">
        <div class="col-md-4">
            <input type="text" name="em_id" class="form-control" placeholder="Staff No" value="{{ request('em_id') }}">
        </div>
        <div class="col-md-4">
            <input type="text" name="name" class="form-control" placeholder="Name" value="{{ request('name') }}">
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    {{-- Register Form --}}
    <form method="POST" action="{{ route('permission.store') }}" class="border p-4 mb-4">
        @csrf
        <h5>Register New Permission</h5>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label>Staff No</label>
                <input type="text" name="em_id" class="form-control" required>
            </div>
            <div class="col-md-5 mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>email</label>
                <input type="text" name="email" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-success">Register</button>
    </form>

    {{-- Table --}}
    <h5>User Permissions</h5>
    @if($permissions->count())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Staff ID</th>
                <th>Name</th>
                <th>email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permissions as $i => $item)
                <tr>
                    <td>{{ $i + $permissions->firstItem() }}</td>
                    <td>{{ $item->em_id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $permissions->links() }}
    @else
        <p>No results found.</p>
    @endif
</div>

{{-- Auto-hide alert --}}
<script>
    setTimeout(() => {
        const alert = document.getElementById('success-alert');
        if (alert) alert.style.display = 'none';
    }, 2000);
</script>
@endsection
