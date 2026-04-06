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
            $media = $gameData['media'];
            unset($gameData['media']);

            $gameData['created_at'] = $now;
            $gameData['updated_at'] = $now;

            $gameId = DB::table('games')->insertGetId($gameData);

            $mediaRows = array_map(function ($item) use ($gameId, $now) {
                return [
                    'game_id' => $gameId,
                    'type' => $item['type'],
                    'url' => $item['url'],
                    'thumbnail_url' => $item['thumbnail_url'],
                    'sort_order' => $item['sort_order'],
                    'is_cover' => $item['is_cover'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }, $media);

            DB::table('game_media')->insert($mediaRows);
        }
    }
}
