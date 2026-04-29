@extends('layouts.app')
@section('title', 'Order #'.$order->id)

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">

<style>
/* ── Reset & Base ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --red:        #dc0000;
    --red-glow:   rgba(220, 0, 0, 0.18);
    --red-soft:   rgba(220, 0, 0, 0.08);
    --gold:       #c9a84c;
    --gold-soft:  rgba(201, 168, 76, 0.12);
    --bg:         #0a0a0a;
    --surface:    #111111;
    --surface2:   #161616;
    --surface3:   #1c1c1c;
    --border:     rgba(255,255,255,0.06);
    --border-md:  rgba(255,255,255,0.10);
    --text:       #f0f0f0;
    --text-muted: #666;
    --text-dim:   #888;
    --blue:       #3a8ef6;
    --blue-soft:  rgba(58, 142, 246, 0.10);
    --green:      #22c55e;
    --green-soft: rgba(34, 197, 94, 0.10);
    --yellow:     #f5c518;
    --yellow-soft:rgba(245, 197, 24, 0.08);
    --mono:       'JetBrains Mono', monospace;
    --sans:       'DM Sans', sans-serif;
    --display:    'Bebas Neue', sans-serif;
    --radius:     10px;
    --radius-lg:  16px;
    --ease-out:   cubic-bezier(0.16, 1, 0.3, 1);
    --ease-spring:cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* ── Page Shell ── */
.od-page {
    font-family: var(--sans);
    background: var(--bg);
    min-height: 100vh;
    color: var(--text);
    padding: 56px 24px 80px;
}

.od-wrap {
    max-width: 680px;
    margin: 0 auto;
}

/* ── Entrance Animations ── */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
@keyframes slideRight {
    from { opacity: 0; transform: translateX(-16px); }
    to   { opacity: 1; transform: translateX(0); }
}
@keyframes pulse-ring {
    0%   { box-shadow: 0 0 0 0 var(--red-glow); }
    70%  { box-shadow: 0 0 0 10px transparent; }
    100% { box-shadow: 0 0 0 0 transparent; }
}

.od-page { animation: fadeIn .4s ease both; }

/* ── Header ── */
.od-header {
    margin-bottom: 36px;
    animation: fadeUp .5s var(--ease-out) both;
}

.od-back {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--text-muted);
    text-decoration: none;
    margin-bottom: 22px;
    transition: color .2s, gap .2s;
}
.od-back:hover { color: var(--text-dim); gap: 10px; }
.od-back svg { transition: transform .2s; }
.od-back:hover svg { transform: translateX(-3px); }

.od-title-row {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.od-order-id {
    font-family: var(--display);
    font-size: 46px;
    letter-spacing: 4px;
    line-height: 1;
    color: var(--text);
}
.od-order-id span { color: var(--red); }

/* ── Status Badge ── */
.od-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 14px 6px 10px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    border: 1px solid;
    white-space: nowrap;
}
.od-status .dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    animation: pulse-ring 2s infinite;
}

.od-status.pending   { color: var(--yellow); border-color: rgba(245,197,24,.25); background: var(--yellow-soft); }
.od-status.pending .dot { background: var(--yellow); }
.od-status.processing { color: var(--blue); border-color: rgba(58,142,246,.25); background: var(--blue-soft); }
.od-status.processing .dot { background: var(--blue); }
.od-status.shipped   { color: #a78bfa; border-color: rgba(167,139,250,.25); background: rgba(167,139,250,.08); }
.od-status.shipped .dot { background: #a78bfa; }
.od-status.delivered { color: var(--green); border-color: rgba(34,197,94,.25); background: var(--green-soft); }
.od-status.delivered .dot { background: var(--green); }
.od-status.cancelled { color: var(--red); border-color: rgba(220,0,0,.25); background: var(--red-soft); }
.od-status.cancelled .dot { background: var(--red); animation: none; }

/* ── Card ── */
.od-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    animation: fadeUp .5s var(--ease-out) .1s both;
    transition: border-color .3s;
}
.od-card:hover { border-color: var(--border-md); }

.od-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 20px;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
}
.od-card-header-icon {
    width: 28px; height: 28px;
    border-radius: 7px;
    background: var(--red-soft);
    border: 1px solid rgba(220,0,0,.2);
    display: flex; align-items: center; justify-content: center;
    color: var(--red);
    font-size: 11px;
}
.od-card-header-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--text-muted);
}

.od-card-body { padding: 24px 20px; }

/* ── Info Grid ── */
.od-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
}

