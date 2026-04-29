@extends('layouts.app')
@section('title', 'Home — Ferrari System')
@push('styles')
<style>
    /* ── HERO ── */
    .hero{
        position:relative;
        height:100vh; min-height:600px;
        /* Pull hero up behind the fixed navbar */
        margin-top:calc(-1 * var(--nav-h));
        padding-top:var(--nav-h);
        display:flex; align-items:center; justify-content:center;
        overflow:hidden; text-align:center;
    }
    .hero-video{
        position:absolute; inset:0; width:100%; height:100%;
        object-fit:cover; opacity:0.5; z-index:0;
        transition:opacity 0.6s ease;
    }
    .hero-overlay{
        position:absolute; inset:0;
        background:linear-gradient(to top,#0d0d0d 0%,rgba(13,13,13,0.25) 50%,transparent 100%);
        z-index:1;
    }
    .hero-content{position:relative;z-index:2;padding:0 24px}
    .hero-eyebrow{font-size:11px;letter-spacing:6px;text-transform:uppercase;color:var(--ferrari-red);margin-bottom:20px;animation:fadeUp 1s ease both}
    .hero-title{
        font-family:'Bebas Neue',sans-serif;
        font-size:clamp(56px,10vw,130px);
        letter-spacing:6px;line-height:0.9;margin-bottom:24px;
        animation:fadeUp 1s ease 0.15s both;
    }
    .hero-title span{color:var(--ferrari-red);display:block}
    .hero-sub{color:var(--gray);max-width:480px;margin:0 auto 40px;font-size:16px;line-height:1.8;animation:fadeUp 1s ease 0.3s both}
    .hero-cta{animation:fadeUp 1s ease 0.45s both;display:flex;gap:16px;justify-content:center;align-items:center}
    .hero-scroll{
        position:absolute;bottom:32px;left:50%;transform:translateX(-50%);z-index:2;
        color:var(--gray);font-size:10px;letter-spacing:4px;text-transform:uppercase;
        animation:bounce 2s infinite;
        display:flex;flex-direction:column;align-items:center;gap:8px;
    }
    .hero-scroll-line{width:1px;height:40px;background:linear-gradient(to bottom,var(--ferrari-red),transparent)}
    @keyframes fadeUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:translateY(0)}}
    @keyframes bounce{0%,100%{transform:translateX(-50%) translateY(0)}50%{transform:translateX(-50%) translateY(10px)}}

    /* Sound btn */
    .sound-btn{
        position:absolute;top:calc(var(--nav-h) + 16px);right:24px;z-index:10;
        background:rgba(0,0,0,0.55);border:1px solid rgba(220,0,0,0.35);
        color:var(--gray);width:42px;height:42px;border-radius:50%;
        display:flex;align-items:center;justify-content:center;
        cursor:pointer;font-size:14px;backdrop-filter:blur(8px);
        transition:all 0.3s ease;
    }
    .sound-btn:hover{border-color:var(--ferrari-red);color:var(--ferrari-red);transform:scale(1.1)}
    .sound-btn.active{background:rgba(220,0,0,0.2);border-color:var(--ferrari-red);color:var(--ferrari-red)}

    /* ── CARS SECTION ── */
    .cars-section{padding:100px 0}
    .section-header{display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:52px}

    .cars-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:28px}

    /* ── CAR CARD ── */
    .car-card{
        background:var(--dark2);border:1px solid #1e1e1e;border-radius:10px;
        overflow:hidden;cursor:pointer;
        transition:transform 0.45s cubic-bezier(.25,.8,.25,1),
                    border-color 0.35s ease,
                    box-shadow 0.45s ease;
    }
    .car-card:hover{
        transform:translateY(-8px) scale(1.025);
        border-color:var(--ferrari-red);
        box-shadow:0 24px 64px rgba(220,0,0,0.13);
    }

    /* Media */
    .car-media{position:relative;width:100%;height:210px;overflow:hidden;background:var(--dark3)}

    .car-media-img{
        position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:1;
        transition:opacity 0.5s ease, transform 0.6s cubic-bezier(.25,.8,.25,1);
        transform:scale(1);
    }
    .car-card:hover .car-media-img{opacity:0;transform:scale(1.06)}

    .car-media-placeholder{
        position:absolute;inset:0;display:flex;flex-direction:column;
        align-items:center;justify-content:center;gap:10px;z-index:1;
        background:linear-gradient(135deg,#1a1a1a,#222);
        transition:opacity 0.5s ease, transform 0.6s cubic-bezier(.25,.8,.25,1);
        transform:scale(1);
    }
    .car-media-placeholder i{font-size:36px;color:#2a2a2a}
    .car-media-placeholder span{font-size:11px;color:#333;letter-spacing:2px;text-transform:uppercase}
    .car-card:hover .car-media-placeholder{opacity:0;transform:scale(1.04)}

    .car-media-video{
        position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:2;
        opacity:0;transform:scale(1.04);
        transition:opacity 0.5s ease, transform 0.6s cubic-bezier(.25,.8,.25,1);
    }
    .car-card:hover .car-media-video{opacity:1;transform:scale(1.08)}

    /* Red sweep bar */
    .car-media::after{
        content:'';position:absolute;bottom:0;left:0;right:0;height:3px;
        background:var(--ferrari-red);transform:scaleX(0);transform-origin:left;
        transition:transform 0.4s ease;z-index:3;
    }
    .car-card:hover .car-media::after{transform:scaleX(1)}

    /* Info */
    .car-info{padding:20px 22px 22px}
    .car-name{
        font-family:'Bebas Neue',sans-serif;font-size:22px;letter-spacing:2px;margin-bottom:4px;
        transition:color 0.25s ease;
    }
    .car-card:hover .car-name{color:var(--ferrari-red)}
    .car-desc{color:var(--gray);font-size:12px;line-height:1.6;margin-bottom:12px}
    .car-price{color:var(--ferrari-red);font-size:20px;font-weight:700;margin-bottom:16px;display:flex;align-items:baseline;gap:4px}
    .car-price-label{font-size:11px;color:#555;font-weight:400}
    .car-actions{display:flex;gap:10px}

    /* Smooth btn */
    .btn{
        display:inline-flex;align-items:center;gap:6px;
        transition:background 0.25s ease, transform 0.2s ease,
                    box-shadow 0.25s ease, color 0.2s ease,
                    border-color 0.2s ease;
        position:relative;overflow:hidden;
    }
    .btn::after{
        content:'';position:absolute;inset:0;
        background:linear-gradient(120deg,transparent 30%,rgba(255,255,255,0.1) 50%,transparent 70%);
        transform:translateX(-100%);transition:transform 0.45s ease;
    }
    .btn:hover::after{transform:translateX(100%)}
    .btn:hover{transform:translateY(-2px)}
    .btn:active{transform:translateY(0)}
    .btn-red:hover{box-shadow:0 8px 22px rgba(220,0,0,0.3)}
    .btn-outline:hover{box-shadow:0 8px 22px rgba(220,0,0,0.2)}

    /* Reveal */
    .reveal{opacity:0;transform:translateY(24px);transition:opacity 0.6s ease,transform 0.6s ease}
    .reveal.visible{opacity:1;transform:translateY(0)}
</style>
@endpush

@section('content')

{{-- ── HERO ── --}}
<section class="hero">
    <video class="hero-video" id="heroVideo" autoplay muted loop playsinline>
        <source src="{{ asset('videos/ferrari.mp4') }}" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>

    <button class="sound-btn" id="soundBtn" title="Toggle sound">
        <i class="fas fa-volume-mute" id="soundIcon"></i>
    </button>

    <div class="hero-content">
        <p class="hero-eyebrow">Exclusive &nbsp;•&nbsp; Luxury &nbsp;•&nbsp; Performance</p>
        <h1 class="hero-title">THE PRANCING<span>HORSE</span></h1>
        <p class="hero-sub">Experience the pinnacle of Italian automotive engineering. Where passion meets performance.</p>
        <div class="hero-cta">
            <a href="#explore" class="btn btn-red"
               onclick="document.getElementById('explore').scrollIntoView({behavior:'smooth'});return false;">
                Explore Cars
            </a>
            <a href="{{ route('shop') }}" class="btn btn-outline">View All</a>
        </div>
    </div>

    <div class="hero-scroll">
        <div class="hero-scroll-line"></div>
        Scroll
    </div>
</section>

{{-- ── FEATURED CARS ── --}}
<section class="cars-section" id="explore">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-title reveal">Featured <span>Cars</span></p>
                <div class="section-divider"></div>
                <p class="section-subtitle reveal">Hover over a car to see it in action.</p>
            </div>
            <a href="{{ route('shop') }}" class="btn btn-outline reveal">View Full Collection</a>
        </div>

        <div class="cars-grid">
            @forelse($featuredCars as $car)
            <div class="car-card reveal" style="transition-delay:{{ $loop->index * 0.08 }}s">
                <div class="car-media">
                    @if($car->image && file_exists(storage_path('app/public/cars/'.$car->image)))
                        <img class="car-media-img"
                             src="{{ asset('storage/cars/'.$car->image) }}"
                             alt="{{ $car->name }}">
                    @else
                        <div class="car-media-placeholder">
                            <i class="fas fa-car"></i>
                            <span>No Image</span>
                        </div>
                    @endif

                    <video class="car-media-video car-hover-video"
                           muted loop playsinline preload="none"
                           data-src="{{ asset('videos/cars/'.$car->id.'.mp4') }}">
                    </video>
                </div>

                <div class="car-info">
                    <div class="car-name">{{ $car->name }}</div>
                    <div class="car-desc">{{ Str::limit($car->description, 70) }}</div>
                    <div class="car-price">
                        <span class="car-price-label">From</span>
                        ₱{{ number_format($car->price, 2) }}
                    </div>
                    <div class="car-actions">
                        <a href="{{ route('shop') }}" class="btn btn-outline btn-sm">Details</a>
                        @auth
                            <a href="{{ route('orders.create', $car) }}" class="btn btn-red btn-sm">Buy Now</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-red btn-sm">Buy Now</a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <p style="color:var(--gray);grid-column:1/-1;text-align:center;padding:60px 0">
                No cars available yet. Check back soon.
            </p>
            @endforelse
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// ── Sound toggle ───────────────────────────────────────────────
const heroVideo = document.getElementById('heroVideo');
const soundBtn  = document.getElementById('soundBtn');
const soundIcon = document.getElementById('soundIcon');
soundBtn.addEventListener('click', () => {
    heroVideo.muted = !heroVideo.muted;
    soundBtn.classList.toggle('active', !heroVideo.muted);
    soundIcon.className = heroVideo.muted ? 'fas fa-volume-mute' : 'fas fa-volume-high';
});

// ── Hover video lazy load ──────────────────────────────────────
document.querySelectorAll('.car-card').forEach(card => {
    const video = card.querySelector('.car-hover-video');
    if (!video) return;
    let loaded = false;
    card.addEventListener('mouseenter', () => {
        if (!loaded) {
            const src = video.dataset.src;
            if (src) {
                const s = document.createElement('source');
                s.src = src; s.type = 'video/mp4';
                video.appendChild(s); video.load();
            }
            loaded = true;
        }
        video.currentTime = 0;
        video.play().catch(() => {});
    });
    card.addEventListener('mouseleave', () => video.pause());
});

// ── Scroll reveal ──────────────────────────────────────────────
const observer = new IntersectionObserver(entries => {
    entries.forEach(el => {
        if (el.isIntersecting) { el.target.classList.add('visible'); observer.unobserve(el.target); }
    });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
@endpush