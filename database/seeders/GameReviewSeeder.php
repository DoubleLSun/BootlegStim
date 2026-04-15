<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GameReviewSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        // Reviewer template users to guarantee stable user_id values for review seeds.
        $reviewerTemplates = [
            ['name' => 'Iris Walker', 'email' => 'iris.walker@example.com'],
            ['name' => 'Kai Rhee', 'email' => 'kai.rhee@example.com'],
            ['name' => 'Maya Torres', 'email' => 'maya.torres@example.com'],
            ['name' => 'Noah Bennett', 'email' => 'noah.bennett@example.com'],
            ['name' => 'Sana Aziz', 'email' => 'sana.aziz@example.com'],
        ];

        foreach ($reviewerTemplates as $reviewer) {
            DB::table('users')->updateOrInsert(
                ['email' => $reviewer['email']],
                [
                    'name' => $reviewer['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => $now,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $gamesByTitle = DB::table('games')->pluck('id', 'title');
        if ($gamesByTitle->isEmpty()) {
            return;
        }

        $usersByEmail = DB::table('users')->pluck('id', 'email');

        // Dummy data template for GameReview model, grouped by game title.
        $reviewTemplate = [
            'The Witcher 3: Wild Hunt' => [
                [
                    'email' => 'iris.walker@example.com',
                    'is_recommended' => true,
                    'rating' => 10,
                    'review_content' => 'Massive world, strong writing, and side quests that feel like full stories.',
                    'hours_played' => 124,
                    'helpful_votes' => 56,
                    'review_date' => '2026-04-03 11:20:00',
                ],
                [
                    'email' => 'kai.rhee@example.com',
                    'is_recommended' => true,
                    'rating' => 9,
                    'review_content' => 'Combat takes time to click, but once it does it is very rewarding.',
                    'hours_played' => 87,
                    'helpful_votes' => 24,
                    'review_date' => '2026-04-05 20:45:00',
                ],
            ],
            'Cyberpunk 2077' => [
                [
                    'email' => 'maya.torres@example.com',
                    'is_recommended' => true,
                    'rating' => 8,
                    'review_content' => 'Great atmosphere and story arcs. Night City feels alive after updates.',
                    'hours_played' => 71,
                    'helpful_votes' => 18,
                    'review_date' => '2026-04-06 09:10:00',
                ],
                [
                    'email' => 'noah.bennett@example.com',
                    'is_recommended' => false,
                    'rating' => 6,
                    'review_content' => 'Main missions are excellent but some systems still feel rough around the edges.',
                    'hours_played' => 43,
                    'helpful_votes' => 9,
                    'review_date' => '2026-04-08 22:30:00',
                ],
            ],
            'Hades' => [
                [
                    'email' => 'sana.aziz@example.com',
                    'is_recommended' => true,
                    'rating' => 10,
                    'review_content' => 'Fast runs, polished combat, and constant progression hooks.',
                    'hours_played' => 96,
                    'helpful_votes' => 32,
                    'review_date' => '2026-04-09 13:05:00',
                ],
            ],
        ];

        $rows = [];
        foreach ($reviewTemplate as $gameTitle => $reviews) {
            $gameId = $gamesByTitle->get($gameTitle);
            if (! $gameId) {
                continue;
            }

            foreach ($reviews as $review) {
                //check if new seeder hours_played equals the hours_played of same user and same game in the database, if so skip to avoid duplicate reviews when running seeder multiple times
                
                
                // 
                $existingReview = DB::table('game_reviews')->where([
                    'game_id' => $gameId,
                    'user_id' => $usersByEmail->get($review['email']),
                    'hours_played' => $review['hours_played'],
                ])->first();

                if ($existingReview) {
                    continue;
                }

                $userId = $usersByEmail->get($review['email']);
                if (! $userId) {
                    continue;
                }

                $rows[] = [
                    'game_id' => $gameId,
                    'user_id' => $userId,
                    'is_recommended' => $review['is_recommended'],
                    'rating' => $review['rating'],
                    'review_content' => $review['review_content'],
                    'hours_played' => $review['hours_played'],
                    'helpful_votes' => $review['helpful_votes'],
                    'review_date' => $review['review_date'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (! empty($rows)) {
            DB::table('game_reviews')->upsert(
                $rows,
                ['game_id', 'user_id'],
                ['is_recommended', 'rating', 'review_content', 'hours_played', 'helpful_votes', 'review_date', 'updated_at']
            );
        }
    }
}
