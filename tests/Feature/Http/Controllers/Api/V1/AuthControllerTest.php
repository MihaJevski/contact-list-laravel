<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * @group api_controller
 */
class AuthControllerTest extends TestCase
{
    /** @test */
    public function it_logins_user()
    {
        $user = create(User::class, [
            'email' => 'admin@examle.com',
            'password' => Hash::make('admin'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'admin@examle.com',
            'password' => 'admin'
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'username', 'role', 'auth_key'
            ])
            ->assertJsonFragment([
                'username' => $user->name,
                'role' => $user->role,
            ]);
    }

    /** @test */
    public function it_does_not_login_user_with_wrong_credentials()
    {
        create(User::class, [
            'email' => 'admin@examle.com',
            'password' => Hash::make('admin'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'other@email.com',
            'password' => 'wrong-pass'
        ]);
        $response
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "password" => "Invalid login or password"
                ]
            ]);
    }

    /** @test */
    public function it_logout_user()
    {
        $this->actingAsAdmin();

        $response = $this->postJson('/api/v1/logout');

        $response
            ->assertOk()
            ->assertExactJson([
                'message' => 'Successfully logged out'
            ]);
    }

    /** @test */
    public function it_returns_logged_user()
    {
        $user = $this->actingAsAdmin();

        $response = $this->getJson('/api/v1/me');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'username', 'role', 'auth_key'
            ])
            ->assertJsonFragment([
                'username' => $user->name,
                'role' => $user->role,
            ]);
    }

    /** @test */
    public function it_does_not_return_logged_user_for_unauthenticated()
    {
        $response = $this->getJson('/api/v1/me');

        $response
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }
}
