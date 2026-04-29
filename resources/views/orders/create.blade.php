@extends('layouts.app')
@section('title', 'Purchase — '.$car->name)
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; }

:root {
    --ferrari-red:  #dc0000;
    --red-glow:     rgba(220,0,0,0.35);
    --red-subtle:   rgba(220,0,0,0.06);
    --dark1:  #0a0a0a;
    --dark2:  #111111;
    --dark3:  #181818;
    --dark4:  #1f1f1f;
    --border: #252525;
    --border-hover: #333;
    --gray:   #777;
    --light:  #f0f0f0;
}

@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(22px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes lineExpand {
    from { transform: scaleX(0); }
    to   { transform: scaleX(1); }
}
@keyframes subtlePulse {
    0%, 100% { box-shadow: 0 0 0 0 var(--red-glow); }
    50%       { box-shadow: 0 0 0 8px transparent; }
}
@keyframes ripple {
    from { transform: scale(0); opacity: .4; }
    to   { transform: scale(2.5); opacity: 0; }
}
@keyframes btnGlow {
    0%, 100% { box-shadow: 0 4px 22px rgba(220,0,0,.3), 0 0 0 0 transparent; }
    50%       { box-shadow: 0 6px 30px rgba(220,0,0,.5), 0 0 0 6px transparent; }
}
@keyframes shimmer {
    0%   { left: -100%; }
    100% { left: 200%; }
}
@keyframes dot1 { 0%,80%,100%{opacity:0} 40%{opacity:1} }

/* ── Page layout ── */
.page-wrap {
    width: 100%;
    max-width: 1100px;
    margin: 0 auto;
    padding: 36px 16px 48px;
    animation: fadeSlideUp .55s cubic-bezier(.22,.68,0,1.2) both;
}

.section-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(26px, 5vw, 36px);
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--light);
    line-height: 1;
}
.section-title span { color: var(--ferrari-red); }

.section-divider {
    height: 2px;
    background: linear-gradient(90deg, var(--ferrari-red) 0%, transparent 100%);
    margin: 10px 0 0;
    transform-origin: left;
    animation: lineExpand .65s .2s cubic-bezier(.22,.68,0,1.2) both;
}

/* ── Car header bar ── */
.car-header {
    background: var(--dark3);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 18px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    position: relative;
    overflow: hidden;
    margin-top: 22px;
    animation: fadeSlideUp .6s .1s cubic-bezier(.22,.68,0,1.2) both;
}
.car-header::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--ferrari-red);
    box-shadow: 0 0 16px var(--red-glow);
}
.car-name {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(19px, 4vw, 24px);
    letter-spacing: 2px;
    line-height: 1.1;
}
.car-desc { color: var(--gray); font-size: 12px; margin-top: 3px; }
.car-price {
    color: var(--ferrari-red);
    font-size: clamp(18px, 4vw, 24px);
    font-weight: 700;
    white-space: nowrap;
    letter-spacing: 1px;
}

/* ── Two-column grid ── */
.purchase-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 24px;
    margin-top: 20px;
    align-items: start;
}

/* ── Column panels ── */
.panel {
    background: var(--dark2);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 22px;
    animation: fadeSlideUp .6s .15s cubic-bezier(.22,.68,0,1.2) both;
}

.section-head {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 16px;
    letter-spacing: 2.5px;
    margin-bottom: 14px;
    color: var(--ferrari-red);
    display: flex;
    align-items: center;
    gap: 10px;
}
.section-head::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, var(--border) 0%, transparent 100%);
}

