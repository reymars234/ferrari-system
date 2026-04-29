@extends('layouts.admin')
@section('title', 'Order #' . $order->id)
@section('page-title', 'Order #' . $order->id)
@section('content')

{{-- ══════════════════════════════════════════════════════
     PASSWORD CONFIRMATION MODAL
══════════════════════════════════════════════════════ --}}
<div id="pwOverlay" style="
    display:none; position:fixed; inset:0; z-index:10000;
    background:rgba(0,0,0,.82); backdrop-filter:blur(6px);
    align-items:center; justify-content:center; padding:16px;">

    <div id="pwModal" style="
        background:#111; border:1px solid rgba(220,0,0,.25);
        border-radius:14px; padding:28px 24px; width:100%; max-width:400px;
        box-shadow:0 32px 80px rgba(0,0,0,.65);
        animation:modalIn .25s cubic-bezier(.25,.8,.25,1) both;">

        <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
            <div style="width:42px;height:42px;border-radius:50%;flex-shrink:0;
                        display:flex;align-items:center;justify-content:center;
                        background:rgba(220,0,0,.1);border:1px solid rgba(220,0,0,.3)">
                <i id="pwModalIcon" class="fas fa-check" style="color:var(--ferrari-red);font-size:16px"></i>
            </div>
            <div>
                <div id="pwModalTitle" style="font-family:'Bebas Neue',sans-serif;font-size:18px;letter-spacing:2px">Confirm Action</div>
                <div style="color:var(--gray);font-size:11px;margin-top:1px">Admin password required to proceed</div>
            </div>
        </div>

        <p id="pwModalDesc" style="color:#aaa;font-size:12px;line-height:1.6;margin:12px 0 16px">
            Enter your admin password to confirm this action.
        </p>

        <div style="margin-bottom:16px">
            <label style="font-size:10px;font-weight:700;letter-spacing:2px;
                          text-transform:uppercase;color:var(--gray);display:block;margin-bottom:6px">
                Your Admin Password
            </label>
            <div style="position:relative">
                <input type="password" id="pwInput"
                    placeholder="Enter your password to confirm"
                    style="width:100%;box-sizing:border-box;padding:10px 40px 10px 14px;
                           background:#1a1a1a;border:1px solid #2a2a2a;border-radius:6px;
                           color:#fff;font-size:13px;outline:none;transition:border-color .2s">
                <span id="pwEyeToggle" style="
                    position:absolute;right:12px;top:50%;transform:translateY(-50%);
                    color:#555;cursor:pointer;font-size:13px;transition:color .2s"
                    onmouseover="this.style.color='var(--ferrari-red)'"
                    onmouseout="this.style.color='#555'">
                    <i id="pwEye" class="fas fa-eye"></i>
                </span>
            </div>
            <div id="pwError" style="color:var(--ferrari-red);font-size:11px;margin-top:6px;display:none;align-items:center;gap:6px">
                <i class="fas fa-exclamation-circle"></i>
                <span id="pwErrorMsg">Incorrect password.</span>
            </div>
        </div>

        <div style="display:flex;gap:10px">
            <button id="pwOkBtn" style="
                flex:1;padding:11px;border:none;border-radius:6px;cursor:pointer;
                font-family:'Barlow',sans-serif;font-weight:700;font-size:12px;
                letter-spacing:2px;text-transform:uppercase;
                background:var(--ferrari-red);color:#fff;
                transition:background .2s,transform .15s;
                display:flex;align-items:center;justify-content:center;gap:8px"
                onmouseover="this.style.background='#b00000';this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='var(--ferrari-red)';this.style.transform=''">
                <i id="pwOkIcon" class="fas fa-check"></i>
                <span id="pwOkText">Confirm</span>
                <div id="pwSpinner" style="display:none;width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite"></div>
            </button>
            <button id="pwCancelBtn" style="
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

