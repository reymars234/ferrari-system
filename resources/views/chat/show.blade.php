{{-- FILE: resources/views/chat/show.blade.php --}}
@extends('layouts.app')
@section('title','Chat — Order #'.$order->id)
@push('styles')
<style>
html, body { height: 100%; margin: 0; }

/* Full-height fixed layout anchored below navbar */
.chat-page {
    position: fixed;
    top: 64px;
    left: 0; right: 0; bottom: 0;
    display: flex;
    flex-direction: column;
    background: #0a0a0a;
    overflow: hidden;
}

/* ── Header ── */
.chat-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px 32px;
    background: #111;
    border-bottom: 1px solid rgba(220,0,0,0.15);
    flex-shrink: 0;
}
.chat-av {
    width: 42px; height: 42px;
    border-radius: 50%;
    background: rgba(220,0,0,0.08);
    border: 1.5px solid rgba(220,0,0,0.3);
    display: flex; align-items: center; justify-content: center;
    color: var(--ferrari-red);
    font-size: 16px;
    flex-shrink: 0;
}
.chat-hname {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 18px;
    letter-spacing: 2px;
    color: #fff;
}
.chat-hsub {
    color: #444;
    font-size: 11px;
    margin-top: 3px;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.chat-hsub strong { color: #888; font-weight: 600; }
.badge {
    padding: 2px 7px;
    border-radius: 3px;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}
.badge-processing { background:rgba(68,136,255,.12); border:1px solid rgba(68,136,255,.25); color:#4488ff; }
.badge-pending    { background:rgba(245,197,24,.1);  border:1px solid rgba(245,197,24,.25);  color:#f5c518; }
.badge-delivered  { background:rgba(29,185,84,.1);   border:1px solid rgba(29,185,84,.25);   color:#1db954; }
.badge-cancelled  { background:rgba(255,68,68,.1);   border:1px solid rgba(255,68,68,.25);   color:#ff4444; }

/* ── Messages area ── */
.chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 28px 32px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    scrollbar-width: thin;
    scrollbar-color: #1e1e1e transparent;
}
.chat-body::-webkit-scrollbar { width: 4px; }
.chat-body::-webkit-scrollbar-thumb { background: #1e1e1e; border-radius: 2px; }

/* Empty state */
.no-msg {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 14px;
    color: #1e1e1e;
}
.no-msg i    { font-size: 52px; }
.no-msg p    { font-size: 11px; letter-spacing: 3px; text-transform: uppercase; color: #2a2a2a; margin: 0; }

/* Message rows */
.msg {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    max-width: 68%;
    animation: msgIn .22s ease both;
}
@keyframes msgIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
.msg.mine  { align-self: flex-end;  flex-direction: row-reverse; }
.msg.other { align-self: flex-start; }

.msg-av {
    width: 26px; height: 26px;
    border-radius: 50%;
    background: #161616;
    border: 1px solid #1e1e1e;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; color: #333;
    flex-shrink: 0; margin-bottom: 2px;
}
.msg-inner { display: flex; flex-direction: column; gap: 3px; }
.msg-sender { font-size: 10px; color: #3a3a3a; padding: 0 4px; }
.msg.mine .msg-sender { text-align: right; color: #3a3a3a; }

.bubble {
    padding: 10px 15px;
    border-radius: 18px;
    font-size: 13.5px;
    line-height: 1.6;
    word-break: break-word;
}
.msg.other .bubble {
    background: #141414;
    border: 1px solid #1e1e1e;
    border-bottom-left-radius: 4px;
    color: #ccc;
}
.msg.mine .bubble {
    background: linear-gradient(135deg, #dc0000 0%, #a00000 100%);
    color: #fff;
    border-bottom-right-radius: 4px;
    box-shadow: 0 4px 18px rgba(220,0,0,0.22);
}
.msg-meta {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 0 4px;
}
.msg.mine .msg-meta { justify-content: flex-end; }
.msg-time { font-size: 10px; color: #2e2e2e; }

/* ── Footer ── */
.chat-footer {
    display: flex;
    align-items: flex-end;
    gap: 10px;
    padding: 14px 32px 20px;
    background: #111;
    border-top: 1px solid rgba(220,0,0,0.1);
    flex-shrink: 0;
}
.recv-select {
    background: #161616;
    border: 1px solid #222;
    border-radius: 8px;
    color: #666;
    padding: 9px 12px;
    font-size: 11px;
    font-family: 'Barlow', sans-serif;
    letter-spacing: .5px;
    flex-shrink: 0;
    transition: border-color .2s, color .2s;
    cursor: pointer;
}
.recv-select:focus { outline: none; border-color: rgba(220,0,0,0.35); color: #aaa; }

.chat-input-wrap {
    flex: 1;
    display: flex;
    align-items: flex-end;
    gap: 10px;
    background: #141414;
    border: 1px solid #222;
    border-radius: 26px;
    padding: 6px 6px 6px 20px;
    transition: border-color .2s;
}
.chat-input-wrap:focus-within { border-color: rgba(220,0,0,0.35); }

.chat-input {
    flex: 1;
    background: transparent;
    border: none;
    color: #ddd;
    font-size: 14px;
    font-family: 'Barlow', sans-serif;
    resize: none;
    min-height: 36px;
    max-height: 120px;
    line-height: 1.55;
    padding: 7px 0;
    overflow-y: auto;
    scrollbar-width: none;
}
.chat-input:focus   { outline: none; }
.chat-input::placeholder { color: #2e2e2e; }

.send-btn {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: var(--ferrari-red, #dc0000);
    border: none;
    color: #fff;
    cursor: pointer;
    font-size: 13px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: background .2s, transform .15s, box-shadow .2s;
    align-self: flex-end;
    margin-bottom: 1px;
}
.send-btn:hover  { background: #b00000; transform: scale(1.1); box-shadow: 0 4px 18px rgba(220,0,0,0.4); }
.send-btn:active { transform: scale(.93); }
</style>
@endpush

@section('content')
<div class="chat-page">

    {{-- Header --}}
    <div class="chat-header">
        <div class="chat-av"><i class="fas fa-car"></i></div>
        <div style="flex:1;min-width:0">
            <div class="chat-hname">Order #{{ $order->id }} &mdash; {{ $order->car->name }}</div>
            <div class="chat-hsub">
                @if($order->driver)
                    <i class="fas fa-steering-wheel" style="font-size:9px"></i>
                    <strong>{{ $order->driver->name }}</strong>
                    <span style="color:#222">/</span>
                @endif
                <i class="fas fa-user" style="font-size:9px"></i>
                <strong>{{ $order->user->name }}</strong>
                <span style="color:#222">/</span>
                <span class="badge badge-{{ $order->status }}">{{ $order->status }}</span>
            </div>
        </div>
        <a href="{{ request()->is('admin/*') ? route('admin.orders.show',$order) : route('orders.show',$order) }}"
           class="btn btn-outline btn-sm" style="flex-shrink:0">
            <i class="fas fa-receipt" style="margin-right:5px"></i>Order Details
        </a>
    </div>

    {{-- Messages --}}
    <div class="chat-body" id="chatBody">
        @if($messages->isEmpty())
            <div class="no-msg">
                <i class="fas fa-comments"></i>
                <p>No messages yet</p>
            </div>
        @else
            @foreach($messages as $msg)
            <div class="msg {{ $msg->sender_id === auth()->id() ? 'mine' : 'other' }}" data-id="{{ $msg->id }}">
                @if($msg->sender_id !== auth()->id())
                    <div class="msg-av"><i class="fas fa-user"></i></div>
                @endif
                <div class="msg-inner">
                    @if($msg->sender_id !== auth()->id())
                        <div class="msg-sender">{{ $msg->sender->name }}</div>
                    @endif
                    <div class="bubble">{{ $msg->body }}</div>
                    <div class="msg-meta">
                        <span class="msg-time">{{ $msg->created_at->format('h:i A') }}</span>
                        @if($msg->sender_id === auth()->id())
                            <i class="fas fa-check" style="font-size:8px;color:rgba(255,255,255,0.25)"></i>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    {{-- Footer --}}
    <div class="chat-footer">
        @if(auth()->user()->isAdmin())
        <select class="recv-select" id="recvSel">
            @if($order->driver)
                <option value="{{ $order->driver_id }}">→ Driver: {{ $order->driver->name }}</option>
            @endif
            <option value="{{ $order->user_id }}">→ Customer: {{ $order->user->name }}</option>
        </select>
        @endif

        <div class="chat-input-wrap">
            <textarea class="chat-input" id="chatInput"
                      placeholder="Type a message…" rows="1"
                      oninput="autoResize(this)"
                      onkeydown="if(event.key==='Enter'&&!event.shiftKey){sendMsg(event)}"></textarea>
            <button class="send-btn" type="button" onclick="sendMsg(event)">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const chatBody = document.getElementById('chatBody');
let lastId = {{ $messages->last() ? $messages->last()->id : 0 }};
const myId  = {{ auth()->id() }};

function scrollBot(smooth = false) {
    chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: smooth ? 'smooth' : 'instant' });
}
scrollBot();

function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

function esc(s) {
    return String(s)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;');
}

async function sendMsg(e) {
    e.preventDefault();
    const input = document.getElementById('chatInput');
    const body  = input.value.trim();
    if (!body) return;
    input.value = '';
    input.style.height = 'auto';

    const recvSel = document.getElementById('recvSel');
    const payload = { body };
    if (recvSel) payload.receiver_id = recvSel.value;

    try {
        const res = await fetch('{{ route("chat.send", $order) }}', {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  '{{ csrf_token() }}',
                'Accept':        'application/json',
            },
            body: JSON.stringify(payload),
        });
        const msg = await res.json();
        appendMsg(msg.body, msg.time, true, msg.id, 'You');
        lastId = msg.id;
    } catch(err) { console.error(err); }
}

function appendMsg(body, time, mine, id, sender) {
    document.querySelector('.no-msg')?.remove();

    const div = document.createElement('div');
    div.className  = 'msg ' + (mine ? 'mine' : 'other');
    div.dataset.id = id;
    div.innerHTML  = `
        ${!mine ? `<div class="msg-av"><i class="fas fa-user"></i></div>` : ''}
        <div class="msg-inner">
            ${!mine ? `<div class="msg-sender">${esc(sender)}</div>` : ''}
            <div class="bubble">${esc(body)}</div>
            <div class="msg-meta">
                <span class="msg-time">${esc(time)}</span>
                ${mine ? `<i class="fas fa-check" style="font-size:8px;color:rgba(255,255,255,0.25)"></i>` : ''}
            </div>
        </div>`;

    chatBody.appendChild(div);
    scrollBot(true);
}

// Poll every 3 s
setInterval(async () => {
    try {
        const res  = await fetch(`{{ route('chat.poll', $order) }}?since=${lastId}`, {
            headers: { 'Accept': 'application/json' }
        });
        const msgs = await res.json();
        msgs.forEach(m => {
            if (!document.querySelector(`.msg[data-id="${m.id}"]`)) {
                appendMsg(m.body, m.time, m.sender_id === myId, m.id, m.sender);
                lastId = Math.max(lastId, m.id);
            }
        });
    } catch(e) {}
}, 3000);
</script>
@endpush