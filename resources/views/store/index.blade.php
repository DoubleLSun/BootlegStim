<!DOCTYPE html>
<html>
<head>
    <title>Game Store | Home</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script> </head>
<body class="bg-gray-900 text-white">

    <main class="container mx-auto p-6">
        <section class="mb-10">
            <form action="/" method="GET" class="flex gap-2">
                <input type="text" name="search" placeholder="Search games..." 
                       class="w-full p-3 rounded bg-gray-800 border border-gray-700 text-white">
                <button type="submit" class="bg-blue-600 px-6 py-2 rounded">Search</button>
            </form>
        </section>

        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-4 text-yellow-400">Featured Games</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredGames as $game)
                    <div class="relative rounded-lg overflow-hidden border-2 border-yellow-500">
                        <img src="{{ $game->cover_image }}" class="w-full h-64 object-cover">
                        <div class="absolute bottom-0 p-4 bg-gradient-to-t from-black w-full">
                            <h3 class="text-xl font-bold">{{ $game->title }}</h3>
                            <a href="{{ route('games.show', $game->id) }}" class="text-blue-400">View Game</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section>
            <h2 class="text-2xl font-bold mb-4">All Games</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($allGames as $game)
                    <a href="{{ route('games.show', $game->id) }}" class="bg-gray-800 p-2 rounded hover:bg-gray-700">
                        <img src="{{ $game->cover_image }}" class="w-full h-40 object-cover rounded mb-2">
                        <p class="font-semibold truncate">{{ $game->title }}</p>
                        <p class="text-green-400">${{ $game->price }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    </main>

</body>
</html>