@if($vehicles->count())
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Last Updated</th>
            <th>Faculty / Department</th>
            <th>Type</th>
            <th>Registration No.</th>
            <th>Model</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vehicles as $index => $vehicle)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $vehicle->updated_at }}</td>
                <td>{{ $vehicle->department->name ?? '-' }}</td>
                <td>{{ $vehicle->type }}</td>
                <td>{{ $vehicle->registration_no }}</td>
                <td>{{ $vehicle->model }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $vehicles->links() }}
@else
<p>No records found.</p>
@endif
