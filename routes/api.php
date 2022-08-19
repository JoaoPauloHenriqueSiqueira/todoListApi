<?php

use App\Http\Controllers\TodoListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('todo-list', TodoListController::class);
});

Route::post('/register', \App\Http\Controllers\Auth\RegisterController::class)->name('user.register');
Route::post('/login', \App\Http\Controllers\Auth\LoginController::class)->name('user.login');
