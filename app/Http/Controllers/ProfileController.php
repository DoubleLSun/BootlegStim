<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show a user's public profile page.
     * Route: GET /profile/{user}
     */
    public function show(User $user)
    {
        

        $user->load('statusGame');

        $recentGames = collect();
        try {
            $recentGames = $user->games()
                ->orderByPivot('last_played_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            // user_games table not yet migrated
        }

        $totalGames = $recentGames->count();

        $friends = collect([
            ['name' => 'FragMaster99',  'status' => 'online',  'avatar' => null],
            ['name' => 'PixelWitch',    'status' => 'away',    'avatar' => null],
            ['name' => 'ShadowRunner',  'status' => 'offline', 'avatar' => null],
            ['name' => 'NeonGhost',     'status' => 'busy',    'avatar' => null],
            ['name' => 'CryptoKnight', 'status' => 'online',  'avatar' => null],
        ])->map(function ($f) {
            $f['avatar'] = $f['avatar']
                ?? 'https://ui-avatars.com/api/?name=' . urlencode($f['name'])
                   . '&size=64&background=1a1a2e&color=ccece8&bold=true';
            return $f;
        });

        $badges = collect([
            ['name' => 'Early Adopter',    'xp' => 100, 'icon' => '🏅'],
            ['name' => 'Game Collector',   'xp' => 250, 'icon' => '🎮'],
            ['name' => 'Community Helper', 'xp' => 50,  'icon' => '🤝'],
        ]);

        return view('profile.profilePage', compact(
            'user', 'recentGames', 'totalGames', 'friends', 'badges'
        ));
    }

    /**
     * Show the edit profile form.
     * Route: GET /profile/{user}/edit
     */
    public function edit(User $user)
    {
        $games = Game::orderBy('title')->get();
        return view('profile.editProfile', compact('user', 'games'));
    }

    /**
     * Save updated profile data to the database.
     * Route: POST /profile/{user}/update
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'bio'            => 'nullable|string|max:1000',
            'location'       => 'nullable|string|max:120',
            'avatar_url'     => 'nullable|url|max:500',
            'banner_url'     => 'nullable|url|max:500',
            'status'         => 'required|in:online,away,busy,offline',
            'status_game_id' => 'nullable|exists:games,id',
        ]);

        // Clear status_game_id if status is offline or away
        if (in_array($validated['status'], ['offline', 'away'])) {
            $validated['status_game_id'] = null;
        }

        // Update last_online_at when going online
        if ($validated['status'] === 'online') {
            $validated['last_online_at'] = now();
        }

        $user->update($validated);

        return redirect()
            ->route('profile.show', $user)
            ->with('success', 'Profile updated successfully!');
    }
}