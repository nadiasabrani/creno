<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Creno')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #f4f5f7; color: #1f2937; }
        .container { max-width: 960px; margin: 0 auto; padding: 0 1rem; }
        header { background: #111827; color: #fff; padding: 1rem 0; }
        header .container { display: flex; align-items: center; justify-content: space-between; }
        header h1 { font-size: 1.25rem; }
        nav ul { display: flex; gap: 1rem; list-style: none; align-items: center; }
        nav a { color: #d1d5db; text-decoration: none; }
        nav a:hover { color: #fff; }
        nav button.nav-button { background: none; border: none; color: #d1d5db; cursor: pointer; font: inherit; padding: 0; }
        nav button.nav-button:hover { color: #fff; }
        main { padding: 2rem 0 3rem; }
        .alert { padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 1rem; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-errors { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-errors ul { margin-left: 1.25rem; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { text-align: left; padding: 0.625rem 1rem; border-bottom: 1px solid #e5e7eb; }
        th { background: #f9fafb; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; }
        .btn { display: inline-block; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; text-decoration: none; cursor: pointer; border: none; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-secondary { background: #6b7280; color: #fff; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card { background: #fff; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .stat-card .value { font-size: 1.75rem; font-weight: 700; }
        .stat-card .label { color: #6b7280; font-size: 0.875rem; }
        form label { display: block; font-size: 0.875rem; margin-bottom: 0.25rem; }
        form input { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; margin-bottom: 1rem; }
        .form-error { color: #dc2626; font-size: 0.75rem; margin: -0.5rem 0 0.5rem; }
        .page-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
        .badge { display: inline-block; padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .pagination nav { display: flex; justify-content: center; margin-top: 1.5rem; }
        .pagination nav a, .pagination nav span { display: inline-block; padding: 0.375rem 0.75rem; margin: 0 0.125rem; border: 1px solid #d1d5db; border-radius: 0.375rem; text-decoration: none; color: #2563eb; background: #fff; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Creno</h1>
            <nav>
                <ul>
                    @auth
                        @if (auth()->user()->isAdmin())
                            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('admin.creneaux.index') }}">Créneaux</a></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-button">Déconnexion</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}">Se connecter</a></li>
                        <li><a href="{{ route('register') }}">S'inscrire</a></li>
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