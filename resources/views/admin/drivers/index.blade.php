@extends('layouts.admin')
@section('title','Drivers')
@section('page-title','Driver Accounts')
@section('content')

{{-- ══════════════════════════════════════════════════════
     CONFIRM MODAL  (Edit & Delete with admin password gate)
══════════════════════════════════════════════════════ --}}
<div id="confirmOverlay" style="
    display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,.72); backdrop-filter:blur(4px);
    align-items:center; justify-content:center; padding:16px;">

    <div id="confirmModal" style="
        background:#111; border:1px solid rgba(220,0,0,.25);
        border-radius:14px; padding:32px 28px; width:100%; max-width:420px;
        box-shadow:0 32px 80px rgba(0,0,0,.6);
        animation:modalIn .25s cubic-bezier(.25,.8,.25,1) both;">

        {{-- Icon + title --}}
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
            <div id="confirmIconWrap" style="
                width:44px;height:44px;border-radius:50%;flex-shrink:0;
                display:flex;align-items:center;justify-content:center;
                background:rgba(220,0,0,.1);border:1px solid rgba(220,0,0,.3)">
                <i id="confirmIcon" class="fas fa-exclamation-triangle" style="color:var(--ferrari-red);font-size:18px"></i>
            </div>
            <div>
                <div id="confirmTitle" style="font-family:'Bebas Neue',sans-serif;font-size:20px;letter-spacing:2px">Confirm Action</div>
                <div id="confirmSub" style="color:var(--gray);font-size:12px;margin-top:1px"></div>
            </div>
        </div>

        <p id="confirmMsg" style="color:#aaa;font-size:13px;line-height:1.6;margin:14px 0 20px"></p>

        {{-- Admin password field --}}
        <div style="margin-bottom:20px">
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
                <span onclick="toggleConfirmPw()" style="
                    position:absolute;right:12px;top:50%;transform:translateY(-50%);
                    color:#555;cursor:pointer;font-size:13px;transition:color .2s"
                    onmouseover="this.style.color='var(--ferrari-red)'"
                    onmouseout="this.style.color='#555'">
                    <i id="confirmEye" class="fas fa-eye"></i>
                </span>
            </div>
            <div id="confirmError" style="
                color:var(--ferrari-red);font-size:11px;margin-top:6px;
                display:none;align-items:center;gap:6px">
                <i class="fas fa-exclamation-circle"></i>
                <span id="confirmErrorMsg">Incorrect password. Please try again.</span>
            </div>
        </div>

        {{-- Buttons --}}
        <div style="display:flex;gap:10px">
            <button id="confirmOkBtn" onclick="submitConfirm()" style="
                flex:1;padding:12px;border:none;border-radius:6px;cursor:pointer;
                font-family:'Barlow',sans-serif;font-weight:700;font-size:13px;
                letter-spacing:2px;text-transform:uppercase;
                background:var(--ferrari-red);color:#fff;
                transition:background .2s,transform .15s,box-shadow .2s;
                display:flex;align-items:center;justify-content:center;gap:8px"
                onmouseover="this.style.background='#b00000';this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 20px rgba(220,0,0,.3)'"
                onmouseout="this.style.background='var(--ferrari-red)';this.style.transform='';this.style.boxShadow=''">
                <i id="confirmOkIcon" class="fas fa-check"></i>
                <span id="confirmOkText">Confirm</span>
                <div id="confirmSpinner" style="display:none;width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite"></div>
            </button>
            <button onclick="closeConfirm()" style="
                flex:1;padding:12px;border:1px solid #2a2a2a;border-radius:6px;cursor:pointer;
                font-family:'Barlow',sans-serif;font-weight:700;font-size:13px;
                letter-spacing:2px;text-transform:uppercase;
                background:transparent;color:var(--gray);
                transition:border-color .2s,color .2s"
                onmouseover="this.style.borderColor='#444';this.style.color='#fff'"
                onmouseout="this.style.borderColor='#2a2a2a';this.style.color='var(--gray)'">
                Cancel
            </button>
        </div>

    </div>
</div>

