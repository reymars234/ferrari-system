@extends('layouts.admin')
@section('title','Manage Cars')
@section('page-title','Cars')
@section('content')

{{-- ══════════════════════════════════════════════════════
     SHARED: Admin Password Verify URL & CSRF
══════════════════════════════════════════════════════ --}}
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

/* ═══════════════════════════════════════════
   MODAL BASE — responsive
═══════════════════════════════════════════ */
.confirm-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 10000;
    background: rgba(0,0,0,.78);
    backdrop-filter: blur(6px);
    align-items: center;
    justify-content: center;
    padding: 16px;
}
.confirm-modal {
    background: #111;
    border: 1px solid rgba(220,0,0,.25);
    border-radius: 14px;
    padding: 28px 24px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 32px 80px rgba(0,0,0,.6);
    animation: modalIn .25s cubic-bezier(.25,.8,.25,1) both;
}
.confirm-modal-head {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
}
.confirm-modal-icon {
    width: 42px; height: 42px;
    border-radius: 50%;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(220,0,0,.1);
    border: 1px solid rgba(220,0,0,.3);
}
.confirm-modal-icon i { color: var(--red); font-size: 16px; }
.confirm-modal-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 18px;
    letter-spacing: 2px;
}
.confirm-modal-sub { color: var(--gray); font-size: 11px; margin-top: 1px; }
.confirm-modal-desc {
    color: #aaa;
    font-size: 12px;
    line-height: 1.6;
    margin: 12px 0 4px;
}
.confirm-modal-desc strong { color: #fff; }

/* Password field */
.pw-wrap { margin: 16px 0; }
.pw-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--gray);
    display: block;
    margin-bottom: 6px;
}
.pw-field-wrap { position: relative; }
.pw-input {
    width: 100%;
    box-sizing: border-box;
    padding: 10px 40px 10px 14px;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 6px;
    color: #fff;
    font-size: 13px;
    outline: none;
    transition: border-color .2s;
    font-family: 'Barlow', sans-serif;
}
.pw-input:focus { border-color: rgba(220,0,0,.5); }
.pw-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #555;
    cursor: pointer;
    font-size: 13px;
    transition: color .2s;
    background: none;
    border: none;
    padding: 0;
    line-height: 1;
}
.pw-toggle:hover { color: var(--red); }

.pw-error {
    color: var(--red);
    font-size: 11px;
    margin-top: 6px;
    display: none;
    align-items: center;
    gap: 6px;
}

/* Modal action buttons */
.confirm-modal-actions {
    display: flex;
    gap: 10px;
}
.confirm-modal-actions .btn-confirm-ok {
    flex: 1;
    padding: 11px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-family: 'Barlow', sans-serif;
    font-weight: 700;
    font-size: 12px;
    letter-spacing: 2px;
    text-transform: uppercase;
    background: var(--red);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background .2s, transform .15s;
}
.confirm-modal-actions .btn-confirm-ok:hover {
    background: #b00000;
    transform: translateY(-1px);
}
.confirm-modal-actions .btn-confirm-cancel {
    flex: 1;
    padding: 11px;
    border: 1px solid #2a2a2a;
    border-radius: 6px;
    cursor: pointer;
    font-family: 'Barlow', sans-serif;
    font-weight: 700;
    font-size: 12px;
    letter-spacing: 2px;
    text-transform: uppercase;
    background: transparent;
    color: var(--gray);
    transition: border-color .2s, color .2s;
}
.confirm-modal-actions .btn-confirm-cancel:hover {
    border-color: #444;
    color: #fff;
}
.btn-spinner {
    display: none;
    width: 14px; height: 14px;
    border: 2px solid rgba(255,255,255,.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .7s linear infinite;
    flex-shrink: 0;
}

/* ═══════════════════════════════════════════
   TABLE — responsive cards on mobile
═══════════════════════════════════════════ */

/* Action cell aligned */
.actions-cell {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* Mobile card list — hidden on desktop */
.cars-mobile-list { display: none; }

.car-card {
    background: var(--dark3);
    border: 1px solid rgba(220,0,0,.08);
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    transition: border-color .25s;
}
.car-card:hover { border-color: rgba(220,0,0,.22); }

.car-card-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}
.car-card-label {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--gray);
    flex-shrink: 0;
}
.car-card-value { font-size: 13px; font-weight: 600; text-align: right; }
.car-card-name { font-size: 14px; font-weight: 700; color: var(--light); }
.car-card-actions {
    display: flex;
    gap: 8px;
    padding-top: 6px;
    border-top: 1px solid rgba(255,255,255,.05);
    flex-wrap: wrap;
}
.car-card-actions .btn { flex: 1; justify-content: center; min-width: 80px; }

