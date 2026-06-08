<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin user ────────────────────────────────────────────────────────
        User::factory()->create([
            'name'     => 'Admin',
            'username' => 'admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);
    }
}
