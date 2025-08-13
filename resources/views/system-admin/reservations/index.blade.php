@extends('layouts.app')

@section('content')
<div class="container">
    <h4>All Reservation History</h4>

    <table class="table table-bordered table-hover text-center">
        <thead class="table-secondary">
            <tr>
                <th>#</th>
                <th>REQ ID</th>
                <th>Date Requested</th>
                <th>Program</th>
                <th>Send</th>
                <th>Fetch</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reservations as $index => $res)
                <tr class="align-middle">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $res->request_id }}</td>
                    <td>{{ $res->created_at->format('d-m-Y H:i:s') }}</td>
                    <td>{{ $res->program }}</td>
                    <td>{{ \Carbon\Carbon::parse($res->start_date)->format('d-m-Y') }} {{ $res->start_time }}</td>
                    <td>{{ \Carbon\Carbon::parse($res->end_date)->format('d-m-Y') }} {{ $res->end_time }}</td>
                    <td>{{ $res->booking_type }}</td>
                    <td>{{ $res->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No reservation history found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
