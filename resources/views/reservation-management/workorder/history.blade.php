@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <h4 class="font-semibold text-xl mb-4">Work Order &raquo; History</h4>

    {{-- Filters --}}
    <form method="GET" action="{{ route('workorder.history') }}" class="mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="request_id" class="block text-sm font-medium text-gray-700">Request ID</label>
                    <input type="text" name="request_id" id="request_id"
                           class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ request('request_id') }}">
                </div>

                <div>
                    <label for="wr_id" class="block text-sm font-medium text-gray-700">WR ID</label>
                    <input type="text" name="wr_id" id="wr_id"
                           class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ request('wr_id') }}">
                </div>

                <div>
                    <label for="wo_id" class="block text-sm font-medium text-gray-700">WO ID</label>
                    <input type="text" name="wo_id" id="wo_id"
                           class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ request('wo_id') }}">
                </div>

                <div>
                    <label for="date_3" class="block text-sm font-medium text-gray-700">Date Send (From)</label>
                    <input type="date" name="date_3" id="date_3"
                           class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ request('date_3') }}">
                </div>

                <div>
                    <label for="date_4" class="block text-sm font-medium text-gray-700">Date Send (To)</label>
                    <input type="date" name="date_4" id="date_4"
                           class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ request('date_4') }}">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status"
                            class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-3">
                    <span class="block text-sm font-medium text-gray-700 mb-1">Charted</span>
                    <div class="flex items-center gap-4 text-sm">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="charted" value="-1" class="text-blue-600 border-gray-300"
                                   {{ request('charted', '-1') == '-1' ? 'checked' : '' }}>
                            <span>All</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="charted" value="0" class="text-blue-600 border-gray-300"
                                   {{ request('charted') === '0' ? 'checked' : '' }}>
                            <span>No</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="charted" value="1" class="text-blue-600 border-gray-300"
                                   {{ request('charted') === '1' ? 'checked' : '' }}>
                            <span>Yes</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Date presets + Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between pt-2">
                <div class="flex flex-wrap items-center gap-2 text-xs sm:text-sm">
                    <span class="text-gray-500">Quick ranges:</span>
                    <button type="button" data-range="today"
                            class="px-2.5 py-1 border border-gray-300 rounded hover:bg-gray-50">Today</button>
                    <button type="button" data-range="last7"
                            class="px-2.5 py-1 border border-gray-300 rounded hover:bg-gray-50">Last 7 days</button>
                    <button type="button" data-range="last30"
                            class="px-2.5 py-1 border border-gray-300 rounded hover:bg-gray-50">Last 30 days</button>
                    <button type="button" data-range="thisMonth"
                            class="px-2.5 py-1 border border-gray-300 rounded hover:bg-gray-50">This month</button>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Search
                    </button>
                    <a href="{{ route('workorder.history') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-200 border border-gray-300">
                        Reset
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- Results summary --}}
    @if ($workorders->count())
        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
            @php
                $from = ($workorders->currentPage() - 1) * $workorders->perPage() + 1;
                $to   = $from + $workorders->count() - 1;
            @endphp
            <div>Showing <span class="font-medium">{{ $from }}</span>–<span class="font-medium">{{ $to }}</span> of <span class="font-medium">{{ $workorders->total() }}</span></div>
            <div class="hidden sm:block">Updated {{ now()->format('d M Y, H:i') }}</div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b">WO ID</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b">Date Time Assigned</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b">Driver Assigned / Company</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b">Date Send</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b">Charted</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700 border-b">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($workorders as $wo)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 whitespace-nowrap font-medium text-gray-900">
                                {{ $wo->wo_id }}
                            </td>

                            <td class="px-3 py-2 whitespace-nowrap text-gray-700">
                                @php
                                    $dta = $wo->date_time_assigned ? \Illuminate\Support\Carbon::parse($wo->date_time_assigned) : null;
                                @endphp
                                {{ $dta ? $dta->format('d M Y, H:i') : '—' }}
                            </td>

                            <td class="px-3 py-2 text-gray-700">
                                @if ($wo->drivers && count($wo->drivers))
                                    {{ $wo->drivers->pluck('name')->join(', ') }}
                                @else
                                    —
                                @endif
                                @if ($wo->company)
                                    <div class="text-xs text-gray-500">
                                        <span class="font-semibold">Company:</span> {{ $wo->company->name }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-3 py-2 whitespace-nowrap text-gray-700">
                                @php
                                    $ds = $wo->date_send ? \Illuminate\Support\Carbon::parse($wo->date_send) : null;
                                @endphp
                                {{ $ds ? $ds->format('d M Y') : '—' }}
                            </td>

                            <td class="px-3 py-2 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $wo->charted ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $wo->charted ? 'YES' : 'NO' }}
                                </span>
                            </td>

                            <td class="px-3 py-2 whitespace-nowrap">
                                @php
                                    $status = strtoupper((string)($wo->status ?? ''));
                                    $badge = match($status) {
                                        'OPEN', 'IN PROGRESS', 'ASSIGNED' => 'bg-blue-100 text-blue-700',
                                        'COMPLETED', 'CLOSED', 'DONE'    => 'bg-green-100 text-green-700',
                                        'CANCELLED', 'REJECTED'           => 'bg-red-100 text-red-700',
                                        'ON HOLD', 'PENDING'              => 'bg-yellow-100 text-yellow-700',
                                        default                           => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                    {{ $status ?: '—' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $workorders->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center text-gray-600 mt-10">
            <p class="mb-3">No records found.</p>
            <a href="{{ route('workorder.history') }}" class="text-blue-600 hover:underline">Reset all filters</a>
        </div>
    @endif

</div>

{{-- Date preset script (vanilla JS) --}}
<script>
(function(){
  const d3 = document.getElementById('date_3');
  const d4 = document.getElementById('date_4');
  if (!d3 || !d4) return;

  function fmt(d){
    const z = n => String(n).padStart(2,'0');
    return d.getFullYear() + '-' + z(d.getMonth()+1) + '-' + z(d.getDate());
  }
  const today = new Date();
  const presets = {
    'today':     [today, today],
    'last7':     [new Date(Date.now() - 6*86400000), today],
    'last30':    [new Date(Date.now() - 29*86400000), today],
    'thisMonth': [new Date(today.getFullYear(), today.getMonth(), 1), today],
  };
  document.querySelectorAll('[data-range]').forEach(btn=>{
    btn.addEventListener('click', (e)=>{
      e.preventDefault();
      const key = btn.getAttribute('data-range');
      const pair = presets[key];
      if (!pair) return;
      d3.value = fmt(pair[0]);
      d4.value = fmt(pair[1]);
    });
  });
})();
</script>
@endsection
