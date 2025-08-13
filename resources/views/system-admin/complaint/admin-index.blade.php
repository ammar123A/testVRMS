@extends('layouts.app')

@section('content')
<div class="container">
    <h4>All Reservation / Complaint History</h4>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>REQ ID</th>
                <th>Date Requested</th>
                <th>Type</th>
                <th>Registration No</th>
                <th>Complaint</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $index => $complaint)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $complaint->id }}</td>
                    <td>{{ optional($complaint->created_at)->format('d-m-Y H:i') }}</td>
                    <td>{{ $complaint->type }}</td>

                    <td>{{ $complaint->vehicle?->plate_number ?? $complaint->plate_number }}</td>

                    <td>{{ $complaint->complaint }}</td>
                    <td>{{ $complaint->status ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- if you use paginate() in controller --}}
    {{-- {{ $complaints->links() }} --}}
</div>
@endsection
