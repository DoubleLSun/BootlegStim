<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserGamesSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        // Look up actual game IDs by title to avoid hard-coded IDs that may not exist
        $gamesByTitle = DB::table('games')->pluck('id', 'title');
        $usersByEmail = DB::table('users')->pluck('id', 'email');

        if ($gamesByTitle->isEmpty() || $usersByEmail->isEmpty()) {
            return;
        }

        // Use the first seeded user (FragMaster99)
        $userId = $usersByEmail->get('frag@example.com') ?? $usersByEmail->first();

        $libraryEntries = [
            ['title' => 'The Witcher 3: Wild Hunt', 'hours' => 42.50, 'installed' => true,  'last_played' => '2026-04-12 12:03:06'],
            ['title' => 'Cyberpunk 2077',            'hours' => 18.00, 'installed' => true,  'last_played' => '2026-04-09 12:03:06'],
            ['title' => 'Hades',                     'hours' => 7.25,  'installed' => false, 'last_played' => '2026-04-02 12:03:06'],
        ];

        foreach ($libraryEntries as $entry) {
            $gameId = $gamesByTitle->get($entry['title']);
            if (!$gameId) {
                continue;
            }

            DB::table('user_games')->updateOrInsert(
                ['user_id' => $userId, 'game_id' => $gameId],
                [
                    'hours_played'  => $entry['hours'],
                    'is_installed'  => $entry['installed'],
                    'last_played'   => $entry['last_played'],
                    'purchased_at'  => $now,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]
            );
        }
    }
}