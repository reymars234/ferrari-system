@extends('layouts.app')
@section('title', 'About — Ferrari System')
@push('styles')
<style>
    /* ══════════════════════════════════════════════════════════════
       GLOBAL PAGE STYLES
    ══════════════════════════════════════════════════════════════ */
    .about-page { overflow-x: hidden; }

    /* Scroll reveal base */
    .reveal {
        opacity: 0;
        transform: translateY(48px);
        transition: opacity 0.8s cubic-bezier(.25,.8,.25,1),
                    transform 0.8s cubic-bezier(.25,.8,.25,1);
    }
    .reveal.from-left  { transform: translateX(-60px); }
    .reveal.from-right { transform: translateX(60px); }
    .reveal.scale-in   { transform: scale(0.9); }
    .reveal.visible {
        opacity: 1;
        transform: translate(0) scale(1);
    }

    /* Stagger children */
    .stagger .reveal:nth-child(1) { transition-delay: 0s; }
    .stagger .reveal:nth-child(2) { transition-delay: .12s; }
    .stagger .reveal:nth-child(3) { transition-delay: .24s; }
    .stagger .reveal:nth-child(4) { transition-delay: .36s; }
    .stagger .reveal:nth-child(5) { transition-delay: .48s; }

    /* ══════════════════════════════════════════════════════════════
       HERO BANNER
    ══════════════════════════════════════════════════════════════ */
    .about-hero {
        position: relative;
        height: 55vh; min-height: 420px;
        display: flex; align-items: center; justify-content: center;
        text-align: center; overflow: hidden;
    }
    .about-hero-bg {
        position: absolute; inset: 0;
        background: linear-gradient(135deg, #1a0000 0%, #0d0d0d 55%, #1a0505 100%);
    }
    /* Animated red diagonal lines */
    .about-hero-lines {
        position: absolute; inset: 0; overflow: hidden; opacity: .06;
    }
    .about-hero-lines::before,
    .about-hero-lines::after {
        content: '';
        position: absolute; left: -40%; right: -40%;
        height: 2px; background: var(--ferrari-red);
        transform-origin: center;
    }
    .about-hero-lines::before { top: 35%; transform: rotate(-12deg); }
    .about-hero-lines::after  { top: 62%; transform: rotate(-12deg); }

    .about-hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to bottom, transparent 40%, var(--dark) 100%);
    }
    .about-hero-content { position: relative; z-index: 2; padding: 0 24px; }
    .hero-eyebrow {
        font-size: 11px; letter-spacing: 6px; text-transform: uppercase;
        color: var(--ferrari-red); margin-bottom: 16px;
        animation: fadeUp 1s ease both;
    }
    .hero-title {
        font-family: 'Bebas Neue', sans-serif;
        font-size: clamp(52px, 8vw, 96px);
        letter-spacing: 6px; line-height: .9; margin-bottom: 20px;
        animation: fadeUp 1s ease .15s both;
    }
    .hero-title span { color: var(--ferrari-red); display: block; }
    .hero-sub {
        color: var(--gray); font-size: 15px; max-width: 520px;
        margin: 0 auto; line-height: 1.8;
        animation: fadeUp 1s ease .3s both;
    }
    @keyframes fadeUp { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }

    /* ══════════════════════════════════════════════════════════════
       QUICK STATS STRIP
    ══════════════════════════════════════════════════════════════ */
    .stats-strip {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1px; background: rgba(220,0,0,.12);
        border-top: 1px solid rgba(220,0,0,.15);
        border-bottom: 1px solid rgba(220,0,0,.15);
        margin-bottom: 0;
    }
    .stat-item {
        background: var(--dark); padding: 32px 24px; text-align: center;
        transition: background .3s ease;
    }
    .stat-item:hover { background: rgba(220,0,0,.04); }
    .stat-num {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 42px; letter-spacing: 2px; color: var(--ferrari-red);
        line-height: 1; margin-bottom: 6px;
    }
    .stat-lbl {
        font-size: 10px; letter-spacing: 3px; text-transform: uppercase;
        color: var(--gray);
    }

    /* ══════════════════════════════════════════════════════════════
       SECTION COMMON
    ══════════════════════════════════════════════════════════════ */
    .about-section { padding: 96px 0; }
    .about-section:nth-child(even) { background: rgba(220,0,0,.025); }

    .section-kicker {
        font-size: 10px; letter-spacing: 5px; text-transform: uppercase;
        color: var(--ferrari-red); margin-bottom: 12px; display: block;
    }
    .section-h {
        font-family: 'Bebas Neue', sans-serif;
        font-size: clamp(36px, 5vw, 54px);
        letter-spacing: 3px; line-height: 1; margin-bottom: 16px;
    }
    .section-h span { color: var(--ferrari-red); }
    .section-body {
        color: var(--gray); font-size: 15px; line-height: 1.9;
        max-width: 560px;
    }
    .red-rule {
        width: 48px; height: 3px; background: var(--ferrari-red);
        margin: 20px 0; border-radius: 2px;
    }

    /* ══════════════════════════════════════════════════════════════
       PERSON CARD (Founder / CEO)
    ══════════════════════════════════════════════════════════════ */
    .person-grid {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 40px; align-items: center;
    }
    .person-grid.reverse { direction: rtl; }
    .person-grid.reverse > * { direction: ltr; }

    /* Photo placeholder */
    .person-photo-wrap {
        position: relative; border-radius: 12px; overflow: hidden;
        aspect-ratio: 4/5; background: var(--dark2);
        border: 1px solid rgba(220,0,0,.15);
        box-shadow: 0 28px 72px rgba(0,0,0,.6);
    }
    .person-photo-wrap img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform .6s cubic-bezier(.25,.8,.25,1);
    }
    .person-photo-wrap:hover img { transform: scale(1.04); }

    /* Photo placeholder when no image yet */
    .person-photo-placeholder {
        width: 100%; height: 100%;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center; gap: 16px;
        color: #2a2a2a;
        min-height: 360px;
    }
    .person-photo-placeholder i { font-size: 64px; }
    .person-photo-placeholder span {
        font-size: 11px; letter-spacing: 2px; text-transform: uppercase;
        color: #333;
    }

    /* Photo caption badge */
    .person-badge {
        position: absolute; bottom: 16px; left: 16px;
        background: rgba(13,13,13,.9); border: 1px solid rgba(220,0,0,.3);
        border-radius: 6px; padding: 10px 16px; backdrop-filter: blur(10px);
    }
    .person-badge-name {
        font-family: 'Bebas Neue', sans-serif; font-size: 18px;
        letter-spacing: 2px; margin-bottom: 2px;
    }
    .person-badge-role {
        font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
        color: var(--ferrari-red);
    }

    /* Person info */
    .person-info {}
    .person-role-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(220,0,0,.08); border: 1px solid rgba(220,0,0,.25);
        color: var(--ferrari-red); font-size: 9px; font-weight: 700;
        letter-spacing: 2.5px; text-transform: uppercase;
        padding: 4px 12px; border-radius: 2px; margin-bottom: 16px;
    }
    .person-name {
        font-family: 'Bebas Neue', sans-serif;
        font-size: clamp(36px, 5vw, 58px);
        letter-spacing: 2px; line-height: 1; margin-bottom: 8px;
    }
    .person-dates {
        color: var(--ferrari-red); font-size: 13px;
        font-weight: 600; margin-bottom: 20px;
    }
    .person-bio {
        color: var(--gray); font-size: 14px; line-height: 1.9;
        margin-bottom: 24px;
    }

    /* Quote */
    .person-quote {
        border-left: 3px solid var(--ferrari-red); padding: 14px 20px;
        background: rgba(220,0,0,.04); border-radius: 0 6px 6px 0;
        margin-bottom: 24px;
        font-style: italic; color: var(--light);
        font-size: 15px; line-height: 1.7;
    }
    .person-quote cite {
        display: block; margin-top: 8px;
        font-style: normal; font-size: 11px;
        letter-spacing: 2px; text-transform: uppercase; color: var(--ferrari-red);
    }

    /* Facts chips */
    .fact-chips { display: flex; flex-wrap: wrap; gap: 8px; }
    .fact-chip {
        background: var(--dark2); border: 1px solid #2a2a2a;
        border-radius: 4px; padding: 6px 14px;
        font-size: 12px; color: var(--gray);
        display: flex; align-items: center; gap: 7px;
        transition: border-color .25s, color .25s;
    }
    .fact-chip:hover { border-color: rgba(220,0,0,.4); color: var(--light); }
    .fact-chip i { color: var(--ferrari-red); font-size: 11px; }

    /* ══════════════════════════════════════════════════════════════
       HEADQUARTERS SECTION
    ══════════════════════════════════════════════════════════════ */
    .hq-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 40px;
        align-items: start;
    }

    .hq-photo-wrap {
        position: relative; border-radius: 12px; overflow: hidden;
        aspect-ratio: 16/10; background: var(--dark2);
        border: 1px solid rgba(220,0,0,.15);
        box-shadow: 0 28px 72px rgba(0,0,0,.6);
    }
    .hq-photo-wrap img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform .6s cubic-bezier(.25,.8,.25,1);
    }
    .hq-photo-wrap:hover img { transform: scale(1.04); }
    .hq-photo-placeholder {
        width: 100%; height: 100%; min-height: 280px;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center; gap: 16px;
        color: #2a2a2a;
    }
    .hq-photo-placeholder i { font-size: 52px; }
    .hq-photo-placeholder span { font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #333; }

    /* Location pin */
    .hq-location {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(220,0,0,.08); border: 1px solid rgba(220,0,0,.2);
        border-radius: 4px; padding: 8px 16px;
        font-size: 13px; color: var(--light); margin-bottom: 20px;
    }
    .hq-location i { color: var(--ferrari-red); }

    /* HQ detail cards */
    .hq-details { display: flex; flex-direction: column; gap: 14px; margin-top: 24px; }
    .hq-detail-card {
        background: var(--dark2); border: 1px solid #1e1e1e;
        border-radius: 8px; padding: 18px 20px;
        display: flex; align-items: flex-start; gap: 16px;
        transition: border-color .3s, transform .3s;
    }
    .hq-detail-card:hover { border-color: rgba(220,0,0,.3); transform: translateX(4px); }
    .hq-detail-icon {
        width: 40px; height: 40px; flex-shrink: 0;
        background: rgba(220,0,0,.08); border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        color: var(--ferrari-red); font-size: 16px;
    }
    .hq-detail-title { font-weight: 700; font-size: 13px; margin-bottom: 3px; }
    .hq-detail-value { color: var(--gray); font-size: 13px; line-height: 1.5; }

    /* ══════════════════════════════════════════════════════════════
       TIMELINE (optional decorative)
    ══════════════════════════════════════════════════════════════ */
    .timeline { position: relative; padding-left: 32px; }
    .timeline::before {
        content: ''; position: absolute; left: 7px; top: 0; bottom: 0;
        width: 2px; background: linear-gradient(to bottom, var(--ferrari-red), rgba(220,0,0,.1));
    }
    .tl-item { position: relative; margin-bottom: 32px; }
    .tl-dot {
        position: absolute; left: -29px; top: 4px;
        width: 12px; height: 12px; border-radius: 50%;
        background: var(--ferrari-red);
        box-shadow: 0 0 0 3px rgba(220,0,0,.15);
    }
    .tl-year {
        font-family: 'Bebas Neue', sans-serif; font-size: 16px;
        letter-spacing: 2px; color: var(--ferrari-red); margin-bottom: 3px;
    }
    .tl-title { font-weight: 700; font-size: 14px; margin-bottom: 4px; }
    .tl-body  { color: var(--gray); font-size: 13px; line-height: 1.7; }

    /* ══════════════════════════════════════════════════════════════
       CTA BANNER
    ══════════════════════════════════════════════════════════════ */
    .about-cta {
        background: linear-gradient(135deg, rgba(220,0,0,.08) 0%, transparent 60%);
        border: 1px solid rgba(220,0,0,.18); border-radius: 16px;
        padding: 64px 48px; text-align: center; margin: 0 0 80px;
    }
    .about-cta h2 {
        font-family: 'Bebas Neue', sans-serif; font-size: 46px;
        letter-spacing: 4px; margin-bottom: 12px;
    }
    .about-cta p { color: var(--gray); font-size: 15px; margin-bottom: 32px; }

    /* ══════════════════════════════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════════════════════════════ */
    @media (max-width: 900px) {
        .person-grid, .hq-grid { grid-template-columns: 1fr; gap: 28px; }
        .person-grid.reverse { direction: ltr; }
        .person-photo-wrap { aspect-ratio: 16/9; }
        .about-cta { padding: 40px 24px; }
        .about-cta h2 { font-size: 34px; }
    }
    @media (max-width: 600px) {
        .about-section { padding: 64px 0; }
        .stats-strip { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')
<div class="about-page">

{{-- ══ HERO ════════════════════════════════════════════════════════════ --}}
<section class="about-hero">
    <div class="about-hero-bg"></div>
    <div class="about-hero-lines"></div>
    <div class="about-hero-overlay"></div>
    <div class="about-hero-content">
        <p class="hero-eyebrow">Est. 1939 · Maranello, Italy</p>
        <h1 class="hero-title">
            THE LEGEND OF
            <span>FERRARI</span>
        </h1>
        <p class="hero-sub">
            Where passion meets engineering. A story of ambition, racing heritage,
            and the relentless pursuit of perfection on four wheels.
        </p>
    </div>
</section>

{{-- ══ STATS STRIP ═════════════════════════════════════════════════════ --}}
<div class="stats-strip stagger">
    @foreach([
        ['85+', 'Years of Legacy'],
        ['13,000+', 'Cars Produced/Year'],
        ['236', 'Formula 1 Victories'],
        ['40+', 'Countries Served'],
        ['4,500+', 'Employees'],
    ] as $stat)
    <div class="stat-item reveal scale-in">
        <div class="stat-num">{{ $stat[0] }}</div>
        <div class="stat-lbl">{{ $stat[1] }}</div>
    </div>
    @endforeach
</div>

{{-- ══ FOUNDER ══════════════════════════════════════════════════════════ --}}
<section class="about-section">
    <div class="container">
        <div class="person-grid">

            {{-- Photo --}}
            <div class="reveal from-left">
                <div class="person-photo-wrap">
                    @if(file_exists(public_path('images/founder.jpg')))
                        <img src="{{ asset('images/founder.jpg') }}" alt="Enzo Ferrari">
                    @else
                        <div class="person-photo-placeholder">
                            <i class="fas fa-user-tie"></i>
                            <span>Founder Photo</span>
                            <span style="font-size:10px;color:#222">public/images/founder.jpg</span>
                        </div>
                    @endif
                    <div class="person-badge">
                        <div class="person-badge-name">Enzo Ferrari</div>
                        <div class="person-badge-role">Founder</div>
                    </div>
                </div>
            </div>

            {{-- Info --}}
            <div class="reveal from-right">
                <span class="section-kicker">The Founder</span>
                <div class="person-role-tag">
                    <i class="fas fa-horse"></i> Il Commendatore
                </div>
                <h2 class="person-name">Enzo<br>Ferrari</h2>
                <div class="person-dates">18 February 1898 — 14 August 1988</div>

                <p class="person-bio">
                    Born in Modena, Italy, Enzo Ferrari began his automotive career as a racing driver
                    before founding Scuderia Ferrari in 1929 as a racing team under Alfa Romeo.
                    In 1939, he established Auto Avio Costruzioni, which evolved into Ferrari S.p.A.
                    in 1947 — the company that would redefine what a sports car could be.
                </p>
                <p class="person-bio" style="margin-top:-8px">
                    His obsession was not profit — it was victory. He built road cars only to fund
                    his true passion: Formula 1 racing. Under his leadership, Ferrari became the
                    most successful constructor in the history of motorsport.
                </p>

                <div class="person-quote">
                    "Racing is a great mania to which one must sacrifice everything, without reticence, without hesitation."
                    <cite>— Enzo Ferrari</cite>
                </div>

                <div class="fact-chips stagger">
                    <div class="fact-chip reveal"><i class="fas fa-flag-checkered"></i> Founded Ferrari in 1947</div>
                    <div class="fact-chip reveal"><i class="fas fa-trophy"></i> 14 F1 Constructors' Titles</div>
                    <div class="fact-chip reveal"><i class="fas fa-map-marker-alt"></i> Modena, Italy</div>
                    <div class="fact-chip reveal"><i class="fas fa-car"></i> 125 S — First Ferrari Road Car</div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══ CEO ══════════════════════════════════════════════════════════════ --}}
<section class="about-section">
    <div class="container">
        <div class="person-grid reverse">

            {{-- Info --}}
            <div class="reveal from-left">
                <span class="section-kicker">Current Leadership</span>
                <div class="person-role-tag">
                    <i class="fas fa-crown"></i> Chief Executive Officer
                </div>
                <h2 class="person-name">Benedetto<br>Vigna</h2>
                <div class="person-dates">CEO since September 2021</div>

                <p class="person-bio">
                    Benedetto Vigna brings a background in semiconductor physics and technology
                    to Ferrari's top role. Previously President of STMicroelectronics' Analog,
                    MEMS and Sensors Group, Vigna is steering Ferrari's transformation into
                    a technology-led luxury powerhouse.
                </p>
                <p class="person-bio" style="margin-top:-8px">
                    Under his leadership, Ferrari is accelerating its electrification roadmap,
                    with the first fully electric Ferrari — the 2025 Ferrari Elettrica —
                    set to redefine what a sports car can be in the modern era.
                </p>

                <div class="person-quote">
                    "We must be a technology company first, a luxury brand second — and a Ferrari always."
                    <cite>— Benedetto Vigna</cite>
                </div>

                <div class="fact-chips stagger">
                    <div class="fact-chip reveal"><i class="fas fa-microchip"></i> Physics &amp; Tech Background</div>
                    <div class="fact-chip reveal"><i class="fas fa-bolt"></i> Leading Electrification</div>
                    <div class="fact-chip reveal"><i class="fas fa-chart-line"></i> Record Revenue in 2023</div>
                    <div class="fact-chip reveal"><i class="fas fa-globe"></i> Global Luxury Strategy</div>
                </div>
            </div>

            {{-- Photo --}}
            <div class="reveal from-right">
                <div class="person-photo-wrap">
                    @if(file_exists(public_path('images/ceo.jpg')))
                        <img src="{{ asset('images/ceo.jpg') }}" alt="Benedetto Vigna">
                    @else
                        <div class="person-photo-placeholder">
                            <i class="fas fa-user-tie"></i>
                            <span>CEO Photo</span>
                            <span style="font-size:10px;color:#222">public/images/ceo.jpg</span>
                        </div>
                    @endif
                    <div class="person-badge">
                        <div class="person-badge-name">Benedetto Vigna</div>
                        <div class="person-badge-role">CEO — Since 2021</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══ HEADQUARTERS ════════════════════════════════════════════════════ --}}
