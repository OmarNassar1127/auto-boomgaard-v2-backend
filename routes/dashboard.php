<?php

use App\Http\Controllers\Dashboard\DashboardCarController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('dashboard')->group(function () {
    // Car management
    Route::apiResource('cars', DashboardCarController::class);
    
    // Image management for cars
    Route::post('cars/{car}/images', [DashboardCarController::class, 'uploadImages']);
    Route::patch('cars/{car}/images/{media}/main', [DashboardCarController::class, 'setMainImage']);
    Route::delete('cars/{car}/images/{media}', [DashboardCarController::class, 'deleteImage']);
    
    // Status management
    Route::patch('cars/{car}/publish', [DashboardCarController::class, 'togglePublishStatus']);
    Route::patch('cars/{car}/vehicle-status', [DashboardCarController::class, 'updateVehicleStatus']);
});
