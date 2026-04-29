@extends('layouts.admin')
@section('title', 'Orders')
@section('page-title', 'All Orders')
@section('content')

<style>
@keyframes modalIn {
    from { opacity:0; transform:translateY(16px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
@keyframes spin { to { transform:rotate(360deg); } }

/* ═══════════════════════════════════════════
   VIEW ORDER MODAL
═══════════════════════════════════════════ */
.confirm-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 10000;
    background: rgba(0,0,0,.82);
    backdrop-filter: blur(6px);
    align-items: center;
    justify-content: center;
    padding: 16px;
}
.confirm-modal {
    background: #111;
    border: 1px solid rgba(220,0,0,.22);
    border-radius: 14px;
    padding: 28px 24px;
    width: 100%;
    max-width: 380px;
    box-shadow: 0 32px 80px rgba(0,0,0,.65);
    animation: modalIn .25s cubic-bezier(.25,.8,.25,1) both;
    text-align: left;
}
.confirm-modal-head {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
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
.confirm-modal-icon i { color: var(--red); font-size: 15px; }
.confirm-modal-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 18px;
    letter-spacing: 2px;
}
.confirm-modal-sub { color: var(--gray); font-size: 11px; margin-top: 1px; }
.confirm-modal-desc { color: #aaa; font-size: 12px; line-height: 1.6; margin: 0 0 14px; }
.pw-wrap { margin-bottom: 14px; }
.pw-label {
    font-size: 10px; font-weight: 700;
    letter-spacing: 2px; text-transform: uppercase;
    color: var(--gray); display: block; margin-bottom: 6px;
}
.pw-field-wrap { position: relative; }
.pw-input {
    width: 100%; box-sizing: border-box;
    padding: 10px 40px 10px 14px;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 6px;
    color: #fff; font-size: 13px;
    font-family: 'Barlow', sans-serif;
    outline: none; transition: border-color .2s;
}
.pw-input:focus { border-color: rgba(220,0,0,.5); }
.pw-toggle {
    position: absolute; right: 12px;
    top: 50%; transform: translateY(-50%);
    color: #555; cursor: pointer; font-size: 13px;
    background: none; border: none; padding: 0;
    line-height: 1; transition: color .2s;
}
.pw-toggle:hover { color: var(--red); }
.pw-error {
    color: var(--red); font-size: 11px;
    margin-top: 6px; display: none;
    align-items: center; gap: 6px;
}
.confirm-modal-actions { display: flex; gap: 10px; }
.btn-confirm-ok {
    flex: 1; padding: 11px; border: none;
    border-radius: 6px; cursor: pointer;
    font-family: 'Barlow', sans-serif;
    font-weight: 700; font-size: 12px;
    letter-spacing: 2px; text-transform: uppercase;
    background: var(--red); color: #fff;
    display: flex; align-items: center;
    justify-content: center; gap: 8px;
    transition: background .2s, transform .15s;
}
.btn-confirm-ok:hover { background: #b00000; transform: translateY(-1px); }
.btn-confirm-cancel {
    flex: 1; padding: 11px;
    border: 1px solid #2a2a2a;
    border-radius: 6px; cursor: pointer;
    font-family: 'Barlow', sans-serif;
    font-weight: 700; font-size: 12px;
    letter-spacing: 2px; text-transform: uppercase;
    background: transparent; color: var(--gray);
    transition: border-color .2s, color .2s;
}
.btn-confirm-cancel:hover { border-color: #444; color: #fff; }
.btn-spinner {
    display: none; width: 14px; height: 14px;
    border: 2px solid rgba(255,255,255,.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .7s linear infinite;
    flex-shrink: 0;
}

/* ═══════════════════════════════════════════
   FILTER TABS
═══════════════════════════════════════════ */
.filter-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 18px;
    flex-wrap: wrap;
}

/* ═══════════════════════════════════════════
   ORDERS — desktop table
═══════════════════════════════════════════ */
.car-thumb {
    display: flex;
    align-items: center;
    gap: 10px;
}
.car-thumb img,
.car-thumb-placeholder {
    width: 42px; height: 32px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #2a2a2a;
    flex-shrink: 0;
}
.car-thumb-placeholder {
    background: #1a1a1a;
    display: flex;
    align-items: center;
    justify-content: center;
}
.car-thumb-placeholder i { color: #333; font-size: 12px; }

/* ═══════════════════════════════════════════
   ORDERS — mobile card list
═══════════════════════════════════════════ */
.orders-mobile-list { display: none; }

.order-card {
    background: var(--dark3);
    border: 1px solid rgba(220,0,0,.08);
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    transition: border-color .25s;
}
.order-card:hover { border-color: rgba(220,0,0,.22); }

.order-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    flex-wrap: wrap;
}
.order-card-id {
    font-size: 11px;
    color: var(--gray);
    font-weight: 700;
    letter-spacing: 1px;
}
.order-card-car {
    display: flex;
    align-items: center;
    gap: 10px;
}
.order-card-car span {
    font-size: 14px;
    font-weight: 700;
    color: var(--light);
}
.order-card-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px 14px;
}
.order-card-field {}
.order-card-label {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--gray);
    margin-bottom: 2px;
}
.order-card-value { font-size: 12px; font-weight: 600; color: var(--light); }
.order-card-value.muted { color: var(--gray); }
.order-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding-top: 8px;
    border-top: 1px solid rgba(255,255,255,.05);
    flex-wrap: wrap;
}

/* Hide/show per breakpoint */
@media (max-width: 860px) {
    .orders-desktop-table { display: none !important; }
    .orders-mobile-list   { display: flex; flex-direction: column; gap: 12px; padding: 16px; }
    .filter-tabs .btn     { font-size: 10px; padding: 6px 12px; }
    .card-header h3       { font-size: 17px; }
}

@media (max-width: 400px) {
    .confirm-modal           { padding: 20px 14px; }
    .confirm-modal-actions   { flex-direction: column; }
    .order-card-grid         { grid-template-columns: 1fr; }
}
</style>

{{-- ══════════════════════════════════════════════════════
     VIEW ORDER — PASSWORD CONFIRMATION MODAL
══════════════════════════════════════════════════════ --}}
<div id="viewOverlay" class="confirm-overlay">
    <div id="viewModal" class="confirm-modal">
        <div class="confirm-modal-head">
            <div class="confirm-modal-icon"><i class="fas fa-eye"></i></div>
            <div>
                <div class="confirm-modal-title">View Order</div>
                <div id="viewOrderLabel" class="confirm-modal-sub">Admin password required</div>
            </div>
        </div>
        <p class="confirm-modal-desc">Enter your admin password to open this order's details.</p>
        <div class="pw-wrap">
            <label class="pw-label">Your Admin Password</label>
            <div class="pw-field-wrap">
                <input type="password" id="viewPassword" class="pw-input"
                    placeholder="Enter your password"
                    oninput="clearViewError()"
                    onkeydown="if(event.key==='Enter')submitViewConfirm()">
                <button type="button" class="pw-toggle" onclick="toggleViewPw()">
                    <i id="viewEye" class="fas fa-eye"></i>
                </button>
            </div>
            <div id="viewError" class="pw-error">
                <i class="fas fa-exclamation-circle"></i>
                <span id="viewErrorMsg">Incorrect password.</span>
            </div>
        </div>
        <div class="confirm-modal-actions">
            <button id="viewOkBtn" class="btn-confirm-ok" onclick="submitViewConfirm()">
                <i id="viewOkIcon" class="fas fa-eye"></i>
                <span id="viewOkText">Open Order</span>
                <div id="viewSpinner" class="btn-spinner"></div>
            </button>
            <button class="btn-confirm-cancel" onclick="closeViewConfirm()">Cancel</button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     FILTER TABS
══════════════════════════════════════════════════════ --}}
<div class="filter-tabs">
    @foreach(['all'=>'All','pending'=>'Pending','processing'=>'Processing','delivered'=>'Delivered','cancelled'=>'Cancelled'] as $val => $label)
    <a href="{{ request()->fullUrlWithQuery(['status' => $val === 'all' ? null : $val]) }}"
       class="btn btn-sm {{ (!request('status') && $val==='all') || request('status')===$val ? 'btn-red' : 'btn-gray' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-shopping-bag" style="color:var(--red);margin-right:8px"></i> Orders</h3>
        <span style="color:var(--gray);font-size:12px">{{ $orders->total() }} total</span>
    </div>

    {{-- ── Desktop Table ── --}}
    <div class="table-wrap orders-desktop-table">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Car</th>
                    <th>Customer</th>
                    <th>Driver</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Accepted</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td style="color:var(--gray);font-size:12px">#{{ $order->id }}</td>

                    <td>
                        <div class="car-thumb">
                            @if($order->car->image && file_exists(storage_path('app/public/cars/'.$order->car->image)))
                                <img src="{{ asset('storage/cars/'.$order->car->image) }}" alt="{{ $order->car->name }}">
                            @else
                                <div class="car-thumb-placeholder"><i class="fas fa-car"></i></div>
                            @endif
                            <span style="font-weight:600;font-size:13px">{{ $order->car->name }}</span>
                        </div>
                    </td>

                    <td>
                        <div style="font-size:13px;font-weight:600">{{ $order->user->name }}</div>
                        <div style="font-size:11px;color:var(--gray)">{{ $order->user->email }}</div>
                    </td>

                    <td>
                        @if($order->driver)
                            <div style="font-size:13px;font-weight:600">{{ $order->driver->name }}</div>
                            <div style="font-size:11px;color:var(--gray)">{{ $order->driver->contact_number }}</div>
                        @else
                            <span style="color:#333;font-size:12px">— unassigned</span>
                        @endif
                    </td>

                    <td style="color:var(--red);font-weight:700;font-size:13px">
                        ₱{{ number_format($order->total_price, 2) }}
                    </td>

                    <td>
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px">
                            {{ str_replace('_',' ',$order->payment_method) }}
                        </div>
                        @php $pColor = match($order->payment_status) { 'paid'=>'#1db954','failed'=>'#ff4444',default=>'#f5c518' }; @endphp
                        <span style="font-size:10px;color:{{ $pColor }};font-weight:700">
                            {{ strtoupper($order->payment_status) }}
                        </span>
                        @if($order->payment_method === 'cod' && $order->cod_paid)
                            <span style="font-size:10px;color:#1db954;font-weight:700"> · CASH ✓</span>
                        @endif
                    </td>

                    <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>

                    <td>
                        @if($order->admin_accepted)
                            <i class="fas fa-check-circle" style="color:#1db954"></i>
                        @else
                            <i class="fas fa-clock" style="color:#444"></i>
                        @endif
                    </td>

                    <td style="color:var(--gray);font-size:11px;white-space:nowrap">
                        {{ $order->created_at->format('M d, Y') }}<br>
                        <span style="font-size:10px">{{ $order->created_at->format('h:i A') }}</span>
                    </td>

                    <td>
                        <button onclick="openViewConfirm('{{ route('admin.orders.show', $order) }}', '#{{ $order->id }}')"
                            class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center;padding:50px;color:var(--gray)">
                        <i class="fas fa-shopping-bag" style="font-size:32px;color:#222;display:block;margin-bottom:12px"></i>
                        No orders found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Mobile Card List ── --}}
    <div class="orders-mobile-list">
        @forelse($orders as $order)
        @php $pColor = match($order->payment_status) { 'paid'=>'#1db954','failed'=>'#ff4444',default=>'#f5c518' }; @endphp
        <div class="order-card">

            {{-- Top: ID + status badge --}}
            <div class="order-card-top">
                <span class="order-card-id">#{{ $order->id }}</span>
                <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap">
                    <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    @if($order->admin_accepted)
                        <i class="fas fa-check-circle" style="color:#1db954;font-size:13px" title="Admin accepted"></i>
                    @else
                        <i class="fas fa-clock" style="color:#444;font-size:13px" title="Pending acceptance"></i>
                    @endif
                </div>
            </div>

            {{-- Car --}}
            <div class="order-card-car">
                @if($order->car->image && file_exists(storage_path('app/public/cars/'.$order->car->image)))
                    <img src="{{ asset('storage/cars/'.$order->car->image) }}"
                         style="width:48px;height:36px;object-fit:cover;border-radius:6px;border:1px solid #2a2a2a;flex-shrink:0"
                         alt="{{ $order->car->name }}">
                @else
                    <div style="width:48px;height:36px;background:#1a1a1a;border-radius:6px;border:1px solid #2a2a2a;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="fas fa-car" style="color:#333;font-size:14px"></i>
                    </div>
                @endif
                <span style="font-size:14px;font-weight:700;color:var(--light)">{{ $order->car->name }}</span>
            </div>

            {{-- Grid: Customer / Driver / Amount / Payment --}}
            <div class="order-card-grid">
                <div class="order-card-field">
                    <div class="order-card-label">Customer</div>
                    <div class="order-card-value">{{ $order->user->name }}</div>
                    <div style="font-size:10px;color:var(--gray)">{{ $order->user->email }}</div>
                </div>

                <div class="order-card-field">
                    <div class="order-card-label">Driver</div>
                    @if($order->driver)
                        <div class="order-card-value">{{ $order->driver->name }}</div>
                        <div style="font-size:10px;color:var(--gray)">{{ $order->driver->contact_number }}</div>
                    @else
                        <div class="order-card-value muted">Unassigned</div>
                    @endif
                </div>

                <div class="order-card-field">
                    <div class="order-card-label">Amount</div>
                    <div class="order-card-value" style="color:var(--red)">₱{{ number_format($order->total_price, 2) }}</div>
                </div>

                <div class="order-card-field">
                    <div class="order-card-label">Payment</div>
                    <div class="order-card-value" style="font-size:11px;text-transform:uppercase;letter-spacing:1px">
                        {{ str_replace('_',' ',$order->payment_method) }}
                    </div>
                    <div style="font-size:10px;color:{{ $pColor }};font-weight:700">
                        {{ strtoupper($order->payment_status) }}
                        @if($order->payment_method === 'cod' && $order->cod_paid)
                            · CASH ✓
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer: Date + View button --}}
            <div class="order-card-footer">
                <div style="font-size:11px;color:var(--gray)">
                    <i class="fas fa-calendar-alt" style="margin-right:4px"></i>
                    {{ $order->created_at->format('M d, Y') }}
                    <span style="font-size:10px;margin-left:4px">{{ $order->created_at->format('h:i A') }}</span>
                </div>
                <button onclick="openViewConfirm('{{ route('admin.orders.show', $order) }}', '#{{ $order->id }}')"
                    class="btn btn-outline btn-sm">
                    <i class="fas fa-eye"></i> View
                </button>
            </div>

        </div>
        @empty
        <div style="text-align:center;padding:40px 0;color:var(--gray)">
            <i class="fas fa-shopping-bag" style="font-size:28px;color:#222;display:block;margin-bottom:10px"></i>
            No orders found.
        </div>
        @endforelse
    </div>
