@extends('layouts.app')
@section('title', $car->name . ' — Ferrari System')
@push('styles')
<style>
    .detail-page { padding: 52px 0 80px; }

    /* ── BACK LINK ── */
    .back-link {
        display: inline-flex; align-items: center; gap: 8px;
        color: var(--gray); font-size: 12px; letter-spacing: 2px;
        text-transform: uppercase; margin-bottom: 36px;
        transition: color 0.2s ease;
    }
    .back-link:hover { color: var(--ferrari-red); }
    .back-link:hover i { transform: translateX(-3px); }
    .back-link i { transition: transform 0.2s ease; }

    /* ── HERO GRID ── */
    .detail-hero {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 40px; align-items: start; margin-bottom: 60px;
    }

    /* ── MEDIA CARD ── */
    .media-card {
        background: var(--dark2); border: 1px solid #1e1e1e;
        border-radius: 14px; overflow: hidden;
        box-shadow: 0 24px 64px rgba(0,0,0,0.5);
        animation: slideInLeft 0.65s cubic-bezier(.25,.8,.25,1) both;
    }
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-32px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    /* Tabs */
    .media-tabs { display: flex; border-bottom: 1px solid #1e1e1e; }
    .media-tab {
        flex: 1; padding: 13px; text-align: center;
        font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
        color: var(--gray); cursor: pointer;
        border: none; background: none; font-family: 'Barlow', sans-serif;
        transition: color 0.25s, background 0.25s;
    }
    .media-tab:hover { color: var(--light); background: rgba(255,255,255,0.02); }
    .media-tab.active {
        color: var(--ferrari-red);
        border-bottom: 2px solid var(--ferrari-red);
        background: rgba(220,0,0,0.04);
    }

    /* Panels */
    .media-panels { position: relative; height: 300px; background: #080808; overflow: hidden; }
    .media-panel {
        position: absolute; inset: 0;
        opacity: 0; transition: opacity 0.45s ease;
        pointer-events: none;
    }
    .media-panel.active { opacity: 1; pointer-events: all; }

    /* Image */
    .media-panel-img img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.6s cubic-bezier(.25,.8,.25,1);
    }
    .media-panel-img:hover img { transform: scale(1.04); }
    .no-img-placeholder {
        width: 100%; height: 100%;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 12px;
    }
    .no-img-placeholder i    { font-size: 56px; color: #222; }
    .no-img-placeholder span { font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #333; }

    /* Video */
    .media-panel-video { background: #000; }
    .car-video { width: 100%; height: 100%; object-fit: cover; display: block; }
    .no-video-msg {
        width: 100%; height: 100%;
        display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px;
    }
    .no-video-msg i    { font-size: 48px; color: #1e1e1e; }
    .no-video-msg span { font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #2a2a2a; }
    .no-video-msg small{ font-size: 10px; color: #222; margin-top: 2px; }

    /* Video controls */
    .video-controls {
        padding: 10px 14px; display: none;
        align-items: center; gap: 10px;
        background: var(--dark3); border-top: 1px solid #1e1e1e;
    }
    .video-controls.visible { display: flex; }
    .vc-btn {
        background: none; border: none; cursor: pointer;
        color: var(--gray); font-size: 15px;
        width: 30px; height: 30px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; transition: color 0.2s, background 0.2s;
    }
    .vc-btn:hover { color: var(--ferrari-red); background: rgba(220,0,0,0.08); }
    .vc-progress {
        flex: 1; height: 3px; background: #2a2a2a; border-radius: 2px;
        cursor: pointer; position: relative; overflow: hidden;
    }
    .vc-progress-fill { height: 100%; background: var(--ferrari-red); border-radius: 2px; width: 0; pointer-events: none; }
    .vc-time { font-size: 11px; color: var(--gray); letter-spacing: 1px; white-space: nowrap; min-width: 76px; text-align: right; }

    /* Card footer */
    .media-footer {
        padding: 12px 18px; display: flex; align-items: center; justify-content: space-between;
        border-top: 1px solid #1e1e1e;
    }
    .avail-badge { display: inline-flex; align-items: center; gap: 6px; font-size: 10px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; }
    .avail-badge .dot { width: 7px; height: 7px; border-radius: 50%; }
    .avail-yes { color: #1db954; }
    .avail-yes .dot { background: #1db954; animation: blink-dot 2s infinite; }
    .avail-no  { color: #ff4444; }
    .avail-no  .dot { background: #ff4444; }
    @keyframes blink-dot { 0%,100%{opacity:1} 50%{opacity:0.3} }

    /* ── INFO ── */
    .info-card { animation: slideInRight 0.65s cubic-bezier(.25,.8,.25,1) 0.1s both; }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(32px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .car-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(220,0,0,0.08); border: 1px solid rgba(220,0,0,0.25);
        color: var(--ferrari-red); font-size: 9px; font-weight: 700;
        letter-spacing: 2px; text-transform: uppercase;
        padding: 4px 12px; border-radius: 2px; margin-bottom: 14px;
    }
    .car-name-large {
        font-family: 'Bebas Neue', sans-serif;
        font-size: clamp(32px, 4.5vw, 52px); letter-spacing: 3px;
        line-height: 1; margin-bottom: 14px;
    }
    .car-desc-text {
        color: var(--gray); font-size: 14px; line-height: 1.85;
        margin-bottom: 24px; border-left: 2px solid rgba(220,0,0,0.3);
        padding-left: 14px;
    }
    .car-price-large {
        font-size: 32px; font-weight: 700; color: var(--ferrari-red);
        margin-bottom: 28px; display: flex; align-items: baseline; gap: 8px;
    }
    .car-price-large span { font-size: 13px; color: #555; font-weight: 400; }
    .detail-actions { display: flex; gap: 12px; flex-wrap: wrap; }

    /* ── SPECS ── */
    .specs-section { animation: fadeUp 0.6s ease 0.3s both; }
    @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .section-head {
        font-family: 'Bebas Neue', sans-serif; font-size: 26px;
        letter-spacing: 3px; margin-bottom: 20px;
        display: flex; align-items: center; gap: 16px;
    }
    .section-head::after { content:''; flex:1; height:1px; background:#1e1e1e; }
    .specs-grid {
        display: grid; grid-template-columns: repeat(auto-fill,minmax(155px,1fr));
        gap: 12px; margin-bottom: 44px;
    }
    .spec-card {
        background: var(--dark2); border: 1px solid #1e1e1e; border-radius: 8px;
        padding: 18px 14px; text-align: center;
        transition: border-color 0.3s, transform 0.3s, box-shadow 0.3s;
    }
    .spec-card:hover { border-color: var(--ferrari-red); transform: translateY(-3px); box-shadow: 0 10px 28px rgba(220,0,0,0.1); }
    .spec-icon  { font-size: 20px; color: var(--ferrari-red); margin-bottom: 8px; }
    .spec-value { font-family:'Bebas Neue',sans-serif; font-size:24px; letter-spacing:2px; margin-bottom:4px; }
    .spec-label { font-size:9px; letter-spacing:2px; text-transform:uppercase; color:var(--gray); }

    .highlights-grid {
        display: grid; grid-template-columns: repeat(auto-fill,minmax(210px,1fr));
        gap: 12px; margin-bottom: 44px;
    }
    .highlight-card {
        background: var(--dark2); border: 1px solid #1e1e1e; border-radius: 8px;
        padding: 18px; display: flex; align-items: flex-start; gap: 14px;
        transition: border-color 0.3s;
    }
    .highlight-card:hover { border-color: rgba(220,0,0,0.3); }
    .h-icon { width:38px; height:38px; flex-shrink:0; background:rgba(220,0,0,0.07); border-radius:6px; display:flex; align-items:center; justify-content:center; color:var(--ferrari-red); font-size:15px; }
    .h-title { font-weight:700; font-size:13px; margin-bottom:4px; }
    .h-desc  { color:var(--gray); font-size:12px; line-height:1.6; }

    .cta-banner {
        background: linear-gradient(135deg,rgba(220,0,0,0.07) 0%,transparent 60%);
        border: 1px solid rgba(220,0,0,0.18); border-radius: 12px;
        padding: 36px; display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap; gap: 20px;
    }
    .cta-banner h3 { font-family:'Bebas Neue',sans-serif; font-size:26px; letter-spacing:3px; margin-bottom:4px; }
    .cta-banner p  { color:var(--gray); font-size:13px; }

    @media(max-width:768px) {
        .detail-hero { grid-template-columns:1fr; gap:24px; }
        .car-name-large { font-size:36px; }
        .specs-grid { grid-template-columns:repeat(2,1fr); }
        .media-panels { height:230px; }
    }
</style>
@endpush

@section('content')
<div class="container detail-page">

    <a href="{{ route('shop') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Shop
    </a>

    <div class="detail-hero">

        {{-- ══ MEDIA CARD ══════════════════════════════════════════════════ --}}
        <div class="media-card">

            <div class="media-tabs">
                <button class="media-tab active" id="tab-image" onclick="switchTab('image')">
                    <i class="fas fa-image" style="margin-right:6px;"></i> Photo
                </button>
                <button class="media-tab" id="tab-video" onclick="switchTab('video')">
                    <i class="fas fa-play-circle" style="margin-right:6px;"></i> Video
                </button>
            </div>

            <div class="media-panels">

                {{-- Image --}}
                <div class="media-panel media-panel-img active" id="panel-image">
                    @if($car->image && file_exists(storage_path('app/public/cars/'.$car->image)))
                        <img src="{{ asset('storage/cars/'.$car->image) }}" alt="{{ $car->name }}">
                    @else
                        <div class="no-img-placeholder">
                            <i class="fas fa-car"></i>
                            <span>No photo uploaded</span>
                        </div>
                    @endif
                </div>

                {{-- Video
                     ══════════════════════════════════════════════════════
                     Add video at: public/videos/cars/{{ $car->id }}.mp4
                     It plays automatically when the Video tab is clicked.
                     ══════════════════════════════════════════════════════ --}}
                <div class="media-panel media-panel-video" id="panel-video">
                    @php $videoPath = public_path('videos/cars/'.$car->id.'.mp4'); $hasVideo = file_exists($videoPath); @endphp
                    @if($hasVideo)
                        <video id="carVideo" class="car-video" loop playsinline preload="metadata"
                               src="{{ asset('videos/cars/'.$car->id.'.mp4') }}">
                        </video>
                    @else
                        <div class="no-video-msg">
                            <i class="fas fa-film"></i>
                            <span>No video yet</span>
                            <small>Add: public/videos/cars/{{ $car->id }}.mp4</small>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Video controls --}}
            <div class="video-controls" id="videoControls">
                <button class="vc-btn" onclick="togglePlay()" title="Play/Pause">
                    <i class="fas fa-play" id="playIcon"></i>
                </button>
                <div class="vc-progress" id="progressBar" onclick="seekVideo(event)">
                    <div class="vc-progress-fill" id="progressFill"></div>
                </div>
                <span class="vc-time" id="timeDisplay">0:00 / 0:00</span>
                <button class="vc-btn" id="muteBtn" onclick="toggleMute()">
                    <i class="fas fa-volume-up" id="muteIcon"></i>
                </button>
                <button class="vc-btn" onclick="toggleFullscreen()" title="Fullscreen">
                    <i class="fas fa-expand"></i>
                </button>
            </div>

            <div class="media-footer">
                @if($car->is_available)
                    <div class="avail-badge avail-yes"><div class="dot"></div> Available Now</div>
                @else
                    <div class="avail-badge avail-no"><div class="dot"></div> Unavailable</div>
                @endif
                <span style="color:#2a2a2a; font-size:11px; letter-spacing:1px;">ID #{{ $car->id }}</span>
            </div>

        </div>

        {{-- ══ INFO CARD ═══════════════════════════════════════════════════ --}}
        <div class="info-card">
            <div class="car-badge"><i class="fas fa-horse"></i> Ferrari</div>
            <h1 class="car-name-large">{{ $car->name }}</h1>
            <p class="car-desc-text">
                {{ $car->description ?? 'A masterpiece of Italian engineering. Every detail crafted to deliver pure performance and unforgettable emotion.' }}
            </p>
            <div class="car-price-large">
                <span>From</span> ₱{{ number_format($car->price, 2) }}
            </div>
            <div class="detail-actions">
                @if($car->is_available)
                    @auth
                        <a href="{{ route('orders.create', $car) }}" class="btn btn-red">
                            <i class="fas fa-shopping-bag"></i>&nbsp; Buy Now
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-red">Login to Buy</a>
                    @endauth
                @endif
                <a href="{{ route('shop') }}" class="btn btn-outline">← Back</a>
            </div>
        </div>

    </div>

    {{-- ══ SPECS ══════════════════════════════════════════════════════════ --}}
    <div class="specs-section">
        <div class="section-head">Technical <span style="color:var(--ferrari-red);margin-left:8px;">Specs</span></div>
        <div class="specs-grid">
            @foreach([
                ['fas fa-tachometer-alt','320+','Top Speed (km/h)'],
                ['fas fa-bolt','710','Horsepower (hp)'],
                ['fas fa-clock','2.9s','0–100 km/h'],
                ['fas fa-cog','V8','Engine Type'],
                ['fas fa-road','3.9L','Displacement'],
                ['fas fa-weight-hanging','1,435','Weight (kg)'],
                ['fas fa-calendar-alt','2023','Release Year'],
                ['fas fa-gas-pump','11.4','Fuel (L/100km)'],
            ] as [$icon,$val,$lbl])
            <div class="spec-card">
                <div class="spec-icon"><i class="{{ $icon }}"></i></div>
                <div class="spec-value">{{ $val }}</div>
                <div class="spec-label">{{ $lbl }}</div>
            </div>
            @endforeach
        </div>

        <div class="section-head">Key <span style="color:var(--ferrari-red);margin-left:8px;">Highlights</span></div>
        <div class="highlights-grid">
            @foreach([
                ['fas fa-drafting-compass','Italian Craftsmanship','Hand-built at the Maranello factory with over 80 years of racing heritage.'],
                ['fas fa-shield-alt','Advanced Aerodynamics','Active aerodynamic elements automatically optimise downforce at high speed.'],
                ['fas fa-sliders-h','Manettino Dial','Ferrari\'s iconic steering-wheel dial switches between 5 drive modes instantly.'],
                ['fas fa-leaf','Hybrid Technology','Next-generation PHEV system delivers maximum performance with reduced emissions.'],
            ] as [$icon,$title,$desc])
            <div class="highlight-card">
                <div class="h-icon"><i class="{{ $icon }}"></i></div>
                <div><div class="h-title">{{ $title }}</div><div class="h-desc">{{ $desc }}</div></div>
            </div>
            @endforeach
        </div>

        <div class="cta-banner">
            <div>
                <h3>Ready to Own <span style="color:var(--ferrari-red)">This Ferrari?</span></h3>
                <p>Place your order today. Our team will contact you with delivery details.</p>
            </div>
            @if($car->is_available)
                @auth
                    <a href="{{ route('orders.create', $car) }}" class="btn btn-red" style="padding:14px 32px;">
                        Buy Now — ₱{{ number_format($car->price, 2) }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-red" style="padding:14px 32px;">Login to Purchase</a>
                @endauth
            @else
                <span style="color:#444; font-size:13px;">Currently Unavailable</span>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const video   = document.getElementById('carVideo');
const controls= document.getElementById('videoControls');

// ── Tab switcher ───────────────────────────────────────────────
function switchTab(tab) {
    ['image','video'].forEach(t => {
        document.getElementById('tab-'+t).classList.toggle('active', t===tab);
        document.getElementById('panel-'+t).classList.toggle('active', t===tab);
    });

    if (tab === 'video' && video) {
        controls.classList.add('visible');
        video.play().catch(()=>{});
        updatePlayIcon();
    } else {
        controls.classList.remove('visible');
        if (video) { video.pause(); updatePlayIcon(); }
    }
}

// ── Controls ───────────────────────────────────────────────────
function togglePlay() {
    if (!video) return;
    video.paused ? video.play() : video.pause();
    updatePlayIcon();
}
function updatePlayIcon() {
    if (!video) return;
    document.getElementById('playIcon').className =
        video.paused ? 'fas fa-play' : 'fas fa-pause';
}
function toggleMute() {
    if (!video) return;
    video.muted = !video.muted;
    document.getElementById('muteIcon').className =
        video.muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
}
function toggleFullscreen() {
    if (!video) return;
    document.fullscreenElement ? document.exitFullscreen() : video.requestFullscreen().catch(()=>{});
}
function seekVideo(e) {
    if (!video || !video.duration) return;
    const bar  = document.getElementById('progressBar');
    const pct  = (e.clientX - bar.getBoundingClientRect().left) / bar.offsetWidth;
    video.currentTime = Math.max(0, Math.min(1, pct)) * video.duration;
}
function fmt(s) {
    const m = Math.floor(s/60);
    return m + ':' + String(Math.floor(s%60)).padStart(2,'0');
}

if (video) {
    video.addEventListener('timeupdate', () => {
        if (!video.duration) return;
        document.getElementById('progressFill').style.width = (video.currentTime/video.duration*100)+'%';
        document.getElementById('timeDisplay').textContent = fmt(video.currentTime)+' / '+fmt(video.duration);
    });
    video.addEventListener('play',  updatePlayIcon);
    video.addEventListener('pause', updatePlayIcon);
    video.addEventListener('ended', updatePlayIcon);
}
</script>
@endpush