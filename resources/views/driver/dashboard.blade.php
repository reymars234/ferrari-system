<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Driver Dashboard — Ferrari System</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--red:#dc0000;--dark:#0d0d0d;--dark2:#1a1a1a;--dark3:#252525;--light:#e8e8e8;--gray:#888}
body{background:var(--dark);color:var(--light);font-family:'Barlow',sans-serif;min-height:100vh}
a{color:inherit;text-decoration:none}

.topbar{background:var(--dark2);border-bottom:1px solid rgba(220,0,0,.15);padding:0 28px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50;backdrop-filter:blur(8px)}
.brand{font-family:'Bebas Neue',sans-serif;font-size:18px;letter-spacing:3px;color:var(--red);display:flex;align-items:center;gap:10px}
.brand img{height:28px;filter:drop-shadow(0 0 6px rgba(220,0,0,.35))}
.topbar-right{display:flex;align-items:center;gap:12px;font-size:13px;color:var(--gray)}
.topbar-right strong{color:var(--light)}
.btn-logout-top{background:transparent;border:1px solid rgba(220,0,0,.2);color:var(--gray);padding:7px 16px;border-radius:4px;cursor:pointer;font-size:11px;letter-spacing:1.5px;text-transform:uppercase;font-family:'Barlow',sans-serif;font-weight:700;transition:all .25s;display:flex;align-items:center;gap:6px}
.btn-logout-top:hover{border-color:var(--red);color:var(--red)}

.page{padding:28px;max-width:1100px;margin:0 auto}
.page-title{font-family:'Bebas Neue',sans-serif;font-size:28px;letter-spacing:3px;margin-bottom:4px}
.page-sub{color:var(--gray);font-size:12px;margin-bottom:24px}

