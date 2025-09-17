@extends('layouts.app')

@section('content')
<style>
:root{
  --blue:#1e88e5; --blue-dark:#1565c0; --blue-100:#eaf4ff; --blue-200:#cfe5ff;
  --border:#e5e7eb; --muted:#6b7280; --danger:#dc2626; --ok:#16a34a;
}
.container { font-family: Arial, Helvetica, sans-serif; }

/* Page head */
.page-head{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin:12px 0 8px; }
.page-head h4{ margin:0; }

/* Card */
.card{
  background:#fff; border:1px solid var(--blue-200); border-radius:10px; padding:16px 18px;
  box-shadow:0 1px 2px rgba(0,0,0,.04);
}

/* Buttons */
.btn{ padding:10px 14px; border-radius:8px; border:1px solid var(--border); cursor:pointer; }
.btn-success{ background:#10b981; color:#fff; border-color:#0d8f68; font-weight:700; }
.btn-success:hover{ filter:brightness(.95); }
.btn-secondary{ background:#fff; color:#1f2937; border:1px solid #cbd5e1; }
.btn-secondary:hover{ background:#eaf4ff; }
.btn-primary{ background:var(--blue); color:#fff; border:1px solid #0f4fa8; font-weight:700; }
.btn-primary:hover{ background:var(--blue-dark); }

/* Helper text */
.hint{ font-size:12px; color:var(--muted); margin-top:4px; }
.hint.ok{ color:var(--ok); }
.hint.error{ color:var(--danger); }

/* Table */
.table thead th{ background:var(--blue-100); }
.table-sticky thead th{ position:sticky; top:0; z-index:2; }
.tools{ display:flex; gap:8px; flex-wrap:wrap; align-items:center; margin-bottom:8px; }
.tools input{ padding:8px 10px; border:1px solid var(--border); border-radius:8px; }

@media (max-width: 600px){
  .page-head{ flex-direction:column; align-items:flex-start; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // === Form elements ===
  const plate = document.querySelector('input[name="plate_number"]');
  const date  = document.querySelector('input[name="registration_date"]');
  const submitBtn = document.getElementById('submitVehicle');

  const plateMsg = document.getElementById('plateMsg');
  const dateMsg  = document.getElementById('dateMsg');

  // Success alert auto-hide
  setTimeout(() => {
    const a = document.getElementById('success-alert');
    if(a){ a.style.display='none'; }
  }, 2500);

  // Plate: force uppercase + simple format check (letters/numbers/space/dash)
  const plateRe = /^[A-Z0-9\- ]{3,20}$/;
  function validatePlate(){
    if(!plate) return true;
    plate.value = plate.value.toUpperCase();
    if(!plate.value){
      plateMsg.textContent = '';
      plateMsg.className = 'hint';
      return false;
    }
    if(!plateRe.test(plate.value)){
      plateMsg.textContent = 'Use letters/numbers, space or dash (3–20 chars).';
      plateMsg.className = 'hint error';
      return false;
    }
    plateMsg.textContent = 'Looks good.';
    plateMsg.className = 'hint ok';
    return true;
  }
  plate?.addEventListener('input', validatePlate);

  // Date: cannot be in the future
  function todayStr(){
    const d = new Date(); const pad = n=>String(n).padStart(2,'0');
    return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
  }
  function validateDate(){
    if(!date) return true;
    if(!date.value){ dateMsg.textContent = ''; dateMsg.className='hint'; return false; }
    const max = todayStr();
    if(date.value > max){
      dateMsg.textContent = 'Registration date cannot be in the future.';
      dateMsg.className   = 'hint error';
      return false;
    }
    dateMsg.textContent = '';
    dateMsg.className='hint';
    return true;
  }
  date?.addEventListener('change', validateDate);
  date?.addEventListener('input',  validateDate);

  document.getElementById('btnToday')?.addEventListener('click', (e)=>{
    e.preventDefault(); if(!date) return; date.value = todayStr(); validateDate();
  });

  // Gate submit
  function gate(){
    const ok = validatePlate() & validateDate(); // bitwise to run both
    if(submitBtn) submitBtn.disabled = !ok;
  }
  ['input','change','keyup'].forEach(ev=>{
    plate?.addEventListener(ev, gate);
    date ?.addEventListener(ev, gate);
  });
  gate();

  // === Table tools ===
  const tf = document.getElementById('vehicleFind');
  const table = document.getElementById('veh-table');
  function filterRows(){
    if(!tf || !table) return;
    const q = tf.value.toLowerCase();
    table.querySelectorAll('tbody tr').forEach(tr=>{
      const txt = tr.innerText.toLowerCase();
      tr.style.display = txt.includes(q) ? '' : 'none';
    });
  }
  tf?.addEventListener('input', ()=> requestAnimationFrame(filterRows));

  // CSV export
  document.getElementById('btnCsv')?.addEventListener('click', ()=>{
    if(!table) return;
    const rows = table.querySelectorAll('tr');
    const csv = [];
    rows.forEach(tr=>{
      if(tr.style.display === 'none') return;
      const cells = tr.querySelectorAll('th,td');
      if(!cells.length) return;
      const row = Array.from(cells).map(td=> `"${td.innerText.replace(/\s+/g,' ').trim().replace(/"/g,'""')}"` );
      csv.push(row.join(','));
    });
    const blob = new Blob(["\ufeff"+csv.join('\n')], {type:'text/csv;charset=utf-8;'});
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'vehicles.csv';
    document.body.appendChild(a); a.click(); a.remove();
  });
});
</script>

<div class="container">
  <div class="page-head">
    <h4>Register New Vehicle</h4>
  </div>

  @if(session('success'))
    <div id="success-alert" class="alert alert-success mt-2">{{ session('success') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger mt-2">
      <strong>There were some problems:</strong>
      <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
  @endif

  <div class="card mb-4">
    <form action="{{ route('vehicle.store') }}" method="POST" novalidate>
      @csrf
      <div class="row g-3">
        <div class="col-md-4">
          <label for="plate_number" class="form-label">Plate Number</label>
          <input type="text" name="plate_number" class="form-control @error('plate_number') is-invalid @enderror"
                 value="{{ old('plate_number') }}" required>
          <div id="plateMsg" class="hint"></div>
          @error('plate_number') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label for="model" class="form-label">Model</label>
          <input type="text" name="model" class="form-control @error('model') is-invalid @enderror"
                 value="{{ old('model') }}" required>
          @error('model') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label for="type" class="form-label">Type</label>
          <select name="type" class="form-select @error('type') is-invalid @enderror" required>
            <option value="">-- Choose Type --</option>
            @foreach (['CAR','VAN','BUS','4WD'] as $opt)
              <option value="{{ $opt }}" {{ old('type') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
          </select>
          @error('type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="department" class="form-label">Faculty / Department</label>
          <select name="department" class="form-select @error('department') is-invalid @enderror" required>
            <option value="">-- Choose Department --</option>
            @foreach (['Fakulti Komputeran','Fakulti Matematik','Fakulti Mekatronik','Fakulti Elektrik'] as $dep)
              <option value="{{ $dep }}" {{ old('department') === $dep ? 'selected' : '' }}>{{ $dep }}</option>
            @endforeach
          </select>
          @error('department') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="registration_date" class="form-label">Registration Date</label>
          <div class="input-group">
            <input type="date" name="registration_date"
                   class="form-control @error('registration_date') is-invalid @enderror"
                   value="{{ old('registration_date') }}" required>
            <button id="btnToday" type="button" class="btn btn-secondary">Today</button>
          </div>
          <div id="dateMsg" class="hint"></div>
          @error('registration_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="mt-3 d-flex gap-2">
        <button id="submitVehicle" type="submit" class="btn btn-success">Register</button>
        <a href="{{ route('vehicle.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>

  {{-- Registered list --}}
  <div class="page-head" style="margin-top:18px;">
    <h4>Registered Vehicles</h4>
    @if (count($vehicles))
      <div class="tools">
        <input id="vehicleFind" type="text" placeholder="Quick find…">
        <button id="btnCsv" type="button" class="btn btn-primary">Export CSV</button>
      </div>
    @endif
  </div>

  <div class="table-responsive table-sticky">
    <table id="veh-table" class="table table-bordered table-striped align-middle">
      <thead>
        <tr>
          <th>Plate Number</th>
          <th>Model</th>
          <th>Type</th>
          <th>Department</th>
          <th>Registration Date</th>
        </tr>
      </thead>
      <tbody>
        @forelse($vehicles as $vehicle)
          <tr>
            <td>{{ strtoupper($vehicle->plate_number) }}</td>
            <td>{{ $vehicle->model }}</td>
            <td>{{ strtoupper($vehicle->type) }}</td>
            <td>{{ $vehicle->department }}</td>
            <td>
              @php
                try { $dt = \Carbon\Carbon::parse($vehicle->registration_date); }
                catch (\Exception $e) { $dt = null; }
              @endphp
              {{ $dt ? $dt->format('d-m-Y') : $vehicle->registration_date }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted">No vehicles registered yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>
@endsection
