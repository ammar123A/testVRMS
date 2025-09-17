@extends('layouts.app')

@section('content')
<style>
    :root{
        --purple:#1e88e5;
        --purple-700:#1565c0;
        --border:#e5e7eb;
        --muted:#6b7280;
        --danger:#dc2626;
        --ok:#16a34a;
    }

    .container { font-family: Arial, Helvetica, sans-serif; }

    .page-head{
        margin-top: 14px;
        display:flex; align-items:center; justify-content:space-between; gap:10px;
    }
    .page-head h5{ margin:0; }

    .head-actions { display:flex; gap:8px; }
    .btn-outline{
        border:1px solid var(--border); background:#fff; padding:6px 10px; border-radius:8px; cursor:pointer;
    }
    .btn-outline:hover{ background:#edf6ff; } 

    .section-title{
        font-weight:700;
        background:#eaf4ff;
        padding:10px 14px;
        margin-top:16px;
        border:1px solid #cfe5ff;
        border-radius:10px 10px 0 0;
        cursor:pointer;
        position:relative;
        user-select:none;
    }
    .section-title:focus{ outline:2px solid var(--purple); }
    .section-title .caret{
        position:absolute; right:12px; top:50%; transform:translateY(-50%) rotate(-90deg);
        transition: transform .2s ease;
        font-weight:900;
    }
    .section-title[aria-expanded="true"] .caret{
        transform:translateY(-50%) rotate(0deg);
    }
    .section-content{
        border:1px solid #cfe5ff;
        border-top:none;
        padding:16px; display:none; border-radius:0 0 10px 10px; background:#fff;
    }
    .section-content.show{ display:block; }

    .form-label, .col-form-label{ font-weight:600; color:#374151; }
    .hint{ font-size:12px; color:var(--muted); margin-top:4px; }
    .hint.ok{ color:var(--ok); }
    .hint.error{ color:var(--danger); }
    .invalid-feedback{ color:var(--danger); }

    .dt-row{ display:flex; flex-wrap:wrap; gap:12px; }
    .dt-chunk{ flex:1 1 220px; }

    .actions{ text-align:center; margin:18px 0; display:flex; gap:10px; justify-content:center; flex-wrap:wrap; }
    .btn-primary{
        background:var(--purple); color:#fff; border:1px solid #0f4fa8;
        padding:10px 16px; border-radius:10px; font-weight:700; cursor:pointer;
    }
    .btn-primary:hover{ background:var(--purple-700); }
    .btn-primary:disabled{ opacity:.6; cursor:not-allowed; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.section-title').forEach(btn => {
        btn.setAttribute('role','button');
        btn.setAttribute('tabindex','0');
        btn.addEventListener('click', () => toggleSection(btn));
        btn.addEventListener('keydown', e => {
            if(e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleSection(btn); }
        });
    });
    function toggleSection(btn){
        const content = btn.nextElementSibling;
        const isOpen = content.classList.toggle('show');
        btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }

    const firstContent = document.querySelector('.section-content');
    if(firstContent) {
        firstContent.classList.add('show');
        const firstTitle = firstContent.previousElementSibling;
        if(firstTitle) firstTitle.setAttribute('aria-expanded','true');
    }
    if(document.getElementById('error-box')){
        document.querySelectorAll('.section-content').forEach(c => c.classList.add('show'));
        document.querySelectorAll('.section-title').forEach(t => t.setAttribute('aria-expanded','true'));
    }

    const expandAllBtn = document.getElementById('expandAll');
    const collapseAllBtn = document.getElementById('collapseAll');
    if(expandAllBtn) expandAllBtn.addEventListener('click', () => {
        document.querySelectorAll('.section-content').forEach(c => c.classList.add('show'));
        document.querySelectorAll('.section-title').forEach(t => t.setAttribute('aria-expanded','true'));
    });
    if(collapseAllBtn) collapseAllBtn.addEventListener('click', () => {
        document.querySelectorAll('.section-content').forEach(c => c.classList.remove('show'));
        document.querySelectorAll('.section-title').forEach(t => t.setAttribute('aria-expanded','false'));
    });

    const startDate = document.querySelector('input[name="start_date"]');
    const startTime = document.querySelector('input[name="start_time"]');
    const endDate   = document.querySelector('input[name="end_date"]');
    const endTime   = document.querySelector('input[name="end_time"]');
    const dtMsg     = document.getElementById('dtMsg');
    const submitBtn = document.getElementById('submitBtn');
    const agreeBox  = document.getElementById('agreeCheckbox');

    function toDT(d,t){
        if(!d || !t) return null;
        const s = `${d}T${t}`;
        const dt = new Date(s);
        return isNaN(dt) ? null : dt;
    }
    function fmtDuration(ms){
        if(ms <= 0) return '';
        const mins = Math.floor(ms/60000);
        const d = Math.floor(mins/1440);
        const h = Math.floor((mins%1440)/60);
        const m = mins % 60;
        const parts = [];
        if(d) parts.push(`${d} day${d>1?'s':''}`);
        if(h) parts.push(`${h} hr${h>1?'s':''}`);
        if(m) parts.push(`${m} min`);
        return parts.join(' ');
    }
    function validateDT(){
        const s = toDT(startDate?.value, startTime?.value);
        const e = toDT(endDate?.value, endTime?.value);
        let ok = true;

        if(!s || !e){
            dtMsg.textContent = '';
            dtMsg.className = 'hint';
            ok = false; 
        }else if(e <= s){
            dtMsg.textContent = 'End must be after start.';
            dtMsg.className = 'hint error';
            ok = false;
        }else{
            const dur = fmtDuration(e - s);
            dtMsg.textContent = `Duration: ${dur}`;
            dtMsg.className = 'hint ok';
        }

        if(submitBtn){
            const canSubmit = ok && (agreeBox?.checked);
            submitBtn.disabled = !canSubmit;
        }
    }
    ['input','change'].forEach(ev => {
        startDate?.addEventListener(ev, validateDT);
        startTime?.addEventListener(ev, validateDT);
        endDate  ?.addEventListener(ev, validateDT);
        endTime  ?.addEventListener(ev, validateDT);
    });
    agreeBox?.addEventListener('change', validateDT);
    validateDT();

    addCounter('program',  120);
    addCounter('purpose',  160);
    addCounter('remark',   160);
    function addCounter(name, max){
        const field = document.querySelector(`[name="${name}"]`);
        if(!field) return;
        field.setAttribute('maxlength', max);
        const p = document.createElement('div');
        p.className = 'hint';
        field.insertAdjacentElement('afterend', p);
        const update = () => p.textContent = `${field.value.length}/${max} characters`;
        field.addEventListener('input', update);
        update();
    }
});
</script>