<section class="about-section">
    <div class="container">

        <div style="text-align:center;margin-bottom:52px" class="reveal">
            <span class="section-kicker">Where the Legend Lives</span>
            <h2 class="section-h" style="justify-content:center;display:flex;gap:12px">
                The <span>Headquarters</span>
            </h2>
            <div class="red-rule" style="margin:16px auto"></div>
            <p class="section-body" style="margin:0 auto;text-align:center">
                Maranello, a small town in the Province of Modena, Italy —
                where every Ferrari is still hand-built today.
            </p>
        </div>

        <div class="hq-grid">

            {{-- Photo --}}
            <div class="reveal from-left">
                <div class="hq-photo-wrap">
                    @if(file_exists(public_path('images/headquarters.jpg')))
                        <img src="{{ asset('images/headquarters.jpg') }}" alt="Ferrari Headquarters">
                    @else
                        <div class="hq-photo-placeholder">
                            <i class="fas fa-building"></i>
                            <span>HQ Photo</span>
                            <span style="font-size:10px;color:#222">public/images/headquarters.jpg</span>
                        </div>
                    @endif
                </div>

                {{-- Second HQ image slot --}}
                <div class="hq-photo-wrap" style="margin-top:16px;aspect-ratio:16/7">
                    @if(file_exists(public_path('images/factory.jpg')))
                        <img src="{{ asset('images/factory.jpg') }}" alt="Ferrari Factory">
                    @else
                        <div class="hq-photo-placeholder">
                            <i class="fas fa-industry"></i>
                            <span>Factory / Fiorano Track Photo</span>
                            <span style="font-size:10px;color:#222">public/images/factory.jpg</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- HQ Details --}}
            <div class="reveal from-right">
                <div class="hq-location">
                    <i class="fas fa-map-marker-alt"></i>
                    Via Abetone Inferiore, 4 — Maranello, MO 41053, Italy
                </div>

                <p class="section-body" style="margin-bottom:28px">
                    The Ferrari Headquarters has been in Maranello since 1943.
                    The campus includes the Gestione Sportiva (racing division),
                    the main factory, the Fiorano Circuit test track, and the
                    Museo Ferrari. Every car that wears the Prancing Horse badge
                    is assembled here by hand.
                </p>

                <div class="hq-details stagger">
                    <div class="hq-detail-card reveal">
                        <div class="hq-detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <div class="hq-detail-title">Location</div>
                            <div class="hq-detail-value">Maranello, Province of Modena, Italy<br>Emilia-Romagna Region</div>
                        </div>
                    </div>
                    <div class="hq-detail-card reveal">
                        <div class="hq-detail-icon"><i class="fas fa-industry"></i></div>
                        <div>
                            <div class="hq-detail-title">Main Factory</div>
                            <div class="hq-detail-value">Founded in 1943 · 40+ hectares<br>Produces ~13,000 cars per year</div>
                        </div>
                    </div>
                    <div class="hq-detail-card reveal">
                        <div class="hq-detail-icon"><i class="fas fa-road"></i></div>
                        <div>
                            <div class="hq-detail-title">Fiorano Circuit</div>
                            <div class="hq-detail-value">Private 3 km test track<br>Used for road car development &amp; F1 testing</div>
                        </div>
                    </div>
                    <div class="hq-detail-card reveal">
                        <div class="hq-detail-icon"><i class="fas fa-landmark"></i></div>
                        <div>
                            <div class="hq-detail-title">Museo Ferrari</div>
                            <div class="hq-detail-value">On-site museum housing iconic race cars<br>Over 300,000 visitors per year</div>
                        </div>
                    </div>
                    <div class="hq-detail-card reveal">
                        <div class="hq-detail-icon"><i class="fas fa-phone"></i></div>
                        <div>
                            <div class="hq-detail-title">Contact</div>
                            <div class="hq-detail-value">+39 0536 949111<br>info@ferrari.com</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══ TIMELINE ════════════════════════════════════════════════════════ --}}
