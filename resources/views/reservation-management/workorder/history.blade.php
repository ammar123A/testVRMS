@extends('layouts.app')

@section('content')
@php
    // active filter helpers
    $q = request()->query();
    $hasFilters = collect($q)->filter(fn($v, $k) => $v !== null && $v !== '' && !in_array($k, ['page']))->isNotEmpty();

    // status counts for current page
    $pageCounts = $workorders->getCollection()
        ->groupBy(fn($w) => strtoupper((string) ($w->status ?? '—')))
        ->map->count();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-4">

    {{-- Header / actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h4 class="font-semibold text-xl">Work Order &raquo; History</h4>

        <div class="flex items-center gap-2">
            {{-- Per-page (server paginated) --}}
            <form method="GET" class="flex items-center gap-2">
                @foreach(request()->except('per_page', 'page') as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <label class="text-sm text-gray-600">Per page</label>
                <select name="per_page" class="border border-gray-300 rounded-md px-2 py-1 text-sm"
                        onchange="this.form.submit()">
                    @foreach([10,20,50,100] as $pp)
                        <option value="{{ $pp }}" {{ request('per_page', $workorders->perPage()) == $pp ? 'selected' : '' }}>
                            {{ $pp }}
                        </option>
                    @endforeach
                </select>
            </form>

            @if ($workorders->count())
            <button id="btnCsv"
                    class="inline-flex items-center px-3 py-2 bg-white text-gray-800 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-50">
                Export CSV
            </button>
            @endif
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('workorder.history') }}" class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="request_id" class="block text-sm font-medium text-gray-700">Request ID</label>
                    <input type="text" name="request_id" id="request_id"
                           class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ request('request_id') }}" placeholder="e.g. R000123">
                </div>

                <div>
                    <label for="wr_id" class="block text-sm font-medium text-gray-700">WR ID</label>
                    <input type="text" name="wr_id" id="wr_id"
                           class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ request('wr_id') }}" placeholder="e.g. WR00123">
                </div>

                <div>
                    <label for="wo_id" class="block text-sm font-medium text-gray-700">WO ID</label>
                    <input type="text" name="wo_id" id="wo_id"
                           class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ request('wo_id') }}" placeholder="e.g. WO00123">
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
                    <p id="drMsg" class="mt-1 text-xs text-gray-500"></p>
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
                    <button id="btnSearch" type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Search
                    </button>
                    <a href="{{ route('workorder.history') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-200 border border-gray-300">
                        Reset
                    </a>
                </div>
            </div>

            {{-- Active filter chips --}}
            @if ($hasFilters)
                <div class="pt-2 flex flex-wrap items-center gap-2">
                    <span class="text-xs text-gray-500">Active filters:</span>
                    @foreach (['request_id','wr_id','wo_id','date_3','date_4','status','charted'] as $key)
                        @if (($val = request($key)) !== null && $val !== '')
                            @php
                                $new = collect(request()->query())->except($key, 'page')->toArray();
                                $link = url()->current() . (count($new) ? ('?' . http_build_query($new)) : '');
                            @endphp
                            <a href="{{ $link }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs bg-blue-50 text-blue-700 border border-blue-200">
                                <span class="font-medium">{{ strtoupper($key) }}:</span> {{ $val }}
                                <span class="ml-1 text-blue-600">✕</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </form>

    {{-- Optional page summary (by status) --}}
    @if ($workorders->count())
    <div class="flex flex-wrap items-center gap-2 text-xs sm:text-sm text-gray-600">
        <span>Page totals:</span>
        @foreach ($pageCounts as $label => $count)
            <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                {{ $label }}: <span class="font-semibold">{{ $count }}</span>
            </span>
        @endforeach
    </div>
    @endif

    {{-- Table controls --}}
    @if ($workorders->count())
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600">Quick find:</label>
            <input id="quickFind" type="text" placeholder="Type to filter rows..."
                   class="w-64 border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="text-sm text-gray-600">
            Updated {{ now()->format('d M Y, H:i') }}
        </div>
    </div>
    @endif

    {{-- Table --}}
    @if ($workorders->count())
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table id="wo-table" class="min-w-full text-sm">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th data-sort="text"  class="px-3 py-2 text-left font-semibold text-gray-700 border-b cursor-pointer select-none">WO ID ▲▼</th>
                        <th data-sort="dt"    class="px-3 py-2 text-left font-semibold text-gray-700 border-b cursor-pointer select-none">Date Time Assigned ▲▼</th>
                        <th data-sort="text"  class="px-3 py-2 text-left font-semibold text-gray-700 border-b">Driver Assigned / Company</th>
                        <th data-sort="date"  class="px-3 py-2 text-left font-semibold text-gray-700 border-b cursor-pointer select-none">Date Send ▲▼</th>
                        <th data-sort="bool"  class="px-3 py-2 text-left font-semibold text-gray-700 border-b cursor-pointer select-none">Charted ▲▼</th>
                        <th data-sort="text"  class="px-3 py-2 text-left font-semibold text-gray-700 border-b cursor-pointer select-none">Status ▲▼</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($workorders as $wo)
                        @php
                            $dta = $wo->date_time_assigned ? \Illuminate\Support\Carbon::parse($wo->date_time_assigned) : null;
                            $ds  = $wo->date_send ? \Illuminate\Support\Carbon::parse($wo->date_send) : null;
                            $status = strtoupper((string)($wo->status ?? ''));
                            $badge = match($status) {
                                'OPEN', 'IN PROGRESS', 'ASSIGNED' => 'bg-blue-100 text-blue-700',
                                'COMPLETED', 'CLOSED', 'DONE'    => 'bg-green-100 text-green-700',
                                'CANCELLED', 'REJECTED'           => 'bg-red-100 text-red-700',
                                'ON HOLD', 'PENDING'              => 'bg-yellow-100 text-yellow-700',
                                default                           => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50">
                            {{-- WO ID with copy --}}
                            <td class="px-3 py-2 whitespace-nowrap font-medium text-gray-900">
                                <div class="flex items-center gap-2">
                                    <span class="wo-id">{{ $wo->wo_id }}</span>
                                    <button type="button"
                                            class="copy-wo inline-flex items-center px-1.5 py-0.5 text-xs rounded border border-gray-300 hover:bg-gray-100"
                                            title="Copy WO ID">Copy</button>
                                </div>
                            </td>

                            <td class="px-3 py-2 whitespace-nowrap text-gray-700"
                                data-value="{{ $dta ? $dta->timestamp : 0 }}">
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

                            <td class="px-3 py-2 whitespace-nowrap text-gray-700"
                                data-value="{{ $ds ? $ds->format('Y-m-d') : '' }}">
                                {{ $ds ? $ds->format('d M Y') : '—' }}
                            </td>

                            <td class="px-3 py-2 whitespace-nowrap" data-value="{{ $wo->charted ? 1 : 0 }}">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $wo->charted ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $wo->charted ? 'YES' : 'NO' }}
                                </span>
                            </td>

                            <td class="px-3 py-2 whitespace-nowrap" data-value="{{ $status }}">
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

