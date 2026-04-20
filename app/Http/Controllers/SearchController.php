<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameGenre;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // keyword trimming 
        $queryText = trim((string) $request->query('q', ''));
        // collect selected genre IDs, map function converts all values to integer, 
        // filter removes non-positive integers,
        // unique ensures no duplicates, 
        // values resets the keys
        $selectedGenreIds = collect($request->query('genres', []))
            ->map(fn($value) => (int) $value)
            ->filter(fn($id) => $id > 0)
            ->unique()
            ->values();
        // get genre with display_flag true
        $visibleGenres = GameGenre::query()
            ->where('display_flag', true)
            ->withCount('games')
            ->orderByDesc('games_count')
            ->orderBy('name')
            ->get();

        $gamesQuery = Game::query()
            ->where('is_delisted', false)
            ->with(['genres' => function ($q) {
                $q->where('display_flag', true)->orderBy('name');
            }, 'getGamePricing' => function ($q) {
                $q->orderBy('id');
            }]);

        if ($queryText !== '') {
            $gamesQuery->where('title', 'like', '%' . $queryText . '%');
        }

        if ($selectedGenreIds->isNotEmpty()) {
            foreach ($selectedGenreIds as $genreId) {
                $gamesQuery->whereHas('genres', function ($genreQuery) use ($genreId) {
                    $genreQuery->where('game_genres.id', $genreId)
                        ->where('display_flag', true);
                });
            }
        }

        $results = $gamesQuery
            ->orderBy('title')
            ->paginate(20)
            ->appends($request->query());

        $selectedGenres = $visibleGenres
            ->whereIn('id', $selectedGenreIds)
            ->values();

        return view('search.searchPage', [
            'queryText' => $queryText,
            'results' => $results,
            'visibleGenres' => $visibleGenres,
            'selectedGenres' => $selectedGenres,
            'selectedGenreIds' => $selectedGenreIds,
        ]);
    }

    public function preview(Request $request)
    {
        $queryText = trim((string) $request->query('q', ''));

        if ($queryText === '') {
            return response()->json(['results' => []]);
        }

        $results = Game::query()
            ->select(['id', 'title', 'cover_image'])
            ->where('is_delisted', false)
            ->where('title', 'like', '%' . $queryText . '%')
            ->orderBy('title')
            ->limit(3)
            ->get()
            ->map(function (Game $game) {
                return [
                    'id' => $game->id,
                    'title' => $game->title,
                    'cover_image' => $game->cover_image,
                    'url' => route('games.show', ['game' => $game]),
                ];
            })
            ->values();

        return response()->json(['results' => $results]);
    }
}