/* Header action row */
.card-header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

@media (max-width: 640px) {
    /* Hide desktop table, show mobile cards */
    .cars-desktop-table { display: none !important; }
    .cars-mobile-list   { display: flex; flex-direction: column; gap: 12px; padding: 16px; }

    /* Card header stacks on very small screens */
    .card-header { flex-wrap: wrap; gap: 10px; }
    .card-header h3 { font-size: 17px; }
}

@media (max-width: 400px) {
    .confirm-modal { padding: 20px 16px; }
    .confirm-modal-actions { flex-direction: column; }
}
</style>

{{-- ══════════════════════════════════════════════════════
     DELETE CONFIRMATION MODAL
══════════════════════════════════════════════════════ --}}
<div id="deleteOverlay" class="confirm-overlay">
    <div id="deleteModal" class="confirm-modal">
        <div class="confirm-modal-head">
            <div class="confirm-modal-icon"><i class="fas fa-trash"></i></div>
            <div>
                <div class="confirm-modal-title">Delete Car</div>
                <div class="confirm-modal-sub">This action cannot be undone</div>
            </div>
        </div>
        <p class="confirm-modal-desc">
            You are about to delete <strong id="deleteCarName"></strong>.<br>
            Enter your admin password to confirm.
        </p>
        <div class="pw-wrap">
            <label class="pw-label">Your Admin Password</label>
            <div class="pw-field-wrap">
                <input type="password" id="deleteConfirmPassword" class="pw-input"
                    placeholder="Enter your password to confirm"
                    oninput="clearDeleteError()"
                    onkeydown="if(event.key==='Enter')submitDelete()">
                <button type="button" class="pw-toggle" onclick="togglePwVisibility('deleteConfirmPassword','deleteEye')">
                    <i id="deleteEye" class="fas fa-eye"></i>
                </button>
            </div>
            <div id="deleteConfirmError" class="pw-error">
                <i class="fas fa-exclamation-circle"></i>
                <span id="deleteConfirmErrorMsg">Incorrect password.</span>
            </div>
        </div>
        <div class="confirm-modal-actions">
            <button id="deleteOkBtn" class="btn-confirm-ok" onclick="submitDelete()">
                <i id="deleteOkIcon" class="fas fa-trash"></i>
                <span id="deleteOkText">Yes, Delete</span>
                <div id="deleteSpinner" class="btn-spinner"></div>
            </button>
            <button class="btn-confirm-cancel" onclick="closeDeleteModal()">Cancel</button>
        </div>
    </div>
</div>

{{-- Hidden delete form --}}
<form id="deleteForm" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>

