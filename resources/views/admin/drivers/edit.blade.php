@extends('layouts.admin')
@section('title', 'Edit Driver — ' . $driver->name)
@section('page-title', 'Edit Driver')
@section('content')

{{-- ══════════════════════════════════════════════════════
     CONFIRM MODAL
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
                        background:rgba(74,158,255,.1);border:1px solid rgba(74,158,255,.3)">
                <i class="fas fa-save" style="color:#4a9eff;font-size:16px"></i>
            </div>
            <div>
                <div style="font-family:'Bebas Neue',sans-serif;font-size:18px;letter-spacing:2px">Save Changes</div>
                <div style="color:var(--gray);font-size:11px;margin-top:1px">Admin password required to update driver</div>
            </div>
        </div>

        <p style="color:#aaa;font-size:12px;line-height:1.6;margin:12px 0 16px">
            You are about to update the driver record for
            <strong style="color:#fff">{{ $driver->name }}</strong>.<br>
            Enter your admin password to save the changes.
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
                <button type="button" onclick="togglePw('confirmPassword','confirmEye')" style="
                    position:absolute;right:12px;top:50%;transform:translateY(-50%);
                    color:#555;cursor:pointer;font-size:13px;transition:color .2s;
                    background:none;border:none;padding:0"
                    onmouseover="this.style.color='var(--ferrari-red)'"
                    onmouseout="this.style.color='#555'">
                    <i id="confirmEye" class="fas fa-eye"></i>
                </button>
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
                background:#185fa5;color:#fff;
                transition:background .2s,transform .15s;
                display:flex;align-items:center;justify-content:center;gap:8px"
                onmouseover="this.style.background='#0c447c';this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='#185fa5';this.style.transform=''">
                <i id="confirmOkIcon" class="fas fa-save"></i>
                <span id="confirmOkText">Save Changes</span>
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
#confirmPassword:focus { border-color:rgba(74,158,255,.5) !important; }

/* ── Form grid ── */
.driver-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 16px;
}
.driver-grid .span-2 { grid-column: span 2; }

/* Password strength */
.strength-bar {
    height: 3px; border-radius: 2px; margin-top: 6px;
    background: #1e1e1e; overflow: hidden;
}
.strength-fill {
    height: 100%; border-radius: 2px; width: 0%;
    transition: width .4s, background .4s;
}
.strength-label {
    font-size: 10px; margin-top: 4px; letter-spacing: 1px;
    text-transform: uppercase; transition: color .3s;
}
.req-list { display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; }
.req-item {
    font-size:10px; padding:3px 8px; border-radius:20px;
    border:1px solid #2a2a2a; color:#555; transition:all .25s;
    display:flex; align-items:center; gap:4px;
}
.req-item.met {
    color:#4caf50; border-color:rgba(76,175,80,.3);
    background:rgba(76,175,80,.07);
}

/* Toggle button */
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
     EDIT FORM
══════════════════════════════════════════════════════ --}}
<div style="max-width:700px">
    <div class="card card-body" style="padding:24px">

        {{-- Header --}}
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #1e1e1e">
            <div style="width:44px;height:44px;border-radius:50%;background:rgba(220,0,0,.1);border:1px solid rgba(220,0,0,.3);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-truck" style="color:var(--ferrari-red);font-size:16px"></i>
            </div>
            <div>
                <div style="font-family:'Bebas Neue',sans-serif;font-size:18px;letter-spacing:2px">{{ $driver->name }}</div>
                <div style="color:var(--gray);font-size:11px">{{ $driver->email }}</div>
            </div>
            @php $sc = ['available'=>'badge-delivered','busy'=>'badge-processing','offline'=>'badge-cancelled']; @endphp
            <span class="badge {{ $sc[$driver->driver_status] ?? '' }}" style="margin-left:auto">
                {{ strtoupper($driver->driver_status) }}
            </span>
        </div>

        <form id="editForm" method="POST" action="{{ route('admin.drivers.update', $driver) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="admin_password" id="adminPasswordField">

            <div class="driver-grid">

                {{-- Full Name --}}
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" class="form-control"
                        value="{{ old('name', $driver->name) }}" required>
                    <div class="form-error">@error('name'){{ $message }}@enderror</div>
                </div>

                {{-- Contact --}}
                <div class="form-group">
                    <label>Contact Number *</label>
                    <input type="text" name="contact_number" class="form-control"
                        value="{{ old('contact_number', $driver->contact_number) }}"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                        maxlength="15" required>
                    <div class="form-error">@error('contact_number'){{ $message }}@enderror</div>
                </div>

                {{-- License --}}
                <div class="form-group">
                    <label>License Number</label>
                    <input type="text" name="license_number" class="form-control"
                        value="{{ old('license_number', $driver->license_number) }}"
                        placeholder="e.g. N01-12-345678">
                    <div class="form-error">@error('license_number'){{ $message }}@enderror</div>
                </div>

                {{-- Vehicle --}}
                <div class="form-group">
                    <label>Vehicle Info</label>
                    <input type="text" name="vehicle_info" class="form-control"
                        value="{{ old('vehicle_info', $driver->vehicle_info) }}"
                        placeholder="e.g. Toyota Vios 2022 — ABC 123">
                    <div class="form-error">@error('vehicle_info'){{ $message }}@enderror</div>
                </div>

                {{-- Address --}}
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control"
                        value="{{ old('address', $driver->address) }}">
                    <div class="form-error">@error('address'){{ $message }}@enderror</div>
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label>Driver Status</label>
                    <select name="driver_status" class="form-control">
                        @foreach(['available', 'busy', 'offline'] as $s)
                            <option value="{{ $s }}" {{ $driver->driver_status === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-error">@error('driver_status'){{ $message }}@enderror</div>
                </div>

                {{-- Is Active --}}
                <div class="form-group span-2" style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                    <input type="checkbox" name="is_active" id="is_active"
                        style="accent-color:var(--ferrari-red);width:16px;height:16px"
                        {{ $driver->is_active ? 'checked' : '' }}>
                    <label for="is_active"
                        style="text-transform:none;font-size:14px;color:var(--light);
                               margin-bottom:0;letter-spacing:0;font-weight:400;cursor:pointer">
                        Account is Active
                    </label>
                </div>

                {{-- Divider --}}
                <div class="span-2" style="border-top:1px solid #1e1e1e;padding-top:16px;margin-bottom:4px">
                    <span style="font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--gray)">
                        Change Password
                    </span>
                    <span style="color:#444;font-size:10px;margin-left:8px">(leave blank to keep current)</span>
                </div>

                {{-- New Password --}}
                <div class="form-group">
                    <label>New Password</label>
                    <div class="pw-wrap">
                        <input type="password" id="newPassword" name="password" class="form-control"
                            placeholder="Min. 8 chars"
                            oninput="checkStrength(this.value)"
                            style="padding-right:40px">
                        <button type="button" class="pw-toggle" onclick="togglePw('newPassword','eyeNew')">
                            <i id="eyeNew" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                    <div class="strength-label" id="strengthLabel" style="color:#555"></div>
                    <div class="req-list">
                        <span class="req-item" id="req-len"><i class="fas fa-circle" style="font-size:6px"></i> 8+ chars</span>
                        <span class="req-item" id="req-upper"><i class="fas fa-circle" style="font-size:6px"></i> Uppercase</span>
                        <span class="req-item" id="req-lower"><i class="fas fa-circle" style="font-size:6px"></i> Lowercase</span>
                        <span class="req-item" id="req-num"><i class="fas fa-circle" style="font-size:6px"></i> Number</span>
                        <span class="req-item" id="req-sym"><i class="fas fa-circle" style="font-size:6px"></i> Symbol</span>
                    </div>
                    <div class="form-error">@error('password'){{ $message }}@enderror</div>
                </div>

                {{-- Confirm New Password --}}
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <div class="pw-wrap">
                        <input type="password" id="confirmPwField" name="password_confirmation" class="form-control"
                            placeholder="Repeat new password"
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
                    <i class="fas fa-save"></i> Update Driver
                </button>
                <a href="{{ route('admin.drivers.index') }}" class="btn btn-gray">Cancel</a>
            </div>

        </form>
    </div>
</div>

<script>
const VERIFY_URL = '{{ route('admin.verify-password') }}';
const CSRF       = '{{ csrf_token() }}';

/* ── Toggle password visibility ── */
function togglePw(inputId, eyeId) {
    const inp = document.getElementById(inputId);
    const eye = document.getElementById(eyeId);
    inp.type      = inp.type === 'password' ? 'text' : 'password';
    eye.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}

/* ── Strength checker ── */
function checkStrength(val) {
    if (!val) {
        document.getElementById('strengthFill').style.width = '0%';
        document.getElementById('strengthLabel').textContent = '';
        ['req-len','req-upper','req-lower','req-num','req-sym'].forEach(id => {
            document.getElementById(id).classList.remove('met');
        });
        checkMatch();
        return;
    }
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
    const levels = [
        { pct:'0%',   color:'#333',    text:'' },
        { pct:'20%',  color:'#e53935', text:'Very Weak' },
        { pct:'40%',  color:'#fb8c00', text:'Weak' },
        { pct:'60%',  color:'#fdd835', text:'Fair' },
        { pct:'80%',  color:'#43a047', text:'Strong' },
        { pct:'100%', color:'#00c853', text:'Very Strong' },
    ];
    document.getElementById('strengthFill').style.width      = levels[score].pct;
    document.getElementById('strengthFill').style.background = levels[score].color;
    document.getElementById('strengthLabel').style.color     = levels[score].color;
    document.getElementById('strengthLabel').textContent     = levels[score].text;
    checkMatch();
}

/* ── Match checker ── */
function checkMatch() {
    const pw  = document.getElementById('newPassword').value;
    const cpw = document.getElementById('confirmPwField').value;
    const msg = document.getElementById('matchMsg');
    if (!cpw || !pw) { msg.style.display = 'none'; return; }
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

/* ── Open modal ── */
function openConfirm() {
    const pw  = document.getElementById('newPassword').value;
    const cpw = document.getElementById('confirmPwField').value;

    // If password fields are filled, validate them first
    if (pw || cpw) {
        if (pw !== cpw) {
            document.getElementById('confirmPwField').focus();
            return;
        }
        const allMet = pw.length >= 8
            && /[A-Z]/.test(pw)
            && /[a-z]/.test(pw)
            && /[0-9]/.test(pw)
            && /[^A-Za-z0-9]/.test(pw);
        if (!allMet) {
            document.getElementById('newPassword').focus();
            return;
        }
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

/* ── Close modal ── */
function closeConfirm() {
    document.getElementById('confirmOverlay').style.display = 'none';
}
document.getElementById('confirmOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeConfirm();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeConfirm();
});

/* ── Submit ── */
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
        document.getElementById('editForm').submit();
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