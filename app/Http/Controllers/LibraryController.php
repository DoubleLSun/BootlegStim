<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Game;

class LibraryController extends Controller
{
    /**
     * Display the authenticated user's game library.
     */
    public function libraryPage(Request $request)
    {
        $user = Auth::user();

        // Eager-load games with pivot data (hours_played, is_installed, last_played)
        $games = $user->games()
            ->withPivot(['hours_played', 'is_installed', 'last_played'])
            ->orderBy('title')
            ->get();

        // Recently played: last 6 games with a last_played date, sorted by most recent
        $recentGames = $user->games()
            ->withPivot(['hours_played', 'is_installed', 'last_played'])
            ->whereNotNull('user_games.last_played')
            ->orderByDesc('user_games.last_played')
            ->limit(6)
            ->get();

        return view('library.libraryPage', compact('games', 'recentGames', 'user'));
    }

    /**
     * Show a single game detail inside the library context.
     */
    public function show(Game $game)
    {
        $user = Auth::user();

        $selectedGame = $user->games()
            ->withPivot(['hours_played', 'is_installed', 'last_played'])
            ->findOrFail($game->id);

        $games = $user->games()
            ->withPivot(['hours_played', 'is_installed', 'last_played'])
            ->orderBy('title')
            ->get();

        $recentGames = $user->games()
            ->withPivot(['hours_played', 'is_installed', 'last_played'])
            ->whereNotNull('user_games.last_played')
            ->orderByDesc('user_games.last_played')
            ->limit(6)
            ->get();

        return view('library.libraryPage', compact('games', 'recentGames', 'selectedGame','user'));
    }
}
