<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        $games = [
            [
                'title' => 'The Witcher 3: Wild Hunt',
                'description' => 'An open-world action RPG following Geralt of Rivia on a monster-hunting journey.',
                'price' => 39.99,
                'release_date' => '2015-05-19',
                'is_featured' => true,
                'created_by' => 1,
                'developer_id' => 1,
                'publisher_id' => 1,
                'cover_image' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/292030/header.jpg',
                'media' => [
                    [
                        'type' => 'image',
                        'url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/292030/header.jpg',
                        'thumbnail_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/292030/capsule_231x87.jpg',
                        'sort_order' => 1,
                        'is_cover' => true,
                    ],
                    [
                        'type' => 'image',
                        'url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/292030/capsule_616x353.jpg',
                        'thumbnail_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/292030/capsule_231x87.jpg',
                        'sort_order' => 2,
                        'is_cover' => false,
                    ],
                    [
                        'type' => 'image',
                        'url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/292030/library_hero.jpg',
                        'thumbnail_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/292030/capsule_184x69.jpg',
                        'sort_order' => 3,
                        'is_cover' => false,
                    ],
                ],
            ],
            [
                'title' => 'Cyberpunk 2077',
                'description' => 'A narrative-driven open-world RPG set in Night City with deep character customization.',
                'price' => 59.99,
                'release_date' => '2020-12-10',
                'is_featured' => true,
                'created_by' => 1,
                'developer_id' => 2,
                'publisher_id' => 2,
                'cover_image' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1091500/header.jpg',
                'media' => [
                    [
                        'type' => 'image',
                        'url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1091500/header.jpg',
                        'thumbnail_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1091500/capsule_231x87.jpg',
                        'sort_order' => 1,
                        'is_cover' => true,
                    ],
                    [
                        'type' => 'image',
                        'url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1091500/capsule_616x353.jpg',
                        'thumbnail_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1091500/capsule_231x87.jpg',
                        'sort_order' => 2,
                        'is_cover' => false,
                    ],
                    [
                        'type' => 'image',
                        'url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1091500/library_hero.jpg',
                        'thumbnail_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1091500/capsule_184x69.jpg',
                        'sort_order' => 3,
                        'is_cover' => false,
                    ],
                ],
            ],
            [
                'title' => 'Hades',
                'description' => 'A fast-paced rogue-like dungeon crawler inspired by Greek mythology.',
                'price' => 24.99,
                'release_date' => '2020-09-17',
                'is_featured' => true,
                'created_by' => 1,
                'developer_id' => 3,
                'publisher_id' => 3,
                'cover_image' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1145360/header.jpg',
                'media' => [
                    [
                        'type' => 'image',
                        'url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1145360/header.jpg',
                        'thumbnail_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1145360/capsule_231x87.jpg',
                        'sort_order' => 1,
                        'is_cover' => true,
                    ],
                    [
                        'type' => 'image',
                        'url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1145360/capsule_616x353.jpg',
                        'thumbnail_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1145360/capsule_231x87.jpg',
                        'sort_order' => 2,
                        'is_cover' => false,
                    ],
                    [
                        'type' => 'image',
                        'url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1145360/library_hero.jpg',
                        'thumbnail_url' => 'https://cdn.cloudflare.steamstatic.com/steam/apps/1145360/capsule_184x69.jpg',
                        'sort_order' => 3,
                        'is_cover' => false,
                    ],
                ],
            ],
        ];

        foreach ($games as $gameData) {
            // Extract media data and remove it from the game data array to avoid issues when inserting into the games table
            $media = $gameData['media'];
            unset($gameData['media']);

            $gameData['created_at'] = $now;
            $gameData['updated_at'] = $now;


            // Check if a game with the same title already exists in the database, if so skip to avoid duplicate entries when running seeder multiple times
            $gameId = DB::table('games')->updateOrInsert(
                ['title' => $gameData['title']],$gameData
            );

            $gameId = DB::table('games')
            ->where('title', $gameData['title'])
            ->value('id');
        
            /** 
             * Media entries duplicate check: check if the media entries for the same game
             * with the same type, url, thumbnail_url, sort_order, and is_cover 
             * already exist in the database, if so skip to avoid duplicate entries when
             * running seeder multiple times
             * */ 
            foreach ($media as $item) {
                DB::table('game_media')->updateOrInsert(
                    [
                        'game_id' => $gameId,
                        'type' => $item['type'],
                        'url' => $item['url'],
                    ],
                    [
                        'thumbnail_url' => $item['thumbnail_url'],
                        'sort_order' => $item['sort_order'],
                        'is_cover' => $item['is_cover'],
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );
            }
        }
    }
}
