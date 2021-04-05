<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'email' => 'admin@examle.com',
            'role' => User::ROLE_ADMIN,
        ]);

        User::factory()->create([
            'email' => 'moderator@examle.com',
            'role' => User::ROLE_MODERATOR,
        ]);

        User::factory()->create([
            'email' => 'viewer@examle.com',
            'role' => User::ROLE_VIEWER,
        ]);
    }
}
