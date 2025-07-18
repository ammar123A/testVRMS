@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Supervisor » Charted Company</h4>

    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search --}}
    <form method="GET" action="{{ route('company.index') }}" class="row g-3 mb-4">
        <div class="col-md-6">
            <input type="text" name="name" class="form-control" placeholder="Search by Name" value="{{ request('name') }}">
        </div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    {{-- Register New Company --}}
    <form method="POST" action="{{ route('company.store') }}" class="border p-4 mb-4">
        @csrf
        <h5>Register New Company</h5>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>ROC</label>
                <input type="text" name="roc" class="form-control">
            </div>
            <div class="col-md-6">
                <label>Contact Person</label>
                <input type="text" name="contact_person" class="form-control">
            </div>
            <div class="col-md-6">
                <label>Tel</label>
                <input type="text" name="tel" class="form-control">
            </div>
            <div class="col-md-6">
                <label>Mobile</label>
                <input type="text" name="mobile" class="form-control">
            </div>
            <div class="col-md-6">
                <label>Fax</label>
                <input type="text" name="fax" class="form-control">
            </div>
            <div class="col-md-12">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-md-6">
                <label>State</label>
                <input type="text" name="state" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-success">Register</button>
    </form>

    {{-- Company List --}}
    <h5>Company List</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Last Updated</th>
                <th>Name</th>
                <th>Tel</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companies as $i => $company)
                <tr>
                    <td>{{ $i + $companies->firstItem() }}</td>
                    <td>{{ $company->updated_at }}</td>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->tel }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $companies->links() }}
</div>

<script>
    setTimeout(() => {
        let alert = document.getElementById('success-alert');
        if (alert) alert.style.display = 'none';
    }, 2000);
</script>
@endsection