.od-field {
    padding: 14px 16px;
    border-radius: var(--radius);
    transition: background .2s;
    position: relative;
}
.od-field:hover { background: var(--surface2); }
.od-field.full   { grid-column: 1 / -1; }

.od-field-label {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.od-field-label i { font-size: 8px; opacity: .7; }

.od-field-value {
    font-size: 14px;
    font-weight: 500;
    color: var(--text);
    line-height: 1.5;
}

.od-car-name {
    font-family: var(--display);
    font-size: 26px;
    letter-spacing: 2px;
    color: var(--text);
}

.od-price {
    font-family: var(--mono);
    font-size: 22px;
    font-weight: 500;
    color: var(--red);
    letter-spacing: 0;
}

/* ── Divider ── */
.od-divider {
    height: 1px;
    background: var(--border);
    margin: 4px 16px;
}

/* ── Payment Badge ── */
.od-pay-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 8px;
    border-radius: 5px;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    margin-left: 6px;
    vertical-align: middle;
}
.od-pay-badge.paid     { background: var(--green-soft); color: var(--green); border: 1px solid rgba(34,197,94,.2); }
.od-pay-badge.unpaid   { background: var(--yellow-soft); color: var(--yellow); border: 1px solid rgba(245,197,24,.2); }

.od-ref {
    font-family: var(--mono);
    font-size: 11px;
    color: var(--text-muted);
    margin-top: 4px;
}

/* ── Driver Card ── */
.od-driver {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--surface2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 12px 14px;
    margin: 4px 16px;
    transition: border-color .2s, background .2s;
}
.od-driver:hover { border-color: var(--border-md); background: var(--surface3); }

.od-driver-avatar {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: var(--surface3);
    border: 2px solid var(--border-md);
    display: flex; align-items: center; justify-content: center;
    font-family: var(--display);
    font-size: 16px;
    letter-spacing: 1px;
    color: var(--text-dim);
    flex-shrink: 0;
}

.od-driver-name  { font-size: 13px; font-weight: 600; }
.od-driver-meta  { font-size: 11px; color: var(--text-muted); margin-top: 1px; }

/* ── Map ── */
.od-map-wrap {
    margin: 0;
    border-top: 1px solid var(--border);
    animation: fadeUp .5s var(--ease-out) .2s both;
}

.od-map-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: var(--surface2);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--text-muted);
}
.od-map-header i { color: var(--red); font-size: 11px; }

.od-map-coords {
    margin-left: auto;
    font-family: var(--mono);
    font-size: 10px;
    color: #333;
    font-weight: 400;
    letter-spacing: 0;
}

#orderMap {
    height: 260px;
    width: 100%;
    display: block;
    background: #111;
    position: relative;
    z-index: 1;
}

/* ── Actions ── */
.od-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    margin-top: 20px;
    animation: fadeUp .5s var(--ease-out) .25s both;
}

/* ── Buttons ── */
.od-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 20px;
    border-radius: 8px;
    font-family: var(--sans);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .5px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all .2s var(--ease-out);
    white-space: nowrap;
    position: relative;
    overflow: hidden;
}

.od-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0);
    transition: background .2s;
    border-radius: inherit;
}
.od-btn:hover::before { background: rgba(255,255,255,.06); }
.od-btn:active        { transform: scale(.97); }

.od-btn-outline {
    background: transparent;
    border: 1px solid var(--border-md);
    color: var(--text-dim);
}
.od-btn-outline:hover { border-color: rgba(255,255,255,.2); color: var(--text); }

.od-btn-red {
    background: var(--red);
    color: #fff;
    box-shadow: 0 4px 20px var(--red-glow);
}
.od-btn-red:hover { box-shadow: 0 6px 28px rgba(220,0,0,.35); }

.od-btn-ghost {
    background: var(--surface2);
    border: 1px solid var(--border);
    color: var(--text-dim);
}
.od-btn-ghost:hover { border-color: var(--border-md); color: var(--text); }

.od-btn-danger {
    background: transparent;
    border: 1px solid rgba(220,0,0,.3);
    color: var(--red);
}
.od-btn-danger:hover {
    background: var(--red-soft);
    border-color: rgba(220,0,0,.5);
}

/* ── Notices ── */
.od-notice {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 12px;
    line-height: 1.65;
    width: 100%;
}
.od-notice.yellow {
    background: var(--yellow-soft);
    border: 1px solid rgba(245,197,24,.18);
    color: var(--yellow);
}
.od-notice.blue {
    background: var(--blue-soft);
    border: 1px solid rgba(58,142,246,.18);
    color: var(--blue);
}
.od-notice i { margin-top: 1px; flex-shrink: 0; opacity: .8; }
.od-notice strong { font-weight: 600; }

