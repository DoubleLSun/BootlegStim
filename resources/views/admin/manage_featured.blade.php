@extends('layouts.app')

@section('title', 'Admin | Manage Featured Games')

@push('styles')
<style>
    :root {
        --bg-dark: #0f172a;
        --card-bg: #1e293b;
        --accent-blue: #38bdf8;
        --accent-green: #10b981;
        --text-main: #ffffff; /* Forced to pure white */
        --text-muted: #94a3b8;
        --border-color: #334155;
    }

    body {
        background-color: var(--bg-dark);
        color: var(--text-main);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        -webkit-font-smoothing: antialiased;
    }

    .admin-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 60px 20px;
    }

    /* HEADER - ENHANCED WHITE TITLE */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 40px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 24px;
    }

    .title-group h1 {
        font-size: 36px; /* Slightly bigger */
        font-weight: 900; /* Extra bold */
        letter-spacing: -1.5px;
        margin: 0;
        color: #ffffff; /* Solid White */
        text-transform: tight;
    }

    .title-group p {
        color: var(--text-muted);
        margin: 8px 0 0 0;
        font-size: 15px;
    }

    .back-link {
        color: var(--text-muted);
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--border-color);
    }

    .back-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        transform: translateY(-2px);
    }

    /* TABLE DESIGN */
    .table-wrapper {
        background: var(--card-bg);
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
        overflow: hidden;
    }

    .section-title {
        margin: 34px 0 12px;
        font-size: 18px;
        color: #fff;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    th {
        background: rgba(15, 23, 42, 0.8);
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        padding: 22px 24px;
        text-align: left;
    }

    td {
        padding: 20px 24px;
        border-top: 1px solid var(--border-color);
        vertical-align: middle;
    }

    /* GAME ELEMENT */
    .game-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .game-thumbnail-wrapper {
        width: 110px; /* Bigger thumbnail */
        height: 62px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.4);
        border: 1px solid var(--border-color);
    }

    .game-thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .game-title {
        font-weight: 700;
        font-size: 18px; /* Bigger Title */
        color: #ffffff;
    }

    /* BIGGER BADGE SYSTEM */
    .toggle-container {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 16px;
    }

    .badge {
        font-size: 13px; /* Much bigger */
        padding: 6px 14px;
        border-radius: 8px; /* Squared off slightly for a pro look */
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: 1px solid transparent;
        transition: 0.4s;
    }

    .badge-standard {
        background: rgba(148, 163, 184, 0.1);
        color: #cbd5e1;
        border-color: rgba(148, 163, 184, 0.3);
    }

    .badge-featured {
        background: rgba(16, 185, 129, 0.15);
        color: var(--accent-green);
        border-color: rgba(16, 185, 129, 0.4);
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.1);
    }

    /* ENHANCED SWITCH */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px; /* Wider switch */
        height: 30px;
    }

    .switch input { opacity: 0; width: 0; height: 0; }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #334151;
        transition: .4s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 34px;
        border: 1px solid var(--border-color);
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 4px;
        bottom: 3px;
        background-color: #fff;
        transition: .4s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: var(--accent-green);
        border-color: #10b981;
    }

    input:checked + .slider:before {
        transform: translateX(28px);
    }

    tbody tr:hover {
        background: rgba(255, 255, 255, 0.02);
    }
</style>
@endpush

@section('content')
<div class="admin-container">

    <div class="header">
        <div class="title-group">
            <h1>Featured Content Manager</h1>
            <p>Manage which games are featured on the store.</p>
        </div>
        <a href="{{ route('store.index') }}" class="back-link">
            <span>&larr;</span> Exit Admin
        </a>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Game Assets & Info</th>
                    <th style="text-align: right; padding-right: 50px;">Visibility Control</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allGames as $game)
                    <tr>
                        <td>
                            <div class="game-info">
                                <div class="game-thumbnail-wrapper">
                                    <img src="{{ $game->cover_image }}" alt="{{ $game->title }}" class="game-thumbnail">
                                </div>
                                <span class="game-title">{{ $game->title }}</span>
                            </div>
                        </td>
                        <td>
                            <form action="{{ route('admin.toggle', $game->id) }}" method="POST" id="toggle-form-{{ $game->id }}">
                                @csrf
                                <div class="toggle-container">
                                    
                                    <span class="badge {{ $game->is_featured ? '' : 'badge-standard' }}" 
                                          style="opacity: {{ $game->is_featured ? '0.15' : '1' }}">
                                        Standard
                                    </span>

                                    <label class="switch">
                                        <input type="checkbox" 
                                               {{ $game->is_featured ? 'checked' : '' }} 
                                               onchange="document.getElementById('toggle-form-{{ $game->id }}').submit()">
                                        <span class="slider"></span>
                                    </label>

                                    <span class="badge {{ $game->is_featured ? 'badge-featured' : '' }}" 
                                          style="opacity: {{ !$game->is_featured ? '0.15' : '1' }}">
                                        Featured
                                    </span>

                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="padding: 60px; text-align: center; color: var(--text-muted);">
                            No titles found in the database.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h2 class="section-title">Genre Visibility</h2>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Genre</th>
                    <th style="text-align: right; padding-right: 50px;">Display Flag</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allGenres as $genre)
                    <tr>
                        <td>
                            <div class="game-info" style="gap:12px;">
                                <span class="game-title" style="font-size:16px;">{{ $genre->name }}</span>
                                <span style="font-size:12px;color:var(--text-muted);">{{ $genre->games_count }} games</span>
                            </div>
                        </td>
                        <td>
                            <form action="{{ route('admin.genres.toggle-display', $genre) }}" method="POST" id="toggle-genre-form-{{ $genre->id }}">
                                @csrf
                                <div class="toggle-container">
                                    <span class="badge {{ $genre->display_flag ? '' : 'badge-standard' }}" style="opacity: {{ $genre->display_flag ? '0.15' : '1' }}">
                                        Hidden
                                    </span>

                                    <label class="switch">
                                        <input
                                            type="checkbox"
                                            {{ $genre->display_flag ? 'checked' : '' }}
                                            onchange="document.getElementById('toggle-genre-form-{{ $genre->id }}').submit()"
                                        >
                                        <span class="slider"></span>
                                    </label>

                                    <span class="badge {{ $genre->display_flag ? 'badge-featured' : '' }}" style="opacity: {{ !$genre->display_flag ? '0.15' : '1' }}">
                                        Visible
                                    </span>
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="padding: 40px; text-align: center; color: var(--text-muted);">
                            No genres found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection