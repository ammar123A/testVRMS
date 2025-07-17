@if($results->isEmpty())
    <p>No results found.</p>
@else
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>REQ ID</th>
                <th>Date Time Requested</th>
                <th>Requestor Type</th>
                <th>Program</th>
                <th>Send</th>
                <th>Fetch</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->request_id }}</td>
                <td>{{ $row->datetime_requested }}</td>
                <td>{{ $row->user_type }}</td>
                <td>{{ $row->booking_program }}</td>
                <td>{{ $row->datetime_pickup }}</td>
                <td>{{ $row->datetime_fetch }}</td>
                <td>{{ $row->booking_type }}</td>
                <td>{{ $row->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
