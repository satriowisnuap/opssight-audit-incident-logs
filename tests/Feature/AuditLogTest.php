<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

test('guests are redirected to login', function () {
    $response = $this->get('/audit-logs/1/details');
    $response->assertRedirect('/login');
});

test('returns 404 for non-existent log', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get('/audit-logs/999/details');
    $response->assertStatus(404);
    $response->assertJson(['error' => 'Log not found']);
});

test('returns existing incident details', function () {
    $user = User::factory()->create();

    // Create an incident category
    $categoryId = DB::table('incident_categories')->insertGetId([
        'name' => 'Server Issue',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Create an incident
    $incidentId = DB::table('incidents')->insertGetId([
        'title' => 'Database crash',
        'description' => 'The production DB crashed unexpectedly.',
        'severity' => 'CRITICAL',
        'status' => 'OPEN',
        'category_id' => $categoryId,
        'reported_by' => $user->id,
        'incident_date' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Create audit log
    $logId = DB::table('audit_logs')->insertGetId([
        'user_id' => $user->id,
        'action' => 'CREATE_INCIDENT',
        'table_name' => 'incidents',
        'record_id' => $incidentId,
        'created_at' => now(),
    ]);

    $response = $this->actingAs($user)->get("/audit-logs/{$logId}/details");

    $response->assertOk();
    $response->assertJson([
        'table_name' => 'incidents',
        'record_id' => $incidentId,
        'exists' => true,
        'data' => [
            'title' => 'Database crash',
            'severity' => 'CRITICAL',
            'status' => 'OPEN',
        ],
    ]);
});

test('returns deleted incident historical details using old/new values', function () {
    $user = User::factory()->create();

    // Create audit log with snapshot of deleted incident
    $oldValues = [
        'title' => 'Disk Space Alert',
        'description' => 'Disk is 99% full.',
        'severity' => 'HIGH',
        'status' => 'OPEN',
    ];

    $logId = DB::table('audit_logs')->insertGetId([
        'user_id' => $user->id,
        'action' => 'DELETE_INCIDENT',
        'table_name' => 'incidents',
        'record_id' => 12345, // Doesn't exist in incidents table
        'old_values' => json_encode($oldValues),
        'created_at' => now(),
    ]);

    $response = $this->actingAs($user)->get("/audit-logs/{$logId}/details");

    $response->assertOk();
    $response->assertJson([
        'table_name' => 'incidents',
        'record_id' => 12345,
        'exists' => false,
        'data' => [
            'id' => 12345,
            'title' => 'Disk Space Alert',
            'description' => 'Disk is 99% full.',
            'severity' => 'HIGH',
            'status' => 'OPEN',
            'is_deleted' => true,
            'old_values' => $oldValues,
        ],
    ]);
});

test('returns existing category details', function () {
    $user = User::factory()->create();

    $categoryId = DB::table('incident_categories')->insertGetId([
        'name' => 'Network Outage',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $logId = DB::table('audit_logs')->insertGetId([
        'user_id' => $user->id,
        'action' => 'CREATE_CATEGORY',
        'table_name' => 'incident_categories',
        'record_id' => $categoryId,
        'created_at' => now(),
    ]);

    $response = $this->actingAs($user)->get("/audit-logs/{$logId}/details");

    $response->assertOk();
    $response->assertJson([
        'table_name' => 'incident_categories',
        'record_id' => $categoryId,
        'exists' => true,
        'data' => [
            'name' => 'Network Outage',
        ],
    ]);
});

test('returns deleted category historical details using snapshot', function () {
    $user = User::factory()->create();

    $oldValues = [
        'name' => 'Legacy Service Outage',
    ];

    $logId = DB::table('audit_logs')->insertGetId([
        'user_id' => $user->id,
        'action' => 'DELETE_CATEGORY',
        'table_name' => 'incident_categories',
        'record_id' => 9999, // Doesn't exist in incident_categories
        'old_values' => json_encode($oldValues),
        'created_at' => now(),
    ]);

    $response = $this->actingAs($user)->get("/audit-logs/{$logId}/details");

    $response->assertOk();
    $response->assertJson([
        'table_name' => 'incident_categories',
        'record_id' => 9999,
        'exists' => false,
        'data' => [
            'id' => 9999,
            'name' => 'Legacy Service Outage',
            'is_deleted' => true,
            'old_values' => $oldValues,
        ],
    ]);
});
