@extends('layouts.app')
@section('title', 'Contact — Ferrari System')

@push('styles')
<style>
    /* ══════════════════════════════════════════════════════════════
       CONTACT PAGE
    ══════════════════════════════════════════════════════════════ */

    /* Scroll reveal */
    .c-reveal {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity .75s cubic-bezier(.25,.8,.25,1),
                    transform .75s cubic-bezier(.25,.8,.25,1);
    }
    .c-reveal.from-left  { transform: translateX(-50px); }
    .c-reveal.from-right { transform: translateX(50px); }
    .c-reveal.scale-up   { transform: scale(.93); }
    .c-reveal.visible    { opacity:1; transform:none; }

    .c-delay-1 { transition-delay: .08s; }
    .c-delay-2 { transition-delay: .16s; }
    .c-delay-3 { transition-delay: .24s; }
    .c-delay-4 { transition-delay: .32s; }

    /* ── HERO ───────────────────────────────────────────────────── */
    .ct-hero {
        position: relative;
        padding: 110px 0 70px;
        overflow: hidden;
        text-align: center;
    }
    .ct-hero-bg {
        position: absolute; inset: 0;
        background: radial-gradient(ellipse 80% 60% at 50% 0%,
                    rgba(220,0,0,.13) 0%, transparent 70%),
                    linear-gradient(180deg, rgba(220,0,0,.04) 0%, transparent 100%);
    }
    /* Decorative racing stripe */
    .ct-hero-stripe {
        position: absolute; left: 0; right: 0; bottom: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent 0%, var(--ferrari-red) 30%,
                    var(--ferrari-red) 70%, transparent 100%);
        opacity: .5;
    }
    .ct-hero-content { position: relative; z-index: 2; }
    .ct-eyebrow {
        font-size: 10px; letter-spacing: 6px; text-transform: uppercase;
        color: var(--ferrari-red); margin-bottom: 14px;
        animation: ctFadeUp .9s ease both;
    }
    .ct-title {
        font-family: 'Bebas Neue', sans-serif;
        font-size: clamp(56px, 8vw, 100px);
        letter-spacing: 6px; line-height: .9; margin-bottom: 18px;
        animation: ctFadeUp .9s ease .12s both;
    }
    .ct-title span { color: var(--ferrari-red); }
    .ct-sub {
        color: var(--gray); font-size: 15px; line-height: 1.8;
        max-width: 460px; margin: 0 auto;
        animation: ctFadeUp .9s ease .24s both;
    }
    @keyframes ctFadeUp {
        from { opacity:0; transform:translateY(24px); }
        to   { opacity:1; transform:translateY(0); }
    }

    /* ── MAIN GRID ──────────────────────────────────────────────── */
    .ct-body { padding: 72px 0 100px; }
    .ct-grid {
        display: grid;
        grid-template-columns: 1fr 1.3fr;
        gap: 48px;
        align-items: start;
    }

    /* ── LEFT COLUMN ────────────────────────────────────────────── */
    .ct-left {}

    /* Info cards */
    .ct-info-cards { display: flex; flex-direction: column; gap: 14px; margin-bottom: 36px; }
    .ct-info-card {
        display: flex; align-items: center; gap: 16px;
        background: var(--dark2);
        border: 1px solid #1e1e1e;
        border-radius: 10px;
        padding: 18px 20px;
        transition: border-color .3s ease, transform .3s ease, background .3s ease;
        cursor: default;
    }
    .ct-info-card:hover {
        border-color: rgba(220,0,0,.35);
        transform: translateX(5px);
        background: rgba(220,0,0,.03);
    }
    .ct-info-icon {
        width: 44px; height: 44px; flex-shrink: 0;
        background: rgba(220,0,0,.08);
        border: 1px solid rgba(220,0,0,.15);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: var(--ferrari-red); font-size: 16px;
        transition: background .3s, box-shadow .3s;
    }
    .ct-info-card:hover .ct-info-icon {
        background: rgba(220,0,0,.15);
        box-shadow: 0 0 20px rgba(220,0,0,.2);
    }
    .ct-info-label {
        font-size: 10px; letter-spacing: 2.5px; text-transform: uppercase;
        color: var(--ferrari-red); margin-bottom: 3px;
    }
    .ct-info-val { font-size: 14px; color: var(--light); font-weight: 500; }

    /* ── SOCIAL SECTION ─────────────────────────────────────────── */
    .ct-social-heading {
        font-size: 10px; letter-spacing: 4px; text-transform: uppercase;
        color: var(--gray); margin-bottom: 16px;
    }
    .ct-social-row { display: flex; flex-wrap: wrap; gap: 10px; }

    .ct-social-btn {
        display: inline-flex; align-items: center; gap: 9px;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 13px; font-weight: 600;
        text-decoration: none;
        border: 1px solid transparent;
        transition: transform .25s cubic-bezier(.34,1.56,.64,1),
                    box-shadow .25s ease,
                    background .25s ease,
                    border-color .25s ease;
        position: relative; overflow: hidden;
    }
    .ct-social-btn::after {
        content: '';
        position: absolute; inset: 0;
        background: rgba(255,255,255,.05);
        opacity: 0;
        transition: opacity .2s;
    }
    .ct-social-btn:hover::after { opacity: 1; }
    .ct-social-btn:hover {
        transform: translateY(-3px) scale(1.04);
    }
    .ct-social-btn i { font-size: 15px; }

    /* Per-platform colours */
    .social-fb {
        background: rgba(24,119,242,.1);
        border-color: rgba(24,119,242,.25);
        color: #5b9cf6;
    }
    .social-fb:hover {
        background: rgba(24,119,242,.2);
        box-shadow: 0 8px 28px rgba(24,119,242,.25);
        border-color: rgba(24,119,242,.5);
        color: #7ab0ff;
    }
    .social-ig {
        background: rgba(225,48,108,.1);
        border-color: rgba(225,48,108,.25);
        color: #f06292;
    }
    .social-ig:hover {
        background: rgba(225,48,108,.2);
        box-shadow: 0 8px 28px rgba(225,48,108,.25);
        border-color: rgba(225,48,108,.5);
        color: #f48fb1;
    }
    .social-tt {
        background: rgba(255,255,255,.06);
        border-color: rgba(255,255,255,.15);
        color: #e0e0e0;
    }
    .social-tt:hover {
        background: rgba(255,255,255,.12);
        box-shadow: 0 8px 28px rgba(255,255,255,.1);
        border-color: rgba(255,255,255,.3);
        color: #fff;
    }
    .social-yt {
        background: rgba(255,0,0,.08);
        border-color: rgba(255,0,0,.2);
        color: #ef5350;
    }
    .social-yt:hover {
        background: rgba(255,0,0,.18);
        box-shadow: 0 8px 28px rgba(255,0,0,.25);
        border-color: rgba(255,0,0,.45);
        color: #ff6b6b;
    }
    .social-tw {
        background: rgba(29,161,242,.08);
        border-color: rgba(29,161,242,.2);
        color: #4fc3f7;
    }
    .social-tw:hover {
        background: rgba(29,161,242,.18);
        box-shadow: 0 8px 28px rgba(29,161,242,.22);
        border-color: rgba(29,161,242,.45);
        color: #81d4fa;
    }

    /* ── RIGHT COLUMN — FORM ────────────────────────────────────── */
    .ct-form-card {
        background: var(--dark2);
        border: 1px solid #1e1e1e;
        border-radius: 14px;
        padding: 40px 36px;
        position: relative; overflow: hidden;
    }
    /* Subtle red glow top-right corner */
    .ct-form-card::before {
        content: '';
        position: absolute; top: -40px; right: -40px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(220,0,0,.12) 0%, transparent 70%);
        pointer-events: none;
    }

    .ct-form-title {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 28px; letter-spacing: 3px;
        color: var(--ferrari-red); margin-bottom: 6px;
    }
    .ct-form-sub {
        color: var(--gray); font-size: 13px; margin-bottom: 28px; line-height: 1.6;
    }

    /* Form fields */
    .ct-field { margin-bottom: 20px; }
    .ct-field label {
        display: block; font-size: 10px; font-weight: 700;
        letter-spacing: 2.5px; text-transform: uppercase;
        color: var(--gray); margin-bottom: 8px;
    }
    .ct-field .ct-input {
        width: 100%; background: rgba(255,255,255,.04);
        border: 1px solid #2a2a2a;
        border-radius: 8px; padding: 13px 16px;
        color: var(--light); font-size: 14px;
        outline: none;
        transition: border-color .25s ease, box-shadow .25s ease, background .25s ease;
        box-sizing: border-box;
    }
    .ct-field .ct-input::placeholder { color: #444; }
    .ct-field .ct-input:focus {
        border-color: rgba(220,0,0,.5);
        background: rgba(220,0,0,.03);
        box-shadow: 0 0 0 3px rgba(220,0,0,.08);
    }
    .ct-field textarea.ct-input { resize: vertical; min-height: 130px; }

    /* Field row */
    .ct-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

    /* Submit button */
    .ct-submit {
        width: 100%; padding: 15px 24px;
        background: var(--ferrari-red);
        color: #fff; font-family: 'Bebas Neue', sans-serif;
        font-size: 17px; letter-spacing: 3px;
        border: none; border-radius: 8px; cursor: pointer;
        position: relative; overflow: hidden;
        transition: transform .25s cubic-bezier(.34,1.56,.64,1),
                    box-shadow .25s ease,
                    background .25s ease;
    }
    .ct-submit::after {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,.15) 0%, transparent 60%);
        opacity: 0; transition: opacity .25s;
    }
    .ct-submit:hover::after { opacity: 1; }
    .ct-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 32px rgba(220,0,0,.4);
        background: #e80000;
    }
    .ct-submit:active { transform: translateY(0) scale(.98); }

    /* Success toast */
    .ct-toast {
        display: none; align-items: center; gap: 12px;
        background: rgba(0,200,100,.08);
        border: 1px solid rgba(0,200,100,.25);
        border-radius: 8px; padding: 14px 18px;
        margin-top: 16px; color: #4caf82; font-size: 14px;
    }
    .ct-toast.show { display: flex; animation: toastIn .4s ease; }
    @keyframes toastIn {
        from { opacity:0; transform:translateY(8px); }
        to   { opacity:1; transform:translateY(0); }
    }

    /* ── MAP STRIP ──────────────────────────────────────────────── */
    .ct-map-strip {
        margin-top: 64px; border-radius: 14px; overflow: hidden;
        border: 1px solid #1e1e1e;
        position: relative;
    }
    .ct-map-strip iframe {
        width: 100%; height: 320px; border: none; display: block;
        filter: grayscale(1) invert(1) brightness(.8) contrast(.9);
        transition: filter .4s ease;
    }
    .ct-map-strip:hover iframe { filter: grayscale(.6) invert(1) brightness(.9); }
    .ct-map-label {
        position: absolute; top: 16px; left: 16px;
        background: rgba(13,13,13,.9);
        border: 1px solid rgba(220,0,0,.25);
        border-radius: 6px; padding: 8px 14px;
        font-size: 12px; letter-spacing: 1.5px;
        backdrop-filter: blur(10px);
        display: flex; align-items: center; gap: 8px;
    }
    .ct-map-label i { color: var(--ferrari-red); }

    /* ── RESPONSIVE ─────────────────────────────────────────────── */
    @media (max-width: 860px) {
        .ct-grid { grid-template-columns: 1fr; gap: 32px; }
        .ct-form-card { padding: 28px 22px; }
        .ct-field-row { grid-template-columns: 1fr; gap: 0; }
    }
    @media (max-width: 520px) {
        .ct-title { font-size: 58px; }
        .ct-social-row { gap: 8px; }
        .ct-social-btn { font-size: 12px; padding: 9px 14px; }
    }
