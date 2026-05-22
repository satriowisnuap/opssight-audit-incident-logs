<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncidentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('incidents')->insert([
            [
                'title' => 'Primary Database Connection Failure',
                'description' => 'Database server unreachable from application.',
                'severity' => 'CRITICAL',
                'status' => 'OPEN',
                'category_id' => 5,
                'assigned_to' => 2,
                'reported_by' => 1,
                'incident_date' => now(),
                'resolved_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'API Response Delay',
                'description' => 'Slow response detected on production API.',
                'severity' => 'HIGH',
                'status' => 'IN_PROGRESS',
                'category_id' => 3,
                'assigned_to' => 3,
                'reported_by' => 1,
                'incident_date' => now()->subHours(3),
                'resolved_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Network Packet Loss',
                'description' => 'Packet loss detected in internal network.',
                'severity' => 'MEDIUM',
                'status' => 'OPEN',
                'category_id' => 1,
                'assigned_to' => 2,
                'reported_by' => 1,
                'incident_date' => now()->subHours(5),
                'resolved_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Unauthorized Login Attempt',
                'description' => 'Multiple failed login attempts detected.',
                'severity' => 'HIGH',
                'status' => 'RESOLVED',
                'category_id' => 4,
                'assigned_to' => 3,
                'reported_by' => 1,
                'incident_date' => now()->subDay(),
                'resolved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Server CPU Spike',
                'description' => 'CPU usage exceeded threshold.',
                'severity' => 'LOW',
                'status' => 'CLOSED',
                'category_id' => 2,
                'assigned_to' => 2,
                'reported_by' => 1,
                'incident_date' => now()->subDays(2),
                'resolved_at' => now()->subDay(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
