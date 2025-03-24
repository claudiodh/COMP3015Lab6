<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

// Optional: this is the default route Laravel includes for authenticated users
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Article API Routes
Route::get('/articles', [ArticleController::class, 'index']);        // Get latest 15
Route::get('/articles/{id}', [ArticleController::class, 'show']);    // Get one + increment views
Route::post('/articles', [ArticleController::class, 'store']);       // Create new
Route::put('/articles/{id}', [ArticleController::class, 'update']);  // Update existing
Route::delete('/articles/{id}', [ArticleController::class, 'destroy']); // Delete
