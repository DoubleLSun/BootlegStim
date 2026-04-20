<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameGenre;
use App\Models\GameMedia;
use App\Models\GamePricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard list of all games.
     */
    public function manageFeatured()
    {
        // Get all games alphabetically to make management easier
        $allGames = Game::query()
            ->with(['genres' => function ($q) {
                $q->orderBy('name');
            }, 'getGamePricing' => function ($q) {
                $q->orderBy('id');
            }, 'media' => function ($q) {
                $q->orderByDesc('is_cover')->orderBy('sort_order')->orderBy('id');
            }])
            ->orderBy('title', 'asc')
            ->get();

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

    public function createGame(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:games,title',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'release_date' => 'required|date',
            'developer_id' => 'required|integer|min:1',
            'publisher_id' => 'required|integer|min:1',
            'cover_image' => 'nullable|url|max:2048',
            'genre_ids' => 'nullable|array',
            'genre_ids.*' => 'integer|exists:game_genres,id',
        ]);

        $game = Game::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'release_date' => $validated['release_date'],
            'is_featured' => false,
            'is_delisted' => false,
            'use_pricing_tag' => false,
            'selected_pricing_id' => null,
            'created_by' => (int) auth()->id(),
            'developer_id' => $validated['developer_id'],
            'publisher_id' => $validated['publisher_id'],
            'cover_image' => $validated['cover_image'] ?? null,
        ]);

        if (!empty($validated['genre_ids'])) {
            $game->genres()->sync($validated['genre_ids']);
        }

        return back()->with('success', 'Game created: ' . $game->title);
    }

    public function updateGame(Request $request, Game $game)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:games,title,' . $game->id,
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'release_date' => 'required|date',
            'developer_id' => 'required|integer|min:1',
            'publisher_id' => 'required|integer|min:1',
            'cover_image' => 'nullable|url|max:2048',
            'genre_ids' => 'nullable|array',
            'genre_ids.*' => 'integer|exists:game_genres,id',
        ]);

        $game->fill([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'release_date' => $validated['release_date'],
            'developer_id' => $validated['developer_id'],
            'publisher_id' => $validated['publisher_id'],
            'cover_image' => $validated['cover_image'] ?? null,
        ])->save();

        $game->genres()->sync($validated['genre_ids'] ?? []);

        return back()->with('success', 'Game updated: ' . $game->title);
    }

    public function toggleDelisted(Game $game)
    {
        $game->is_delisted = !$game->is_delisted;
        $game->save();

        return back()->with('success', 'Delist status updated for ' . $game->title);
    }

    public function createGenre(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:game_genres,name',
            'slug' => 'required|string|max:255|unique:game_genres,slug',
            'description' => 'nullable|string',
            'display_flag' => 'nullable|boolean',
        ]);

        GameGenre::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'display_flag' => (bool) ($validated['display_flag'] ?? true),
        ]);

        return back()->with('success', 'Genre created successfully.');
    }

    public function updateGenre(Request $request, GameGenre $genre)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:game_genres,name,' . $genre->id,
            'slug' => 'required|string|max:255|unique:game_genres,slug,' . $genre->id,
            'description' => 'nullable|string',
            'display_flag' => 'nullable|boolean',
        ]);

        $genre->fill([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'display_flag' => (bool) ($validated['display_flag'] ?? false),
        ])->save();

        return back()->with('success', 'Genre updated: ' . $genre->name);
    }

    public function deleteGenre(GameGenre $genre)
    {
        $name = $genre->name;
        $genre->delete();

        return back()->with('success', 'Genre deleted: ' . $name);
    }

    public function createPricing(Request $request, Game $game)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discounted_price' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
        ]);

        GamePricing::create([
            'game_id' => $game->id,
            'price' => $validated['price'],
            'discount_percentage' => $validated['discount_percentage'] ?? null,
            'discounted_price' => $validated['discounted_price'] ?? null,
            'currency' => strtoupper($validated['currency']),
        ]);

        return back()->with('success', 'Pricing created for ' . $game->title);
    }

    public function updatePricing(Request $request, Game $game, GamePricing $pricing)
    {
        if ((int) $pricing->game_id !== (int) $game->id) {
            abort(404);
        }

        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discounted_price' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
        ]);

        $pricing->fill([
            'price' => $validated['price'],
            'discount_percentage' => $validated['discount_percentage'] ?? null,
            'discounted_price' => $validated['discounted_price'] ?? null,
            'currency' => strtoupper($validated['currency']),
        ])->save();

        return back()->with('success', 'Pricing updated for ' . $game->title);
    }

    public function deletePricing(Game $game, GamePricing $pricing)
    {
        if ((int) $pricing->game_id !== (int) $game->id) {
            abort(404);
        }

        if ((int) ($game->selected_pricing_id ?? 0) === (int) $pricing->id) {
            $game->selected_pricing_id = null;
            $game->use_pricing_tag = false;
            $game->save();
        }

        $pricing->delete();

        return back()->with('success', 'Pricing deleted from ' . $game->title);
    }

    public function setPricingTag(Request $request, Game $game)
    {
        $validated = $request->validate([
            'use_pricing_tag' => 'nullable|boolean',
            'selected_pricing_id' => 'nullable|integer|exists:game_pricings,id',
        ]);

        $selectedPricingId = !empty($validated['selected_pricing_id']) ? (int) $validated['selected_pricing_id'] : null;
        if ($selectedPricingId) {
            $belongsToGame = GamePricing::query()
                ->where('id', $selectedPricingId)
                ->where('game_id', $game->id)
                ->exists();
            if (!$belongsToGame) {
                return back()->withErrors(['selected_pricing_id' => 'Selected pricing does not belong to this game.']);
            }
        }

        $game->use_pricing_tag = (bool) ($validated['use_pricing_tag'] ?? false);
        $game->selected_pricing_id = $selectedPricingId;
        $game->save();

        return back()->with('success', 'Pricing tag settings updated for ' . $game->title);
    }

    public function addMedia(Request $request, Game $game)
    {
        $validated = $request->validate([
            'image_links' => 'nullable|string',
            'video_links' => 'nullable|string',
            'image_files' => 'nullable|array',
            'image_files.*' => 'file|mimes:jpg,jpeg,png,webp,gif|max:6144',
        ]);

        $nextSortOrder = (int) GameMedia::query()->where('game_id', $game->id)->max('sort_order');

        $insertFromLinks = function (?string $raw, string $type) use (&$nextSortOrder, $game) {
            $raw = trim((string) $raw);
            if ($raw === '') {
                return;
            }

            $lines = preg_split('/\r\n|\r|\n/', $raw);
            foreach ($lines as $line) {
                $url = trim($line);
                if ($url === '') {
                    continue;
                }
                $nextSortOrder++;
                GameMedia::create([
                    'game_id' => $game->id,
                    'type' => $type,
                    'url' => $url,
                    'thumbnail_url' => $type === 'image' ? $url : null,
                    'sort_order' => $nextSortOrder,
                    'is_cover' => false,
                ]);
            }
        };

        $insertFromLinks($validated['image_links'] ?? null, 'image');
        $insertFromLinks($validated['video_links'] ?? null, 'video');

        foreach ($request->file('image_files', []) as $file) {
            $directory = public_path('game-media');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $filename = uniqid('game_', true) . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $url = asset('game-media/' . $filename);
            $nextSortOrder++;

            GameMedia::create([
                'game_id' => $game->id,
                'type' => 'image',
                'url' => $url,
                'thumbnail_url' => $url,
                'sort_order' => $nextSortOrder,
                'is_cover' => false,
            ]);
        }

        return back()->with('success', 'Media added for ' . $game->title);
    }

    public function deleteMedia(Game $game, GameMedia $media)
    {
        if ((int) $media->game_id !== (int) $game->id) {
            abort(404);
        }

        $media->delete();

        return back()->with('success', 'Media entry removed from ' . $game->title);
    }
}