<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncidentCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('incident_categories')->insert([
            [
                'name' => 'Network',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Server',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Application',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Security',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Database',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
