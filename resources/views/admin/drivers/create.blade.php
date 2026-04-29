@extends('layouts.admin')
@section('title','Add Driver')
@section('page-title','Add Driver Account')
@section('content')

{{-- ══════════════════════════════════════════════════════
     CANCEL CONFIRMATION MODAL
══════════════════════════════════════════════════════ --}}
<div id="cancelOverlay" style="
    display:none; position:fixed; inset:0; z-index:10000;
    background:rgba(0,0,0,.78); backdrop-filter:blur(6px);
    align-items:center; justify-content:center; padding:16px;">

    <div id="cancelModal" style="
        background:#111; border:1px solid rgba(220,0,0,.2);
        border-radius:14px; padding:28px 24px; width:100%; max-width:380px;
        box-shadow:0 32px 80px rgba(0,0,0,.6);
        animation:modalIn .25s cubic-bezier(.25,.8,.25,1) both;
        text-align:center;">

        {{-- Icon --}}
        <div style="width:56px;height:56px;border-radius:50%;margin:0 auto 16px;
                    display:flex;align-items:center;justify-content:center;
                    background:rgba(220,0,0,.08);border:1px solid rgba(220,0,0,.25)">
            <i class="fas fa-exclamation-triangle" style="color:var(--ferrari-red);font-size:22px"></i>
        </div>

        <div style="font-family:'Bebas Neue',sans-serif;font-size:20px;letter-spacing:2px;margin-bottom:8px">
            Discard Changes?
        </div>
        <p style="color:var(--gray);font-size:12px;line-height:1.7;margin-bottom:20px">
            Are you sure you want to cancel?<br>
            All entered information will be <strong style="color:#fff">lost</strong>.
        </p>

        <div style="display:flex;gap:10px">
            {{-- Stay --}}
            <button onclick="closeCancelConfirm()" style="
                flex:1;padding:11px;border:1px solid #2a2a2a;border-radius:6px;cursor:pointer;
                font-family:'Barlow',sans-serif;font-weight:700;font-size:12px;
                letter-spacing:2px;text-transform:uppercase;
                background:transparent;color:var(--gray);transition:border-color .2s,color .2s"
                onmouseover="this.style.borderColor='#555';this.style.color='#fff'"
                onmouseout="this.style.borderColor='#2a2a2a';this.style.color='var(--gray)'">
                <i class="fas fa-arrow-left" style="margin-right:6px"></i> Stay
            </button>
            {{-- Yes, Leave --}}
            <a href="{{ route('admin.drivers.index') }}" style="
                flex:1;padding:11px;border:none;border-radius:6px;cursor:pointer;
                font-family:'Barlow',sans-serif;font-weight:700;font-size:12px;
                letter-spacing:2px;text-transform:uppercase;
                background:var(--ferrari-red);color:#fff;
                text-decoration:none;display:flex;align-items:center;justify-content:center;gap:8px;
                transition:background .2s,transform .15s"
                onmouseover="this.style.background='#b00000';this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='var(--ferrari-red)';this.style.transform=''">
                <i class="fas fa-times"></i> Yes, Cancel
            </a>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     CONFIRM CREATE MODAL
