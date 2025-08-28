@extends('layouts.app')

@section('content')
<style>
/* Light-blue theme + small UX touches */
:root{
    --blue:#1e88e5;
    --blue-dark:#1565c0;
    --blue-100:#eaf4ff;
    --blue-200:#cfe5ff;
    --border:#e5e7eb;
    --muted:#6b7280;
    --success:#16a34a;
    --danger:#dc2626;
    --warning:#d97706;
}
.container { font-family: Arial, Helvetica, sans-serif; }

.page-head{
    margin-top: 10px;
    display:flex; align-items:center; justify-content:space-between; gap:10px;
}
.page-head h4{ margin:0; }

.filter-bar{
    background:#fff; border:1px solid var(--blue-200); border-radius:10px; padding:12px;
    box-shadow:0 1px 2px rgba(0,0,0,.04);
}
.filter-actions{ display:flex; gap:8px; flex-wrap:wrap; }

.quick-btn{
    border:1px solid var(--border); background:#fff; padding:6px 10px; border-radius:8px; cursor:pointer;
}
.quick-btn:hover{ background:#edf6ff; }

.btn-primary{
    background:var(--blue); border:1px solid #0f4fa8; color:#fff; padding:8px 14px; border-radius:8px; font-weight:700;
}
.btn-primary:hover{ background:var(--blue-dark); }
.btn-ghost{ background:#fff; border:1px solid var(--border); padding:8px 14px; border-radius:8px; }

.hint{ font-size:12px; color:var(--muted); }
.hint.error{ color:var(--danger); }

/* Table */
.table thead th{ background:#eaf4ff; }
.table-sticky thead th{ position:sticky; top:0; z-index:2; }
.badge{
    display:inline-block; padding:4px 8px; border-radius:999px; font-size:12px; font-weight:700;
}
.badge-REQUESTED{ background:#e7f0ff; color:#1e3a8a; border:1px solid #bfd4ff; }
.badge-APPROVED{ background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
.badge-REJECTED{ background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }

/* Responsive tweaks */
@media (max-width: 768px){
    .page-head{ flex-direction:column; align-items:flex-start; }
    .filter-actions{ width:100%; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // --- Quick ranges ---
  const start = document.querySelector('input[name="start_date"]');
  const end   = document.querySelector('input[name="end_date"]');
  const searchBtn = document.getElementById('btnSearch');
  const drMsg = document.getElementById('drMsg');

  function fmt(d){ // yyyy-mm-dd
    const y = d.getFullYear();
    const m = String(d.getMonth()+1).padStart(2,'0');
    const day = String(d.getDate()).padStart(2,'0');
    return `${y}-${m}-${day}`;
  }
  function setRange(days){
    const today = new Date();
    const from = new Date();
    from.setDate(today.getDate() - days + 1);
    start.value = fmt(from); end.value = fmt(today);
    validateDR();
  }
  function setThisMonth(){
    const d = new Date();
    const from = new Date(d.getFullYear(), d.getMonth(), 1);
    const to   = new Date(d.getFullYear(), d.getMonth()+1, 0);
    start.value = fmt(from); end.value = fmt(to);
    validateDR();
  }

  document.getElementById('q7') ?.addEventListener('click', () => setRange(7));
  document.getElementById('q30')?.addEventListener('click', () => setRange(30));
  document.getElementById('qMon')?.addEventListener('click', setThisMonth);

  // --- Date range validation ---
  function validateDR(){
    drMsg.textContent = '';
    drMsg.className = 'hint';
    let ok = true;

    if(start.value && end.value){
      const s = new Date(start.value), e = new Date(end.value);
      if(e < s){
        drMsg.textContent = 'End date must be on/after start date.';
        drMsg.className = 'hint error';
        ok = false;
      }
    }
    if(searchBtn) searchBtn.disabled = !ok;
  }

  ['change','input'].forEach(ev => {
    start?.addEventListener(ev, validateDR);
    end  ?.addEventListener(ev, validateDR);
  });
  validateDR();

  // --- Reset filters ---
  document.getElementById('btnReset')?.addEventListener('click', (e) => {
    e.preventDefault();
    window.location = "{{ url()->current() }}";
  });

  // --- Export CSV of current table ---
  document.getElementById('btnCsv')?.addEventListener('click', () => {
    const table = document.getElementById('history-table');
    let csv = [];
    const rows = table.querySelectorAll('tr');
    rows.forEach(tr => {
      const cells = tr.querySelectorAll('th,td');
      const row = Array.from(cells).map(td => {
        const text = td.innerText.replace(/\s+/g,' ').trim();
        return `"${text.replace(/"/g,'""')}"`;
      });
      if(row.length) csv.push(row.join(','));
    });
    const blob = new Blob(["\ufeff"+csv.join('\n')], {type:'text/csv;charset=utf-8;'});
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'reservation-history.csv';
    document.body.appendChild(a); a.click(); document.body.removeChild(a);
  });
});
</script>

<div class="container">
    <div class="page-head">
        <h4>Reservation History</h4>
        <div class="filter-actions">
            <button type="button" class="quick-btn" id="q7">Last 7 days</button>
            <button type="button" class="quick-btn" id="q30">Last 30 days</button>
            <button type="button" class="quick-btn" id="qMon">This month</button>
            <button type="button" class="btn-ghost" id="btnCsv">Export CSV</button>
        </div>
    </div>

    <form method="GET" class="filter-bar mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label">REQ ID</label>
                <input type="text" name="req_id" class="form-control" value="{{ request('req_id') }}" placeholder="e.g. R000123">
            </div>

            <div class="col-md-3">
                <label class="form-label">Date Requested (from)</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">to</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                <div id="drMsg" class="hint"></div>
            </div>

            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">ALL</option>
                    @foreach (['REQUESTED','APPROVED','REJECTED'] as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button id="btnSearch" type="submit" class="btn btn-primary w-100">Search</button>
                <button id="btnReset" class="btn btn-ghost w-100">Reset</button>
            </div>
        </div>
    </form>

    @php
        $isPaginated = $reservations instanceof \Illuminate\Pagination\LengthAwarePaginator || $reservations instanceof \Illuminate\Pagination\Paginator;
        $startIndex = $isPaginated && method_exists($reservations, 'firstItem') ? ($reservations->firstItem() ?? 1) : 1;
    @endphp

    <div class="table-responsive table-sticky">
        <table id="history-table" class="table table-bordered table-hover text-center align-middle">
            <thead class="table-secondary">
                <tr>
                    <th style="width:60px">#</th>
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
                <tr>
                    <td>{{ $startIndex + $index }}</td>
                    <td class="text-nowrap">{{ $res->request_id }}</td>
                    <td class="text-nowrap">{{ optional($res->created_at)->format('d-m-Y H:i') }}</td>
                    <td class="text-start" title="{{ $res->program }}">{{ \Illuminate\Support\Str::limit($res->program, 40) }}</td>
                    <td class="text-nowrap">
                        {{ \Carbon\Carbon::parse($res->start_date)->format('d-m-Y') }}
                        {{ $res->start_time }}
                    </td>
                    <td class="text-nowrap">
                        {{ \Carbon\Carbon::parse($res->end_date)->format('d-m-Y') }}
                        {{ $res->end_time }}
                    </td>
                    <td class="text-nowrap">{{ strtoupper($res->booking_type) }}</td>
                    <td>
                        @php $st = strtoupper($res->status); @endphp
                        <span class="badge badge-{{ $st }}">{{ $st }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No reservation history found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($isPaginated)
        <div class="d-flex justify-content-between align-items-center mt-2">
            <div class="hint">
                Showing
                {{ $reservations->firstItem() ?? 0 }}
                to
                {{ $reservations->lastItem() ?? 0 }}
                of
                {{ $reservations->total() ?? $reservations->count() }}
                entries
            </div>
            <div>
                {{ $reservations->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
