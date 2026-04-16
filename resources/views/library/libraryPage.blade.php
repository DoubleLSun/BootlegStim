@extends('layouts.app')

@section('title', $user->name . "'s Game Library")
@section('main-class', '')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/library/libraryPage.css') }}">
@endpush

@section('content')
<div class="library-wrapper">

    {{-- SIDEBAR --}}
    <aside class="library-sidebar">
        <div class="sidebar-header">
            <h2>Games</h2>
            <span style="font-size:11px;color:#8f98a0;">{{ $games->count() }} games</span>
        </div>

        <div class="sidebar-search">
            <input type="text" id="sidebarSearch" placeholder="Search your games..." autocomplete="off">
        </div>

        <div class="sidebar-filter">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="installed">Installed</button>
            <button class="filter-btn" data-filter="recent">Recent</button>
        </div>

        <div class="game-list" id="gameList">
            @foreach($games as $game)
            <a href="{{ route('library.show', $game->id) }}"
               class="game-list-item {{ isset($selectedGame) && $selectedGame->id === $game->id ? 'active' : '' }}"
               data-name="{{ strtolower($game->title) }}"
               data-installed="{{ $game->pivot->is_installed ?? 0 }}">
                <img class="game-list-thumb"
                     src="{{ $game->cover_image }}"
                     alt="{{ $game->title }}">
                <div class="game-list-info">
                    <span class="game-list-name">{{ $game->title }}</span>
                    <span class="game-list-hours">{{ $game->pivot->hours_played ?? 0 }} hrs</span>
                </div>
                <span class="game-list-status {{ ($game->pivot->is_installed ?? false) ? 'status-installed' : 'status-not-installed' }}"></span>
            </a>
            @endforeach
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="library-main">

        {{-- Stats Bar --}}
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-value">{{ $games->count() }}</div>
                <div class="stat-label">Total Games</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $games->where('pivot.is_installed', true)->count() }}</div>
                <div class="stat-label">Installed</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($games->sum('pivot.hours_played'), 0) }}</div>
                <div class="stat-label">Hours Played</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $games->filter(fn($g) => ($g->pivot->last_played ?? null) && \Carbon\Carbon::parse($g->pivot->last_played)->gte(now()->startOfWeek()))->count() }}</div>
                <div class="stat-label">Played This Week</div>
            </div>
        </div>

        {{-- Recently Played --}}
        @if($recentGames->isNotEmpty())
        <div class="recently-played-section">
            <h3 class="section-title">Recently Played</h3>
            <div class="recent-games-strip">
                @foreach($recentGames as $game)
                <a href="{{ route('library.show', $game->id) }}" class="recent-card">
                    <img src="{{ $game->cover_image }}" alt="{{ $game->title }}">
                    <div class="recent-card-footer">
                        <div class="recent-card-name">{{ $game->title }}</div>
                        <div>{{ $game->pivot->hours_played ?? 0 }} hrs on record</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Content Header --}}
        <div class="library-content-header">
            <h1>All Games</h1>
            <div style="display:flex;align-items:center;gap:10px;">
                <select class="sort-select" id="sortSelect">
                    <option value="name">Name</option>
                    <option value="hours">Hours Played</option>
                    <option value="recent">Last Played</option>
                </select>
                <div class="view-toggle">
                    <button class="view-btn active" id="btnGrid" title="Grid view">⊞</button>
                    <button class="view-btn" id="btnList" title="List view">☰</button>
                </div>
            </div>
        </div>

        {{-- Grid View --}}
        <div class="games-grid" id="gamesGrid">
            @foreach($games as $game)
            <a href="{{ route('library.show', $game->id) }}" class="game-grid-card"
               data-name="{{ strtolower($game->title) }}"
               data-installed="{{ $game->pivot->is_installed ?? 0 }}">
                <img class="game-card-img"
                     src="{{ $game->cover_image }}"
                     alt="{{ $game->title }}">
                <div class="card-overlay">
                    <button class="card-overlay-btn">
                        {{ ($game->pivot->is_installed ?? false) ? 'Play' : 'Install' }}
                    </button>
                </div>
                <div class="game-card-body">
                    <p class="game-card-name">{{ $game->title }}</p>
                    <div class="game-card-meta">
                        <span>{{ $game->pivot->hours_played ?? 0 }} hrs</span>
                        <span class="install-badge {{ ($game->pivot->is_installed ?? false) ? 'installed' : 'not-installed' }}">
                            {{ ($game->pivot->is_installed ?? false) ? 'Installed' : 'Not Installed' }}
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- List View --}}
        <div class="games-list-view" id="gamesList">
            <div style="display:flex;padding:6px 10px;font-size:10px;color:#8f98a0;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #2a3f5f;margin-bottom:4px;">
                <span style="flex:1;margin-left:72px;">Game Name</span>
                <span style="width:120px;text-align:right;">Hours Played</span>
                <span style="width:80px;text-align:right;">Status</span>
            </div>
            @foreach($games as $game)
            <a href="{{ route('library.show', $game->id) }}" class="list-game-row"
               data-name="{{ strtolower($game->title) }}"
               data-installed="{{ $game->pivot->is_installed ?? 0 }}">
                <img class="list-thumb"
                     src="{{ $game->cover_image }}"
                     alt="{{ $game->title }}">
                <span class="list-name">{{ $game->title }}</span>
                <span class="list-hours">{{ $game->pivot->hours_played ?? 0 }} hrs on record</span>
                <span class="list-status">
                    <span class="install-badge {{ ($game->pivot->is_installed ?? false) ? 'installed' : 'not-installed' }}" style="font-size:9px;">
                        {{ ($game->pivot->is_installed ?? false) ? 'Installed' : 'Not installed' }}
                    </span>
                </span>
            </a>
            @endforeach
        </div>

    </main>
</div>


<script>
    // View toggle
    const grid = document.getElementById('gamesGrid');
    const list = document.getElementById('gamesList');
    document.getElementById('btnGrid').addEventListener('click', function() {
        grid.classList.remove('hidden');
        list.classList.remove('active');
        this.classList.add('active');
        document.getElementById('btnList').classList.remove('active');
    });
    document.getElementById('btnList').addEventListener('click', function() {
        grid.classList.add('hidden');
        list.classList.add('active');
        this.classList.add('active');
        document.getElementById('btnGrid').classList.remove('active');
    });

    // Sidebar search
    document.getElementById('sidebarSearch').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.game-list-item').forEach(item => {
            item.style.display = item.dataset.name.includes(q) ? '' : 'none';
        });
        document.querySelectorAll('.game-grid-card, .list-game-row').forEach(item => {
            item.style.display = item.dataset.name.includes(q) ? '' : 'none';
        });
    });

    // Filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const f = this.dataset.filter;
            document.querySelectorAll('.game-list-item, .game-grid-card, .list-game-row').forEach(item => {
                if (f === 'all') item.style.display = '';
                else if (f === 'installed') item.style.display = item.dataset.installed == '1' ? '' : 'none';
                else item.style.display = '';
            });
        });
    });
</script>
</body>
</html>
@endsection

