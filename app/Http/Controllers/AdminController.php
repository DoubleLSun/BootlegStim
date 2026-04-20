<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameGenre;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard list of all games.
     */
    public function manageFeatured()
    {
        // Get all games alphabetically to make management easier
        $allGames = Game::orderBy('title', 'asc')->get();
        $allGenres = GameGenre::query()
            ->withCount('games')
            ->orderByDesc('games_count')
            ->orderBy('name')
            ->get();
        
        return view('admin.manage_featured', compact('allGames', 'allGenres'));
    }

    /**
     * Toggle the featured status of a specific game.
     */
    public function toggleFeatured(Game $game)
    {
        // Flip the boolean value
        $game->is_featured = !$game->is_featured;
        $game->save();

        return back()->with('success', 'Featured status updated for ' . $game->title);
    }

    public function toggleGenreDisplay(GameGenre $genre)
    {
        $genre->display_flag = !$genre->display_flag;
        $genre->save();

        return back()->with('success', 'Genre visibility updated for ' . $genre->name);
    }
}