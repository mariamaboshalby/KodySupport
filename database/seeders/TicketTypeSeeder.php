<?php

namespace Database\Seeders;

use App\Models\TicketType;
use Illuminate\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'دعم تقني',  'expected_cost' => 200,  'sort_order' => 1],
            ['name' => 'استشارة',   'expected_cost' => 150,  'sort_order' => 2],
            ['name' => 'تركيب',     'expected_cost' => 500,  'sort_order' => 3],
            ['name' => 'صيانة',     'expected_cost' => 300,  'sort_order' => 4],
            ['name' => 'تدريب',     'expected_cost' => 400,  'sort_order' => 5],
            ['name' => 'أخرى',      'expected_cost' => null, 'sort_order' => 6],
        ];

        foreach ($types as $type) {
            TicketType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
