<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Library - BootlegStim</title>
<style>
    /* ---- LIBRARY PAGE STYLES ---- */
    .library-wrapper {
        display: flex;
        min-height: calc(100vh - 60px);
        background: #1b2838;
        font-family: 'Motiva Sans', Arial, sans-serif;
    }

    /* ---- SIDEBAR ---- */
    .library-sidebar {
        width: 240px;
        min-width: 240px;
        background: #2a3f5f;
        display: flex;
        flex-direction: column;
        border-right: 1px solid #1b2838;
    }

    .sidebar-header {
        padding: 16px 12px 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #1b2838;
    }

    .sidebar-header h2 {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #8f98a0;
        margin: 0;
    }

    .sidebar-search {
        padding: 8px 10px;
        border-bottom: 1px solid #1b2838;
    }

    .sidebar-search input {
        width: 100%;
        background: #316282;
        border: 1px solid #1b2838;
        border-radius: 3px;
        color: #c6d4df;
        font-size: 12px;
        padding: 5px 8px;
        outline: none;
        box-sizing: border-box;
    }

    .sidebar-search input::placeholder { color: #8f98a0; }
    .sidebar-search input:focus { border-color: #66c0f4; }

    .sidebar-filter {
        padding: 6px 10px;
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        border-bottom: 1px solid #1b2838;
    }

    .filter-btn {
        font-size: 10px;
        background: #1b2838;
        color: #8f98a0;
        border: 1px solid #2a475e;
        border-radius: 2px;
        padding: 3px 8px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .filter-btn:hover, .filter-btn.active {
        background: #3d6f8e;
        color: #c6d4df;
        border-color: #3d6f8e;
    }

    .game-list {
        flex: 1;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #2a475e #1b2838;
    }

    .game-list::-webkit-scrollbar { width: 6px; }
    .game-list::-webkit-scrollbar-track { background: #1b2838; }
    .game-list::-webkit-scrollbar-thumb { background: #2a475e; border-radius: 3px; }

    .game-list-item {
        display: flex;
        align-items: center;
        padding: 7px 12px;
        cursor: pointer;
        border-bottom: 1px solid rgba(0,0,0,0.2);
        transition: background 0.1s;
        gap: 10px;
        text-decoration: none;
    }

    .game-list-item:hover { background: #2f4d6e; }
    .game-list-item.active { background: #3d6f8e; }

    .game-list-thumb {
        width: 30px;
        height: 30px;
        border-radius: 3px;
        object-fit: cover;
        flex-shrink: 0;
        background: #1b2838;
    }

    .game-list-info { flex: 1; min-width: 0; }
    .game-list-name {
        font-size: 12px;
        color: #c6d4df;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
    .game-list-hours {
        font-size: 10px;
        color: #8f98a0;
    }

    .game-list-status {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .status-installed { background: #57cbde; }
    .status-not-installed { background: #5c7e96; }

    /* ---- MAIN CONTENT ---- */
    .library-main {
        flex: 1;
        overflow-y: auto;
        background: #1b2838;
    }

    /* Grid / List toggle header */
    .library-content-header {
        padding: 16px 20px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #2a3f5f;
    }

    .library-content-header h1 {
        font-size: 18px;
        font-weight: 300;
        color: #c6d4df;
        margin: 0;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .view-toggle {
        display: flex;
        gap: 4px;
    }

    .view-btn {
        background: #2a3f5f;
        border: 1px solid #1b2838;
        color: #8f98a0;
        padding: 5px 8px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.15s;
    }
    .view-btn:hover, .view-btn.active {
        background: #3d6f8e;
        color: #c6d4df;
    }

    /* ---- GRID VIEW ---- */
    .games-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
        padding: 20px;
    }

    .game-grid-card {
        background: #16202d;
        border-radius: 4px;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s;
        position: relative;
        text-decoration: none;
        display: block;
    }

    .game-grid-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.5);
    }

    .game-grid-card:hover .card-overlay { opacity: 1; }

    .game-card-img {
        width: 100%;
        aspect-ratio: 460/215;
        object-fit: cover;
        display: block;
        background: #2a3f5f;
    }

    .card-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, transparent 40%, rgba(0,0,0,0.85) 100%);
        opacity: 0;
        transition: opacity 0.2s;
        display: flex;
        align-items: flex-end;
        padding: 12px;
    }

    .card-overlay-btn {
        background: #4c6b22;
        color: #d2e885;
        border: none;
        border-radius: 2px;
        padding: 6px 14px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        width: 100%;
        transition: background 0.15s;
    }
    .card-overlay-btn:hover { background: #5c8226; }

    .game-card-body {
        padding: 10px 12px;
    }

    .game-card-name {
        font-size: 13px;
        font-weight: 600;
        color: #c6d4df;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 0 0 5px;
    }

    .game-card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        color: #8f98a0;
    }

    .install-badge {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 2px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .install-badge.installed { background: #4c6b22; color: #d2e885; }
    .install-badge.not-installed { background: #1b2838; color: #5c7e96; border: 1px solid #2a3f5f; }

    /* ---- LIST VIEW ---- */
    .games-list-view {
        padding: 10px 20px;
        display: none;
    }

    .games-list-view.active { display: block; }
    .games-grid.hidden { display: none; }

    .list-game-row {
        display: flex;
        align-items: center;
        padding: 8px 10px;
        border-radius: 3px;
        cursor: pointer;
        gap: 12px;
        text-decoration: none;
        transition: background 0.1s;
        border-bottom: 1px solid rgba(255,255,255,0.04);
    }
    .list-game-row:hover { background: #2f4d6e; }

    .list-thumb {
        width: 60px;
        height: 28px;
        border-radius: 2px;
        object-fit: cover;
        background: #2a3f5f;
        flex-shrink: 0;
    }

    .list-name {
        flex: 1;
        font-size: 13px;
        color: #c6d4df;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .list-hours {
        font-size: 11px;
        color: #8f98a0;
        width: 120px;
        text-align: right;
    }

    .list-status {
        width: 80px;
        text-align: right;
    }

    /* ---- RECENTLY PLAYED BANNER ---- */
    .recently-played-section {
        padding: 20px 20px 0;
    }

    .section-title {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8f98a0;
        font-weight: 700;
        margin: 0 0 10px;
    }

    .recent-games-strip {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 10px;
        scrollbar-width: thin;
        scrollbar-color: #2a475e transparent;
    }
    .recent-games-strip::-webkit-scrollbar { height: 4px; }
    .recent-games-strip::-webkit-scrollbar-thumb { background: #2a475e; border-radius: 2px; }

    .recent-card {
        flex-shrink: 0;
        width: 156px;
        background: #16202d;
        border-radius: 4px;
        overflow: hidden;
        cursor: pointer;
        position: relative;
        transition: transform 0.15s;
        text-decoration: none;
    }
    .recent-card:hover { transform: scale(1.03); }

    .recent-card img {
        width: 100%;
        height: 73px;
        object-fit: cover;
        display: block;
        background: #2a3f5f;
    }

    .recent-card-footer {
        padding: 6px 8px;
        font-size: 10px;
        color: #8f98a0;
    }

    .recent-card-name {
        font-size: 11px;
        color: #c6d4df;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 2px;
    }

    /* ---- STATS BAR ---- */
    .stats-bar {
        display: flex;
        gap: 1px;
        padding: 10px 20px;
        border-bottom: 1px solid #2a3f5f;
    }

    .stat-item {
        flex: 1;
        background: #16202d;
        padding: 8px 12px;
        text-align: center;
    }
    .stat-item:first-child { border-radius: 3px 0 0 3px; }
    .stat-item:last-child { border-radius: 0 3px 3px 0; }

    .stat-value {
        font-size: 18px;
        font-weight: 700;
        color: #57cbde;
        line-height: 1;
    }

    .stat-label {
        font-size: 10px;
        color: #8f98a0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 3px;
    }

    /* Sort dropdown */
    .sort-select {
        background: #316282;
        border: 1px solid #1b2838;
        color: #c6d4df;
        font-size: 12px;
        padding: 5px 8px;
        border-radius: 3px;
        outline: none;
        cursor: pointer;
    }
</style>



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