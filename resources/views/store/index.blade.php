@extends('layouts.app')

@section('title', 'Game Store | Home')

@push('styles')
<style>
/* GLOBAL STEAM STYLE */
body {
    background:
        radial-gradient(circle at top left, #1b2838 0%, transparent 50%),
        radial-gradient(circle at top right, #13202e 0%, transparent 55%),
        linear-gradient(180deg, #0e141b 0%, #0b1016 100%);
    color: #c6d4df;
    font-family: Arial, Helvetica, sans-serif;
}

/* MAIN CONTAINER */
.main-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 28px;
}

/* SEARCH */
.search-form {
    display: flex;
    gap: 10px;
}

.search-input {
    width: 100%;
    padding: 12px 14px;
    border-radius: 4px;
    background: #2a475e;
    border: 1px solid #1b2838;
    color: #c6d4df;
    outline: none;
}

.search-input:focus {
    border-color: #66c0f4;
    box-shadow: 0 0 6px rgba(102,192,244,0.3);
}

.search-button {
    background: linear-gradient(#66c0f4, #1b6aa5);
    color: #0e141b;
    padding: 12px 18px;
    border-radius: 4px;
    border: none;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
}

.search-button:hover {
    filter: brightness(1.1);
}

/* SECTION TITLE */
.section-title {
    font-size: 18px;
    font-weight: bold;
    margin: 18px 0;
    color: #66c0f4;
    letter-spacing: 0.5px;
}

/* FEATURED GRID */
.featured-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}

/* FEATURED CARD */
.featured-card {
    background: #1b2838;
    border: 1px solid #2a475e;
    border-radius: 4px;
    overflow: hidden;
    transition: 0.2s;
}

.featured-card:hover {
    transform: translateY(-4px);
    border-color: #66c0f4;
}

.featured-card img {
    width: 100%;
    height: 240px;
    object-fit: cover;
}

.featured-overlay {
    padding: 10px;
    background: #1b2838;
}

.featured-title {
    font-size: 16px;
    font-weight: bold;
}

.view-link {
    color: #66c0f4;
    font-size: 13px;
    text-decoration: none;
}

.view-link:hover {
    text-decoration: underline;
}

/* ALL GAMES GRID */
.games-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 12px;
}

/* GAME CARD */
.game-card {
    background: #1b2838;
    border: 1px solid #2a475e;
    border-radius: 4px;
    padding: 8px;
    text-decoration: none;
    color: #c6d4df;
    transition: 0.2s;
}

.game-card:hover {
    border-color: #66c0f4;
    transform: translateY(-3px);
}

.game-card img {
    width: 100%;
    height: 130px;
    object-fit: cover;
    margin-bottom: 6px;
}

.game-title {
    font-size: 13px;
    font-weight: bold;
    white-space: nowrap;
    overflow: hidden;
}

.game-price {
    color: #66c0f4;
    font-size: 13px;
}

/* ADMIN BAR */
.admin-banner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #1b2838;
    border: 1px solid #2a475e;
    padding: 10px 16px;
    margin: 12px;
}

.status-dot {
    width: 8px;
    height: 8px;
    background: #66c0f4;
    border-radius: 50%;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.5); opacity: 0.5; }
    100% { transform: scale(1); opacity: 1; }
}

.admin-text .title {
    font-size: 13px;
    font-weight: bold;
    color: #66c0f4;
}

.admin-text .subtitle {
    font-size: 11px;
    color: #8f98a0;
}

.admin-button {
    background: #66c0f4;
    color: #0e141b;
    padding: 6px 10px;
    border-radius: 3px;
    font-weight: bold;
    text-decoration: none;
}

.admin-button:hover {
    filter: brightness(1.1);
}
</style>
@endpush

@section('content')

<main class="main-container">

    <!-- Search -->
    <section class="search-section">
        <form action="{{ route('store.index') }}" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search games..." class="search-input">
            <button type="submit" class="search-button">Search</button>
        </form>
    </section>

    <!-- Featured -->
    <section style="margin-bottom: 50px;">
        <h2 class="section-title">Featured Games</h2>

        <div class="featured-grid">
            @foreach($featuredGames as $game)
                <div class="featured-card">
                    <img src="{{ $game->cover_image }}">

                    <div class="featured-overlay">
                        <h3 class="featured-title">{{ $game->title }}</h3>

                        <a href="{{ route('games.show', $game->id) }}" class="view-link">
                            View Game
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- All Games -->
    <section>
        <h2 class="section-title">All Games</h2>

        <div class="games-grid">
            @foreach($allGames as $game)
                <a href="{{ route('games.show', $game->id) }}" class="game-card">

                    <img src="{{ $game->cover_image }}">

                    <p class="game-title">{{ $game->title }}</p>

                    <p class="game-price">${{ $game->price }}</p>

                </a>
            @endforeach
        </div>
    </section>

</main>

@endsection