</div>

<div style="margin-top:20px">{{ $orders->links() }}</div>

@push('scripts')
<script>
const VERIFY_URL = '{{ route('admin.verify-password') }}';
const CSRF       = '{{ csrf_token() }}';
let pendingViewUrl = '';

function openViewConfirm(url, orderLabel) {
    pendingViewUrl = url;
    document.getElementById('viewOrderLabel').textContent = 'Order ' + orderLabel;
    document.getElementById('viewPassword').value = '';
    clearViewError();
    setViewLoading(false);
    const overlay = document.getElementById('viewOverlay');
    const modal   = document.getElementById('viewModal');
    overlay.style.display = 'flex';
    modal.style.animation = 'none';
    void modal.offsetWidth;
    modal.style.animation = 'modalIn .25s cubic-bezier(.25,.8,.25,1) both';
    setTimeout(() => document.getElementById('viewPassword').focus(), 80);
}

function closeViewConfirm() {
    document.getElementById('viewOverlay').style.display = 'none';
    pendingViewUrl = '';
}

document.getElementById('viewOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeViewConfirm();
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeViewConfirm();
});

function toggleViewPw() {
    const inp = document.getElementById('viewPassword');
    const eye = document.getElementById('viewEye');
    inp.type      = inp.type === 'password' ? 'text' : 'password';
    eye.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}

