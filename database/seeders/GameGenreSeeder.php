<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameGenre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GameGenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = [
            ['name' => 'Action', 'description' => 'Fast-paced combat and reflex-driven gameplay.'],
            ['name' => 'RPG', 'description' => 'Character progression, quests, and narrative-driven systems.'],
            ['name' => 'Adventure', 'description' => 'Exploration-focused experiences with story and discovery.'],
            ['name' => 'Open World', 'description' => 'Large free-roam environments with player-driven objectives.'],
            ['name' => 'Shooter', 'description' => 'First or third person gunplay as a core mechanic.'],
            ['name' => 'Survival', 'description' => 'Resource management and hostile environments.'],
            ['name' => 'Rogue-like', 'description' => 'Run-based structure, procedural content, and permadeath influence.'],
            ['name' => 'Indie', 'description' => 'Independently developed games with unique design direction.'],
            ['name' => 'Strategy', 'description' => 'Tactical decision-making and planning-intensive gameplay.'],
            ['name' => 'Simulation', 'description' => 'System-based gameplay emulating real or fictional processes.'],
            ['name' => 'Multiplayer', 'description' => 'Online or local shared gameplay with other players.'],
            ['name' => 'Story Rich', 'description' => 'Narrative-first experiences with strong writing and characters.'],
        ];

        $genreIdsByName = [];
        foreach ($genres as $genre) {
            $model = GameGenre::updateOrCreate(
                ['slug' => Str::slug($genre['name'])],
                [
                    'name' => $genre['name'],
                    'description' => $genre['description'],
                    'display_flag' => true,
                ]
            );
            $genreIdsByName[$genre['name']] = $model->id;
        }

        $mapping = [
            'The Witcher 3: Wild Hunt' => ['RPG', 'Open World', 'Adventure', 'Story Rich', 'Action'],
            'Cyberpunk 2077' => ['RPG', 'Open World', 'Action', 'Shooter', 'Story Rich'],
            'Hades' => ['Rogue-like', 'Action', 'Indie'],
        ];

        foreach ($mapping as $gameTitle => $genreNames) {
            $game = Game::query()->where('title', $gameTitle)->first();
            if (!$game) {
                continue;
            }

            $genreIds = collect($genreNames)
                ->map(fn($name) => $genreIdsByName[$name] ?? null)
                ->filter()
                ->values()
                ->all();

            if (!empty($genreIds)) {
                $game->genres()->syncWithoutDetaching($genreIds);
            }
        }
    }
}
