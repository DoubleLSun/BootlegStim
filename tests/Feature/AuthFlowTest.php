<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_is_accessible(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }

    public function test_user_can_register_with_valid_data(): void
    {
        $payload = [
            'name' => 'Test Player',
            'email' => 'player@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register.post'), $payload);

        $user = User::where('email', $payload['email'])->first();

        $this->assertNotNull($user);
        $this->assertTrue(Hash::check($payload['password'], $user->password));
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('profile.show', $user));
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        // create a user with 
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('profile.show', $user));
    }

    public function test_authenticated_user_can_open_edit_profile_from_profile_page_link(): void
    {
        $user = User::factory()->create();

        $profileResponse = $this->actingAs($user)->get(route('profile.show', $user));
        $profileResponse->assertOk();
        $profileResponse->assertSee(route('profile.edit', $user), false);

        $editResponse = $this->get(route('profile.edit', $user));
        $editResponse->assertOk();
        $editResponse->assertSee('Editing Profile');
    }

    public function test_user_can_access_edit_profile_page_using_logged_in_session_id(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $loginResponse = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $loginResponse->assertRedirect(route('profile.show', $user));

        $sessionId = session()->getId();
        $sessionData = session()->all();

        $this->assertNotEmpty($sessionId);

        $response = $this
            ->withCookie(config('session.cookie'), $sessionId)
            ->withSession($sessionData)
            ->get(route('profile.edit', $user));

        $response->assertOk();
        $response->assertSee('Editing Profile');
    }

    public function test_authenticated_user_can_update_profile_from_edit_route(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'bio' => 'Old bio',
            'location' => 'Old Location',
            'status' => 'offline',
        ]);

        $payload = [
            'name' => 'Updated Name',
            'bio' => 'Updated bio content',
            'location' => 'Updated Location',
            'avatar_url' => 'https://example.com/avatar.jpg',
            'banner_url' => 'https://example.com/banner.jpg',
            'status' => 'online',
            'status_game_id' => null,
        ];

        $response = $this->actingAs($user)->post(route('profile.update', $user), $payload);

        $response->assertRedirect(route('profile.show', $user));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'bio' => 'Updated bio content',
            'location' => 'Updated Location',
            'avatar_url' => 'https://example.com/avatar.jpg',
            'banner_url' => 'https://example.com/banner.jpg',
            'status' => 'online',
        ]);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->from(route('login'))->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_from_profile_route(): void
    {
        $response = $this->get(route('profile'));

        $response->assertRedirect(route('login'));
    }
    
}
