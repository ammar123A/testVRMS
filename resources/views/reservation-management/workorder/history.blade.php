@extends('layouts.app')

@section('content')
<h4 class="font-semibold text-lg mb-4">Work Order » History</h4>

<form method="GET" action="{{ route('workorder.history') }}" class="mb-6 bg-gray-100 p-4 rounded shadow">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="request_id" class="block font-medium">Request ID:</label>
            <input type="text" name="request_id" id="request_id" class="form-input w-full" value="{{ request('request_id') }}">
        </div>
        <div>
            <label for="wr_id" class="block font-medium">WR ID:</label>
            <input type="text" name="wr_id" id="wr_id" class="form-input w-full" value="{{ request('wr_id') }}">
        </div>
        <div>
            <label for="wo_id" class="block font-medium">WO ID:</label>
            <input type="text" name="wo_id" id="wo_id" class="form-input w-full" value="{{ request('wo_id') }}">
        </div>
        <div>
            <label for="date_3" class="block font-medium">Date Send (From):</label>
            <input type="date" name="date_3" id="date_3" class="form-input w-full" value="{{ request('date_3') }}">
        </div>
        <div>
            <label for="date_4" class="block font-medium">Date Send (To):</label>
            <input type="date" name="date_4" id="date_4" class="form-input w-full" value="{{ request('date_4') }}">
        </div>
        <div>
            <label for="status" class="block font-medium">Status:</label>
            <select name="status" id="status" class="form-select w-full">
                <option value="">All</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-span-3">
            <label class="block font-medium">Charted:</label>
            <div class="flex items-center space-x-4 mt-1">
                <label><input type="radio" name="charted" value="-1" {{ request('charted', '-1') == '-1' ? 'checked' : '' }}> All</label>
                <label><input type="radio" name="charted" value="0" {{ request('charted') === '0' ? 'checked' : '' }}> No</label>
                <label><input type="radio" name="charted" value="1" {{ request('charted') === '1' ? 'checked' : '' }}> Yes</label>
            </div>
        </div>
        <div class="col-span-3 mt-4">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>
</form>

@if ($workorders->count())
    <table class="table-auto w-full border-collapse border border-gray-400 text-sm">
        <thead class="bg-gray-200">
            <tr>
                <th class="border px-2 py-1">WO ID</th>
                <th class="border px-2 py-1">Date Time Assigned</th>
                <th class="border px-2 py-1">Driver Assigned / Company</th>
                <th class="border px-2 py-1">Date Send</th>
                <th class="border px-2 py-1">Charted</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($workorders as $wo)
                <tr>
                    <td class="border px-2 py-1">{{ $wo->wo_id }}</td>
                    <td class="border px-2 py-1">{{ $wo->date_time_assigned ?? '-' }}</td>
                    <td class="border px-2 py-1">
                        @foreach ($wo->drivers as $driver)
                            • {{ $driver->name }}<br>
                        @endforeach
                        @if ($wo->company)
                            <strong>Company:</strong> {{ $wo->company->name }}
                        @endif
                    </td>
                    <td class="border px-2 py-1">{{ $wo->date_send ?? '-' }}</td>
                    <td class="border px-2 py-1">{{ $wo->charted ? 'YES' : 'NO' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $workorders->withQueryString()->links() }}
    </div>
@else
    <p class="text-center text-gray-600 mt-6">No records found.</p>
@endif

@endsection
