@extends('layouts.app')
@section('title', 'Shop — Ferrari Collection')
@push('styles')
<style>
    *, *::before, *::after { box-sizing: border-box; }
   
    .shop-bg {
        position: fixed; inset: 0; z-index: 0;
        background-image: url('{{ asset("images/shop-bg.jpg") }}');
        background-size: cover; background-position: center; background-attachment: fixed;
        filter: blur(3px) brightness(0.35);
        transform: scale(1.05); will-change: transform;
    }
    .shop-bg-overlay { position: fixed; inset: 0; z-index: 1; background: rgba(6,6,6,0.75); }
    .shop-page { position: relative; z-index: 2; }

    /* ── HERO ── */
    .shop-hero { padding: 56px 0 32px; text-align: center; }
    .shop-logo-wrap { margin-bottom: 16px; animation: fadeDown .5s cubic-bezier(.22,1,.36,1) both; }
    @keyframes fadeDown { from{opacity:0;transform:translateY(-12px)} to{opacity:1;transform:translateY(0)} }
    .shop-logo-img {
        height: 60px; width: auto;
        filter: drop-shadow(0 0 8px rgba(220,0,0,.25));
        transition: filter .3s ease, transform .3s ease; will-change: transform, filter;
    }
    .shop-logo-img:hover { filter: drop-shadow(0 0 18px rgba(220,0,0,.55)); transform: scale(1.05); }
    .shop-logo-fallback {
        display: inline-flex; align-items: center; gap: 10px;
        color: var(--ferrari-red); font-family: 'Bebas Neue', sans-serif; font-size: 13px; letter-spacing: 4px;
    }
    .shop-hero .section-title { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) .08s both; }
    .shop-hero .section-divider { margin: 12px auto; }
    @keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

    /* ── FILTER BUTTONS ── */
    .filter-wrap {
        display: flex; align-items: center; justify-content: center;
        flex-wrap: wrap; gap: 10px; margin-bottom: 36px;
        animation: fadeUp .5s cubic-bezier(.22,1,.36,1) .12s both;
    }
    .filter-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 20px; border-radius: 6px;
        font-family: inherit; font-weight: 700;
        font-size: 11px; letter-spacing: 2px; text-transform: uppercase;
        cursor: pointer; border: 1px solid #252525;
        background: rgba(20,20,20,0.85); color: var(--gray);
        backdrop-filter: blur(8px);
        transition: border-color .2s ease, color .2s ease,
                    background .2s ease, transform .2s ease, box-shadow .2s ease;
        will-change: transform; position: relative; overflow: hidden;
    }
    .filter-btn::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(110deg,transparent 30%,rgba(255,255,255,.06) 50%,transparent 70%);
        transform: translateX(-100%); transition: transform .4s ease;
    }
    .filter-btn:hover::after { transform: translateX(100%); }
    .filter-btn:hover {
        border-color: rgba(220,0,0,.45); color: var(--light);
        transform: translateY(-2px); box-shadow: 0 8px 22px rgba(220,0,0,.12);
    }
    .filter-btn.active {
        border-color: var(--ferrari-red); color: #fff;
        background: var(--ferrari-red);
        box-shadow: 0 6px 20px rgba(220,0,0,.32); transform: translateY(-2px);
    }

    /* ── RESULT BAR ── */
    .result-bar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px; padding: 12px 0 22px;
        border-bottom: 1px solid rgba(220,0,0,.1); margin-bottom: 30px;
        transition: opacity .25s ease;
    }
    .result-bar.loading { opacity: .4; pointer-events: none; }
    .result-count { color: var(--gray); font-size: 11px; letter-spacing: 2px; text-transform: uppercase; }
    .result-count strong { color: var(--ferrari-red); }
    .result-label { font-family: 'Bebas Neue', sans-serif; font-size: 18px; letter-spacing: 2px; color: var(--ferrari-red); }

    /* ── GRID ── */
    .cars-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
        gap: 24px; padding-bottom: 80px; position: relative;
    }

    /* ── SPINNER ── */
    .grid-spinner {
        position: absolute; inset: 0; z-index: 10;
        display: flex; align-items: flex-start; justify-content: center; padding-top: 80px;
        pointer-events: none; opacity: 0; transition: opacity .2s ease;
    }
    .grid-spinner.visible { opacity: 1; }
    .spinner-ring {
        width: 44px; height: 44px; border-radius: 50%;
        border: 3px solid rgba(220,0,0,.15); border-top-color: var(--ferrari-red);
        animation: spin .7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── CAR CARD ── */
    .car-card {
        background: rgba(18,18,18,0.88); border: 1px solid rgba(220,0,0,.08);
        border-radius: 10px; overflow: hidden; cursor: pointer;
        backdrop-filter: blur(10px);
        opacity: 0; transform: translateY(28px);
        transition: opacity .5s cubic-bezier(.22,1,.36,1),
                    transform .5s cubic-bezier(.22,1,.36,1),
                    border-color .25s ease, box-shadow .3s ease;
        will-change: opacity, transform, box-shadow;
    }
    .car-card.visible { opacity: 1; transform: translateY(0); }
    .car-card:hover {
        transform: translateY(-6px) scale(1.018) !important;
        border-color: rgba(220,0,0,.55);
        box-shadow: 0 20px 50px rgba(220,0,0,.14);
    }

    /* ── MEDIA ── */
    .car-media { position: relative; width: 100%; height: 210px; overflow: hidden; background: #090909; }

    .car-media-img {
        position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;
        opacity: 0; transform: translateY(12px) scale(1.04);
        transition: opacity .5s cubic-bezier(.22,1,.36,1), transform .5s cubic-bezier(.22,1,.36,1);
        will-change: opacity, transform;
    }
    .car-card.visible .car-media-img { opacity: 1; transform: translateY(0) scale(1); }
    .car-card:hover .car-media-img   { opacity: 0; transform: translateY(-4px) scale(1.06); }

    .car-media-placeholder {
        position: absolute; inset: 0; display: flex; flex-direction: column;
        align-items: center; justify-content: center; gap: 8px; z-index: 1;
        background: linear-gradient(135deg,#0f0f0f,#181818); transition: opacity .3s ease;
    }
    .car-media-placeholder i { font-size: 32px; color: #252525; }
    .car-media-placeholder span { font-size: 11px; color: #2e2e2e; letter-spacing: 2px; text-transform: uppercase; }
    .car-card:hover .car-media-placeholder { opacity: 0; }

    .car-media-video {
        position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 2;
        opacity: 0; transform: scale(1.03);
        transition: opacity .35s ease, transform .35s cubic-bezier(.22,1,.36,1);
        will-change: opacity, transform;
    }
    .car-card:hover .car-media-video { opacity: 1; transform: scale(1.06); }

    .car-media::after {
        content: ''; position: absolute; bottom: 0; left: 0; right: 0;
        height: 2px; background: var(--ferrari-red);
        transform: scaleX(0); transform-origin: left;
        transition: transform .3s cubic-bezier(.22,1,.36,1); z-index: 5;
    }
    .car-card:hover .car-media::after { transform: scaleX(1); }

    /* Price tag */
    .price-tag {
        position: absolute; bottom: 10px; left: 10px; z-index: 4;
        background: rgba(8,8,8,.9); border: 1px solid rgba(220,0,0,.25);
        border-radius: 3px; padding: 4px 10px;
        font-size: 13px; font-weight: 700; color: var(--ferrari-red);
        backdrop-filter: blur(6px);
        opacity: .8; transform: translateY(3px);
        transition: opacity .25s ease, transform .25s ease;
    }
    .car-card:hover .price-tag { opacity: 1; transform: translateY(0); }

    /* ── RARITY BADGE ── */
    .rarity-badge {
        position: absolute; top: 10px; left: 10px; z-index: 4;
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 9px; border-radius: 3px;
        font-size: 9px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase;
        backdrop-filter: blur(6px); border: 1px solid;
    }
    .rarity-badge i { font-size: 8px; }

    /* Cart button */
    .cart-btn {
        position: absolute; top: 10px; right: 10px; z-index: 4;
        background: rgba(8,8,8,.9); border: 1px solid rgba(220,0,0,.25);
        border-radius: 50%; width: 34px; height: 34px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; cursor: pointer; backdrop-filter: blur(6px);
        transition: background .2s ease, border-color .2s ease, transform .2s ease; will-change: transform;
    }
    .cart-btn:hover { background: rgba(220,0,0,.9); border-color: var(--ferrari-red); transform: scale(1.1); }
    .cart-btn i { font-size: 12px; pointer-events: none; }

    /* ── INFO ── */
    .car-info { padding: 18px 20px 20px; }
    .car-name {
        font-family: 'Bebas Neue', sans-serif; font-size: 21px;
        letter-spacing: 2px; margin-bottom: 4px; transition: color .2s ease;
    }
    .car-card:hover .car-name { color: var(--ferrari-red); }
    .car-desc { color: var(--gray); font-size: 12px; line-height: 1.65; margin-bottom: 14px; }
    .car-actions { display: flex; gap: 8px; flex-wrap: wrap; }

    .btn {
        display: inline-flex; align-items: center; gap: 6px;
        transition: background .2s ease, transform .2s ease, box-shadow .2s ease;
        will-change: transform; position: relative; overflow: hidden;
    }
    .btn::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(110deg,transparent 30%,rgba(255,255,255,.08) 50%,transparent 70%);
        transform: translateX(-100%); transition: transform .4s ease;
    }
    .btn:hover::after { transform: translateX(100%); }
    .btn:hover { transform: translateY(-2px); }
    .btn:active { transform: translateY(0) scale(.98); }
    .btn-red:hover     { box-shadow: 0 6px 18px rgba(220,0,0,.28); }
    .btn-outline:hover { box-shadow: 0 6px 18px rgba(220,0,0,.15); }

    .empty-state { grid-column: 1/-1; text-align: center; padding: 80px 0; color: var(--gray); }
    .empty-state i { font-size: 44px; color: #1e1e1e; margin-bottom: 14px; display: block; }
</style>
@endpush

@section('content')

<div class="shop-bg"></div>
<div class="shop-bg-overlay"></div>

<div class="shop-page">
<div class="container">

    <div class="shop-hero">
        <div class="shop-logo-wrap">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="shop-logo-img">
            @else
                <div class="shop-logo-fallback"><i class="fas fa-horse"></i> Ferrari System</div>
            @endif
        </div>
        <p class="section-title">Ferrari <span>Collection</span></p>
        <div class="section-divider"></div>
    </div>

    @php
        $filterDefs = [
            'latest'    => ['icon' => 'fa-bolt',   'label' => 'Latest'],
            'cheapest'  => ['icon' => 'fa-tag',    'label' => 'Cheapest'],
            'expensive' => ['icon' => 'fa-gem',    'label' => 'Most Expensive'],
            'rarest'    => ['icon' => 'fa-crown',  'label' => 'Rarest'],
            'iconic'    => ['icon' => 'fa-horse',  'label' => 'Iconic'],
            'featured'  => ['icon' => 'fa-star',   'label' => 'Featured'],
        ];
    @endphp

    <div class="filter-wrap" id="filterWrap">
        @foreach($filterDefs as $key => $f)
            <button class="filter-btn {{ $filter === $key ? 'active' : '' }}"
                    data-filter="{{ $key }}"
                    onclick="applyFilter('{{ $key }}', this)">
                <i class="fas {{ $f['icon'] }}"></i>
                <span>{{ $f['label'] }}</span>
            </button>
        @endforeach
    </div>

    <div class="result-bar" id="resultBar">
        <span class="result-count">
            Showing <strong id="resultCount">{{ $cars->total() }}</strong> models
        </span>
        <span class="result-label" id="resultLabel">
            {{ $filterDefs[$filter]['label'] ?? 'Latest' }}
        </span>
    </div>

    <div style="position:relative">
        <div class="grid-spinner" id="gridSpinner"><div class="spinner-ring"></div></div>

        <div class="cars-grid" id="carsGrid">
            @forelse($cars as $car)
            @php
                $rarities = \App\Models\Car::RARITIES;
                $rColor   = $rarities[$car->rarity]['color'] ?? '#888';
                $rIcon    = $rarities[$car->rarity]['icon']  ?? 'fa-car';
                $rLabel   = $rarities[$car->rarity]['label'] ?? 'Common';
            @endphp
            <div class="car-card" data-id="{{ $car->id }}">
                <div class="car-media">

                    @if($car->image && file_exists(storage_path('app/public/cars/'.$car->image)))
                        <img class="car-media-img"
                             src="{{ asset('storage/cars/'.$car->image) }}"
                             alt="{{ $car->name }}" loading="lazy">
                    @else
                        <div class="car-media-placeholder">
                            <i class="fas fa-car"></i>
                            <span>{{ $car->name }}</span>
                        </div>
                    @endif

                    <video class="car-media-video car-hover-video"
                           muted loop playsinline preload="none"
                           data-src="{{ asset('videos/cars/'.$car->id.'.mp4') }}">
                    </video>

                    @if($car->rarity !== 'common')
                    <div class="rarity-badge"
                         style="color:{{ $rColor }};border-color:{{ $rColor }}44;background:{{ $rColor }}1a">
                        <i class="fas {{ $rIcon }}"></i> {{ $rLabel }}
                    </div>
                    @endif

                    <div class="price-tag">₱{{ number_format($car->price, 2) }}</div>

                    @auth
                    <form method="POST" action="{{ route('cart.add') }}"
                          style="position:absolute;top:10px;right:10px;z-index:4;margin:0">
                        @csrf
                        <input type="hidden" name="car_id" value="{{ $car->id }}">
                        <button type="submit" class="cart-btn" title="Add to Cart">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </form>
                    @endauth

                </div>

                <div class="car-info">
                    <div class="car-name">{{ $car->name }}</div>
                    <div class="car-desc">{{ Str::limit($car->description, 80) }}</div>
                    <div class="car-actions">
                        <a href="{{ route('cars.show', $car) }}" class="btn btn-outline btn-sm">Details</a>
                        @auth
                            <a href="{{ route('orders.create', $car) }}" class="btn btn-red btn-sm">Buy Now</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-red btn-sm">Login to Buy</a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-car"></i>
                <p>No cars available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div style="padding-bottom:60px" id="pagWrap">{{ $cars->links() }}</div>

</div>
</div>
@endsection

@push('scripts')
<script>
const IS_AUTH     = {{ auth()->check() ? 'true' : 'false' }};
const SHOP_URL    = '{{ route("shop") }}';
const CSRF        = '{{ csrf_token() }}';
const FILTER_DEFS = {
    latest:    { icon: 'fa-bolt',  label: 'Latest' },
    cheapest:  { icon: 'fa-tag',   label: 'Cheapest' },
    expensive: { icon: 'fa-gem',   label: 'Most Expensive' },
    rarest:    { icon: 'fa-crown', label: 'Rarest' },
    iconic:    { icon: 'fa-horse', label: 'Iconic' },
    featured:  { icon: 'fa-star',  label: 'Featured' },
};

let currentFilter = '{{ $filter }}';
let isLoading     = false;

/* ── Smooth fade + slide-up via IntersectionObserver ── */
function observeCards(container) {
    const cards = [...container.querySelectorAll('.car-card:not(.visible)')];
    if (!cards.length) return;
    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const idx = cards.indexOf(entry.target);
            setTimeout(() => entry.target.classList.add('visible'), Math.max(idx, 0) * 55);
            io.unobserve(entry.target);
        });
    }, { threshold: 0.07, rootMargin: '0px 0px -20px 0px' });
    cards.forEach(c => io.observe(c));
}
observeCards(document.getElementById('carsGrid'));

/* ── Hover video lazy-load ── */
function bindHoverVideos(container) {
    container.querySelectorAll('.car-hover-video').forEach(video => {
        if (video._bound) return;
        video._bound = true;
        const card = video.closest('.car-card');
        let loaded = false;
        card.addEventListener('mouseenter', () => {
            if (!loaded) {
                const src = video.dataset.src;
                if (src) { const s = document.createElement('source'); s.src = src; s.type = 'video/mp4'; video.appendChild(s); video.load(); }
                loaded = true;
            }
            video.currentTime = 0;
            video.play().catch(() => {});
        }, { passive: true });
        card.addEventListener('mouseleave', () => video.pause(), { passive: true });
    });
}
bindHoverVideos(document.getElementById('carsGrid'));

/* ── HTML escape ── */
function esc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/* ── Build card from AJAX JSON ── */
function buildCard(car) {
    const imgHtml = car.image
        ? `<img class="car-media-img" src="${car.image}" alt="${esc(car.name)}" loading="lazy">`
        : `<div class="car-media-placeholder"><i class="fas fa-car"></i><span>${esc(car.name.substring(0,20))}</span></div>`;

    const badge = (car.rarity && car.rarity !== 'common')
        ? `<div class="rarity-badge" style="color:${car.rarity_color};border-color:${car.rarity_color}44;background:${car.rarity_color}1a">
               <i class="fas ${car.rarity_icon}"></i> ${esc(car.rarity_label)}
           </div>` : '';

    const cartBtn = IS_AUTH
        ? `<form method="POST" action="/cart/add" style="position:absolute;top:10px;right:10px;z-index:4;margin:0">
               <input type="hidden" name="_token" value="${CSRF}">
               <input type="hidden" name="car_id" value="${car.id}">
               <button type="submit" class="cart-btn" title="Add to Cart"><i class="fas fa-shopping-cart"></i></button>
           </form>` : '';

    const buyBtn = IS_AUTH
        ? `<a href="${car.buy_url}" class="btn btn-red btn-sm">Buy Now</a>`
        : `<a href="${car.buy_url}" class="btn btn-red btn-sm">Login to Buy</a>`;

    const div = document.createElement('div');
    div.className = 'car-card';
    div.dataset.id = car.id;
    div.innerHTML = `
        <div class="car-media">
            ${imgHtml}
            <video class="car-media-video car-hover-video" muted loop playsinline preload="none" data-src="${car.video_url}"></video>
            ${badge}
            <div class="price-tag">₱${car.price_fmt}</div>
            ${cartBtn}
        </div>
        <div class="car-info">
            <div class="car-name">${esc(car.name)}</div>
            <div class="car-desc">${esc((car.description||'').substring(0,80))}</div>
            <div class="car-actions">
                <a href="${car.detail_url}" class="btn btn-outline btn-sm">Details</a>
                ${buyBtn}
            </div>
        </div>`;
    return div;
}

/* ── Apply filter via AJAX ── */
async function applyFilter(filter, btnEl) {
    if (isLoading || filter === currentFilter) return;
    isLoading = true;

    const grid    = document.getElementById('carsGrid');
    const spinner = document.getElementById('gridSpinner');
    const bar     = document.getElementById('resultBar');
    const pagWrap = document.getElementById('pagWrap');

    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btnEl.classList.add('active');

    // Animate out
    const existing = [...grid.querySelectorAll('.car-card')];
    existing.forEach((c, i) => {
        c.style.transition = `opacity .2s ease ${i * 22}ms, transform .2s ease ${i * 22}ms`;
        c.style.opacity    = '0';
        c.style.transform  = 'translateY(-10px) scale(.97)';
    });
    setTimeout(() => spinner.classList.add('visible'), 80);
    bar.classList.add('loading');
    await new Promise(r => setTimeout(r, existing.length * 22 + 200));
    grid.innerHTML = '';

    try {
        const res  = await fetch(`${SHOP_URL}?filter=${filter}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        });
        const data = await res.json();

        history.pushState({}, '', `?filter=${filter}`);
        currentFilter = filter;

        document.getElementById('resultCount').textContent = data.pagination.total;
        document.getElementById('resultLabel').textContent  = FILTER_DEFS[filter]?.label || filter;

        spinner.classList.remove('visible');
        bar.classList.remove('loading');
        pagWrap.innerHTML = '';

        if (!data.cars.length) {
            grid.innerHTML = `<div class="empty-state"><i class="fas fa-car"></i><p>No cars found.</p></div>`;
        } else {
            data.cars.forEach(car => grid.appendChild(buildCard(car)));
            bindHoverVideos(grid);
            requestAnimationFrame(() => observeCards(grid));
        }
    } catch (err) {
        console.error(err);
        spinner.classList.remove('visible');
        bar.classList.remove('loading');
        grid.innerHTML = `<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Something went wrong. Please try again.</p></div>`;
    }
    isLoading = false;
}

/* ── Keyboard shortcuts 1–6 ── */
const filterKeys = Object.keys(FILTER_DEFS);
document.addEventListener('keydown', e => {
    if (['INPUT','TEXTAREA'].includes(e.target.tagName)) return;
    const idx = parseInt(e.key) - 1;
    if (idx >= 0 && idx < filterKeys.length) {
        const btn = document.querySelector(`.filter-btn[data-filter="${filterKeys[idx]}"]`);
        if (btn) applyFilter(filterKeys[idx], btn);
    }
});
</script>
@endpush