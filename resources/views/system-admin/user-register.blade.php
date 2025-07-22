@extends('layouts.app')

@section('content')
<div class="container">
    <h4>System Administration &raquo; Register User</h4>

    <form action="{{ route('system-admin.user.store') }}" method="POST">
        @csrf

        {{-- Staff Search --}}
        <div class="mb-4">
            <label>Search User (min 3 chars):</label>
            <input type="text" id="searchUser" name="search" class="form-control" autocomplete="off">
            <div id="searchResults"></div>
        </div>

        {{-- User Info --}}
        <div class="card mb-3">
            <div class="card-header">User Info</div>
            <div class="card-body">
                <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" class="form-control mb-2">
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" class="form-control mb-2">
                <input type="text" name="em_id" value="{{ old('em_id') }}" placeholder="Staff No." class="form-control mb-2">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="form-control mb-2">
                <input type="text" name="faculty" value="{{ old('faculty') }}" placeholder="Department" class="form-control">
            </div>
        </div>

        {{-- Role Privileges --}}
        <div class="card mb-3">
            <div class="card-header">Assign Roles</div>
            <div class="card-body">
                @foreach ($roles as $role)
                    <div class="mb-2">
                        <input type="radio" name="role" value="{{ $role }}" id="role_{{ $role }}">
                        <label for="role_{{ $role }}">{{ ucfirst($role) }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Submit --}}
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('system-admin.user.register') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>

{{-- Include JS to handle user search --}}
<script>
document.getElementById('searchUser').addEventListener('keyup', function() {
    if (this.value.length < 3) return;

    fetch(`/system-admin/user-search?input=${this.value}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('searchResults').innerHTML = html;
        });
});
</script>
@endsection