/* ── Modal ── */
.od-modal-backdrop {
    position: fixed;
    inset: 0;
    z-index: 9000;
    background: rgba(0,0,0,.8);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity .3s ease;
    padding: 24px;
}
.od-modal-backdrop.open {
    opacity: 1;
    pointer-events: all;
}

.od-modal {
    background: var(--surface);
    border: 1px solid rgba(220,0,0,.18);
    border-radius: 18px;
    padding: 40px 36px;
    max-width: 400px;
    width: 100%;
    text-align: center;
    box-shadow:
        0 40px 100px rgba(0,0,0,.7),
        0 0 0 1px var(--border) inset;
    transform: translateY(30px) scale(.95);
    transition: transform .4s var(--ease-spring);
}
.od-modal-backdrop.open .od-modal {
    transform: translateY(0) scale(1);
}

.od-modal-icon {
    width: 58px; height: 58px;
    border-radius: 50%;
    background: var(--red-soft);
    border: 1px solid rgba(220,0,0,.25);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
    font-size: 22px;
    color: var(--red);
}

.od-modal-title {
    font-family: var(--display);
    font-size: 26px;
    letter-spacing: 4px;
    margin-bottom: 8px;
}

.od-modal-desc {
    color: var(--text-muted);
    font-size: 13px;
    margin-bottom: 20px;
    line-height: 1.75;
}

.od-modal-refund {
    background: var(--blue-soft);
    border: 1px solid rgba(58,142,246,.18);
    border-radius: 7px;
    padding: 10px 14px;
    margin-bottom: 18px;
    font-size: 12px;
    color: var(--blue);
    line-height: 1.65;
    text-align: left;
}