══════════════════════════════════════════════════════ --}}
<div id="confirmOverlay" style="
    display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,.78); backdrop-filter:blur(6px);
    align-items:center; justify-content:center; padding:16px;">

    <div id="confirmModal" style="
        background:#111; border:1px solid rgba(220,0,0,.25);
        border-radius:14px; padding:28px 24px; width:100%; max-width:400px;
        box-shadow:0 32px 80px rgba(0,0,0,.6);
        animation:modalIn .25s cubic-bezier(.25,.8,.25,1) both;">

        <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
            <div style="width:42px;height:42px;border-radius:50%;flex-shrink:0;
                        display:flex;align-items:center;justify-content:center;
                        background:rgba(220,0,0,.1);border:1px solid rgba(220,0,0,.3)">
                <i class="fas fa-user-plus" style="color:var(--ferrari-red);font-size:16px"></i>
            </div>
            <div>
                <div style="font-family:'Bebas Neue',sans-serif;font-size:18px;letter-spacing:2px">Create Driver Account</div>
                <div style="color:var(--gray);font-size:11px;margin-top:1px">Admin password required to proceed</div>
            </div>
        </div>

        <p style="color:#aaa;font-size:12px;line-height:1.6;margin:12px 0 16px">
            You are about to create a new driver account.<br>
            Enter your admin password to confirm.
        </p>

        <div style="margin-bottom:16px">
            <label style="font-size:10px;font-weight:700;letter-spacing:2px;
                          text-transform:uppercase;color:var(--gray);display:block;margin-bottom:6px">
                Your Admin Password
            </label>
            <div style="position:relative">
                <input type="password" id="confirmPassword"
                    placeholder="Enter your password to confirm"
                    style="width:100%;box-sizing:border-box;padding:10px 40px 10px 14px;
                           background:#1a1a1a;border:1px solid #2a2a2a;border-radius:6px;
                           color:#fff;font-size:13px;outline:none;transition:border-color .2s"
                    oninput="clearConfirmError()"
                    onkeydown="if(event.key==='Enter')submitConfirm()">
                <span onclick="togglePw('confirmPassword','confirmEye')" style="
                    position:absolute;right:12px;top:50%;transform:translateY(-50%);
                    color:#555;cursor:pointer;font-size:13px;transition:color .2s"
                    onmouseover="this.style.color='var(--ferrari-red)'"
                    onmouseout="this.style.color='#555'">
                    <i id="confirmEye" class="fas fa-eye"></i>
                </span>
            </div>
            <div id="confirmError" style="color:var(--ferrari-red);font-size:11px;margin-top:6px;display:none;align-items:center;gap:6px">
                <i class="fas fa-exclamation-circle"></i>
                <span id="confirmErrorMsg">Incorrect password.</span>
            </div>
        </div>

        <div style="display:flex;gap:10px">
            <button id="confirmOkBtn" onclick="submitConfirm()" style="
                flex:1;padding:11px;border:none;border-radius:6px;cursor:pointer;
                font-family:'Barlow',sans-serif;font-weight:700;font-size:12px;
                letter-spacing:2px;text-transform:uppercase;
                background:var(--ferrari-red);color:#fff;
                transition:background .2s,transform .15s;
                display:flex;align-items:center;justify-content:center;gap:8px"
                onmouseover="this.style.background='#b00000';this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='var(--ferrari-red)';this.style.transform=''">
                <i id="confirmOkIcon" class="fas fa-user-plus"></i>
                <span id="confirmOkText">Create Account</span>
                <div id="confirmSpinner" style="display:none;width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite"></div>
            </button>
            <button onclick="closeConfirm()" style="
                flex:1;padding:11px;border:1px solid #2a2a2a;border-radius:6px;cursor:pointer;
                font-family:'Barlow',sans-serif;font-weight:700;font-size:12px;
                letter-spacing:2px;text-transform:uppercase;
                background:transparent;color:var(--gray);transition:border-color .2s,color .2s"
                onmouseover="this.style.borderColor='#444';this.style.color='#fff'"
                onmouseout="this.style.borderColor='#2a2a2a';this.style.color='var(--gray)'">
                Cancel
            </button>
        </div>
    </div>
</div>

