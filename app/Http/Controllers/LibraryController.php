<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Game;
use Carbon\Carbon;

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

    /**
     * Mark an owned game as installed.
     */
    public function install(Game $game)
    {
        $user = Auth::user();

        $ownedGame = $user->games()
            ->withPivot(['hours_played', 'is_installed', 'last_played'])
            ->findOrFail($game->id);

        if (!($ownedGame->pivot->is_installed ?? false)) {
            $user->games()->updateExistingPivot($game->id, [
                'is_installed' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Installation complete.',
        ]);
    }

    /**
     * Start a play session for an installed owned game.
     */
    public function startPlay(Game $game)
    {
        $user = Auth::user();

        $ownedGame = $user->games()
            ->withPivot(['hours_played', 'is_installed', 'last_played'])
            ->findOrFail($game->id);

        if (!($ownedGame->pivot->is_installed ?? false)) {
            return response()->json([
                'success' => false,
                'message' => 'Install the game before playing.',
            ], 422);
        }

        session([
            $this->playSessionKey((int) $user->id, (int) $game->id) => now()->toIso8601String(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Play session started.',
        ]);
    }

    /**
     * Stop the active play session and persist played time to pivot.
     */
    public function stopPlay(Game $game)
    {
        $user = Auth::user();

        $ownedGame = $user->games()
            ->withPivot(['hours_played', 'is_installed', 'last_played'])
            ->findOrFail($game->id);

        if (!($ownedGame->pivot->is_installed ?? false)) {
            return response()->json([
                'success' => false,
                'message' => 'Install the game before playing.',
            ], 422);
        }

        $key = $this->playSessionKey((int) $user->id, (int) $game->id);
        $startedAtRaw = session($key);
        $endedAt = now();

        $secondsPlayed = 0;
        if ($startedAtRaw) {
            $startedAt = Carbon::parse($startedAtRaw);
            $secondsPlayed = max(0, $startedAt->diffInSeconds($endedAt));
        }

        $hoursPlayed = (float) ($ownedGame->pivot->hours_played ?? 0);
        $hoursPlayed += ($secondsPlayed / 3600);

        $user->games()->updateExistingPivot($game->id, [
            'hours_played' => round($hoursPlayed, 2),
            'last_played' => $endedAt,
        ]);

        session()->forget($key);

        return response()->json([
            'success' => true,
            'message' => 'Play session stopped.',
            'seconds_played' => $secondsPlayed,
        ]);
    }

    private function playSessionKey(int $userId, int $gameId): string
    {
        return "library.playing.{$userId}.{$gameId}";
    }
}
