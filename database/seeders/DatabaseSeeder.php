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
            'email'    => 'admin@admin.com',
            'password' => bcrypt('12345678'),
            'role'     => 'admin',
        ]);
    }
}
