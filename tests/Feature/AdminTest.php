<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\GameGenre;
use App\Models\GamePricing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->role = 'admin';
        $user->save();

        return $user;
    }

    private function game(array $overrides = []): Game
    {
        return Game::create(array_merge([
            'title' => 'Admin Game ' . uniqid(),
            'description' => 'Admin game description',
            'price' => 49.99,
            'release_date' => now()->toDateString(),
            'is_featured' => false,
            'created_by' => 1,
            'developer_id' => 1,
            'publisher_id' => 1,
            'cover_image' => null,
        ], $overrides));
    }

    public function test_page(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get(route('admin.manage'));

        $response->assertOk();
        $response->assertSee('Admin Content Control');
    }

    public function test_create_game(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('admin.games.create'), [
            'title' => 'Game One',
            'description' => 'Desc',
            'price' => 29.99,
            'release_date' => now()->toDateString(),
            'developer_id' => 1,
            'publisher_id' => 1,
            'cover_image' => 'https://example.com/cover.jpg',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('games', ['title' => 'Game One']);
    }

    public function test_game_required(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->from(route('admin.manage'))->post(route('admin.games.create'), [
            'description' => 'No title payload',
            'price' => 19.99,
            'release_date' => now()->toDateString(),
            'developer_id' => 1,
            'publisher_id' => 1,
        ]);

        $response->assertRedirect(route('admin.manage'));
        $response->assertSessionHasErrors(['title']);
    }

    public function test_create_genre(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('admin.genres.create'), [
            'name' => 'Action',
            'slug' => 'action',
            'description' => 'Action genre',
            'display_flag' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('game_genres', ['name' => 'Action', 'slug' => 'action']);
    }

    public function test_genre_required(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->from(route('admin.manage'))->post(route('admin.genres.create'), [
            'name' => 'Missing Slug',
            'description' => 'Missing slug should fail',
        ]);

        $response->assertRedirect(route('admin.manage'));
        $response->assertSessionHasErrors(['slug']);
    }

    public function test_create_pricing(): void
    {
        $admin = $this->admin();
        $game = $this->game(['created_by' => $admin->id]);

        $response = $this->actingAs($admin)->post(route('admin.pricings.create', $game), [
            'price' => 59.99,
            'discount_percentage' => 20,
            'discounted_price' => 47.99,
            'currency' => 'USD',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('game_pricings', [
            'game_id' => $game->id,
            'price' => 59.99,
            'discount_percentage' => 20.00,
            'discounted_price' => 47.99,
            'currency' => 'USD',
        ]);
    }

    public function test_delete_genre(): void
    {
        $admin = $this->admin();
        $genre = GameGenre::create([
            'name' => 'Puzzle',
            'slug' => 'puzzle',
            'description' => 'Puzzle genre',
            'display_flag' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.genres.delete', $genre));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('game_genres', ['id' => $genre->id]);
    }
}