{{-- ══════════════════════════════════════════════════════
     EDIT CONFIRMATION MODAL
══════════════════════════════════════════════════════ --}}
<div id="editOverlay" class="confirm-overlay">
    <div id="editModal" class="confirm-modal">
        <div class="confirm-modal-head">
            <div class="confirm-modal-icon"><i class="fas fa-pen"></i></div>
            <div>
                <div class="confirm-modal-title">Edit Car</div>
                <div class="confirm-modal-sub">Admin password required to proceed</div>
            </div>
        </div>
        <p class="confirm-modal-desc">
            You are about to edit <strong id="editCarName"></strong>.<br>
            Enter your admin password to continue.
        </p>
        <div class="pw-wrap">
            <label class="pw-label">Your Admin Password</label>
            <div class="pw-field-wrap">
                <input type="password" id="editConfirmPassword" class="pw-input"
                    placeholder="Enter your password to confirm"
                    oninput="clearEditError()"
                    onkeydown="if(event.key==='Enter')submitEdit()">
                <button type="button" class="pw-toggle" onclick="togglePwVisibility('editConfirmPassword','editEye')">
                    <i id="editEye" class="fas fa-eye"></i>
                </button>
            </div>
            <div id="editConfirmError" class="pw-error">
                <i class="fas fa-exclamation-circle"></i>
                <span id="editConfirmErrorMsg">Incorrect password.</span>
            </div>
        </div>
        <div class="confirm-modal-actions">
            <button id="editOkBtn" class="btn-confirm-ok" onclick="submitEdit()">
                <i id="editOkIcon" class="fas fa-pen"></i>
                <span id="editOkText">Go to Edit</span>
                <div id="editSpinner" class="btn-spinner"></div>
            </button>
            <button class="btn-confirm-cancel" onclick="closeEditModal()">Cancel</button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     ADD CAR CONFIRMATION MODAL
══════════════════════════════════════════════════════ --}}
<div id="addOverlay" class="confirm-overlay">
    <div id="addModal" class="confirm-modal">
        <div class="confirm-modal-head">
            <div class="confirm-modal-icon"><i class="fas fa-plus"></i></div>
            <div>
                <div class="confirm-modal-title">Add New Car</div>
                <div class="confirm-modal-sub">Admin password required to proceed</div>
            </div>
        </div>
        <p class="confirm-modal-desc">
            You are about to navigate to the Add Car page.<br>
            Enter your admin password to continue.
        </p>
        <div class="pw-wrap">
            <label class="pw-label">Your Admin Password</label>
            <div class="pw-field-wrap">
                <input type="password" id="addConfirmPassword" class="pw-input"
                    placeholder="Enter your password to confirm"
                    oninput="clearAddError()"
                    onkeydown="if(event.key==='Enter')submitAdd()">
                <button type="button" class="pw-toggle" onclick="togglePwVisibility('addConfirmPassword','addEye')">
                    <i id="addEye" class="fas fa-eye"></i>
                </button>
            </div>
            <div id="addConfirmError" class="pw-error">
                <i class="fas fa-exclamation-circle"></i>
                <span id="addConfirmErrorMsg">Incorrect password.</span>
            </div>
        </div>
        <div class="confirm-modal-actions">
            <button id="addOkBtn" class="btn-confirm-ok" onclick="submitAdd()">
                <i id="addOkIcon" class="fas fa-plus"></i>
                <span id="addOkText">Go to Add Car</span>
                <div id="addSpinner" class="btn-spinner"></div>
            </button>
            <button class="btn-confirm-cancel" onclick="closeAddModal()">Cancel</button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     TABLE / CARD LIST