</style>
@endpush

@section('content')

{{-- ══ HERO ══════════════════════════════════════════════════════════ --}}
<section class="ct-hero">
    <div class="ct-hero-bg"></div>
    <div class="ct-hero-content container">
        <p class="ct-eyebrow">We'd love to hear from you</p>
        <h1 class="ct-title">Get In <span>Touch</span></h1>
        <p class="ct-sub">
            Whether you're inquiring about a model, scheduling a test drive,
            or simply passionate about Ferrari — we're here.
        </p>
    </div>
    <div class="ct-hero-stripe"></div>
</section>

{{-- ══ BODY ═══════════════════════════════════════════════════════════ --}}
<section class="ct-body">
    <div class="container">
        <div class="ct-grid">

            {{-- ── LEFT ─────────────────────────────────────────── --}}
            <div>
                {{-- Info cards --}}
                <div class="ct-info-cards">
                    <div class="ct-info-card c-reveal c-delay-1">
                        <div class="ct-info-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <div class="ct-info-label">Email</div>
                            <div class="ct-info-val">info@ferrarisystem.com</div>
                        </div>
                    </div>
                    <div class="ct-info-card c-reveal c-delay-2">
                        <div class="ct-info-icon"><i class="fas fa-phone"></i></div>
                        <div>
                            <div class="ct-info-label">Phone</div>
                            <div class="ct-info-val">+63 (2) 8888-0000</div>
                        </div>
                    </div>
                    <div class="ct-info-card c-reveal c-delay-3">
                        <div class="ct-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <div class="ct-info-label">Address</div>
                            <div class="ct-info-val">Makati City, Metro Manila, Philippines</div>
                        </div>
                    </div>
                    <div class="ct-info-card c-reveal c-delay-4">
                        <div class="ct-info-icon"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="ct-info-label">Business Hours</div>
                            <div class="ct-info-val">Mon – Sat &nbsp;·&nbsp; 9:00 AM – 6:00 PM</div>
                        </div>
                    </div>
                </div>

                {{-- Social media --}}
                <div class="c-reveal">
                    <p class="ct-social-heading">Follow Us</p>
                    <div class="ct-social-row">
                        <a href="#" class="ct-social-btn social-fb" target="_blank">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                        <a href="#" class="ct-social-btn social-ig" target="_blank">
                            <i class="fab fa-instagram"></i> Instagram
                        </a>
                        <a href="#" class="ct-social-btn social-tt" target="_blank">
                            <i class="fab fa-tiktok"></i> TikTok
                        </a>
                        <a href="#" class="ct-social-btn social-yt" target="_blank">
                            <i class="fab fa-youtube"></i> YouTube
                        </a>
                        <a href="#" class="ct-social-btn social-tw" target="_blank">
                            <i class="fab fa-x-twitter"></i> X / Twitter
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── RIGHT (FORM) ─────────────────────────────────── --}}
            <div class="ct-form-card c-reveal from-right">
                <div class="ct-form-title">Send a Message</div>
                <p class="ct-form-sub">Fill out the form and our team will get back to you within 24 hours.</p>

                <form id="contactForm" onsubmit="handleSubmit(event)" novalidate>
                    @csrf
                    <div class="ct-field-row">
                        <div class="ct-field">
                            <label for="ct_name">Full Name</label>
                            <input class="ct-input" id="ct_name" name="name" type="text"
                                   placeholder="Juan Dela Cruz" required>
                        </div>
                        <div class="ct-field">
                            <label for="ct_phone">Phone (optional)</label>
                            <input class="ct-input" id="ct_phone" name="phone" type="tel"
                                   placeholder="+63 9XX XXX XXXX">
                        </div>
                    </div>

                    <div class="ct-field">
                        <label for="ct_email">Email Address</label>
                        <input class="ct-input" id="ct_email" name="email" type="email"
                               placeholder="you@email.com" required>
                    </div>

                    <div class="ct-field">
                        <label for="ct_subject">Subject</label>
                        <input class="ct-input" id="ct_subject" name="subject" type="text"
                               placeholder="e.g. Test Drive Inquiry">
                    </div>

                    <div class="ct-field">
                        <label for="ct_msg">Message</label>
                        <textarea class="ct-input" id="ct_msg" name="message" rows="5"
                                  placeholder="Tell us how we can help you..." required></textarea>
                    </div>

                    <button type="submit" class="ct-submit" id="ctSubmitBtn">
                        <span id="ctBtnText"><i class="fas fa-paper-plane" style="margin-right:10px;font-size:14px;"></i>Send Message</span>
                    </button>

                    <div class="ct-toast" id="ctToast">
                        <i class="fas fa-check-circle" style="font-size:18px;"></i>
                        <span>Message sent! We'll be in touch soon.</span>
                    </div>
                </form>
            </div>

        </div>

        {{-- ── MAP ─────────────────────────────────────────────── --}}
        <div class="ct-map-strip c-reveal" style="transition-delay:.2s">
            <div class="ct-map-label">
                <i class="fas fa-location-dot"></i> Makati City, Philippines
            </div>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15447.614836474!2d121.01154!3d14.55027!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c90264a0a7b1%3A0x6f5b0b4f0b0b0b0b!2sMakati%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1680000000000!5m2!1sen!2sph"
                allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
