<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            IncidentCategorySeeder::class,
            IncidentSeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}