<style>
@keyframes modalIn {
    from { opacity:0; transform:translateY(16px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
@keyframes spin { to { transform:rotate(360deg); } }
#confirmPassword:focus { border-color:rgba(220,0,0,.5) !important; }

/* ── Form layout ── */
.driver-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 16px;
}
.driver-grid .span-2 { grid-column: span 2; }

/* Password strength bar */
.strength-bar {
    height: 3px;
    border-radius: 2px;
    margin-top: 6px;
    background: #1e1e1e;
    overflow: hidden;
    transition: all .3s;
}
.strength-fill {
    height: 100%;
    border-radius: 2px;
    width: 0%;
    transition: width .4s, background .4s;
}
.strength-label {
    font-size: 10px;
    margin-top: 4px;
    letter-spacing: 1px;
    text-transform: uppercase;
    transition: color .3s;
}
.req-list {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 8px;
}
.req-item {
    font-size: 10px;
    padding: 3px 8px;
    border-radius: 20px;
    border: 1px solid #2a2a2a;
    color: #555;
    transition: all .25s;
    display: flex;
    align-items: center;
    gap: 4px;
}
.req-item.met {
    color: #4caf50;
    border-color: rgba(76,175,80,.3);
    background: rgba(76,175,80,.07);
}

/* password toggle button */
.pw-wrap { position: relative; }
.pw-toggle {
    position:absolute;right:12px;top:50%;transform:translateY(-50%);
    color:#555;cursor:pointer;font-size:13px;transition:color .2s;
    background:none;border:none;padding:0;
}
.pw-toggle:hover { color: var(--ferrari-red); }

/* Responsive */
@media(max-width:580px) {
    .driver-grid { grid-template-columns: 1fr; }
    .driver-grid .span-2 { grid-column: span 1; }
}
</style>

{{-- ══════════════════════════════════════════════════════
     CREATE FORM
══════════════════════════════════════════════════════ --}}
<div style="max-width:700px">
    <div class="card card-body" style="padding:24px">

        {{-- Header --}}
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #1e1e1e">
            <div style="width:44px;height:44px;border-radius:50%;background:rgba(220,0,0,.1);border:1px solid rgba(220,0,0,.3);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-user-plus" style="color:var(--ferrari-red);font-size:16px"></i>
            </div>
            <div>
                <div style="font-family:'Bebas Neue',sans-serif;font-size:18px;letter-spacing:2px">New Driver Account</div>
                <div style="color:var(--gray);font-size:11px">Fill in the details below</div>
            </div>
        </div>

        <form id="createForm" method="POST" action="{{ route('admin.drivers.store') }}">
            @csrf
            <input type="hidden" name="admin_password" id="adminPasswordField">

            <div class="driver-grid">

                {{-- Full Name --}}
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" class="form-control"
                        value="{{ old('name') }}" required>
                    <div class="form-error">@error('name'){{ $message }}@enderror</div>
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email') }}" required>
                    <div class="form-error">@error('email'){{ $message }}@enderror</div>
                </div>

                {{-- Contact --}}
                <div class="form-group">
                    <label>Contact Number *</label>
                    <input type="text" name="contact_number" class="form-control"
                        value="{{ old('contact_number') }}"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                        maxlength="15" required>
                    <div class="form-error">@error('contact_number'){{ $message }}@enderror</div>
                </div>

                {{-- License --}}
                <div class="form-group">
                    <label>License Number</label>
                    <input type="text" name="license_number" class="form-control"
                        value="{{ old('license_number') }}"
                        placeholder="e.g. N01-12-345678">
                    <div class="form-error">@error('license_number'){{ $message }}@enderror</div>
                </div>

                {{-- Vehicle --}}
                <div class="form-group">
                    <label>Vehicle Info</label>
                    <input type="text" name="vehicle_info" class="form-control"
                        value="{{ old('vehicle_info') }}"
                        placeholder="e.g. Toyota Vios 2022 — ABC 123">
                    <div class="form-error">@error('vehicle_info'){{ $message }}@enderror</div>
                </div>

                {{-- Address --}}
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control"
                        value="{{ old('address') }}">
                    <div class="form-error">@error('address'){{ $message }}@enderror</div>
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label>Password *</label>
                    <div class="pw-wrap">
                        <input type="password" id="newPassword" name="password" class="form-control"
                            placeholder="Min. 8 chars" required
                            oninput="checkStrength(this.value)"
                            style="padding-right:40px">
                        <button type="button" class="pw-toggle" onclick="togglePw('newPassword','eyeNew')">
                            <i id="eyeNew" class="fas fa-eye"></i>
                        </button>
                    </div>
                    {{-- Strength bar --}}
                    <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                    <div class="strength-label" id="strengthLabel" style="color:#555">—</div>
                    {{-- Requirements --}}
                    <div class="req-list">
                        <span class="req-item" id="req-len"><i class="fas fa-circle" style="font-size:6px"></i> 8+ chars</span>
                        <span class="req-item" id="req-upper"><i class="fas fa-circle" style="font-size:6px"></i> Uppercase</span>
                        <span class="req-item" id="req-lower"><i class="fas fa-circle" style="font-size:6px"></i> Lowercase</span>
                        <span class="req-item" id="req-num"><i class="fas fa-circle" style="font-size:6px"></i> Number</span>
                        <span class="req-item" id="req-sym"><i class="fas fa-circle" style="font-size:6px"></i> Symbol</span>
                    </div>
                    <div class="form-error">@error('password'){{ $message }}@enderror</div>
                </div>

                {{-- Confirm Password --}}
                <div class="form-group">
                    <label>Confirm Password *</label>
                    <div class="pw-wrap">
                        <input type="password" id="confirmPwField" name="password_confirmation" class="form-control"
                            placeholder="Repeat password" required
                            oninput="checkMatch()"
                            style="padding-right:40px">
                        <button type="button" class="pw-toggle" onclick="togglePw('confirmPwField','eyeConfirm')">
                            <i id="eyeConfirm" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div id="matchMsg" style="font-size:11px;margin-top:5px;display:none"></div>
                </div>

            </div>{{-- /driver-grid --}}

            <div style="display:flex;gap:10px;margin-top:4px">
                <button type="button" class="btn btn-red" onclick="openConfirm()">
                    <i class="fas fa-user-plus"></i> Create Driver Account
                </button>
                {{-- Cancel now triggers confirmation modal --}}
                <button type="button" class="btn btn-gray" onclick="openCancelConfirm()">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>

        </form>
    </div>
</div>

<script>
const VERIFY_URL = '{{ route('admin.verify-password') }}';
const CSRF       = '{{ csrf_token() }}';

// ── Track if form has any input ────────────────────────────────
function formHasData() {
    const fields = document.querySelectorAll('#createForm input:not([type=hidden])');
    return Array.from(fields).some(f => f.value.trim() !== '');
}

/* ══════════════════════════════════════════════════════
   CANCEL CONFIRMATION
══════════════════════════════════════════════════════ */
function openCancelConfirm() {
    // If form is completely empty, just leave immediately
    if (!formHasData()) {
        window.location.href = '{{ route('admin.drivers.index') }}';
        return;
    }
    const overlay = document.getElementById('cancelOverlay');
    const modal   = document.getElementById('cancelModal');
    overlay.style.display = 'flex';
    modal.style.animation = 'none';
    void modal.offsetWidth;
    modal.style.animation = 'modalIn .25s cubic-bezier(.25,.8,.25,1) both';
}

function closeCancelConfirm() {
    document.getElementById('cancelOverlay').style.display = 'none';
}

// Close cancel modal on overlay click
document.getElementById('cancelOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeCancelConfirm();
});

/* ══════════════════════════════════════════════════════
   CREATE CONFIRM MODAL
══════════════════════════════════════════════════════ */
function togglePw(inputId, eyeId) {
    const inp = document.getElementById(inputId);
    const eye = document.getElementById(eyeId);
    inp.type      = inp.type === 'password' ? 'text' : 'password';
    eye.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}

function checkStrength(val) {
    const reqs = {
        'req-len'  : val.length >= 8,
        'req-upper': /[A-Z]/.test(val),
        'req-lower': /[a-z]/.test(val),
        'req-num'  : /[0-9]/.test(val),
        'req-sym'  : /[^A-Za-z0-9]/.test(val),
    };
    let score = Object.values(reqs).filter(Boolean).length;
    Object.entries(reqs).forEach(([id, met]) => {
        document.getElementById(id).classList.toggle('met', met);
    });
    const fill  = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    const levels = [
        { pct:'0%',   color:'#333',    text:'—' },
        { pct:'20%',  color:'#e53935', text:'Very Weak' },
        { pct:'40%',  color:'#fb8c00', text:'Weak' },
        { pct:'60%',  color:'#fdd835', text:'Fair' },
        { pct:'80%',  color:'#43a047', text:'Strong' },
        { pct:'100%', color:'#00c853', text:'Very Strong' },
    ];
    fill.style.width      = levels[score].pct;
    fill.style.background = levels[score].color;
    label.style.color     = levels[score].color;
    label.textContent     = levels[score].text;
    checkMatch();
}

function checkMatch() {
    const pw  = document.getElementById('newPassword').value;
    const cpw = document.getElementById('confirmPwField').value;
    const msg = document.getElementById('matchMsg');
    if (!cpw) { msg.style.display = 'none'; return; }
    if (pw === cpw) {
        msg.style.display = 'block';
        msg.style.color   = '#4caf50';
        msg.innerHTML     = '<i class="fas fa-check-circle"></i> Passwords match';
    } else {
        msg.style.display = 'block';
        msg.style.color   = 'var(--ferrari-red)';
        msg.innerHTML     = '<i class="fas fa-times-circle"></i> Passwords do not match';
    }
}

function openConfirm() {
    const name  = document.querySelector('input[name="name"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const phone = document.querySelector('input[name="contact_number"]').value.trim();
    const pass  = document.getElementById('newPassword').value;
    const passC = document.getElementById('confirmPwField').value;

    if (!name || !email || !phone || !pass || !passC) {
        document.getElementById('createForm').reportValidity();
        return;
    }
    if (pass !== passC) {
        document.getElementById('confirmPwField').focus();
        return;
    }
    const allMet = pass.length >= 8
        && /[A-Z]/.test(pass)
        && /[a-z]/.test(pass)
        && /[0-9]/.test(pass)
        && /[^A-Za-z0-9]/.test(pass);
    if (!allMet) {
        document.getElementById('newPassword').focus();
        showTempError('Password must meet all requirements.');
        return;
    }

    document.getElementById('confirmPassword').value = '';
    clearConfirmError();
    setConfirmLoading(false);

    const overlay = document.getElementById('confirmOverlay');
    const modal   = document.getElementById('confirmModal');
    overlay.style.display = 'flex';
    modal.style.animation = 'none';
    void modal.offsetWidth;
    modal.style.animation = 'modalIn .25s cubic-bezier(.25,.8,.25,1) both';
    setTimeout(() => document.getElementById('confirmPassword').focus(), 80);
}

function showTempError(msg) {
    const lbl = document.getElementById('strengthLabel');
    lbl.textContent = msg;
    lbl.style.color = 'var(--ferrari-red)';
    setTimeout(() => { checkStrength(document.getElementById('newPassword').value); }, 2000);
}

function closeConfirm() {
    document.getElementById('confirmOverlay').style.display = 'none';
}

document.getElementById('confirmOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeConfirm();
});

// Escape key closes whichever modal is open
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConfirm();
        closeCancelConfirm();
    }
});

