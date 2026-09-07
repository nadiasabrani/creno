<x-guest-layout>
    <style>
        .login-heading {
            margin-bottom: 0.35rem;
            font-size: 1.45rem;
            font-weight: 700;
            color: #e2e8f0;
            letter-spacing: -0.02em;
        }
        .login-subheading {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 1.75rem;
        }
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.07), transparent);
            margin: 1.5rem 0;
        }
    </style>

    <!-- Session Status -->
    @if (session('status'))
        <div class="session-status">
            {{ session('status') }}
        </div>
    @endif

    <h1 class="login-heading">Welcome back</h1>
    <p class="login-subheading">Sign in to your Creno account</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email address</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                placeholder="you@example.com"
                required
                autofocus
                autocomplete="username"
            />
            @if ($errors->has('email'))
                <p class="form-error">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                placeholder="••••••••"
                required
                autocomplete="current-password"
            />
            @if ($errors->has('password'))
                <p class="form-error">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Remember & Forgot -->
        <div class="form-footer">
            <label class="form-check">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a class="forgot-link" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary">
            Sign in →
        </button>
    </form>

    @if (Route::has('register'))
        <div class="auth-alt-link">
            Don't have an account?
            <a href="{{ route('register') }}">Create one</a>
        </div>
    @endif
</x-guest-layout>
