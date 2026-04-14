<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile — {{ $user->name }} | BootlegStim</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Barlow:wght@300;400;500;600&family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="profile-body">

{{-- NAV --}}
<nav class="pnav" aria-label="Site navigation">
    <a href="/" class="pnav__brand">
        <span class="pnav__brand-icon">⚡</span>BootlegStim
    </a>
    <ul class="pnav__links">
        <li><a href="/">Store</a></li>
        <li><a href="#">Library</a></li>
        <li><a href="#">Community</a></li>
    </ul>
    <div class="pnav__actions" style="display:flex;align-items:center;gap:0.8rem;">
        <a href="{{ route('profile.show', $user) }}" class="pnav__btn">← Back to Profile</a>
        @auth
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="pnav__btn">Sign Out</button>
            </form>
        @endauth
    </div>
</nav>

{{-- PAGE HEADER --}}
<div class="edit-page-header">
    <div class="edit-page-header__inner">
        <img
            class="edit-page-header__avatar"
            src="{{ $user->avatar }}"
            alt="{{ $user->name }}"
        >
        <div>
            <div class="edit-page-header__label">Editing Profile</div>
            <h1 class="edit-page-header__name">{{ $user->name }}</h1>
        </div>
    </div>
</div>

{{-- MAIN FORM --}}
<div class="edit-wrap">

    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert--success" role="alert">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="alert alert--error" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('profile.update', $user) }}"
        method="POST"
        class="edit-form"
        novalidate
    >
        @csrf

        {{-- ── SECTION: Identity ──────────────────────────────── --}}
        <section class="edit-section">
            <div class="edit-section__header">
                <span class="edit-section__icon">👤</span>
                <div>
                    <h2 class="edit-section__title">Identity</h2>
                    <p class="edit-section__sub">Your display name and location</p>
                </div>
            </div>

            <div class="edit-section__body">
                <div class="form-row form-row--2">

                    <div class="form-group">
                        <label class="form-label" for="name">
                            Display Name <span class="form-required">*</span>
                        </label>
                        <input
                            class="form-input @error('name') is-error @enderror"
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            placeholder="Your username"
                            maxlength="255"
                            required
                        >
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="location">Location</label>
                        <input
                            class="form-input @error('location') is-error @enderror"
                            type="text"
                            id="location"
                            name="location"
                            value="{{ old('location', $user->location) }}"
                            placeholder="e.g. Kuala Lumpur, Malaysia"
                            maxlength="120"
                        >
                        @error('location')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
        </section>

        {{-- ── SECTION: Bio ────────────────────────────────────── --}}
        <section class="edit-section">
            <div class="edit-section__header">
                <span class="edit-section__icon">📝</span>
                <div>
                    <h2 class="edit-section__title">About Me</h2>
                    <p class="edit-section__sub">Tell other players about yourself</p>
                </div>
            </div>

            <div class="edit-section__body">
                <div class="form-group">
                    <label class="form-label" for="bio">Bio</label>
                    <textarea
                        class="form-textarea @error('bio') is-error @enderror"
                        id="bio"
                        name="bio"
                        rows="4"
                        maxlength="1000"
                        placeholder="Write something about yourself..."
                    >{{ old('bio', $user->bio) }}</textarea>
                    <span class="form-hint">Max 1000 characters</span>
                    @error('bio')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </section>

        {{-- ── SECTION: Appearance ─────────────────────────────── --}}
        <section class="edit-section">
            <div class="edit-section__header">
                <span class="edit-section__icon">🎨</span>
                <div>
                    <h2 class="edit-section__title">Appearance</h2>
                    <p class="edit-section__sub">Avatar and banner image URLs</p>
                </div>
            </div>

            <div class="edit-section__body">
                <div class="form-row form-row--2">

                    <div class="form-group">
                        <label class="form-label" for="avatar_url">Avatar URL</label>
                        <input
                            class="form-input @error('avatar_url') is-error @enderror"
                            type="url"
                            id="avatar_url"
                            name="avatar_url"
                            value="{{ old('avatar_url', $user->avatar_url) }}"
                            placeholder="https://example.com/avatar.jpg"
                        >
                        @error('avatar_url')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        {{-- Live preview --}}
                        <div class="img-preview-wrap" id="avatarPreviewWrap">
                            <img
                                id="avatarPreview"
                                class="img-preview img-preview--avatar"
                                src="{{ $user->avatar }}"
                                alt="Avatar preview"
                            >
                            <span class="img-preview__label">Preview</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="banner_url">Banner URL</label>
                        <input
                            class="form-input @error('banner_url') is-error @enderror"
                            type="url"
                            id="banner_url"
                            name="banner_url"
                            value="{{ old('banner_url', $user->banner_url) }}"
                            placeholder="https://example.com/banner.jpg"
                        >
                        @error('banner_url')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <div class="img-preview-wrap" id="bannerPreviewWrap">
                            <img
                                id="bannerPreview"
                                class="img-preview img-preview--banner"
                                src="{{ $user->banner ?? '' }}"
                                alt="Banner preview"
                                style="{{ $user->banner ? '' : 'display:none' }}"
                            >
                            @if(!$user->banner)
                                <div class="img-preview__placeholder" id="bannerPlaceholder">
                                    No banner set
                                </div>
                            @endif
                            <span class="img-preview__label">Preview</span>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ── SECTION: Status ─────────────────────────────────── --}}
        <section class="edit-section">
            <div class="edit-section__header">
                <span class="edit-section__icon">🟢</span>
                <div>
                    <h2 class="edit-section__title">Status</h2>
                    <p class="edit-section__sub">What other players see next to your name</p>
                </div>
            </div>

            <div class="edit-section__body">
                <div class="form-row form-row--2">

                    <div class="form-group">
                        <label class="form-label" for="status">
                            Online Status <span class="form-required">*</span>
                        </label>
                        <select
                            class="form-select @error('status') is-error @enderror"
                            id="status"
                            name="status"
                        >
                            @foreach(['online' => '🟢 Online', 'away' => '🟡 Away', 'busy' => '🔴 Busy', 'offline' => '⚫ Offline'] as $val => $label)
                                <option
                                    value="{{ $val }}"
                                    {{ old('status', $user->status) === $val ? 'selected' : '' }}
                                >{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" id="currentGameGroup">
                        <label class="form-label" for="status_game_id">Currently Playing</label>
                        <select
                            class="form-select @error('status_game_id') is-error @enderror"
                            id="status_game_id"
                            name="status_game_id"
                        >
                            <option value="">— Not playing anything —</option>
                            @foreach($games as $game)
                                <option
                                    value="{{ $game->id }}"
                                    {{ old('status_game_id', $user->status_game_id) == $game->id ? 'selected' : '' }}
                                >{{ $game->title }}</option>
                            @endforeach
                        </select>
                        @error('status_game_id')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        <span class="form-hint">Only shown when status is Online or Busy</span>
                    </div>

                </div>
            </div>
        </section>

        {{-- ── ACTIONS ──────────────────────────────────────────── --}}
        <div class="edit-actions">
            <a href="{{ route('profile.show', $user) }}" class="edit-btn edit-btn--ghost">
                Cancel
            </a>
            <button type="submit" class="edit-btn edit-btn--primary">
                <span>💾</span> Save Changes
            </button>
        </div>

    </form>
</div>{{-- /edit-wrap --}}

<script>
    // Live avatar preview
    document.getElementById('avatar_url').addEventListener('input', function () {
        const img = document.getElementById('avatarPreview');
        if (this.value) {
            img.src = this.value;
            img.style.display = 'block';
        }
    });

    // Live banner preview
    document.getElementById('banner_url').addEventListener('input', function () {
        const img       = document.getElementById('bannerPreview');
        const ph        = document.getElementById('bannerPlaceholder');
        if (this.value) {
            img.src          = this.value;
            img.style.display = 'block';
            if (ph) ph.style.display = 'none';
        } else {
            img.style.display = 'none';
            if (ph) ph.style.display = 'flex';
        }
    });

    // Hide "currently playing" when status is offline or away
    const statusSel = document.getElementById('status');
    const gameGroup = document.getElementById('currentGameGroup');

    function toggleGameGroup() {
        const hide = statusSel.value === 'offline' || statusSel.value === 'away';
        gameGroup.style.opacity    = hide ? '0.4' : '1';
        gameGroup.style.pointerEvents = hide ? 'none' : 'auto';
    }

    statusSel.addEventListener('change', toggleGameGroup);
    toggleGameGroup(); // run on load
</script>

</body>
</html>