async function submitConfirm() {
    const pw = document.getElementById('confirmPassword').value.trim();
    if (!pw) {
        showConfirmError('Please enter your admin password.');
        document.getElementById('confirmPassword').focus();
        return;
    }
    setConfirmLoading(true);
    clearConfirmError();
    try {
        const res  = await fetch(VERIFY_URL, {
            method : 'POST',
            headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF },
            body   : JSON.stringify({ password: pw }),
        });
        const data = await res.json();
        if (!data.verified) {
            setConfirmLoading(false);
            showConfirmError(data.message || 'Incorrect password. Please try again.');
            document.getElementById('confirmPassword').value = '';
            document.getElementById('confirmPassword').focus();
            return;
        }
        document.getElementById('adminPasswordField').value = pw;
        document.getElementById('createForm').submit();
    } catch (err) {
        setConfirmLoading(false);
        showConfirmError('Something went wrong. Please try again.');
    }
}

function setConfirmLoading(on) {
    const btn = document.getElementById('confirmOkBtn');
    btn.disabled = on; btn.style.opacity = on ? '0.7' : '';
    document.getElementById('confirmOkIcon').style.display    = on ? 'none'  : '';
    document.getElementById('confirmOkText').style.display    = on ? 'none'  : '';
    document.getElementById('confirmSpinner').style.display   = on ? 'block' : 'none';
}
function showConfirmError(msg) {
    document.getElementById('confirmErrorMsg').textContent = msg;
    document.getElementById('confirmError').style.display  = 'flex';
    document.getElementById('confirmPassword').style.borderColor = 'rgba(220,0,0,.6)';
}
function clearConfirmError() {
    document.getElementById('confirmError').style.display  = 'none';
    document.getElementById('confirmPassword').style.borderColor = '#2a2a2a';
}
</script>

@endsection