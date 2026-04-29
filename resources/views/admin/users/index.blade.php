@extends('layouts.admin')
@section('title','Users & Drivers')
@section('page-title','Registered Users')
@section('content')

<script>
const VERIFY_URL = '{{ route('admin.verify-password') }}';
const CSRF       = '{{ csrf_token() }}';
</script>

<style>
/* ── Keyframes ──────────────────────────────────────── */
@keyframes modalIn {
    from { opacity:0; transform:translateY(18px) scale(.96); }
    to   { opacity:1; transform:translateY(0)    scale(1); }
}
@keyframes spin { to { transform:rotate(360deg); } }
@keyframes fadeSlideIn {
    from { opacity:0; transform:translateY(6px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ── Page header row ────────────────────────────────── */
.uu-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
    flex-wrap: wrap;
}
.uu-stat-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 5px 13px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    border: 1px solid #1e1e1e;
    background: #111;
    color: var(--gray);
    white-space: nowrap;
}
.uu-stat-pill i { font-size: 10px; }
.uu-stat-pill span { color: #fff; }

/* ── Toolbar ─────────────────────────────────────────── */
.uu-toolbar {
    display: flex;
    gap: 10px;
    margin-bottom: 16px;
    flex-wrap: wrap;
    align-items: center;
}

/* Search */
.uu-search-wrap {
    position: relative;
    flex: 1;
    min-width: 200px;
    max-width: 360px;
}
.uu-search-wrap i {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #444;
    font-size: 12px;
    pointer-events: none;
    transition: color .2s;
}
.uu-search-wrap:focus-within i { color: var(--ferrari-red); }
#userSearch {
    width: 100%;
    box-sizing: border-box;
    padding: 9px 36px 9px 36px;
    background: #111;
    border: 1px solid #1e1e1e;
    border-radius: 8px;
    color: #fff;
    font-size: 12px;
    outline: none;
    transition: border-color .2s;
}
#userSearch::placeholder { color: #444; }
#userSearch:focus { border-color: rgba(220,0,0,.4); }
#clearSearch {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #444;
    font-size: 11px;
    cursor: pointer;
    display: none;
    background: none;
    border: none;
    padding: 0;
    transition: color .2s;
}
#clearSearch:hover { color: var(--ferrari-red); }

/* Filter pills */
.uu-filter-group {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}
.uu-filter-btn {
    padding: 7px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    border: 1px solid #1e1e1e;
    background: #111;
    color: #555;
    cursor: pointer;
    transition: border-color .2s, color .2s, background .2s;
    white-space: nowrap;
}
.uu-filter-btn:hover { border-color: #333; color: #aaa; }
.uu-filter-btn.active {
    border-color: rgba(220,0,0,.5);
    background: rgba(220,0,0,.08);
    color: var(--ferrari-red);
}

/* Result count */
#resultCount {
    font-size: 11px;
    color: #444;
    letter-spacing: .5px;
    margin-left: auto;
    white-space: nowrap;
}

/* ── Table ───────────────────────────────────────────── */
.uu-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.uu-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
}
.uu-table thead tr {
    border-bottom: 1px solid #1a1a1a;
}
.uu-table th {
    padding: 10px 14px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #555;
    white-space: nowrap;
    text-align: left;
}
.uu-table td {
    padding: 12px 14px;
    font-size: 13px;
    color: var(--light);
    border-bottom: 1px solid #111;
    vertical-align: middle;
}
.uu-table tbody tr {
    transition: background .15s;
    animation: fadeSlideIn .3s ease both;
}
.uu-table tbody tr:hover { background: rgba(255,255,255,.02); }
.uu-table tbody tr.hidden-row { display: none; }

/* Avatar */
.uu-avatar {
    width: 32px; height: 32px; border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700;
    flex-shrink: 0;
}
.uu-name-cell {
    display: flex; align-items: center; gap: 10px;
}