<section class="about-section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:start">

            <div class="reveal from-left">
                <span class="section-kicker">Our History</span>
                <h2 class="section-h">Key <span>Milestones</span></h2>
                <div class="red-rule"></div>
                <p class="section-body">
                    From a humble racing team in 1929 to the world's most iconic
                    automotive brand — every decade marked by achievement.
                </p>
            </div>

            <div class="timeline reveal from-right">
                @foreach([
                    ['1929', 'Scuderia Ferrari Founded', 'Enzo Ferrari establishes his racing team under Alfa Romeo in Modena.'],
                    ['1947', 'First Ferrari Road Car', 'The Ferrari 125 S, powered by a 1.5L V12, becomes the first car to bear the Ferrari name.'],
                    ['1950', 'F1 World Championship Debut', 'Ferrari enters the inaugural Formula 1 World Championship season.'],
                    ['1960s', 'Iconic Road Cars Era', 'The 250 GTO, 275 GTB, and Dino define Ferrari\'s golden age of design.'],
                    ['1988', 'Enzo Ferrari Passes', 'The founder passes away, but the prancing horse continues his legacy.'],
                    ['2002', 'Five Consecutive F1 Titles', 'Michael Schumacher and Ferrari dominate with 5 straight constructors\' championships.'],
                    ['2021', 'New CEO — Benedetto Vigna', 'Technology leader appointed to guide Ferrari into the electric era.'],
                    ['2025', 'First Electric Ferrari', 'The Ferrari Elettrica marks the brand\'s entry into full electrification.'],
                ] as $event)
                <div class="tl-item reveal">
                    <div class="tl-dot"></div>
                    <div class="tl-year">{{ $event[0] }}</div>
                    <div class="tl-title">{{ $event[1] }}</div>
                    <div class="tl-body">{{ $event[2] }}</div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</section>

