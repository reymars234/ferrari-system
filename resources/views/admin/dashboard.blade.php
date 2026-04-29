@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

{{-- ══════════════════════════════════════════════════
     SPLASH STYLES — into <head>
══════════════════════════════════════════════════ --}}
@push('styles')
<style>
/* ─── SPLASH OVERLAY ─────────────────────────────────────────── */
#admin-splash {
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: #080c12;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 16px;
    pointer-events: none;
}

#splash-bar-line {
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #dc0000, #ff6644, #dc0000);
    border-radius: 2px;
}

#splash-text-line {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: nowrap;
    white-space: nowrap;
}

.s-letter {
    font-family: 'Bebas Neue', 'Segoe UI', system-ui, sans-serif;
    font-size: clamp(36px, 5.5vw, 68px);
    font-weight: 800;
    letter-spacing: 6px;
    color: #ffffff;
    display: inline-block;
    opacity: 0;
    will-change: transform, opacity;
}

.s-letter.accent { color: #dc0000; }
.s-letter.space  { width: clamp(10px, 1.6vw, 20px); }

#splash-subtitle {
    font-family: 'Barlow', 'Segoe UI', system-ui, sans-serif;
    font-size: 12px;
    letter-spacing: 7px;
    color: rgba(255, 255, 255, 0.28);
    text-transform: uppercase;
    opacity: 0;
    will-change: opacity;
}

#splash-tagline {
    font-family: 'Barlow', 'Segoe UI', system-ui, sans-serif;
    font-size: 9px;
    letter-spacing: 4px;
    color: rgba(220, 0, 0, 0.5);
    text-transform: uppercase;
    opacity: 0;
    will-change: opacity;
}

#splash-glow {
    position: absolute;
    width: 560px;
    height: 560px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(220, 0, 0, 0.07) 0%, transparent 70%);
    pointer-events: none;
    opacity: 0;
}

/* ─── WELCOME BAR ────────────────────────────────────────────── */
.welcome-bar {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    padding: 8px 16px;
    background: rgba(220, 0, 0, 0.07);
    border: 1px solid rgba(220, 0, 0, 0.2);
    border-radius: 20px;
    opacity: 0;
    animation: wbFadeIn 0.6s ease forwards 0.2s;
}

.welcome-wave {
    font-size: 15px;
    display: inline-block;
    animation: wave 1.2s ease 0.8s 2;
    transform-origin: 70% 70%;
}