══════════════════════════════════════════════════════ --}}
<div class="card">
    <div class="card-header">
        <h3>All Cars</h3>
        <div class="card-header-actions">
            <button type="button" class="btn btn-red" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Add Car
            </button>
        </div>
    </div>

    {{-- ── Desktop Table (hidden on mobile) ── --}}
    <div class="table-wrap cars-desktop-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Available</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cars as $car)
                <tr>
                    <td>{{ $car->id }}</td>
                    <td>{{ $car->name }}</td>
                    <td>₱{{ number_format($car->price,2) }}</td>
                    <td>{{ $car->stock }}</td>
                    <td>
                        @if($car->is_available)
                            <span class="badge badge-delivered">Yes</span>
                        @else
                            <span class="badge badge-cancelled">No</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions-cell">
                            <button type="button" class="btn btn-outline btn-sm"
                                onclick="openEditModal('{{ addslashes($car->name) }}', '{{ route('admin.cars.edit', $car) }}')">
                                <i class="fas fa-pen"></i> Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="openDeleteModal('{{ addslashes($car->name) }}', '{{ route('admin.cars.destroy', $car) }}')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--gray);padding:40px;">
                        No cars yet.
                        <a href="#" onclick="openAddModal();return false;" style="color:var(--red);">Add one.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Mobile Card List (hidden on desktop) ── --}}
    <div class="cars-mobile-list">
        @forelse($cars as $car)
        <div class="car-card">
            <div class="car-card-row">
                <span class="car-card-name">{{ $car->name }}</span>
                @if($car->is_available)
                    <span class="badge badge-delivered">Available</span>
                @else
                    <span class="badge badge-cancelled">Unavailable</span>
                @endif
            </div>
            <div class="car-card-row">
                <span class="car-card-label">Price</span>
                <span class="car-card-value">₱{{ number_format($car->price,2) }}</span>
            </div>
            <div class="car-card-row">
                <span class="car-card-label">Stock</span>
                <span class="car-card-value">{{ $car->stock }}</span>
            </div>
            <div class="car-card-row">
                <span class="car-card-label">ID</span>
                <span class="car-card-value" style="color:var(--gray);font-size:12px">#{{ $car->id }}</span>
            </div>
            <div class="car-card-actions">
                <button type="button" class="btn btn-outline btn-sm"
                    onclick="openEditModal('{{ addslashes($car->name) }}', '{{ route('admin.cars.edit', $car) }}')">
                    <i class="fas fa-pen"></i> Edit
                </button>
                <button type="button" class="btn btn-danger btn-sm"
                    onclick="openDeleteModal('{{ addslashes($car->name) }}', '{{ route('admin.cars.destroy', $car) }}')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
        @empty
        <div style="text-align:center;color:var(--gray);padding:32px 0;">
            No cars yet.
            <a href="#" onclick="openAddModal();return false;" style="color:var(--red);">Add one.</a>
        </div>
        @endforelse
    </div>

    <div style="padding:16px 24px;">{{ $cars->links() }}</div>
</div>

<script>
// ─── Shared Helpers ────────────────────────────────────────────
function togglePwVisibility(inputId, eyeId) {
    const inp = document.getElementById(inputId);
    const eye = document.getElementById(eyeId);
    inp.type      = inp.type === 'password' ? 'text' : 'password';
    eye.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}

function openModal(overlayId, modalId, focusId) {
    const overlay = document.getElementById(overlayId);
    const modal   = document.getElementById(modalId);
    overlay.style.display = 'flex';
    modal.style.animation = 'none';
    void modal.offsetWidth;
    modal.style.animation = 'modalIn .25s cubic-bezier(.25,.8,.25,1) both';
    setTimeout(() => document.getElementById(focusId)?.focus(), 80);
}

function closeModal(overlayId) {
    document.getElementById(overlayId).style.display = 'none';
}

function setLoading(btnId, iconId, textId, spinnerId, on) {
    const btn = document.getElementById(btnId);
    btn.disabled = on;
    btn.style.opacity = on ? '0.7' : '';
    document.getElementById(iconId).style.display    = on ? 'none'  : '';
    document.getElementById(textId).style.display    = on ? 'none'  : '';
    document.getElementById(spinnerId).style.display = on ? 'block' : 'none';
}

function showError(errorDivId, msgId, pwInputId, message) {
    document.getElementById(msgId).textContent = message;
    document.getElementById(errorDivId).style.display = 'flex';
    document.getElementById(pwInputId).style.borderColor = 'rgba(220,0,0,.6)';
}

function clearError(errorDivId, pwInputId) {
    document.getElementById(errorDivId).style.display = 'none';
    document.getElementById(pwInputId).style.borderColor = '#2a2a2a';
}