// ── SCROLL REVEAL ────────────────────────────────────────────────────
const cObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('visible');
            cObs.unobserve(e.target);
        }
    });
}, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });

document.querySelectorAll('.c-reveal').forEach(el => cObs.observe(el));

// ── FORM SUBMIT ──────────────────────────────────────────────────────
function handleSubmit(e) {
    e.preventDefault();

    const btn    = document.getElementById('ctSubmitBtn');
    const text   = document.getElementById('ctBtnText');
    const toast  = document.getElementById('ctToast');
    const form   = document.getElementById('contactForm');

    // Loading state
    btn.disabled = true;
    text.innerHTML = '<i class="fas fa-circle-notch fa-spin" style="margin-right:10px;font-size:14px;"></i>Sending...';

    // Simulate / replace with real fetch to your route
    setTimeout(() => {
        btn.disabled = false;
        text.innerHTML = '<i class="fas fa-paper-plane" style="margin-right:10px;font-size:14px;"></i>Send Message';
        toast.classList.add('show');
        form.reset();
        setTimeout(() => toast.classList.remove('show'), 5000);
    }, 1400);

    /*
    // ── REAL SUBMIT (uncomment & remove setTimeout above) ──────────
    fetch('/contact', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(new FormData(form)))
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        text.innerHTML = '<i class="fas fa-paper-plane" style="margin-right:10px;font-size:14px;"></i>Send Message';
        toast.classList.add('show');
        form.reset();
        setTimeout(() => toast.classList.remove('show'), 5000);
    })
    .catch(() => {
        btn.disabled = false;
        text.innerHTML = 'Try Again';
    });
    */
}

// ── SOCIAL BTN RIPPLE ────────────────────────────────────────────────
document.querySelectorAll('.ct-social-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect   = this.getBoundingClientRect();
        const size   = Math.max(rect.width, rect.height);
        ripple.style.cssText = `
            position:absolute; border-radius:50%;
            width:${size}px; height:${size}px;
            left:${e.clientX - rect.left - size/2}px;
            top:${e.clientY - rect.top  - size/2}px;
            background:rgba(255,255,255,.15);
            transform:scale(0); animation:ripple .5s ease-out;
            pointer-events:none;
        `;
        this.appendChild(ripple);
        ripple.addEventListener('animationend', () => ripple.remove());
    });
});
</script>
<style>
@keyframes ripple {
    to { transform:scale(2.5); opacity:0; }
}
</style>
@endpush