<?php

namespace App\Http\Controllers;

use App\Models\Game;

class GamePageController extends Controller
{
    public function show(Game $game)
    {
        $game->load(['getMedia' => function ($query) {
            $query->where('type', 'image')
                ->orderByDesc('is_cover')
                ->orderBy('sort_order')
                ->orderBy('id');
        }]);
        
        return view('games.gamesPage', [
            'game' => $game,
            'mediaItems' => $game->getMedia,
        ]);
    }
}
