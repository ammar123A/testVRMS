@extends('layouts.app')

@section('content')
<div class="vrms-auth">
    <div class="header">
        <h4 class="title">Change Password</h4>
    </div>

    @if (session('status'))
        <div class="alert success">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('password.update') }}" novalidate>
            @csrf

            {{-- Current --}}
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <div class="pw">
                    <input type="password"
                           id="current_password"
                           name="current_password"
                           required
                           autocomplete="current-password">
                    <button type="button" class="toggle" data-target="current_password">Show</button>
                </div>
                @error('current_password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- New --}}
            <div class="form-group">
                <label for="new_password">New Password</label>
                <div class="pw">
                    <input type="password"
                           id="new_password"
                           name="new_password"
                           required
                           minlength="8"
                           autocomplete="new-password">
                    <button type="button" class="toggle" data-target="new_password">Show</button>
                </div>

                <div class="strength" id="strength">
                    <span></span><span></span><span></span><span></span>
                </div>
                <small class="help">Use at least 8 characters with a mix of upper/lowercase, numbers, and symbols.</small>

                @error('new_password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm --}}
            <div class="form-group">
                <label for="new_password_confirmation">Confirm New Password</label>
                <div class="pw">
                    <input type="password"
                           id="new_password_confirmation"
                           name="new_password_confirmation"
                           required
                           autocomplete="new-password">
                    <button type="button" class="toggle" data-target="new_password_confirmation">Show</button>
                </div>
                <small id="matchMsg" class="help"></small>

                @error('new_password_confirmation')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="actions">
                <button type="submit" class="btn-primary">Update Password</button>
                <button type="button" class="btn-ghost" onclick="history.back()">Cancel</button>
            </div>
        </form>
    </div>

    <p class="muted">© {{ now()->year }} Universiti Teknologi Malaysia — Ver 2.0</p>
</div>

<style>
:root{
    --purple:#2b0054;
    --purple-700:#4a0b8f;
    --border:#e5e7eb;
    --muted:#6b7280;
    --danger:#dc2626;
    --success:#16a34a;
}

.vrms-auth{
    max-width: 640px;
    margin: 18px auto;
    padding: 0 16px;
    font-family: Arial, Helvetica, sans-serif;
}
.header{ display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
.title{ margin:0; font-size:22px; color:#222; }

.card{
    background:#fff;
    border:1px solid var(--border);
    border-radius:10px;
    padding:20px;
    box-shadow:0 1px 3px rgba(0,0,0,.06);
}

.form-group{ margin-bottom:14px; }
label{ display:block; font-weight:600; color:#374151; margin-bottom:6px; }

.pw{ display:flex; align-items:center; gap:8px; }
.pw input{
    flex:1;
    padding:10px 12px;
    border:1px solid var(--border);
    border-radius:8px;
    font-size:14px;
}
.toggle{
    border:1px solid var(--border);
    background:#f7f7f9;
    padding:8px 12px;
    border-radius:8px;
    cursor:pointer;
}

.strength{ display:flex; gap:6px; margin-top:6px; }
.strength span{
    height:6px; flex:1; background:#e5e7eb; border-radius:999px;
}
.strength span.on:nth-child(1){ background:#ef4444; }
.strength span.on:nth-child(2){ background:#f59e0b; }
.strength span.on:nth-child(3){ background:#10b981; }
.strength span.on:nth-child(4){ background:#16a34a; }

.help{ font-size:12px; color:var(--muted); margin-top:4px; }
.help.ok{ color:var(--success); }
.help.error{ color:var(--danger); }

.field-error{ color:var(--danger); font-size:12px; margin-top:6px; }

.alert{
    padding:10px 12px; border-radius:8px; margin-bottom:12px; border:1px solid;
}
.alert.success{ background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
.alert.error{ background:#fef2f2; color:#991b1b; border-color:#fecaca; }

.actions{ display:flex; gap:10px; margin-top:10px; }
.btn-primary{
    background:var(--purple); color:#fff; border:1px solid #1a0036;
    padding:10px 16px; border-radius:8px; cursor:pointer; font-weight:700;
}
.btn-primary:hover{ background:var(--purple-700); }
.btn-ghost{
    background:#fff; border:1px solid var(--border);
    padding:10px 16px; border-radius:8px; cursor:pointer;
}

@media (max-width: 480px){
    .actions{ flex-direction:column; }
    .toggle{ padding:8px 10px; }
}
</style>

<script>
(() => {
  // Toggle show/hide for password fields
  document.querySelectorAll('.vrms-auth .toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-target');
      const input = document.getElementById(id);
      if (!input) return;
      const isPw = input.type === 'password';
      input.type = isPw ? 'text' : 'password';
      btn.textContent = isPw ? 'Hide' : 'Show';
    });
  });

  // Strength + match indicators
  const np   = document.getElementById('new_password');
  const conf = document.getElementById('new_password_confirmation');
  const bars = document.querySelectorAll('#strength span');
  const matchMsg = document.getElementById('matchMsg');

  function score(pw){
    let s = 0;
    if (pw.length >= 8) s++;
    if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) s++;
    if (/\d/.test(pw)) s++;
    if (/[^\w\s]/.test(pw)) s++;
    return s;
  }
  function renderStrength(){
    const s = score(np.value);
    bars.forEach((b,i) => b.classList.toggle('on', i < s));
  }
  function renderMatch(){
    if (!conf.value) { matchMsg.textContent = ''; matchMsg.className = 'help'; return; }
    const ok = np.value && conf.value && np.value === conf.value;
    matchMsg.textContent = ok ? 'Passwords match.' : 'Passwords do not match.';
    matchMsg.className = ok ? 'help ok' : 'help error';
  }

  ['input','change'].forEach(ev => {
    if (np)   np.addEventListener(ev, () => { renderStrength(); renderMatch(); });
    if (conf) conf.addEventListener(ev, renderMatch);
  });
  renderStrength();
})();
</script>
@endsection
