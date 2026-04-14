<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} — BootlegStim</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Barlow:wght@300;400;500;600&family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="profile-body">

{{-- ── NAV ─────────────────────────────────────────────────────── --}}
<nav class="pnav" aria-label="Site navigation">
    <a href="/" class="pnav__brand">
        <span class="pnav__brand-icon">⚡</span>BootlegStim
    </a>
    <ul class="pnav__links">
        <li><a href="/">Store</a></li>
        <li><a href="#">Library</a></li>
        <li><a href="#">Community</a></li>
    </ul>
    <div class="pnav__actions">
        @auth
            <a href="{{ route('profile.show', Auth::user()) }}" class="pnav__user">
                <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="pnav__user-avatar">
                <span class="pnav__user-name">{{ Auth::user()->name }}</span>
            </a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="pnav__btn">Sign Out</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="pnav__btn">Sign In</a>
        @endauth
    </div>
</nav>

{{-- Flash message --}}
@if(session('success'))
<div style="max-width:var(--max-w);margin:1rem auto;padding:0 2rem;">
    <div class="alert alert--success" role="alert">✅ {{ session('success') }}</div>
</div>
@endif

{{-- ── BANNER ──────────────────────────────────────────────────── --}}
<div
    class="profile-banner"
    aria-hidden="true"
    @if($user->banner) style="background-image: url('{{ $user->banner }}');" @endif
>
    <div class="profile-banner__grain"></div>
    <div class="profile-banner__overlay"></div>
</div>

{{-- ── IDENTITY ────────────────────────────────────────────────── --}}
@php
    $statusClass = $user->statusClass;
    $isOnline    = $user->status === 'online';
    $isPlaying   = $user->statusGame !== null;
    $xpCurrent   = ($user->steam_level % 1) * 100; // fractional part as XP %
    $xpPct       = max(5, min(95, $user->steam_level * 7 % 100 ?: 42)); // deterministic mock
@endphp

<section class="profile-identity {{ $statusClass }}" aria-label="Profile header">

    <div class="profile-identity__inner">

        {{-- Avatar --}}
        <div class="avatar-frame">
            <div class="avatar-frame__ring"></div>
            <img
                class="avatar-frame__img"
                src="{{ $user->avatar }}"
                alt="{{ $user->name }}"
            >
            <span class="avatar-frame__status-dot" aria-hidden="true"></span>
            <div class="avatar-frame__glow" aria-hidden="true"></div>
        </div>

        {{-- Info --}}
        <div class="profile-info">
            <h1 class="profile-info__name">{{ $user->name }}</h1>

            <div class="profile-info__meta">
                @if($user->location)
                    <span class="profile-info__location">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        {{ $user->location }}
                    </span>
                @endif
                <span class="profile-info__status-text {{ $isOnline ? 'is-online' : '' }} {{ $isPlaying ? 'is-playing' : '' }}">
                    @if($isPlaying)
                        <span class="status-pulse"></span> In-Game · {{ $user->statusGame->title }}
                    @elseif($isOnline)
                        <span class="status-pulse"></span> Online
                    @else
                        {{ $user->lastSeen }}
                    @endif
                </span>
            </div>

            {{-- Edit button --}}
            <a href="{{ route('profile.edit', $user) }}" class="profile-edit-btn">
                ✏️ Edit Profile
            </a>

            {{-- Level + XP bar --}}
            <div class="level-block">
                <div class="level-block__badge">
                    <span class="level-block__num">{{ $user->steam_level }}</span>
                </div>
                <div class="level-block__bar-wrap">
                    <div class="level-block__bar-track">
                        <div
                            class="level-block__bar-fill"
                            style="--xp-pct: {{ $xpPct }}%"
                            aria-label="{{ $xpPct }}% to next level"
                        ></div>
                        <div class="level-block__bar-shimmer"></div>
                    </div>
                    <div class="level-block__bar-labels">
                        <span>Level {{ $user->steam_level }}</span>
                        <span>{{ $xpPct }} / 100 XP</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick stats on the far right --}}
        <div class="identity-stats">
            <div class="identity-stat">
                <span class="identity-stat__val">{{ $totalGames }}</span>
                <span class="identity-stat__lbl">Games</span>
            </div>
            <div class="identity-stat">
                <span class="identity-stat__val">{{ $friends->count() }}</span>
                <span class="identity-stat__lbl">Friends</span>
            </div>
            <div class="identity-stat">
                <span class="identity-stat__val">{{ $badges->count() }}</span>
                <span class="identity-stat__lbl">Badges</span>
            </div>
        </div>

    </div>
