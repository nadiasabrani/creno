<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Creno')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0f0f11;
            --bg-card: rgba(255,255,255,0.04);
            --bg-card-hover: rgba(255,255,255,0.07);
            --bg-surface: #18181b;
            --border: rgba(255,255,255,0.08);
            --border-hover: rgba(255,255,255,0.15);
            --text: #f4f4f5;
            --text-muted: #a1a1aa;
            --text-dim: #71717a;
            --accent: #818cf8;
            --accent-hover: #6366f1;
            --accent-glow: rgba(129,140,248,0.25);
            --success: #34d399;
            --success-bg: rgba(52,211,153,0.12);
            --danger: #f87171;
            --danger-bg: rgba(248,113,113,0.12);
            --warning: #fbbf24;
            --warning-bg: rgba(251,191,36,0.12);
            --radius: 12px;
            --radius-sm: 8px;
            --radius-full: 9999px;
            --shadow: 0 1px 3px rgba(0,0,0,0.4), 0 4px 20px rgba(0,0,0,0.2);
            --shadow-lg: 0 8px 40px rgba(0,0,0,0.5);
            --transition: 0.2s cubic-bezier(0.4,0,0.2,1);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ── Animated Background ── */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at 20% 20%, rgba(99,102,241,0.08) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 80%, rgba(139,92,246,0.06) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 50%, rgba(59,130,246,0.04) 0%, transparent 60%);
            animation: bgShift 20s ease-in-out infinite alternate;
            z-index: -1;
        }

        @keyframes bgShift {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-2%, -2%) rotate(3deg); }
        }

        .container { max-width: 1040px; margin: 0 auto; padding: 0 1.5rem; }

        /* ── Header ── */
        header.main-header {
            background: rgba(15,15,17,0.8);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid var(--border);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        header.main-header .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }

        .logo {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, var(--accent), #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
        }

        nav ul {
            display: flex;
            gap: 0.25rem;
            list-style: none;
            align-items: center;
        }

        nav a, nav button.nav-button {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 0.875rem;
            border-radius: var(--radius-sm);
            transition: all var(--transition);
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        nav a:hover, nav button.nav-button:hover {
            color: var(--text);
            background: rgba(255,255,255,0.06);
        }

        nav a.active {
            color: var(--accent);
            background: rgba(129,140,248,0.1);
        }

        /* ── Main ── */
        main {
            padding: 2.5rem 0 4rem;
            animation: fadeUp 0.5s ease-out;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Alerts ── */
        .alert {
            padding: 0.875rem 1.25rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideDown 0.3s ease-out;
            border: 1px solid;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success);
            border-color: rgba(52,211,153,0.2);
        }

        .alert-success::before { content: '✓'; font-weight: 700; }

        .alert-error, .alert-errors {
            background: var(--danger-bg);
            color: var(--danger);
            border-color: rgba(248,113,113,0.2);
        }

        .alert-errors { display: block; }
        .alert-errors ul { margin-left: 1.25rem; }
        .alert-errors li { margin-top: 0.25rem; }

        /* ── Page Header ── */
        .page-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        h2 {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.03em;
        }

        h3 {
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-bottom: 1rem;
            color: var(--text-muted);
        }

        /* ── Tables ── */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--bg-card);
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        th, td {
            text-align: left;
            padding: 0.875rem 1.25rem;
            border-bottom: 1px solid var(--border);
        }

        th {
            background: rgba(255,255,255,0.03);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-dim);
        }

        tbody tr {
            transition: all var(--transition);
        }

        tbody tr:hover {
            background: var(--bg-card-hover);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        td {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1.125rem;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
            opacity: 0;
            transition: opacity var(--transition);
        }

        .btn:hover::after { opacity: 1; }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #6366f1);
            color: #fff;
            box-shadow: 0 2px 12px var(--accent-glow);
        }

        .btn-primary:hover {
            box-shadow: 0 4px 20px rgba(129,140,248,0.4);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            box-shadow: 0 2px 12px rgba(239,68,68,0.2);
        }

        .btn-danger:hover {
            box-shadow: 0 4px 20px rgba(239,68,68,0.35);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: rgba(255,255,255,0.08);
            color: var(--text-muted);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.12);
            color: var(--text);
            border-color: var(--border-hover);
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 6px;
        }

        /* ── Badges ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        .badge-green {
            background: var(--success-bg);
            color: var(--success);
        }

        .badge-red {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .badge-yellow {
            background: var(--warning-bg);
            color: var(--warning);
        }

        .badge-yellow::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--warning);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        /* ── Stats Cards ── */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: all var(--transition);
        }

        .stat-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card .value {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            background: linear-gradient(135deg, var(--text), var(--text-muted));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-card .label {
            color: var(--text-dim);
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.25rem;
        }

        /* ── Forms ── */
        form label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.375rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        form input[type="date"],
        form input[type="time"],
        form input[type="number"],
        form input[type="text"],
        form input[type="email"],
        form input[type="password"] {
            width: 100%;
            padding: 0.625rem 0.875rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            margin-bottom: 1.25rem;
            color: var(--text);
            font-family: inherit;
            font-size: 0.9rem;
            transition: all var(--transition);
            color-scheme: dark;
        }

        form input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .form-error {
            color: var(--danger);
            font-size: 0.75rem;
            margin: -0.75rem 0 0.75rem;
        }

        /* ── Slot Cards ── */
        .slot-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        .slot-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
        }

        .slot-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--accent), #a78bfa, var(--accent));
            opacity: 0;
            transition: opacity var(--transition);
        }

        .slot-card:hover {
            border-color: var(--border-hover);
            background: var(--bg-card-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .slot-card:hover::before { opacity: 1; }

        .slot-date {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--accent);
            margin-bottom: 0.5rem;
        }

        .slot-time {
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text);
        }

        .slot-duration {
            font-size: 0.8rem;
            color: var(--text-dim);
            margin-top: 0.25rem;
            margin-bottom: 1rem;
        }

        .slot-card .btn { width: 100%; justify-content: center; }

        /* ── RDV Cards ── */
        .rdv-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .rdv-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            transition: all var(--transition);
        }

        .rdv-card:hover {
            border-color: var(--border-hover);
            background: var(--bg-card-hover);
        }

        .rdv-card.rdv-annule {
            opacity: 0.5;
        }

        .rdv-info { flex: 1; }

        .rdv-date {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-dim);
            margin-bottom: 0.25rem;
        }

        .rdv-time {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text);
        }

        .rdv-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        .rdv-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-dim);
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.4;
        }

        .empty-state p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        /* ── Pagination ── */
        .pagination nav {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .pagination nav a, .pagination nav span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.875rem;
            margin: 0 0.125rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--text-muted);
            background: var(--bg-card);
            font-size: 0.875rem;
            transition: all var(--transition);
        }

        .pagination nav a:hover {
            background: var(--bg-card-hover);
            border-color: var(--border-hover);
            color: var(--text);
        }

        /* ── Responsive ── */
        @media (max-width: 640px) {
            .container { padding: 0 1rem; }
            h2 { font-size: 1.35rem; }
            .slot-grid { grid-template-columns: 1fr; }
            .rdv-card { flex-direction: column; align-items: flex-start; }
            nav ul { gap: 0.125rem; }
            nav a, nav button.nav-button { padding: 0.375rem 0.5rem; font-size: 0.8rem; }
            .page-head { flex-direction: column; align-items: flex-start; gap: 1rem; }
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="container">
            <a href="/" class="logo">Creno</a>
            <nav>
                <ul>
                    @auth
                        <li><a href="{{ route('creneaux.index') }}" @if(request()->routeIs('creneaux.*')) class="active" @endif>Créneaux</a></li>
                        <li><a href="{{ route('rendez-vous.mine') }}" @if(request()->routeIs('rendez-vous.*')) class="active" @endif>Mes RDV</a></li>
                        @if (auth()->user()->isAdmin())
                            <li><a href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) class="active" @endif>Dashboard</a></li>
                            <li><a href="{{ route('admin.creneaux.index') }}" @if(request()->routeIs('admin.creneaux.*')) class="active" @endif>Admin</a></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-button">Déconnexion</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}">Connexion</a></li>
                        <li><a href="{{ route('register') }}" class="btn btn-primary btn-sm">S'inscrire</a></li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    @isset($header)
        <header>
            <div class="container">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

        @isset($slot)
            {{ $slot }}
        @endisset
    </main>
</body>
</html>