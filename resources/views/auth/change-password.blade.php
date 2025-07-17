@extends('layouts.app')

@section('content')
<h2>Change Password</h2>

@if(session('status'))
    <div style="color: green;">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <div>
        <label>Current Password</label><br>
        <input type="password" name="current_password" required>
        @error('current_password') <div style="color:red;">{{ $message }}</div> @enderror
    </div>

    <div>
        <label>New Password</label><br>
        <input type="password" name="new_password" required>
    </div>

    <div>
        <label>Confirm New Password</label><br>
        <input type="password" name="new_password_confirmation" required>
    </div>

    <button type="submit">Update Password</button>
</form>
@endsection
