{{-- resources/views/driver/chat-admin.blade.php — Driver ↔ Admin --}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Chat with Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--red:#dc0000;--dark:#0d0d0d;--dark2:#1a1a1a;--dark3:#252525;--light:#e8e8e8;--gray:#888}
body{background:var(--dark);color:var(--light);font-family:'Barlow',sans-serif;height:100vh;display:flex;flex-direction:column}
.topbar{background:var(--dark2);border-bottom:1px solid rgba(220,0,0,.15);padding:0 20px;height:56px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.topbar-left{display:flex;align-items:center;gap:12px}
.back-btn{background:transparent;border:1px solid #333;color:var(--gray);padding:6px 12px;border-radius:4px;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;text-decoration:none;display:flex;align-items:center;gap:5px;transition:all .2s}
.back-btn:hover{border-color:var(--red);color:var(--red)}
.chat-title{font-family:'Bebas Neue',sans-serif;font-size:16px;letter-spacing:2px}
.chat-subtitle{color:var(--gray);font-size:11px;margin-top:1px}
.badge{padding:2px 10px;border-radius:20px;font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;border:1px solid}
.badge-admin{background:rgba(220,0,0,.12);border-color:var(--red);color:var(--red)}
.switch-btn{padding:6px 14px;border-radius:4px;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;text-decoration:none;display:flex;align-items:center;gap:5px;transition:all .2s;border:1px solid}
.switch-customer{background:rgba(29,185,84,.1);border-color:#1db954;color:#1db954}
.switch-customer:hover{background:#1db954;color:#000}
.messages-wrap{flex:1;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:10px}
.msg{max-width:68%;display:flex;flex-direction:column;gap:3px}
.msg.mine{align-self:flex-end;align-items:flex-end}
.msg.theirs{align-self:flex-start;align-items:flex-start}
.msg-bubble{padding:10px 14px;border-radius:12px;font-size:13px;line-height:1.6;word-break:break-word}
.msg.mine .msg-bubble{background:var(--red);color:#fff;border-bottom-right-radius:3px}
.msg.theirs .msg-bubble{background:var(--dark3);color:var(--light);border-bottom-left-radius:3px}
.msg-meta{font-size:10px;color:var(--gray)}
.input-area{background:var(--dark2);border-top:1px solid #1e1e1e;padding:14px 20px;display:flex;gap:10px;flex-shrink:0}
.input-area textarea{flex:1;background:var(--dark3);border:1px solid #2e2e2e;color:var(--light);padding:10px 14px;border-radius:8px;font-family:'Barlow',sans-serif;font-size:13px;resize:none;height:44px;outline:none;transition:border-color .2s}
.input-area textarea:focus{border-color:rgba(220,0,0,.4)}
.send-btn{background:var(--red);border:none;color:#fff;padding:0 20px;border-radius:8px;cursor:pointer;font-size:15px;flex-shrink:0;transition:background .2s}
.send-btn:hover{background:#b00000}
.empty{text-align:center;color:var(--gray);margin:auto}
.empty i{font-size:30px;display:block;margin-bottom:10px;opacity:.3}
</style>
</head>
<body>
<div class="topbar">
    <div class="topbar-left">
        <a href="{{ route('driver.orders') }}" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
        <div>
            <div class="chat-title">
                <i class="fas fa-user-shield" style="color:var(--red);margin-right:5px"></i>
                Admin
                <span class="badge badge-admin" style="margin-left:6px">Admin</span>
            </div>
            <div class="chat-subtitle">Order #{{ $order->id }} · {{ $order->car->name ?? '' }}</div>
        </div>
    </div>
    <a href="{{ route('driver.chat', $order) }}" class="switch-btn switch-customer">
        <i class="fas fa-user"></i> Chat with Customer
    </a>
</div>

<div class="messages-wrap" id="msgWrap">
    @forelse($messages as $msg)
        <div class="msg {{ $msg->sender_id === auth()->id() ? 'mine' : 'theirs' }}">
            <div class="msg-bubble">{{ $msg->body }}</div>
            <div class="msg-meta">{{ $msg->sender->name }} · {{ $msg->created_at->format('h:i A') }}</div>
        </div>
    @empty
        <div class="empty"><i class="fas fa-comments"></i>No messages with admin yet.</div>
    @endforelse
</div>

<div class="input-area">
    <form method="POST" action="{{ route('driver.admin-chat.send', $order) }}" style="display:contents">
        @csrf
        <textarea name="body" placeholder="Message admin..." required
            onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();this.closest('form').submit()}"></textarea>
        <button type="submit" class="send-btn"><i class="fas fa-paper-plane"></i></button>
    </form>
</div>
<script>
const wrap = document.getElementById('msgWrap');
wrap.scrollTop = wrap.scrollHeight;
setInterval(() => {
    fetch('{{ route('driver.admin-chat.poll', $order) }}')
        .then(r => r.json()).then(msgs => {
            if (!msgs.length) return;
            wrap.innerHTML = msgs.map(m =>
                `<div class="msg ${m.mine?'mine':'theirs'}">
                    <div class="msg-bubble">${m.body}</div>
                    <div class="msg-meta">${m.sender} · ${m.created_at}</div>
                </div>`).join('');
            wrap.scrollTop = wrap.scrollHeight;
        });
}, 4000);
</script>
</body>
</html>