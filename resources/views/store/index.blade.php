<!DOCTYPE html>
<html>
<head>
    <title>Game Store | Home</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* MAIN LAYOUT */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        /* SEARCH */
        .search-section {
            margin-bottom: 40px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            background: #1f2937;
            border: 1px solid #374151;
            color: white;
            outline: none;
        }

        .search-button {
            background: #2563eb;
            padding: 10px 24px;
            border-radius: 8px;
            color: white;
            border: none;
            cursor: pointer;
        }

        .search-button:hover {
            background: #1d4ed8;
        }

        /* SECTION TITLE */
        .section-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 16px;
        }

        .yellow {
            color: #facc15;
        }

        /* FEATURED GAMES */
        .featured-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .featured-card {
            position: relative;
            border: 2px solid #facc15;
            border-radius: 10px;
            overflow: hidden;
        }

        .featured-card img {
            width: 100%;
            height: 260px;
            object-fit: cover;
        }

        .featured-overlay {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 16px;
            background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
        }

        .featured-title {
            font-size: 18px;
            font-weight: bold;
        }

        .view-link {
            color: #60a5fa;
            text-decoration: none;
        }

        /* ALL GAMES */
        .games-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 16px;
        }

        .game-card {
            background: #1f2937;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            transition: 0.2s;
        }

        .game-card:hover {
            background: #374151;
        }

        .game-card img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .game-title {
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .game-price {
            color: #4ade80;
        }

        /* ADMIN BANNER (unchanged) */
        .admin-banner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(90deg, #1f2937, #111827);
            border: 1px solid #c0ae68;
            padding: 12px 20px;
            margin: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }

        .admin-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            background-color: #22c55e;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.5); opacity: 0.6; }
            100% { transform: scale(1); opacity: 1; }
        }

        .admin-text .title {
            font-size: 16px;
            font-weight: bold;
            color: #facc15;
        }

        .admin-text .subtitle {
            font-size: 12px;
            color: #9ca3af;
        }

        .admin-button {
            background-color: #facc15;
            color: #111827;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        .admin-button:hover {
            background-color: #eab308;
            transform: scale(1.05);
        }
    </style>
</head>

<body class="bg-gray-900 text-white">

@auth
    @if(auth()->user()->role == 'admin')
        <div class="admin-banner">
            <div class="admin-left">
                <div class="status-dot"></div>
                <div class="admin-text">
                    <div class="title">Admin Control Panel</div>
                    <div class="subtitle">Full system privileges enabled</div>
                </div>
            </div>

            <a href="{{ route('admin.manage') }}" class="admin-button">
                Open Dashboard
            </a>
        </div>
    @endif
@endauth

<main class="main-container">

    <!-- Search Section -->
    <section class="search-section">
        <form action="{{ route('store.index') }}" method="GET" class="search-form">
            <input
                type="text"
                name="search"
                placeholder="Search games..."
                class="search-input"
            >
            <button type="submit" class="search-button">
                Search
            </button>
        </form>
    </section>

    <!-- Featured Games -->
    <section style="margin-bottom: 50px;">
        <h2 class="section-title yellow">
            Featured Games
        </h2>

        <div class="featured-grid">
            @foreach($featuredGames as $game)
                <div class="featured-card">
                    <img src="{{ $game->cover_image }}">

                    <div class="featured-overlay">
                        <h3 class="featured-title">
                            {{ $game->title }}
                        </h3>

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
        <h2 class="section-title">
            All Games
        </h2>

        <div class="games-grid">
            @foreach($allGames as $game)
                <a href="{{ route('games.show', $game->id) }}" class="game-card">

                    <img src="{{ $game->cover_image }}">

                    <p class="game-title">
                        {{ $game->title }}
                    </p>

                    <p class="game-price">
                        ${{ $game->price }}
                    </p>

                </a>
            @endforeach
        </div>
    </section>

</main>

</body>
</html>