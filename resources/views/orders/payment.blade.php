@extends('layouts.app')
@section('title', 'Payment')
@push('styles')
<style>
.payment-page{padding:60px 0 80px;max-width:560px;margin:0 auto}
.pay-card{background:var(--dark2);border:1px solid rgba(220,0,0,0.15);border-radius:14px;padding:36px;box-shadow:0 20px 60px rgba(0,0,0,0.4);animation:slideUp .5s ease both}
@keyframes slideUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
.pay-header{text-align:center;margin-bottom:28px}
.pay-logo{font-size:36px;margin-bottom:8px}
.pay-title{font-family:'Bebas Neue',sans-serif;font-size:24px;letter-spacing:3px}
.pay-amount{font-size:32px;font-weight:700;color:var(--ferrari-red);margin:4px 0}
.pay-sub{color:var(--gray);font-size:12px}
.pay-car-name{font-size:13px;color:var(--gray);margin-top:4px;font-family:'Bebas Neue',sans-serif;letter-spacing:1px}
.card-field{margin-bottom:16px}
.card-field label{display:block;margin-bottom:5px;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--gray)}
.card-input{width:100%;padding:12px 14px;background:var(--dark3);border:1px solid #2a2a2a;border-radius:6px;color:var(--light);font-size:15px;font-family:'Barlow',sans-serif;transition:border-color .2s}
.card-input:focus{outline:none;border-color:var(--ferrari-red)}
.card-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.btn-pay-now{width:100%;padding:15px;background:var(--ferrari-red);color:#fff;border:none;border-radius:6px;font-weight:700;font-size:14px;letter-spacing:2px;text-transform:uppercase;cursor:pointer;font-family:'Barlow',sans-serif;margin-top:8px;transition:background .25s,transform .2s,box-shadow .25s;position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;gap:10px}
.btn-pay-now::after{content:'';position:absolute;inset:0;background:linear-gradient(120deg,transparent 30%,rgba(255,255,255,0.1) 50%,transparent 70%);transform:translateX(-100%);transition:transform .45s ease}
.btn-pay-now:hover::after{transform:translateX(100%)}
.btn-pay-now:hover{background:#b00000;transform:translateY(-2px);box-shadow:0 8px 24px rgba(220,0,0,0.3)}
.btn-pay-now:disabled{pointer-events:none;opacity:.7;transform:none}
.spinner{width:16px;height:16px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;display:none;flex-shrink:0}
.btn-pay-now.loading .spinner{display:block}
@keyframes spin{to{transform:rotate(360deg)}}
.secure-note{display:flex;align-items:center;justify-content:center;gap:6px;margin-top:14px;color:#444;font-size:11px;letter-spacing:1px;text-transform:uppercase}
.paypal-mock-box{background:var(--dark3);border:1px solid #1a3a5c;border-radius:10px;padding:24px;text-align:center;margin-bottom:20px}
.pp-email{font-size:13px;color:var(--gray);margin-bottom:10px}
.pp-amount{font-size:28px;font-weight:700;color:#0070ba}
.pp-note{font-size:11px;color:var(--gray);margin-top:6px}
.btn-paypal{width:100%;padding:15px;background:#0070ba;color:#fff;border:none;border-radius:6px;font-weight:700;font-size:15px;cursor:pointer;font-family:'Barlow',sans-serif;transition:background .25s,transform .2s,box-shadow .25s;display:flex;align-items:center;justify-content:center;gap:10px}
.btn-paypal:hover{background:#005a9e;transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,112,186,0.3)}
.btn-paypal:disabled{pointer-events:none;opacity:.7;transform:none}
</style>
@endpush

@section('content')
<div class="container payment-page">
<div class="pay-card">

    @if($method === 'credit_card')

    <div class="pay-header">
        <div class="pay-logo"><i class="fas fa-credit-card" style="color:#4488ff"></i></div>
        <div class="pay-title">Card Payment</div>
        <div class="pay-amount">₱{{ number_format($car->price, 2) }}</div>
        <div class="pay-car-name">{{ $car->name }}</div>
        <div class="pay-sub" style="margin-top:4px">Veloce Vantage Secure Checkout</div>
    </div>

    <form method="POST"
          action="{{ route('orders.pay') }}"
          id="cardForm"
          onsubmit="return handleSubmit('payNowBtn','payLabel')">
        @csrf
        <input type="hidden" name="payment_method" value="credit_card">

        <div class="card-field">
            <label>Cardholder Name</label>
            <input class="card-input" type="text" name="card_name"
                   placeholder="Juan Dela Cruz" required>
        </div>
        <div class="card-field">
            <label>Card Number</label>
            <input class="card-input" type="text" name="card_number"
                   placeholder="1234 5678 9012 3456"
                   maxlength="19" oninput="formatCard(this)" required>
        </div>
        <div class="card-row">
            <div class="card-field">
                <label>Expiry Date</label>
                <input class="card-input" type="text" name="card_expiry"
                       placeholder="MM / YY" maxlength="7"
                       oninput="formatExpiry(this)" required>
            </div>
            <div class="card-field">
                <label>CVV</label>
                <input class="card-input" type="password" name="card_cvv"
                       placeholder="•••" maxlength="4" required>
            </div>
        </div>

        <div style="display:flex;gap:12px;margin:16px 0;justify-content:center">
            <i class="fab fa-cc-visa"       style="font-size:32px;color:#1a1f71"></i>
            <i class="fab fa-cc-mastercard" style="font-size:32px;color:#eb001b"></i>
            <i class="fab fa-cc-amex"       style="font-size:32px;color:#2e77bc"></i>
        </div>

        <button type="submit" class="btn-pay-now" id="payNowBtn">
            <div class="spinner"></div>
            <span id="payLabel">
                <i class="fas fa-lock" style="margin-right:6px"></i>Pay ₱{{ number_format($car->price, 2) }}
            </span>
        </button>
    </form>

    @elseif($method === 'paypal')

    <div class="pay-header">
        <div class="pay-logo"><i class="fab fa-paypal" style="color:#0070ba"></i></div>
        <div class="pay-title">PayPal Checkout</div>
        <div class="pay-amount">₱{{ number_format($car->price, 2) }}</div>
        <div class="pay-car-name">{{ $car->name }}</div>
        <div class="pay-sub" style="margin-top:4px">Confirm your payment below</div>
    </div>

    <div class="paypal-mock-box">
        <div style="font-size:30px;margin-bottom:10px">
            <i class="fab fa-paypal" style="color:#0070ba"></i>
        </div>
        <div class="pp-email">
            Paying as <strong style="color:var(--light)">demo@paypal.com</strong>
        </div>
        <hr style="border:none;border-top:1px solid #1a3a5c;margin:12px 0">
        <div style="font-size:11px;color:var(--gray);margin-bottom:4px;text-transform:uppercase;letter-spacing:1px">Amount Due</div>
        <div class="pp-amount">₱{{ number_format($car->price, 2) }}</div>
        <div class="pp-note">{{ $car->name }} · Veloce Vantage · Simulated PayPal</div>
    </div>

    <form method="POST"
          action="{{ route('orders.pay') }}"
          id="ppForm"
          onsubmit="return handleSubmit('ppBtn','ppLabel',true)">
        @csrf
        <input type="hidden" name="payment_method" value="paypal">

        <button type="submit" class="btn-paypal" id="ppBtn">
            <div class="spinner" style="border-top-color:#fff"></div>
            <i class="fab fa-paypal" id="ppIcon"></i>
            <span id="ppLabel">Confirm &amp; Pay with PayPal</span>
        </button>
    </form>

    @endif

    <div class="secure-note">
        <i class="fas fa-lock"></i> Secured with 256-bit SSL encryption
    </div>
    <div style="text-align:center;margin-top:12px">
        <a href="{{ route('orders.create', session('single_checkout.car_id', '#')) }}"
           style="color:var(--gray);font-size:12px;transition:color .2s"
           onmouseover="this.style.color='var(--ferrari-red)'"
           onmouseout="this.style.color='var(--gray)'">
            ← Back
        </a>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
function handleSubmit(btnId, labelId, isPayPal = false) {
    const btn   = document.getElementById(btnId);
    const label = document.getElementById(labelId);
    btn.classList.add('loading');
    btn.disabled = true;
    if (label) label.textContent = 'Processing…';
    if (isPayPal) {
        const icon = document.getElementById('ppIcon');
        if (icon) icon.style.display = 'none';
    }

    // Clear saved pin after payment is confirmed
    try {
        var carId = '{{ session('single_checkout.car_id') }}';
        if (carId) sessionStorage.removeItem('delivery_pin_' + carId);
    } catch(e) {}

    return true;
}

function formatCard(input) {
    let v = input.value.replace(/\D/g, '').substring(0, 16);
    input.value = v.replace(/(.{4})/g, '$1 ').trim();
}

function formatExpiry(input) {
    let v = input.value.replace(/\D/g, '');
    if (v.length >= 2) v = v.substring(0, 2) + ' / ' + v.substring(2, 4);
    input.value = v;
}
</script>
@endpush