/* ── Form controls ── */
.form-group { margin-bottom: 12px; }
.form-group label {
    display: block;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--gray);
    margin-bottom: 6px;
}
.form-control {
    width: 100%;
    background: var(--dark3);
    border: 1px solid var(--border);
    border-radius: 7px;
    padding: 10px 14px;
    color: var(--light);
    font-size: 14px;
    font-family: 'Barlow', sans-serif;
    outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
    resize: vertical;
}
.form-control:focus {
    border-color: rgba(220,0,0,.5);
    box-shadow: 0 0 0 3px rgba(220,0,0,.08);
    background: var(--dark4);
}
.form-control::placeholder { color: #3a3a3a; }
.form-error { color: #ff5555; font-size: 12px; margin-top: 4px; min-height: 16px; }

/* ── Map section ── */
.map-section {
    margin-bottom: 0;
    border: 1px solid var(--border);
    border-radius: 9px;
    overflow: hidden;
    transition: border-color .3s, box-shadow .3s;
}
.map-section:focus-within {
    border-color: rgba(220,0,0,.35);
    box-shadow: 0 0 0 3px rgba(220,0,0,.06);
}
.map-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 12px 14px;
    background: var(--dark3);
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
}
.map-header-left { display: flex; align-items: center; gap: 10px; }
.map-header-icon {
    width: 30px; height: 30px; border-radius: 6px;
    background: rgba(220,0,0,.1);
    border: 1px solid rgba(220,0,0,.22);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.map-header-icon i { color: var(--ferrari-red); font-size: 12px; }
.map-header-title { font-weight: 700; font-size: 13px; }
.map-header-sub   { color: var(--gray); font-size: 11px; margin-top: 1px; }
.map-optional-badge {
    font-size: 9px; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; color: #444;
    background: #151515;
    border: 1px solid var(--border);
    border-radius: 4px; padding: 3px 8px; flex-shrink: 0;
}
.map-search-wrap {
    display: flex; gap: 8px;
    padding: 9px 10px;
    background: var(--dark2);
    border-bottom: 1px solid var(--border);
}
.map-search-input {
    flex: 1; min-width: 0;
    background: var(--dark3);
    border: 1px solid #2e2e2e;
    border-radius: 6px; padding: 8px 12px;
    color: var(--light); font-size: 13px;
    font-family: 'Barlow', sans-serif;
    outline: none; transition: border-color .2s;
}
.map-search-input:focus { border-color: var(--ferrari-red); }
.map-search-input::placeholder { color: #333; }
.map-search-btn {
    padding: 8px 14px;
    background: var(--ferrari-red);
    border: none; border-radius: 6px;
    color: #fff; font-size: 12px; font-weight: 700;
    letter-spacing: 1px; text-transform: uppercase;
    cursor: pointer; font-family: 'Barlow', sans-serif;
    white-space: nowrap; flex-shrink: 0;
    transition: background .2s, transform .15s, box-shadow .2s;
}
.map-search-btn:hover {
    background: #b00000;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(220,0,0,.3);
}
.map-search-btn:active { transform: translateY(0); }
#deliveryMap {
    height: 220px;
    width: 100%;
    display: block;
    position: relative;
    z-index: 1;
    background: #131313;
}
.map-loc-btn {
    position: absolute; top: 10px; right: 10px; z-index: 900;
    background: var(--dark2); border: 1px solid #333;
    border-radius: 6px; padding: 6px 12px; font-size: 11px;
    font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
    color: var(--gray); cursor: pointer; font-family: 'Barlow', sans-serif;
    display: flex; align-items: center; gap: 6px;
    transition: border-color .2s, color .2s, background .2s;
}
.map-loc-btn:hover {
    border-color: var(--ferrari-red);
    color: var(--ferrari-red);
    background: rgba(220,0,0,.05);
}
.map-hint {
    position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%);
    background: rgba(10,10,10,.88); border: 1px solid #2a2a2a;
    border-radius: 20px; padding: 5px 16px; font-size: 11px;
    color: #444; white-space: nowrap; z-index: 900; pointer-events: none;
    transition: opacity .4s ease;
}
.map-hint.hide { opacity: 0; }
.map-address-bar {
    display: none; align-items: flex-start; gap: 10px;
    padding: 10px 14px;
    background: rgba(220,0,0,.04);
    border-top: 1px solid rgba(220,0,0,.12);
}
.map-address-bar.visible { display: flex; }
.map-address-bar i { color: var(--ferrari-red); margin-top: 2px; flex-shrink: 0; }
.map-address-text { font-size: 13px; color: var(--light); line-height: 1.5; flex: 1; }
.map-clear-btn {
    background: none; border: 1px solid #2e2e2e; color: #555;
    border-radius: 5px; padding: 4px 10px; font-size: 11px;
    cursor: pointer; flex-shrink: 0;
    transition: border-color .2s, color .2s;
    font-family: 'Barlow', sans-serif;
}
.map-clear-btn:hover { border-color: #ff4444; color: #ff4444; }

/* ── Payment options ── */
.pay-option {
    display: flex; align-items: center; gap: 12px;
    padding: 11px 14px;
    border: 1px solid var(--border);
    border-radius: 8px; cursor: pointer;
    transition: border-color .25s ease, background .25s ease, transform .2s ease, box-shadow .25s ease;
    margin-bottom: 8px; user-select: none;
}
.pay-option:hover {
    border-color: rgba(220,0,0,.35);
    background: var(--red-subtle);
    transform: translateX(3px);
}
.pay-option input[type=radio] {
    accent-color: var(--ferrari-red);
    width: 15px; height: 15px; flex-shrink: 0;
}
.pay-option.selected {
    border-color: var(--ferrari-red);
    background: var(--red-subtle);
    box-shadow: 0 0 0 1px rgba(220,0,0,.15), inset 0 0 20px rgba(220,0,0,.03);
}
.pay-icon { font-size: 18px; width: 26px; text-align: center; flex-shrink: 0; }
.pay-label { font-weight: 700; font-size: 13px; }
.pay-sub { color: var(--gray); font-size: 11px; margin-top: 1px; }

/* ── Total + actions ── */
.total-bar {
    background: var(--dark3);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 14px 18px;
    margin-top: 16px;
    margin-bottom: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    position: relative;
    overflow: hidden;
}
.total-bar::before {
    content: '';
    position: absolute;
    right: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--ferrari-red);
    box-shadow: 0 0 14px var(--red-glow);
}
.total-label { color: var(--gray); font-size: 14px; letter-spacing: .5px; }
.total-amount {
    color: var(--ferrari-red);
    font-weight: 700;
    font-size: clamp(18px, 3vw, 22px);
    letter-spacing: 1px;
}

.form-actions {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
}

.btn-confirm {
    position: relative;
    display: block;
    width: 100%;
    padding: 14px 28px;
    background: var(--ferrari-red);
    color: #fff;
    border: none;
    border-radius: 7px;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 18px;
    letter-spacing: 3px;
    text-transform: uppercase;
    cursor: pointer;
    text-align: center;
    overflow: hidden;
    transition: background .25s, transform .2s cubic-bezier(.22,.68,0,1.3), box-shadow .25s;
    box-shadow: 0 4px 22px rgba(220,0,0,.3);
    animation: btnGlow 3s ease-in-out infinite;
    -webkit-tap-highlight-color: transparent;
}
.btn-confirm::before {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 60%; height: 100%;
    background: linear-gradient(120deg, transparent, rgba(255,255,255,.18), transparent);
    transition: none;
    pointer-events: none;
}
.btn-confirm:hover::before { animation: shimmer .55s ease forwards; }
.btn-confirm .ripple-el {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,.25);
    pointer-events: none;
    animation: ripple .6s ease-out forwards;
}
.btn-confirm:hover {
    background: #c00000;
    transform: translateY(-2px) scale(1.01);
    box-shadow: 0 8px 32px rgba(220,0,0,.5);
    animation: none;
}
.btn-confirm:active {
    transform: translateY(0) scale(.99);
    box-shadow: 0 3px 16px rgba(220,0,0,.4);
}
.btn-confirm:disabled {
    opacity: .75;
    cursor: not-allowed;
    transform: none;
    animation: subtlePulse 1.5s ease-in-out infinite;
}

