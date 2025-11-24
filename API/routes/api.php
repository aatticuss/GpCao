<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\TechnologyController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    // logout
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // usuário
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);

    // tecnologias do usuário
    Route::put('/user/technologies', [TechnologyController::class, 'sync']);

    // exercícios
    Route::get('/exercises', [ExerciseController::class, 'index']);
    Route::post('/exercises', [ExerciseController::class, 'store']);
    Route::get('/exercises/{exercise}', [ExerciseController::class, 'show']);
    Route::post('/exercises/{exercise}/answer', [ExerciseController::class, 'answer']);
});
