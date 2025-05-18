<?php

use App\Http\Controllers\Dashboard\DashboardCarController;
use App\Http\Controllers\Dashboard\DashboardUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'active.user'])->prefix('dashboard')->group(function () {
    // Car management - explicit routes
    Route::get('cars', [DashboardCarController::class, 'index']);
    Route::post('cars', [DashboardCarController::class, 'store']);
    Route::get('cars/{car}', [DashboardCarController::class, 'show']);
    Route::put('cars/{car}', [DashboardCarController::class, 'update']);
    Route::delete('cars/{car}', [DashboardCarController::class, 'destroy']);
    
    // Image management for cars
    Route::post('cars/{car}/images', [DashboardCarController::class, 'uploadImages']);
    Route::patch('cars/{car}/images/{media}/main', [DashboardCarController::class, 'setMainImage']);
    Route::delete('cars/{car}/images/{media}', [DashboardCarController::class, 'deleteImage']);
    
    // Status management
    Route::patch('cars/{car}/publish', [DashboardCarController::class, 'togglePublishStatus']);
    Route::patch('cars/{car}/vehicle-status', [DashboardCarController::class, 'updateVehicleStatus']);
    
    // User management
    Route::get('users', [DashboardUserController::class, 'index']);
    Route::post('users', [DashboardUserController::class, 'store']);
    Route::get('users/{user}', [DashboardUserController::class, 'show']);
    Route::put('users/{user}', [DashboardUserController::class, 'update']);
    Route::delete('users/{user}', [DashboardUserController::class, 'destroy']);
    
    // User status management
    Route::patch('users/{user}/activate', [DashboardUserController::class, 'activate']);
    Route::patch('users/{user}/deactivate', [DashboardUserController::class, 'deactivate']);
});
