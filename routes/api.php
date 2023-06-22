<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoListController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('todo-list', TodoListController::class);

    Route::apiResource('todo-list.task', TaskController::class)
        ->except(['show'])
        ->shallow();

});

Route::post('/register', \App\Http\Controllers\Auth\RegisterController::class)->name('user.register');
Route::post('/login', \App\Http\Controllers\Auth\LoginController::class)->name('user.login');
