<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — BootlegStim</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Barlow:wght@300;400;500;600&family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="auth-body">

    {{-- Animated background particles --}}
    <div class="auth-bg" aria-hidden="true">
        <div class="auth-bg__grid"></div>
        <div class="auth-bg__glow auth-bg__glow--1"></div>
        <div class="auth-bg__glow auth-bg__glow--2"></div>
        <div class="auth-bg__glow auth-bg__glow--3"></div>
    </div>

    {{-- Top bar --}}
    <nav class="auth-topbar" aria-label="Site navigation">
        <a href="/" class="auth-topbar__brand">
            <span>⚡</span>BootlegStim
        </a>
        <div class="auth-topbar__links">
            <a href="{{ route('register') }}">Create Account</a>
            <a href="/">Store</a>
        </div>
    </nav>

    {{-- Centre card --}}
    <main class="auth-wrap" aria-label="Login form">
        <div class="auth-card">

            {{-- Card header --}}
            <div class="auth-card__header">
                <div class="auth-card__logo" aria-hidden="true">⚡</div>
                <h1 class="auth-card__title">Sign In</h1>
                <p class="auth-card__sub">to your BootlegStim account</p>
            </div>

            {{-- Errors --}}
            @if($errors->any())
                <div class="auth-alert" role="alert">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('login.post') }}" method="POST" class="auth-form" novalidate>
                @csrf

                <div class="auth-field">
                    <label class="auth-label" for="email">Email Address</label>
                    <input
                        class="auth-input @error('email') is-error @enderror"
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        autocomplete="email"
                        autofocus
                        required
                    >
                    @error('email')
                        <span class="auth-field__error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="auth-field">
                    <div class="auth-label-row">
                        <label class="auth-label" for="password">Password</label>
                        <a href="#" class="auth-label-link">Forgot password?</a>
                    </div>
                    <div class="auth-input-wrap">
                        <input
                            class="auth-input @error('password') is-error @enderror"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                        >
                        <button
                            type="button"
                            class="auth-input__toggle"
                            id="togglePassword"
                            aria-label="Toggle password visibility"
                        >👁</button>
                    </div>
                    @error('password')
                        <span class="auth-field__error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="auth-remember">
                    <label class="auth-checkbox">
                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <span class="auth-checkbox__box"></span>
                        <span class="auth-checkbox__label">Remember me on this computer</span>
                    </label>
                </div>

                <div class="auth-captcha-notice">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                    This form is protected against unauthorized access.
                </div>

                <button type="submit" class="auth-submit">
                    Sign In
                </button>

            </form>

            {{-- Footer --}}
            <div class="auth-card__footer">
                New to BootlegStim?
                <a href="{{ route('register') }}" class="auth-card__footer-link">
                    Create a free account →
                </a>
            </div>

        </div>

        {{-- Side panel: featured games (decorative) --}}
        <aside class="auth-side" aria-hidden="true">
            <div class="auth-side__label">Featured on BootlegStim</div>
            <div class="auth-side__games">
                @for($i = 0; $i < 5; $i++)
                    <div class="auth-side__game" style="animation-delay: {{ $i * 120 }}ms">
                        <div class="auth-side__game-art" style="background: hsl({{ 200 + $i * 30 }}, 60%, 20%);">
                            <span style="font-size:2rem">{{ ['🎮','⚔️','🚀','🏎️','🔫'][$i] }}</span>
                        </div>
                    </div>
                @endfor
            </div>
            <div class="auth-side__tagline">
                Over <strong>50,000</strong> games.<br>Join millions of players.
            </div>
        </aside>

    </main>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            this.textContent = isHidden ? '🙈' : '👁';
        });
    </script>

</body>
</html>
