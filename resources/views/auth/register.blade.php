<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — BootlegStim</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Barlow:wght@300;400;500;600&family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="auth-body">

    {{-- Animated background --}}
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
            <a href="{{ route('login') }}">Sign In</a>
            <a href="/">Store</a>
        </div>
    </nav>

    {{-- Centre card --}}
    <main class="auth-wrap auth-wrap--wide" aria-label="Registration form">
        <div class="auth-card auth-card--register">

            {{-- Header --}}
            <div class="auth-card__header">
                <div class="auth-card__logo" aria-hidden="true">⚡</div>
                <h1 class="auth-card__title">Create Your Account</h1>
                <p class="auth-card__sub">It's free and only takes a minute</p>
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
            <form action="{{ route('register.post') }}" method="POST" class="auth-form" novalidate>
                @csrf

                {{-- Step 1: Account info --}}
                <div class="auth-step-label">
                    <span class="auth-step-label__num">1</span> Account Details
                </div>

                <div class="auth-form-grid">
                    <div class="auth-field">
                        <label class="auth-label" for="name">
                            Display Name <span class="auth-required">*</span>
                        </label>
                        <input
                            class="auth-input @error('name') is-error @enderror"
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="FragMaster99"
                            maxlength="255"
                            autocomplete="username"
                            autofocus
                            required
                        >
                        @error('name')
                            <span class="auth-field__error">{{ $message }}</span>
                        @enderror
                        <span class="auth-field__hint">This is what other players will see</span>
                    </div>

                    <div class="auth-field">
                        <label class="auth-label" for="email">
                            Email Address <span class="auth-required">*</span>
                        </label>
                        <input
                            class="auth-input @error('email') is-error @enderror"
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            autocomplete="email"
                            required
                        >
                        @error('email')
                            <span class="auth-field__error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Step 2: Password --}}
                <div class="auth-step-label">
                    <span class="auth-step-label__num">2</span> Choose a Password
                </div>

                <div class="auth-form-grid">
                    <div class="auth-field">
                        <label class="auth-label" for="password">
                            Password <span class="auth-required">*</span>
                        </label>
                        <div class="auth-input-wrap">
                            <input
                                class="auth-input @error('password') is-error @enderror"
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Min. 8 characters"
                                autocomplete="new-password"
                                minlength="8"
                                required
                            >
                            <button type="button" class="auth-input__toggle" id="togglePw" aria-label="Toggle password">👁</button>
                        </div>
                        {{-- Strength meter --}}
                        <div class="pw-strength" id="pwStrength" aria-label="Password strength">
                            <div class="pw-strength__bar">
                                <div class="pw-strength__fill" id="pwFill"></div>
                            </div>
                            <span class="pw-strength__label" id="pwLabel">Enter a password</span>
                        </div>
                        @error('password')
                            <span class="auth-field__error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="auth-field">
                        <label class="auth-label" for="password_confirmation">
                            Confirm Password <span class="auth-required">*</span>
                        </label>
                        <div class="auth-input-wrap">
                            <input
                                class="auth-input"
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Repeat password"
                                autocomplete="new-password"
                                required
                            >
                            <button type="button" class="auth-input__toggle" id="togglePwConf" aria-label="Toggle password">👁</button>
                        </div>
                        <span class="auth-field__match" id="pwMatch"></span>
                    </div>
                </div>

                <div class="auth-terms">
                    By creating an account you agree to the
                    <a href="#">Terms of Service</a> and
                    <a href="#">Privacy Policy</a>.
                </div>

                <button type="submit" class="auth-submit auth-submit--register">
                    Create My Account →
                </button>

            </form>

            <div class="auth-card__footer">
                Already have an account?
                <a href="{{ route('login') }}" class="auth-card__footer-link">Sign in →</a>
            </div>

        </div>
    </main>

    <script>
        // Toggle password visibility
        function makeToggle(btnId, inputId) {
            document.getElementById(btnId).addEventListener('click', function () {
                const input = document.getElementById(inputId);
                const hide  = input.type === 'password';
                input.type  = hide ? 'text' : 'password';
                this.textContent = hide ? '🙈' : '👁';
            });
        }
        makeToggle('togglePw', 'password');
        makeToggle('togglePwConf', 'password_confirmation');

        // Password strength meter
        const pwInput = document.getElementById('password');
        const pwFill  = document.getElementById('pwFill');
        const pwLabel = document.getElementById('pwLabel');

        pwInput.addEventListener('input', function () {
            const val = this.value;
            let score = 0;
            if (val.length >= 8)  score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const levels = [
                { pct: '0%',   color: 'transparent', label: 'Enter a password' },
                { pct: '25%',  color: '#f94144',      label: 'Weak' },
                { pct: '50%',  color: '#f9c74f',      label: 'Fair' },
                { pct: '75%',  color: '#90be6d',      label: 'Good' },
                { pct: '100%', color: '#57e389',      label: 'Strong' },
            ];
            const lvl = levels[score];
            pwFill.style.width       = lvl.pct;
            pwFill.style.background  = lvl.color;
            pwLabel.textContent      = lvl.label;
            pwLabel.style.color      = lvl.color;
        });

        // Password match indicator
        const pwConf  = document.getElementById('password_confirmation');
        const pwMatch = document.getElementById('pwMatch');

        function checkMatch() {
            if (!pwConf.value) { pwMatch.textContent = ''; return; }
            const match = pwInput.value === pwConf.value;
            pwMatch.textContent = match ? '✅ Passwords match' : '❌ Passwords do not match';
            pwMatch.style.color = match ? '#57e389' : '#f94144';
        }

        pwConf.addEventListener('input', checkMatch);
        pwInput.addEventListener('input', checkMatch);
    </script>

</body>
</html>
