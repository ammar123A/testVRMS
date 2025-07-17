<table class="table">
    <thead>
        <tr>
            <th>WO ID</th>
            <th>Date Time Assigned</th>
            <th>Driver Assigned/Company</th>
            <th>Date Send</th>
            <th>Charted</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($workorders as $wo)
            <tr>
                <td>{{ $wo->wo_id }}</td>
                <td>{{ $wo->datetime_assigned }}</td>
                <td>
                    @foreach($wo->drivers as $driver)
                        • {{ $driver->name }}<br>
                    @endforeach
                    {{ $wo->company->name ?? '' }}
                </td>
                <td>{{ $wo->date_send }}</td>
                <td>{{ $wo->charted ? 'YES' : 'NO' }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No records found.</td></tr>
        @endforelse
    </tbody>
</table>

@if ($workorders instanceof \Illuminate\Pagination\Paginator || $workorders instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $workorders->links() }}
@endif

