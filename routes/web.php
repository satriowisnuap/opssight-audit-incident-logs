<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
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

    Route::view('/incidents', 'pages.opssight.dashboard')
        ->name('incidents.index');

    Route::view('/incidents/create', 'pages.opssight.dashboard')
        ->name('incidents.create');

    Route::view('/incidents/{id}', 'pages.opssight.dashboard')
        ->name('incidents.show');

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