{{-- Scripts: presets, validation, quick find, sort, CSV, copy --}}
<script>
(function(){
  const d3 = document.getElementById('date_3');
  const d4 = document.getElementById('date_4');
  const drMsg = document.getElementById('drMsg');
  const btnSearch = document.getElementById('btnSearch');

  function fmt(d){
    const z = n => String(n).padStart(2,'0');
    return d.getFullYear() + '-' + z(d.getMonth()+1) + '-' + z(d.getDate());
  }

  // Presets
  if (d3 && d4) {
    const today = new Date();
    const presets = {
      today:     [today, today],
      last7:     [new Date(Date.now() - 6*86400000), today],
      last30:    [new Date(Date.now() - 29*86400000), today],
      thisMonth: [new Date(today.getFullYear(), today.getMonth(), 1), today],
    };
    document.querySelectorAll('[data-range]').forEach(btn=>{
      btn.addEventListener('click', (e)=>{
        e.preventDefault();
        const key = btn.getAttribute('data-range');
        const pair = presets[key];
        if (!pair) return;
        d3.value = fmt(pair[0]);
        d4.value = fmt(pair[1]);
        validate();
      });
    });
  }

  // Validation
  function validate(){
    if(!d3 || !d4 || !btnSearch || !drMsg) return;
    drMsg.textContent = '';
    drMsg.className = 'mt-1 text-xs text-gray-500';
    let ok = true;
    if(d3.value && d4.value){
      const s = new Date(d3.value), e = new Date(d4.value);
      if(e < s){
        drMsg.textContent = 'End date must be on/after start date.';
        drMsg.className = 'mt-1 text-xs text-red-600';
        ok = false;
      }
    }
    btnSearch.disabled = !ok;
    btnSearch.classList.toggle('opacity-50', !ok);
    btnSearch.classList.toggle('cursor-not-allowed', !ok);
  }
  ['input','change'].forEach(ev=>{
    d3?.addEventListener(ev, validate);
    d4?.addEventListener(ev, validate);
  });
  validate();

  // Quick find (client-side)
  const qf = document.getElementById('quickFind');
  const table = document.getElementById('wo-table');
  function filterRows(){
    if(!qf || !table) return;
    const needle = qf.value.toLowerCase();
    table.querySelectorAll('tbody tr').forEach(tr=>{
      const txt = tr.innerText.toLowerCase();
      tr.style.display = txt.includes(needle) ? '' : 'none';
    });
  }
  qf?.addEventListener('input', () => { window.requestAnimationFrame(filterRows); });

  // Sort (client-side)
  if(table){
    const getVal = (td, type) => {
      const raw = td.getAttribute('data-value') || td.innerText.trim();
      if(type === 'dt')   return Number(td.getAttribute('data-value') || 0);
      if(type === 'date') return (td.getAttribute('data-value') || '');
      if(type === 'bool') return Number(td.getAttribute('data-value') || 0);
      return raw.toLowerCase();
    };
    table.querySelectorAll('thead th[data-sort]').forEach((th, idx)=>{
      let asc = true;
      th.addEventListener('click', ()=>{
        const type = th.getAttribute('data-sort');
        const rows = Array.from(table.querySelectorAll('tbody tr'));
        rows.sort((a,b)=>{
          const av = getVal(a.children[idx], type);
          const bv = getVal(b.children[idx], type);
          if(av < bv) return asc ? -1 : 1;
          if(av > bv) return asc ? 1 : -1;
          return 0;
        });
        asc = !asc;
        const tb = table.querySelector('tbody');
        rows.forEach(r=>tb.appendChild(r));
      });
    });
  }

  // CSV export of current table
  document.getElementById('btnCsv')?.addEventListener('click', ()=>{
    if(!table) return;
    const rows = table.querySelectorAll('tr');
    const csv = [];
    rows.forEach(tr=>{
      if(tr.style.display === 'none') return; // respect quick-find filter
      const cells = tr.querySelectorAll('th,td');
      if(!cells.length) return;
      const row = Array.from(cells).map(td=>{
        const text = td.innerText.replace(/\s+/g,' ').trim().replace(/"/g,'""');
        return `"${text}"`;
      });
      csv.push(row.join(','));
    });
    const blob = new Blob(["\ufeff"+csv.join('\n')], {type:'text/csv;charset=utf-8;'});
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'workorder-history.csv';
    document.body.appendChild(a); a.click(); a.remove();
  });

  // Copy WO ID
  table?.querySelectorAll('.copy-wo').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const id = btn.closest('td').querySelector('.wo-id')?.innerText?.trim();
      if(!id) return;
      navigator.clipboard?.writeText(id).then(()=>{
        const old = btn.textContent;
        btn.textContent = 'Copied!';
        setTimeout(()=> btn.textContent = old, 1200);
      });
    });
  });
})();
</script>
@endsection
