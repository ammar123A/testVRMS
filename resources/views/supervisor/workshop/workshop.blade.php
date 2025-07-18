@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Supervisor » Workshop</h4>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success" id="flash-msg">{{ session('success') }}</div>
    @endif

    {{-- Register Form --}}
    <form method="POST" action="{{ route('workshop.store') }}">
        @csrf
        <div class="row mb-3">
            <div class="col">
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}" required>
            </div>
            <div class="col">
                <input type="text" name="contact_person" class="form-control" placeholder="Contact Person" value="{{ old('contact_person') }}">
            </div>
            <div class="col">
                <input type="text" name="tel" class="form-control" placeholder="Tel" value="{{ old('tel') }}">
            </div>
            <div class="col">
                <input type="text" name="fax" class="form-control" placeholder="Fax" value="{{ old('fax') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">Register</button>
            </div>
        </div>
    </form>

    {{-- Search --}}
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col">
                <input type="text" name="search" class="form-control" placeholder="Search name..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-secondary">Search</button>
            </div>
        </div>
    </form>

    {{-- View Table --}}
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Last Updated</th>
                <th>Name</th>
                <th>Tel</th>
                <th>Fax</th>
            </tr>
        </thead>
        <tbody>
        @forelse($workshops as $index => $ws)
            <tr>
                <td>{{ $loop->iteration + ($workshops->currentPage() - 1) * $workshops->perPage() }}</td>
                <td>{{ $ws->updated_at->format('d-m-Y H:i:s') }}</td>
                <td>{{ $ws->name }}</td>
                <td>{{ $ws->tel }}</td>
                <td>{{ $ws->fax }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No workshops found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $workshops->links() }}
</div>

{{-- Flash Message Auto-hide --}}
<script>
    setTimeout(() => {
        const msg = document.getElementById('flash-msg');
        if (msg) msg.remove();
    }, 2000);
</script>
@endsection