<style>
@keyframes modalIn {
    from { opacity:0; transform:translateY(16px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
@keyframes spin { to { transform:rotate(360deg); } }
#pwInput:focus { border-color:rgba(220,0,0,.5) !important; }
</style>

{{-- ══════════════════════════════════════════════════════
     BACK TO ORDERS CONFIRM MODAL
══════════════════════════════════════════════════════ --}}
<div id="backOverlay" style="
    display:none; position:fixed; inset:0; z-index:10000;
    background:rgba(0,0,0,.78); backdrop-filter:blur(6px);
    align-items:center; justify-content:center; padding:16px;">

    <div id="backModal" style="
        background:#111; border:1px solid rgba(255,255,255,.07);
        border-radius:14px; padding:28px 24px; width:100%; max-width:360px;
        box-shadow:0 32px 80px rgba(0,0,0,.65);
        animation:modalIn .25s cubic-bezier(.25,.8,.25,1) both;
        text-align:center;">

        <div style="width:52px;height:52px;border-radius:50%;margin:0 auto 16px;
                    display:flex;align-items:center;justify-content:center;
                    background:rgba(255,255,255,.04);border:1px solid #2a2a2a">
            <i class="fas fa-arrow-left" style="color:#aaa;font-size:18px"></i>
        </div>
        <div style="font-family:'Bebas Neue',sans-serif;font-size:20px;letter-spacing:2px;margin-bottom:8px">
            Leave this page?
        </div>
        <p style="color:var(--gray);font-size:12px;line-height:1.8;margin-bottom:22px">
            You're about to go back to the Orders list.<br>
            Any unsaved changes will be <strong style="color:#fff">lost</strong>.
        </p>
        <div style="display:flex;gap:10px">
            <button id="backStayBtn" style="
                flex:1;padding:11px;border:1px solid #2a2a2a;border-radius:6px;cursor:pointer;
                font-family:'Barlow',sans-serif;font-weight:700;font-size:12px;
                letter-spacing:2px;text-transform:uppercase;
                background:transparent;color:var(--gray);transition:border-color .2s,color .2s"
                onmouseover="this.style.borderColor='#555';this.style.color='#fff'"
                onmouseout="this.style.borderColor='#2a2a2a';this.style.color='var(--gray)'">
                <i class="fas fa-times" style="margin-right:6px"></i>Stay
            </button>
            <a href="{{ route('admin.orders.index') }}" style="
                flex:1;padding:11px;border:none;border-radius:6px;
                font-family:'Barlow',sans-serif;font-weight:700;font-size:12px;
                letter-spacing:2px;text-transform:uppercase;
                background:#222;color:#ccc;border:1px solid #333;
                text-decoration:none;display:flex;align-items:center;justify-content:center;gap:8px;
                transition:background .2s,color .2s,border-color .2s"
                onmouseover="this.style.background='#2a2a2a';this.style.color='#fff';this.style.borderColor='#444'"
                onmouseout="this.style.background='#222';this.style.color='#ccc';this.style.borderColor='#333'">
                <i class="fas fa-arrow-left"></i> Yes, Go Back
            </a>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     HIDDEN FORMS
══════════════════════════════════════════════════════ --}}
<form id="acceptForm" method="POST" action="{{ route('admin.orders.accept', $order) }}" style="display:none">
    @csrf
</form>
<form id="statusForm" method="POST" action="{{ route('admin.orders.status', $order) }}" style="display:none">
    @csrf @method('PATCH')
    <input type="hidden" name="status"             id="hiddenStatus">
    <input type="hidden" name="estimated_delivery" id="hiddenDelivery">
    <input type="hidden" name="delivery_notes"     id="hiddenNotes">
</form>
<form id="driverForm" method="POST" action="{{ route('admin.orders.assign-driver', $order) }}" style="display:none">
    @csrf
    <input type="hidden" name="driver_id" id="hiddenDriverId">
</form>

{{-- ── DELIVERED BANNER ── --}}
@if($order->status === 'delivered')
<div style="background:rgba(29,185,84,.08);border:1px solid rgba(29,185,84,.3);border-radius:10px;
            padding:16px 20px;margin-bottom:22px;display:flex;align-items:center;gap:14px">
    <i class="fas fa-check-circle" style="color:#1db954;font-size:24px;flex-shrink:0"></i>
    <div>
        <div style="color:#1db954;font-weight:700;font-size:14px;letter-spacing:1px;text-transform:uppercase">Order Delivered</div>
        <div style="color:var(--gray);font-size:12px;margin-top:3px;line-height:1.6">
            This order has been successfully delivered. All actions are now locked.
        </div>
    </div>
</div>
@endif

{{-- ── CANCELLED BANNER ── --}}
@if($order->status === 'cancelled')
<div style="background:rgba(255,68,68,.08);border:1px solid rgba(255,68,68,.3);border-radius:10px;
            padding:16px 20px;margin-bottom:22px;display:flex;align-items:center;gap:14px">
    <i class="fas fa-ban" style="color:#ff4444;font-size:24px;flex-shrink:0"></i>
    <div>
        <div style="color:#ff4444;font-weight:700;font-size:14px;letter-spacing:1px;text-transform:uppercase">Order Cancelled</div>
        <div style="color:var(--gray);font-size:12px;margin-top:3px;line-height:1.6">
            This order was cancelled by the customer. No further actions can be taken.
            @if($order->cancel_reason)
                <br><strong style="color:#ccc">Reason:</strong> {{ $order->cancel_reason }}
            @endif
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════
     MAIN GRID
══════════════════════════════════════════════════════ --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:22px;margin-bottom:22px">

    {{-- ── LEFT — ORDER INFO ── --}}
    <div class="card card-body">
        <h3 style="font-family:'Bebas Neue',sans-serif;font-size:18px;letter-spacing:2px;margin-bottom:14px;color:var(--ferrari-red)">
            Order Details
        </h3>

        @if($order->car->image && file_exists(storage_path('app/public/cars/'.$order->car->image)))
            <img src="{{ asset('storage/cars/'.$order->car->image) }}"
                 style="width:100%;height:150px;object-fit:cover;border-radius:6px;border:1px solid #222;margin-bottom:14px"
                 alt="{{ $order->car->name }}">
        @endif

        <table style="border:none;width:100%">
            @foreach([
                'Car'     => $order->car->name,
                'Buyer'   => $order->buyer_name,
                'Contact' => $order->buyer_contact,
                'Address' => $order->buyer_address,
                'Amount'  => '₱' . number_format($order->total_price, 2),
                'Payment' => strtoupper(str_replace('_',' ',$order->payment_method)) . ' — ' . strtoupper($order->payment_status),
                'Ref #'   => $order->payment_reference ?? '—',
                'Placed'  => $order->created_at->format('M d, Y H:i'),
            ] as $key => $value)
            <tr>
                <td style="color:var(--gray);font-size:10px;letter-spacing:1px;text-transform:uppercase;
                            border:none;padding:6px 0;width:70px;vertical-align:top">{{ $key }}</td>
                <td style="border:none;padding:6px 0;font-size:13px;line-height:1.5">{{ $value }}</td>
            </tr>
            @endforeach
        </table>

        @if($order->payment_method === 'cod')
        <div style="margin-top:12px;padding:10px 14px;border-radius:6px;
            {{ $order->cod_paid
               ? 'background:rgba(29,185,84,.06);border:1px solid rgba(29,185,84,.2)'
               : 'background:rgba(245,197,24,.04);border:1px solid rgba(245,197,24,.2)' }}">
            @if($order->cod_paid)
                <div style="color:#1db954;font-size:12px;font-weight:700">
                    <i class="fas fa-check-circle"></i> CASH RECEIVED BY DRIVER
                </div>
                <div style="color:var(--gray);font-size:11px;margin-top:3px">
                    {{ $order->cod_paid_at?->format('M d, Y H:i') }}
                    @if($order->codConfirmedBy) · Confirmed by {{ $order->codConfirmedBy->name }} @endif
                </div>
            @else
                <div style="color:#f5c518;font-size:12px;font-weight:700">
                    <i class="fas fa-clock"></i> AWAITING CASH PAYMENT FROM CUSTOMER
                </div>
            @endif
        </div>
        @endif

        @if($order->refund_status === 'processed')
        <div style="margin-top:12px;padding:10px 14px;background:rgba(58,142,246,.06);border:1px solid rgba(58,142,246,.2);border-radius:6px">
            <div style="color:#3a8ef6;font-size:12px;font-weight:700">
                <i class="fas fa-undo"></i> PAYPAL REFUND PROCESSED
            </div>
            <div style="color:var(--gray);font-size:11px;margin-top:3px">
                Ref: {{ $order->refund_reference }}
                @if($order->refunded_at) · {{ $order->refunded_at->format('M d, Y H:i') }} @endif
            </div>
        </div>
        @endif

        @if($order->driver)
        <div style="margin-top:14px;padding:12px;background:rgba(29,185,84,.06);border:1px solid rgba(29,185,84,.2);border-radius:6px">
            <div style="font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:4px">Assigned Driver</div>
            <div style="font-weight:700;margin-bottom:2px">{{ $order->driver->name }}</div>
            <div style="color:var(--gray);font-size:12px">
                {{ $order->driver->contact_number }}
                @if($order->driver->vehicle_info) &nbsp;·&nbsp; {{ $order->driver->vehicle_info }} @endif
            </div>
        </div>
        @endif
    </div>

    {{-- ── RIGHT COLUMN — ACTIONS ── --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        @if($order->status === 'cancelled' || $order->status === 'delivered')
        <div style="background:{{ $order->status === 'delivered' ? 'rgba(29,185,84,.05)' : 'rgba(255,68,68,.05)' }};
                    border:1px solid {{ $order->status === 'delivered' ? 'rgba(29,185,84,.2)' : 'rgba(255,68,68,.2)' }};
                    border-radius:10px;padding:28px 20px;text-align:center">
            <i class="fas fa-lock" style="color:{{ $order->status === 'delivered' ? '#1db954' : '#ff4444' }};
                font-size:32px;display:block;margin-bottom:12px;opacity:.7"></i>
            <div style="font-family:'Bebas Neue',sans-serif;font-size:17px;letter-spacing:2px;
                        color:{{ $order->status === 'delivered' ? '#1db954' : '#ff4444' }};margin-bottom:8px">
                Actions Locked
            </div>
            <div style="color:var(--gray);font-size:12px;line-height:1.8">
                @if($order->status === 'delivered')
                    This order has been successfully delivered.<br>No further changes can be made.
                @else
                    This order has been cancelled by the customer.<br>
                    Accepting, updating status, and assigning a<br>driver are no longer available.
                @endif
            </div>
            <button id="backBtn1" class="btn btn-gray btn-sm" style="margin-top:18px">← Back to Orders</button>
        </div>

        @else

        @if(!$order->admin_accepted)
        <div class="card card-body">
            <h3 style="font-family:'Bebas Neue',sans-serif;font-size:16px;letter-spacing:2px;margin-bottom:10px">Accept Order</h3>
            <p style="color:var(--gray);font-size:12px;margin-bottom:14px;line-height:1.7">
                Waiting for your confirmation. Accept to begin processing and notify the customer.
            </p>
            <button type="button" id="acceptOrderBtn" class="btn btn-red" style="width:100%">
                <i class="fas fa-check"></i> &nbsp;Accept Order
            </button>
        </div>
        @else
        <div style="background:rgba(29,185,84,.06);border:1px solid rgba(29,185,84,.2);
                    border-radius:8px;padding:14px;display:flex;align-items:center;gap:12px">
            <i class="fas fa-check-circle" style="color:#1db954;font-size:22px;flex-shrink:0"></i>
            <div>
                <div style="font-weight:700;font-size:13px;color:#1db954">Order Accepted</div>
                <div style="color:var(--gray);font-size:11px">
                    {{ $order->admin_accepted_at?->format('M d, Y H:i') ?? 'Already accepted' }}
                </div>
            </div>
        </div>
        @endif

        <div class="card card-body">
            <h3 style="font-family:'Bebas Neue',sans-serif;font-size:16px;letter-spacing:2px;margin-bottom:14px">Update Status</h3>
            <div class="form-group">
                <label>Status</label>
                <select id="statusSelect" class="form-control">
                    @foreach(['pending','processing','delivered','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Estimated Delivery Date</label>
                <input type="date" id="deliveryDate" class="form-control"
                    value="{{ $order->estimated_delivery?->format('Y-m-d') }}">
            </div>
            <div class="form-group">
                <label>Delivery Notes</label>
                <textarea id="deliveryNotes" class="form-control" rows="2">{{ $order->delivery_notes }}</textarea>
            </div>
            <div style="display:flex;gap:10px">
                <button type="button" id="updateStatusBtn" class="btn btn-red">Update Status</button>
                <button type="button" id="backBtn2" class="btn btn-gray">← Back</button>
            </div>
        </div>

        <div class="card card-body">
            <h3 style="font-family:'Bebas Neue',sans-serif;font-size:16px;letter-spacing:2px;margin-bottom:14px">Assign Driver</h3>
            @if($drivers->isEmpty())
                <p style="color:var(--gray);font-size:13px">
                    No active drivers available.
                    <a href="{{ route('admin.drivers.create') }}" style="color:var(--ferrari-red)">Create one.</a>
                </p>
            @else
            <div class="form-group">
                <label>Select Driver</label>
                <select id="driverSelect" class="form-control">
                    <option value="">— Select a driver —</option>
                    @foreach($drivers as $d)
                        <option value="{{ $d->id }}" {{ $order->driver_id === $d->id ? 'selected' : '' }}>
                            {{ $d->name }}@if($d->vehicle_info) — {{ $d->vehicle_info }}@endif ({{ strtoupper($d->driver_status) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="button" id="assignDriverBtn" class="btn btn-red">
                <i class="fas fa-truck"></i> &nbsp;Assign Driver
            </button>
            @endif
        </div>

        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     CHAT WITH DRIVER
══════════════════════════════════════════════════════ --}}
@if($order->driver_id)
@php $chatLocked = in_array($order->status, ['delivered', 'cancelled']); @endphp
<div class="card" id="driverChatCard" style="margin-bottom:22px">
    <div class="card-header">
        <h3>
            <i class="fas fa-truck" style="color:var(--ferrari-red);margin-right:8px"></i>
            Chat with Driver — {{ $order->driver->name }}
        </h3>
        @if(!$chatLocked)
        <a href="{{ route('admin.chat.show', $order) }}" class="btn btn-red btn-sm" target="_blank">
            <i class="fas fa-external-link-alt"></i> Open Full Chat
        </a>
        @else
        <span style="font-size:11px;color:var(--gray);display:flex;align-items:center;gap:6px">
            <i class="fas fa-lock" style="font-size:10px"></i> Read-only
        </span>
        @endif
    </div>

    <div style="position:relative" id="chatBoxWrap">
        <div id="adminChatBox"
             style="height:280px;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:10px;
                    scrollbar-width:thin;scrollbar-color:rgba(220,0,0,.15) transparent;
                    {{ $chatLocked ? 'filter:blur(4px);pointer-events:none;user-select:none' : '' }}"
             aria-hidden="{{ $chatLocked ? 'true' : 'false' }}">
            @forelse($driverMessages as $msg)
            <div style="display:flex;align-items:flex-end;gap:8px;
                        {{ $msg->sender_id === auth()->id() ? 'flex-direction:row-reverse' : '' }}"
                 data-aid="{{ $msg->id }}">
                <div style="max-width:68%;padding:9px 13px;border-radius:16px;font-size:13px;line-height:1.5;
                    {{ $msg->sender_id === auth()->id()
                       ? 'background:var(--ferrari-red);color:#fff;border-bottom-right-radius:3px'
                       : 'background:var(--dark3);border:1px solid #2a2a2a;border-bottom-left-radius:3px' }}">
                    <div style="font-size:10px;margin-bottom:3px;
                        {{ $msg->sender_id === auth()->id() ? 'color:rgba(255,255,255,.65)' : 'color:var(--gray)' }}">
                        {{ $msg->sender->name }}
                    </div>
                    {{ $msg->body }}
                </div>
                <div style="font-size:10px;color:#444;flex-shrink:0">{{ $msg->created_at->format('h:i A') }}</div>
            </div>
            @empty
            <div style="text-align:center;color:var(--gray);padding:30px;margin:auto">
                <i class="fas fa-comments" style="font-size:28px;color:#222;display:block;margin-bottom:10px"></i>
                No messages in this conversation.
            </div>
            @endforelse
        </div>

        {{-- Order-level lock overlay (delivered / cancelled) --}}
        @if($chatLocked)
        <div id="chatBlurOverlay" style="
            position:absolute;inset:0;background:rgba(10,10,10,.55);backdrop-filter:blur(2px);
            display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;
            transition:opacity .35s">
            <div style="text-align:center">
                <div style="width:52px;height:52px;border-radius:50%;margin:0 auto 10px;
                            display:flex;align-items:center;justify-content:center;
                            background:rgba(220,0,0,.1);border:1px solid rgba(220,0,0,.3)">
                    <i class="fas fa-lock" style="color:var(--ferrari-red);font-size:18px"></i>
                </div>
                <div style="font-family:'Bebas Neue',sans-serif;font-size:16px;letter-spacing:2px;color:#fff;margin-bottom:4px">
                    Conversation Locked
                </div>
                <div style="color:var(--gray);font-size:11px;line-height:1.7">
                    This order is {{ ucfirst($order->status) }}.<br>
                    Messaging is disabled. Verify to view the conversation.
                </div>
            </div>
            <button id="viewChatBtn" style="
                padding:10px 22px;border:none;border-radius:24px;cursor:pointer;
                font-family:'Barlow',sans-serif;font-weight:700;font-size:12px;
                letter-spacing:2px;text-transform:uppercase;background:var(--ferrari-red);color:#fff;
                display:flex;align-items:center;gap:8px;transition:background .2s,transform .15s;
                box-shadow:0 4px 20px rgba(220,0,0,.35)"
                onmouseover="this.style.background='#b00000';this.style.transform='translateY(-2px)'"
                onmouseout="this.style.background='var(--ferrari-red)';this.style.transform=''">
                <i class="fas fa-eye"></i> See This Conversation
            </button>
        </div>
        @endif
    </div>

    @if(!$chatLocked)
    <div style="padding:12px 16px;border-top:1px solid #1e1e1e;display:flex;gap:10px;align-items:center">
        <input type="text" id="adminMsgInput"
            placeholder="Type a message to driver…"
            style="flex:1;background:var(--dark3);border:1px solid #2a2a2a;border-radius:24px;
                   padding:9px 16px;color:var(--light);font-size:13px;font-family:'Barlow',sans-serif;
                   outline:none;transition:border-color .2s"
            onfocus="this.style.borderColor='var(--ferrari-red)'"
            onblur="this.style.borderColor='#2a2a2a'">
        <button id="chatSendBtn" style="
            width:40px;height:40px;border-radius:50%;background:var(--ferrari-red);border:none;
            color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;
            flex-shrink:0;transition:background .2s,transform .2s"
            onmouseover="this.style.background='#b00000';this.style.transform='scale(1.08)'"
            onmouseout="this.style.background='var(--ferrari-red)';this.style.transform='scale(1)'">
            <i class="fas fa-paper-plane" style="font-size:13px"></i>
        </button>
    </div>
    @else
    <div style="padding:12px 16px;border-top:1px solid #1e1e1e;display:flex;align-items:center;
                justify-content:center;gap:8px;color:#333">
        <i class="fas fa-ban" style="font-size:12px"></i>
        <span style="font-size:11px;letter-spacing:1px;text-transform:uppercase;font-weight:700">
            Messaging disabled — Order {{ ucfirst($order->status) }}
        </span>
    </div>
    @endif
</div>
@endif

{{-- ══════════════════════════════════════════════════════
     JAVASCRIPT — DOMContentLoaded, no onclick attributes
══════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    var VERIFY_URL = '{{ route('admin.verify-password') }}';
    var CSRF       = '{{ csrf_token() }}';
    var el;

    /* ════════════════════════════════════
       BACK CONFIRM
    ════════════════════════════════════ */
    function openBackConfirm() {
        var overlay = document.getElementById('backOverlay');
        var modal   = document.getElementById('backModal');
        overlay.style.display = 'flex';
        modal.style.animation = 'none';
        void modal.offsetWidth;
        modal.style.animation = 'modalIn .25s cubic-bezier(.25,.8,.25,1) both';
    }
    function closeBackConfirm() {
        document.getElementById('backOverlay').style.display = 'none';
    }

    el = document.getElementById('backBtn1');    if (el) el.addEventListener('click', openBackConfirm);
    el = document.getElementById('backBtn2');    if (el) el.addEventListener('click', openBackConfirm);
    el = document.getElementById('backStayBtn'); if (el) el.addEventListener('click', closeBackConfirm);
    el = document.getElementById('backOverlay');
    if (el) el.addEventListener('click', function (e) { if (e.target === this) closeBackConfirm(); });

    /* ════════════════════════════════════
       PASSWORD MODAL
    ════════════════════════════════════ */
    var pendingAction   = null;
    var pendingFormData = {};

    var modalConfig = {
        accept     : { icon:'fa-check',      title:'Accept Order',      desc:'You are about to accept this order.<br>Enter your admin password to confirm.',              okText:'Accept Order',      okIcon:'fa-check'      },
        status     : { icon:'fa-sync-alt',   title:'Update Status',     desc:'You are about to update this order status.<br>Enter your admin password to confirm.',      okText:'Update Status',     okIcon:'fa-sync-alt'   },
        driver     : { icon:'fa-truck',      title:'Assign Driver',     desc:'You are about to assign a driver to this order.<br>Enter your admin password to confirm.', okText:'Assign Driver',     okIcon:'fa-truck'      },
        chat       : { icon:'fa-comments',   title:'View Conversation', desc:'This conversation belongs to a completed order.<br>Enter your admin password to view it.', okText:'View Conversation', okIcon:'fa-eye'        },
        chatUnlock : { icon:'fa-unlock-alt', title:'Unlock Chat',       desc:'Chat was locked due to inactivity.<br>Enter your admin password to continue.',             okText:'Unlock Chat',       okIcon:'fa-unlock-alt' },
    };

    function openPwConfirm(action) {
        if (action === 'driver') {
            var sel = document.getElementById('driverSelect');
            if (!sel || !sel.value) { alert('Please select a driver first.'); return; }
            pendingFormData = { driverId: sel.value };
        }
        if (action === 'status') {
            pendingFormData = {
                status  : document.getElementById('statusSelect').value,
                delivery: document.getElementById('deliveryDate').value,
                notes   : document.getElementById('deliveryNotes').value
            };
        }

        pendingAction = action;
        var cfg = modalConfig[action];
        document.getElementById('pwModalIcon').className    = 'fas ' + cfg.icon;
        document.getElementById('pwModalTitle').textContent = cfg.title;
        document.getElementById('pwModalDesc').innerHTML    = cfg.desc;
        document.getElementById('pwOkIcon').className       = 'fas ' + cfg.okIcon;
        document.getElementById('pwOkText').textContent     = cfg.okText;
        document.getElementById('pwInput').value            = '';
        clearPwError();
        setPwLoading(false);

        var overlay = document.getElementById('pwOverlay');
        var modal   = document.getElementById('pwModal');
        overlay.style.display = 'flex';
        modal.style.animation = 'none';
        void modal.offsetWidth;
        modal.style.animation = 'modalIn .25s cubic-bezier(.25,.8,.25,1) both';
        setTimeout(function () { document.getElementById('pwInput').focus(); }, 80);
    }

    function closePwConfirm() {
        document.getElementById('pwOverlay').style.display = 'none';
        pendingAction   = null;
        pendingFormData = {};
    }

    el = document.getElementById('acceptOrderBtn'); if (el) el.addEventListener('click', function () { openPwConfirm('accept'); });
    el = document.getElementById('updateStatusBtn'); if (el) el.addEventListener('click', function () { openPwConfirm('status'); });
    el = document.getElementById('assignDriverBtn'); if (el) el.addEventListener('click', function () { openPwConfirm('driver'); });
    el = document.getElementById('viewChatBtn');     if (el) el.addEventListener('click', function () { openPwConfirm('chat');   });
    el = document.getElementById('pwCancelBtn');     if (el) el.addEventListener('click', closePwConfirm);
    el = document.getElementById('pwOkBtn');         if (el) el.addEventListener('click', submitPwConfirm);

    el = document.getElementById('pwOverlay');
    if (el) el.addEventListener('click', function (e) { if (e.target === this) closePwConfirm(); });

    el = document.getElementById('pwEyeToggle');
    if (el) el.addEventListener('click', function () {
        var inp = document.getElementById('pwInput');
        var eye = document.getElementById('pwEye');
        inp.type      = inp.type === 'password' ? 'text' : 'password';
        eye.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    });

    el = document.getElementById('pwInput');
    if (el) {
        el.addEventListener('input',   clearPwError);
        el.addEventListener('keydown', function (e) { if (e.key === 'Enter') submitPwConfirm(); });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closePwConfirm(); closeBackConfirm(); }
    });

    function submitPwConfirm() {
        var pw = document.getElementById('pwInput').value.trim();
        if (!pw) { showPwError('Please enter your admin password.'); document.getElementById('pwInput').focus(); return; }
        setPwLoading(true);
        clearPwError();

        fetch(VERIFY_URL, {
            method : 'POST',
            headers: { 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN':CSRF },
            body   : JSON.stringify({ password: pw })
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (!data.verified) {
                setPwLoading(false);
                showPwError(data.message || 'Incorrect password. Please try again.');
                document.getElementById('pwInput').value = '';
                document.getElementById('pwInput').focus();
                return;
            }
            if (pendingAction === 'accept') {
                document.getElementById('acceptForm').submit();
            } else if (pendingAction === 'status') {
                document.getElementById('hiddenStatus').value   = pendingFormData.status;
                document.getElementById('hiddenDelivery').value = pendingFormData.delivery;
                document.getElementById('hiddenNotes').value    = pendingFormData.notes;
                document.getElementById('statusForm').submit();
            } else if (pendingAction === 'driver') {
                document.getElementById('hiddenDriverId').value = pendingFormData.driverId;
                document.getElementById('driverForm').submit();
            } else if (pendingAction === 'chat') {
                closePwConfirm();
                unblurChat();
            } else if (pendingAction === 'chatUnlock') {
                closePwConfirm();
                unlockChatByPassword();
            }
        })
        .catch(function () {
            setPwLoading(false);
            showPwError('Something went wrong. Please try again.');
        });
    }

    function setPwLoading(on) {
        var btn = document.getElementById('pwOkBtn');
        btn.disabled      = on;
        btn.style.opacity = on ? '0.7' : '';
        document.getElementById('pwOkIcon').style.display  = on ? 'none'  : '';
        document.getElementById('pwOkText').style.display  = on ? 'none'  : '';
        document.getElementById('pwSpinner').style.display = on ? 'block' : 'none';
    }
    function showPwError(msg) {
        document.getElementById('pwErrorMsg').textContent    = msg;
        document.getElementById('pwError').style.display     = 'flex';
        document.getElementById('pwInput').style.borderColor = 'rgba(220,0,0,.6)';
    }
    function clearPwError() {
        document.getElementById('pwError').style.display     = 'none';
        document.getElementById('pwInput').style.borderColor = '#2a2a2a';
    }

    /* ════════════════════════════════════
       CHAT UNBLUR (order-level lock)
    ════════════════════════════════════ */
    function unblurChat() {
        var box     = document.getElementById('adminChatBox');
        var overlay = document.getElementById('chatBlurOverlay');
        if (overlay) {
            overlay.style.opacity = '0';
            setTimeout(function () { overlay.style.display = 'none'; }, 360);
        }
        if (box) {
            box.style.filter        = 'none';
            box.style.pointerEvents = 'auto';
            box.style.userSelect    = 'auto';
            box.removeAttribute('aria-hidden');
            box.scrollTop = box.scrollHeight;
        }
    }

    /* ════════════════════════════════════
       DRIVER CHAT SEND + POLL
    ════════════════════════════════════ */
    var adminChatBox   = document.getElementById('adminChatBox');
    var adminId        = {{ auth()->id() }};
    var lastAdminMsgId = {{ $driverMessages->last()?->id ?? 0 }};

    if (adminChatBox) adminChatBox.scrollTop = adminChatBox.scrollHeight;

    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function adminSend() {
        var input = document.getElementById('adminMsgInput');
        var body  = input.value.trim();
        if (!body) return;
        input.value    = '';
        input.disabled = true;
        resetInactivityTimer();

        fetch('{{ route("admin.chat.send", $order) }}', {
            method : 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json' },
            body   : JSON.stringify({ body: body, receiver_id: {{ $order->driver_id ?? 'null' }} })
        })
        .then(function (res) { if (res.ok) pollAdminChat(); })
        .catch(function (e) { console.error('Send error:', e); })
        .finally(function () { input.disabled = false; input.focus(); });
    }

    el = document.getElementById('chatSendBtn');
    if (el) el.addEventListener('click', adminSend);

    el = document.getElementById('adminMsgInput');
    if (el) el.addEventListener('keydown', function (e) { if (e.key === 'Enter') adminSend(); });

    function pollAdminChat() {
        if (!adminChatBox) return;
        fetch('{{ route('admin.chat.poll', $order) }}' + '?since=' + lastAdminMsgId, {
            headers: { 'Accept':'application/json' }
        })
        .then(function (res) { return res.json(); })
        .then(function (msgs) {
            msgs.forEach(function (m) {
                if (adminChatBox.querySelector('[data-aid="' + m.id + '"]')) return;
                var mine = m.sender_id === adminId;
                var div  = document.createElement('div');
                div.dataset.aid   = m.id;
                div.style.cssText = 'display:flex;align-items:flex-end;gap:8px;' + (mine ? 'flex-direction:row-reverse' : '');
                div.innerHTML =
                    '<div style="max-width:68%;padding:9px 13px;border-radius:16px;font-size:13px;line-height:1.5;' +
                        (mine ? 'background:var(--ferrari-red);color:#fff;border-bottom-right-radius:3px'
                              : 'background:var(--dark3);border:1px solid #2a2a2a;border-bottom-left-radius:3px') + '">' +
                        '<div style="font-size:10px;margin-bottom:3px;' + (mine ? 'color:rgba(255,255,255,.65)' : 'color:var(--gray)') + '">' +
                            escHtml(m.sender) +
                        '</div>' + escHtml(m.body) +
                    '</div>' +
                    '<div style="font-size:10px;color:#444;flex-shrink:0">' + m.time + '</div>';
                adminChatBox.appendChild(div);
                adminChatBox.scrollTop = adminChatBox.scrollHeight;
                lastAdminMsgId = Math.max(lastAdminMsgId, m.id);
            });
        })
        .catch(function (e) { console.error('Poll error:', e); });
    }

    @if($order->driver_id && !in_array($order->status, ['delivered', 'cancelled']))
    setInterval(pollAdminChat, 3000);
    @endif

    /* ════════════════════════════════════
       INACTIVITY AUTO-LOCK (5 minutes)
       Only active when order is live
    ════════════════════════════════════ */
    @if($order->driver_id && !in_array($order->status, ['delivered', 'cancelled']))

    var INACTIVITY_MS   = 5 * 60 * 1000;   /* 5 minutes          */
    var WARNING_MS      = 30 * 1000;        /* warn 30s before    */
    var inactivityTimer = null;
    var warningTimer    = null;
    var chatIsLocked    = false;

    function resetInactivityTimer() {
        if (chatIsLocked) return;
        clearTimeout(inactivityTimer);
        clearTimeout(warningTimer);
        hideInactivityWarning();

        warningTimer = setTimeout(function () {
            if (!chatIsLocked) showInactivityWarning();
        }, INACTIVITY_MS - WARNING_MS);

        inactivityTimer = setTimeout(function () {
            lockChatByInactivity();
        }, INACTIVITY_MS);
    }

    function lockChatByInactivity() {
        if (chatIsLocked) return;
        chatIsLocked = true;
        clearTimeout(inactivityTimer);
        clearTimeout(warningTimer);
        hideInactivityWarning();

        /* Blur the messages area */
        var box = document.getElementById('adminChatBox');
        if (box) {
            box.style.filter        = 'blur(4px)';
            box.style.pointerEvents = 'none';
            box.style.userSelect    = 'none';
        }

        /* Inject inactivity overlay into the position:relative wrap */
        var wrap = document.getElementById('chatBoxWrap');
        if (wrap) {
            var existing = document.getElementById('inactivityLockOverlay');
            if (existing) {
                existing.style.display  = 'flex';
                existing.style.opacity  = '1';
            } else {
                var ov = document.createElement('div');
                ov.id  = 'inactivityLockOverlay';
                ov.style.cssText =
                    'position:absolute;inset:0;background:rgba(10,10,10,.6);' +
                    'backdrop-filter:blur(2px);display:flex;flex-direction:column;' +
                    'align-items:center;justify-content:center;gap:14px;' +
                    'transition:opacity .35s;z-index:10;';
                ov.innerHTML =
                    '<div style="text-align:center">' +
                        '<div style="width:52px;height:52px;border-radius:50%;margin:0 auto 10px;' +
                                    'display:flex;align-items:center;justify-content:center;' +
                                    'background:rgba(220,0,0,.1);border:1px solid rgba(220,0,0,.3)">' +
                            '<i class="fas fa-lock" style="color:var(--ferrari-red);font-size:18px"></i>' +
                        '</div>' +
                        '<div style="font-family:\'Bebas Neue\',sans-serif;font-size:16px;' +
                                    'letter-spacing:2px;color:#fff;margin-bottom:4px">Session Timed Out</div>' +
                        '<div style="color:var(--gray);font-size:11px;line-height:1.7">' +
                            'Chat locked after 5 minutes of inactivity.<br>Enter your password to continue.' +
                        '</div>' +
                    '</div>' +
                    '<button id="inactivityUnlockBtn" style="' +
                        'padding:10px 22px;border:none;border-radius:24px;cursor:pointer;' +
                        'font-family:\'Barlow\',sans-serif;font-weight:700;font-size:12px;' +
                        'letter-spacing:2px;text-transform:uppercase;background:var(--ferrari-red);' +
                        'color:#fff;display:flex;align-items:center;gap:8px;' +
                        'box-shadow:0 4px 20px rgba(220,0,0,.35)">' +
                        '<i class="fas fa-unlock-alt"></i> Unlock Chat' +
                    '</button>';
                wrap.appendChild(ov);

                document.getElementById('inactivityUnlockBtn')
                    .addEventListener('click', function () { openPwConfirm('chatUnlock'); });
            }
        }

        /* Disable the input bar */
        var input = document.getElementById('adminMsgInput');
        var sendB = document.getElementById('chatSendBtn');
        if (input) { input.disabled = true; input.placeholder = 'Chat locked — enter password to continue…'; }
        if (sendB)   sendB.disabled = true;
    }

    function unlockChatByPassword() {
        chatIsLocked = false;

        var ov = document.getElementById('inactivityLockOverlay');
        if (ov) {
            ov.style.opacity = '0';
            setTimeout(function () { ov.style.display = 'none'; }, 360);
        }

        var box = document.getElementById('adminChatBox');
        if (box) {
            box.style.filter        = 'none';
            box.style.pointerEvents = 'auto';
            box.style.userSelect    = 'auto';
            box.scrollTop = box.scrollHeight;
        }

        var input = document.getElementById('adminMsgInput');
        var sendB = document.getElementById('chatSendBtn');
        if (input) { input.disabled = false; input.placeholder = 'Type a message to driver…'; input.focus(); }
        if (sendB)   sendB.disabled = false;

        /* Restart the inactivity timer after unlock */
        resetInactivityTimer();
    }

    /* 30-second warning bar appended to the card */
    function showInactivityWarning() {
        if (document.getElementById('inactivityWarningBar')) return;
        var card = document.getElementById('driverChatCard');
        if (!card) return;

        var remaining = 30;
        var bar       = document.createElement('div');
        bar.id        = 'inactivityWarningBar';
        bar.style.cssText =
            'background:rgba(245,197,24,.08);border-top:1px solid rgba(245,197,24,.25);' +
            'padding:9px 16px;display:flex;align-items:center;' +
            'justify-content:space-between;gap:12px;font-size:12px;color:#f5c518;';
        bar.innerHTML =
            '<span style="display:flex;align-items:center;gap:8px">' +
                '<i class="fas fa-clock"></i>' +
                'Chat will lock in <strong id="inactivityCountdown">30</strong>s due to inactivity.' +
            '</span>' +
            '<button id="inactivityStayBtn" style="' +
                'padding:4px 16px;border:1px solid rgba(245,197,24,.35);border-radius:20px;' +
                'background:transparent;color:#f5c518;cursor:pointer;font-size:11px;' +
                'font-family:\'Barlow\',sans-serif;font-weight:700;letter-spacing:1px;' +
                'white-space:nowrap;flex-shrink:0">Keep Active' +
            '</button>';
        card.appendChild(bar);

        var tick = setInterval(function () {
            remaining--;
            var cd = document.getElementById('inactivityCountdown');
            if (cd) cd.textContent = remaining;
            if (remaining <= 0) clearInterval(tick);
        }, 1000);

        document.getElementById('inactivityStayBtn')
            .addEventListener('click', function () {
                clearInterval(tick);
                resetInactivityTimer();
            });
    }

    function hideInactivityWarning() {
        var bar = document.getElementById('inactivityWarningBar');
        if (bar) bar.remove();
    }

    /* Activity triggers that reset the timer */
    el = document.getElementById('adminMsgInput');
    if (el) {
        el.addEventListener('keydown', resetInactivityTimer);
        el.addEventListener('focus',   resetInactivityTimer);
    }
    el = document.getElementById('chatSendBtn');
    if (el) el.addEventListener('click', resetInactivityTimer);

    /* Kick off the timer as soon as the page loads */
    resetInactivityTimer();

    @endif /* end inactivity block */

}); /* end DOMContentLoaded */
</script>

@endsection