<div class="container">
    <div class="page-head">
        <h5>Reservation &raquo; Create Reservation</h5>
        <div class="head-actions">
            <button type="button" class="btn-outline" id="expandAll">Expand all</button>
            <button type="button" class="btn-outline" id="collapseAll">Collapse all</button>
        </div>
    </div>

    {{-- Success flash --}}
    @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    {{-- Error summary --}}
    @if ($errors->any())
        <div id="error-box" class="alert alert-danger mt-3">
            <strong>There were some problems with your submission:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reservation.store') }}" novalidate>
        @csrf

        {{-- Requestor Information --}}
        <div class="section-title" aria-expanded="false">
            Requestor Information <span class="caret">&#9654;</span>
        </div>
        <div class="section-content">
            <table class="table table-bordered mb-0">
                <tr>
                    <th style="width:280px;">Requestor Type</th>
                    <td>{{ strtoupper(auth()->user()->role ?? '-') }}</td>
                </tr>
                <tr>
                    <th>Requestor ID</th>
                    <td>{{ auth()->user()->username ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ auth()->user()->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Staff No. / New IC / Passport No.</th>
                    <td>{{ auth()->user()->em_id ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Department / Faculty</th>
                    <td>{{ auth()->user()->department ?? '-' }} / {{ auth()->user()->faculty ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Campus</th>
                    <td>{{ auth()->user()->campus ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Handphone</th>
                    <td>{{ auth()->user()->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
                        <input type="email"
                               name="officer_email"
                               class="form-control @error('officer_email') is-invalid @enderror"
                               value="{{ old('officer_email', auth()->user()->email ?? '') }}"
                               required>
                        @error('officer_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </td>
                </tr>
            </table>
        </div>

        {{-- Costing Details --}}
        <div class="section-title" aria-expanded="false">
            Costing Details <span class="caret">&#9654;</span>
        </div>
        <div class="section-content">
            <div class="row mb-3">
                <div class="col-md-4"><label class="col-form-label">Attention To:</label></div>
                <div class="col-md-8">
                    <select name="attention_to" class="form-select @error('attention_to') is-invalid @enderror" required>
                        <option value="" disabled {{ old('attention_to') ? '' : 'selected' }}>-- Select campus --</option>
                        @foreach ($campuses as $c)
                            <option value="{{ $c }}" {{ old('attention_to') === $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                    @error('attention_to') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label class="col-form-label">Vote PTJ:</label></div>
                <div class="col-md-8">
                    <select name="vote_ptj" class="form-select @error('vote_ptj') is-invalid @enderror" required>
                        <option value="" disabled {{ old('vote_ptj') ? '' : 'selected' }}>-- Select vote --</option>
                        @foreach ($votePtj as $v)
                            <option value="{{ $v }}" {{ old('vote_ptj') === $v ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('vote_ptj') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label class="col-form-label">Department / Faculty:</label></div>
                <div class="col-md-8">
                    <input type="text"
                           name="dept_faculty"
                           class="form-control @error('dept_faculty') is-invalid @enderror"
                           value="{{ old('dept_faculty') }}"
                           required>
                    @error('dept_faculty') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label class="col-form-label">Vehicle Request (Type):</label></div>
                <div class="col-md-8">
                    <select name="vehicle_request" class="form-select @error('vehicle_request') is-invalid @enderror" required>
                        <option value="" disabled {{ old('vehicle_request') ? '' : 'selected' }}>-- Select vehicle type --</option>
                        @forelse ($vehicleTypes as $t)
                            <option value="{{ $t }}" {{ old('vehicle_request') === $t ? 'selected' : '' }}>{{ strtoupper($t) }}</option>
                        @empty
                            <option value="" disabled>No vehicle types available</option>
                        @endforelse
                    </select>
                    @error('vehicle_request') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><label class="col-form-label">No. of Vehicles:</label></div>
                <div class="col-md-8">
                    <input type="number"
                           name="no_vehicle"
                           class="form-control @error('no_vehicle') is-invalid @enderror"
                           value="{{ old('no_vehicle', 1) }}"
                           min="1"
                           required>
                    @error('no_vehicle') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Booking Details --}}
        <div class="section-title" aria-expanded="false">
            Booking Details <span class="caret">&#9654;</span>
        </div>
        <div class="section-content">
            <div class="mb-3">
                <label class="form-label">Program:</label>
                <input type="text"
                       name="program"
                       class="form-control @error('program') is-invalid @enderror"
                       value="{{ old('program') }}"
                       required>
                @error('program') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Purpose:</label>
                <input type="text"
                       name="purpose"
                       class="form-control @error('purpose') is-invalid @enderror"
                       value="{{ old('purpose') }}"
                       required>
                @error('purpose') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Booking Type:</label>
                <select name="booking_type" class="form-select @error('booking_type') is-invalid @enderror" required>
                    <option value="" disabled {{ old('booking_type') ? '' : 'selected' }}>-- Select booking type --</option>
                    @foreach ($bookingTypes as $bt)
                        <option value="{{ $bt }}" {{ old('booking_type') === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                    @endforeach
                </select>
                @error('booking_type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Pickup Point:</label>
                <textarea name="pickup_point"
                          class="form-control @error('pickup_point') is-invalid @enderror"
                          required>{{ old('pickup_point') }}</textarea>
                @error('pickup_point') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                <select name="pickup_state" class="form-select mt-2 @error('pickup_state') is-invalid @enderror" required>
                    <option value="" disabled {{ old('pickup_state') ? '' : 'selected' }}>-- Select state --</option>
                    @foreach ($states as $st)
                        <option value="{{ $st }}" {{ old('pickup_state') === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
                @error('pickup_state') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Destination:</label>
                <textarea name="destination"
                          class="form-control @error('destination') is-invalid @enderror"
                          required>{{ old('destination') }}</textarea>
                @error('destination') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                <select name="destination_state" class="form-select mt-2 @error('destination_state') is-invalid @enderror" required>
                    <option value="" disabled {{ old('destination_state') ? '' : 'selected' }}>-- Select state --</option>
                    @foreach ($states as $st)
                        <option value="{{ $st }}" {{ old('destination_state') === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
                @error('destination_state') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="dt-row mb-2">
                <div class="dt-chunk">
                    <label class="form-label">Start Date</label>
                    <input type="date"
                           name="start_date"
                           class="form-control @error('start_date') is-invalid @enderror"
                           value="{{ old('start_date') }}"
                           required>
                    @error('start_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="dt-chunk">
                    <label class="form-label">Start Time</label>
                    <input type="time"
                           name="start_time"
                           class="form-control @error('start_time') is-invalid @enderror"
                           value="{{ old('start_time') }}"
                           required>
                    @error('start_time') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="dt-chunk">
                    <label class="form-label">End Date</label>
                    <input type="date"
                           name="end_date"
                           class="form-control @error('end_date') is-invalid @enderror"
                           value="{{ old('end_date') }}"
                           required>
                    @error('end_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="dt-chunk">
                    <label class="form-label">End Time</label>
                    <input type="time"
                           name="end_time"
                           class="form-control @error('end_time') is-invalid @enderror"
                           value="{{ old('end_time') }}"
                           required>
                    @error('end_time') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
            <div id="dtMsg" class="hint"></div>

            <div class="mb-3 mt-2">
                <label class="form-label">Remark:</label>
                <input type="text"
                       name="remark"
                       class="form-control @error('remark') is-invalid @enderror"
                       value="{{ old('remark') }}">
                @error('remark') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Disclaimer --}}
        <div class="section-title" aria-expanded="false">
            Disclaimer <span class="caret">&#9654;</span>
        </div>
        <div class="section-content">
            <div class="mb-2">
                <strong>Disclaimer:</strong>
                <p class="mb-2">
                    I CERTIFY ALL THE INFORMATION ABOVE IS TRUE.
                    I UNDERSTAND AND WILL COMPLY WITH THE CONDITIONS AND REGULATIONS
                    FOR USE OF THE VEHICLE AS STATED IN THE MANAGEMENT AND SERVICES OF
                    UNIVERSITY VEHICLE POLICY 2011.
                </p>
            </div>
            <div class="form-check">
                <input class="form-check-input @error('agree') is-invalid @enderror"
                       type="checkbox"
                       name="agree"
                       value="1"
                       id="agreeCheckbox"
                       {{ old('agree') ? 'checked' : '' }}
                       required>
                <label class="form-check-label" for="agreeCheckbox">AGREE</label>
                @error('agree') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="actions">
            <button id="submitBtn" type="submit" class="btn btn-primary btn-primary">Submit Reservation</button>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
