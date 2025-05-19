<?php

use App\Http\Controllers\App\AppCarController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| App API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register App API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('app')->group(function () {
    // Featured cars for homepage
    Route::get('cars/featured', [AppCarController::class, 'featured']);
    
    // Public car listings (to be implemented later)
    Route::get('cars', [AppCarController::class, 'index']);
    Route::get('cars/{car}', [AppCarController::class, 'show']);
});