{{-- ══ CTA ═════════════════════════════════════════════════════════════ --}}
<div class="container">
    <div class="about-cta reveal scale-in">
        <h2>Experience the <span style="color:var(--ferrari-red)">Prancing Horse</span></h2>
        <p>Browse our exclusive Ferrari collection and find your dream car today.</p>
        <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap">
            <a href="{{ route('shop') }}" class="btn btn-red" style="padding:14px 36px;font-size:14px">
                <i class="fas fa-car"></i> &nbsp;Explore Collection
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline" style="padding:14px 36px;font-size:14px">
                <i class="fas fa-envelope"></i> &nbsp;Contact Us
            </a>
        </div>
    </div>
</div>

</div>{{-- /.about-page --}}
@endsection

@push('scripts')
<script>
// ══════════════════════════════════════════════════════════════
//  SMOOTH SCROLL REVEAL  (IntersectionObserver)
// ══════════════════════════════════════════════════════════════
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            revealObserver.unobserve(entry.target);
        }
    });
}, {
    threshold: 0.1,
    rootMargin: '0px 0px -60px 0px',
});

document.querySelectorAll('.reveal').forEach(el => {
    revealObserver.observe(el);
});

// ══════════════════════════════════════════════════════════════
//  ANIMATED NUMBER COUNTER (for stats strip)
// ══════════════════════════════════════════════════════════════
const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el   = entry.target;
        const raw  = el.dataset.target;
        if (!raw) return;

        const isPlus  = raw.includes('+');
        const target  = parseInt(raw.replace(/[^0-9]/g, ''), 10);
        const suffix  = raw.replace(/[0-9]/g, '');
        let   current = 0;
        const step    = Math.ceil(target / 60);

        const tick = setInterval(() => {
            current = Math.min(current + step, target);
            el.textContent = current.toLocaleString() + suffix;
            if (current >= target) clearInterval(tick);
        }, 16);

        counterObserver.unobserve(el);
    });
}, { threshold: 0.5 });

document.querySelectorAll('.stat-num').forEach(el => {
    el.dataset.target = el.textContent;
    counterObserver.observe(el);
});
</script>
@endpush