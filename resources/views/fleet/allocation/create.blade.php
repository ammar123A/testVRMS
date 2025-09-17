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
    --danger:#dc2626;
    --ok:#16a34a;
}
.container { font-family: Arial, Helvetica, sans-serif; }

/* Header */
.page-head{
    margin-top: 10px;
    display:flex; align-items:center; justify-content:space-between; gap:10px;
}
.page-head h4{ margin:0; }

/* Card */
.card{
    background:#fff; border:1px solid var(--blue-200); border-radius:10px; padding:16px 18px;
    box-shadow:0 1px 2px rgba(0,0,0,.04); margin-top:12px;
}

/* Buttons */
.btn{ padding:10px 14px; border-radius:8px; cursor:pointer; border:1px solid var(--border); background:#fff; }
.btn-primary{ background:var(--blue); color:#fff; border:1px solid #0f4fa8; font-weight:700; }
.btn-primary:hover{ background:var(--blue-dark); }

/* >>> Visibility fix for secondary buttons (Back / Today) */
a.btn, button.btn { color:#111 !important; }
.btn-secondary{
    background:#fff !important;
    color:#1f2937 !important;
    border:1px solid #cbd5e1 !important;
}
.btn-secondary:hover{
    background:#eaf4ff !important;
    color:#0f172a !important;
}
#btnToday{ white-space:nowrap; }

/* Helper text */
.hint{ font-size:12px; color:var(--muted); margin-top:4px; }
.hint.ok{ color:var(--ok); }
.hint.error{ color:var(--danger); }

/* Amount preview */
.amount-row{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
.preview-chip{
    padding:6px 10px; border-radius:999px; background:#f4f6fb; border:1px solid var(--border); font-size:12px;
}
/* Hide RM preview chip when empty */
.preview-chip:empty{ display:none; }

@media (max-width: 600px){
    .page-head{ flex-direction:column; align-items:flex-start; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Elements
  const selYear = document.querySelector('select[name="altn_year"]');
  const amt     = document.querySelector('input[name="altn_allocation"]');
  const date    = document.querySelector('input[name="altn_date"]');
  const submit  = document.getElementById('submitBtn');

  const amtPreview = document.getElementById('amtPreview');
  const amtMsg     = document.getElementById('amtMsg');
  const dateMsg    = document.getElementById('dateMsg');

  // Helpers
  const clampYear = (y) => {
    const maxY = {{ now()->year + 1 }};
    const minY = 2010;
    return Math.max(minY, Math.min(maxY, y));
  };
  const pad = (n) => String(n).padStart(2,'0');
  const yBounds = (y) => [`${y}-01-01`, `${y}-12-31`];

  function setDateBoundsFromYear(){
    if(!selYear || !date) return;
    const y = parseInt(selYear.value, 10);
    if(!y) { date.min=''; date.max=''; return; }
    const [min,max] = yBounds(y);
    date.min = min; date.max = max;

    if(date.value){
      if(date.value < min || date.value > max){
        dateMsg.textContent = `Date must be within ${y}.`;
        dateMsg.className = 'hint error';
      } else {
        dateMsg.textContent = '';
        dateMsg.className = 'hint';
      }
    }
  }

  function syncYearFromDate(){
    if(!date || !date.value) return;
    const y = clampYear(new Date(date.value).getFullYear());
    if(selYear && String(y) !== selYear.value){
      selYear.value = String(y);
      setDateBoundsFromYear();
      dateMsg.textContent = `Year set to ${y} based on selected date.`;
      dateMsg.className = 'hint ok';
      setTimeout(() => { dateMsg.textContent=''; dateMsg.className='hint'; }, 2200);
    }
  }

  function formatRM(v){
    if(v === '' || isNaN(v)) return '';
    const num = Number(v);
    return 'RM ' + num.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
  }

  function validateAmount(){
    const val = amt.value;
    let ok = true;
    amtPreview.textContent = formatRM(val);
    if(val === '' || isNaN(val) || Number(val) <= 0){
      amtMsg.textContent = 'Amount must be a positive number.';
      amtMsg.className = 'hint error';
      ok = false;
    } else {
      amtMsg.textContent = '';
      amtMsg.className = 'hint';
    }
    return ok;
  }

  function validateDate(){
    if(!date.value || !selYear.value){ dateMsg.textContent=''; dateMsg.className='hint'; return false; }
    const y = parseInt(selYear.value, 10);
    const [min,max] = yBounds(y);
    if(date.value < min || date.value > max){
      dateMsg.textContent = `Date must be within ${y}.`;
      dateMsg.className = 'hint error';
      return false;
    }
    dateMsg.textContent = '';
    dateMsg.className = 'hint';
    return true;
  }

  function updateSubmit(){
    // Enable submit only when amount is positive and date is valid
    const ok = validateAmount() && validateDate();
    if(submit) submit.disabled = !ok;
  }

  selYear?.addEventListener('change', () => { setDateBoundsFromYear(); updateSubmit(); });
  date   ?.addEventListener('change', () => { syncYearFromDate(); updateSubmit(); });
  date   ?.addEventListener('input',  () => { syncYearFromDate(); updateSubmit(); });
  amt    ?.addEventListener('input',  () => { validateAmount(); updateSubmit(); });

  document.getElementById('btnToday')?.addEventListener('click', (e) => {
    e.preventDefault();
    const today = new Date();
    const y = today.getFullYear();
    date.value = `${y}-${pad(today.getMonth()+1)}-${pad(today.getDate())}`;
    syncYearFromDate();
    updateSubmit();
  });

  // Init
  setDateBoundsFromYear();
  amtPreview.textContent = formatRM(amt?.value || '');
  updateSubmit();
});
</script>

<div class="container">
    <div class="page-head">
        <h4>Fleet Management &raquo; Create Allocation</h4>
        <div>
            <a href="{{ route('allocation.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mt-2">
            <strong>There were some problems with your submission:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <form action="{{ route('allocation.store') }}" method="POST" novalidate>
            @csrf

            <div class="row g-3">
                {{-- Year --}}
                <div class="col-md-4">
                    <label class="form-label">Year</label>
                    <select name="altn_year" class="form-select @error('altn_year') is-invalid @enderror" required>
                        <option value="">-- Select Year --</option>
                        @for($year = now()->year + 1; $year >= 2010; $year--)
                            <option value="{{ $year }}" {{ old('altn_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    @error('altn_year') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Assign To --}}
                <div class="col-md-4">
                    <label class="form-label">Assign To</label>
                    <select name="altn_phb" class="form-select @error('altn_phb') is-invalid @enderror" required>
                        <option value="" disabled {{ old('altn_phb') ? '' : 'selected' }}>-- Select PHB --</option>
                        @foreach($phbs as $phb)
                            <option value="{{ $phb }}" {{ old('altn_phb') == $phb ? 'selected' : '' }}>{{ $phb }}</option>
                        @endforeach
                    </select>
                    @error('altn_phb') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Category --}}
                <div class="col-md-4">
                    <label class="form-label">Allocation Category</label>
                    <select name="altn_category" class="form-select @error('altn_category') is-invalid @enderror" required>
                        <option value="" disabled {{ old('altn_category') ? '' : 'selected' }}>-- Select Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('altn_category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('altn_category') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Amount --}}
                <div class="col-md-6">
                    <label class="form-label">Allocation (RM)</label>
                    <div class="amount-row">
                        <input type="number"
                               name="altn_allocation"
                               step="0.01"
                               min="0"
                               class="form-control @error('altn_allocation') is-invalid @enderror"
                               required
                               value="{{ old('altn_allocation') }}">
                        <span class="preview-chip" id="amtPreview"></span>
                    </div>
                    <div id="amtMsg" class="hint"></div>
                    @error('altn_allocation') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Date --}}
                <div class="col-md-6">
                    <label class="form-label">Date</label>
                    <div class="input-group">
                        <input type="date"
                               name="altn_date"
                               class="form-control @error('altn_date') is-invalid @enderror"
                               required
                               value="{{ old('altn_date') }}">
                        <button class="btn btn-secondary" id="btnToday" type="button">Today</button>
                    </div>
                    <div id="dateMsg" class="hint"></div>
                    @error('altn_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button id="submitBtn" type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('allocation.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection
