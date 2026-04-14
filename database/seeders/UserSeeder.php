<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'           => 'FragMaster99',
                'email'          => 'frag@example.com',
                'password'       => Hash::make('password'),
                'avatar_url'     => null,
                'banner_url'     => null,
                'bio'            => 'Just here to frag and chill. Top 500 grinder.',
                'location'       => 'Kuala Lumpur, Malaysia',
                'steam_level'    => 42,
                'status'         => 'online',
                'status_game_id' => null,
                'last_online_at' => now(),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'name'           => 'PixelWitch',
                'email'          => 'pixel@example.com',
                'password'       => Hash::make('password'),
                'avatar_url'     => null,
                'banner_url'     => null,
                'bio'            => 'Indie game collector. I have played over 400 games and counting.',
                'location'       => 'Penang, Malaysia',
                'steam_level'    => 27,
                'status'         => 'away',
                'status_game_id' => null,
                'last_online_at' => now()->subHours(2),
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}