</section>

{{-- ── TABS ────────────────────────────────────────────────────── --}}
<nav class="profile-tabs" aria-label="Profile sections">
    <div class="profile-tabs__inner">
        <a href="#summary"     class="profile-tab is-active">Summary</a>
        <a href="#games"       class="profile-tab">Games <span class="profile-tab__badge">{{ $totalGames }}</span></a>
        <a href="#friends"     class="profile-tab">Friends <span class="profile-tab__badge">{{ $friends->count() }}</span></a>
        <a href="#badges"      class="profile-tab">Badges</a>
        <a href="#screenshots" class="profile-tab">Screenshots</a>
        <a href="#reviews"     class="profile-tab">Reviews</a>
    </div>
</nav>

{{-- ── BODY ────────────────────────────────────────────────────── --}}
<div class="profile-grid" id="summary">

    {{-- ════════════════════ LEFT / MAIN ════════════════════════ --}}
    <main class="profile-main" aria-label="Profile main content">

        {{-- Currently Playing showcase --}}
        @if($isPlaying)
        <article class="now-playing-card" aria-label="Currently playing">
            <div class="now-playing-card__bg" aria-hidden="true"></div>
            <div class="now-playing-card__content">
                <div class="now-playing-card__eyebrow">
                    <span class="status-pulse"></span> Currently In-Game
                </div>
                <div class="now-playing-card__title">{{ $user->statusGame->title }}</div>
                <div class="now-playing-card__sub">
                    {{ $user->name }} is playing right now
                </div>
            </div>
            <a href="{{ route('games.show', $user->statusGame) }}" class="now-playing-card__cta">
                View Game →
            </a>
        </article>
        @endif

        {{-- Pinned Showcase --}}
        <article class="showcase-card" aria-label="Featured game showcase">
            <div class="panel-header">
                <span class="panel-header__icon">🏆</span> Featured Game Showcase
            </div>
            <div class="showcase-card__body">
                @if($recentGames->isNotEmpty())
                    @php $featured = $recentGames->first(); @endphp
                    <div class="showcase-featured">
                        <div class="showcase-featured__cover-wrap">
                            @if($featured->cover_image)
                                <img src="{{ $featured->cover_image }}" alt="{{ $featured->title }}" class="showcase-featured__cover">
                            @else
                                <div class="showcase-featured__cover-placeholder">
                                    <span>🎮</span>
                                </div>
                            @endif
                            <div class="showcase-featured__cover-glow" aria-hidden="true"></div>
                        </div>
                        <div class="showcase-featured__info">
                            <a href="{{ route('games.show', $featured) }}" class="showcase-featured__title">
                                {{ $featured->title }}
                            </a>
                            <p class="showcase-featured__desc">
                                {{ Str::limit($featured->description, 140) }}
                            </p>
                            @php
                                $mins = $featured->pivot->playtime_minutes ?? 0;
                                $hrs  = round($mins / 60, 1);
                            @endphp
                            <div class="showcase-featured__playtime">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm.5 5v5.25l4.5 2.67-.75 1.23L11 13V7h1.5z"/></svg>
                                {{ $hrs }} hours on record
                            </div>
                            <a href="{{ route('games.show', $featured) }}" class="showcase-featured__btn">
                                View Store Page
                            </a>
                        </div>
                    </div>
                @else
                    <div class="empty-showcase">
                        <div class="empty-showcase__icon">🎮</div>
                        <div class="empty-showcase__text">No games in library yet</div>
                        <div class="empty-showcase__sub">Games you own will appear here</div>
                    </div>
                @endif
            </div>
        </article>

        {{-- Recent Activity --}}
        <article class="panel" aria-label="Recent game activity">
            <div class="panel-header">
                <span class="panel-header__icon">🕹️</span> Recent Activity
                <span class="panel-header__right">
                    <span class="panel-header__count">{{ $totalGames }} games</span>
                </span>
            </div>

            @if($recentGames->isNotEmpty())
                <ul class="activity-list" aria-label="Recently played games">
                    @foreach($recentGames as $i => $game)
                        @php
                            $gameMins  = $game->pivot->playtime_minutes ?? 0;
                            $gameHrs   = round($gameMins / 60, 1);
                            $maxHrs    = $recentGames->max(fn($g) => $g->pivot->playtime_minutes ?? 0) / 60;
                            $barPct    = $maxHrs > 0 ? round(($gameHrs / $maxHrs) * 100) : 0;
                            $lastPlayed = $game->pivot->last_played_at
                                ? \Illuminate\Support\Carbon::parse($game->pivot->last_played_at)->diffForHumans()
                                : 'a while ago';
                        @endphp
                        <li class="activity-item" style="animation-delay: {{ $i * 60 }}ms">
                            @if($game->cover_image)
                                <img class="activity-item__art" src="{{ $game->cover_image }}" alt="{{ $game->title }}">
                            @else
                                <div class="activity-item__art-placeholder">🎮</div>
                            @endif
                            <div class="activity-item__body">
                                <a class="activity-item__title" href="{{ route('games.show', $game) }}">
                                    {{ $game->title }}
                                </a>
                                <div class="activity-item__playtime-bar">
                                    <div
                                        class="activity-item__playtime-fill"
                                        style="--bar-pct: {{ $barPct }}%"
                                    ></div>
                                </div>
                                <div class="activity-item__sub">
                                    Last played {{ $lastPlayed }}
                                </div>
                            </div>
                            <div class="activity-item__hrs">
                                <span class="activity-item__hrs-val">{{ $gameHrs }}</span>
                                <span class="activity-item__hrs-lbl">hrs</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="panel-empty">
                    <span class="panel-empty__icon">🎮</span>
                    <p>No recent activity yet.</p>
                    <small>Start playing games to populate this section.</small>
                </div>
            @endif
        </article>

    </main>

    {{-- ════════════════════ RIGHT / SIDEBAR ════════════════════ --}}
    <aside class="profile-sidebar" aria-label="Profile sidebar">

        {{-- About Me --}}
        <article class="panel" aria-label="About {{ $user->name }}">
            <div class="panel-header">
                <span class="panel-header__icon">👤</span> About Me
            </div>
            <div class="panel-body">
                @if($user->bio)
                    <p class="bio-text">{{ $user->bio }}</p>
                @else
                    <p class="bio-empty">No bio written yet.</p>
                @endif
            </div>
        </article>

        {{-- Friends --}}
        <article class="panel" aria-label="Friends list">
            <div class="panel-header">
                <span class="panel-header__icon">👥</span> Friends
                <span class="panel-header__right">
                    <a href="#">View All</a>
                </span>
            </div>

            @if($friends->isNotEmpty())
                <ul class="friends-list">
                    @foreach($friends as $friend)
                        @php $fs = $friend['status']; @endphp
                        <li class="friend-item status--{{ $fs }}">
                            <a class="friend-item__link" href="#" aria-label="{{ $friend['name'] }}, {{ $fs }}">
                                <div class="friend-item__avatar-wrap">
                                    <img class="friend-item__avatar" src="{{ $friend['avatar'] }}" alt="{{ $friend['name'] }}">
                                    <span class="friend-item__dot" aria-hidden="true"></span>
                                </div>
                                <div class="friend-item__info">
                                    <div class="friend-item__name">{{ $friend['name'] }}</div>
                                    <div class="friend-item__status is-{{ $fs }}">
                                        @if($fs === 'online') 🟢 Online
                                        @elseif($fs === 'away') 🟡 Away
                                        @elseif($fs === 'busy') 🔴 Busy
                                        @else ⚫ Offline @endif
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="panel-empty">
                    <span class="panel-empty__icon">👥</span>
                    <p>No friends yet.</p>
                </div>
            @endif
        </article>

        {{-- Badges --}}
        <article class="panel" aria-label="Badges">
            <div class="panel-header">
                <span class="panel-header__icon">🏅</span> Badges
                <span class="panel-header__right">
                    <a href="#">View All</a>
                </span>
            </div>

            @if($badges->isNotEmpty())
                <div class="badges-grid">
                    @foreach($badges as $badge)
                        <div class="badge-card" aria-label="{{ $badge['name'] }} badge, {{ $badge['xp'] }} XP">
                            <div class="badge-card__icon">{{ $badge['icon'] }}</div>
                            <div class="badge-card__info">
                                <div class="badge-card__name">{{ $badge['name'] }}</div>
                                <div class="badge-card__xp">{{ $badge['xp'] }} XP</div>
                            </div>
                            <div class="badge-card__shine" aria-hidden="true"></div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="panel-empty">
                    <span class="panel-empty__icon">🏅</span>
                    <p>No badges earned yet.</p>
                </div>
            @endif
        </article>

    </aside>

</div>{{-- /profile-grid --}}

</body>
</html>