async function verifyAndProceed({ passwordInputId, errorDivId, errorMsgId, btnId, iconId, textId, spinnerId, onSuccess }) {
    const pw = document.getElementById(passwordInputId).value.trim();
    if (!pw) {
        showError(errorDivId, errorMsgId, passwordInputId, 'Please enter your admin password.');
        document.getElementById(passwordInputId).focus();
        return;
    }
    setLoading(btnId, iconId, textId, spinnerId, true);
    clearError(errorDivId, passwordInputId);
    try {
        const res  = await fetch(VERIFY_URL, {
            method : 'POST',
            headers: { 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN': CSRF },
            body   : JSON.stringify({ password: pw }),
        });
        const data = await res.json();
        if (!data.verified) {
            setLoading(btnId, iconId, textId, spinnerId, false);
            showError(errorDivId, errorMsgId, passwordInputId, data.message || 'Incorrect password. Please try again.');
            document.getElementById(passwordInputId).value = '';
            document.getElementById(passwordInputId).focus();
            return;
        }
        onSuccess();
    } catch (err) {
        setLoading(btnId, iconId, textId, spinnerId, false);
        showError(errorDivId, errorMsgId, passwordInputId, 'Something went wrong. Please try again.');
    }
}

// Close on overlay click / Escape
['deleteOverlay','editOverlay','addOverlay'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) closeModal(id);
    });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        ['deleteOverlay','editOverlay','addOverlay'].forEach(closeModal);
    }
});

// ─── DELETE ───────────────────────────────────────────────────
let _deleteUrl = '';

function openDeleteModal(name, url) {
    _deleteUrl = url;
    document.getElementById('deleteCarName').textContent = name;
    document.getElementById('deleteConfirmPassword').value = '';
    clearDeleteError();
    setLoading('deleteOkBtn','deleteOkIcon','deleteOkText','deleteSpinner', false);
    openModal('deleteOverlay','deleteModal','deleteConfirmPassword');
}
function closeDeleteModal() { closeModal('deleteOverlay'); }
function clearDeleteError()  { clearError('deleteConfirmError','deleteConfirmPassword'); }

function submitDelete() {
    verifyAndProceed({
        passwordInputId: 'deleteConfirmPassword',
        errorDivId     : 'deleteConfirmError',
        errorMsgId     : 'deleteConfirmErrorMsg',
        btnId          : 'deleteOkBtn',
        iconId         : 'deleteOkIcon',
        textId         : 'deleteOkText',
        spinnerId      : 'deleteSpinner',
        onSuccess() {
            const form = document.getElementById('deleteForm');
            form.action = _deleteUrl;
            form.submit();
        }
    });
}

// ─── EDIT ─────────────────────────────────────────────────────
let _editUrl = '';

function openEditModal(name, url) {
    _editUrl = url;
    document.getElementById('editCarName').textContent = name;
    document.getElementById('editConfirmPassword').value = '';
    clearEditError();
    setLoading('editOkBtn','editOkIcon','editOkText','editSpinner', false);
    openModal('editOverlay','editModal','editConfirmPassword');
}
function closeEditModal() { closeModal('editOverlay'); }
function clearEditError()  { clearError('editConfirmError','editConfirmPassword'); }

function submitEdit() {
    verifyAndProceed({
        passwordInputId: 'editConfirmPassword',
        errorDivId     : 'editConfirmError',
        errorMsgId     : 'editConfirmErrorMsg',
        btnId          : 'editOkBtn',
        iconId         : 'editOkIcon',
        textId         : 'editOkText',
        spinnerId      : 'editSpinner',
        onSuccess() { window.location.href = _editUrl; }
    });
}

// ─── ADD ──────────────────────────────────────────────────────
const ADD_URL = '{{ route('admin.cars.create') }}';

function openAddModal() {
    document.getElementById('addConfirmPassword').value = '';
    clearAddError();
    setLoading('addOkBtn','addOkIcon','addOkText','addSpinner', false);
    openModal('addOverlay','addModal','addConfirmPassword');
}
function closeAddModal() { closeModal('addOverlay'); }
function clearAddError()  { clearError('addConfirmError','addConfirmPassword'); }

function submitAdd() {
    verifyAndProceed({
        passwordInputId: 'addConfirmPassword',
        errorDivId     : 'addConfirmError',
        errorMsgId     : 'addConfirmErrorMsg',
        btnId          : 'addOkBtn',
        iconId         : 'addOkIcon',
        textId         : 'addOkText',
        spinnerId      : 'addSpinner',
        onSuccess() { window.location.href = ADD_URL; }
    });
}
</script>

@endsection