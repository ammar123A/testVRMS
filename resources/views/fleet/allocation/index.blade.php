@extends('layouts.app')

@section('content')
<style>
:root{
    --blue:#1e88e5;
    --blue-dark:#1565c0;
    --blue-100:#eaf4ff;
    --blue-200:#cfe5ff;
    --border:#e5e7eb;
    --muted:#6b7280;
}
.container { font-family: Arial, Helvetica, sans-serif; }

/* Header */
.page-head{
    margin-top: 10px;
    display:flex; align-items:center; justify-content:space-between; gap:10px;
}
.page-head h4{ margin:0; }
.actions{ display:flex; gap:8px; flex-wrap:wrap; }
.btn{
    padding:8px 14px; border-radius:8px; cursor:pointer; border:1px solid var(--border); background:#fff;
}
.btn-primary{ background:var(--blue); color:#fff; border:1px solid #0f4fa8; font-weight:700; }
.btn-primary:hover{ background:var(--blue-dark); }
.btn-success{ background:#10b981; color:#fff; border:1px solid #0d8f68; font-weight:700; }
.btn-success:hover{ filter:brightness(0.95); }

/* Filters */
.filter-bar{
    background:#fff; border:1px solid var(--blue-200); border-radius:10px; padding:12px;
    box-shadow:0 1px 2px rgba(0,0,0,.04); margin-top:10px;
}
.quick-btn{ border:1px solid var(--border); background:#fff; padding:6px 10px; border-radius:8px; cursor:pointer; }
.quick-btn:hover{ background:#edf6ff; }
.hint{ font-size:12px; color:var(--muted); }
.hint.error{ color:#dc2626; }

/* Table */
.table thead th{ background:var(--blue-100); }
.table-sticky thead th{ position:sticky; top:0; z-index:2; }
.total-line{
    display:flex; justify-content:flex-end; gap:12px; margin:8px 0; color:#111; font-weight:700;
}
@media (max-width: 768px){
    .page-head{ flex-direction:column; align-items:flex-start; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Quick date ranges
  const s = document.querySelector('input[name="start_date"]');
  const e = document.querySelector('input[name="end_date"]');
  const drMsg = document.getElementById('drMsg');
  const searchBtn = document.getElementById('btnSearch');

  function fmt(d){ const y=d.getFullYear(), m=String(d.getMonth()+1).padStart(2,'0'), da=String(d.getDate()).padStart(2,'0'); return `${y}-${m}-${da}`; }
  function setRange(days){
    const today = new Date();
    const from = new Date();
    from.setDate(today.getDate() - days + 1);
    s.value = fmt(from); e.value = fmt(today); validateDR();
  }
  function setThisMonth(){
    const d = new Date();
    const from = new Date(d.getFullYear(), d.getMonth(), 1);
    const to   = new Date(d.getFullYear(), d.getMonth()+1, 0);
    s.value = fmt(from); e.value = fmt(to); validateDR();
  }
  document.getElementById('q7') ?.addEventListener('click', () => setRange(7));
  document.getElementById('q30')?.addEventListener('click', () => setRange(30));
  document.getElementById('qMon')?.addEventListener('click', setThisMonth);

  // Validate date range
  function validateDR(){
    drMsg.textContent=''; drMsg.className='hint';
    let ok = true;
    if(s.value && e.value){
      const sd = new Date(s.value), ed = new Date(e.value);
      if(ed < sd){ drMsg.textContent='End date must be on/after start date.'; drMsg.className='hint error'; ok = false; }
    }
    if(searchBtn) searchBtn.disabled = !ok;
  }
  ['input','change'].forEach(ev => { s?.addEventListener(ev, validateDR); e?.addEventListener(ev, validateDR); });
  validateDR();

  // Reset filters
  document.getElementById('btnReset')?.addEventListener('click', (ev) => {
    ev.preventDefault();
    window.location = "{{ url()->current() }}";
  });

  // Export CSV of current table
  document.getElementById('btnCsv')?.addEventListener('click', () => {
    const table = document.getElementById('altn-table');
    let csv = [];
    table.querySelectorAll('tr').forEach(tr => {
      const cells = tr.querySelectorAll('th,td');
      const row = Array.from(cells).map(td => `"${td.innerText.replace(/\s+/g,' ').trim().replace(/"/g,'""')}"`);
      if(row.length) csv.push(row.join(','));
    });
    const blob = new Blob(["\ufeff"+csv.join('\n')], {type:'text/csv;charset=utf-8;'});
    const a = document.createElement('a'); a.href = URL.createObjectURL(blob);
    a.download = 'allocation-list.csv'; document.body.appendChild(a); a.click(); a.remove();
  });
});
</script>

<div class="container">
    <div class="page-head">
        <h4>Fleet Management &raquo; Allocation List</h4>
        <div class="actions">
            <a href="{{ route('allocation.create') }}" class="btn btn-success">Create New Allocation</a>
            <button type="button" class="btn" id="btnCsv">Export CSV</button>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="filter-bar">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">PHB</label>
                <input type="text" name="altn_phb" class="form-control" value="{{ request('altn_phb') }}" placeholder="e.g. PHB-01">
            </div>
            <div class="col-md-3">
                <label class="form-label">Category</label>
                <input type="text" name="altn_category" class="form-control" value="{{ request('altn_category') }}" placeholder="e.g. BUS / VAN">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date (from)</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">to</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                <div id="drMsg" class="hint"></div>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button id="btnSearch" type="submit" class="btn btn-primary w-100">Search</button>
                <button id="btnReset" class="btn w-100">Reset</button>
            </div>
        </div>

        <div class="mt-2" style="display:flex; gap:8px; flex-wrap:wrap;">
            <span class="hint">Quick ranges:</span>
            <button type="button" class="quick-btn" id="q7">Last 7 days</button>
            <button type="button" class="quick-btn" id="q30">Last 30 days</button>
            <button type="button" class="quick-btn" id="qMon">This month</button>
        </div>
    </form>

    @php
        $isPaginated = $allocations instanceof \Illuminate\Pagination\AbstractPaginator;
        $collection  = $isPaginated ? $allocations->getCollection() : collect($allocations);
        $pageTotal   = $collection->sum('altn_allocation');
        $startIndex  = $isPaginated && method_exists($allocations, 'firstItem') ? ($allocations->firstItem() ?? 1) : 1;
    @endphp

    {{-- Total for current result set (page) --}}
    <div class="total-line">
        <div>Total (RM):</div>
        <div>{{ number_format($pageTotal, 2) }}</div>
    </div>

    <div class="table-responsive table-sticky">
        <table id="altn-table" class="table table-bordered table-hover text-center align-middle">
            <thead class="table-secondary">
                <tr>
                    <th style="width:60px">#</th>
                    <th>Date</th>
                    <th>PHB</th>
                    <th>Category</th>
                    <th>Allocation (RM)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allocations as $index => $al)
                    <tr>
                        <td>{{ $startIndex + $index }}</td>
                        <td class="text-nowrap">{{ \Carbon\Carbon::parse($al->altn_date)->format('d-m-Y') }}</td>
                        <td class="text-nowrap">{{ $al->altn_phb }}</td>
                        <td class="text-nowrap">{{ $al->altn_category }}</td>
                        <td class="text-nowrap">{{ number_format($al->altn_allocation, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted">No allocations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($isPaginated)
        <div class="d-flex justify-content-between align-items-center mt-2">
            <div class="hint">
                Showing {{ $allocations->firstItem() ?? 0 }}
                to {{ $allocations->lastItem() ?? 0 }}
                of {{ $allocations->total() ?? $allocations->count() }} entries
            </div>
            <div>
                {{ $allocations->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
