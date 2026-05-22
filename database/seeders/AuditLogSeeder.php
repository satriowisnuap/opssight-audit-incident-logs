<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('audit_logs')->insert([
            [
                'user_id' => 1,
                'action' => 'CREATE',
                'table_name' => 'incidents',
                'record_id' => 1,
                'old_values' => null,
                'new_values' => json_encode([
                    'title' => 'Primary Database Connection Failure',
                ]),
                'ip_address' => '127.0.0.1',
                'created_at' => now(),
            ],
            [
                'user_id' => 2,
                'action' => 'UPDATE',
                'table_name' => 'incidents',
                'record_id' => 2,
                'old_values' => json_encode([
                    'status' => 'OPEN',
                ]),
                'new_values' => json_encode([
                    'status' => 'IN_PROGRESS',
                ]),
                'ip_address' => '127.0.0.1',
                'created_at' => now(),
            ],
            [
                'user_id' => 1,
                'action' => 'DELETE',
                'table_name' => 'incidents',
                'record_id' => 5,
                'old_values' => json_encode([
                    'title' => 'Server CPU Spike',
                ]),
                'new_values' => null,
                'ip_address' => '127.0.0.1',
                'created_at' => now(),
            ],
        ]);
    }
}
