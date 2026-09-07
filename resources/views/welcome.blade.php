<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Creno — Smart Appointment Scheduling</title>
    <meta name="description" content="Creno makes scheduling appointments effortless. Manage your time slots, bookings, and availability all in one place.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --indigo: #6366f1;
            --violet: #8b5cf6;
            --cyan: #06b6d4;
            --pink: #ec4899;
            --bg: #0d0f14;
            --surface: rgba(255,255,255,0.04);
            --border: rgba(255,255,255,0.08);
            --text: #e2e8f0;
            --muted: #64748b;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ─── Background ─── */
        .bg-canvas {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.15;
        }
        .blob-1 {
            width: 700px; height: 700px;
            background: radial-gradient(circle, var(--indigo), var(--violet));
            top: -200px; left: -200px;
            animation: drift1 18s ease-in-out infinite;
        }
        .blob-2 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, var(--cyan), #3b82f6);
            bottom: -150px; right: -100px;
            animation: drift2 14s ease-in-out infinite;
        }
        .blob-3 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, var(--pink), var(--violet));
            top: 50%; left: 60%;
            animation: drift3 20s ease-in-out infinite;
        }
        @keyframes drift1 {
            0%,100% { transform: translate(0,0) scale(1); }
            50% { transform: translate(60px, 40px) scale(1.1); }
        }
        @keyframes drift2 {
            0%,100% { transform: translate(0,0) scale(1); }
            50% { transform: translate(-40px, -60px) scale(1.08); }
        }
        @keyframes drift3 {
            0%,100% { transform: translate(-50%,-50%) scale(1); }
            50% { transform: translate(calc(-50% + 50px), calc(-50% - 30px)) scale(0.9); }
        }
        .bg-grid-lines {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(99,102,241,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* ─── Layout ─── */
        .container {
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 10;
        }

        /* ─── Navbar ─── */
        nav {
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 1.2rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            background: rgba(13,15,20,0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
        }
        .nav-logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--indigo), var(--violet));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 16px rgba(99,102,241,0.4);
        }
        .nav-logo-icon svg { width: 18px; height: 18px; fill: white; }
        .nav-brand {
            font-size: 1.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #e2e8f0, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .btn-ghost {
            padding: 0.5rem 1.1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #94a3b8;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            background: none;
            cursor: pointer;
        }
        .btn-ghost:hover {
            color: #e2e8f0;
            border-color: rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.04);
        }
        .btn-nav-primary {
            padding: 0.5rem 1.1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            background: linear-gradient(135deg, var(--indigo), var(--violet));
            border: none;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(99,102,241,0.3);
        }
        .btn-nav-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99,102,241,0.45);
        }

        /* ─── Hero ─── */
        .hero {
            padding: 7rem 0 5rem;
            text-align: center;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            background: rgba(99,102,241,0.1);
            border: 1px solid rgba(99,102,241,0.2);
            font-size: 0.8rem;
            font-weight: 500;
            color: #a5b4fc;
            margin-bottom: 2rem;
            animation: fadeInDown 0.8s ease both;
        }
        .hero-badge span.dot {
            width: 6px; height: 6px;
            background: #6366f1;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%,100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.85); }
        }
        .hero-title {
            font-size: clamp(2.8rem, 6vw, 4.5rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.8s 0.1s ease both;
        }
        .hero-title .gradient-text {
            background: linear-gradient(135deg, var(--indigo), var(--violet), var(--pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-desc {
            font-size: 1.15rem;
            color: var(--muted);
            max-width: 560px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
            animation: fadeInUp 0.8s 0.2s ease both;
        }
        .hero-cta {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s 0.3s ease both;
        }
        .btn-primary-lg {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.85rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            background: linear-gradient(135deg, var(--indigo), var(--violet));
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: all 0.25s ease;
            box-shadow: 0 8px 25px rgba(99,102,241,0.35);
        }
        .btn-primary-lg:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 35px rgba(99,102,241,0.5);
        }
        .btn-outline-lg {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.85rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            color: #94a3b8;
            text-decoration: none;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.25s ease;
        }
        .btn-outline-lg:hover {
            color: #e2e8f0;
            border-color: rgba(255,255,255,0.18);
            background: rgba(255,255,255,0.07);
            transform: translateY(-1px);
        }

        /* ─── Hero visual ─── */
        .hero-visual {
            margin-top: 5rem;
            position: relative;
            animation: fadeInUp 0.8s 0.4s ease both;
        }
        .hero-card-wrap {
            position: relative;
            max-width: 860px;
            margin: 0 auto;
        }
        .hero-glow {
            position: absolute;
            inset: -60px;
            background: radial-gradient(ellipse 60% 40% at 50% 50%, rgba(99,102,241,0.2), transparent 70%);
            pointer-events: none;
        }
        .dashboard-mockup {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            overflow: hidden;
            box-shadow:
                0 0 0 1px rgba(99,102,241,0.12),
                0 40px 80px -20px rgba(0,0,0,0.7),
                inset 0 1px 0 rgba(255,255,255,0.06);
        }
        .mockup-topbar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.9rem 1.2rem;
            background: rgba(255,255,255,0.02);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .mockup-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
        }
        .dot-red { background: #ff5f57; }
        .dot-yellow { background: #ffbd2e; }
        .dot-green { background: #28c840; }
        .mockup-url {
            margin-left: 0.75rem;
            flex: 1;
            height: 24px;
            background: rgba(255,255,255,0.05);
            border-radius: 6px;
            max-width: 240px;
        }
        .mockup-body {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 1.5rem;
            min-height: 320px;
        }
        .mockup-sidebar {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .sidebar-item {
            padding: 0.55rem 0.75rem;
            border-radius: 8px;
            font-size: 0.8rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .sidebar-item.active {
            background: rgba(99,102,241,0.12);
            color: #a5b4fc;
            border: 1px solid rgba(99,102,241,0.15);
        }
        .sidebar-icon {
            width: 14px; height: 14px;
            border-radius: 3px;
            background: currentColor;
            opacity: 0.5;
        }
        .mockup-content { display: flex; flex-direction: column; gap: 1rem; }
        .mockup-stat-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }
        .stat-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 10px;
            padding: 0.9rem;
        }
        .stat-label {
            font-size: 0.7rem;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 0.35rem;
        }
        .stat-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: #e2e8f0;
        }
        .stat-card.accent { border-color: rgba(99,102,241,0.2); background: rgba(99,102,241,0.06); }
        .stat-card.accent .stat-value { color: #a5b4fc; }
        .appointment-list { display: flex; flex-direction: column; gap: 0.5rem; }
        .appt-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.8rem;
            background: rgba(255,255,255,0.025);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 8px;
        }
        .appt-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--indigo), var(--violet));
            flex-shrink: 0;
        }
        .appt-avatar.cyan { background: linear-gradient(135deg, var(--cyan), #3b82f6); }
        .appt-avatar.pink { background: linear-gradient(135deg, var(--pink), var(--violet)); }
        .appt-info { flex: 1; }
        .appt-name {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 500;
        }
        .appt-time { font-size: 0.68rem; color: #475569; }
        .appt-badge {
            padding: 0.2rem 0.55rem;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .badge-confirmed { background: rgba(34,197,94,0.12); color: #86efac; border: 1px solid rgba(34,197,94,0.2); }
        .badge-pending { background: rgba(234,179,8,0.12); color: #fde047; border: 1px solid rgba(234,179,8,0.2); }
        .badge-upcoming { background: rgba(99,102,241,0.12); color: #a5b4fc; border: 1px solid rgba(99,102,241,0.2); }

        /* ─── Features ─── */
        .features {
            padding: 6rem 0;
        }
        .section-label {
            text-align: center;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--indigo);
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-bottom: 1rem;
        }
        .section-title {
            text-align: center;
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 800;
            letter-spacing: -0.025em;
            margin-bottom: 1rem;
        }
        .section-desc {
            text-align: center;
            color: var(--muted);
            font-size: 1rem;
            max-width: 500px;
            margin: 0 auto 3.5rem;
            line-height: 1.7;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .feature-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.75rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(99,102,241,0.08), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .feature-card:hover {
            border-color: rgba(99,102,241,0.25);
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .feature-card:hover::before { opacity: 1; }
        .feature-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 1.1rem;
            position: relative;
            z-index: 1;
        }
        .icon-indigo { background: rgba(99,102,241,0.15); color: #818cf8; }
        .icon-cyan { background: rgba(6,182,212,0.15); color: #22d3ee; }
        .icon-violet { background: rgba(139,92,246,0.15); color: #a78bfa; }
        .icon-pink { background: rgba(236,72,153,0.15); color: #f472b6; }
        .icon-green { background: rgba(34,197,94,0.15); color: #4ade80; }
        .icon-amber { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .feature-title {
            font-size: 1rem;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }
        .feature-desc {
            font-size: 0.875rem;
            color: var(--muted);
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        /* ─── Stats ─── */
        .stats-section {
            padding: 4rem 0;
            border-top: 1px solid rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 2rem;
            text-align: center;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--indigo), var(--violet));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.03em;
        }
        .stat-desc {
            font-size: 0.875rem;
            color: var(--muted);
            margin-top: 0.25rem;
        }

        /* ─── CTA section ─── */
        .cta-section {
            padding: 6rem 0;
            text-align: center;
        }
        .cta-card {
            background: linear-gradient(135deg, rgba(99,102,241,0.12), rgba(139,92,246,0.08));
            border: 1px solid rgba(99,102,241,0.2);
            border-radius: 24px;
            padding: 4rem 2rem;
            position: relative;
            overflow: hidden;
        }
        .cta-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 400px;
            background: radial-gradient(ellipse, rgba(99,102,241,0.15), transparent 70%);
            pointer-events: none;
        }
        .cta-title {
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 800;
            letter-spacing: -0.025em;
            margin-bottom: 1rem;
            position: relative;
        }
        .cta-desc {
            color: var(--muted);
            font-size: 1rem;
            margin-bottom: 2rem;
            position: relative;
        }
        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            position: relative;
        }

        /* ─── Footer ─── */
        footer {
            padding: 2.5rem 0;
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        .footer-logo-icon {
            width: 28px; height: 28px;
            background: linear-gradient(135deg, var(--indigo), var(--violet));
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .footer-logo-icon svg { width: 14px; height: 14px; fill: white; }
        .footer-brand {
            font-size: 1rem;
            font-weight: 700;
            color: #94a3b8;
        }
        .footer-copy {
            font-size: 0.8rem;
            color: #334155;
        }

        /* ─── Animations ─── */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ─── Responsive ─── */
        @media (max-width: 640px) {
            .mockup-body { grid-template-columns: 1fr; }
            .mockup-sidebar { display: none; }
            .mockup-stat-row { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

<!-- Background -->
<div class="bg-canvas">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="bg-grid-lines"></div>
</div>

<!-- Navbar -->
<nav>
    <div class="container">
        <div class="nav-inner">
            <a href="/" class="nav-logo">
                <div class="nav-logo-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                </div>
                <span class="nav-brand">Creno</span>
            </a>

            @if (Route::has('login'))
                <div class="nav-links">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-nav-primary" id="nav-dashboard-btn">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost" id="nav-login-btn">Sign in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-nav-primary" id="nav-register-btn">Get started</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container">
        <div class="hero-badge">
            <span class="dot"></span>
            Smart appointment management
        </div>

        <h1 class="hero-title">
            Schedule smarter,<br>
            <span class="gradient-text">not harder</span>
        </h1>

        <p class="hero-desc">
            Creno streamlines your appointment booking, time slot management, and client scheduling — all in one elegant platform.
        </p>

        <div class="hero-cta">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary-lg" id="hero-dashboard-btn">
                    Go to Dashboard →
                </a>
            @else
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary-lg" id="hero-get-started-btn">
                        Get started free →
                    </a>
                @endif
                <a href="{{ route('login') }}" class="btn-outline-lg" id="hero-login-btn">
                    Sign in
                </a>
            @endauth
        </div>

        <!-- Dashboard Preview -->
        <div class="hero-visual">
            <div class="hero-card-wrap">
                <div class="hero-glow"></div>
                <div class="dashboard-mockup">
                    <div class="mockup-topbar">
                        <div class="mockup-dot dot-red"></div>
                        <div class="mockup-dot dot-yellow"></div>
                        <div class="mockup-dot dot-green"></div>
                        <div class="mockup-url"></div>
                    </div>
                    <div class="mockup-body">
                        <div class="mockup-sidebar">
                            <div class="sidebar-item active"><div class="sidebar-icon"></div> Dashboard</div>
                            <div class="sidebar-item"><div class="sidebar-icon"></div> Calendar</div>
                            <div class="sidebar-item"><div class="sidebar-icon"></div> Appointments</div>
                            <div class="sidebar-item"><div class="sidebar-icon"></div> Time Slots</div>
                            <div class="sidebar-item"><div class="sidebar-icon"></div> Clients</div>
                            <div class="sidebar-item"><div class="sidebar-icon"></div> Settings</div>
                        </div>
                        <div class="mockup-content">
                            <div class="mockup-stat-row">
                                <div class="stat-card accent">
                                    <div class="stat-label">Today</div>
                                    <div class="stat-value">12</div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-label">This week</div>
                                    <div class="stat-value">48</div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-label">Total</div>
                                    <div class="stat-value">1.2k</div>
                                </div>
                            </div>
                            <div class="appointment-list">
                                <div class="appt-row">
                                    <div class="appt-avatar"></div>
                                    <div class="appt-info">
                                        <div class="appt-name">Marie Dupont</div>
                                        <div class="appt-time">09:00 — 09:30</div>
                                    </div>
                                    <div class="appt-badge badge-confirmed">Confirmed</div>
                                </div>
                                <div class="appt-row">
                                    <div class="appt-avatar cyan"></div>
                                    <div class="appt-info">
                                        <div class="appt-name">Jean Martin</div>
                                        <div class="appt-time">10:15 — 11:00</div>
                                    </div>
                                    <div class="appt-badge badge-upcoming">Upcoming</div>
                                </div>
                                <div class="appt-row">
                                    <div class="appt-avatar pink"></div>
                                    <div class="appt-info">
                                        <div class="appt-name">Sophie Bernard</div>
                                        <div class="appt-time">14:00 — 14:45</div>
                                    </div>
                                    <div class="appt-badge badge-pending">Pending</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div>
                <div class="stat-number">10k+</div>
                <div class="stat-desc">Appointments managed</div>
            </div>
            <div>
                <div class="stat-number">99.9%</div>
                <div class="stat-desc">Uptime guaranteed</div>
            </div>
            <div>
                <div class="stat-number">3min</div>
                <div class="stat-desc">Average setup time</div>
            </div>
            <div>
                <div class="stat-number">4.9★</div>
                <div class="stat-desc">Average user rating</div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="features">
    <div class="container">
        <p class="section-label">Features</p>
        <h2 class="section-title">Everything you need to manage bookings</h2>
        <p class="section-desc">A complete toolkit for professionals who value their time and their clients'.</p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon icon-indigo">📅</div>
                <h3 class="feature-title">Smart Scheduling</h3>
                <p class="feature-desc">Intelligent slot management that prevents double-booking and optimizes your availability automatically.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-cyan">⚡</div>
                <h3 class="feature-title">Instant Confirmations</h3>
                <p class="feature-desc">Clients receive instant confirmation emails and reminders so they never miss an appointment.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-violet">📊</div>
                <h3 class="feature-title">Analytics Dashboard</h3>
                <p class="feature-desc">Get a clear view of your booking trends, peak hours, and client retention metrics.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-pink">🔒</div>
                <h3 class="feature-title">Secure & Private</h3>
                <p class="feature-desc">Your data and client information are protected with industry-standard encryption at all times.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-green">🌐</div>
                <h3 class="feature-title">Multi-service Support</h3>
                <p class="feature-desc">Manage multiple services with different durations, prices, and availability rules effortlessly.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-amber">📱</div>
                <h3 class="feature-title">Mobile Friendly</h3>
                <p class="feature-desc">Fully responsive design ensures a seamless experience on any device, anytime, anywhere.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="cta-card">
            <h2 class="cta-title">Ready to take control<br>of your schedule?</h2>
            <p class="cta-desc">Join thousands of professionals already using Creno.</p>
            <div class="cta-buttons">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary-lg" id="cta-dashboard-btn">Go to Dashboard →</a>
                @else
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary-lg" id="cta-register-btn">Start for free →</a>
                    @endif
                    <a href="{{ route('login') }}" class="btn-outline-lg" id="cta-login-btn">Sign in</a>
                @endauth
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-inner">
            <a href="/" class="footer-logo">
                <div class="footer-logo-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                </div>
                <span class="footer-brand">Creno</span>
            </a>
            <p class="footer-copy">© {{ date('Y') }} Creno. All rights reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>
