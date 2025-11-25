<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');

Route::prefix('profile')->group(function () {
    Route::get('/', function () {
        return view('user.profile');
    })->name('user.profile');

    Route::get('/edit', function () {
        return view('user.edit');
    })->name('user.profile.edit');
});

// Exercícios
Route::prefix('exercises')->group(function () {
    Route::get('/', function () {
        return view('exercises.list');
    })->name('exercicios.index');

    Route::get('/create', function () {
        return view('exercises.create');
    })->name('exercicios.store');

    Route::get('/{id}', function ($id) {
        return view('exercises.show', ['exerciseId' => $id]);
    })->name('exercicios.show');

    Route::get('/{id}/answer', function ($id) {
        return view('exercicios.show', ['exerciseId' => $id]);
    })->name('exercicios.responder');
});

Route::get('/', function () {
    return redirect()->route('login');
});