.od-modal-field {
    text-align: left;
    margin-bottom: 20px;
}
.od-modal-field label {
    display: block;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.od-textarea {
    width: 100%;
    background: var(--surface2);
    border: 1px solid var(--border-md);
    border-radius: 8px;
    padding: 10px 14px;
    font-family: var(--sans);
    font-size: 13px;
    color: var(--text);
    resize: none;
    outline: none;
    transition: border-color .2s;
    line-height: 1.6;
}
.od-textarea::placeholder { color: var(--text-muted); }
.od-textarea:focus { border-color: rgba(220,0,0,.4); }

.od-modal-btns {
    display: flex;
    gap: 10px;
    justify-content: center;
}

/* ── Leaflet customisation ── */
.leaflet-tile-pane { opacity: 1 !important; }
.leaflet-container { background: #111 !important; }
.leaflet-control-attribution {
    background: rgba(10,10,10,.75) !important;
    color: #444 !important;
    font-size: 9px !important;
    padding: 2px 6px !important;
}
.leaflet-control-attribution a { color: #555 !important; }

/* ── Stagger helper ── */
.od-field:nth-child(1)  { animation: fadeUp .4s var(--ease-out) .15s both; }
.od-field:nth-child(2)  { animation: fadeUp .4s var(--ease-out) .18s both; }
.od-field:nth-child(3)  { animation: fadeUp .4s var(--ease-out) .21s both; }
.od-field:nth-child(4)  { animation: fadeUp .4s var(--ease-out) .24s both; }
.od-field:nth-child(5)  { animation: fadeUp .4s var(--ease-out) .27s both; }
.od-field:nth-child(6)  { animation: fadeUp .4s var(--ease-out) .30s both; }
.od-field:nth-child(7)  { animation: fadeUp .4s var(--ease-out) .33s both; }
.od-field:nth-child(8)  { animation: fadeUp .4s var(--ease-out) .36s both; }
</style>
@endpush


{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- CANCEL MODAL                                                   --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div class="od-modal-backdrop" id="cancelModal" role="dialog" aria-modal="true" aria-labelledby="cancelModalTitle">
    <div class="od-modal">
        <div class="od-modal-icon">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="od-modal-title" id="cancelModalTitle">Cancel Order</div>
        <p class="od-modal-desc">
            Are you sure you want to cancel Order <strong style="color:var(--text)">#{{ $order->id }}</strong>?
            This action cannot be undone.
        </p>

        @if($order->payment_method === 'paypal' && $order->payment_status === 'paid')
        <div class="od-modal-refund">
            <i class="fab fa-paypal"></i>&nbsp;
            Your PayPal payment will be <strong>automatically refunded</strong> within 3–5 business days.
        </div>
        @endif

        <form method="POST" action="{{ route('orders.cancel', $order) }}" style="margin:0">
            @csrf
            <div class="od-modal-field">
                <label for="cancelReason">Reason (optional)</label>
                <textarea id="cancelReason" name="cancel_reason" class="od-textarea" rows="2"
                    placeholder="Tell us why you're cancelling…"></textarea>
            </div>
            <div class="od-modal-btns">
                <button type="button" class="od-btn od-btn-ghost" onclick="closeModal()">
                    <i class="fas fa-arrow-left"></i> Keep Order
                </button>
                <button type="submit" class="od-btn od-btn-red">
                    <i class="fas fa-trash-alt"></i> Yes, Cancel
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- PAGE CONTENT                                                   --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
@section('content')
<div class="od-page">
<div class="od-wrap">

    {{-- ── Header ── --}}
    <div class="od-header">
        <a href="{{ route('orders.index') }}" class="od-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            All Orders
        </a>

        <div class="od-title-row">
            <div class="od-order-id">Order <span>#{{ $order->id }}</span></div>
            <div class="od-status {{ $order->status }}">
                <span class="dot"></span>
                {{ strtoupper($order->status) }}
            </div>
        </div>
    </div>

    {{-- ── Main Card ── --}}
    <div class="od-card">

        {{-- Header strip --}}
        <div class="od-card-header">
            <div class="od-card-header-icon"><i class="fas fa-file-alt"></i></div>
            <span class="od-card-header-label">Order Details</span>
            <span style="margin-left:auto;font-family:var(--mono);font-size:10px;color:#333">
                {{ $order->created_at->format('M d, Y · H:i') }}
            </span>
        </div>

        <div class="od-card-body">
            <div class="od-grid">

                {{-- Car Name --}}
                <div class="od-field">
                    <div class="od-field-label"><i class="fas fa-car"></i> Vehicle</div>
                    <div class="od-car-name">{{ $order->car->name }}</div>
                </div>

                {{-- Amount --}}
                <div class="od-field">
                    <div class="od-field-label"><i class="fas fa-tag"></i> Total Amount</div>
                    <div class="od-price">₱{{ number_format($order->total_price, 2) }}</div>
                </div>

            </div>

            <div class="od-divider"></div>

            <div class="od-grid">

                {{-- Buyer Name --}}
                <div class="od-field">
                    <div class="od-field-label"><i class="fas fa-user"></i> Buyer Name</div>
                    <div class="od-field-value">{{ $order->buyer_name }}</div>
                </div>

                {{-- Contact --}}
                <div class="od-field">
                    <div class="od-field-label"><i class="fas fa-phone"></i> Contact</div>
                    <div class="od-field-value" style="font-family:var(--mono);font-size:13px">{{ $order->buyer_contact }}</div>
                </div>

                {{-- Address --}}
                <div class="od-field full">
                    <div class="od-field-label"><i class="fas fa-map-marker-alt"></i> Delivery Address</div>
                    <div class="od-field-value">{{ $order->buyer_address }}</div>
                </div>

            </div>

            <div class="od-divider"></div>

            <div class="od-grid">

                {{-- Payment --}}
                <div class="od-field">
                    <div class="od-field-label"><i class="fas fa-credit-card"></i> Payment</div>
                    <div class="od-field-value">
                        <strong>{{ strtoupper(str_replace('_',' ',$order->payment_method)) }}</strong>
                        @if($order->payment_method === 'cod')
                            @if($order->cod_paid)
                                <span class="od-pay-badge paid">Cash Paid ✓</span>
                            @else
                                <span class="od-pay-badge unpaid">Unpaid</span>
                            @endif
                        @else
                            <span class="od-pay-badge {{ $order->payment_status === 'paid' ? 'paid' : 'unpaid' }}">
                                {{ strtoupper($order->payment_status) }}
                            </span>
                        @endif
                    </div>
                    @if($order->payment_reference)
                        <div class="od-ref">Ref: {{ $order->payment_reference }}</div>
                    @endif
                </div>

                {{-- Estimated Delivery --}}
                @if($order->estimated_delivery)
                <div class="od-field">
                    <div class="od-field-label"><i class="fas fa-calendar-check"></i> Est. Delivery</div>
                    <div class="od-field-value">{{ $order->estimated_delivery->format('F d, Y') }}</div>
                </div>
                @endif

                {{-- Delivery Notes --}}
                @if($order->delivery_notes)
                <div class="od-field full">
                    <div class="od-field-label"><i class="fas fa-sticky-note"></i> Delivery Notes</div>
                    <div class="od-field-value" style="color:var(--text-dim)">{{ $order->delivery_notes }}</div>
                </div>
                @endif

            </div>

        </div>{{-- /card-body --}}

        {{-- ── Driver ── --}}
        @if($order->driver)
        <div style="padding: 0 8px 16px;">
            <div class="od-driver">
                <div class="od-driver-avatar">{{ strtoupper(substr($order->driver->name, 0, 1)) }}</div>
                <div>
                    <div class="od-field-label" style="margin-bottom:3px"><i class="fas fa-steering-wheel"></i> Assigned Driver</div>
                    <div class="od-driver-name">{{ $order->driver->name }}</div>
                    <div class="od-driver-meta">
                        {{ $order->driver->contact_number }}
                        @if($order->driver->vehicle_info)
                            &nbsp;·&nbsp; {{ $order->driver->vehicle_info }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Map ── --}}
        @if($order->hasCoordinates())
        <div class="od-map-wrap">
            <div class="od-map-header">
                <i class="fas fa-map-marker-alt"></i>
                Pinned Delivery Location
                <span class="od-map-coords">
                    {{ number_format($order->delivery_latitude, 6) }},
                    {{ number_format($order->delivery_longitude, 6) }}
                </span>
            </div>
            <div id="orderMap"></div>
        </div>
        @endif

    </div>{{-- /card --}}

    {{-- ── Action Row ── --}}
    <div class="od-actions">

        @if($order->driver_id)
        <a href="{{ route('chat.show', $order) }}" class="od-btn od-btn-red">
            <i class="fas fa-comment-dots"></i> Chat Driver
        </a>
        @endif

        @if($order->isCancellable())
            <button type="button" onclick="openModal()" class="od-btn od-btn-danger">
                <i class="fas fa-times"></i> Cancel Order
            </button>
        @elseif($order->status !== 'cancelled' && $order->status !== 'delivered')
            <div class="od-notice yellow">
                <i class="fas fa-lock"></i>
                <div>
                    <strong>Cancellation Locked</strong> —
                    @if($order->admin_accepted)
                        This order has been confirmed by admin and is now
                        <strong>{{ ucfirst($order->status) }}</strong>.
                        It can no longer be cancelled.
                    @else
                        Orders with status <strong>{{ ucfirst($order->status) }}</strong>
                        cannot be cancelled.
                    @endif
                </div>
            </div>
        @endif

    </div>

</div>{{-- /wrap --}}
</div>{{-- /page --}}
@endsection


{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- SCRIPTS                                                        --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

@if($order->hasCoordinates())
<script>
(function () {
    var lat = parseFloat("{{ $order->delivery_latitude }}");
    var lng = parseFloat("{{ $order->delivery_longitude }}");
    if (isNaN(lat) || isNaN(lng)) return;

    function initMap() {
        var el = document.getElementById('orderMap');
        if (!el) return;

        el.style.height  = '260px';
        el.style.display = 'block';

        var map = L.map('orderMap', {
            center: [lat, lng],
            zoom: 16,
            zoomControl: true,
            dragging: true,
            scrollWheelZoom: false,
            fadeAnimation: false,
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
            crossOrigin: 'anonymous',
        }).addTo(map);

        // Red teardrop pin
        var pin = L.divIcon({
            className: '',
            html: [
                '<div style="',
                    'width:22px;height:22px;',
                    'border-radius:50% 50% 50% 0;',
                    'background:#dc0000;',
                    'border:3px solid #fff;',
                    'transform:rotate(-45deg);',
                    'box-shadow:0 4px 16px rgba(220,0,0,.55),0 0 0 4px rgba(220,0,0,.12);',
                '"></div>'
            ].join(''),
            iconSize:    [22, 22],
            iconAnchor:  [11, 22],
            popupAnchor: [0, -28],
        });

        L.marker([lat, lng], { icon: pin })
            .addTo(map)
            .bindPopup(
                '<div style="font-size:12px;color:#111;font-family:sans-serif;line-height:1.5">'
                + '{{ addslashes($order->buyer_address) }}'
                + '</div>'
            )
            .openPopup();

        setTimeout(function () { map.invalidateSize(); }, 300);
    }

    if (document.readyState === 'complete') { initMap(); }
    else { window.addEventListener('load', initMap); }
})();
</script>
@endif

<script>
(function () {
    var modal  = document.getElementById('cancelModal');
    var opener = null; // track focus for a11y

    function openModal() {
        opener = document.activeElement;
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';
        modal.querySelector('textarea').focus();
    }
    function closeModal() {
        modal.classList.remove('open');
        document.body.style.overflow = '';
        if (opener) opener.focus();
    }

    // Expose globals (used by inline onclick)
    window.openModal  = openModal;
    window.closeModal = closeModal;

    // Click-outside-to-close
    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });

    // Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
    });
})();
</script>
@endpush