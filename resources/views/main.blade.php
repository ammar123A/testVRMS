@extends('layouts.app')

@section('content')

<div id="Menu">
    <div class="clock"></div>
</div>

<div class="vrms-profile">
    <div class="header">
        <h4 class="title">Profile {{ ucfirst(auth()->user()->role) }}</h4>
        <a href="{{ url('/change-password') }}" class="btn-primary">Change Password</a>
    </div>

    <div class="card">
        @if(auth()->user()->role === 'staff')
            <dl class="details">
                <dt>Username</dt>
                <dd>{{ auth()->user()->username }}</dd>

                <dt>Name</dt>
                <dd>{{ auth()->user()->name }}</dd>

                <dt>Staff No</dt>
                <dd>{{ auth()->user()->em_id ?? '-' }}</dd>

                <dt>Faculty</dt>
                <dd>{{ auth()->user()->faculty ?? '-' }}</dd>

                <dt>Department</dt>
                <dd>{{ auth()->user()->department ?? '-' }}</dd>

                <dt>Unit</dt>
                <dd>{{ auth()->user()->unit ?? '-' }}</dd>

                <dt>Designation</dt>
                <dd>{{ auth()->user()->designation ?? '-' }}</dd>

                <dt>Campus</dt>
                <dd>{{ strtoupper(strtolower(auth()->user()->campus ?? '-')) }}</dd>
            </dl>
        @else
            <dl class="details">
                <dt>Username</dt>
                <dd>{{ auth()->user()->username }}</dd>

                <dt>Name</dt>
                <dd>{{ auth()->user()->name }}</dd>

                <dt>Matric No</dt>
                <dd>{{ auth()->user()->em_id ?? '-' }}</dd>

                <dt>Department / Faculty</dt>
                <dd>{{ auth()->user()->department ?? '-' }} / {{ auth()->user()->faculty ?? '-' }}</dd>

                <dt>Campus</dt>
                <dd>{{ strtoupper(strtolower(auth()->user()->campus ?? '-')) }}</dd>
            </dl>
        @endif
    </div>

    <p class="muted">
        © {{ now()->year }} Universiti Teknologi Malaysia — Ver 2.0
    </p>
</div>

<style>
/* Theme */
:root{
    --purple:#2b0054;
    --purple-700:#4a0b8f;
    --border:#e5e7eb;
    --muted:#6b7280;
}

/* Page shell */
.vrms-profile{
    max-width: 1024px;
    margin: 18px auto;
    padding: 0 16px;
    font-family: Arial, Helvetica, sans-serif;
}

/* Header */
.vrms-profile .header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:14px;
}
.vrms-profile .title{
    margin:0;
    font-size:22px;
    color:#222;
}

/* Button */
.vrms-profile .btn-primary{
    display:inline-block;
    background:var(--purple);
    color:#fff !important;
    text-decoration:none;
    padding:8px 14px;
    border:1px solid #1a0036;
    border-radius:8px;
    font-weight:700;
    line-height:1.2;
}
.vrms-profile .btn-primary:hover{ background:var(--purple-700); }

/* Card */
.vrms-profile .card{
    background:#fff;
    border:1px solid var(--border);
    border-radius:10px;
    padding:18px 20px;
    box-shadow:0 1px 3px rgba(0,0,0,.06);
}

/* Details grid */
.vrms-profile .details{
    margin:0;
    display:grid;
    grid-template-columns: 220px 1fr;
    column-gap:16px;
    row-gap:10px;
}
.vrms-profile .details dt{
    margin:0;
    font-weight:600;
    color:#374151;
}
.vrms-profile .details dd{
    margin:0;
    color:#111827;
}
.vrms-profile .card a{
    color:#1a46d8;
    text-decoration:none;
}
.vrms-profile .card a:hover{ text-decoration:underline; }

.vrms-profile .muted{
    color:var(--muted);
    font-size:12px;
    margin-top:14px;
}

/* Mobile */
@media (max-width: 640px){
    .vrms-profile .details{
        grid-template-columns: 1fr;
    }
    .vrms-profile .header{
        flex-direction:column;
        align-items:flex-start;
    }
    .vrms-profile .btn-primary{
        width:100%;
        text-align:center;
    }
}
</style>
@endsection
