<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        //  Get Featured Games 
        $featuredGames = Game::where('is_featured', true)
            ->where('is_delisted', false)
            ->take(3)
            ->get();

        //  Search Logic
        $search = $request->input('search');
        $allGames = Game::where('is_delisted', false)->when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{$search}%");
        })->get();

        return view('store.index', compact('featuredGames', 'allGames'));
    }
}