/* Type badge */
.uu-type-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 9px; border-radius: 20px;
    font-size: 10px; font-weight: 700; letter-spacing: 1px;
    text-transform: uppercase; white-space: nowrap;
}
.uu-type-user   { background:rgba(59,130,246,.1); border:1px solid rgba(59,130,246,.25); color:#60a5fa; }
.uu-type-driver { background:rgba(220,0,0,.08);   border:1px solid rgba(220,0,0,.25);   color:var(--ferrari-red); }

/* Empty state */
.uu-empty {
    text-align: center;
    padding: 60px 20px;
    color: #333;
}
.uu-empty i { font-size: 36px; display: block; margin-bottom: 12px; }
.uu-empty p { font-size: 13px; margin: 0; }

/* ── Delete button ───────────────────────────────────── */
.btn-del {
    padding: 5px 12px;
    font-size: 11px; font-weight: 700;
    letter-spacing: 1px; text-transform: uppercase;
    border: 1px solid rgba(220,0,0,.3);
    border-radius: 5px;
    background: transparent;
    color: #dc4444;
    cursor: pointer;
    transition: background .2s, border-color .2s, color .2s, transform .15s;
    white-space: nowrap;
}
.btn-del:hover {
    background: rgba(220,0,0,.1);
    border-color: rgba(220,0,0,.5);
    color: #ff5555;
    transform: translateY(-1px);
}

/* ── Modal overlay ───────────────────────────────────── */
.uu-overlay {
    display: none;
    position: fixed; inset: 0; z-index: 10000;
    background: rgba(0,0,0,.8);
    backdrop-filter: blur(6px);
    align-items: center; justify-content: center;
    padding: 16px;
}

/* ── Responsive ──────────────────────────────────────── */
@media (max-width: 640px) {
    .uu-toolbar { gap: 8px; }
    .uu-search-wrap { max-width: 100%; flex: 1 1 100%; }
    .uu-filter-group { flex: 1 1 100%; }
    #resultCount { flex: 1 1 100%; margin-left: 0; }
    .uu-table th:nth-child(4),
    .uu-table td:nth-child(4),
    .uu-table th:nth-child(6),
    .uu-table td:nth-child(6) { display: none; }
}
@media (max-width: 480px) {
    .uu-table th:nth-child(3),
    .uu-table td:nth-child(3) { display: none; }
    .uu-header { gap: 8px; }
    .uu-stat-pill { font-size: 10px; padding: 4px 10px; }
}
</style>

{{-- ══════════════════════════════════════════════════════
     DELETE CONFIRMATION MODAL
══════════════════════════════════════════════════════ --}}
<div id="deleteOverlay" class="uu-overlay">
    <div id="deleteModal" style="
        background:#111; border:1px solid rgba(220,0,0,.25);
        border-radius:14px; padding:28px 24px; width:100%; max-width:400px;
        box-shadow:0 32px 80px rgba(0,0,0,.6);
        animation:modalIn .25s cubic-bezier(.25,.8,.25,1) both;">

        <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
            <div style="width:42px;height:42px;border-radius:50%;flex-shrink:0;
                        display:flex;align-items:center;justify-content:center;
                        background:rgba(220,0,0,.1);border:1px solid rgba(220,0,0,.3)">
                <i class="fas fa-user-times" style="color:var(--ferrari-red);font-size:16px"></i>
            </div>
            <div>
                <div style="font-family:'Bebas Neue',sans-serif;font-size:18px;letter-spacing:2px">Delete Account</div>
                <div style="color:var(--gray);font-size:11px;margin-top:1px">This action cannot be undone</div>
            </div>
        </div>

        <p style="color:#aaa;font-size:12px;line-height:1.7;margin:14px 0 4px">
            You are about to permanently delete
            <strong id="deleteUserName" style="color:#fff"></strong>'s account.<br>
            Enter your admin password to confirm.
        </p>

        <div style="margin:16px 0">
            <label style="font-size:10px;font-weight:700;letter-spacing:2px;
                          text-transform:uppercase;color:var(--gray);display:block;margin-bottom:6px">
                Your Admin Password
            </label>
            <div style="position:relative">
                <input type="password" id="deletePassword"
                    placeholder="Enter your password to confirm"
                    style="width:100%;box-sizing:border-box;padding:10px 40px 10px 14px;
                           background:#1a1a1a;border:1px solid #2a2a2a;border-radius:6px;
                           color:#fff;font-size:13px;outline:none;transition:border-color .2s"
                    oninput="clearDelError()"
                    onkeydown="if(event.key==='Enter')submitDelete()">
                <span onclick="toggleDelPw()" style="
                    position:absolute;right:12px;top:50%;transform:translateY(-50%);
                    color:#555;cursor:pointer;font-size:13px;transition:color .2s"
                    onmouseover="this.style.color='var(--ferrari-red)'"
                    onmouseout="this.style.color='#555'">
                    <i id="delEye" class="fas fa-eye"></i>
                </span>
            </div>
            <div id="delError" style="color:var(--ferrari-red);font-size:11px;margin-top:6px;
                                      display:none;align-items:center;gap:6px">
                <i class="fas fa-exclamation-circle"></i>
                <span id="delErrorMsg">Incorrect password.</span>
            </div>
        </div>

        <div style="display:flex;gap:10px">
            <button id="delOkBtn" onclick="submitDelete()" style="
                flex:1;padding:11px;border:none;border-radius:6px;cursor:pointer;
                font-family:'Barlow',sans-serif;font-weight:700;font-size:12px;
                letter-spacing:2px;text-transform:uppercase;
                background:var(--ferrari-red);color:#fff;
                display:flex;align-items:center;justify-content:center;gap:8px;
                transition:background .2s,transform .15s"
                onmouseover="this.style.background='#b00000';this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='var(--ferrari-red)';this.style.transform=''">
                <i id="delOkIcon" class="fas fa-user-times"></i>
                <span id="delOkText">Delete Account</span>
                <div id="delSpinner" style="display:none;width:14px;height:14px;
                     border:2px solid rgba(255,255,255,.3);border-top-color:#fff;
                     border-radius:50%;animation:spin .7s linear infinite"></div>
            </button>
            <button onclick="closeDeleteModal()" style="
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

{{-- Hidden delete form --}}
<form id="deleteForm" method="POST" style="display:none">
    @csrf @method('DELETE')
</form>

{{-- ══════════════════════════════════════════════════════
     PAGE CONTENT
══════════════════════════════════════════════════════ --}}

{{-- Stats row --}}
<div class="uu-header">
    <div class="uu-stat-pill">
        <i class="fas fa-users"></i>
        All &nbsp;<span id="statTotal">0</span>
    </div>
    <div class="uu-stat-pill">
        <i class="fas fa-user" style="color:#60a5fa"></i>
        Users &nbsp;<span id="statUsers">0</span>
    </div>
    <div class="uu-stat-pill">
        <i class="fas fa-car" style="color:var(--ferrari-red)"></i>
        Drivers &nbsp;<span id="statDrivers">0</span>
    </div>
    <div class="uu-stat-pill">
        <i class="fas fa-check-circle" style="color:#4caf50"></i>
        Verified &nbsp;<span id="statVerified">0</span>
    </div>
</div>

<div class="card">
    {{-- Toolbar --}}
    <div style="padding:16px 20px 0">
        <div class="uu-toolbar">
            {{-- Search --}}
            <div class="uu-search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" id="userSearch" placeholder="Search name, email, contact…"
                    oninput="applyFilters()">
                <button id="clearSearch" onclick="clearSearchInput()" title="Clear">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Filter pills --}}
            <div class="uu-filter-group">
                <button class="uu-filter-btn active" data-filter="all"    onclick="setFilter(this)">All</button>
                <button class="uu-filter-btn"        data-filter="user"   onclick="setFilter(this)">
                    <i class="fas fa-user" style="margin-right:4px"></i>Users
                </button>
                <button class="uu-filter-btn"        data-filter="driver" onclick="setFilter(this)">
                    <i class="fas fa-car" style="margin-right:4px"></i>Drivers
                </button>
                <button class="uu-filter-btn"        data-filter="verified"   onclick="setFilter(this)">Verified</button>
                <button class="uu-filter-btn"        data-filter="unverified" onclick="setFilter(this)">Unverified</button>
            </div>

            <span id="resultCount"></span>
        </div>
    </div>

    {{-- Table --}}
    <div class="uu-table-wrap" style="padding:0 0 4px">
        <table class="uu-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Type</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="userTableBody">

                {{-- ── Regular Users ── --}}
                @foreach($users as $user)
                <tr class="uu-row"
                    data-type="user"
                    data-verified="{{ $user->email_verified_at ? '1' : '0' }}"
                    data-search="{{ strtolower($user->name . ' ' . $user->email . ' ' . ($user->contact_number ?? '')) }}"
                    style="animation-delay:{{ $loop->index * 0.03 }}s">
                    <td style="color:#555;font-size:11px">{{ $user->id }}</td>
                    <td>
                        <div class="uu-name-cell">
                            <div class="uu-avatar" style="background:rgba(59,130,246,.12);color:#60a5fa">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span>{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="color:#888;font-size:12px">{{ $user->email }}</td>
                    <td style="color:#666;font-size:12px">{{ $user->contact_number ?? '—' }}</td>
                    <td>
                        <span class="uu-type-badge uu-type-user">
                            <i class="fas fa-user" style="font-size:9px"></i> User
                        </span>
                    </td>
                    <td style="color:#555;font-size:11px;white-space:nowrap">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td>
                        @if($user->email_verified_at)
                            <span class="badge badge-delivered">Verified</span>
                        @else
                            <span class="badge badge-cancelled">Unverified</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn-del"
                            onclick="openDeleteModal('{{ addslashes($user->name) }}', '{{ route('admin.users.destroy', $user) }}')">
                            <i class="fas fa-trash" style="margin-right:4px"></i>Delete
                        </button>
                    </td>
                </tr>
                @endforeach

                {{-- ── Drivers ── --}}
                @foreach($drivers as $driver)
                <tr class="uu-row"
                    data-type="driver"
                    data-verified="{{ $driver->email_verified_at ? '1' : '0' }}"
                    data-search="{{ strtolower($driver->name . ' ' . $driver->email . ' ' . ($driver->contact_number ?? '')) }}"
                    style="animation-delay:{{ ($loop->index + $users->count()) * 0.03 }}s">
                    <td style="color:#555;font-size:11px">{{ $driver->id }}</td>
                    <td>
                        <div class="uu-name-cell">
                            <div class="uu-avatar" style="background:rgba(220,0,0,.1);color:var(--ferrari-red)">
                                {{ strtoupper(substr($driver->name, 0, 1)) }}
                            </div>
                            <span>{{ $driver->name }}</span>
                        </div>
                    </td>
                    <td style="color:#888;font-size:12px">{{ $driver->email }}</td>
                    <td style="color:#666;font-size:12px">{{ $driver->contact_number ?? '—' }}</td>
                    <td>
                        <span class="uu-type-badge uu-type-driver">
                            <i class="fas fa-car" style="font-size:9px"></i> Driver
                        </span>
                    </td>
                    <td style="color:#555;font-size:11px;white-space:nowrap">
                        {{ $driver->created_at->format('M d, Y') }}
                    </td>
                    <td>
                        @if($driver->email_verified_at)
                            <span class="badge badge-delivered">Verified</span>
                        @else
                            <span class="badge badge-cancelled">Unverified</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn-del"
                            onclick="openDeleteModal('{{ addslashes($driver->name) }}', '{{ route('admin.drivers.destroy', $driver) }}')">
                            <i class="fas fa-trash" style="margin-right:4px"></i>Delete
                        </button>
                    </td>
                </tr>
                @endforeach

                {{-- Empty state (shown via JS) --}}
                <tr id="emptyRow" style="display:none">
                    <td colspan="8">
                        <div class="uu-empty">
                            <i class="fas fa-search"></i>
                            <p id="emptyMsg">No results found.</p>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <div style="padding:16px 20px;">{{ $users->links() }}</div>
</div>

<script>
// ── State ───────────────────────────────────────────────────────
let currentFilter = 'all';

// ── Stats ───────────────────────────────────────────────────────
(function computeStats() {
    const rows = document.querySelectorAll('.uu-row');
    let total = 0, users = 0, drivers = 0, verified = 0;
    rows.forEach(r => {
        total++;
        if (r.dataset.type === 'user')   users++;
        if (r.dataset.type === 'driver') drivers++;
        if (r.dataset.verified === '1')  verified++;
    });
    document.getElementById('statTotal').textContent    = total;
    document.getElementById('statUsers').textContent    = users;
    document.getElementById('statDrivers').textContent  = drivers;
    document.getElementById('statVerified').textContent = verified;
})();

// ── Filter & Search ─────────────────────────────────────────────
function setFilter(btn) {
    document.querySelectorAll('.uu-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentFilter = btn.dataset.filter;
    applyFilters();
}

function applyFilters() {
    const q    = document.getElementById('userSearch').value.trim().toLowerCase();
    const rows = document.querySelectorAll('.uu-row');
    const clearBtn = document.getElementById('clearSearch');
    clearBtn.style.display = q ? 'block' : 'none';

    let visible = 0;

    rows.forEach((row, i) => {
        const typeMatch =
            currentFilter === 'all'        ? true :
            currentFilter === 'verified'   ? row.dataset.verified === '1' :
            currentFilter === 'unverified' ? row.dataset.verified === '0' :
            row.dataset.type === currentFilter;

        const searchMatch = !q || row.dataset.search.includes(q);
        const show = typeMatch && searchMatch;

        row.classList.toggle('hidden-row', !show);
        if (show) {
            visible++;
            row.style.animationDelay = (visible * 0.025) + 's';
            row.style.animation = 'none';
            void row.offsetWidth;
            row.style.animation = 'fadeSlideIn .25s ease both';
        }
    });

    // Empty state
    const emptyRow = document.getElementById('emptyRow');
    const emptyMsg = document.getElementById('emptyMsg');
    if (visible === 0) {
        emptyRow.style.display = '';
        emptyMsg.textContent = q
            ? `No results for "${q}".`
            : 'No accounts in this category.';
    } else {
        emptyRow.style.display = 'none';
    }

    document.getElementById('resultCount').textContent =
        visible === document.querySelectorAll('.uu-row').length
            ? ''
            : `${visible} result${visible !== 1 ? 's' : ''}`;
}

function clearSearchInput() {
    document.getElementById('userSearch').value = '';
    applyFilters();
    document.getElementById('userSearch').focus();
}

// ── Delete Modal ────────────────────────────────────────────────
let _deleteUrl = '';

function openDeleteModal(name, url) {
    _deleteUrl = url;
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('deletePassword').value = '';
    clearDelError();
    setDelLoading(false);
    const overlay = document.getElementById('deleteOverlay');
    const modal   = document.getElementById('deleteModal');
    overlay.style.display = 'flex';
    modal.style.animation = 'none';
    void modal.offsetWidth;
    modal.style.animation = 'modalIn .25s cubic-bezier(.25,.8,.25,1) both';
    setTimeout(() => document.getElementById('deletePassword').focus(), 80);
}

function closeDeleteModal() {
    document.getElementById('deleteOverlay').style.display = 'none';
}

document.getElementById('deleteOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeDeleteModal();
});

function toggleDelPw() {
    const inp = document.getElementById('deletePassword');
    const eye = document.getElementById('delEye');
    inp.type      = inp.type === 'password' ? 'text' : 'password';
    eye.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}

function clearDelError() {
    document.getElementById('delError').style.display = 'none';
    document.getElementById('deletePassword').style.borderColor = '#2a2a2a';
}

function showDelError(msg) {
    document.getElementById('delErrorMsg').textContent = msg;
    document.getElementById('delError').style.display = 'flex';
    document.getElementById('deletePassword').style.borderColor = 'rgba(220,0,0,.6)';
}

function setDelLoading(on) {
    const btn = document.getElementById('delOkBtn');
    btn.disabled = on; btn.style.opacity = on ? '0.7' : '';
    document.getElementById('delOkIcon').style.display    = on ? 'none'  : '';
    document.getElementById('delOkText').style.display    = on ? 'none'  : '';
    document.getElementById('delSpinner').style.display   = on ? 'block' : 'none';
}

async function submitDelete() {
    const pw = document.getElementById('deletePassword').value.trim();
    if (!pw) {
        showDelError('Please enter your admin password.');
        document.getElementById('deletePassword').focus();
        return;
    }
    setDelLoading(true);
    clearDelError();
    try {
        const res  = await fetch(VERIFY_URL, {
            method : 'POST',
            headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF },
            body   : JSON.stringify({ password: pw }),
        });
        const data = await res.json();
        if (!data.verified) {
            setDelLoading(false);
            showDelError(data.message || 'Incorrect password. Please try again.');
            document.getElementById('deletePassword').value = '';
            document.getElementById('deletePassword').focus();
            return;
        }
        const form = document.getElementById('deleteForm');
        form.action = _deleteUrl;
        form.submit();
    } catch (err) {
        setDelLoading(false);
        showDelError('Something went wrong. Please try again.');
    }
}
</script>

@endsection