.welcome-msg { font-size: 13px; color: #aab4c8; letter-spacing: 0.2px; }
.welcome-msg strong { color: #dc0000; font-weight: 600; }

@keyframes wbFadeIn { to { opacity: 1; } }
@keyframes wave {
    0%, 100% { transform: rotate(0deg); }
    25%       { transform: rotate(20deg); }
    75%       { transform: rotate(-10deg); }
}
</style>
@endpush

{{-- ══════════════════════════════════════════════════
     SPLASH HTML — pushed to @stack('splash') in layout.
     This stack sits directly under <body> BEFORE the
     sidebar/topbar markup, so position:fixed covers
     the entire viewport including the sidebar.
══════════════════════════════════════════════════ --}}
@push('splash')
<div id="admin-splash">
    <div id="splash-glow"></div>
    <div id="splash-bar-line"></div>
    <div id="splash-text-line"></div>
    <div id="splash-subtitle">Dashboard</div>
    <div id="splash-tagline">Admin Panel</div>
</div>
@endpush

{{-- ══════════════════════════════════════════════════
     DASHBOARD CONTENT
══════════════════════════════════════════════════ --}}
@section('content')

<div id="dashboard-body" style="opacity:0;">

    <div class="welcome-bar">
        <span class="welcome-wave">👋</span>
        <span class="welcome-msg">Welcome back, <strong>{{ auth()->user()->name }}!</strong></span>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="color:#3a8ef6;"><i class="fas fa-users"></i></div>
            <div class="stat-value">{{ $stats['total_users'] }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="color:var(--red);"><i class="fas fa-car"></i></div>
            <div class="stat-value">{{ $stats['total_cars'] }}</div>
            <div class="stat-label">Cars Listed</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="color:var(--yellow);"><i class="fas fa-shopping-bag"></i></div>
            <div class="stat-value">{{ $stats['total_orders'] }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="color:#ff6b00;"><i class="fas fa-clock"></i></div>
            <div class="stat-value">{{ $stats['pending_orders'] }}</div>
            <div class="stat-label">Pending Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="color:#1db954;"><i class="fas fa-peso-sign"></i></div>
            <div class="stat-value" style="font-size:24px;">₱{{ number_format($stats['total_revenue'], 0) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">
        <div class="card">
            <div class="card-header">
                <h3>Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">View All</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>ID</th><th>User</th><th>Car</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->car->name }}</td>
                            <td><span class="badge badge-{{ $order->status }}">{{ $order->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Recent Activity</h3>
                <a href="{{ route('admin.audit.index') }}" class="btn btn-outline btn-sm">View All</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Action</th><th>User</th><th>Time</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentLogs as $log)
                        <tr>
                            <td><span style="color:var(--red); font-size:11px; font-weight:700;">{{ $log->action }}</span></td>
                            <td style="color:var(--gray);">{{ $log->user->name ?? 'System' }}</td>
                            <td style="color:var(--gray);">{{ $log->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>{{-- end #dashboard-body --}}

@endsection

{{-- ══════════════════════════════════════════════════
     SPLASH ANIMATION SCRIPT
══════════════════════════════════════════════════ --}}
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<script>
(function () {

    /* ══════════════════════════════════════════════════════════════
       SESSION GUARD
       Key cleared by logout form listener in admin.blade.php,
       so splash plays fresh on every new login session.
    ══════════════════════════════════════════════════════════════ */
    var SPLASH_KEY  = 'vv_splash_shown';
    var splashShown = sessionStorage.getItem(SPLASH_KEY) === '1';
    var splash      = document.getElementById('admin-splash');
    var body        = document.getElementById('dashboard-body');

    if (splashShown) {
        splash.style.display = 'none';
        if (typeof gsap !== 'undefined') {
            gsap.set(body, { opacity: 1 });
        } else {
            body.style.opacity = '1';
        }
        return;
    }

    sessionStorage.setItem(SPLASH_KEY, '1');

    if (typeof gsap === 'undefined') {
        splash.style.display = 'none';
        body.style.opacity   = '1';
        return;
    }

    /* ══════════════════════════════════════════════════════════════
       BUILD LETTERS
    ══════════════════════════════════════════════════════════════ */
    var line  = document.getElementById('splash-text-line');
    var words = [
        { text: 'V',      accent: true  },
        { text: 'ELOCE',  accent: false },
        { text: ' ',      space:  true  },
        { text: 'V',      accent: true  },
        { text: 'ANTAGE', accent: false },
    ];

    words.forEach(function (word) {
        word.text.split('').forEach(function (ch) {
            var s   = document.createElement('span');
            var cls = 's-letter';
            if (word.accent) cls += ' accent';
            if (word.space)  cls += ' space';
            s.className   = cls;
            s.textContent = ch === ' ' ? '\u00A0' : ch;
            line.appendChild(s);
        });
    });

    /* ══════════════════════════════════════════════════════════════
       REFS & INITIAL STATES
    ══════════════════════════════════════════════════════════════ */
    var bar     = document.getElementById('splash-bar-line');
    var glow    = document.getElementById('splash-glow');
    var sub     = document.getElementById('splash-subtitle');
    var tag     = document.getElementById('splash-tagline');
    var letters = document.querySelectorAll('.s-letter');

    gsap.set(letters, { y: 50, rotateX: -70, opacity: 0, transformPerspective: 800 });
    gsap.set(sub,     { opacity: 0, y: 10 });
    gsap.set(tag,     { opacity: 0, y:  6 });
    gsap.set(glow,    { opacity: 0, scale: 0.6 });
    gsap.set(body,    { opacity: 0 });

    /* ══════════════════════════════════════════════════════════════
       MASTER TIMELINE
    ══════════════════════════════════════════════════════════════ */
    var tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    tl
        .to(glow, { opacity: 1, scale: 1.2, duration: 0.9, ease: 'power2.out' }, 0)
        .to(bar,  { width: 260, duration: 0.55 }, 0.15)
        .to(letters, {
            opacity: 1, y: 0, rotateX: 0,
            duration: 0.65, ease: 'back.out(1.6)',
            stagger: { amount: 0.42, from: 'start' },
        }, 0.3)
        .to(sub, { opacity: 1, y: 0, duration: 0.4 }, 0.9)
        .to(tag, { opacity: 1, y: 0, duration: 0.35 }, 1.05)

        /* hold */
        .to({}, { duration: 1.4 })

        /* exit letters */
        .to(letters, {
            opacity: 0, y: -44, rotateX: 55,
            duration: 0.38, ease: 'power2.in',
            stagger: { amount: 0.25, from: 'end' },
        })

        /* fade out bar / labels */
        .to([bar, sub, tag, glow], { opacity: 0, duration: 0.3 }, '-=0.3')

        /* overlay out */
        .to(splash, {
            opacity: 0, duration: 0.5, ease: 'power2.inOut',
            onComplete: function () { splash.style.display = 'none'; },
        }, '-=0.1')

        /* dashboard in */
        .to(body, { opacity: 1, duration: 0.6, ease: 'power2.out' }, '-=0.25');

})();
</script>
@endpush