.processing-dots span:nth-child(1) { animation: dot1 1.2s .0s infinite; }
.processing-dots span:nth-child(2) { animation: dot1 1.2s .2s infinite; }
.processing-dots span:nth-child(3) { animation: dot1 1.2s .4s infinite; }

.btn-cancel {
    display: block;
    width: 100%;
    padding: 11px 24px;
    background: transparent;
    color: var(--gray);
    border: 1px solid var(--border);
    border-radius: 7px;
    font-family: 'Barlow', sans-serif;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: border-color .25s, color .25s, background .25s, transform .2s;
}
.btn-cancel:hover {
    border-color: #444;
    color: var(--light);
    background: rgba(255,255,255,.03);
    transform: translateY(-1px);
}
.btn-cancel:active { transform: translateY(0); }

/* ── Responsive ── */
@media (max-width: 860px) {
    .purchase-grid { grid-template-columns: 1fr; }
    #deliveryMap   { height: 240px; }
    .map-search-btn { padding: 8px 10px; font-size: 11px; letter-spacing: .5px; }
}
@media (max-width: 480px) {
    .pay-option:hover { transform: none; }
}
</style>
@endpush

@section('content')
<div class="page-wrap">

    <p class="section-title">Purchase <span>{{ $car->name }}</span></p>
    <div class="section-divider"></div>

    {{-- Car info bar (full width) --}}
    <div class="car-header">
        <div>
            <div class="car-name">{{ $car->name }}</div>
            <div class="car-desc">{{ $car->description }}</div>
        </div>
        <div class="car-price">₱{{ number_format($car->price, 2) }}</div>
    </div>

    <form method="POST"
          action="{{ route('orders.store') }}"
          id="orderForm"
          onsubmit="return handleSubmit(event)">
        @csrf
        <input type="hidden" name="car_id" value="{{ $car->id }}">
        <input type="hidden" name="delivery_latitude"  id="latInput"  value="{{ old('delivery_latitude') }}">
        <input type="hidden" name="delivery_longitude" id="lngInput"  value="{{ old('delivery_longitude') }}">

        <div class="purchase-grid">

            {{-- ─── Left column: Delivery details + Payment ─── --}}
            <div class="panel">
                <div class="section-head">Delivery Details</div>

                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="buyer_name" class="form-control"
                        value="{{ old('buyer_name', auth()->user()->name) }}"
                        placeholder="Complete Legal Name" required>
                    <div class="form-error">@error('buyer_name'){{ $message }}@enderror</div>
                </div>

                <div class="form-group">
                    <label>Contact Number *</label>
                    <input type="text" name="buyer_contact" class="form-control"
                        value="{{ old('buyer_contact', auth()->user()->contact_number) }}"
                        placeholder="e.g. 09171234567"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                        maxlength="15" required>
                    <div class="form-error">@error('buyer_contact'){{ $message }}@enderror</div>
                </div>

                <div class="form-group">
                    <label>Delivery Address *</label>
                    <textarea name="buyer_address" id="addressTextarea" class="form-control" rows="2"
                        placeholder="Type your address or drop a pin on the map"
                        required>{{ old('buyer_address', auth()->user()->address) }}</textarea>
                    <div class="form-error">@error('buyer_address'){{ $message }}@enderror</div>
                </div>

                <div class="section-head" style="margin-top:16px">Payment Method</div>

                <label class="pay-option @if(old('payment_method','cod')==='cod') selected @endif"
                       id="opt-cod" onclick="selPay('cod')">
                    <input type="radio" name="payment_method" value="cod"
                           @if(old('payment_method','cod')==='cod') checked @endif
                           onchange="selPay('cod')">
                    <span class="pay-icon" style="color:#f5c518"><i class="fas fa-money-bill-wave"></i></span>
                    <div>
                        <div class="pay-label">Cash on Delivery</div>
                        <div class="pay-sub">Pay when your Ferrari arrives</div>
                    </div>
                </label>

                <label class="pay-option @if(old('payment_method')==='credit_card') selected @endif"
                       id="opt-credit_card" onclick="selPay('credit_card')">
                    <input type="radio" name="payment_method" value="credit_card"
                           @if(old('payment_method')==='credit_card') checked @endif
                           onchange="selPay('credit_card')">
                    <span class="pay-icon" style="color:#4d88ff"><i class="fas fa-credit-card"></i></span>
                    <div>
                        <div class="pay-label">Credit / Debit Card</div>
                        <div class="pay-sub">Visa, Mastercard, AMEX accepted</div>
                    </div>
                </label>

                <label class="pay-option @if(old('payment_method')==='paypal') selected @endif"
                       id="opt-paypal" onclick="selPay('paypal')">
                    <input type="radio" name="payment_method" value="paypal"
                           @if(old('payment_method')==='paypal') checked @endif
                           onchange="selPay('paypal')">
                    <span class="pay-icon" style="color:#009cde"><i class="fab fa-paypal"></i></span>
                    <div>
                        <div class="pay-label">PayPal</div>
                        <div class="pay-sub">Fast, secure PayPal checkout</div>
                    </div>
                </label>

                <div class="form-error">@error('payment_method'){{ $message }}@enderror</div>

                <div class="total-bar">
                    <span class="total-label">Total Amount</span>
                    <span class="total-amount">₱{{ number_format($car->price, 2) }}</span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-confirm" id="confirmBtn">
                        Confirm Purchase
                    </button>
                    <a href="{{ route('shop') }}" class="btn-cancel">Cancel</a>
                </div>
            </div>

            {{-- ─── Right column: Map pin ─── --}}
            <div class="panel">
                <div class="section-head">Delivery Pin</div>

                <div class="map-section">
                    <div class="map-header">
                        <div class="map-header-left">
                            <div class="map-header-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <div class="map-header-title">Pin Your Delivery Location</div>
                                <div class="map-header-sub">Drop a pin for more accurate delivery</div>
                            </div>
                        </div>
                        <span class="map-optional-badge">Optional</span>
                    </div>

                    <div class="map-search-wrap">
                        <input type="text" id="mapSearchInput" class="map-search-input"
                            placeholder="Search for a location…"
                            onkeydown="if(event.key==='Enter'){event.preventDefault();searchLocation()}">
                        <button type="button" class="map-search-btn" onclick="searchLocation()">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>

                    <div style="position:relative">
                        <div id="deliveryMap"></div>
                        <button type="button" class="map-loc-btn" id="locBtn" onclick="useMyLocation()">
                            <i class="fas fa-crosshairs"></i> My Location
                        </button>
                        <div class="map-hint" id="mapHint">Click anywhere on the map to drop a pin</div>
                    </div>

                    <div class="map-address-bar" id="mapAddressBar">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="map-address-text" id="mapAddressText">—</div>
                        <button type="button" class="map-clear-btn" onclick="clearPin()">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>
            </div>

        </div>{{-- end .purchase-grid --}}
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
(function () {
    var CAR_ID       = '{{ $car->id }}';
    var STORAGE_KEY  = 'delivery_pin_' + CAR_ID;

    var DEFAULT_LAT  = 15.9995;
    var DEFAULT_LNG  = 120.4853;
    var DEFAULT_ZOOM = 13;

    var map = L.map('deliveryMap', {
        center: [DEFAULT_LAT, DEFAULT_LNG],
        zoom: DEFAULT_ZOOM,
        zoomControl: true,
        fadeAnimation: false,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
        crossOrigin: 'anonymous',
    }).addTo(map);

    window.addEventListener('load', function () { map.invalidateSize(); });

    var redIcon = L.divIcon({
        className: '',
        html: '<div style="width:26px;height:26px;border-radius:50% 50% 50% 0;background:#dc0000;border:3px solid #fff;transform:rotate(-45deg);box-shadow:0 3px 14px rgba(220,0,0,0.55);margin-left:-4px;margin-top:-4px;"></div>',
        iconSize: [26, 26],
        iconAnchor: [13, 26],
        popupAnchor: [0, -30],
    });

    var marker = null;

    var saved = null;
    try { saved = JSON.parse(sessionStorage.getItem(STORAGE_KEY)); } catch(e) {}

    @if(old('delivery_latitude') && old('delivery_longitude'))
        placePin({{ old('delivery_latitude') }}, {{ old('delivery_longitude') }}, '{{ addslashes(old('buyer_address', '')) }}');
    @else
        if (saved && saved.lat && saved.lng) {
            map.setView([saved.lat, saved.lng], 16);
            placePin(saved.lat, saved.lng, saved.address || null);
            hideHint();
            if (saved.address) {
                var ta = document.getElementById('addressTextarea');
                if (!ta.value.trim()) ta.value = saved.address;
            }
        }
    @endif

    map.on('click', function (e) {
        placePin(e.latlng.lat, e.latlng.lng);
        hideHint();
    });

    async function placePin(lat, lng, forcedAddress) {
        if (marker) map.removeLayer(marker);

        marker = L.marker([lat, lng], { icon: redIcon, draggable: true }).addTo(map);
        marker.on('dragend', function (e) {
            var pos = e.target.getLatLng();
            placePin(pos.lat, pos.lng);
        });

        document.getElementById('latInput').value = lat.toFixed(8);
        document.getElementById('lngInput').value = lng.toFixed(8);

        var address = forcedAddress || null;
        if (!address) {
            try {
                var res  = await fetch(
                    'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + lat + '&lon=' + lng,
                    { headers: { 'Accept-Language': 'en', 'User-Agent': 'VeloceVantage/1.0' } }
                );
                var data = await res.json();
                address  = data.display_name || (lat.toFixed(6) + ', ' + lng.toFixed(6));
            } catch (err) {
                address = lat.toFixed(6) + ', ' + lng.toFixed(6);
            }
        }

        var textarea = document.getElementById('addressTextarea');
        if (!textarea.value.trim() || textarea.value === textarea.dataset.original) {
            textarea.value = address;
        }
        document.getElementById('mapAddressText').textContent = address;
        document.getElementById('mapAddressBar').classList.add('visible');
        marker.bindPopup('<div style="font-size:12px;max-width:200px;color:#111">' + address + '</div>').openPopup();

        try {
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify({ lat: lat, lng: lng, address: address }));
        } catch(e) {}
    }

    document.getElementById('addressTextarea').dataset.original =
        document.getElementById('addressTextarea').value;

    window.searchLocation = async function () {
        var query = document.getElementById('mapSearchInput').value.trim();
        if (!query) return;

        var btn = document.querySelector('.map-search-btn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching…';
        btn.disabled  = true;

        try {
            var res  = await fetch(
                'https://nominatim.openstreetmap.org/search?format=jsonv2&q=' + encodeURIComponent(query) + '&limit=1',
                { headers: { 'Accept-Language': 'en', 'User-Agent': 'VeloceVantage/1.0' } }
            );
            var data = await res.json();
            if (data.length > 0) {
                var lat = parseFloat(data[0].lat);
                var lon = parseFloat(data[0].lon);
                map.flyTo([lat, lon], 16, { duration: 1.2 });
                await placePin(lat, lon, data[0].display_name);
                hideHint();
            } else {
                alert('Location not found. Try a more specific address.');
            }
        } catch (err) {
            alert('Search failed. Please try again.');
        } finally {
            btn.innerHTML = '<i class="fas fa-search"></i> Search';
            btn.disabled  = false;
        }
    };

    window.useMyLocation = function () {
        if (!navigator.geolocation) { alert('Geolocation is not supported by your browser.'); return; }
        var btn = document.getElementById('locBtn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Locating…';
        btn.disabled  = true;

        navigator.geolocation.getCurrentPosition(
            async function (pos) {
                var lat = pos.coords.latitude;
                var lng = pos.coords.longitude;
                map.flyTo([lat, lng], 17, { duration: 1.2 });
                await placePin(lat, lng);
                hideHint();
                btn.innerHTML = '<i class="fas fa-crosshairs"></i> My Location';
                btn.disabled  = false;
            },
            function () {
                alert('Could not get your location. Please allow location access or drop a pin manually.');
                btn.innerHTML = '<i class="fas fa-crosshairs"></i> My Location';
                btn.disabled  = false;
            }
        );
    };

    window.clearPin = function () {
        if (marker) { map.removeLayer(marker); marker = null; }
        document.getElementById('latInput').value = '';
        document.getElementById('lngInput').value = '';
        document.getElementById('mapAddressBar').classList.remove('visible');
        document.getElementById('mapAddressText').textContent = '—';
        document.getElementById('mapHint').classList.remove('hide');
        try { sessionStorage.removeItem(STORAGE_KEY); } catch(e) {}
    };

    function hideHint() {
        document.getElementById('mapHint').classList.add('hide');
    }
})();

/* ── Payment method ── */
function selPay(val) {
    ['cod', 'credit_card', 'paypal'].forEach(function (v) {
        var el = document.getElementById('opt-' + v);
        if (el) el.classList.toggle('selected', v === val);
    });
    var labels = {
        cod:         'Confirm Purchase',
        credit_card: 'Continue to Card Payment →',
        paypal:      'Continue to PayPal →',
    };
    document.getElementById('confirmBtn').textContent = labels[val] || 'Confirm Purchase';
}

/* ── Ripple effect ── */
document.getElementById('confirmBtn').addEventListener('click', function (e) {
    var btn  = this;
    var rect = btn.getBoundingClientRect();
    var size = Math.max(rect.width, rect.height) * 1.2;
    var x    = e.clientX - rect.left - size / 2;
    var y    = e.clientY - rect.top  - size / 2;
    var ripple = document.createElement('span');
    ripple.classList.add('ripple-el');
    ripple.style.cssText = 'width:' + size + 'px;height:' + size + 'px;left:' + x + 'px;top:' + y + 'px;';
    btn.appendChild(ripple);
    setTimeout(function () { ripple.remove(); }, 650);
});

/* ── Submit handler ── */
function handleSubmit(e) {
    var btn = document.getElementById('confirmBtn');
    btn.disabled = true;
    btn.innerHTML = 'Processing<span class="processing-dots"><span>.</span><span>.</span><span>.</span></span>';
    return true;
}

selPay('{{ old('payment_method', 'cod') }}');
</script>
@endpush