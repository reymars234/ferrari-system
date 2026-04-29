@extends('layouts.app')
@section('title', 'My Orders')
@push('styles')
<style>
    .orders-page { padding: 60px 0 80px; }

    /* ── Order card ── */
    .order-cards { display: flex; flex-direction: column; gap: 22px; margin-top: 32px; }

    .order-card {
        background: var(--dark2);
        border: 1px solid #1e1e1e;
        border-radius: 12px;
        overflow: hidden;
        display: grid;
        grid-template-columns: 220px 1fr auto;
        transition: border-color 0.4s ease, box-shadow 0.4s ease, transform 0.4s cubic-bezier(.25,.8,.25,1);
        animation: cardIn 0.5s ease both;
        position: relative;
    }
    .order-card:hover {
        border-color: rgba(220,0,0,0.35);
        box-shadow: 0 16px 48px rgba(220,0,0,0.1), 0 4px 16px rgba(0,0,0,0.4);
        transform: translateY(-3px);
    }
    @keyframes cardIn {
        from { opacity:0; transform:translateY(14px); }
        to   { opacity:1; transform:translateY(0); }
    }

    /* ── Car image — FULL cover with overlay ── */
    .oc-img {
        position: relative;
        overflow: hidden;
        background: var(--dark3);
        min-height: 140px;
    }
    .oc-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        transition: transform 0.6s cubic-bezier(.25,.8,.25,1), filter 0.6s ease;
        filter: brightness(0.88) saturate(1.1);
    }
    .order-card:hover .oc-img img {
        transform: scale(1.08);
        filter: brightness(1) saturate(1.25);
    }
    /* Dark gradient overlay on the image */
    .oc-img::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to right,
            rgba(0,0,0,0.18) 0%,
            rgba(0,0,0,0.0) 70%,
            rgba(0,0,0,0.55) 100%
        );
        pointer-events: none;
        transition: opacity 0.4s ease;
    }
    .order-card:hover .oc-img::after {
        opacity: 0.6;
    }
    /* Red accent bar on left edge */
    .oc-img::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--ferrari-red, #dc0000);
        z-index: 2;
        transform: scaleY(0);
        transform-origin: bottom;
        transition: transform 0.4s cubic-bezier(.25,.8,.25,1);
    }
    .order-card:hover .oc-img::before {
        transform: scaleY(1);
    }
    .oc-img-ph {
        width: 100%; height: 100%; min-height: 140px;
        display: flex; align-items: center; justify-content: center;
    }
    .oc-img-ph i { font-size: 36px; color: #2a2a2a; }

    /* ── Body ── */
    .oc-body { padding: 20px 22px; display: flex; flex-direction: column; justify-content: space-between; }
    .oc-name {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 22px;
        letter-spacing: 2.5px;
        margin-bottom: 6px;
        transition: color 0.25s ease;
        line-height: 1.1;
    }
    .order-card:hover .oc-name { color: var(--ferrari-red); }
    .oc-meta {
        color: var(--gray);
        font-size: 12px;
        margin-bottom: 12px;
        line-height: 1.8;
        flex: 1;
    }
    .oc-price {
        color: var(--ferrari-red);
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 14px;
        letter-spacing: 0.5px;
    }
    .oc-actions { display: flex; gap: 8px; flex-wrap: wrap; }

    /* Payment badge */
    .pay-badge {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
        padding: 2px 8px; border-radius: 3px;
    }
    .pay-paid   { background: rgba(29,185,84,.1);  border: 1px solid rgba(29,185,84,.3);  color: #1db954; }
    .pay-unpaid { background: rgba(245,197,24,.08); border: 1px solid rgba(245,197,24,.3); color: #f5c518; }
    .pay-failed { background: rgba(255,68,68,.08);  border: 1px solid rgba(255,68,68,.3);  color: #ff4444; }

    /* ── Right panel ── */
    .oc-right {
        padding: 20px 22px;
        display: flex; flex-direction: column;
        align-items: flex-end; justify-content: space-between;
        border-left: 1px solid #1a1a1a;
        min-width: 170px;
        background: rgba(0,0,0,0.12);
        transition: background 0.4s ease;
    }
    .order-card:hover .oc-right {
        background: rgba(220,0,0,0.03);
    }
    .oc-date { color: var(--gray); font-size: 11px; text-align: right; }

    /* Status tracker */
    .status-track { display: flex; flex-direction: column; gap: 5px; align-items: flex-end; }
    .status-step {
        font-size: 10px; letter-spacing: 1px; text-transform: uppercase;
        color: #333; display: flex; align-items: center; gap: 5px;
        transition: color 0.2s ease;
    }
    .status-step.done   { color: #1db954; }
    .status-step.active { color: var(--ferrari-red); font-weight: 700; }
    .status-step.cancel { color: #ff4444; }

    /* ── Buttons ── */
    .btn {
        display: inline-flex; align-items: center; gap: 6px;
        transition: background 0.25s, transform 0.2s, box-shadow 0.25s, color 0.2s;
        position: relative; overflow: hidden;
    }
    .btn::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(120deg,transparent 30%,rgba(255,255,255,0.1) 50%,transparent 70%);
        transform: translateX(-100%); transition: transform 0.45s ease;
    }
    .btn:hover::after { transform: translateX(100%); }
    .btn:hover  { transform: translateY(-2px); }
    .btn:active { transform: translateY(0); }
    .btn-red:hover     { box-shadow: 0 6px 18px rgba(220,0,0,0.3); }
    .btn-outline:hover { box-shadow: 0 6px 18px rgba(220,0,0,0.2); }



    /* ── Responsive ── */
    @media(max-width:700px) {
        .order-card { grid-template-columns: 110px 1fr; }
        .oc-right   { grid-column: 1/-1; flex-direction: row; border-left: none; border-top: 1px solid #1a1a1a; min-width: unset; }
        .oc-img     { min-height: 110px; }
    }
    @media(max-width:480px) {
        .order-card { grid-template-columns: 1fr; }
        .oc-img     { min-height: 180px; max-height: 200px; }
        .oc-right   { grid-column: 1; }
    }
</style>
@endpush

@section('content')


<div class="container orders-page">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:8px">
        <div>
            <p class="section-title">My <span>Orders</span></p>
            <div class="section-divider"></div>
        </div>
        <div style="display:flex;gap:10px;align-items:center">
            <a href="{{ route('cart.index') }}" class="btn btn-outline" style="padding:9px 18px;font-size:12px">
                <i class="fas fa-shopping-cart"></i> Cart
                @php $cartCount = auth()->user()->cartItems()->count(); @endphp
                @if($cartCount > 0)
                    <span style="background:var(--ferrari-red);color:#fff;border-radius:50%;width:16px;height:16px;font-size:9px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;margin-left:2px">{{ $cartCount }}</span>
                @endif
            </a>
            <a href="{{ route('shop') }}" class="btn btn-gray" style="padding:9px 18px;font-size:12px">
                <i class="fas fa-car"></i> Shop
            </a>

        </div>
    </div>

    {{-- Order list --}}
    <div class="order-cards">
        @forelse($orders as $order)
        <div class="order-card" style="animation-delay:{{ $loop->index * 0.07 }}s">

            {{-- Car image — full cover --}}
            <div class="oc-img">
                @if($order->car->image && file_exists(storage_path('app/public/cars/'.$order->car->image)))
                    <img src="{{ asset('storage/cars/'.$order->car->image) }}"
                         alt="{{ $order->car->name }}"
                         loading="lazy">
                @else
                    <div class="oc-img-ph"><i class="fas fa-car"></i></div>
                @endif
            </div>

            {{-- Body --}}
            <div class="oc-body">
                <div>
                    <div class="oc-name">{{ $order->car->name }}</div>
                    <div class="oc-meta">
                        Order <strong style="color:var(--light)">#{{ $order->id }}</strong>
                        <br>
                        @if($order->admin_accepted)
                            <span style="color:#1db954;font-size:11px">
                                <i class="fas fa-check-circle"></i> Confirmed by admin
                            </span>
                        @else
                            <span style="color:#555;font-size:11px">
                                <i class="fas fa-clock"></i> Awaiting admin confirmation
                            </span>
                        @endif
                        <br>
                        @if($order->driver)
                            Driver: <strong style="color:var(--light)">{{ $order->driver->name }}</strong>
                            ({{ $order->driver->contact_number }})
                        @else
                            <span style="color:#444">No driver assigned yet</span>
                        @endif
                        <br>
                        Payment:
                        <strong style="color:var(--light)">
                            {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}
                        </strong>
                        <span class="pay-badge pay-{{ $order->payment_status }}">
                            {{ strtoupper($order->payment_status) }}
                        </span>
                        @if($order->payment_reference)
                            <span style="color:#444;font-size:11px">&nbsp;· Ref: {{ $order->payment_reference }}</span>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="oc-price">₱{{ number_format($order->total_price, 2) }}</div>
                    <div class="oc-actions">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                        @if($order->driver_id)
                            <a href="{{ route('chat.show', $order) }}" class="btn btn-red btn-sm">
                                <i class="fas fa-comment"></i> Chat Driver
                            </a>
                        @endif
                        @if($order->isCancellable())
                            <form method="POST" action="{{ route('orders.cancel', $order) }}" style="margin:0">
                                @csrf
                                <button type="submit"
                                    class="btn btn-sm"
                                    style="background:#330000;color:#ff4444;border:1px solid rgba(255,68,68,0.35);font-family:'Barlow',sans-serif"
                                    onclick="return confirm('Cancel Order #{{ $order->id }}?')">
                                    Cancel
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right: status tracker + date --}}
            <div class="oc-right">
                <div class="status-track">
                    @if($order->status === 'cancelled')
                        <div class="status-step cancel">
                            <i class="fas fa-times-circle" style="font-size:9px"></i> Cancelled
                        </div>
                    @else
                        @php
                            $steps   = ['pending' => 'Pending', 'processing' => 'Processing', 'delivered' => 'Delivered'];
                            $reached = false;
                        @endphp
                        @foreach($steps as $key => $label)
                            @php
                                $isActive = $order->status === $key;
                                $isDone   = !$reached && !$isActive && in_array($order->status, array_slice(array_keys($steps), array_search($key, array_keys($steps)) + 1));
                            @endphp
                            <div class="status-step {{ $isActive ? 'active' : ($isDone ? 'done' : '') }}">
                                <i class="fas {{ $isActive ? 'fa-dot-circle' : ($isDone ? 'fa-check-circle' : 'fa-circle') }}"
                                   style="font-size:9px"></i>
                                {{ $label }}
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="oc-date">{{ $order->created_at->format('M d, Y') }}</div>
            </div>

        </div>
        @empty
        <div style="text-align:center;padding:70px 0;color:var(--gray)">
            <i class="fas fa-shopping-bag" style="font-size:52px;color:#222;display:block;margin-bottom:16px"></i>
            <p style="font-size:16px;margin-bottom:8px">No orders yet.</p>
            <p style="font-size:13px;margin-bottom:24px">Browse our Ferrari collection to place your first order.</p>
            <a href="{{ route('shop') }}" class="btn btn-red">Shop Now</a>
        </div>
        @endforelse
    </div>

    <div style="margin-top:24px">{{ $orders->links() }}</div>
</div>

@endsection

@push('scripts')
<script>
</script>
@endpush