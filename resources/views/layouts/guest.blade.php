<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Creno') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { box-sizing: border-box; margin: 0; padding: 0; }

            body {
                font-family: 'Inter', sans-serif;
                min-height: 100vh;
                background: #0d0f14;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                position: relative;
            }

            /* Animated background blobs */
            .bg-blob {
                position: fixed;
                border-radius: 50%;
                filter: blur(80px);
                opacity: 0.18;
                animation: floatBlob 12s ease-in-out infinite;
                pointer-events: none;
                z-index: 0;
            }
            .bg-blob-1 {
                width: 500px; height: 500px;
                background: radial-gradient(circle, #6366f1, #8b5cf6);
                top: -150px; left: -150px;
                animation-delay: 0s;
            }
            .bg-blob-2 {
                width: 400px; height: 400px;
                background: radial-gradient(circle, #06b6d4, #3b82f6);
                bottom: -100px; right: -100px;
                animation-delay: -4s;
            }
            .bg-blob-3 {
                width: 300px; height: 300px;
                background: radial-gradient(circle, #a855f7, #ec4899);
                top: 50%; left: 50%;
                transform: translate(-50%, -50%);
                animation-delay: -8s;
            }

            @keyframes floatBlob {
                0%, 100% { transform: scale(1) translate(0, 0); }
                33% { transform: scale(1.08) translate(20px, -15px); }
                66% { transform: scale(0.95) translate(-15px, 20px); }
            }

            /* Grid pattern overlay */
            .bg-grid {
                position: fixed;
                inset: 0;
                background-image:
                    linear-gradient(rgba(99,102,241,0.04) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(99,102,241,0.04) 1px, transparent 1px);
                background-size: 50px 50px;
                z-index: 0;
                pointer-events: none;
            }

            /* Auth card */
            .auth-wrapper {
                position: relative;
                z-index: 10;
                width: 100%;
                max-width: 440px;
                padding: 1.5rem;
                animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
            }

            @keyframes slideUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }

            /* Logo area */
            .auth-logo {
                text-align: center;
                margin-bottom: 2rem;
            }
            .auth-logo a {
                display: inline-flex;
                align-items: center;
                gap: 0.6rem;
                text-decoration: none;
            }
            .logo-icon {
                width: 42px;
                height: 42px;
                background: linear-gradient(135deg, #6366f1, #8b5cf6);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 0 20px rgba(99,102,241,0.4);
            }
            .logo-icon svg {
                width: 22px;
                height: 22px;
                fill: white;
            }
            .logo-text {
                font-size: 1.5rem;
                font-weight: 700;
                background: linear-gradient(135deg, #e2e8f0, #a5b4fc);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                letter-spacing: -0.02em;
            }

            /* Glass card */
            .auth-card {
                background: rgba(255,255,255,0.04);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                border: 1px solid rgba(255,255,255,0.08);
                border-radius: 20px;
                padding: 2.5rem;
                box-shadow:
                    0 0 0 1px rgba(99,102,241,0.1),
                    0 25px 50px -12px rgba(0,0,0,0.5),
                    inset 0 1px 0 rgba(255,255,255,0.07);
            }

            /* Form styles */
            .form-group { margin-bottom: 1.25rem; }
            .form-label {
                display: block;
                font-size: 0.8rem;
                font-weight: 500;
                color: #94a3b8;
                margin-bottom: 0.5rem;
                text-transform: uppercase;
                letter-spacing: 0.08em;
            }
            .form-input {
                width: 100%;
                padding: 0.75rem 1rem;
                background: rgba(255,255,255,0.05);
                border: 1px solid rgba(255,255,255,0.08);
                border-radius: 10px;
                color: #e2e8f0;
                font-size: 0.95rem;
                font-family: 'Inter', sans-serif;
                transition: all 0.2s ease;
                outline: none;
            }
            .form-input::placeholder { color: #475569; }
            .form-input:focus {
                border-color: rgba(99,102,241,0.5);
                background: rgba(99,102,241,0.06);
                box-shadow: 0 0 0 3px rgba(99,102,241,0.12), 0 0 15px rgba(99,102,241,0.08);
            }
            .form-input.is-error { border-color: rgba(239,68,68,0.5); }

            /* Error messages */
            .form-error {
                font-size: 0.8rem;
                color: #f87171;
                margin-top: 0.35rem;
            }

            /* Session status */
            .session-status {
                background: rgba(34,197,94,0.1);
                border: 1px solid rgba(34,197,94,0.2);
                border-radius: 8px;
                padding: 0.65rem 1rem;
                font-size: 0.85rem;
                color: #86efac;
                margin-bottom: 1.25rem;
            }

            /* Remember / forgot row */
            .form-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: 0.5rem;
                margin-bottom: 1.5rem;
            }
            .form-check {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                cursor: pointer;
            }
            .form-check input[type="checkbox"] {
                width: 16px;
                height: 16px;
                accent-color: #6366f1;
                cursor: pointer;
            }
            .form-check span {
                font-size: 0.85rem;
                color: #94a3b8;
            }
            .forgot-link {
                font-size: 0.85rem;
                color: #818cf8;
                text-decoration: none;
                transition: color 0.2s;
            }
            .forgot-link:hover { color: #a5b4fc; }

            /* Submit button */
            .btn-primary {
                width: 100%;
                padding: 0.8rem 1.5rem;
                background: linear-gradient(135deg, #6366f1, #8b5cf6);
                color: white;
                font-size: 0.95rem;
                font-weight: 600;
                font-family: 'Inter', sans-serif;
                border: none;
                border-radius: 10px;
                cursor: pointer;
                transition: all 0.25s ease;
                box-shadow: 0 4px 15px rgba(99,102,241,0.35);
                position: relative;
                overflow: hidden;
            }
            .btn-primary::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(255,255,255,0.12), transparent);
                opacity: 0;
                transition: opacity 0.2s;
            }
            .btn-primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 25px rgba(99,102,241,0.45);
            }
            .btn-primary:hover::after { opacity: 1; }
            .btn-primary:active { transform: translateY(0); }

            /* Register link */
            .auth-alt-link {
                text-align: center;
                margin-top: 1.5rem;
                font-size: 0.875rem;
                color: #64748b;
            }
            .auth-alt-link a {
                color: #818cf8;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s;
            }
            .auth-alt-link a:hover { color: #a5b4fc; }
        </style>
    </head>
    <body>
        <!-- Animated background -->
        <div class="bg-blob bg-blob-1"></div>
        <div class="bg-blob bg-blob-2"></div>
        <div class="bg-blob bg-blob-3"></div>
        <div class="bg-grid"></div>

        <div class="auth-wrapper">
            <!-- Logo -->
            <div class="auth-logo">
                <a href="/">
                    <div class="logo-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                        </svg>
                    </div>
                    <span class="logo-text">Creno</span>
                </a>
            </div>

            <!-- Card -->
            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
