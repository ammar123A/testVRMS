@if($complaints->count())
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
            @foreach($complaints as $index => $complaint)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $complaint->id }}</td>
                    <td>{{ $complaint->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $complaint->type }}</td>
                    <td>{{ $complaint->plate_number }}</td>
                    <td>{{ $complaint->complaint }}</td>
                    <td>{{ $complaint->status ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-info">No record found.</div>
@endif
