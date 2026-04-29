{{-- FILE: resources/views/cart/checkout.blade.php --}}
@extends('layouts.app')
@section('title','Checkout')
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

.checkout-page {
    padding: 36px 16px 48px;
    max-width: 1100px;
    margin: 0 auto;
    animation: fadeSlideUp .55s cubic-bezier(.22,.68,0,1.2) both;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 28px;
    margin-top: 28px;
    align-items: start;
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
.form-group { margin-bottom: 10px; }
.form-group label {
    display: block;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--gray);
    margin-bottom: 7px;
}
.form-control {
    width: 100%;
    background: var(--dark3);
    border: 1px solid var(--border);
    border-radius: 7px;
    padding: 11px 14px;
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
.form-error { color: #ff5555; font-size: 12px; margin-top: 5px; min-height: 18px; }

/* ── Map section (ported from create.blade.php) ── */
.map-section {
    margin-bottom: 20px;
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
    padding: 13px 16px;
    background: var(--dark3);
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
}
.map-header-left { display: flex; align-items: center; gap: 10px; }
.map-header-icon {
    width: 32px; height: 32px; border-radius: 7px;
    background: rgba(220,0,0,.1);
    border: 1px solid rgba(220,0,0,.22);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.map-header-icon i { color: var(--ferrari-red); font-size: 13px; }
.map-header-title { font-weight: 700; font-size: 13px; }
.map-header-sub   { color: var(--gray); font-size: 11px; margin-top: 1px; }
.map-optional-badge {
    font-size: 9px; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; color: #444;
    background: #151515;
    border: 1px solid var(--border);
    border-radius: 4px; padding: 3px 9px; flex-shrink: 0;
}
.map-search-wrap {
    display: flex; gap: 8px;
    padding: 10px 12px;
    background: var(--dark2);
    border-bottom: 1px solid var(--border);
}
.map-search-input {
    flex: 1; min-width: 0;
    background: var(--dark3);
    border: 1px solid #2e2e2e;
    border-radius: 6px; padding: 9px 13px;
    color: var(--light); font-size: 13px;
    font-family: 'Barlow', sans-serif;
    outline: none; transition: border-color .2s;
}
.map-search-input:focus { border-color: var(--ferrari-red); }
.map-search-input::placeholder { color: #333; }
.map-search-btn {
    padding: 9px 16px;
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
    height: 200px;
    width: 100%;
    display: block;
    position: relative;
    z-index: 1;
    background: #131313;
}
.map-loc-btn {
    position: absolute; top: 12px; right: 12px; z-index: 900;
    background: var(--dark2); border: 1px solid #333;
    border-radius: 6px; padding: 7px 13px; font-size: 11px;
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
    position: absolute; bottom: 12px; left: 50%; transform: translateX(-50%);
    background: rgba(10,10,10,.88); border: 1px solid #2a2a2a;
    border-radius: 20px; padding: 6px 18px; font-size: 11px;
    color: #444; white-space: nowrap; z-index: 900; pointer-events: none;
    transition: opacity .4s ease;
}
.map-hint.hide { opacity: 0; }
.map-address-bar {
    display: none; align-items: flex-start; gap: 10px;
    padding: 12px 15px;
    background: rgba(220,0,0,.04);
    border-top: 1px solid rgba(220,0,0,.12);
}
.map-address-bar.visible { display: flex; }
.map-address-bar i { color: var(--ferrari-red); margin-top: 2px; flex-shrink: 0; }
.map-address-text { font-size: 13px; color: var(--light); line-height: 1.5; flex: 1; }
.map-clear-btn {
    background: none; border: 1px solid #2e2e2e; color: #555;
    border-radius: 5px; padding: 4px 11px; font-size: 11px;
    cursor: pointer; flex-shrink: 0;
    transition: border-color .2s, color .2s;
    font-family: 'Barlow', sans-serif;
}
.map-clear-btn:hover { border-color: #ff4444; color: #ff4444; }

/* ── Payment options ── */
.pay-option {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 16px;
    border: 1px solid var(--border);
    border-radius: 8px; cursor: pointer;
    transition: all .25s ease;
    margin-bottom: 10px; user-select: none;
}
.pay-option:hover {
    border-color: rgba(220,0,0,.4);
    background: var(--red-subtle);
}
.pay-option input[type=radio] { accent-color: var(--ferrari-red); width: 16px; height: 16px; }
.pay-option.selected {
    border-color: var(--ferrari-red);
    background: var(--red-subtle);
}
.pay-icon { font-size: 22px; width: 32px; text-align: center; flex-shrink: 0; }
.pay-label { font-weight: 700; font-size: 14px; }
.pay-sub { color: var(--gray); font-size: 11px; margin-top: 1px; }

/* ── Order summary ── */
.order-summary-card {
    background: var(--dark2);
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
}
.sum-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 18px;
    border-bottom: 1px solid #111;
}
.sum-item-img {
    width: 56px; height: 40px;
    border-radius: 4px; overflow: hidden;
    background: var(--dark3); flex-shrink: 0;
}
.sum-item-img img { width: 100%; height: 100%; object-fit: cover; }
.sum-item-ph {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
}
.sum-total-row {
    display: flex; justify-content: space-between;
    padding: 16px 18px;
    font-weight: 700; font-size: 17px;
}

/* ── Submit button ── */
.btn-pay {
    width: 100%; padding: 15px;
    background: var(--ferrari-red);
    color: #fff; border: none; border-radius: 7px;
    font-weight: 700; font-size: 14px;
    letter-spacing: 2px; text-transform: uppercase;
    cursor: pointer; font-family: 'Barlow', sans-serif;
    margin-top: 20px;
    transition: background .25s, transform .2s, box-shadow .25s;
    position: relative; overflow: hidden;
}
.btn-pay::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,.1) 50%, transparent 70%);
    transform: translateX(-100%); transition: transform .45s ease;
}
.btn-pay:hover::after { transform: translateX(100%); }
.btn-pay:hover {
    background: #b00000;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(220,0,0,.3);
}
.btn-pay:disabled { pointer-events: none; opacity: .7; }

@media (max-width: 860px) {
    .checkout-grid { grid-template-columns: 1fr; }
    #deliveryMap { height: 220px; }
    .map-search-btn { padding: 9px 10px; font-size: 11px; letter-spacing: .5px; }
}
</style>
@endpush

@section('content')
<div class="container checkout-page">
    <p class="section-title">Check<span>out</span></p>
    <div class="section-divider"></div>

    <div class="checkout-grid">
        {{-- ─── Left: Form ─── --}}
        <div>
            <form method="POST" action="{{ route('cart.payment') }}" id="checkoutForm"
                  onsubmit="return prepareCheckout(this)">
                @csrf
                {{-- Hidden pin coordinates --}}
                <input type="hidden" name="delivery_latitude"  id="latInput"  value="{{ old('delivery_latitude') }}">
                <input type="hidden" name="delivery_longitude" id="lngInput"  value="{{ old('delivery_longitude') }}">

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
                    <textarea name="buyer_address" id="addressTextarea" class="form-control" rows="3"
                              placeholder="Type your address or drop a pin on the map below"
                              required>{{ old('buyer_address', auth()->user()->address) }}</textarea>
                    <div class="form-error">@error('buyer_address'){{ $message }}@enderror</div>
                </div>

                <div class="section-head" style="margin-top:16px">Payment Method</div>

                <label class="pay-option" id="opt-cod" onclick="selPay('cod')">
                    <input type="radio" name="payment_method" value="cod" checked onchange="selPay('cod')">
                    <span class="pay-icon" style="color:#f5c518"><i class="fas fa-money-bill-wave"></i></span>
                    <div>
                        <div class="pay-label">Cash on Delivery</div>
                        <div class="pay-sub">Pay when your Ferrari arrives</div>
                    </div>
                </label>

                <label class="pay-option" id="opt-credit_card" onclick="selPay('credit_card')">
                    <input type="radio" name="payment_method" value="credit_card" onchange="selPay('credit_card')">
                    <span class="pay-icon" style="color:#4488ff"><i class="fas fa-credit-card"></i></span>
                    <div>
                        <div class="pay-label">Credit / Debit Card</div>
                        <div class="pay-sub">Visa, Mastercard, AMEX accepted</div>
                    </div>
                </label>

                <label class="pay-option" id="opt-paypal" onclick="selPay('paypal')">
                    <input type="radio" name="payment_method" value="paypal" onchange="selPay('paypal')">
                    <span class="pay-icon" style="color:#0070ba"><i class="fab fa-paypal"></i></span>
                    <div>
                        <div class="pay-label">PayPal</div>
                        <div class="pay-sub">Fast, secure PayPal checkout</div>
                    </div>
                </label>

                <button type="submit" class="btn-pay" id="payBtn">
                    Place Order (Cash on Delivery)
                </button>
            </form>
        </div>

        {{-- ─── Right: Map + Summary ─── --}}
        <div>
            {{-- Map Pin Section --}}
            <div class="section-head">Delivery Pin</div>
            <div class="map-section" style="margin-bottom:20px">
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

            <div class="section-head">Order Summary</div>
            <div class="order-summary-card">
                @foreach($items as $item)
                <div class="sum-item">
                    <div class="sum-item-img">
                        @if($item->car->image && file_exists(storage_path('app/public/cars/'.$item->car->image)))
                            <img src="{{ asset('storage/cars/'.$item->car->image) }}" alt="{{ $item->car->name }}">
                        @else
                            <div class="sum-item-ph"><i class="fas fa-car" style="color:#2a2a2a;font-size:14px"></i></div>
                        @endif
                    </div>
                    <div style="flex:1">
                        <div style="font-family:'Bebas Neue',sans-serif;font-size:15px;letter-spacing:1px">{{ $item->car->name }}</div>
                        <div style="color:var(--gray);font-size:11px">Qty: {{ $item->quantity }}</div>
                    </div>
                    <div style="color:var(--ferrari-red);font-weight:700;font-size:14px">
                        ₱{{ number_format($item->car->price * $item->quantity, 2) }}
                    </div>
                </div>
                @endforeach
                <div class="sum-total-row">
                    <span>Total</span>
                    <span style="color:var(--ferrari-red)">₱{{ number_format($total, 2) }}</span>
                </div>
            </div>

            <div style="background:var(--dark2);border:1px solid var(--border);border-radius:8px;padding:16px;margin-top:16px">
                <div style="display:flex;align-items:center;gap:8px;color:#1db954;font-size:12px;font-weight:700;letter-spacing:1px;margin-bottom:6px">
                    <i class="fas fa-lock"></i> SECURE CHECKOUT
                </div>
                <div style="color:var(--gray);font-size:11px;line-height:1.7">
                    Your payment information is encrypted and secure. We never store your card details.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
/* ──────────────────────────────────────────────
   Map / Pin logic (matches create.blade.php)
────────────────────────────────────────────── */
(function () {
    var STORAGE_KEY  = 'delivery_pin_checkout';

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

    // Restore from sessionStorage if available
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
const ROUTES = {
    cod:         '{{ route('cart.process-payment') }}',
    credit_card: '{{ route('cart.payment') }}',
    paypal:      '{{ route('cart.payment') }}',
};
const BTN_LABELS = {
    cod:         'Place Order (Cash on Delivery)',
    credit_card: 'Continue to Card Payment →',
    paypal:      'Continue to PayPal →',
};

function selPay(val) {
    ['cod', 'credit_card', 'paypal'].forEach(function (v) {
        var el = document.getElementById('opt-' + v);
        if (el) el.classList.toggle('selected', v === val);
    });
    document.getElementById('checkoutForm').action = ROUTES[val];
    document.getElementById('payBtn').textContent  = BTN_LABELS[val] || 'Continue →';
}

function prepareCheckout(form) {
    var btn = document.getElementById('payBtn');
    btn.disabled    = true;
    btn.textContent = 'Processing…';
    return true;
}

selPay('cod');
</script>
@endpush