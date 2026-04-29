@extends('layouts.admin')
@section('title','Edit Car')
@section('page-title','Edit Car')
@section('content')

<script>
const VERIFY_URL = '{{ route('admin.verify-password') }}';
const CSRF       = '{{ csrf_token() }}';
</script>

<style>
@keyframes modalIn {
    from { opacity:0; transform:translateY(16px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
@keyframes spin { to { transform:rotate(360deg); } }
</style>

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
            All unsaved changes will be <strong style="color:#fff">lost</strong>.
        </p>

        <div style="display:flex;gap:10px">
            <button onclick="closeCancelConfirm()" style="
                flex:1;padding:11px;border:1px solid #2a2a2a;border-radius:6px;cursor:pointer;
                font-family:'Barlow',sans-serif;font-weight:700;font-size:12px;
                letter-spacing:2px;text-transform:uppercase;
                background:transparent;color:var(--gray);transition:border-color .2s,color .2s"
                onmouseover="this.style.borderColor='#555';this.style.color='#fff'"
                onmouseout="this.style.borderColor='#2a2a2a';this.style.color='var(--gray)'">
                <i class="fas fa-arrow-left" style="margin-right:6px"></i> Stay
            </button>
            <a href="{{ route('admin.cars.index') }}" style="
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
     CONFIRM UPDATE MODAL
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
                <i class="fas fa-pen" style="color:var(--ferrari-red);font-size:16px"></i>
            </div>
            <div>
                <div style="font-family:'Bebas Neue',sans-serif;font-size:18px;letter-spacing:2px">Update Car</div>
                <div style="color:var(--gray);font-size:11px;margin-top:1px">Admin password required to proceed</div>
            </div>
        </div>

        <p style="color:#aaa;font-size:12px;line-height:1.6;margin:12px 0 16px">
            You are about to save changes to <strong style="color:#fff">{{ $car->name }}</strong>.<br>
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
                display:flex;align-items:center;justify-content:center;gap:8px;
                transition:background .2s,transform .15s"
                onmouseover="this.style.background='#b00000';this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='var(--ferrari-red)';this.style.transform=''">
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

{{-- ══════════════════════════════════════════════════════
     EDIT FORM
══════════════════════════════════════════════════════ --}}
<div style="max-width:860px;">
    <div class="card card-body">
        <form id="editCarForm" method="POST" action="{{ route('admin.cars.update', $car) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.cars._form')
            <div style="margin-top:20px;display:flex;gap:10px">
                <button type="button" class="btn btn-red" onclick="openConfirm()">
                    <i class="fas fa-save"></i> Update Car
                </button>
                <button type="button" class="btn btn-gray" onclick="openCancelConfirm()">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePw(inputId, eyeId) {
    const inp = document.getElementById(inputId);
    const eye = document.getElementById(eyeId);
    inp.type      = inp.type === 'password' ? 'text' : 'password';
    eye.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}

/* ── Cancel Confirm ── */
function openCancelConfirm() {
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
document.getElementById('cancelOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeCancelConfirm();
});

/* ── Update Confirm ── */
function openConfirm() {
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
function closeConfirm() {
    document.getElementById('confirmOverlay').style.display = 'none';
}
document.getElementById('confirmOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeConfirm();
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeConfirm(); closeCancelConfirm(); }
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
        document.getElementById('editCarForm').submit();
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