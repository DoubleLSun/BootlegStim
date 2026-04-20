<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\GameGenre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchFilterTest extends TestCase
{
    use RefreshDatabase;

    private function makeGame(string $title): Game
    {
        return Game::create([
            'title' => $title,
            'description' => $title . ' description',
            'price' => 39.99,
            'release_date' => now()->toDateString(),
            'is_featured' => false,
            'created_by' => 1,
            'developer_id' => 1,
            'publisher_id' => 1,
            'cover_image' => null,
        ]);
    }

    public function test_search_page_can_filter_by_keyword_and_genre(): void
    {
        $rpg = GameGenre::create([
            'name' => 'RPG',
            'slug' => 'rpg',
            'description' => 'Role-playing games',
            'display_flag' => true,
        ]);

        $action = GameGenre::create([
            'name' => 'Action',
            'slug' => 'action',
            'description' => 'Action games',
            'display_flag' => true,
        ]);

        $witcher = $this->makeGame('The Witcher 3');
        $doom = $this->makeGame('DOOM Eternal');

        $witcher->genres()->attach([$rpg->id, $action->id]);
        $doom->genres()->attach([$action->id]);

        $response = $this->get(route('search.index', [
            'q' => 'witch',
            'genres' => [$rpg->id],
        ]));

        $response->assertOk();
        $response->assertSee('The Witcher 3');
        $response->assertDontSee('DOOM Eternal');
    }

    public function test_search_preview_returns_up_to_three_matches(): void
    {
        $this->makeGame('Star One');
        $this->makeGame('Star Two');
        $this->makeGame('Star Three');
        $this->makeGame('Star Four');

        $response = $this->get(route('search.preview', ['q' => 'star']));

        $response->assertOk();
        $response->assertJsonCount(3, 'results');
    }
}
