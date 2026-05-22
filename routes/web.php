<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncidentCategoryController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 */

Route::get('/', function () {
    return redirect()->route('opssight.dashboard');
});

/*
 * |--------------------------------------------------------------------------
 * | Authenticated Routes
 * |--------------------------------------------------------------------------
 */

Route::middleware(['auth'])->group(function () {
    /*
     * |--------------------------------------------------------------------------
     * | Dashboard
     * |--------------------------------------------------------------------------
     */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/opssight/dashboard', [DashboardController::class, 'index'])
        ->name('opssight.dashboard');

    /*
    * |--------------------------------------------------------------------------
    * | Incidents
    * |--------------------------------------------------------------------------
    */

    Route::get('/incidents', [IncidentController::class, 'index'])
        ->name('incidents.index');

    Route::get('/incidents/create', [IncidentController::class, 'create'])
        ->name('incidents.create');

    Route::post('/incidents', [IncidentController::class, 'store'])
        ->name('incidents.store');

    Route::get('/incidents/{id}', [IncidentController::class, 'show'])
        ->name('incidents.show');

    Route::get('/incidents/{id}/edit', [IncidentController::class, 'edit'])
        ->name('incidents.edit');

    Route::put('/incidents/{id}', [IncidentController::class, 'update'])
        ->name('incidents.update');

    Route::delete('/incidents/{id}', [IncidentController::class, 'destroy'])
        ->name('incidents.destroy');

    /*
     * |--------------------------------------------------------------------------
     * | Audit Logs
     * |--------------------------------------------------------------------------
     */

    Route::get('/audit-logs', [AuditLogController::class, 'index'])
        ->name('audit-logs.index');

    Route::get('/audit-logs/{id}/details', [AuditLogController::class, 'details'])
        ->name('audit-logs.details');

    /*
     * |--------------------------------------------------------------------------
     * | Users
     * |--------------------------------------------------------------------------
     */

    Route::get('/users', [UserManagementController::class, 'index'])
        ->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])
        ->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])
        ->name('users.store');
    Route::get('/users/{id}', [UserManagementController::class, 'show'])
        ->name('users.show');
    Route::get('/users/{id}/edit', [UserManagementController::class, 'edit'])
        ->name('users.edit');
    Route::put('/users/{id}', [UserManagementController::class, 'update'])
        ->name('users.update');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])
        ->name('users.destroy');

    /*
     * |--------------------------------------------------------------------------
     * | Categories
     * |--------------------------------------------------------------------------
     */

    Route::get('/categories', [IncidentCategoryController::class, 'index'])
        ->name('categories.index');

    Route::get('/categories/create', [IncidentCategoryController::class, 'create'])
        ->name('categories.create');

    Route::post('/categories', [IncidentCategoryController::class, 'store'])
        ->name('categories.store');

    Route::get('/categories/{id}/edit', [IncidentCategoryController::class, 'edit'])
        ->name('categories.edit');

    Route::put('/categories/{id}', [IncidentCategoryController::class, 'update'])
        ->name('categories.update');

    Route::delete('/categories/{id}', [IncidentCategoryController::class, 'destroy'])
        ->name('categories.destroy');

    /*
     * |--------------------------------------------------------------------------
     * | Profile
     * |--------------------------------------------------------------------------
     */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
 * |--------------------------------------------------------------------------
 * | Authentication Routes
 * |--------------------------------------------------------------------------
 */

require __DIR__.'/auth.php';