async function submitViewConfirm() {
    const pw = document.getElementById('viewPassword').value.trim();
    if (!pw) {
        showViewError('Please enter your admin password.');
        document.getElementById('viewPassword').focus();
        return;
    }
    setViewLoading(true);
    clearViewError();
    try {
        const res  = await fetch(VERIFY_URL, {
            method : 'POST',
            headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF },
            body   : JSON.stringify({ password: pw }),
        });
        const data = await res.json();
        if (!data.verified) {
            setViewLoading(false);
            showViewError(data.message || 'Incorrect password. Please try again.');
            document.getElementById('viewPassword').value = '';
            document.getElementById('viewPassword').focus();
            return;
        }
        window.location.href = pendingViewUrl;
    } catch (err) {
        setViewLoading(false);
        showViewError('Something went wrong. Please try again.');
    }
}

function setViewLoading(on) {
    const btn = document.getElementById('viewOkBtn');
    btn.disabled = on; btn.style.opacity = on ? '0.7' : '';
    document.getElementById('viewOkIcon').style.display   = on ? 'none'  : '';
    document.getElementById('viewOkText').style.display   = on ? 'none'  : '';
    document.getElementById('viewSpinner').style.display  = on ? 'block' : 'none';
}
function showViewError(msg) {
    document.getElementById('viewErrorMsg').textContent = msg;
    document.getElementById('viewError').style.display  = 'flex';
    document.getElementById('viewPassword').style.borderColor = 'rgba(220,0,0,.6)';
}
function clearViewError() {
    document.getElementById('viewError').style.display  = 'none';
    document.getElementById('viewPassword').style.borderColor = '#2a2a2a';
}
</script>
@endpush

@endsection