<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TelemetryController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Routes for IoT telemetry and dashboard data APIs.
| All routes are prefixed with /api automatically.
|
*/

// ─── IoT Telemetry (ESP32 / Simulator) ──────────────────────
Route::post('/telemetry', [TelemetryController::class, 'store']);

// ─── Dashboard Data (Frontend PWA) ──────────────────────────
Route::prefix('enclosures/{id}')->group(function () {
    Route::get('/latest',    [DashboardController::class, 'latest']);
    Route::get('/history',   [DashboardController::class, 'history']);
    Route::get('/dashboard', [DashboardController::class, 'dashboard']);
    Route::get('/analytics', [DashboardController::class, 'analytics']);
    Route::get('/stability', [DashboardController::class, 'stability']);
    
    // Update enclosure (name, settings, etc)
    Route::put('/', [\App\Http\Controllers\Api\EnclosureController::class, 'update']);
});