{{-- Hidden delete forms --}}
@foreach($drivers as $driver)
    <form id="deleteForm-{{ $driver->id }}" method="POST"
          action="{{ route('admin.drivers.destroy', $driver) }}" style="display:none">
        @csrf
        @method('DELETE')
        <input type="hidden" name="admin_password" class="admin-password-field">
    </form>
@endforeach

<style>
@keyframes modalIn {
    from { opacity:0; transform:translateY(20px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
@keyframes spin { to { transform:rotate(360deg); } }
#confirmPassword:focus { border-color: rgba(220,0,0,.5) !important; }
</style>

{{-- ══════════════════════════════════════════════════════
     MAIN TABLE
══════════════════════════════════════════════════════ --}}
<div class="card">
    <div class="card-header">
        <h3>All Drivers</h3>
        <a href="{{ route('admin.drivers.create') }}" class="btn btn-red">+ Add Driver</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>License</th>
                    <th>Vehicle</th>
                    <th>Status</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $driver)
                <tr>
                    <td>{{ $driver->id }}</td>
                    <td>{{ $driver->name }}</td>
                    <td style="color:var(--gray)">{{ $driver->email }}</td>
                    <td>{{ $driver->contact_number }}</td>
                    <td style="color:var(--gray);font-size:12px">{{ $driver->license_number ?? '—' }}</td>
                    <td style="color:var(--gray);font-size:12px">{{ $driver->vehicle_info ?? '—' }}</td>
                    <td>
                        @php $sc = ['available'=>'badge-delivered','busy'=>'badge-processing','offline'=>'badge-cancelled']; @endphp
                        <span class="badge {{ $sc[$driver->driver_status] ?? '' }}">
                            {{ $driver->driver_status }}
                        </span>
                    </td>
                    <td>
                        @if($driver->is_active)
                            <span class="badge badge-delivered">Active</span>
                        @else
                            <span class="badge badge-cancelled">Inactive</span>
                        @endif
                    </td>
                    <td style="display:flex;gap:8px;padding:12px 16px">
                        <button type="button" class="btn btn-outline btn-sm"
                            onclick="openConfirm('edit', {{ $driver->id }}, '{{ addslashes($driver->name) }}', '{{ route('admin.drivers.edit', $driver) }}')">
                            Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="openConfirm('delete', {{ $driver->id }}, '{{ addslashes($driver->name) }}', null)">
                            Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;color:var(--gray);padding:40px">
                        No drivers yet.
                        <a href="{{ route('admin.drivers.create') }}" style="color:var(--ferrari-red)">Add one.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:14px 22px">{{ $drivers->links() }}</div>
</div>

<script>
let _action   = null;
let _driverId = null;
let _editUrl  = null;

const VERIFY_URL = '{{ route('admin.verify-password') }}';
const CSRF       = '{{ csrf_token() }}';

function openConfirm(action, driverId, driverName, editUrl) {
    _action   = action;
    _driverId = driverId;
    _editUrl  = editUrl;

    const overlay  = document.getElementById('confirmOverlay');
    const modal    = document.getElementById('confirmModal');
    const icon     = document.getElementById('confirmIcon');
    const iconWrap = document.getElementById('confirmIconWrap');
    const title    = document.getElementById('confirmTitle');
    const sub      = document.getElementById('confirmSub');
    const msg      = document.getElementById('confirmMsg');
    const okIcon   = document.getElementById('confirmOkIcon');
    const okText   = document.getElementById('confirmOkText');
    const okBtn    = document.getElementById('confirmOkBtn');

    document.getElementById('confirmPassword').value = '';
    clearConfirmError();
    setConfirmLoading(false);

    if (action === 'delete') {
        icon.className            = 'fas fa-trash-alt';
        icon.style.color          = 'var(--ferrari-red)';
        iconWrap.style.background = 'rgba(220,0,0,.1)';
        iconWrap.style.border     = '1px solid rgba(220,0,0,.3)';
        title.textContent         = 'Delete Driver';
        sub.textContent           = 'This action cannot be undone';
        msg.innerHTML             = `You are about to permanently delete <strong style="color:#fff">${driverName}</strong>.<br>Enter your admin password to confirm.`;
        okIcon.className          = 'fas fa-trash-alt';
        okText.textContent        = 'Delete';
        okBtn.style.background    = 'var(--ferrari-red)';
        okBtn.onmouseover = function() {
            this.style.background = '#b00000';
            this.style.transform  = 'translateY(-1px)';
            this.style.boxShadow  = '0 8px 20px rgba(220,0,0,.3)';
        };
        okBtn.onmouseout = function() {
            this.style.background = 'var(--ferrari-red)';
            this.style.transform  = '';
            this.style.boxShadow  = '';
        };
    } else {
        icon.className            = 'fas fa-edit';
        icon.style.color          = '#4a9eff';
        iconWrap.style.background = 'rgba(74,158,255,.1)';
        iconWrap.style.border     = '1px solid rgba(74,158,255,.3)';
        title.textContent         = 'Edit Driver';
        sub.textContent           = 'Password required to proceed';
        msg.innerHTML             = `You are about to edit <strong style="color:#fff">${driverName}</strong>.<br>Enter your admin password to continue.`;
        okIcon.className          = 'fas fa-edit';
        okText.textContent        = 'Proceed to Edit';
        okBtn.style.background    = '#185fa5';
        okBtn.onmouseover = function() {
            this.style.background = '#0c447c';
            this.style.transform  = 'translateY(-1px)';
            this.style.boxShadow  = 'none';
        };
        okBtn.onmouseout = function() {
            this.style.background = '#185fa5';
            this.style.transform  = '';
            this.style.boxShadow  = '';
        };
    }

    overlay.style.display = 'flex';
    modal.style.animation = 'none';
    void modal.offsetWidth;
    modal.style.animation = 'modalIn .25s cubic-bezier(.25,.8,.25,1) both';
    setTimeout(() => document.getElementById('confirmPassword').focus(), 80);
}

function closeConfirm() {
    document.getElementById('confirmOverlay').style.display = 'none';
    _action = _driverId = _editUrl = null;
}

document.getElementById('confirmOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeConfirm();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeConfirm();
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
            headers: {
                'Content-Type' : 'application/json',
                'Accept'       : 'application/json',
                'X-CSRF-TOKEN' : CSRF,
            },
            body: JSON.stringify({ password: pw }),
        });

        const data = await res.json();

        if (!data.verified) {
            setConfirmLoading(false);
            showConfirmError(data.message || 'Incorrect password. Please try again.');
            document.getElementById('confirmPassword').value = '';
            document.getElementById('confirmPassword').focus();
            return;
        }

        if (_action === 'delete') {
            const form = document.getElementById('deleteForm-' + _driverId);
            form.querySelector('.admin-password-field').value = pw;
            form.submit();
        } else {
            window.location.href = _editUrl;
        }

    } catch (err) {
        setConfirmLoading(false);
        showConfirmError('Something went wrong. Please try again.');
    }
}

function setConfirmLoading(on) {
    const btn     = document.getElementById('confirmOkBtn');
    const icon    = document.getElementById('confirmOkIcon');
    const text    = document.getElementById('confirmOkText');
    const spinner = document.getElementById('confirmSpinner');
    btn.disabled          = on;
    btn.style.opacity     = on ? '0.7' : '';
    icon.style.display    = on ? 'none' : '';
    text.style.display    = on ? 'none' : '';
    spinner.style.display = on ? 'block' : 'none';
}

function showConfirmError(msg) {
    document.getElementById('confirmErrorMsg').textContent = msg;
    document.getElementById('confirmError').style.display = 'flex';
    document.getElementById('confirmPassword').style.borderColor = 'rgba(220,0,0,.6)';
}

function clearConfirmError() {
    document.getElementById('confirmError').style.display = 'none';
    document.getElementById('confirmPassword').style.borderColor = '#2a2a2a';
}

function toggleConfirmPw() {
    const inp = document.getElementById('confirmPassword');
    const eye = document.getElementById('confirmEye');
    inp.type      = inp.type === 'password' ? 'text' : 'password';
    eye.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
</script>

@endsection