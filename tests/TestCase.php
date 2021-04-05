<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;


    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function actingAsAdmin(): \Illuminate\Database\Eloquent\Model
    {
        $user = create(User::class, [
            'email' => 'admin@example.com',
            'password' => Hash::make('secret'),
            'role' => User::ROLE_ADMIN
        ]);

        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', "Bearer {$token}");

        return $user;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function actingAsModerator(): \Illuminate\Database\Eloquent\Model
    {
        $user = create(User::class, [
            'email' => 'moderator@example.com',
            'password' => Hash::make('secret'),
            'role' => User::ROLE_MODERATOR,
        ]);

        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', "Bearer {$token}");

        return $user;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function actingAsViewer(): \Illuminate\Database\Eloquent\Model
    {
        $user = create(User::class, [
            'email' => 'viewer@example.com',
            'password' => Hash::make('secret'),
            'role' => User::ROLE_VIEWER,
        ]);

        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', "Bearer {$token}");

        return $user;
    }
}
