<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VELOCE VANTAGE — Where Speed Meets Superiority')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- GSAP (CDN) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ferrari-red: #dc0000;
            --ferrari-yellow: #f5c518;
            --dark: #0d0d0d;
            --dark2: #1a1a1a;
            --dark3: #252525;
            --light: #e8e8e8;
            --gray: #888;
            --nav-h: 72px;
            --nav-logo-h: 38px;
            --nav-transition: 250ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background: var(--dark);
            color: var(--light);
            font-family: 'Barlow', sans-serif;
            font-size: 16px;
        }
        a { color: inherit; text-decoration: none; }

        /* ══════════════════════════════════════════════════
           NAVBAR
        ══════════════════════════════════════════════════ */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            padding: 0 40px;
            height: var(--nav-h);
            background: rgba(10, 10, 10, 0.92);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
            border-bottom: 1px solid rgba(220, 0, 0, 0.18);
            gap: 20px;
            opacity: 0;
            transform: translateY(-100%);
        }
        nav::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(
                90deg,
                transparent 0%,
                rgba(220, 0, 0, 0.5) 30%,
                rgba(220, 0, 0, 0.8) 50%,
                rgba(220, 0, 0, 0.5) 70%,
                transparent 100%
            );
            opacity: 0.6;
        }

        /* ── Brand ── */
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 11px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px;
            letter-spacing: 3.5px;
            color: var(--ferrari-red);
            white-space: nowrap;
            flex-shrink: 0;
            will-change: transform;
        }
        .brand-sys { color: var(--light); }

        .nav-logo {
            height: var(--nav-logo-h);
            width: auto;
            flex-shrink: 0;
            filter: drop-shadow(0 0 5px rgba(220, 0, 0, 0.25));
            will-change: filter, transform;
        }

        /* ── Center nav links ── */
        .nav-center {
            display: flex;
            gap: 6px;
            align-items: center;
            justify-content: center;
        }
        .nav-center a {
            position: relative;
            font-weight: 600;
            font-size: 11.5px;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            color: rgba(232, 232, 232, 0.75);
            white-space: nowrap;
            padding: 8px 14px;
            border-radius: 4px;
            transition: color var(--nav-transition), background var(--nav-transition);
            will-change: transform;
        }
        .nav-center a::after {
            content: '';
            position: absolute;
            bottom: 4px; left: 14px; right: 14px;
            height: 1.5px;
            background: var(--ferrari-red);
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 1px;
        }
        .nav-center a:hover { color: var(--light); background: rgba(255,255,255,0.04); }
        .nav-center a:hover::after { transform: scaleX(1); }
        .nav-center a.active { color: var(--ferrari-red); }
        .nav-center a.active::after { transform: scaleX(1); }

        /* ── Right area ── */
        .nav-right {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-shrink: 0;
        }
        .nav-right-sep {
            width: 3px; height: 3px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            flex-shrink: 0;
        }
        .nav-right-link {
            font-weight: 600;
            font-size: 11.5px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(232, 232, 232, 0.72);
            white-space: nowrap;
            padding: 6px 10px;
            border-radius: 4px;
            transition: color var(--nav-transition), background var(--nav-transition);
        }
        .nav-right-link:hover { color: var(--light); }
        .nav-right-link.active { color: var(--ferrari-red); }

        /* Icon-only buttons */
        .nav-icon-btn {
            position: relative;
            width: 38px; height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid transparent;
            background: transparent;
            color: var(--light);
            font-size: 15px;
            cursor: pointer;
            transition:
                color var(--nav-transition),
                background var(--nav-transition),
                border-color var(--nav-transition),
                box-shadow var(--nav-transition);
            text-decoration: none;
        }
        .nav-icon-btn:hover {
            color: var(--ferrari-red);
            background: rgba(220, 0, 0, 0.07);
            border-color: rgba(220, 0, 0, 0.22);
            box-shadow: 0 4px 14px rgba(220, 0, 0, 0.15);
        }

        /* ── LOGIN BUTTON ── */
        .nav-login-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 6px;
            background: rgba(220, 0, 0, 0.06);
            border: 1.5px solid rgba(220, 0, 0, 0.35);
            font-family: 'Barlow', sans-serif;
            font-weight: 700;
            font-size: 11.5px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(232, 232, 232, 0.85);
            white-space: nowrap;
            cursor: pointer;
            transition:
                background var(--nav-transition),
                border-color var(--nav-transition),
                color var(--nav-transition),
                box-shadow var(--nav-transition);
            will-change: transform;
        }
        .nav-login-btn i {
            font-size: 13px;
            color: var(--ferrari-red);
            opacity: 0.9;
            transition: opacity var(--nav-transition), color var(--nav-transition);
        }
        .nav-login-btn:hover {
            background: var(--ferrari-red);
            border-color: var(--ferrari-red);
            color: #fff;
            box-shadow: 0 6px 22px rgba(220, 0, 0, 0.4);
        }
        .nav-login-btn:hover i { opacity: 1; color: #fff; }
        .nav-login-btn.active {
            background: rgba(220, 0, 0, 0.12);
            border-color: rgba(220, 0, 0, 0.55);
            color: #fff;
        }

        /* Register icon-only button */
        .nav-register-btn {
            position: relative;
            width: 38px; height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.13);
            background: rgba(255, 255, 255, 0.04);
            color: rgba(232, 232, 232, 0.75);
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition:
                color var(--nav-transition),
                background var(--nav-transition),
                border-color var(--nav-transition),
                box-shadow var(--nav-transition);
        }
        .nav-register-btn:hover {
            color: #fff;
            background: rgba(255,255,255,0.10);
            border-color: rgba(255,255,255,0.30);
            box-shadow: 0 4px 18px rgba(0,0,0,0.3);
        }
        .nav-register-btn.active {
            color: #fff;
            border-color: rgba(255,255,255,0.35);
            background: rgba(255,255,255,0.09);
        }

        /* Tooltip */
        .nav-icon-btn[data-tip]::after,
        .nav-register-btn[data-tip]::after {
            content: attr(data-tip);
            position: absolute;
            bottom: -34px; left: 50%;
            transform: translateX(-50%) translateY(5px);
            background: var(--dark2);
            border: 1px solid rgba(255,255,255,0.08);
            color: var(--light);
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            white-space: nowrap;
            padding: 4px 10px;
            border-radius: 4px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .nav-icon-btn[data-tip]:hover::after,
        .nav-register-btn[data-tip]:hover::after {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        /* Cart badge */
        .cart-wrap { position: relative; display: inline-flex; }
        .cart-badge {
            position: absolute;
            top: -6px; right: -8px;
            background: var(--ferrari-red);
            color: #fff;
            border-radius: 50%;
            width: 17px; height: 17px;
            font-size: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            pointer-events: none;
            border: 1.5px solid var(--dark);
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .cart-wrap:hover .cart-badge { transform: scale(1.15); }

        /* ── User dropdown ── */
        .nav-user-wrap { position: relative; display: inline-flex; }
        .nav-user-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 13px;
            border-radius: 6px;
            border: 1.5px solid rgba(220, 0, 0, 0.22);
            background: rgba(220, 0, 0, 0.04);
            font-family: 'Barlow', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(232, 232, 232, 0.82);
            cursor: pointer;
            white-space: nowrap;
            transition:
                background var(--nav-transition),
                border-color var(--nav-transition),
                color var(--nav-transition),
                box-shadow var(--nav-transition);
            will-change: transform;
            max-width: 200px;
        }
        .nav-user-btn:hover,
        .nav-user-wrap.open .nav-user-btn {
            background: rgba(220, 0, 0, 0.09);
            border-color: rgba(220, 0, 0, 0.48);
            color: var(--light);
            box-shadow: 0 4px 16px rgba(220, 0, 0, 0.14);
        }
        .nav-user-name {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 160px;
        }
        .nav-user-chevron {
            font-size: 9px;
            opacity: 0.55;
            flex-shrink: 0;
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s;
        }
        .nav-user-wrap.open .nav-user-chevron { transform: rotate(180deg); opacity: 1; }

        .nav-user-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            min-width: 220px;
            background: #141414;
            border: 1px solid rgba(220, 0, 0, 0.20);
            border-radius: 10px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.65), 0 0 0 1px rgba(255,255,255,0.03);
            overflow: hidden;
            z-index: 2000;
            opacity: 0;
            pointer-events: none;
            transform-origin: top right;
        }
        .nav-user-wrap.open .nav-user-dropdown { pointer-events: all; }

        .nav-dropdown-header {
            padding: 16px 18px 14px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            background: rgba(220, 0, 0, 0.04);
        }
        .nav-dropdown-name { font-weight: 700; font-size: 14px; color: var(--light); letter-spacing: 0.3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .nav-dropdown-role { font-size: 10px; color: var(--ferrari-red); letter-spacing: 2px; text-transform: uppercase; margin-top: 3px; }

        .nav-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 18px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(232, 232, 232, 0.75);
            cursor: pointer;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            font-family: 'Barlow', sans-serif;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: color 0.22s ease, background 0.22s ease, padding-left 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-dropdown-item:last-child { border-bottom: none; }
        .nav-dropdown-item i { width: 15px; text-align: center; font-size: 13px; opacity: 0.6; flex-shrink: 0; transition: opacity 0.22s ease; }
        .nav-dropdown-item:hover { color: #fff; background: rgba(255,255,255,0.06); padding-left: 26px; }
        .nav-dropdown-item:hover i { opacity: 1; }

        .nav-dropdown-item.danger {
            color: rgba(220, 0, 0, 0.85);
            border-bottom: none;
            justify-content: center;
            padding: 14px 18px;
            font-size: 13px;
            letter-spacing: 2px;
            margin: 4px 10px 8px;
            border-radius: 6px;
            border: 1px solid rgba(220, 0, 0, 0.18);
            background: rgba(220, 0, 0, 0.04);
            width: calc(100% - 20px);
            transition: color 0.28s ease, background 0.28s ease, border-color 0.28s ease, box-shadow 0.28s ease, padding 0s;
        }
        .nav-dropdown-item.danger i { color: var(--ferrari-red); opacity: 0.85; transition: opacity 0.28s, color 0.28s; }
        .nav-dropdown-item.danger:hover {
            color: #fff;
            background: rgba(220, 0, 0, 0.22);
            border-color: rgba(220, 0, 0, 0.55);
            box-shadow: 0 6px 20px rgba(220, 0, 0, 0.20);
            padding-left: 18px;
        }
        .nav-dropdown-item.danger:hover i { opacity: 1; color: #fff; }
        .nav-dropdown-sep { height: 1px; background: rgba(255,255,255,0.05); margin: 2px 0; }

        /* ══════════════════════════════════════════════════
           HAMBURGER
        ══════════════════════════════════════════════════ */
        .nav-hamburger {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
            width: 42px; height: 42px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 6px;
            border-radius: 6px;
            transition: background var(--nav-transition);
        }
        .nav-hamburger:hover { background: rgba(255,255,255,0.05); }
        .nav-hamburger span {
            display: block;
            width: 22px; height: 2px;
            background: var(--light);
            border-radius: 2px;
            transition: background var(--nav-transition);
        }
        .nav-hamburger:hover span { background: var(--ferrari-red); }

        /* ══════════════════════════════════════════════════
           MOBILE MENU
        ══════════════════════════════════════════════════ */
        .nav-mobile {
            display: none;
            position: fixed;
            top: var(--nav-h); left: 0; right: 0;
            z-index: 999;
            background: rgba(8, 8, 8, 0.97);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
            border-bottom: 1px solid rgba(220, 0, 0, 0.14);
            overflow: hidden;
            height: 0;
        }
        .nav-mobile a,
        .nav-mobile button {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 16px 28px;
            font-size: 12.5px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(232, 232, 232, 0.8);
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-family: 'Barlow', sans-serif;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: color 0.22s ease, background 0.22s ease, padding-left 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-mobile a:hover,
        .nav-mobile button:hover {
            color: var(--ferrari-red);
            background: rgba(220, 0, 0, 0.055);
            padding-left: 38px;
        }
        .nav-mobile a.active { color: var(--ferrari-red); }
        .nav-mobile a i, .nav-mobile button i { width: 18px; text-align: center; font-size: 14px; flex-shrink: 0; opacity: 0.7; }
        .mobile-divider { height: 1px; background: rgba(220, 0, 0, 0.1); margin: 4px 0; }
        .nav-mobile .mobile-red { color: var(--ferrari-red); }
        .nav-mobile .mobile-red i { opacity: 1; }
        .nav-mobile .mobile-red:hover { background: rgba(220, 0, 0, 0.08); }
        .nav-mobile-inner { padding: 8px 0 20px; }

        /* ══════════════════════════════════════════════════
           LOGOUT MODAL
        ══════════════════════════════════════════════════ */
        .logout-modal-overlay {
            position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,0.78);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
        }
        .logout-modal-overlay.open { pointer-events: all; }
        .logout-modal-box {
            background: var(--dark2);
            border: 1px solid rgba(220, 0, 0, 0.2);
            border-radius: 14px;
            padding: 44px 40px;
            max-width: 360px;
            width: 90%;
            text-align: center;
            box-shadow: 0 32px 80px rgba(0,0,0,0.65);
        }
        .logout-modal-icon {
            width: 62px; height: 62px;
            border-radius: 50%;
            background: rgba(220,0,0,0.09);
            border: 1px solid rgba(220,0,0,0.28);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
            color: var(--ferrari-red);
            animation: logoutPulse 2.8s ease infinite;
        }
        @keyframes logoutPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(220,0,0,0.22); }
            50%       { box-shadow: 0 0 0 14px rgba(220,0,0,0); }
        }
        .logout-modal-title { font-family: 'Bebas Neue', sans-serif; font-size: 28px; letter-spacing: 3.5px; margin-bottom: 8px; }
        .logout-modal-desc  { color: var(--gray); font-size: 14px; margin-bottom: 28px; line-height: 1.7; }
        .logout-modal-btns  { display: flex; gap: 12px; justify-content: center; }
        .lm-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 11px 24px;
            border-radius: 5px;
            font-weight: 700;
            font-size: 11.5px;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            cursor: pointer;
            border: none;
            font-family: 'Barlow', sans-serif;
            transition: background 0.24s ease, box-shadow 0.24s ease;
            position: relative; overflow: hidden;
            will-change: transform;
        }
        .lm-btn::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.45s ease;
        }
        .lm-btn:hover::after { transform: translateX(100%); }
        .lm-btn-gray { background: #252525; color: var(--gray); border: 1px solid rgba(255,255,255,0.08); }
        .lm-btn-gray:hover { background: #2e2e2e; color: var(--light); }
        .lm-btn-red  { background: var(--ferrari-red); color: #fff; }
        .lm-btn-red:hover { background: #b80000; box-shadow: 0 8px 24px rgba(220,0,0,0.35); }

        /* ══════════════════════════════════════════════════
           TOAST NOTIFICATIONS
        ══════════════════════════════════════════════════ */
        .toast-container {
            position: fixed;
            top: calc(var(--nav-h) + 16px);
            left: 50%;
            transform: translateX(-50%);
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            pointer-events: none;
            min-width: 300px;
            max-width: 460px;
            width: max-content;
        }
        .toast-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.4px;
            min-width: 300px;
            max-width: 460px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.55);
            pointer-events: all;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(12px);
            opacity: 0;
        }
        .toast-item::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            height: 2px;
            width: 100%;
            transform-origin: left center;
            animation: toastDrain 3s linear both;
        }
        .toast-success { background: rgba(10,47,26,0.97); border: 1px solid rgba(29,185,84,0.45); border-left: 4px solid #1db954; color: #4ade80; }
        .toast-success i { color: #1db954; }
        .toast-success::after { background: linear-gradient(to right, #1db954, #4ade80); }
        .toast-error { background: rgba(47,10,10,0.97); border: 1px solid rgba(220,0,0,0.45); border-left: 4px solid var(--ferrari-red); color: #ff6b6b; }
        .toast-error i { color: var(--ferrari-red); }
        .toast-error::after { background: linear-gradient(to right, var(--ferrari-red), #ff6b6b); }
        .toast-warning { background: rgba(47,38,10,0.97); border: 1px solid rgba(245,197,24,0.45); border-left: 4px solid var(--ferrari-yellow); color: #fde68a; }
        .toast-warning i { color: var(--ferrari-yellow); }
        .toast-warning::after { background: linear-gradient(to right, var(--ferrari-yellow), #fde68a); }
        .toast-info { background: rgba(10,26,47,0.97); border: 1px solid rgba(58,142,246,0.45); border-left: 4px solid #3a8ef6; color: #93c5fd; }
        .toast-info i { color: #3a8ef6; }
        .toast-info::after { background: linear-gradient(to right, #3a8ef6, #93c5fd); }
        .toast-item i.toast-icon { font-size: 17px; flex-shrink: 0; }
        .toast-item span { flex: 1; line-height: 1.5; }
        .toast-close-btn {
            background: none; border: none; cursor: pointer;
            font-size: 13px; padding: 0; margin-left: 6px; flex-shrink: 0;
            opacity: 0.5; transition: opacity 0.2s, transform 0.2s;
            color: inherit;
        }
        .toast-close-btn:hover { opacity: 1; transform: scale(1.2); }
        @keyframes toastDrain {
            from { transform: scaleX(1); }
            to   { transform: scaleX(0); }
        }

        /* ══════════════════════════════════════════════════
           PAGE CONTENT
        ══════════════════════════════════════════════════ */
        .page-content { min-height: 100vh; }
        .page-content > .container:first-child { padding-top: calc(var(--nav-h) + 24px); }

        .flash { padding: 14px 24px; margin: 16px auto; max-width: 700px; border-radius: 4px; text-align: center; font-weight: 600; }
        .flash-success { background: #0a2f1a; border: 1px solid #1db954; color: #1db954; }
        .flash-error   { background: #2f0a0a; border: 1px solid var(--ferrari-red); color: var(--ferrari-red); }
        .flash-info    { background: #0a1a2f; border: 1px solid #3a8ef6; color: #3a8ef6; }

        .btn { display:inline-flex; align-items:center; gap:6px; padding:12px 30px; border-radius:3px; font-weight:700; font-size:13px; letter-spacing:2px; text-transform:uppercase; cursor:pointer; border:none; transition:all .25s; position:relative; overflow:hidden; font-family:'Barlow',sans-serif; }
        .btn::after { content:''; position:absolute; inset:0; background:linear-gradient(120deg,transparent 30%,rgba(255,255,255,.1) 50%,transparent 70%); transform:translateX(-100%); transition:transform .45s ease; }
        .btn:hover::after { transform:translateX(100%); }
        .btn:hover  { transform: translateY(-2px); }
        .btn:active { transform: translateY(0); }
        .btn-red    { background: var(--ferrari-red); color: #fff; }
        .btn-red:hover { background: #b00000; box-shadow: 0 8px 22px rgba(220,0,0,.3); }
        .btn-outline { background: transparent; border: 2px solid var(--ferrari-red); color: var(--ferrari-red); }
        .btn-outline:hover { background: var(--ferrari-red); color: #fff; }
        .btn-sm     { padding: 8px 18px; font-size: 11px; }
        .btn-gray   { background: #2a2a2a; color: var(--gray); border: 1px solid #333; }
        .btn-gray:hover { background: #333; color: var(--light); }
        .btn-danger { background: #330000; color: #ff4444; border: 1px solid rgba(255,68,68,.4); }
        .btn-danger:hover { background: #550000; box-shadow: 0 6px 18px rgba(255,0,0,.2); }

        footer { background: var(--dark2); border-top: 1px solid #222; text-align: center; padding: 28px; color: var(--gray); font-size: 13px; }
        footer strong { color: var(--ferrari-red); }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; letter-spacing: 1px; text-transform: uppercase; color: var(--gray); }
        .form-control { width: 100%; padding: 12px 16px; background: var(--dark3); border: 1px solid #333; border-radius: 4px; color: var(--light); font-size: 15px; transition: border-color .2s, box-shadow .2s; font-family: 'Barlow', sans-serif; }
        .form-control:focus { outline: none; border-color: var(--ferrari-red); box-shadow: 0 0 0 3px rgba(220,0,0,.08); }
        .form-error { color: var(--ferrari-red); font-size: 12px; margin-top: 4px; }

        .card { background: var(--dark2); border: 1px solid #222; border-radius: 8px; overflow: hidden; }
        .card-body { padding: 24px; }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 13px 16px; text-align: left; border-bottom: 1px solid #222; font-size: 14px; }
        th { background: var(--dark3); color: var(--gray); font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; }
        tr { transition: background .2s; }
        tr:hover td { background: rgba(220,0,0,.04); }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
        .badge-pending    { background: #332a00; color: var(--ferrari-yellow); border: 1px solid var(--ferrari-yellow); }
        .badge-processing { background: #002266; color: #4488ff; border: 1px solid #4488ff; }
        .badge-delivered  { background: #00331a; color: #1db954; border: 1px solid #1db954; }
        .badge-cancelled  { background: #330000; color: #ff4444; border: 1px solid #ff4444; }

        .section-title { font-family: 'Bebas Neue', sans-serif; font-size: 42px; letter-spacing: 4px; line-height: 1; }
        .section-title span { color: var(--ferrari-red); }
        .section-subtitle { color: var(--gray); font-size: 15px; margin-top: 8px; }
        .section-divider { width: 60px; height: 3px; background: var(--ferrari-red); margin: 16px 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }

        nav[aria-label="Pagination Navigation"] { display: flex; gap: 4px; flex-wrap: wrap; margin-top: 8px; }
        nav[aria-label="Pagination Navigation"] span,
        nav[aria-label="Pagination Navigation"] a { display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 8px; background: var(--dark3); border: 1px solid #333; border-radius: 4px; color: var(--gray); font-size: 13px; transition: all .2s; }
        nav[aria-label="Pagination Navigation"] a:hover { border-color: var(--ferrari-red); color: var(--ferrari-red); }
        nav[aria-label="Pagination Navigation"] span[aria-current] { background: var(--ferrari-red); color: #fff; border-color: var(--ferrari-red); }

        @media (max-width: 960px) {
            nav { grid-template-columns: auto 1fr auto; padding: 0 24px; }
            .nav-center { display: none; }
        }
        @media (max-width: 640px) {
            :root { --nav-h: 64px; --nav-logo-h: 32px; }
            nav { grid-template-columns: 1fr auto; padding: 0 16px; gap: 8px; }
            .nav-center  { display: none; }
            .nav-right   { display: none; }
            .nav-hamburger { display: flex; }
            .nav-mobile    { display: block; }
        }
        @media (min-width: 641px) { .nav-hamburger { display: none; } }
        @media (max-width: 380px) { .nav-brand { font-size: 17px; gap: 7px; letter-spacing: 2.5px; } }

        /* Red cursor */
        a, button, [role="button"], input[type="submit"], input[type="button"],
        input[type="reset"], label[for], select, .btn, .nav-login-btn,
        .nav-register-btn, .nav-user-btn, .nav-hamburger, .nav-dropdown-item,
        .nav-mobile a, .nav-mobile button, .lm-btn, .cart-wrap a, [onclick] {
            cursor: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path fill='%23dc0000' stroke='%23fff' stroke-width='1' d='M5.5 0 L5.5 17 L8.5 14 L11 19.5 L13 18.5 L10.5 13 L14.5 13 Z'/></svg>") 5 0, pointer;
        }



    </style>

    @stack('styles')
</head>
<body>

{{-- ══ LOGOUT MODAL ════════════════════════════════════════════════════ --}}
<div class="logout-modal-overlay" id="globalLogoutModal">
    <div class="logout-modal-box" id="logoutModalBox">
        <div class="logout-modal-icon"><i class="fas fa-sign-out-alt"></i></div>
        <div class="logout-modal-title">SIGN OUT</div>
        <div class="logout-modal-desc">Are you sure you want to log out of VELOCE VANTAGE?</div>
        <div class="logout-modal-btns">
            <button class="lm-btn lm-btn-gray" onclick="closeLogoutModal()">
                <i class="fas fa-times"></i> No, Stay
            </button>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="lm-btn lm-btn-red">
                    <i class="fas fa-sign-out-alt"></i> Yes, Logout
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ══ TOAST CONTAINER ═════════════════════════════════════════════════ --}}
<div class="toast-container" id="toastContainer" aria-live="polite"></div>

{{-- ══ NAVBAR ══════════════════════════════════════════════════════════ --}}
<nav id="mainNav">
    <a href="{{ route('home') }}" class="nav-brand" id="navBrand">
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="nav-logo" id="navLogo">
        @endif
        VELOCE<span class="brand-sys">&nbsp;VANTAGE</span>
    </a>

    <div class="nav-center" id="navCenter">
        <a href="{{ route('home') }}"    class="{{ request()->routeIs('home')    ? 'active' : '' }}">Home</a>
        <a href="{{ route('shop') }}"    class="{{ request()->routeIs('shop')    ? 'active' : '' }}">Shop</a>
        <a href="{{ route('about') }}"   class="{{ request()->routeIs('about')   ? 'active' : '' }}">About</a>
        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
    </div>

    <div class="nav-right" id="navRight">
        @auth
            <a href="{{ route('orders.index') }}"
               class="nav-right-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                My Orders
            </a>
            <span class="nav-right-sep"></span>
            <div class="cart-wrap">
                <a href="{{ route('cart.index') }}" class="nav-icon-btn" data-tip="Cart" title="Cart">
                    <i class="fas fa-shopping-cart"></i>
                    @php $cartCount = auth()->user()->cartItems()->count(); @endphp
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
            <span class="nav-right-sep"></span>
            <div class="nav-user-wrap" id="navUserWrap">
                <button class="nav-user-btn" id="navUserBtn" aria-haspopup="true" aria-expanded="false">
                    <span class="nav-user-name">{{ auth()->user()->name }}</span>
                    <i class="fas fa-chevron-down nav-user-chevron"></i>
                </button>
                <div class="nav-user-dropdown" id="navUserDropdown" role="menu">
                    <div class="nav-dropdown-header">
                        <div class="nav-dropdown-name">{{ auth()->user()->name }}</div>
                        <div class="nav-dropdown-role">
                            @if(auth()->user()->isAdmin()) Admin @else Member @endif
                        </div>
                    </div>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-dropdown-item" role="menuitem">
                            <i class="fas fa-shield-alt"></i> Admin Panel
                        </a>
                        <div class="nav-dropdown-sep"></div>
                    @endif
                    <button onclick="closeUserDropdown(); openLogoutModal();" class="nav-dropdown-item danger" role="menuitem">
                        <i class="fas fa-sign-out-alt"></i> Sign Out
                    </button>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}"
               class="nav-login-btn {{ request()->routeIs('login') ? 'active' : '' }}">
                <i class="fas fa-user"></i> Login
            </a>
            <a href="{{ route('register') }}"
               class="nav-register-btn {{ request()->routeIs('register') ? 'active' : '' }}"
               data-tip="Register" title="Register">
                <i class="fas fa-user-plus"></i>
            </a>
        @endauth
    </div>

    <button class="nav-hamburger" id="navHamburger" aria-label="Toggle menu">
        <span id="hbLine1"></span>
        <span id="hbLine2"></span>
        <span id="hbLine3"></span>
    </button>
</nav>

{{-- ══ MOBILE MENU ═════════════════════════════════════════════════════ --}}
<div class="nav-mobile" id="navMobile">
    <div class="nav-mobile-inner" id="navMobileInner">
        <a href="{{ route('home') }}"    class="{{ request()->routeIs('home')    ? 'active' : '' }}"><i class="fas fa-home"></i> Home</a>
        <a href="{{ route('shop') }}"    class="{{ request()->routeIs('shop')    ? 'active' : '' }}"><i class="fas fa-car"></i> Shop</a>
        <a href="{{ route('about') }}"   class="{{ request()->routeIs('about')   ? 'active' : '' }}"><i class="fas fa-info-circle"></i> About</a>
        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}"><i class="fas fa-envelope"></i> Contact</a>
        @auth
            <div class="mobile-divider"></div>
            <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i> My Orders
            </a>
            <a href="{{ route('cart.index') }}" class="{{ request()->routeIs('cart.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> Cart
                @php $mcc = auth()->user()->cartItems()->count(); @endphp
                @if($mcc > 0)
                    <span style="background:var(--ferrari-red);color:#fff;border-radius:50%;width:17px;height:17px;font-size:9px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;margin-left:2px;flex-shrink:0;">{{ $mcc }}</span>
                @endif
            </a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="mobile-red"><i class="fas fa-shield-alt"></i> Admin Panel</a>
            @endif
            <div class="mobile-divider"></div>
            <div style="padding:14px 28px 10px;display:flex;align-items:center;gap:11px;opacity:0.7;pointer-events:none;border-bottom:1px solid rgba(255,255,255,0.04)">
                <div style="width:30px;height:30px;border-radius:50%;background:var(--ferrari-red);color:#fff;font-size:12px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div style="font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--light);">{{ Str::limit(auth()->user()->name, 18) }}</div>
            </div>
            <button onclick="closeMobileMenu(); openLogoutModal()" class="mobile-red"><i class="fas fa-sign-out-alt"></i> Sign Out</button>
        @else
            <div class="mobile-divider"></div>
            <a href="{{ route('login') }}"    class="{{ request()->routeIs('login')    ? 'active' : '' }}"><i class="fas fa-user"></i> Login</a>
            <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}"><i class="fas fa-user-plus"></i> Register</a>
        @endauth
    </div>
</div>

{{-- ══ PAGE CONTENT ════════════════════════════════════════════════════ --}}
<div class="page-content">
    @yield('content')
</div>

<footer>
    <p>&copy; {{ date('Y') }} <strong>VELOCE VANTAGE</strong>. Built with Laravel 12 + IAS Security.</p>
</footer>

{{-- ══════════════════════════════════════════════════════════════════════
     FAQ AI CHAT WIDGET
══════════════════════════════════════════════════════════════════════ --}}
<button id="faqTrigger" aria-label="Ask VELO — AI Assistant" title="Ask VELO, your AI assistant">
    <i class="fas fa-robot" id="faqTriggerIcon"></i>
    <span class="faq-trigger-badge">AI</span>
</button>

<div id="faqPanel" role="dialog" aria-label="VELO FAQ Assistant" aria-modal="true">
    <div class="faq-header">
        <div class="faq-header-avatar"><i class="fas fa-robot"></i></div>
        <div class="faq-header-info">
            <div class="faq-header-name">VELO — AI Assistant</div>
            <div class="faq-header-sub"><strong>● Online</strong> &nbsp;·&nbsp; Veloce Vantage Support</div>
        </div>
        <div class="faq-header-actions">
            <button class="faq-hdr-btn danger" id="faqClearBtn" title="Clear chat" aria-label="Clear conversation">
                <i class="fas fa-trash-alt"></i>
            </button>
            <button class="faq-hdr-btn" id="faqCloseBtn" title="Close" aria-label="Close chat">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <div id="faqMessages" aria-live="polite" aria-relevant="additions"></div>

    <div class="faq-suggestions" id="faqSuggestions">
        <button class="faq-chip" data-q="What cars do you sell?">🚗 Cars available</button>
        <button class="faq-chip" data-q="How do I place an order?">📦 How to order</button>
        <button class="faq-chip" data-q="What payment methods do you accept?">💳 Payment methods</button>
        <button class="faq-chip" data-q="How can I track my order?">🔍 Track my order</button>
        <button class="faq-chip" data-q="What is your return or refund policy?">↩️ Returns & refunds</button>
        <button class="faq-chip" data-q="Do you offer any discounts or promotions?">🏷️ Promos</button>
    </div>

    <div class="faq-input-area">
        <textarea
            id="faqInput"
            rows="1"
            placeholder="Ask me anything about Veloce Vantage…"
            aria-label="Your message to VELO"
            maxlength="800"
        ></textarea>
        <button id="faqSend" aria-label="Send message" disabled>
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>

    <div class="faq-footer-brand">Powered by <strong>VELO AI</strong> · Veloce Vantage</div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════════════════════════════ --}}
<script>
/* ════════════════════════════════════════════════════════════════════════
   VELOCE VANTAGE — GSAP Animation Layer
════════════════════════════════════════════════════════════════════════ */
window.addEventListener('load', function () {
    if (typeof gsap === 'undefined') {
        document.getElementById('mainNav').style.cssText = 'opacity:1;transform:translateY(0)';
        return;
    }
    initGSAP();
});

function initGSAP() {
    gsap.registerPlugin(ScrollTrigger);

    /* ── 1. NAVBAR entrance ── */
    const navTl = gsap.timeline({ defaults: { ease: 'power3.out' } });
    navTl
        .to('#mainNav', { y: 0, opacity: 1, duration: 0.6, ease: 'power4.out' })
        .from('#navBrand', { x: -28, opacity: 0, duration: 0.55, ease: 'back.out(1.4)' }, '-=0.3')
        .from('#navCenter a', { y: -14, opacity: 0, duration: 0.42, stagger: 0.07, ease: 'power2.out' }, '-=0.35')
        .from('#navRight > *', { x: 18, opacity: 0, duration: 0.42, stagger: 0.08, ease: 'back.out(1.5)' }, '-=0.40');

    /* ── 2. NAV BRAND hover ── */
    const brand = document.getElementById('navBrand');
    const logo  = document.getElementById('navLogo');
    if (brand) {
        brand.addEventListener('mouseenter', () => {
            gsap.to(brand, { y: -2, duration: 0.3, ease: 'power2.out' });
            if (logo) gsap.to(logo, { scale: 1.08, filter: 'drop-shadow(0 0 14px rgba(220,0,0,0.65))', duration: 0.35, ease: 'power2.out' });
        });
        brand.addEventListener('mouseleave', () => {
            gsap.to(brand, { y: 0, duration: 0.35, ease: 'power2.inOut' });
            if (logo) gsap.to(logo, { scale: 1, filter: 'drop-shadow(0 0 5px rgba(220,0,0,0.25))', duration: 0.35, ease: 'power2.inOut' });
        });
    }

    /* ── 3. NAV LINK hover ── */
    document.querySelectorAll('.nav-center a').forEach(link => {
        link.addEventListener('mouseenter', () => gsap.to(link, { y: -2, duration: 0.25, ease: 'power2.out' }));
        link.addEventListener('mouseleave', () => gsap.to(link, { y: 0, duration: 0.3, ease: 'power2.inOut' }));
    });

    /* ── 4. NAV ICON BUTTON hover ── */
    document.querySelectorAll('.nav-icon-btn, .nav-login-btn, .nav-register-btn').forEach(btn => {
        btn.addEventListener('mouseenter', () => gsap.to(btn, { y: -2, duration: 0.25, ease: 'back.out(2)' }));
        btn.addEventListener('mouseleave', () => gsap.to(btn, { y: 0, duration: 0.3, ease: 'power2.inOut' }));
        btn.addEventListener('mousedown',  () => gsap.to(btn, { scale: 0.94, duration: 0.1, ease: 'power1.in' }));
        btn.addEventListener('mouseup',    () => gsap.to(btn, { scale: 1, duration: 0.25, ease: 'back.out(2)' }));
    });

    /* ── 5. USER DROPDOWN ── */
    const userWrap     = document.getElementById('navUserWrap');
    const userBtn      = document.getElementById('navUserBtn');
    const userDropdown = document.getElementById('navUserDropdown');
    let dropdownOpen   = false;
    let dropdownTween  = null;

    window.openUserDropdown = function () {
        if (!userWrap) return;
        dropdownOpen = true;
        userWrap.classList.add('open');
        userBtn.setAttribute('aria-expanded', 'true');
        if (dropdownTween) dropdownTween.kill();
        gsap.set(userDropdown, { display: 'block' });
        dropdownTween = gsap.to(userDropdown, { opacity: 1, scaleX: 1, scaleY: 1, y: 0, duration: 0.28, ease: 'back.out(1.5)' });
        gsap.from(userDropdown.querySelectorAll('.nav-dropdown-item, .nav-dropdown-header'), {
            y: -8, opacity: 0, duration: 0.22, stagger: 0.05, ease: 'power2.out'
        });
    };
    window.closeUserDropdown = function () {
        if (!userWrap) return;
        dropdownOpen = false;
        userWrap.classList.remove('open');
        userBtn.setAttribute('aria-expanded', 'false');
        if (dropdownTween) dropdownTween.kill();
        dropdownTween = gsap.to(userDropdown, {
            opacity: 0, scaleX: 0.97, scaleY: 0.96, y: -6, duration: 0.2, ease: 'power2.in',
            onComplete: () => gsap.set(userDropdown, { display: 'none' })
        });
    };
    function toggleUserDropdown() { dropdownOpen ? closeUserDropdown() : openUserDropdown(); }

    if (userDropdown) gsap.set(userDropdown, { opacity: 0, scaleX: 0.97, scaleY: 0.96, y: -6, display: 'none', transformOrigin: 'top right' });
    if (userBtn) {
        userBtn.addEventListener('click', e => { e.stopPropagation(); toggleUserDropdown(); });
        userBtn.addEventListener('mouseenter', () => gsap.to(userBtn, { y: -1.5, duration: 0.25, ease: 'power2.out' }));
        userBtn.addEventListener('mouseleave', () => gsap.to(userBtn, { y: 0, duration: 0.3, ease: 'power2.inOut' }));
    }
    document.addEventListener('click', e => { if (userWrap && !userWrap.contains(e.target)) closeUserDropdown(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeUserDropdown(); });
    if (userDropdown) userDropdown.querySelectorAll('a').forEach(l => l.addEventListener('click', closeUserDropdown));

    /* ── 6. LM BUTTONS hover ── */
    document.querySelectorAll('.lm-btn').forEach(btn => {
        btn.addEventListener('mouseenter', () => gsap.to(btn, { y: -2, duration: 0.25, ease: 'back.out(2)' }));
        btn.addEventListener('mouseleave', () => gsap.to(btn, { y: 0, duration: 0.3, ease: 'power2.inOut' }));
        btn.addEventListener('mousedown',  () => gsap.to(btn, { scale: 0.95, duration: 0.1, ease: 'power1.in' }));
        btn.addEventListener('mouseup',    () => gsap.to(btn, { scale: 1, duration: 0.25, ease: 'back.out(2)' }));
    });

    /* ── 7. HAMBURGER + MOBILE MENU ── */
    const hamburger      = document.getElementById('navHamburger');
    const navMobile      = document.getElementById('navMobile');
    const navMobileInner = document.getElementById('navMobileInner');
    const hbLine1 = document.getElementById('hbLine1');
    const hbLine2 = document.getElementById('hbLine2');
    const hbLine3 = document.getElementById('hbLine3');
    let mobileOpen  = false;
    let mobileTween = null;

    window.openMobileMenu = function () {
        mobileOpen = true;
        gsap.set(navMobile, { display: 'block' });
        if (mobileTween) mobileTween.kill();
        mobileTween = gsap.to(navMobile, {
            height: navMobileInner.scrollHeight + 'px', duration: 0.42, ease: 'power3.out',
            onComplete: () => gsap.set(navMobile, { height: 'auto' })
        });
        gsap.from(navMobileInner.querySelectorAll('a, button, .mobile-divider'), {
            x: -20, opacity: 0, duration: 0.35, stagger: 0.045, ease: 'power2.out', delay: 0.1
        });
        gsap.to(hbLine1, { y: 7,  rotation: 45,  duration: 0.35, ease: 'power2.inOut' });
        gsap.to(hbLine2, { scaleX: 0, opacity: 0, duration: 0.2,  ease: 'power1.in' });
        gsap.to(hbLine3, { y: -7, rotation: -45, duration: 0.35, ease: 'power2.inOut' });
    };
    window.closeMobileMenu = function () {
        mobileOpen = false;
        if (mobileTween) mobileTween.kill();
        mobileTween = gsap.to(navMobile, {
            height: 0, duration: 0.35, ease: 'power3.in',
            onComplete: () => gsap.set(navMobile, { display: 'none' })
        });
        gsap.to(hbLine1, { y: 0, rotation: 0, duration: 0.35, ease: 'power2.inOut' });
        gsap.to(hbLine2, { scaleX: 1, opacity: 1, duration: 0.25, ease: 'power1.out', delay: 0.05 });
        gsap.to(hbLine3, { y: 0, rotation: 0, duration: 0.35, ease: 'power2.inOut' });
    };
    if (hamburger) hamburger.addEventListener('click', e => { e.stopPropagation(); mobileOpen ? closeMobileMenu() : openMobileMenu(); });
    document.addEventListener('click', e => {
        if (mobileOpen && hamburger && !hamburger.contains(e.target) && !navMobile.contains(e.target)) closeMobileMenu();
    });
    if (navMobile) {
        navMobile.querySelectorAll('a').forEach(l => l.addEventListener('click', closeMobileMenu));
        gsap.set(navMobile, { height: 0, display: 'none' });
    }

    /* ── 8. LOGOUT MODAL ── */
    const logoutOverlay = document.getElementById('globalLogoutModal');
    const logoutBox     = document.getElementById('logoutModalBox');
    let logoutModalOpen = false;

    gsap.set(logoutBox, { y: 32, scale: 0.94, opacity: 0 });

    window.openLogoutModal = function () {
        logoutModalOpen = true;
        logoutOverlay.classList.add('open');
        gsap.to(logoutOverlay, { opacity: 1, duration: 0.25, ease: 'power2.out' });
        gsap.to(logoutBox, { y: 0, scale: 1, opacity: 1, duration: 0.5, ease: 'back.out(1.6)' });
    };
    window.closeLogoutModal = function () {
        logoutModalOpen = false;
        gsap.to(logoutBox, {
            y: 20, scale: 0.95, opacity: 0, duration: 0.28, ease: 'power2.in',
            onComplete: () => { logoutOverlay.classList.remove('open'); gsap.set(logoutOverlay, { opacity: 0 }); }
        });
    };
    logoutOverlay.addEventListener('click', e => { if (e.target === logoutOverlay) closeLogoutModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && logoutModalOpen) closeLogoutModal(); });

    /* ── 9. TOAST SYSTEM ── */
    const TOAST_DURATION = 3000;
    const toastIcons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };

    window.showToast = function (message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast-item toast-${type}`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <i class="fas ${toastIcons[type] || 'fa-info-circle'} toast-icon"></i>
            <span>${message}</span>
            <button class="toast-close-btn" onclick="dismissToast(this.closest('.toast-item'))">
                <i class="fas fa-times"></i>
            </button>`;
        container.appendChild(toast);
        gsap.fromTo(toast, { y: -32, opacity: 0, scale: 0.88 }, { y: 0, opacity: 1, scale: 1, duration: 0.48, ease: 'back.out(1.7)' });
        let timer = setTimeout(() => dismissToast(toast), TOAST_DURATION);
        toast.addEventListener('mouseenter', () => clearTimeout(timer));
        toast.addEventListener('mouseleave', () => { timer = setTimeout(() => dismissToast(toast), 1500); });
    };
    window.dismissToast = function (toast) {
        if (!toast || toast.dataset.dismissing) return;
        toast.dataset.dismissing = '1';
        gsap.to(toast, { y: -16, opacity: 0, scale: 0.9, duration: 0.32, ease: 'power2.in', onComplete: () => toast.remove() });
    };

    @if(session('success')) showToast(@json(session('success')), 'success'); @endif
    @if(session('error'))   showToast(@json(session('error')),   'error');   @endif
    @if(session('warning')) showToast(@json(session('warning')), 'warning'); @endif
    @if(session('info'))    showToast(@json(session('info')),    'info');    @endif

    /* ════════════════════════════════════════════════════════════════════
       10. FAQ AI CHAT WIDGET
           Calls your own Laravel backend at /api/faq-chat
           → FaqChatController → Groq API (server-side, key never exposed)
    ════════════════════════════════════════════════════════════════════ */
    (function () {

        /* ─── Config — backend route only, no API key in JS ─── */
        const FAQ_ENDPOINT = '/faq-chat';
        const CSRF_TOKEN   = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        @auth
        const USER_INITIAL  = '{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}';
        const USER_GREETING = 'Hey {{ addslashes(auth()->user()->name) }}! 👋';
        @else
        const USER_INITIAL  = 'G';
        const USER_GREETING = 'Hey there! 👋';
        @endauth

        /* System prompt is sent to the backend and forwarded server-side */
        const SYSTEM_PROMPT = `You are VELO, the enthusiastic and helpful AI assistant for VELOCE VANTAGE — a premium automotive e-commerce store specializing in Ferrari and high-performance vehicles.

You assist customers with:
- Product catalog (Ferrari models, parts, accessories, merchandise)
- How to browse and add items to cart
- Placing and confirming orders
- Payment methods accepted: credit/debit card, bank transfer, cash on delivery (COD)
- Order tracking and estimated delivery times
- Returns and refunds (7-day return window, item must be unused)
- Account creation, login issues, and profile management
- Ongoing promotions, discounts, and loyalty rewards
- Contacting human support via the Contact page

Keep responses friendly, concise (under 120 words), and enthusiastic about cars. Use emojis sparingly to keep it professional. If you don't have specific info, direct the customer to the Contact page or support email.`;

        /* ─── State ─── */
        let chatOpen    = false;
        let isTyping    = false;
        let chatHistory = [];
        let msgCount    = 0;

        /* ─── Elements ─── */
        const trigger     = document.getElementById('faqTrigger');
        const panel       = document.getElementById('faqPanel');
        const closeBtn    = document.getElementById('faqCloseBtn');
        const clearBtn    = document.getElementById('faqClearBtn');
        const msgArea     = document.getElementById('faqMessages');
        const input       = document.getElementById('faqInput');
        const sendBtn     = document.getElementById('faqSend');
        const suggestions = document.getElementById('faqSuggestions');
        const triggerIcon = document.getElementById('faqTriggerIcon');

        /* ─── GSAP init ─── */
        gsap.set(panel, { scale: 0.86, opacity: 0, y: 22, transformOrigin: 'bottom right' });

        /* ─── Open / Close ─── */
        function openPanel() {
            chatOpen = true;
            panel.style.pointerEvents = 'all';
            triggerIcon.className = 'fas fa-times';
            gsap.to(panel, { scale: 1, opacity: 1, y: 0, duration: 0.40, ease: 'back.out(1.7)' });
            if (chatHistory.length === 0) {
                addDateSeparator('Today');
                appendMessage('bot', USER_GREETING + '\n\nI\'m **VELO**, your Veloce Vantage AI assistant. Ask me anything — cars, orders, payments, and more! 🚗💨');
            }
            setTimeout(() => input.focus(), 380);
        }

        function closePanel() {
            chatOpen = false;
            panel.style.pointerEvents = 'none';
            triggerIcon.className = 'fas fa-robot';
            gsap.to(panel, { scale: 0.88, opacity: 0, y: 18, duration: 0.30, ease: 'power2.in' });
        }

        function clearChat() {
            chatHistory = [];
            msgCount    = 0;
            msgArea.innerHTML = '';
            suggestions.style.display = 'flex';
            addDateSeparator('Today');
            appendMessage('bot', USER_GREETING + '\n\nChat cleared! I\'m ready to help again. What can I do for you? 😊');
        }

        /* ─── Events ─── */
        trigger.addEventListener('click', () => chatOpen ? closePanel() : openPanel());
        closeBtn.addEventListener('click', closePanel);
        clearBtn.addEventListener('click', clearChat);
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && chatOpen) closePanel(); });
        document.addEventListener('click', e => {
            if (chatOpen && !panel.contains(e.target) && !trigger.contains(e.target)) closePanel();
        });
        panel.addEventListener('click', e => e.stopPropagation());

        /* ─── Date Separator ─── */
        function addDateSeparator(label) {
            const sep = document.createElement('div');
            sep.className = 'faq-date-sep';
            sep.textContent = label;
            msgArea.appendChild(sep);
        }

        /* ─── Append Message ─── */
        function appendMessage(role, rawText) {
            msgCount++;
            const wrapper = document.createElement('div');
            wrapper.className = `faq-msg ${role}`;

            const avatar = document.createElement('div');
            avatar.className = 'faq-msg-avatar';
            if (role === 'bot') {
                avatar.innerHTML = '<i class="fas fa-robot"></i>';
            } else {
                avatar.textContent = USER_INITIAL;
            }

            const bubble = document.createElement('div');
            bubble.className = 'faq-msg-bubble';
            const escaped = rawText
                .replace(/&/g,'&amp;').replace(/</g,'&lt;')
                .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
            bubble.innerHTML = escaped
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\n/g, '<br>');

            wrapper.appendChild(avatar);
            wrapper.appendChild(bubble);
            msgArea.appendChild(wrapper);
            scrollToBottom();
        }

        /* ─── Typing Indicator ─── */
        function showTyping() {
            const wrap = document.createElement('div');
            wrap.className = 'faq-typing-wrap';
            wrap.id = 'faqTypingWrap';
            const avatar = document.createElement('div');
            avatar.className = 'faq-msg-avatar';
            avatar.style.cssText = 'background:rgba(220,0,0,0.10);border:1px solid rgba(220,0,0,0.28);color:var(--ferrari-red);width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;font-size:12px;';
            avatar.innerHTML = '<i class="fas fa-robot"></i>';
            const typing = document.createElement('div');
            typing.className = 'faq-typing';
            typing.innerHTML = '<span></span><span></span><span></span>';
            wrap.appendChild(avatar);
            wrap.appendChild(typing);
            msgArea.appendChild(wrap);
            scrollToBottom();
        }

        function hideTyping() {
            const el = document.getElementById('faqTypingWrap');
            if (el) el.remove();
        }

        function scrollToBottom() {
            msgArea.scrollTop = msgArea.scrollHeight;
        }

        /* ─── Send Message — calls Laravel backend (secure, no exposed key) ─── */
        async function sendMessage(text) {
            text = text.trim();
            if (!text || isTyping) return;

            if (chatHistory.length === 0 && suggestions.style.display !== 'none') {
                suggestions.style.display = 'none';
            }

            appendMessage('user', text);
            chatHistory.push({ role: 'user', content: text });

            /* Keep last 20 messages to stay within token limits */
            if (chatHistory.length > 20) chatHistory = chatHistory.slice(-20);

            input.value = '';
            input.style.height = 'auto';
            sendBtn.disabled = true;
            isTyping = true;
            showTyping();

            try {
                const response = await fetch(FAQ_ENDPOINT, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept':       'application/json',
                    },
                    body: JSON.stringify({
                        messages: chatHistory,
                        system:   SYSTEM_PROMPT,
                    }),
                });

                const data = await response.json();
                hideTyping();

                if (!response.ok || data.error) {
                    const errMsg = data?.error || ('Server error ' + response.status);
                    console.error('[VELO FAQ] Backend error:', data);
                    appendMessage('bot', '⚠️ ' + errMsg);
                    return;
                }

                if (data.reply) {
                    const clean = data.reply.trim();
                    appendMessage('bot', clean);
                    chatHistory.push({ role: 'assistant', content: clean });
                } else {
                    appendMessage('bot', '⚠️ No response received. Please try again.');
                }

            } catch (err) {
                hideTyping();
                appendMessage('bot', '⚠️ Connection error. Please check your internet and try again.');
                console.error('[VELO FAQ] Fetch error:', err);
            } finally {
                isTyping = false;
                sendBtn.disabled = input.value.trim().length === 0;
                input.focus();
            }
        }

        /* ─── Input events ─── */
        input.addEventListener('input', function () {
            sendBtn.disabled = this.value.trim().length === 0;
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (!sendBtn.disabled) sendMessage(this.value);
            }
        });
        sendBtn.addEventListener('click', () => sendMessage(input.value));

        /* ─── Suggestion Chips ─── */
        document.querySelectorAll('.faq-chip').forEach(chip => {
            chip.addEventListener('click', () => {
                const q = chip.dataset.q;
                if (q && !isTyping) sendMessage(q);
            });
        });

        /* ─── Entrance animation ─── */
        gsap.from(trigger, { scale: 0, opacity: 0, duration: 0.55, delay: 1.4, ease: 'back.out(1.9)' });
        trigger.addEventListener('mouseenter', () => { if (!chatOpen) gsap.to(trigger, { scale: 1.10, duration: 0.28, ease: 'back.out(2)' }); });
        trigger.addEventListener('mouseleave', () => { gsap.to(trigger, { scale: 1, duration: 0.3, ease: 'power2.inOut' }); });
        trigger.addEventListener('mousedown',  () => { gsap.to(trigger, { scale: 0.92, duration: 0.1, ease: 'power1.in' }); });
        trigger.addEventListener('mouseup',    () => { gsap.to(trigger, { scale: chatOpen ? 1 : 1.05, duration: 0.25, ease: 'back.out(2)' }); });

    })();
    /* ── END FAQ WIDGET ── */

} // end initGSAP
</script>

@stack('scripts')

</body>
</html>