/* Stats */
.stats-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:14px;margin-bottom:24px}
.stat{background:var(--dark2);border:1px solid rgba(220,0,0,.08);border-radius:10px;padding:20px;transition:transform .3s,border-color .3s,box-shadow .3s;animation:cardIn .5s ease both}
.stat:hover{transform:translateY(-4px) scale(1.02);border-color:rgba(220,0,0,.3);box-shadow:0 10px 28px rgba(220,0,0,.1)}
@keyframes cardIn{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
.stat-icon{font-size:22px;margin-bottom:10px;transition:transform .3s}
.stat:hover .stat-icon{transform:scale(1.15)}
.stat-val{font-family:'Bebas Neue',sans-serif;font-size:32px;letter-spacing:2px;line-height:1}
.stat-lbl{color:var(--gray);font-size:9px;letter-spacing:2px;text-transform:uppercase;margin-top:4px}

/* Alert badge on stat */
.stat-alert{background:rgba(220,0,0,.1);border-color:rgba(220,0,0,.4) !important}

/* Card / Table */
.card{background:var(--dark2);border:1px solid rgba(220,0,0,.08);border-radius:10px;overflow:hidden;margin-bottom:20px}
.card-head{padding:14px 20px;border-bottom:1px solid #1e1e1e;display:flex;align-items:center;justify-content:space-between}
.card-head h3{font-family:'Bebas Neue',sans-serif;font-size:18px;letter-spacing:2px}
table{width:100%;border-collapse:collapse}
th,td{padding:11px 16px;text-align:left;border-bottom:1px solid #1a1a1a;font-size:13px}
th{background:var(--dark3);color:var(--gray);font-size:9px;letter-spacing:2px;text-transform:uppercase}
tr{transition:background .2s}
tr:hover td{background:rgba(220,0,0,.02)}

/* Badges */
.badge{display:inline-block;padding:2px 10px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase}
.badge-pending   {background:#332a00;color:#f5c518;border:1px solid #f5c518}
.badge-processing{background:#002266;color:#4488ff;border:1px solid #4488ff}
.badge-delivered {background:#00331a;color:#1db954;border:1px solid #1db954}
.badge-cancelled {background:#330000;color:#ff4444;border:1px solid #ff4444}
.badge-cod-paid  {background:#00331a;color:#1db954;border:1px solid #1db954}
.badge-cod-unpaid{background:#332a00;color:#f5c518;border:1px solid #f5c518}

/* Buttons */
.btn{display:inline-flex;align-items:center;gap:5px;padding:7px 15px;border-radius:4px;font-weight:700;font-size:11px;letter-spacing:1.5px;text-transform:uppercase;cursor:pointer;border:none;font-family:'Barlow',sans-serif;transition:all .25s;position:relative;overflow:hidden}
.btn::after{content:'';position:absolute;inset:0;background:linear-gradient(120deg,transparent 30%,rgba(255,255,255,.1) 50%,transparent 70%);transform:translateX(-100%);transition:transform .45s ease}
.btn:hover::after{transform:translateX(100%)}
.btn:hover{transform:translateY(-2px)}
.btn-red{background:var(--red);color:#fff}
.btn-red:hover{background:#b00000;box-shadow:0 6px 18px rgba(220,0,0,.3)}
.btn-outline{background:transparent;border:1px solid var(--red);color:var(--red)}
.btn-outline:hover{background:var(--red);color:#fff}
.btn-green{background:#00331a;color:#1db954;border:1px solid #1db954}
.btn-green:hover{background:#004d27;box-shadow:0 5px 16px rgba(29,185,84,.2)}
.btn-gray{background:#2a2a2a;color:var(--gray);border:1px solid #333}
.btn-gray:hover{background:#333;color:var(--light)}
.btn-yellow{background:#332a00;color:#f5c518;border:1px solid #f5c518}
.btn-yellow:hover{background:#4a3d00;box-shadow:0 5px 16px rgba(245,197,24,.2)}

/* Flash */
.flash{padding:11px 18px;border-radius:4px;margin-bottom:16px;font-weight:600;font-size:13px;animation:cardIn .4s ease both}
.flash-success{background:#0a2f1a;border:1px solid #1db954;color:#1db954}
.flash-error  {background:#2f0a0a;border:1px solid var(--red);color:var(--red)}

/* Modal */
.modal-overlay{position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.75);backdrop-filter:blur(6px);display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .3s}
.modal-overlay.open{opacity:1;pointer-events:all}
.modal-box{background:var(--dark2);border:1px solid rgba(220,0,0,.25);border-radius:14px;padding:36px;max-width:360px;width:90%;text-align:center;transform:translateY(20px) scale(.96);transition:transform .35s cubic-bezier(.25,.8,.25,1)}
.modal-overlay.open .modal-box{transform:translateY(0) scale(1)}
.modal-icon{width:52px;height:52px;border-radius:50%;background:rgba(220,0,0,.1);border:1px solid rgba(220,0,0,.3);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:20px;color:var(--red)}
.modal-title{font-family:'Bebas Neue',sans-serif;font-size:22px;letter-spacing:3px;margin-bottom:8px}
.modal-desc{color:var(--gray);font-size:13px;margin-bottom:22px;line-height:1.7}
.modal-btns{display:flex;gap:10px;justify-content:center}
</style>
</head>
<body>

{{-- ── LOGOUT MODAL ── --}}
<div class="modal-overlay" id="logoutModal">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-sign-out-alt"></i></div>
        <div class="modal-title">SIGN OUT</div>
        <div class="modal-desc">Are you sure you want to log out?</div>
        <div class="modal-btns">
            <button class="btn btn-gray" onclick="closeModal('logoutModal')">No, Stay</button>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf<button type="submit" class="btn btn-red">Yes, Logout</button>
            </form>
        </div>
    </div>
</div>

{{-- ── COD CONFIRM MODAL ── --}}
<div class="modal-overlay" id="codModal">
    <div class="modal-box">
        <div class="modal-icon" style="background:rgba(245,197,24,.1);border-color:rgba(245,197,24,.3);color:#f5c518">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="modal-title">CONFIRM COD PAYMENT</div>
        <div class="modal-desc" id="codModalDesc">Has the customer paid in cash?</div>
        <div class="modal-btns">
            <button class="btn btn-gray" onclick="closeModal('codModal')">
                <i class="fas fa-times"></i> Not Yet
            </button>
            <form method="POST" id="codForm" style="margin:0">
                @csrf
                <button type="submit" class="btn btn-green">
                    <i class="fas fa-check"></i> Yes, Cash Received
                </button>
            </form>
        </div>
    </div>
</div>

<div class="topbar">
    <div class="brand">
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        @endif
        FERRARI DRIVER
    </div>
    <div class="topbar-right">
        <a href="{{ route('driver.orders') }}" class="btn btn-outline" style="padding:6px 14px;font-size:11px">My Orders</a>
        <span>Welcome, <strong>{{ auth()->user()->name }}</strong></span>
        <button class="btn-logout-top" onclick="openModal('logoutModal')">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </div>
</div>

<div class="page">
    @if(session('success'))<div class="flash flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
    @if(session('error'))  <div class="flash flash-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif

    <div class="page-title">Driver <span style="color:var(--red)">Dashboard</span></div>
    <div class="page-sub">
        {{ auth()->user()->vehicle_info ?? 'No vehicle set' }}
        &nbsp;·&nbsp; License: {{ auth()->user()->license_number ?? 'N/A' }}
        &nbsp;·&nbsp;
        <span style="color:{{ auth()->user()->driver_status==='available'?'#1db954':(auth()->user()->driver_status==='busy'?'#f5c518':'#888') }}">
            {{ strtoupper(auth()->user()->driver_status) }}
        </span>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat" style="animation-delay:.05s">
            <div class="stat-icon" style="color:#3a8ef6"><i class="fas fa-truck"></i></div>
            <div class="stat-val">{{ $stats['total'] }}</div>
            <div class="stat-lbl">Total Assigned</div>
        </div>
        <div class="stat" style="animation-delay:.1s">
            <div class="stat-icon" style="color:#f5c518"><i class="fas fa-clock"></i></div>
            <div class="stat-val">{{ $stats['pending'] }}</div>
            <div class="stat-lbl">Pending</div>
        </div>
        <div class="stat" style="animation-delay:.15s">
            <div class="stat-icon" style="color:var(--red)"><i class="fas fa-spinner"></i></div>
            <div class="stat-val">{{ $stats['active'] }}</div>
            <div class="stat-lbl">In Progress</div>
        </div>
        <div class="stat" style="animation-delay:.2s">
            <div class="stat-icon" style="color:#1db954"><i class="fas fa-check-circle"></i></div>
            <div class="stat-val">{{ $stats['done'] }}</div>
            <div class="stat-lbl">Delivered</div>
        </div>
        <div class="stat" style="animation-delay:.25s">
            <div class="stat-icon" style="color:var(--red)"><i class="fas fa-comment-dots"></i></div>
            <div class="stat-val">{{ $stats['unread'] }}</div>
            <div class="stat-lbl">Unread Msgs</div>
        </div>
        @if($stats['cod_pending'] > 0)
        <div class="stat stat-alert" style="animation-delay:.3s">
            <div class="stat-icon" style="color:#f5c518"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-val" style="color:#f5c518">{{ $stats['cod_pending'] }}</div>
            <div class="stat-lbl">COD Awaiting Payment</div>
        </div>
        @endif
    </div>

    {{-- Orders Table --}}
    <div class="card">
        <div class="card-head">
            <h3>My Assigned Orders</h3>
            <a href="{{ route('driver.orders') }}" class="btn btn-outline" style="padding:6px 14px;font-size:10px">View All</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Car</th>
                    <th>Customer</th>
                    <th>Address</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->car->name }}</td>
                    <td>
                        {{ $order->user->name }}
                        <br><span style="color:var(--gray);font-size:11px">{{ $order->user->contact_number }}</span>
                    </td>
                    <td style="color:var(--gray);font-size:12px;max-width:180px">
                        {{ Str::limit($order->buyer_address, 35) }}
                    </td>
                    <td>
                        <span style="font-size:11px;text-transform:uppercase">
                            {{ str_replace('_', ' ', $order->payment_method) }}
                        </span>
                        <br>
                        @if($order->payment_method === 'cod')
                            <span class="badge {{ $order->cod_paid ? 'badge-cod-paid' : 'badge-cod-unpaid' }}" style="font-size:9px">
                                {{ $order->cod_paid ? 'PAID ✓' : 'UNPAID' }}
                            </span>
                        @else
                            <span style="font-size:10px;color:{{ $order->payment_status==='paid'?'#1db954':'#f5c518' }};font-weight:700">
                                {{ strtoupper($order->payment_status) }}
                            </span>
                        @endif
                    </td>
                    <td><span class="badge badge-{{ $order->status }}">{{ $order->status }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="{{ route('driver.chat', $order) }}" class="btn btn-outline" style="padding:5px 10px;font-size:10px">
                                <i class="fas fa-comment"></i> Chat
                            </a>

                            {{-- Mark as Delivered (only for processing orders) --}}
                            @if($order->status === 'processing')
                                <form method="POST" action="{{ route('driver.mark-delivered', $order) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-green" style="padding:5px 10px;font-size:10px"
                                        onclick="return confirm('Mark Order #{{ $order->id }} as Delivered?')">
                                        <i class="fas fa-truck"></i> Delivered
                                    </button>
                                </form>
                            @endif

                            {{-- Mark COD as Paid (only for delivered COD orders not yet paid) --}}
                            @if($order->isCodMarkable())
                                <button onclick="openCodModal({{ $order->id }}, '{{ route('driver.mark-cod-paid', $order) }}')"
                                    class="btn btn-yellow" style="padding:5px 10px;font-size:10px">
                                    <i class="fas fa-money-bill-wave"></i> COD Paid
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--gray);padding:36px">
                        No orders assigned yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function openCodModal(orderId, url) {
    document.getElementById('codModalDesc').textContent =
        `Confirm that the customer has paid ₱ in cash for Order #${orderId}. This will update the payment status.`;
    document.getElementById('codForm').action = url;
    openModal('codModal');
}

document.querySelectorAll('.modal-overlay').forEach(m =>
    m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); })
);
document.addEventListener('keydown', e => {
    if (e.key === 'Escape')
        document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
});
</script>
</body>
</html>