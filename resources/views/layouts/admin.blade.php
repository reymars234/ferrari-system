<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Veloce Vantage</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- GSAP Core + Plugins --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <style>
        *,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
        :root {
            --red:#dc0000; --yellow:#f5c518;
            --dark:#0d0d0d; --dark2:#1a1a1a; --dark3:#252525;
            --light:#e8e8e8; --gray:#888;
            --ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
            --ease-smooth: cubic-bezier(0.4, 0, 0.2, 1);
        }
        body {
            background:var(--dark); color:var(--light);
            font-family:'Barlow',sans-serif; display:flex; min-height:100vh;
            overflow-x:hidden;
        }
        a { color:inherit; text-decoration:none; }

        /* ═══════════════════════════════════════════
           HAMBURGER TOGGLE
        ═══════════════════════════════════════════ */
        .sidebar-toggle {
            display:none;
            position:fixed; top:10px; left:12px; z-index:200;
            background:var(--dark2);
            border:1px solid rgba(220,0,0,0.25);
            color:var(--light);
            width:38px; height:38px;
            border-radius:6px; font-size:16px; cursor:pointer;
            align-items:center; justify-content:center;
            will-change:transform;
        }

        /* Sidebar overlay backdrop */
        .sidebar-backdrop {
            display:none; position:fixed; inset:0;
            background:rgba(0,0,0,0.65); backdrop-filter:blur(3px);
            z-index:90; opacity:0;
        }

        /* ═══════════════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════════════ */
        .sidebar {
            width:240px; background:var(--dark2);
            border-right:1px solid rgba(220,0,0,0.12);
            padding:0; display:flex; flex-direction:column;
            position:fixed; top:0; left:0; bottom:0; z-index:100;
            background-image:url('/images/admin-bg.jpg');
            background-size:cover; background-position:center;
            will-change:transform;
            opacity:0; transform:translateX(-100%);
        }
        .sidebar::before {
            content:''; position:absolute; inset:0; z-index:0;
            background:rgba(10,10,10,0.82);
        }
        .sidebar > * { position:relative; z-index:1; }

        .sidebar-brand {
            padding:22px 20px; border-bottom:1px solid rgba(220,0,0,0.15);
            display:flex; align-items:center; gap:10px;
            opacity:0;
        }
        .sidebar-logo {
            height:28px; width:auto; flex-shrink:0;
            filter:drop-shadow(0 0 6px rgba(220,0,0,0.4));
            will-change:transform, filter;
        }
        .sidebar-logo-fallback {
            width:28px; height:28px; border-radius:50%; flex-shrink:0;
            background:rgba(220,0,0,0.1); border:1px solid rgba(220,0,0,0.3);
            display:flex; align-items:center; justify-content:center;
        }
        .sidebar-logo-fallback i { color:var(--red); font-size:13px; }
        .sidebar-brand-title {
            font-family:'Bebas Neue',sans-serif;
            font-size:16px; letter-spacing:2.5px; color:var(--red); line-height:1;
        }
        .sidebar-brand-sub { font-size:8px; color:#444; letter-spacing:2px; text-transform:uppercase; margin-top:2px; }

        .sidebar-nav { flex:1; padding:16px 0; overflow-y:auto; scrollbar-width:none; }
        .sidebar-nav::-webkit-scrollbar { display:none; }

        .nav-section {
            padding:10px 20px 4px;
            font-size:9px; letter-spacing:2.5px; text-transform:uppercase; color:#333;
            opacity:0;
        }
        .nav-item {
            display:flex; align-items:center; gap:11px;
            padding:11px 20px; font-size:13px; font-weight:600;
            color:var(--gray); border-left:2px solid transparent;
            cursor:pointer; will-change:transform;
            opacity:0;
            transition: color 0.22s var(--ease-smooth),
                        background 0.22s var(--ease-smooth),
                        border-color 0.22s var(--ease-smooth);
        }
        .nav-item.active { color:var(--red); border-left-color:var(--red); background:rgba(220,0,0,0.08); }
        .nav-item i { width:16px; text-align:center; font-size:13px; will-change:transform; }

        .sidebar-footer {
            padding:16px 20px; border-top:1px solid rgba(220,0,0,0.1);
            opacity:0;
        }
        .sidebar-user { display:flex; align-items:center; gap:8px; margin-bottom:10px; }
        .sidebar-user-avatar {
            width:32px; height:32px; border-radius:50%;
            background:rgba(220,0,0,0.1); border:1px solid rgba(220,0,0,0.3);
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }
        .sidebar-user-avatar i { color:var(--red); font-size:13px; }
        .sidebar-user-name { font-size:12px; font-weight:600; color:var(--light); }
        .sidebar-user-role { font-size:9px; color:#444; letter-spacing:1.5px; text-transform:uppercase; }

        .btn-logout {
            width:100%; background:transparent;
            border:1px solid rgba(220,0,0,0.2); color:var(--gray);
            padding:9px; border-radius:4px; cursor:pointer;
            font-size:11px; letter-spacing:1.5px; text-transform:uppercase;
            font-family:'Barlow',sans-serif; font-weight:700;
            display:flex; align-items:center; justify-content:center; gap:7px;
            will-change:transform;
            transition: border-color 0.22s, color 0.22s, background 0.22s;
        }
        .btn-logout:hover { border-color:var(--red); color:var(--red); background:rgba(220,0,0,0.06); }

        /* ═══════════════════════════════════════════
           MAIN
        ═══════════════════════════════════════════ */
        .main-content { margin-left:240px; flex:1; display:flex; flex-direction:column; min-height:100vh; }

        .topbar {
            background:rgba(26,26,26,0.95); border-bottom:1px solid rgba(220,0,0,0.1);
            padding:0 32px; height:58px;
            display:flex; align-items:center; justify-content:space-between;
            backdrop-filter:blur(8px); position:sticky; top:0; z-index:50;
            opacity:0;
        }
        .topbar-title { font-family:'Bebas Neue',sans-serif; font-size:22px; letter-spacing:3px; }
        .topbar-user { color:var(--gray); font-size:13px; }
        .topbar-user strong { color:var(--light); }

        .content-area { padding:32px; flex:1; }

        /* ═══════════════════════════════════════════
           STAT CARDS
        ═══════════════════════════════════════════ */
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:18px; margin-bottom:28px; }
        .stat-card {
            background:var(--dark2); border:1px solid rgba(220,0,0,0.08);
            border-radius:10px; padding:22px;
            will-change:transform;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .stat-card:hover { border-color:rgba(220,0,0,0.3); box-shadow:0 12px 36px rgba(220,0,0,0.1); }
        .stat-icon { font-size:26px; margin-bottom:10px; display:block; will-change:transform; }
        .stat-value { font-family:'Bebas Neue',sans-serif; font-size:34px; letter-spacing:2px; line-height:1; }
        .stat-label { color:var(--gray); font-size:10px; letter-spacing:2px; text-transform:uppercase; margin-top:5px; }

        /* ═══════════════════════════════════════════
           BUTTONS
        ═══════════════════════════════════════════ */
        .btn {
            display:inline-flex; align-items:center; gap:6px;
            padding:10px 22px; border-radius:4px; font-weight:700;
            font-size:12px; letter-spacing:1.5px; text-transform:uppercase;
            cursor:pointer; border:none;
            font-family:'Barlow',sans-serif;
            position:relative; overflow:hidden;
            will-change:transform;
            transition: background 0.22s, box-shadow 0.22s, color 0.22s;
        }
        .btn::after {
            content:''; position:absolute; inset:0;
            background:linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.12) 50%, transparent 70%);
            transform:translateX(-100%); pointer-events:none;
        }
        .btn-red    { background:var(--red); color:#fff; }
        .btn-red:hover { background:#b00000; box-shadow:0 8px 22px rgba(220,0,0,0.3); }
        .btn-outline { background:transparent; border:1px solid var(--red); color:var(--red); }
        .btn-outline:hover { background:var(--red); color:#fff; box-shadow:0 8px 22px rgba(220,0,0,0.25); }
        .btn-sm     { padding:6px 14px; font-size:11px; }
        .btn-gray   { background:#2a2a2a; color:var(--gray); border:1px solid #333; }
        .btn-gray:hover { background:#333; color:var(--light); }
        .btn-danger { background:#330000; color:#ff4444; border:1px solid rgba(255,68,68,0.4); }
        .btn-danger:hover { background:#550000; box-shadow:0 6px 18px rgba(255,0,0,0.2); }

        /* ═══════════════════════════════════════════
           CARD
        ═══════════════════════════════════════════ */
        .card {
            background:var(--dark2); border:1px solid rgba(220,0,0,0.08);
            border-radius:10px; overflow:hidden;
            transition:border-color 0.3s;
        }
        .card:hover { border-color:rgba(220,0,0,0.18); }
        .card-header { padding:18px 24px; border-bottom:1px solid #1e1e1e; display:flex; align-items:center; justify-content:space-between; }
        .card-header h3 { font-family:'Bebas Neue',sans-serif; font-size:20px; letter-spacing:2px; }
        .card-body { padding:24px; }

        /* ═══════════════════════════════════════════
           TABLE
        ═══════════════════════════════════════════ */
        .table-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }
        table { width:100%; border-collapse:collapse; }
        th,td { padding:12px 16px; text-align:left; border-bottom:1px solid #1a1a1a; font-size:13px; }
        th { background:var(--dark3); color:var(--gray); font-size:9px; letter-spacing:2px; text-transform:uppercase; }
        tr { transition:background 0.2s; }
        tr:hover td { background:rgba(220,0,0,0.03); }

        /* ═══════════════════════════════════════════
           FORM
        ═══════════════════════════════════════════ */
        .form-group { margin-bottom:18px; }
        .form-group label { display:block; margin-bottom:5px; font-size:10px; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:var(--gray); }
        .form-control {
            width:100%; padding:10px 14px; background:var(--dark3);
            border:1px solid #2a2a2a; border-radius:4px; color:var(--light);
            font-size:14px; font-family:'Barlow',sans-serif;
            transition:border-color 0.25s, box-shadow 0.25s;
        }
        .form-control:focus { outline:none; border-color:var(--red); box-shadow:0 0 0 3px rgba(220,0,0,0.08); }
        .form-error { color:var(--red); font-size:11px; margin-top:4px; }

        /* ═══════════════════════════════════════════
           BADGES
        ═══════════════════════════════════════════ */
        .badge { display:inline-block; padding:2px 10px; border-radius:20px; font-size:10px; font-weight:700; letter-spacing:1px; text-transform:uppercase; }
        .badge-pending    { background:#332a00; color:var(--yellow); border:1px solid var(--yellow); }
        .badge-processing { background:#002266; color:#4488ff; border:1px solid #4488ff; }
        .badge-delivered  { background:#00331a; color:#1db954; border:1px solid #1db954; }
        .badge-cancelled  { background:#330000; color:#ff4444; border:1px solid #ff4444; }
        .badge-user       { background:#1a1a2e; color:#9988ff; border:1px solid #9988ff; }
        .badge-admin      { background:#2f0a0a; color:var(--red); border:1px solid var(--red); }

        /* ═══════════════════════════════════════════
           MODAL
        ═══════════════════════════════════════════ */
        .modal-overlay {
            position:fixed; inset:0; z-index:9999;
            background:rgba(0,0,0,0.72); backdrop-filter:blur(6px);
            display:flex; align-items:center; justify-content:center;
            opacity:0; pointer-events:none;
        }
        .modal-overlay.open { pointer-events:all; }
        .modal-box {
            background:var(--dark2); border:1px solid rgba(220,0,0,0.25);
            border-radius:14px; padding:40px 36px;
            max-width:360px; width:90%; text-align:center;
            will-change:transform, opacity;
        }
        .modal-icon {
            width:56px; height:56px; border-radius:50%;
            background:rgba(220,0,0,0.1); border:1px solid rgba(220,0,0,0.3);
            display:flex; align-items:center; justify-content:center;
            margin:0 auto 20px; font-size:22px; color:var(--red);
            animation:modalPulse 2.8s ease infinite;
        }
        @keyframes modalPulse {
            0%,100% { box-shadow:0 0 0 0 rgba(220,0,0,0.22); }
            50%      { box-shadow:0 0 0 14px rgba(220,0,0,0); }
        }
        .modal-title { font-family:'Bebas Neue',sans-serif; font-size:24px; letter-spacing:3px; margin-bottom:8px; }
        .modal-desc  { color:var(--gray); font-size:13px; margin-bottom:28px; line-height:1.7; }
        .modal-actions { display:flex; gap:10px; justify-content:center; flex-wrap:wrap; }

        /* ═══════════════════════════════════════════
           TOAST
        ═══════════════════════════════════════════ */
        .toast-container {
            position:fixed; top:24px; left:50%; transform:translateX(-50%);
            z-index:999999; display:flex; flex-direction:column;
            align-items:center; gap:10px; pointer-events:none;
            width:auto; min-width:320px; max-width:460px;
        }
        .toast {
            display:flex; align-items:flex-start; gap:13px;
            padding:15px 18px; border-radius:12px;
            background:rgba(22,22,22,0.97); backdrop-filter:blur(20px);
            box-shadow:0 16px 48px rgba(0,0,0,0.5), 0 2px 8px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.04);
            pointer-events:all; position:relative; overflow:hidden;
            cursor:default; will-change:transform, opacity;
            opacity:0;
        }
        .toast::before {
            content:''; position:absolute; left:0; top:0; bottom:0;
            width:3px; border-radius:12px 0 0 12px;
        }
        .toast-progress {
            position:absolute; bottom:0; left:0; height:2px;
            border-radius:0 0 12px 12px; transform-origin:left center;
            animation:toastDrain 3s linear both;
        }
        .toast-success { border:1px solid rgba(29,185,84,0.25); }
        .toast-success::before { background:#1db954; }
        .toast-success .toast-icon-wrap { background:rgba(29,185,84,0.1); border:1px solid rgba(29,185,84,0.25); color:#1db954; }
        .toast-success .toast-progress  { background:linear-gradient(to right,#1db954,#4ade80); }
        .toast-error   { border:1px solid rgba(220,0,0,0.25); }
        .toast-error::before { background:var(--red); }
        .toast-error .toast-icon-wrap   { background:rgba(220,0,0,0.1); border:1px solid rgba(220,0,0,0.25); color:var(--red); }
        .toast-error .toast-progress    { background:linear-gradient(to right,var(--red),#ff6b6b); }
        .toast-warning { border:1px solid rgba(245,197,24,0.25); }
        .toast-warning::before { background:var(--yellow); }
        .toast-warning .toast-icon-wrap { background:rgba(245,197,24,0.1); border:1px solid rgba(245,197,24,0.25); color:var(--yellow); }
        .toast-warning .toast-progress  { background:linear-gradient(to right,var(--yellow),#fde68a); }
        .toast-info    { border:1px solid rgba(58,142,246,0.25); }
        .toast-info::before { background:#3a8ef6; }
        .toast-info .toast-icon-wrap    { background:rgba(58,142,246,0.1); border:1px solid rgba(58,142,246,0.25); color:#3a8ef6; }
        .toast-info .toast-progress     { background:linear-gradient(to right,#3a8ef6,#93c5fd); }
        .toast-icon-wrap { width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:14px; margin-top:1px; }
        .toast-body { flex:1; min-width:0; }
        .toast-title { font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:var(--gray); margin-bottom:3px; }
        .toast-msg   { font-size:13px; font-weight:500; color:var(--light); line-height:1.5; word-break:break-word; }
        .toast-close { background:none; border:none; color:#444; cursor:pointer; font-size:11px; padding:2px; flex-shrink:0; margin-top:2px; line-height:1; transition:color 0.2s, transform 0.2s; }
        .toast-close:hover { color:var(--light); transform:scale(1.2); }
        @keyframes toastDrain { from{transform:scaleX(1)} to{transform:scaleX(0)} }

        /* ═══════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════ */
        @media (max-width:900px) {
            .sidebar-toggle { display:flex; }
            .sidebar { opacity:1; transform:translateX(-100%); }
            .main-content { margin-left:0; }
            .topbar { padding:0 16px 0 60px; height:54px; }
            .topbar-title { font-size:18px; letter-spacing:2px; }
            .topbar-user  { display:none; }
            .content-area { padding:20px 16px; }
            .toast-container { min-width:280px; max-width:calc(100vw - 32px); }
        }
        @media (max-width:540px) {
            .stats-grid { grid-template-columns:repeat(2,1fr); gap:12px; }
            .stat-card  { padding:16px; }
            .stat-value { font-size:28px; }
            .card-header{ padding:14px 16px; flex-wrap:wrap; gap:10px; }
            .card-body  { padding:16px; }
            .card-header h3 { font-size:17px; }
            th,td { padding:10px 12px; font-size:12px; white-space:nowrap; }
            .btn  { padding:9px 16px; font-size:11px; }
            .modal-box  { padding:28px 20px; }
            .topbar { padding:0 12px 0 58px; }
            .topbar-title { font-size:16px; letter-spacing:1.5px; }
        }
        @media (max-width:380px) {
            .stats-grid { grid-template-columns:1fr; }
            .content-area { padding:14px 12px; }
            .sidebar-toggle { top:8px; left:8px; width:34px; height:34px; font-size:14px; }
            .topbar { padding:0 10px 0 52px; height:50px; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ════════════════════════════════════════════
     SPLASH — injected here from dashboard.blade
     so it sits at body root, above sidebar/layout
════════════════════════════════════════════ --}}
@stack('splash')

{{-- ════════════════════════════════════════════
     HAMBURGER TOGGLE
════════════════════════════════════════════ --}}
<button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle menu">
    <span id="hbIcon" class="fas fa-bars"></span>
</button>

{{-- SIDEBAR BACKDROP --}}
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

{{-- TOAST CONTAINER --}}
<div class="toast-container" id="toastContainer" aria-live="polite" aria-label="Notifications"></div>

{{-- ════════════════════════════════════════════
     LOGOUT MODAL
════════════════════════════════════════════ --}}
<div class="modal-overlay" id="logoutModal">
    <div class="modal-box" id="logoutModalBox">
        <div class="modal-icon"><i class="fas fa-sign-out-alt"></i></div>
        <div class="modal-title">SIGN OUT</div>
        <div class="modal-desc">Are you sure you want to log out of the admin panel?</div>
        <div class="modal-actions">
            <button class="btn btn-gray" onclick="closeLogout()">
                <i class="fas fa-times"></i> No, Stay
            </button>
            {{--
                IMPORTANT: The logout form clears the splash sessionStorage key
                so the animation plays fresh on the next login.
                We intercept the form submit in JS to do this before the POST.
            --}}
            <form method="POST" action="{{ route('logout') }}" style="margin:0" id="logoutForm">
                @csrf
                <button type="submit" class="btn btn-danger" id="logoutConfirmBtn">
                    <i class="fas fa-sign-out-alt"></i> Yes, Logout
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════ --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand" id="sidebarBrand">
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="sidebar-logo" id="sidebarLogo">
        @else
            <div class="sidebar-logo-fallback"><i class="fas fa-horse"></i></div>
        @endif
        <div>
            <div class="sidebar-brand-title">Veloce Vantage ADMIN</div>
            <div class="sidebar-brand-sub">Management Panel</div>
        </div>
    </div>

    <nav class="sidebar-nav" id="sidebarNav">
        <div class="nav-section">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item" data-route="admin.dashboard">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <div class="nav-section">Team</div>
        <a href="{{ route('admin.drivers.index') }}" class="nav-item" data-route="admin.drivers">
            <i class="fas fa-truck"></i> Drivers
        </a>

        <div class="nav-section">Catalog</div>
        <a href="{{ route('admin.cars.index') }}" class="nav-item" data-route="admin.cars">
            <i class="fas fa-car"></i> Cars
        </a>

        <div class="nav-section">Commerce</div>
        <a href="{{ route('admin.orders.index') }}" class="nav-item" data-route="admin.orders">
            <i class="fas fa-shopping-bag"></i> Orders
        </a>

        <div class="nav-section">People</div>
        <a href="{{ route('admin.users.index') }}" class="nav-item" data-route="admin.users">
            <i class="fas fa-users"></i> Users
        </a>

        <div class="nav-section">Security</div>
        <a href="{{ route('admin.audit.index') }}" class="nav-item" data-route="admin.audit">
            <i class="fas fa-shield-alt"></i> Audit Logs
        </a>

        <div class="nav-section">Analytics</div>
        <a href="{{ route('admin.reports.index') }}" class="nav-item" data-route="admin.reports">
            <i class="fas fa-chart-bar"></i> Reports
        </a>

        <div class="nav-section">Site</div>
        <a href="{{ route('home') }}" class="nav-item" target="_blank">
            <i class="fas fa-globe"></i> View Site
        </a>
    </nav>

    <div class="sidebar-footer" id="sidebarFooter">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar"><i class="fas fa-user-shield"></i></div>
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">Administrator</div>
            </div>
        </div>
        <button class="btn-logout" id="logoutBtn" onclick="openLogout()">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </div>
</aside>

{{-- ════════════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════════ --}}
<div class="main-content">
    <div class="topbar" id="adminTopbar">
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-user">
            Logged in as <strong>{{ auth()->user()->name }}</strong>
        </div>
    </div>

    <div class="content-area" id="contentArea">
        @yield('content')
    </div>
</div>

<script>
(function () {

    /* ══════════════════════════════════════════════════════════════
       LOGOUT FORM — clear splash flag BEFORE the POST fires
       so that on the next login the splash plays fresh again.
    ══════════════════════════════════════════════════════════════ */
    var logoutForm = document.getElementById('logoutForm');
    if (logoutForm) {
        logoutForm.addEventListener('submit', function () {
            sessionStorage.removeItem('vv_splash_shown');
        });
    }

    /* ══════════════════════════════════════════════════════════════
       BAIL-SAFE: if GSAP somehow not loaded
    ══════════════════════════════════════════════════════════════ */
    if (typeof gsap === 'undefined') {
        document.querySelectorAll('.sidebar,.sidebar-brand,.nav-section,.nav-item,.sidebar-footer,.topbar')
            .forEach(function(el) { el.style.opacity='1'; el.style.transform='none'; });
        bootLegacyHandlers();
        return;
    }

    gsap.registerPlugin(ScrollTrigger);

    /* ══════════════════════════════════════════════════════════════
       1. PAGE ENTRANCE TIMELINE
    ══════════════════════════════════════════════════════════════ */
    var masterTl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    masterTl
        .to('#sidebar', { x: 0, opacity: 1, duration: 0.65, ease: 'power4.out' })
        .to('#sidebarBrand', { opacity: 1, y: 0, duration: 0.45, ease: 'back.out(1.5)' }, '-=0.35')
        .to('#sidebarNav .nav-section, #sidebarNav .nav-item', {
            opacity: 1, x: 0, duration: 0.38,
            stagger: { each: 0.055, ease: 'power1.inOut' },
            ease: 'power2.out'
        }, '-=0.25')
        .to('#sidebarFooter', { opacity: 1, y: 0, duration: 0.4, ease: 'back.out(1.4)' }, '-=0.2')
        .from('#adminTopbar', {
            y: -32, opacity: 0, duration: 0.5, ease: 'power3.out',
            clearProps: 'all',
            onStart: function() { gsap.set('#adminTopbar', { opacity: 1 }); }
        }, '-=0.45')
        .from('#contentArea', {
            y: 22, opacity: 0, duration: 0.55, ease: 'power3.out', clearProps: 'all'
        }, '-=0.3');

    gsap.set('#sidebarNav .nav-section, #sidebarNav .nav-item', { x: -16, opacity: 0 });
    gsap.set('#sidebarFooter', { y: 16, opacity: 0 });
    gsap.set('#sidebarBrand',  { y: -10, opacity: 0 });
    gsap.set('#adminTopbar',   { opacity: 0 });

    /* ══════════════════════════════════════════════════════════════
       2. SIDEBAR BRAND HOVER — magnetic logo
    ══════════════════════════════════════════════════════════════ */
    var sidebarLogo  = document.getElementById('sidebarLogo');
    var sidebarBrand = document.getElementById('sidebarBrand');
    if (sidebarBrand) {
        sidebarBrand.addEventListener('mouseenter', function() {
            if (sidebarLogo) gsap.to(sidebarLogo, {
                scale: 1.1,
                filter: 'drop-shadow(0 0 14px rgba(220,0,0,0.75))',
                duration: 0.35, ease: 'back.out(1.5)'
            });
        });
        sidebarBrand.addEventListener('mouseleave', function() {
            if (sidebarLogo) gsap.to(sidebarLogo, {
                scale: 1,
                filter: 'drop-shadow(0 0 6px rgba(220,0,0,0.4))',
                duration: 0.4, ease: 'power2.inOut'
            });
        });
    }

    /* ══════════════════════════════════════════════════════════════
       3. NAV ITEM HOVER
    ══════════════════════════════════════════════════════════════ */
    document.querySelectorAll('.nav-item').forEach(function(item) {
        var icon = item.querySelector('i');
        item.addEventListener('mouseenter', function() {
            if (!item.classList.contains('active')) {
                gsap.to(item, { x: 5, duration: 0.28, ease: 'power2.out' });
            }
            if (icon) gsap.to(icon, { scale: 1.18, rotate: -4, duration: 0.28, ease: 'back.out(2)' });
        });
        item.addEventListener('mouseleave', function() {
            if (!item.classList.contains('active')) {
                gsap.to(item, { x: 0, duration: 0.32, ease: 'power2.inOut' });
            }
            if (icon) gsap.to(icon, { scale: 1, rotate: 0, duration: 0.3, ease: 'power2.inOut' });
        });
        if (item.classList.contains('active') && icon) {
            gsap.to(icon, {
                textShadow: '0 0 12px rgba(220,0,0,0.9)',
                duration: 1.2, ease: 'sine.inOut', yoyo: true, repeat: -1
            });
        }
    });

    /* ══════════════════════════════════════════════════════════════
       4. BUTTONS — hover + click feel
    ══════════════════════════════════════════════════════════════ */
    function attachButtonGSAP(selector) {
        document.querySelectorAll(selector).forEach(function(btn) {
            btn.addEventListener('mouseenter', function() {
                gsap.to(btn, { y: -3, scale: 1.03, duration: 0.28, ease: 'back.out(2)' });
                var i = btn.querySelector('i');
                if (i) gsap.to(i, { rotate: -6, duration: 0.22, ease: 'back.out(2)' });
            });
            btn.addEventListener('mouseleave', function() {
                gsap.to(btn, { y: 0, scale: 1, duration: 0.35, ease: 'power2.inOut' });
                var i = btn.querySelector('i');
                if (i) gsap.to(i, { rotate: 0, duration: 0.3, ease: 'power2.inOut' });
            });
            btn.addEventListener('mousedown', function() {
                gsap.to(btn, { scale: 0.94, y: 0, duration: 0.1, ease: 'power2.in' });
            });
            btn.addEventListener('mouseup', function() {
                gsap.to(btn, { scale: 1, duration: 0.38, ease: 'back.out(2.5)' });
            });
        });
    }
    attachButtonGSAP('.btn');

    var logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('mouseenter', function() {
            gsap.to(logoutBtn, { y: -2, duration: 0.25, ease: 'back.out(2)' });
        });
        logoutBtn.addEventListener('mouseleave', function() {
            gsap.to(logoutBtn, { y: 0, duration: 0.3, ease: 'power2.inOut' });
        });
        logoutBtn.addEventListener('mousedown', function() {
            gsap.to(logoutBtn, { scale: 0.95, duration: 0.1 });
        });
        logoutBtn.addEventListener('mouseup', function() {
            gsap.to(logoutBtn, { scale: 1, duration: 0.3, ease: 'back.out(2)' });
        });
    }

    /* ══════════════════════════════════════════════════════════════
       5. STAT CARDS — hover lift + icon bounce
    ══════════════════════════════════════════════════════════════ */
    document.querySelectorAll('.stat-card').forEach(function(card) {
        var icon = card.querySelector('.stat-icon');
        card.addEventListener('mouseenter', function() {
            gsap.to(card, { y: -7, scale: 1.025, duration: 0.35, ease: 'back.out(1.6)' });
            if (icon) gsap.to(icon, { scale: 1.2, rotate: 8, duration: 0.35, ease: 'back.out(2)' });
        });
        card.addEventListener('mouseleave', function() {
            gsap.to(card, { y: 0, scale: 1, duration: 0.4, ease: 'power2.inOut' });
            if (icon) gsap.to(icon, { scale: 1, rotate: 0, duration: 0.35, ease: 'power2.inOut' });
        });
    });

    /* ══════════════════════════════════════════════════════════════
       6. MOBILE SIDEBAR — GSAP hamburger + slide
    ══════════════════════════════════════════════════════════════ */
    var sidebarToggle  = document.getElementById('sidebarToggle');
    var sidebarEl      = document.getElementById('sidebar');
    var backdrop       = document.getElementById('sidebarBackdrop');
    var hbIcon         = document.getElementById('hbIcon');
    var mobileSideOpen = false;
    var mobileTween    = null;

    gsap.set(backdrop, { display: 'none', opacity: 0 });

    function openSidebarMobile() {
        mobileSideOpen = true;
        if (mobileTween) mobileTween.kill();
        gsap.set(backdrop, { display: 'block' });
        mobileTween = gsap.timeline()
            .to(backdrop,  { opacity: 1, duration: 0.3, ease: 'power2.out' })
            .to(sidebarEl, { x: 0,       duration: 0.38, ease: 'power3.out' }, 0);
        gsap.to(sidebarToggle, { rotate: 90, duration: 0.32, ease: 'back.out(2)' });
        hbIcon.className = 'fas fa-times';
        document.body.style.overflow = 'hidden';
    }

    function closeSidebarMobile() {
        mobileSideOpen = false;
        if (mobileTween) mobileTween.kill();
        mobileTween = gsap.timeline({
            onComplete: function() { gsap.set(backdrop, { display: 'none' }); }
        })
            .to(backdrop,  { opacity: 0,    duration: 0.25, ease: 'power2.in' })
            .to(sidebarEl, { x: '-100%',    duration: 0.32, ease: 'power3.in' }, 0);
        gsap.to(sidebarToggle, { rotate: 0, duration: 0.28, ease: 'power2.inOut' });
        hbIcon.className = 'fas fa-bars';
        document.body.style.overflow = '';
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileSideOpen ? closeSidebarMobile() : openSidebarMobile();
        });
    }
    if (backdrop) backdrop.addEventListener('click', closeSidebarMobile);

    document.querySelectorAll('.sidebar-nav .nav-item').forEach(function(item) {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 900) closeSidebarMobile();
        });
    });
    window.addEventListener('resize', function() {
        if (window.innerWidth > 900) { closeSidebarMobile(); document.body.style.overflow = ''; }
    });

    /* Swipe gestures */
    var touchStartX = 0, touchStartY = 0;
    document.addEventListener('touchstart', function(e) {
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
    }, { passive: true });
    document.addEventListener('touchend', function(e) {
        if (window.innerWidth > 900) return;
        var dx = e.changedTouches[0].clientX - touchStartX;
        var dy = Math.abs(e.changedTouches[0].clientY - touchStartY);
        if (dy > 60) return;
        if (dx > 60  && touchStartX < 40) openSidebarMobile();
        if (dx < -60 && mobileSideOpen)   closeSidebarMobile();
    }, { passive: true });

    /* ══════════════════════════════════════════════════════════════
       7. LOGOUT MODAL — spring entrance
    ══════════════════════════════════════════════════════════════ */
    var logoutModal    = document.getElementById('logoutModal');
    var logoutModalBox = document.getElementById('logoutModalBox');
    var logoutOpen     = false;

    gsap.set(logoutModalBox, { y: 36, scale: 0.92, opacity: 0 });

    window.openLogout = function() {
        logoutOpen = true;
        logoutModal.classList.add('open');
        gsap.to(logoutModal,    { opacity: 1, duration: 0.28, ease: 'power2.out' });
        gsap.to(logoutModalBox, { y: 0, scale: 1, opacity: 1, duration: 0.52, ease: 'back.out(1.7)' });
        gsap.from(logoutModalBox.children, {
            y: 12, opacity: 0, duration: 0.3, stagger: 0.07, ease: 'power2.out', delay: 0.15
        });
    };

    window.closeLogout = function() {
        logoutOpen = false;
        gsap.to(logoutModalBox, {
            y: 24, scale: 0.94, opacity: 0,
            duration: 0.3, ease: 'power2.in',
            onComplete: function() {
                logoutModal.classList.remove('open');
                gsap.to(logoutModal, { opacity: 0, duration: 0.01 });
            }
        });
    };

    logoutModal.addEventListener('click', function(e) {
        if (e.target === logoutModal) closeLogout();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (logoutOpen)      closeLogout();
            if (mobileSideOpen)  closeSidebarMobile();
        }
    });

    /* ══════════════════════════════════════════════════════════════
       8. TOAST SYSTEM
    ══════════════════════════════════════════════════════════════ */
    var TOAST_DURATION = 3000;
    var toastConfig = {
        success: { icon: 'fa-check-circle',        label: 'Success' },
        error:   { icon: 'fa-exclamation-circle',   label: 'Error'   },
        warning: { icon: 'fa-exclamation-triangle', label: 'Warning' },
        info:    { icon: 'fa-info-circle',          label: 'Info'    },
    };

    window.showToast = function(message, type) {
        type = type || 'success';
        var cfg       = toastConfig[type] || toastConfig.info;
        var container = document.getElementById('toastContainer');

        var toast = document.createElement('div');
        toast.className = 'toast toast-' + type;
        toast.setAttribute('role', 'alert');
        toast.innerHTML =
            '<div class="toast-icon-wrap"><i class="fas ' + cfg.icon + '"></i></div>' +
            '<div class="toast-body">' +
                '<div class="toast-title">' + cfg.label + '</div>' +
                '<div class="toast-msg">' + message + '</div>' +
            '</div>' +
            '<button class="toast-close" onclick="dismissToast(this.closest(\'.toast\'))">' +
                '<i class="fas fa-times"></i>' +
            '</button>' +
            '<div class="toast-progress"></div>';
        container.appendChild(toast);

        gsap.fromTo(toast,
            { y: -36, opacity: 0, scale: 0.85, rotateX: 14 },
            { y: 0,   opacity: 1, scale: 1,    rotateX: 0,
              duration: 0.52, ease: 'back.out(1.8)' }
        );

        var iconWrap = toast.querySelector('.toast-icon-wrap');
        if (iconWrap) gsap.from(iconWrap, {
            scale: 0, rotate: -20, duration: 0.4, ease: 'back.out(2.5)', delay: 0.08
        });

        var timer = setTimeout(function() { dismissToast(toast); }, TOAST_DURATION);
        var prog  = toast.querySelector('.toast-progress');

        toast.addEventListener('mouseenter', function() {
            clearTimeout(timer);
            if (prog) prog.style.animationPlayState = 'paused';
            gsap.to(toast, { scale: 1.02, duration: 0.22, ease: 'power2.out' });
        });
        toast.addEventListener('mouseleave', function() {
            if (prog) prog.style.animationPlayState = 'running';
            gsap.to(toast, { scale: 1, duration: 0.28, ease: 'power2.inOut' });
            timer = setTimeout(function() { dismissToast(toast); }, 1500);
        });
    };

    window.dismissToast = function(toast) {
        if (!toast || toast.dataset.dismissing) return;
        toast.dataset.dismissing = '1';
        gsap.to(toast, {
            y: -20, opacity: 0, scale: 0.88, rotateX: 10,
            duration: 0.35, ease: 'power2.in',
            onComplete: function() { toast.remove(); }
        });
    };

    /* ══════════════════════════════════════════════════════════════
       9. SCROLL-TRIGGERED CONTENT REVEALS
    ══════════════════════════════════════════════════════════════ */
    ScrollTrigger.batch('.stat-card, .card', {
        start: 'top 90%',
        onEnter: function(batch) {
            gsap.from(batch, {
                y: 28, opacity: 0, scale: 0.97,
                duration: 0.52, stagger: 0.08, ease: 'power3.out', clearProps: 'all'
            });
        }
    });

    /* ══════════════════════════════════════════════════════════════
       10. ACTIVE NAV HIGHLIGHT
    ══════════════════════════════════════════════════════════════ */
    (function updateActiveNav() {
        var path = window.location.pathname;
        document.querySelectorAll('.sidebar-nav .nav-item[data-route]').forEach(function(item) {
            item.classList.remove('active');
            var href = item.getAttribute('href');
            if (href && path.startsWith(href) && href !== '/') {
                item.classList.add('active');
                var icon = item.querySelector('i');
                if (icon) gsap.to(icon, {
                    textShadow: '0 0 14px rgba(220,0,0,1)',
                    duration: 1.4, ease: 'sine.inOut', yoyo: true, repeat: -1
                });
            }
        });
    })();

    /* ─── Laravel session flashes ─── */
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif
        @if(session('error'))
            showToast(@json(session('error')), 'error');
        @endif
        @if(session('warning'))
            showToast(@json(session('warning')), 'warning');
        @endif
        @if(session('info'))
            showToast(@json(session('info')), 'info');
        @endif
    });

})();

/* ── Fallback for no-GSAP ── */
function bootLegacyHandlers() {
    document.getElementById('logoutModal')?.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });
    window.openLogout  = function() { document.getElementById('logoutModal')?.classList.add('open'); };
    window.closeLogout = function() { document.getElementById('logoutModal')?.classList.remove('open'); };
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar')?.classList.toggle('open');
    });
    window.showToast    = function(msg, type) { console.log('[' + type + '] ' + msg); };
    window.dismissToast = function() {};
}
</script>

@stack('scripts')
</body>
</html>