<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('audit_logs')->insert([

            /**
             * INCIDENT LOGS
             */
            [
                'user_id' => 1,
                'action' => 'CREATE_INCIDENT',
                'table_name' => 'incidents',
                'record_id' => 1,

                'old_values' => null,

                'new_values' => json_encode([
                    'title' => 'Primary Database Connection Failure',
                    'status' => 'OPEN',
                    'severity' => 'CRITICAL',
                ]),

                'ip_address' => '127.0.0.1',

                'created_at' => now()->subHours(12),
            ],

            [
                'user_id' => 1,
                'action' => 'ASSIGN_INCIDENT',
                'table_name' => 'incidents',
                'record_id' => 1,

                'old_values' => json_encode([
                    'assigned_to' => null,
                ]),

                'new_values' => json_encode([
                    'assigned_to' => 2,
                ]),

                'ip_address' => '127.0.0.1',

                'created_at' => now()->subHours(11),
            ],

            [
                'user_id' => 2,
                'action' => 'UPDATE_INCIDENT',
                'table_name' => 'incidents',
                'record_id' => 2,

                'old_values' => json_encode([
                    'status' => 'OPEN',
                ]),

                'new_values' => json_encode([
                    'status' => 'IN_PROGRESS',
                ]),

                'ip_address' => '127.0.0.1',

                'created_at' => now()->subHours(10),
            ],

            [
                'user_id' => 3,
                'action' => 'RESOLVE_INCIDENT',
                'table_name' => 'incidents',
                'record_id' => 4,

                'old_values' => json_encode([
                    'status' => 'IN_PROGRESS',
                ]),

                'new_values' => json_encode([
                    'status' => 'RESOLVED',
                ]),

                'ip_address' => '127.0.0.1',

                'created_at' => now()->subHours(9),
            ],

            [
                'user_id' => 1,
                'action' => 'CLOSE_INCIDENT',
                'table_name' => 'incidents',
                'record_id' => 5,

                'old_values' => json_encode([
                    'status' => 'RESOLVED',
                ]),

                'new_values' => json_encode([
                    'status' => 'CLOSED',
                ]),

                'ip_address' => '127.0.0.1',

                'created_at' => now()->subHours(8),
            ],

            [
                'user_id' => 2,
                'action' => 'UPDATE_INCIDENT',
                'table_name' => 'incidents',
                'record_id' => 8,

                'old_values' => json_encode([
                    'severity' => 'MEDIUM',
                ]),

                'new_values' => json_encode([
                    'severity' => 'HIGH',
                ]),

                'ip_address' => '127.0.0.1',

                'created_at' => now()->subHours(7),
            ],

            /**
             * CATEGORY LOGS
             */
            [
                'user_id' => 1,
                'action' => 'CREATE_CATEGORY',
                'table_name' => 'incident_categories',
                'record_id' => 1,

                'old_values' => null,

                'new_values' => json_encode([
                    'name' => 'Network',
                ]),

                'ip_address' => '127.0.0.1',

                'created_at' => now()->subDays(2),
            ],

            [
                'user_id' => 1,
                'action' => 'UPDATE_CATEGORY',
                'table_name' => 'incident_categories',
                'record_id' => 3,

                'old_values' => json_encode([
                    'name' => 'Application',
                ]),

                'new_values' => json_encode([
                    'name' => 'Applications',
                ]),

                'ip_address' => '127.0.0.1',

                'created_at' => now()->subDays(1),
            ],

            /**
             * USER LOGS
             */
            [
                'user_id' => 1,
                'action' => 'CREATE_USER',
                'table_name' => 'users',
                'record_id' => 2,

                'old_values' => null,

                'new_values' => json_encode([
                    'name' => 'Operator 1',
                    'role' => 'OPERATOR',
                ]),

                'ip_address' => '127.0.0.1',

                'created_at' => now()->subDays(5),
            ],

            [
                'user_id' => 1,
                'action' => 'UPDATE_USER',
                'table_name' => 'users',
                'record_id' => 3,

                'old_values' => json_encode([
                    'role' => 'OPERATOR',
                ]),

                'new_values' => json_encode([
                    'role' => 'ADMIN',
                ]),

                'ip_address' => '127.0.0.1',

                'created_at' => now()->subDays(3),
            ],

        ]);
    }
}
