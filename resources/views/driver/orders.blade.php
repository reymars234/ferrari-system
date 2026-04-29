<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Deliveries — Ferrari Driver</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--red:#dc0000;--dark:#0d0d0d;--dark2:#1a1a1a;--dark3:#252525;--light:#e8e8e8;--gray:#888}
body{background:var(--dark);color:var(--light);font-family:'Barlow',sans-serif}
a{color:inherit;text-decoration:none}
.topbar{background:var(--dark2);border-bottom:1px solid rgba(220,0,0,.15);padding:0 28px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}
.brand{font-family:'Bebas Neue',sans-serif;font-size:18px;letter-spacing:3px;color:var(--red)}
.page{padding:28px;max-width:1100px;margin:0 auto}
.page-title{font-family:'Bebas Neue',sans-serif;font-size:28px;letter-spacing:3px;margin-bottom:20px}
.card{background:var(--dark2);border:1px solid rgba(220,0,0,.08);border-radius:10px;overflow:hidden}
table{width:100%;border-collapse:collapse}
th,td{padding:12px 16px;text-align:left;border-bottom:1px solid #1a1a1a;font-size:13px}
th{background:var(--dark3);color:var(--gray);font-size:9px;letter-spacing:2px;text-transform:uppercase}
tr:hover td{background:rgba(220,0,0,.02)}
.badge{display:inline-block;padding:2px 10px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase}
.badge-pending   {background:#332a00;color:#f5c518;border:1px solid #f5c518}
.badge-processing{background:#002266;color:#4488ff;border:1px solid #4488ff}
.badge-delivered {background:#00331a;color:#1db954;border:1px solid #1db954}
.badge-cancelled {background:#330000;color:#ff4444;border:1px solid #ff4444}
.btn{display:inline-flex;align-items:center;gap:5px;padding:7px 14px;border-radius:4px;font-weight:700;font-size:11px;letter-spacing:1.5px;text-transform:uppercase;cursor:pointer;border:none;font-family:'Barlow',sans-serif;transition:all .25s;position:relative;overflow:hidden}
.btn::after{content:'';position:absolute;inset:0;background:linear-gradient(120deg,transparent 30%,rgba(255,255,255,.1) 50%,transparent 70%);transform:translateX(-100%);transition:transform .45s ease}
.btn:hover::after{transform:translateX(100%)}
.btn:hover{transform:translateY(-2px)}
.btn-red{background:var(--red);color:#fff}
.btn-red:hover{background:#b00000;box-shadow:0 5px 16px rgba(220,0,0,.3)}
.btn-outline{background:transparent;border:1px solid var(--red);color:var(--red)}
.btn-outline:hover{background:var(--red);color:#fff}
.btn-green{background:#00331a;color:#1db954;border:1px solid #1db954}
.btn-green:hover{background:#004d27}
.btn-yellow{background:#332a00;color:#f5c518;border:1px solid #f5c518}
.btn-yellow:hover{background:#4a3d00}
.btn-gray{background:#2a2a2a;color:var(--gray);border:1px solid #333}
.btn-gray:hover{background:#333;color:var(--light)}
.flash{padding:11px 18px;border-radius:4px;margin-bottom:16px;font-weight:600;font-size:13px}
.flash-success{background:#0a2f1a;border:1px solid #1db954;color:#1db954}
/* Modal */
.modal-overlay{position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.75);backdrop-filter:blur(6px);display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .3s}
.modal-overlay.open{opacity:1;pointer-events:all}
.modal-box{background:var(--dark2);border:1px solid rgba(245,197,24,.25);border-radius:14px;padding:36px;max-width:360px;width:90%;text-align:center;transform:translateY(20px) scale(.96);transition:transform .35s cubic-bezier(.25,.8,.25,1)}
.modal-overlay.open .modal-box{transform:translateY(0) scale(1)}
.modal-icon{width:52px;height:52px;border-radius:50%;background:rgba(245,197,24,.1);border:1px solid rgba(245,197,24,.3);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:20px;color:#f5c518}
.modal-title{font-family:'Bebas Neue',sans-serif;font-size:22px;letter-spacing:3px;margin-bottom:8px}
.modal-desc{color:var(--gray);font-size:13px;margin-bottom:22px;line-height:1.7}
.modal-btns{display:flex;gap:10px;justify-content:center}
</style>
</head>
<body>

{{-- COD Confirm Modal --}}
<div class="modal-overlay" id="codModal">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-money-bill-wave"></i></div>
        <div class="modal-title">CONFIRM PAYMENT</div>
        <div class="modal-desc" id="codDesc">Has the customer paid in cash?</div>
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
    <div class="brand">Ferrari Driver Panel</div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('driver.dashboard') }}" class="btn btn-gray" style="padding:6px 14px;font-size:11px">← Dashboard</a>
    </div>
</div>

<div class="page">
    <div class="page-title">My <span style="color:var(--red)">Deliveries</span></div>

    @if(session('success'))
        <div class="flash flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Car</th><th>Customer</th><th>Address</th>
                    <th>Payment</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td style="font-weight:700">#{{ $order->id }}</td>
                    <td>{{ $order->car->name }}</td>
                    <td>
                        {{ $order->user->name }}
                        <br><span style="color:var(--gray);font-size:11px">{{ $order->user->contact_number }}</span>
                    </td>
                    <td style="color:var(--gray);font-size:12px;max-width:160px">
                        {{ Str::limit($order->buyer_address, 30) }}
                    </td>
                    <td>
                        <span style="font-size:11px;font-weight:700;text-transform:uppercase">
                            {{ str_replace('_', ' ', $order->payment_method) }}
                        </span>
                        <br>
                        @if($order->payment_method === 'cod')
                            @if($order->cod_paid)
                                <span style="color:#1db954;font-size:10px;font-weight:700">
                                    <i class="fas fa-check-circle"></i> CASH RECEIVED
                                    <br>
                                    <span style="color:var(--gray);font-weight:400">
                                        {{ $order->cod_paid_at?->format('M d, H:i') }}
                                    </span>
                                </span>
                            @else
                                <span style="color:#f5c518;font-size:10px;font-weight:700">AWAITING CASH</span>
                            @endif
                        @else
                            <span style="font-size:10px;font-weight:700;color:{{ $order->payment_status==='paid'?'#1db954':'#f5c518' }}">
                                {{ strtoupper($order->payment_status) }}
                            </span>
                        @endif
                    </td>
                    <td><span class="badge badge-{{ $order->status }}">{{ $order->status }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="{{ route('driver.chat', $order) }}"
                               class="btn btn-outline" style="padding:5px 10px;font-size:10px">
                                <i class="fas fa-comment"></i> Chat
                            </a>

                            {{-- Mark as Delivered --}}
                            @if($order->status === 'processing')
                                <form method="POST" action="{{ route('driver.mark-delivered', $order) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-green" style="padding:5px 10px;font-size:10px"
                                        onclick="return confirm('Mark as Delivered?')">
                                        <i class="fas fa-truck"></i> Delivered
                                    </button>
                                </form>
                            @endif

                            {{-- COD Paid confirmation --}}
                            @if($order->isCodMarkable())
                                <button onclick="openCodModal({{ $order->id }}, '{{ route('driver.mark-cod-paid', $order) }}', '{{ number_format($order->total_price, 2) }}')"
                                    class="btn btn-yellow" style="padding:5px 10px;font-size:10px">
                                    <i class="fas fa-money-bill-wave"></i> COD Paid
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--gray);padding:40px">
                        No deliveries yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding:14px 18px">{{ $orders->links() }}</div>
    </div>
</div>

<script>
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function openCodModal(orderId, url, amount) {
    document.getElementById('codDesc').textContent =
        `Confirm that the customer has paid ₱${amount} in cash for Order #${orderId}. The admin will be notified.`;
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