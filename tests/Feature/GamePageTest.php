<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\GamePricing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_add_game_to_cart_from_game_page(): void
    {
        $user = User::factory()->create();

        $game = Game::create([
            'title' => 'Test Game',
            'description' => 'A test game description',
            'price' => 59.99,
            'release_date' => now()->toDateString(),
            'is_featured' => false,
            'created_by' => 1,
            'developer_id' => 1,
            'publisher_id' => 1,
            'cover_image' => null,
        ]);

        $pricing = GamePricing::create([
            'game_id' => $game->id,
            'price' => 59.99,
            'discount_percentage' => null,
            'discounted_price' => null,
            'currency' => 'USD',
        ]);

        $response = $this->actingAs($user)->post(route('games.cart.add', $game), [
            'pricing_id' => $pricing->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('user_carts', [
            'user_id' => $user->id,
            'game_id' => $game->id,
            'game_pricing_id' => $pricing->id,
            'price' => 59.99,
        ]);
    }

    public function test_authenticated_user_can_leave_comment_on_game_page(): void
    {
        $user = User::factory()->create();

        $game = Game::create([
            'title' => 'Comment Test Game',
            'description' => 'A game for comment tests',
            'price' => 29.99,
            'release_date' => now()->toDateString(),
            'is_featured' => false,
            'created_by' => 1,
            'developer_id' => 1,
            'publisher_id' => 1,
            'cover_image' => null,
        ]);

        $response = $this->actingAs($user)->post(route('games.comments.store', $game), [
            'review_content' => 'Solid game and fun loop.',
            'is_recommended' => 1,
            'rating' => 5,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('game_reviews', [
            'user_id' => $user->id,
            'game_id' => $game->id,
            'review_content' => 'Solid game and fun loop.',
            'is_recommended' => 1,
        ]);
    }
}