<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\TechnologyController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {  
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);

    Route::put('/user/technologies', [TechnologyController::class, 'sync']);

    Route::get('/exercises', [ExerciseController::class, 'index']);
    Route::post('/exercises', [ExerciseController::class, 'store']);
    Route::get('/exercises/{exercicio}', [ExerciseController::class, 'show']);
    Route::post('/exercises/{exercicio}/answer', [ExerciseController::class, 'answer']);
});
