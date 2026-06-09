<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create post_types table
        Schema::create('post_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color', 20)->default('#22d3ee');
            $table->boolean('is_default')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // 2. Seed the four built-in types
        DB::table('post_types')->insert([
            ['name' => 'نقاش',           'slug' => 'post',          'color' => '#22d3ee', 'is_default' => true,  'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'إعلان',          'slug' => 'announcement',  'color' => '#f59e0b', 'is_default' => false, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'توثيق',          'slug' => 'documentation', 'color' => '#8b5cf6', 'is_default' => false, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'سجل تغييرات',   'slug' => 'changelog',     'color' => '#10b981', 'is_default' => false, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Change posts.type from ENUM to VARCHAR
        Schema::table('posts', function (Blueprint $table) {
            $table->string('type', 60)->default('post')->change();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->enum('type', ['post', 'announcement', 'documentation', 'changelog'])
                  ->default('post')->change();
        });

        Schema::dropIfExists('post_types');
    }
};
