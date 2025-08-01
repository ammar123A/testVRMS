@extends('layouts.app')

@section('content')
<style>
    .td-left-search {
        width: 15%;
    }
    .td-right-search {
        width: 35%;
    }
    #campus label {
        padding-left: 5px;
        padding-right: 10px;
        font-weight: bold;
        font-style: italic;
    }
</style>

<div class="title">System Administration &raquo; User</div>

<form method="GET" action="{{ route('system-admin.user') }}">
    <div class="search-box">
        <div class="content">
            <table cellpadding="5" cellspacing="3" width="100%">
                <tr>
                    <td class="td-left-search">Username:</td>
                    <td class="td-right-search">
                        <input type="text" name="username" size="20" value="{{ request('username') }}">
                    </td>
                    <td class="td-left-search">Name:</td>
                    <td class="td-right-search">
                        <input type="text" name="name" size="50" value="{{ request('name') }}">
                    </td>
                </tr>
                <tr>
                    <td class="td-left-search">Role:</td>
                    <td class="td-right-search">
                        <select name="role">
                            <option value=""></option>
                            @foreach ($roles as $role)
                                @if ($role !== 'DRIVER')
                                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                                @endif
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="td-left-search">Campus:</td>
                    <td class="td-right-search" colspan="3">
                        <div id="campus">
                            <input type="radio" name="site_id" value="" {{ request('site_id') == '' ? 'checked' : '' }}><label>ALL</label>
                            <input type="radio" name="site_id" value="UTM" {{ request('site_id') == 'UTM' ? 'checked' : '' }}><label>UNIVERSITI TEKNOLOGI MALAYSIA</label>
                            <input type="radio" name="site_id" value="UTMIC" {{ request('site_id') == 'UTMIC' ? 'checked' : '' }}><label>UTM (KAMPUS ANTARABANGSA)</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td-left-search">&nbsp;</td>
                    <td class="td-right-search">
                        <button type="submit">Search...</button>
                        <a href="{{ route('system-admin.user.register') }}">
                            <button type="button">Register</button>
                        </a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>

<br>

@if($users->count())
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Name</th>
                <th>Department / Faculty & Campus</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->faculty ?? '-' }}</td>
                <td>{{ $user->role ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <div style="margin-top: 20px;">No users found based on the search criteria.</div>
@endif
@endsection
