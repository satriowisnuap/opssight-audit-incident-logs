<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\IncidentCategoryController;

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

    Route::view('/audit-logs', 'pages.opssight.dashboard')
        ->name('audit-logs.index');

    /*
     * |--------------------------------------------------------------------------
     * | Users
     * |--------------------------------------------------------------------------
     */

    Route::view('/users', 'pages.opssight.dashboard')
        ->name('users.index');

    /*
     * |--------------------------------------------------------------------------
     * | Categories
     * |--------------------------------------------------------------------------
     */

    Route::view('/categories', 'pages.opssight.dashboard')
        ->name('categories.index');

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

require __DIR__ . '/auth.php';
