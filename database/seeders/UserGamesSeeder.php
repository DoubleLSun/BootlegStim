<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserGamesSeeder extends Seeder
{
    public function run()
    {
        DB::table('user_games')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'game_id' => 1,
                'hours_played' => 42.50,
                'is_installed' => true,
                'last_played' => '2026-04-12 12:03:06',
                'purchased_at' => '2026-04-12 12:03:06',
                'created_at' => '2026-04-12 12:03:06',
                'updated_at' => '2026-04-12 12:03:06',
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'game_id' => 2,
                'hours_played' => 18.00,
                'is_installed' => true,
                'last_played' => '2026-04-09 12:03:06',
                'purchased_at' => '2026-04-12 12:03:06',
                'created_at' => '2026-04-12 12:03:06',
                'updated_at' => '2026-04-12 12:03:06',
            ],
            [
                'id' => 3,
                'user_id' => 1,
                'game_id' => 3,
                'hours_played' => 7.25,
                'is_installed' => false,
                'last_played' => '2026-04-02 12:03:06',
                'purchased_at' => '2026-04-12 12:03:06',
                'created_at' => '2026-04-12 12:03:06',
                'updated_at' => '2026-04-12 12:03:06',
            ],
            [
                'id' => 4,
                'user_id' => 1,
                'game_id' => 4,
                'hours_played' => 103.00,
                'is_installed' => true,
                'last_played' => '2026-04-11 12:03:06',
                'purchased_at' => '2026-04-12 12:03:06',
                'created_at' => '2026-04-12 12:03:06',
                'updated_at' => '2026-04-12 12:03:06',
            ],
            [
                'id' => 5,
                'user_id' => 1,
                'game_id' => 5,
                'hours_played' => 0.00,
                'is_installed' => false,
                'last_played' => null,
                'purchased_at' => '2026-04-12 12:03:06',
                'created_at' => '2026-04-12 12:03:06',
                'updated_at' => '2026-04-12 12:03:06',
            ],
            [
                'id' => 6,
                'user_id' => 1,
                'game_id' => 6,
                'hours_played' => 55.75,
                'is_installed' => true,
                'last_played' => '2026-04-07 12:03:06',
                'purchased_at' => '2026-04-12 12:03:06',
                'created_at' => '2026-04-12 12:03:06',
                'updated_at' => '2026-04-12 12:03:06',
            ],
        ]);
    }
}